# Backend Django - Sistema Jfactur (PSE)

Backend REST con Django + DRF + JWT, conectado a PostgreSQL **"Sistema Jfactur"**.  
**Multi-rubro**: sirve para cualquier tipo de negocio (abarrotes, ferretería, farmacia, telecom, etc.). Cada empresa puede definir su `tipo_negocio` y usar productos/categorías libremente.

## Requisitos

- Python 3.10+
- PostgreSQL en `localhost:5434` con base de datos **"Sistema Jfactur"** creada.

**Dependencias Python:** todas están en `requirements.txt`. Cualquiera puede instalarlas con:
```bash
pip install -r requirements.txt
```

## Configuración

1. Copiar variables de entorno:
   ```bash
   copy .env.example .env
   ```
2. Editar `.env` y configurar al menos:
   - `DB_PASSWORD` (contraseña de PostgreSQL)
   - `SECRET_KEY` (clave secreta Django)

3. Entorno virtual e instalación:
   ```bash
   python -m venv venv
   venv\Scripts\Activate.ps1   # Windows PowerShell
   pip install -r requirements.txt
   ```

4. Migraciones:
   ```bash
   python manage.py migrate
   ```

5. Cargar datos de prueba para que la base no esté vacía (recomendado):
   ```bash
   python manage.py load_sample_data
   ```
   Inserta datos al estilo **demodb (2).sql**: empresa demo, usuario **rudeus@jfactur.local** / **rudeus123**, varios clientes, proveedor, categoría, productos (ABONO 50KG, ALIMENT CAT 20KG, SIM REGALO, BITEL ILIMITADO, etc.), series F001/B001, 3 facturas de ejemplo y stock por producto.

## Ejecutar

```bash
python manage.py runserver
```

URL base: **http://localhost:8000**

## Pruebas con SUNAT (homologación)

SUNAT ofrece un **usuario de prueba** para el servicio BETA (homologación), sin necesidad de certificado registrado:

| Dato        | Valor    |
|------------|----------|
| **Usuario SOL** | `MODDATOS` |
| **Clave SOL**   | `MODDATOS` |
| **Usuario enviado** | `[RUC]MODDATOS` (ej. `20100000001MODDATOS`) |

Con `load_sample_data` la empresa demo ya tiene `usuario_sol = MODDATOS`. Para probar envío de boleta a SUNAT beta:

```bash
# Verificar que SUNAT responde
python manage.py send_boleta_sunat --solo-conexion

# Enviar una boleta (necesitas un certificado .pfx para firmar el XML)
python manage.py send_boleta_sunat --clave-sol=MODDATOS
```

Referencia: [Pautas servicio BETA - SUNAT](https://orientacion.sunat.gob.pe/12-pautas-servicio-beta).

## APIs REST (Postman)

### Autenticación (JWT)

- **POST** `/api/v1/auth/token/`  
  Body (JSON):
  ```json
  {
  "email": "rudeus@jfactur.local",
  "password": "rudeus123"
  }
  ```
  Respuesta: `{ "access": "...", "refresh": "..." }`

- **POST** `/api/v1/auth/token/refresh/`  
  Body: `{ "refresh": "<refresh_token>" }`  
  Respuesta: `{ "access": "..." }`

En Postman, en las peticiones que requieran auth: **Authorization** → Type **Bearer Token** → Token: valor de `access`.

---

### Core

| Método | URL | Descripción |
|--------|-----|-------------|
| GET | `/api/v1/companies/` | Lista empresas (filtrada por company del usuario) |
| GET | `/api/v1/companies/{id}/` | Detalle empresa |
| GET | `/api/v1/users/me/` | Usuario actual |
| GET | `/api/v1/dni-lookup/?dni=12345678` | Consulta nombre por DNI. Configure `DNI_LOOKUP_API_URL` en `.env` para RENIEC/proveedor. Devuelve: `nombres`, `apellido_paterno`, `apellido_materno`, `razon_social`. |

### Catálogo

| Método | URL | Descripción |
|--------|-----|-------------|
| GET / POST | `/api/v1/customers/` | Lista / Crear clientes |
| GET / PUT / PATCH | `/api/v1/customers/{id}/` | Detalle / Actualizar cliente |
| GET / POST | `/api/v1/suppliers/` | Lista / Crear proveedores |
| GET / PUT / PATCH | `/api/v1/suppliers/{id}/` | Detalle / Actualizar proveedor |
| GET / POST | `/api/v1/categories/` | Lista / Crear categorías |
| GET / PUT / PATCH | `/api/v1/categories/{id}/` | Detalle / Actualizar categoría |
| GET / POST | `/api/v1/products/` | Lista / Crear productos |
| GET / PUT / PATCH | `/api/v1/products/{id}/` | Detalle / Actualizar producto |

### Inventario

| Método | URL | Descripción |
|--------|-----|-------------|
| GET / POST | `/api/v1/warehouses/` | Lista / Crear almacenes |
| GET / PUT / PATCH | `/api/v1/warehouses/{id}/` | Detalle (incluye `locations`) |
| GET | `/api/v1/stock-quants/` | Stock (query: `?product_id=`, `?warehouse_id=`) |

### Facturación

| Método | URL | Descripción |
|--------|-----|-------------|
| GET | `/api/v1/invoice-series/` | Series de factura (por company) |
| GET / POST | `/api/v1/invoices/` | Lista / Crear facturas |
| GET / PUT / PATCH | `/api/v1/invoices/{id}/` | Detalle / Actualizar factura |

### Ejemplo body para crear factura (POST /api/v1/invoices/)

```json
{
  "tipo_documento": "01",
  "serie": "F001",
  "fecha_emision": "2025-02-18",
  "customer": "00000000-0000-0000-0000-000000000005",
  "cliente_tipo_documento": "6",
  "cliente_numero_documento": "20100000002",
  "cliente_razon_social": "Cliente Demo SAC",
  "cliente_direccion": "Calle Cliente 456",
  "subtotal": "8.47",
  "igv_total": "1.53",
  "total": "10.00",
  "status": "draft",
  "warehouse": "00000000-0000-0000-0000-00000000000a",
  "invoice_lines": [
    {
      "product": "00000000-0000-0000-0000-000000000008",
      "descripcion": "Producto Uno",
      "cantidad": "1",
      "valor_unitario": "8.47",
      "valor_venta": "8.47",
      "codigo_tipo_afectacion": "10",
      "igv_monto": "1.53",
      "importe_total": "10.00"
    }
  ]
}
```

El número de factura se asigna de forma atómica desde la serie indicada (`tipo_documento` + `serie`).

### Consulta nombre por DNI

Para saber el nombre de una persona según su DNI (clientes con documento tipo DNI, facturas, etc.):

1. **GET** `/api/v1/dni-lookup/?dni=12345678` (requiere token JWT).
2. Respuesta si no hay API configurada: `message` indicando que configure `DNI_LOOKUP_API_URL`.
3. En `.env` agregue la URL de su proveedor (RENIEC o API de terceros), por ejemplo:  
   `DNI_LOOKUP_API_URL=https://su-api.com/consulta?dni=`
4. La API debe devolver JSON con al menos `nombres`, `apellido_paterno`, `apellido_materno` (o equivalente). El backend normaliza y devuelve también `razon_social` (nombre completo para facturas).
