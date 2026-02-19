"""
Consulta de nombre por DNI (RENIEC o proveedor externo).
El sistema puede integrarse con cualquier API que devuelva datos de persona por DNI.
Configure DNI_LOOKUP_API_URL en .env (ej: https://api-ejemplo.com/consulta-dni?dni=).
"""
import requests
from django.conf import settings


def consultar_dni(dni):
    """
    Consulta nombre/apellidos por DNI.
    Si DNI_LOOKUP_API_URL está configurado, llama a esa API.
    La API debe devolver JSON con al menos: nombres, apellido_paterno, apellido_materno
    (o nombres compuestos en un solo campo).
    Returns: dict con dni, nombres, apellido_paterno, apellido_materno, razon_social
    """
    dni = (dni or "").strip()
    if not dni or not dni.isdigit() or len(dni) != 8:
        return {"error": "DNI debe tener 8 dígitos", "dni": dni}

    api_url = getattr(settings, "DNI_LOOKUP_API_URL", None) or ""
    if not api_url:
        return {
            "dni": dni,
            "message": "Consulta DNI no configurada. Configure DNI_LOOKUP_API_URL en .env para integrar con RENIEC o su proveedor.",
            "nombres": "",
            "apellido_paterno": "",
            "apellido_materno": "",
            "razon_social": "",
        }

    try:
        url = api_url.rstrip("/")
        if "?" in url:
            r = requests.get(f"{url}&dni={dni}", timeout=10)
        else:
            r = requests.get(f"{url}?dni={dni}", timeout=10)
        r.raise_for_status()
        data = r.json()

        # Normalizar respuestas típicas (cada API puede usar nombres distintos)
        nombres = (
            data.get("nombres")
            or data.get("nombre")
            or data.get("nombres_completos")
            or ""
        )
        apellido_paterno = data.get("apellido_paterno") or data.get("apellidoPaterno") or ""
        apellido_materno = data.get("apellido_materno") or data.get("apellidoMaterno") or ""
        razon_social = (
            data.get("razon_social")
            or data.get("nombre_completo")
            or f"{apellido_paterno} {apellido_materno} {nombres}".strip()
        )

        return {
            "dni": dni,
            "nombres": nombres,
            "apellido_paterno": apellido_paterno,
            "apellido_materno": apellido_materno,
            "razon_social": razon_social.strip(),
        }
    except requests.RequestException as e:
        return {
            "dni": dni,
            "error": f"Error al consultar API: {str(e)}",
            "nombres": "",
            "apellido_paterno": "",
            "apellido_materno": "",
            "razon_social": "",
        }
    except (ValueError, KeyError) as e:
        return {
            "dni": dni,
            "error": f"Respuesta de API no válida: {str(e)}",
            "nombres": "",
            "apellido_paterno": "",
            "apellido_materno": "",
            "razon_social": "",
        }
