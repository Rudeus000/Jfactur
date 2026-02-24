"""
Integración SUNAT (Jfactur): XML UBL 2.1, firma digital, envío SOAP sendBill, recepción CDR.
Modelo e IGV según la empresa (Company.igv_porcentaje, codigo_tipo_afectacion por línea).
"""
import base64
import os
import re
import zipfile

import requests
from django.conf import settings
from signxml import XMLSigner
from lxml import etree
from cryptography.hazmat.primitives.serialization import pkcs12
from cryptography import x509


# Catálogo SUNAT: tipo afectación IGV (catálogo 07) -> TaxCategory ID, Percent, Name
# 10=Gravado, 20=Exonerado, 30=Inafecto, 40=Gratuito
AFECTACION_IGV = {
    '10': ('S', '1000', '18', 'IGV', 'VAT'),   # Gravado
    '20': ('E', '9997', '0', 'EXO', 'VAT'),    # Exonerado
    '30': ('O', '9998', '0', 'INA', 'FRE'),    # Inafecto
    '40': ('Z', '9996', '0', 'GRA', 'FRE'),    # Gratuito
}


def _clean_for_xml(text):
    if text is None:
        return ''
    s = str(text).strip()
    # Caracteres no válidos en XML 1.0
    s = re.sub(r'[\x00-\x08\x0b\x0c\x0e-\x1f]', '', s)
    return s


def build_ubl_invoice_xml(company, invoice):
    """
    Construye XML UBL 2.1 Invoice (Jfactur).
    Usa Company para emisor, IGV y establecimiento; Invoice e InvoiceLine para datos.
    """
    nro_comprobante = f"{invoice.serie}-{invoice.numero}"
    fecha_doc = invoice.fecha_emision.strftime('%Y-%m-%d')
    cod_tipo_doc = invoice.tipo_documento  # 01 Factura, 03 Boleta
    cod_moneda = 'PEN'
    igv_pct = float(company.igv_porcentaje or 18)
    tipo_doc_empresa = '6'  # RUC
    tipo_doc_cliente = invoice.cliente_tipo_documento or '6'
    codigo_sunat = company.codigo_sunat or '0000'
    ubigeo_empresa = company.ubigeo or '150101'
    dir_empresa = _clean_for_xml(company.domicilio_fiscal or company.departamento or '')
    dep_empresa = _clean_for_xml(company.departamento or 'LIMA')
    prov_empresa = _clean_for_xml(company.provincia or 'LIMA')
    dist_empresa = _clean_for_xml(company.distrito or 'LIMA')
    cod_pais_empresa = company.codigo_pais or 'PE'
    nom_comercial = _clean_for_xml(company.nombre_comercial or company.razon_social)
    razon_social_empresa = _clean_for_xml(company.razon_social)
    razon_social_cliente = _clean_for_xml(invoice.cliente_razon_social or '')
    direccion_cliente = _clean_for_xml(invoice.cliente_direccion or '')
    ubigeo_cliente = '150101'  # Por defecto si no está en Customer

    # Totales
    subtotal = float(invoice.subtotal)
    total_igv = float(invoice.igv_total)
    total = float(invoice.total)

    ns = {
        '': 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2',
        'cac': 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2',
        'cbc': 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2',
        'ext': 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2',
    }
    def tag(name):
        if ':' in name:
            return '{%s}%s' % (ns[name.split(':')[0]], name.split(':')[1])
        return '{%s}%s' % (ns[''], name)

    root = etree.Element(tag('Invoice'), nsmap=ns)
    etree.register_namespace('cac', ns['cac'])
    etree.register_namespace('cbc', ns['cbc'])
    etree.register_namespace('ext', ns['ext'])

    def add(parent, name, text=None, **attrs):
        el = etree.SubElement(parent, tag(name), **attrs)
        if text is not None:
            el.text = str(text)
        return el

    # UBLExtensions
    ext = etree.SubElement(root, tag('ext:UBLExtensions'))
    ext_ext = etree.SubElement(ext, tag('ext:UBLExtension'))
    ext_content = etree.SubElement(ext_ext, tag('ext:ExtensionContent'))
    # UBLVersionID, CustomizationID
    add(root, 'cbc:UBLVersionID', '2.1')
    add(root, 'cbc:CustomizationID', '2.0')
    add(root, 'cbc:ID', nro_comprobante)
    add(root, 'cbc:IssueDate', fecha_doc)
    add(root, 'cbc:IssueTime', '00:00:00')
    add(root, 'cbc:DueDate', fecha_doc)
    add(root, 'cbc:InvoiceTypeCode', cod_tipo_doc)
    add(root, 'cbc:DocumentCurrencyCode', cod_moneda)
    add(root, 'cbc:LineCountNumeric', str(invoice.invoice_lines.count()))

    # Signature (placeholder - se firma después)
    sig = etree.SubElement(root, tag('cac:Signature'))
    add(sig, 'cbc:ID', nro_comprobante)
    sig_party = etree.SubElement(sig, tag('cac:SignatoryParty'))
    add(sig_party, 'cac:PartyIdentification/cbc:ID', company.ruc)
    add(sig_party, 'cac:PartyName/cbc:Name', razon_social_empresa)
    sig_attach = etree.SubElement(sig, tag('cac:DigitalSignatureAttachment'))
    add(sig_attach, 'cac:ExternalReference/cbc:URI', '#' + nro_comprobante)

    # AccountingSupplierParty (emisor)
    supplier = etree.SubElement(root, tag('cac:AccountingSupplierParty'))
    party_s = etree.SubElement(supplier, tag('cac:Party'))
    add(party_s, 'cac:PartyIdentification/cbc:ID', company.ruc)
    add(party_s, 'cac:PartyName/cbc:Name', nom_comercial)
    party_tax = etree.SubElement(party_s, tag('cac:PartyTaxScheme'))
    add(party_tax, 'cbc:RegistrationName', razon_social_empresa)
    add(party_tax, 'cbc:CompanyID', company.ruc)
    add(party_tax, 'cac:TaxScheme/cbc:ID', company.ruc)
    legal = etree.SubElement(party_s, tag('cac:PartyLegalEntity'))
    add(legal, 'cbc:RegistrationName', razon_social_empresa)
    addr = etree.SubElement(legal, tag('cac:RegistrationAddress'))
    add(addr, 'cbc:ID', ubigeo_empresa)
    add(addr, 'cbc:AddressTypeCode', codigo_sunat)
    add(addr, 'cbc:CityName', dep_empresa)
    add(addr, 'cbc:CountrySubentity', prov_empresa)
    add(addr, 'cbc:District', dist_empresa)
    add(addr, 'cac:AddressLine/cbc:Line', dir_empresa)
    add(addr, 'cac:Country/cbc:IdentificationCode', cod_pais_empresa)

    # AccountingCustomerParty (cliente)
    customer = etree.SubElement(root, tag('cac:AccountingCustomerParty'))
    party_c = etree.SubElement(customer, tag('cac:Party'))
    add(party_c, 'cac:PartyIdentification/cbc:ID', invoice.cliente_numero_documento)
    add(party_c, 'cac:PartyName/cbc:Name', razon_social_cliente)
    party_tax_c = etree.SubElement(party_c, tag('cac:PartyTaxScheme'))
    add(party_tax_c, 'cbc:RegistrationName', razon_social_cliente)
    add(party_tax_c, 'cbc:CompanyID', invoice.cliente_numero_documento)
    add(party_tax_c, 'cac:TaxScheme/cbc:ID', invoice.cliente_numero_documento)
    legal_c = etree.SubElement(party_c, tag('cac:PartyLegalEntity'))
    add(legal_c, 'cbc:RegistrationName', razon_social_cliente)
    addr_c = etree.SubElement(legal_c, tag('cac:RegistrationAddress'))
    add(addr_c, 'cbc:ID', ubigeo_cliente)
    add(addr_c, 'cac:AddressLine/cbc:Line', direccion_cliente)
    add(addr_c, 'cac:Country/cbc:IdentificationCode', 'PE')

    # PaymentTerms (Contado)
    pay = etree.SubElement(root, tag('cac:PaymentTerms'))
    add(pay, 'cbc:ID', 'FormaPago')
    add(pay, 'cbc:PaymentMeansID', 'Contado')

    # TaxTotal
    tax_total = etree.SubElement(root, tag('cac:TaxTotal'))
    add(tax_total, 'cbc:TaxAmount', f'{total_igv:.2f}')
    tax_sub = etree.SubElement(tax_total, tag('cac:TaxSubtotal'))
    add(tax_sub, 'cbc:TaxableAmount', f'{subtotal:.2f}')
    add(tax_sub, 'cbc:TaxAmount', f'{total_igv:.2f}')
    tax_cat = etree.SubElement(tax_sub, tag('cac:TaxCategory'))
    add(tax_cat, 'cbc:ID', 'S')
    add(tax_cat, 'cac:TaxScheme/cbc:ID', '1000')
    add(tax_cat, 'cac:TaxScheme/cbc:Name', 'IGV')
    add(tax_cat, 'cac:TaxScheme/cbc:TaxTypeCode', 'VAT')

    # LegalMonetaryTotal
    monetary = etree.SubElement(root, tag('cac:LegalMonetaryTotal'))
    add(monetary, 'cbc:LineExtensionAmount', f'{subtotal:.2f}')
    add(monetary, 'cbc:TaxInclusiveAmount', f'{total:.2f}')
    add(monetary, 'cbc:AllowanceTotalAmount', '0.00')
    add(monetary, 'cbc:ChargeTotalAmount', '0.00')
    add(monetary, 'cbc:PayableAmount', f'{total:.2f}')

    # InvoiceLine(s)
    for line in invoice.invoice_lines.all().order_by('line_number'):
        cod_afect = line.codigo_tipo_afectacion or '10'
        cat_id, tipo_igv, percent, name_igv, tax_type = AFECTACION_IGV.get(cod_afect, AFECTACION_IGV['10'])
        valor_venta = float(line.valor_venta)
        igv_monto = float(line.igv_monto)
        importe_total = float(line.importe_total)
        precio_unit = float(line.valor_unitario)
        cantidad = float(line.cantidad)
        # Precio tipo 01 = Unitario
        price_type = '01'

        inv_line = etree.SubElement(root, tag('cac:InvoiceLine'))
        add(inv_line, 'cbc:ID', str(line.line_number))
        add(inv_line, 'cbc:InvoicedQuantity', f'{cantidad:.4f}', unitCode=line.product.unidad_medida if line.product else 'NIU')
        add(inv_line, 'cbc:LineExtensionAmount', f'{valor_venta:.2f}')
        # PricingReference
        pr = etree.SubElement(inv_line, tag('cac:PricingReference'))
        alt = etree.SubElement(pr, tag('cac:AlternativeConditionPrice'))
        add(alt, 'cbc:PriceAmount', f'{precio_unit + (igv_monto / cantidad) if cantidad else 0:.2f}')
        add(alt, 'cbc:PriceTypeCode', price_type)
        # TaxTotal (línea)
        tax_line = etree.SubElement(inv_line, tag('cac:TaxTotal'))
        add(tax_line, 'cbc:TaxAmount', f'{igv_monto:.2f}')
        tax_sub_line = etree.SubElement(tax_line, tag('cac:TaxSubtotal'))
        add(tax_sub_line, 'cbc:TaxableAmount', f'{valor_venta:.2f}')
        add(tax_sub_line, 'cbc:TaxAmount', f'{igv_monto:.2f}')
        tax_cat_line = etree.SubElement(tax_sub_line, tag('cac:TaxCategory'))
        add(tax_cat_line, 'cbc:ID', cat_id)
        add(tax_cat_line, 'cbc:Percent', percent)
        add(tax_cat_line, 'cbc:TaxExemptionReasonCode', cod_afect)
        add(tax_cat_line, 'cac:TaxScheme/cbc:ID', tipo_igv)
        add(tax_cat_line, 'cac:TaxScheme/cbc:Name', name_igv)
        add(tax_cat_line, 'cac:TaxScheme/cbc:TaxTypeCode', tax_type)
        # Item
        item = etree.SubElement(inv_line, tag('cac:Item'))
        add(item, 'cbc:Description', _clean_for_xml(line.descripcion))
        add(item, 'cac:SellersItemIdentification/cbc:ID', _clean_for_xml(line.product.sku if line.product else ''))
        add(item, 'cac:CommodityClassification/cbc:ItemClassificationCode', '10000000')  # UNSPSC genérico
        # Price
        add(inv_line, 'cac:Price/cbc:PriceAmount', f'{precio_unit:.4f}')

    return etree.tostring(
        root,
        encoding='unicode',
        xml_declaration=True,
        default_namespace=ns[''],
        standalone=False
    ).encode('utf-8')


def sign_xml_with_pfx(xml_bytes, pfx_path, pfx_password):
    """
    Firma el XML con el certificado PFX (Jfactur).
    """
    with open(pfx_path, 'rb') as f:
        pfx_data = f.read()
    key, cert, _ = pkcs12.load_key_and_certificates(
        pfx_data, pfx_password.encode() if isinstance(pfx_password, str) else pfx_password
    )
    root = etree.fromstring(xml_bytes)
    id_elem = root.find(".//{urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2}ID")
    if id_elem is not None and id_elem.text:
        ref_uri = "#" + id_elem.text
    else:
        ref_uri = "#Invoice"
    signer = XMLSigner(
        c14n_algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315",
        signature_algorithm="rsa-sha1",
        digest_algorithm="sha1",
    )
    signed = signer.sign(
        root,
        key=key,
        cert=cert,
        reference_uri=ref_uri,
        always_add_key_value=True,
    )
    return etree.tostring(
        signed,
        encoding='utf-8',
        xml_declaration=True,
        standalone=False
    )


def send_bill_to_sunat(ruc, usuario_sol, clave_sol, xml_path, nombre_archivo, ruta_ws):
    """
    Envía el comprobante a SUNAT vía SOAP sendBill (Jfactur).
    Retorna: dict respuesta, cod_sunat, mensaje, hash_cdr, cdr_path (si ok).
    """
    zip_path = xml_path + '.ZIP' if not xml_path.upper().endswith('.ZIP') else xml_path
    xml_file = nombre_archivo + '.XML'
    if not zip_path.endswith('.ZIP'):
        with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
            zf.write(xml_path, xml_file)
    else:
        with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
            with open(xml_path, 'rb') as f:
                zf.writestr(xml_file, f.read())

    with open(zip_path, 'rb') as f:
        content_b64 = base64.b64encode(f.read()).decode('ascii')

    username = ruc + usuario_sol
    soap_body = f'''<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
<soapenv:Header>
<wsse:Security>
<wsse:UsernameToken>
<wsse:Username>{username}</wsse:Username>
<wsse:Password>{clave_sol}</wsse:Password>
</wsse:UsernameToken>
</wsse:Security>
</soapenv:Header>
<soapenv:Body>
<ser:sendBill>
<fileName>{nombre_archivo}.ZIP</fileName>
<contentFile>{content_b64}</contentFile>
</ser:sendBill>
</soapenv:Body>
</soapenv:Envelope>'''

    headers = {
        'Content-Type': 'text/xml; charset="utf-8"',
        'Accept': 'text/xml',
        'SOAPAction': '',
    }
    try:
        r = requests.post(ruta_ws, data=soap_body.encode('utf-8'), headers=headers, timeout=30)
    except requests.RequestException as e:
        return {'respuesta': 'error', 'cod_sunat': '0000', 'mensaje': str(e), 'hash_cdr': ''}

    if r.status_code != 200:
        return {'respuesta': 'error', 'cod_sunat': '0000', 'mensaje': r.text or f'HTTP {r.status_code}', 'hash_cdr': ''}

    # Parsear respuesta: applicationResponse (CDR en base64) o fault
    doc = etree.fromstring(r.content)
    ns_soap = {'soap': 'http://schemas.xmlsoap.org/soap/envelope/', 'ser': 'http://service.sunat.gob.pe'}
    app_response = doc.find('.//ser:applicationResponse', namespaces=ns_soap)
    if app_response is not None and app_response.text:
        cdr_zip_b64 = app_response.text
        cdr_zip_bytes = base64.b64decode(cdr_zip_b64)
        ruta_cdr = os.path.dirname(xml_path) + os.sep
        cdr_zip_path = ruta_cdr + 'R-' + nombre_archivo + '.ZIP'
        with open(cdr_zip_path, 'wb') as f:
            f.write(cdr_zip_bytes)
        cdr_xml_path = None
        with zipfile.ZipFile(cdr_zip_path, 'r') as zf:
            for name in zf.namelist():
                if name.upper().endswith('.XML'):
                    zf.extract(name, ruta_cdr)
                    cdr_xml_path = os.path.join(ruta_cdr, name)
                    break
        if not cdr_xml_path or not os.path.exists(cdr_xml_path):
            for n in os.listdir(ruta_cdr):
                if n.upper().startswith('R-') and n.upper().endswith('.XML'):
                    cdr_xml_path = os.path.join(ruta_cdr, n)
                    break
            if not cdr_xml_path:
                cdr_xml_path = os.path.join(ruta_cdr, 'R-' + nombre_archivo + '.XML')
        try:
            cdr_doc = etree.parse(cdr_xml_path)
            cod = cdr_doc.find('.//{*}ResponseCode')
            desc = cdr_doc.find('.//{*}Description')
            digest = cdr_doc.find('.//{*}DigestValue')
            cod_sunat = cod.text if cod is not None else ''
            mensaje = desc.text if desc is not None else ''
            hash_cdr = digest.text if digest is not None else ''
        except Exception:
            cod_sunat = '0'
            mensaje = 'CDR leído con error'
            hash_cdr = ''
        if os.path.exists(cdr_zip_path):
            try:
                os.remove(cdr_zip_path)
            except OSError:
                pass
        return {
            'respuesta': 'ok',
            'cod_sunat': cod_sunat,
            'mensaje': mensaje,
            'hash_cdr': hash_cdr,
            'cdr_path': cdr_xml_path,
        }
    # Error SOAP
    fault_code = doc.find('.//{http://schemas.xmlsoap.org/soap/envelope/}Body//{*}faultcode')
    fault_string = doc.find('.//{http://schemas.xmlsoap.org/soap/envelope/}Body//{*}faultstring')
    fc = fault_code.text if fault_code is not None else 'SOAP'
    fs = fault_string.text if fault_string is not None else r.text[:500] if r.text else 'Error desconocido'
    return {'respuesta': 'error', 'cod_sunat': fc, 'mensaje': fs, 'hash_cdr': ''}


def get_sunat_ws_url():
    if getattr(settings, 'SUNAT_USE_BETA', True):
        return getattr(settings, 'SUNAT_WS_BILL_BETA', '')
    return getattr(settings, 'SUNAT_WS_BILL_PROD', '')


def send_invoice_to_sunat(invoice, clave_sol, cert_password=None):
    """
    Envía una factura/boleta a SUNAT: genera XML, firma, envía y actualiza el estado del invoice.
    Retorna dict: respuesta, cod_sunat, mensaje, (cdr_path si ok).
    Requiere: invoice con company, certificado activo en company, usuario_sol configurado.
    """
    import tempfile
    company = invoice.company
    cert_password = cert_password or clave_sol
    cert = company.digital_certificates.filter(is_active=True).first()
    if not cert or not cert.pfx_file_path or not os.path.isfile(cert.pfx_file_path):
        return {'respuesta': 'error', 'cod_sunat': '', 'mensaje': 'No hay certificado digital activo con archivo PFX válido para esta empresa.'}
    usuario_sol = (company.usuario_sol or '').strip()
    if not usuario_sol:
        return {'respuesta': 'error', 'cod_sunat': '', 'mensaje': 'La empresa no tiene configurado usuario SOL (usuario_sol).'}
    ruta_ws = get_sunat_ws_url()
    if not ruta_ws:
        return {'respuesta': 'error', 'cod_sunat': '', 'mensaje': 'No está configurada la URL del servicio SUNAT.'}
    nombre_archivo = f"{invoice.serie}-{invoice.numero}"
    try:
        xml_bytes = build_ubl_invoice_xml(company, invoice)
    except Exception as e:
        return {'respuesta': 'error', 'cod_sunat': '', 'mensaje': f'Error al generar XML UBL: {e}'}
    try:
        signed_bytes = sign_xml_with_pfx(xml_bytes, cert.pfx_file_path, cert_password)
    except Exception as e:
        return {'respuesta': 'error', 'cod_sunat': '', 'mensaje': f'Error al firmar XML: {e}'}
    with tempfile.NamedTemporaryFile(suffix='.xml', delete=False) as f:
        f.write(signed_bytes)
        xml_path = f.name
    try:
        result = send_bill_to_sunat(
            ruc=company.ruc,
            usuario_sol=usuario_sol,
            clave_sol=clave_sol,
            xml_path=xml_path,
            nombre_archivo=nombre_archivo,
            ruta_ws=ruta_ws,
        )
    finally:
        try:
            os.unlink(xml_path)
        except OSError:
            pass
        zip_path = xml_path + '.ZIP'
        if os.path.isfile(zip_path):
            try:
                os.unlink(zip_path)
            except OSError:
                pass
    invoice.sunat_response_code = result.get('cod_sunat', '')
    invoice.sunat_response_message = result.get('mensaje', '')
    if result.get('respuesta') == 'ok':
        invoice.status = 'accepted' if result.get('cod_sunat') == '0' else 'rejected'
        if result.get('cdr_path') and os.path.isfile(result['cdr_path']):
            invoice.cdr_path = result['cdr_path']
    else:
        invoice.status = 'rejected'
    invoice.save(update_fields=['sunat_response_code', 'sunat_response_message', 'status', 'cdr_path', 'updated_at'])
    return result
