import { Link } from "react-router-dom";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";
import { Button } from "@/components/ui/button";
import { BookOpen, FileText, Warehouse, CreditCard, ShoppingCart, BarChart3, ArrowRight, AlertTriangle } from "lucide-react";

const ComoUsar = () => {
  return (
    <div className="max-w-4xl">
      <div className="erp-page-header mb-6">
        <h1 className="erp-page-title">Cómo usar el sistema</h1>
        <p className="erp-page-subtitle">
          Orden de configuración, casos de uso y flujos típicos para usar Jfactur correctamente.
        </p>
      </div>

      {/* Uso diario */}
      <Card className="mb-6">
        <CardHeader>
          <CardTitle>Uso diario del sistema</CardTitle>
          <CardDescription>
            Flujo habitual una vez todo está configurado.
          </CardDescription>
        </CardHeader>
        <CardContent className="text-sm text-muted-foreground space-y-2">
          <p><strong>1. Iniciar sesión</strong> con tu correo y contraseña. El sistema filtra datos por tu empresa.</p>
          <p><strong>2. Revisar el Dashboard</strong> (inicio): ventas del día, facturas pendientes de envío a SUNAT, total por cobrar y por pagar, y alertas (ej. certificado faltante).</p>
          <p><strong>3. Si trabajas con caja:</strong> abre la caja al inicio del turno; emite facturas y registra cobros vinculados a esa apertura; al cierre, genera el cierre de caja con el resumen y valida.</p>
          <p><strong>4. Facturación:</strong> crea facturas o boletas (cliente, líneas, serie), envía a SUNAT si corresponde, y registra los cobros en Facturación → Cobros.</p>
          <p><strong>5. Compras:</strong> crea órdenes de compra, confírmalas para que actualicen el stock; registra los pagos a proveedores.</p>
          <p><strong>6. Reportes:</strong> libro de ventas, ventas por cliente/producto y cuentas por cobrar/pagar (aged) desde el menú Reportes.</p>
        </CardContent>
      </Card>

      {/* Orden de configuración */}
      <Card className="mb-6">
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <BookOpen className="h-5 w-5" />
            Orden recomendado (primera vez)
          </CardTitle>
          <CardDescription>
            Configura en este orden para evitar errores de “falta de dato”.
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-2 text-sm">
          <ol className="list-decimal list-inside space-y-1.5 text-muted-foreground">
            <li>Empresa y usuarios (ya vienen con datos de prueba o se crean vía admin).</li>
            <li><strong>Catálogo:</strong> Categorías → Productos → Clientes → Proveedores.</li>
            <li><strong>Inventario:</strong> Al menos un <Link to="/inventario/almacenes" className="text-primary underline">almacén</Link> (requisito para series y cajas).</li>
            <li><strong>Contabilidad:</strong> Monedas → Cuentas → Bancos → <Link to="/contabilidad/cajas" className="text-primary underline">Cajas</Link>.</li>
            <li><strong>Facturación:</strong> <Link to="/facturacion/series" className="text-primary underline">Series</Link> de factura/boleta (asociadas a almacén).</li>
            <li>Opcional: Asigna <strong>Mi caja</strong> y <strong>Mi almacén</strong> en el menú superior (barra del header) para que queden por defecto al facturar y cobrar.</li>
            <li>Opcional: <Link to="/contabilidad/aperturas" className="text-primary underline">Apertura de caja</Link> si cobrarás en efectivo y quieres cierre de caja.</li>
          </ol>
          <p className="pt-2 text-muted-foreground">
            Sin almacén no hay series ni cajas por punto de venta. Sin series no puedes emitir facturas.
          </p>
        </CardContent>
      </Card>

      {/* Flujos típicos */}
      <Card className="mb-6">
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <FileText className="h-5 w-5" />
            Flujos típicos de uso
          </CardTitle>
          <CardDescription>
            Sigue estos pasos según lo que quieras hacer.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <Accordion type="single" collapsible className="w-full">
            <AccordionItem value="facturar">
              <AccordionTrigger>Emitir una factura y cobrarla</AccordionTrigger>
              <AccordionContent className="text-sm text-muted-foreground space-y-2">
                <p>1. Ten listos: almacén, serie (F001/B001), <Link to="/catalogo/clientes" className="text-primary underline">clientes</Link> y <Link to="/catalogo/productos" className="text-primary underline">productos</Link>.</p>
                <p>2. Ve a <Link to="/facturacion/facturas" className="text-primary underline">Facturas / Boletas</Link> y crea la factura (tipo, serie, cliente, líneas).</p>
                <p>3. Opcional: envía a SUNAT (requiere certificado y usuario SOL; en homologación usa MODDATOS).</p>
                <p>4. Registra el <Link to="/facturacion/cobros" className="text-primary underline">cobro</Link>: factura, monto, fecha, medio de pago; opcional caja y apertura si es efectivo.</p>
              </AccordionContent>
            </AccordionItem>
            <AccordionItem value="caja">
              <AccordionTrigger>Vender con caja abierta</AccordionTrigger>
              <AccordionContent className="text-sm text-muted-foreground space-y-2">
                <p>1. Crea una <Link to="/contabilidad/cajas" className="text-primary underline">caja</Link> (asociada a almacén si tienes punto de venta).</p>
                <p>2. En el header, asigna <strong>Mi caja</strong> (y opcionalmente <strong>Mi almacén</strong>) para que queden por defecto.</p>
                <p>3. Ve a <Link to="/contabilidad/aperturas" className="text-primary underline">Aperturas de caja</Link> y abre la caja (apertura con monto inicial).</p>
                <p>4. Emite facturas y, al registrar cobros, asocia la apertura de caja.</p>
                <p>5. Al cierre del turno: consulta el resumen de la apertura, crea el cierre de caja con los totales y valida.</p>
              </AccordionContent>
            </AccordionItem>
            <AccordionItem value="comprar">
              <AccordionTrigger>Comprar y que suba el stock</AccordionTrigger>
              <AccordionContent className="text-sm text-muted-foreground space-y-2">
                <p>1. Ten <Link to="/catalogo/proveedores" className="text-primary underline">proveedores</Link>, productos y <Link to="/inventario/almacenes" className="text-primary underline">almacén</Link>.</p>
                <p>2. Crea una <Link to="/compras/ordenes" className="text-primary underline">orden de compra</Link> (borrador) con líneas.</p>
                <p>3. Confirma la compra: el sistema actualiza el <Link to="/inventario/stock" className="text-primary underline">stock</Link> y el <Link to="/inventario/kardex" className="text-primary underline">kardex</Link>.</p>
              </AccordionContent>
            </AccordionItem>
            <AccordionItem value="cotizar">
              <AccordionTrigger>Cotizar y luego facturar</AccordionTrigger>
              <AccordionContent className="text-sm text-muted-foreground space-y-2">
                <p>1. Crea una <Link to="/facturacion/cotizaciones" className="text-primary underline">cotización</Link> con cliente y líneas.</p>
                <p>2. Cuando el cliente acepte, usa <strong>Convertir a factura</strong>: se genera la factura con serie/número.</p>
                <p>3. Envía a SUNAT si aplica y registra los cobros.</p>
              </AccordionContent>
            </AccordionItem>
            <AccordionItem value="traspaso">
              <AccordionTrigger>Traspaso entre almacenes</AccordionTrigger>
              <AccordionContent className="text-sm text-muted-foreground space-y-2">
                <p>1. Necesitas al menos dos <Link to="/inventario/almacenes" className="text-primary underline">almacenes</Link> y <Link to="/inventario/stock" className="text-primary underline">stock</Link> en origen.</p>
                <p>2. Crea el <Link to="/inventario/traspasos" className="text-primary underline">traspaso</Link> (origen, destino, líneas).</p>
                <p>3. Valida el traspaso: se descuenta en origen, se suma en destino y se registra en el kardex.</p>
              </AccordionContent>
            </AccordionItem>
          </Accordion>
        </CardContent>
      </Card>

      {/* Qué hacer antes de cada cosa */}
      <Card className="mb-6">
        <CardHeader>
          <CardTitle>Qué tener listo antes de cada cosa</CardTitle>
          <CardDescription>
            Requisitos por módulo para no encontrarte con errores.
          </CardDescription>
        </CardHeader>
        <CardContent className="text-sm space-y-4">
          <div>
            <h4 className="font-medium flex items-center gap-2 mb-1">
              <FileText className="h-4 w-4" />
              Antes de emitir facturas
            </h4>
            <p className="text-muted-foreground">
              Al menos un almacén, una serie activa (F001/B001), clientes y productos. Para SUNAT: certificado digital y usuario SOL (en homologación: MODDATOS).
            </p>
          </div>
          <div>
            <h4 className="font-medium flex items-center gap-2 mb-1">
              <CreditCard className="h-4 w-4" />
              Antes de cobros y caja
            </h4>
            <p className="text-muted-foreground">
              Cobros: factura existente (no borrador). Caja: crear al menos una caja; en el header puedes asignar <strong>Mi caja</strong>; luego en Contabilidad → Aperturas de caja abre la caja para vincular cobros en efectivo al cierre.
            </p>
          </div>
          <div>
            <h4 className="font-medium flex items-center gap-2 mb-1">
              <ShoppingCart className="h-4 w-4" />
              Antes de compras
            </h4>
            <p className="text-muted-foreground">
              Proveedores cargados. Opcional: almacén para que al confirmar la compra se actualice el stock; bancos si registras pagos por transferencia.
            </p>
          </div>
          <div>
            <h4 className="font-medium flex items-center gap-2 mb-1">
              <Warehouse className="h-4 w-4" />
              Antes de traspasos
            </h4>
            <p className="text-muted-foreground">
              Dos almacenes, stock en origen y productos. El traspaso se valida después; hasta entonces no se mueve el stock.
            </p>
          </div>
        </CardContent>
      </Card>

      {/* Errores frecuentes */}
      <Card className="mb-6">
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-amber-600 dark:text-amber-500">
            <AlertTriangle className="h-5 w-5" />
            Si algo falla, revisa esto
          </CardTitle>
          <CardDescription>
            Mensajes habituales y qué comprobar.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <ul className="text-sm space-y-2 text-muted-foreground">
            <li><strong>“No existe serie activa”:</strong> Crea o activa una serie para ese tipo (01/03) y código (ej. F001), asociada a un almacén.</li>
            <li><strong>No puedo abrir caja:</strong> Debe existir al menos una caja creada.</li>
            <li><strong>No se actualiza el stock:</strong> La compra debe estar confirmada; el traspaso debe estar validado.</li>
            <li><strong>SUNAT rechaza:</strong> RUC, usuario SOL, certificado y datos del comprobante correctos. En beta usar usuario MODDATOS.</li>
            <li><strong>No puedo convertir cotización en factura:</strong> Debe haber una serie activa para el tipo de documento.</li>
          </ul>
        </CardContent>
      </Card>

      {/* Reportes */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <BarChart3 className="h-5 w-5" />
            Reportes
          </CardTitle>
          <CardDescription>
            El <Link to="/" className="text-primary underline">Dashboard</Link> muestra ventas del día, por cobrar, por pagar y alertas. En el menú Reportes tienes libro de ventas, ventas por cliente/producto y cuentas por cobrar/pagar (aged).
          </CardDescription>
        </CardHeader>
        <CardContent>
          <Button asChild variant="outline">
            <Link to="/reportes/libro-ventas" className="flex items-center gap-2">
              Ver Libro de Ventas
              <ArrowRight className="h-4 w-4" />
            </Link>
          </Button>
        </CardContent>
      </Card>
    </div>
  );
};

export default ComoUsar;
