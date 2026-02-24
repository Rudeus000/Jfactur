# Casos de uso y guía de uso del sistema Jfactur

Este documento describe **cómo usar el sistema**, **qué hacer antes de cada cosa** y **qué tener en cuenta** en cada módulo. Sigue el orden recomendado para evitar errores y tener datos consistentes.

---

## 1. Orden recomendado de configuración (primera vez)

El sistema tiene dependencias entre módulos. Configurar en este orden:

```
1. Empresa (ya viene con datos de prueba o se crea vía admin/core)
2. Usuarios y roles
3. Catálogo: Categorías → Productos → Clientes → Proveedores
4. Inventario: Almacenes (al menos uno)
5. Contabilidad: Monedas → Tipos de cuenta → Cuentas → Bancos → Cajas (asociar caja a almacén)
6. Facturación: Series de factura/boleta (asociar serie a almacén)
7. Opcional: Apertura de caja si se cobrará en efectivo y se registrará en caja
```

**Resumen:** Sin **almacén** no puedes crear **series** ni **cajas** vinculadas a punto de venta. Sin **series** no puedes emitir facturas. Sin **clientes** y **productos** no puedes armar facturas ni cotizaciones.

---

## 2. Qué hacer ANTES de cada cosa

### Antes de emitir facturas o boletas

| Requisito | Dónde configurarlo | Por qué |
|-----------|-------------------|--------|
| Al menos un **almacén** activo | Inventario → Almacenes | Cada serie está asociada a un almacén. |
| Al menos una **serie** activa (F001 para factura, B001 para boleta, etc.) | Facturación → Series | El sistema asigna el número siguiente de esa serie. |
| **Clientes** cargados | Catálogo → Clientes | La factura exige cliente (documento, razón social, dirección). |
| **Productos** con precio y unidad | Catálogo → Productos | Las líneas de la factura son productos o descripción manual. |
| Empresa con **RUC y datos fiscales** correctos | Core / Admin | Necesario para el comprobante y envío a SUNAT. |

**Envío a SUNAT (factura electrónica):** Además necesitas certificado digital (.pfx) y **usuario SOL** configurado en la empresa. En homologación (beta) se usa el usuario de prueba **MODDATOS** / clave **MODDATOS**.

---

### Antes de registrar cobros (pagos de clientes)

| Requisito | Dónde | Nota |
|-----------|--------|------|
| Factura existente (no borrador) | Facturación → Facturas | El cobro se asocia a una factura. |
| Opcional: **Caja** y **apertura de caja** | Contabilidad → Cajas, Aperturas | Si el cobro es en efectivo y quieres que quede en un cierre de caja. |

Si no vinculas cobro a una apertura de caja, el cobro queda registrado pero no suma al resumen de esa caja.

---

### Antes de abrir caja (apertura de caja)

| Requisito | Dónde | Nota |
|-----------|--------|------|
| Al menos una **caja** creada | Contabilidad → Cajas | La caja puede estar asociada a un almacén (punto de venta). |
| Usuario con permisos | Core | Solo usuarios de la empresa pueden abrir caja. |

**Cierre de caja:** Se hace sobre una **apertura** abierta (sin cerrar). El sistema puede mostrar un resumen de ventas/cobros de esa apertura para cuadrar montos.

---

### Antes de registrar compras y pagos a proveedores

| Requisito | Dónde | Nota |
|-----------|--------|------|
| **Proveedores** cargados | Catálogo → Proveedores | Cada compra tiene un proveedor. |
| Opcional: **Almacén** | Inventario → Almacenes | Si al confirmar la compra quieres que actualice stock en un almacén. |
| Para pagos: **Banco** o medio de pago | Contabilidad → Bancos | Si el pago es transferencia, puedes asociar el banco. |

**Confirmar compra:** Al confirmar una compra el sistema puede actualizar el **stock** (inventario) de los productos de las líneas. Por eso tiene sentido tener productos y almacén definidos.

---

### Antes de hacer traspasos entre almacenes

| Requisito | Dónde | Nota |
|-----------|--------|------|
| Al menos **dos almacenes** | Inventario → Almacenes | Origen y destino. |
| **Stock** en el almacén de origen | Inventario → Stock | No puedes traspasar más de lo que hay. |
| Productos existentes | Catálogo → Productos | Las líneas del traspaso son productos. |

El traspaso queda en estado **Pendiente** hasta que un usuario con permiso lo **valida**. Al validar, se descuenta del origen y se suma al destino (y se registran movimientos en el kardex).

---

### Antes de registrar gastos

| Requisito | Dónde | Nota |
|-----------|--------|------|
| **Tipo de gasto** (y opcionalmente cuenta contable) | Contabilidad → Tipos de gasto | Cada gasto se clasifica en un tipo. |
| Opcional: **Cuenta contable** | Contabilidad → Plan de cuentas | Para integrar con contabilidad. |
| Opcional: **Moneda** | Contabilidad → Monedas | Por defecto se suele usar PEN. |
| Opcional: **Caja** | Contabilidad → Cajas | Si el gasto sale de una caja. |

---

### Antes de convertir una cotización en factura

| Requisito | Dónde | Nota |
|-----------|--------|------|
| Cotización en estado **Aceptada** (o al menos con datos válidos) | Facturación → Cotizaciones | El sistema usa cliente, líneas y totales de la cotización. |
| **Serie** activa para el tipo de documento (01 factura / 03 boleta) | Facturación → Series | Se asigna número de la serie. |
| **Almacén** (implícito en la serie o se elige) | Inventario | La factura generada se asocia a un almacén. |

---

## 3. Casos de uso por módulo

### 3.1 Catálogo

- **Clientes:** Dar de alta antes de facturar. Necesitas al menos tipo y número de documento, razón social. La dirección es obligatoria para el comprobante electrónico.
- **Proveedores:** Dar de alta antes de cargar compras y pagos a proveedores.
- **Categorías:** Opcional; sirve para agrupar productos. Conviene tener al menos una (ej. “General”) antes de cargar productos.
- **Productos:** Necesarios para facturas, cotizaciones, compras y traspasos. Definir: SKU, nombre, unidad de medida, precio de venta, si afecta IGV, categoría (opcional).

**Qué tener en cuenta:** El **código de tipo de afectación IGV** (ej. 10 = gravado) debe ser el correcto para que los totales y el XML para SUNAT cuadren.

---

### 3.2 Inventario

- **Almacenes:** Crear al menos uno (ej. “Almacén Principal”). Es requisito para series de facturación y para cajas si se asocian a punto de venta.
- **Stock:** Se actualiza al confirmar compras, al validar traspasos y al emitir facturas (si el flujo lo contempla). Revisar en **Kardex** los movimientos.
- **Traspasos:** Crear traspaso (origen, destino, líneas con producto y cantidad). Luego **validar** el traspaso para que el stock se mueva.

**Qué tener en cuenta:** No validar traspasos sin revisar que en origen haya stock suficiente. El kardex es de solo lectura desde la app (historial).

---

### 3.3 Contabilidad

- **Monedas:** Definir al menos la moneda principal (ej. PEN). Útil para gastos y reportes.
- **Plan de cuentas:** Tipos de cuenta y cuentas. Necesario si usas **tipos de gasto** con cuenta asociada o reportes de balance.
- **Bancos:** Para asociar a pagos (cobros de clientes o pagos a proveedores) cuando el medio es transferencia/cuenta bancaria.
- **Cajas:** Una caja por punto de venta. Puede asociarse a un almacén. Sin caja no podrás hacer **aperturas** ni **cierres** de caja.
- **Apertura de caja:** Un usuario abre la caja con un monto inicial (opcional). A partir de ahí se pueden registrar cobros ligados a esa apertura.
- **Cierre de caja:** Se hace sobre una apertura abierta. Se indica el conteo (efectivo, tarjeta, etc.) y, si aplica, validación por otro usuario. Después de cerrar, esa apertura no debe usarse para nuevos cobros.

**Qué tener en cuenta:** No cerrar la caja sin cuadrar con el resumen de ventas/cobros que muestra el sistema para esa apertura.

---

### 3.4 Compras

- **Órdenes de compra:** Crear con proveedor, fecha, líneas (producto, cantidad, precio). Estado **Borrador** hasta confirmar.
- **Confirmar compra:** Pasa a **Confirmada** y, si está implementado, actualiza el **stock** en el almacén de la compra.
- **Pagos a proveedores:** Registrar pagos asociados a una compra (fecha, monto, medio, banco si aplica). Así se lleva el saldo por pagar (reporte **Cuentas por pagar**).

**Qué tener en cuenta:** La **fecha de vencimiento** de la compra se usa en el reporte aged payable (0-30, 31-60, 61+ días).

---

### 3.5 Facturación

- **Series:** Crear una serie por tipo (01 Factura, 03 Boleta) y almacén. El sistema asigna el **siguiente número** automáticamente. No eliminar series ya usadas para no romper la secuencia.
- **Facturas / Boletas:** Elegir serie, cliente, fecha, líneas (producto, cantidad, precios). En **Borrador** puedes editar; al enviar a SUNAT o marcar como aceptada, el número ya está consumido.
- **Enviar a SUNAT:** Requiere empresa con usuario SOL, certificado digital y (en producción) RUC autorizado. En **homologación** se usa usuario MODDATOS. El sistema devuelve aceptado o rechazado (CDR).
- **Cotizaciones:** Crear con cliente y líneas. Luego se puede **convertir en factura** (genera una factura con esos datos y asigna número de serie).
- **Cobros:** Registrar cobros contra facturas existentes (monto, fecha, medio, opcional caja/apertura). Así se construye el saldo por cobrar y el reporte **Cuentas por cobrar**.

**Qué tener en cuenta:** Una vez enviada la factura a SUNAT y aceptada, no se debe modificar. Cualquier corrección es con nota de crédito u otro mecanismo según normativa.

---

### 3.6 Reportes

- **Dashboard:** Muestra ventas del día, facturas pendientes de envío SUNAT, total por cobrar, total por pagar y alertas (ej. certificado faltante). Depende de que existan facturas y cobros/pagos.
- **Libro de ventas:** Rango de fechas. Requiere facturas emitidas en ese periodo.
- **Ventas por cliente / por producto:** Rango de fechas. Usan facturas y sus líneas.
- **Cuentas por cobrar:** Fecha de corte. Agrupa saldos de facturas menos cobros (0-30, 31-60, 61+ días desde fecha de emisión).
- **Cuentas por pagar:** Fecha de corte. Agrupa saldos de compras menos pagos (0-30, 31-60, 61+ días desde vencimiento).

**Qué tener en cuenta:** Los reportes filtran por la **empresa del usuario** (o todas si es superusuario). La fecha de corte en aged debe ser coherente con el periodo que quieres analizar.

---

## 4. Flujos típicos resumidos

### Emitir una factura y cobrarla

1. Tener empresa, almacén, serie (F001 o la que uses), cliente y productos.
2. Crear factura: tipo 01, serie F001, cliente, líneas, totales.
3. (Opcional) Enviar a SUNAT; si está aceptada, ya no se edita.
4. Registrar cobro: factura, monto, fecha, medio de pago; opcional caja y apertura si es efectivo.

### Vender con caja abierta

1. Tener caja (asociada a almacén si aplica).
2. Abrir caja (apertura con monto inicial).
3. Emitir facturas y, al registrar cobros, asociar la **apertura de caja**.
4. Al cierre del turno: consultar resumen de la apertura, crear **cierre de caja** con los totales y validar.

### Comprar y que suba el stock

1. Tener proveedor, productos y almacén.
2. Crear compra (borrador) con líneas.
3. Confirmar compra: el sistema actualiza stock en el almacén de la compra y genera movimientos en el kardex.

### Cotizar y luego facturar

1. Tener cliente y productos.
2. Crear cotización con líneas.
3. Cuando el cliente acepte, usar **Convertir a factura**: se crea la factura con serie/número y datos de la cotización. Luego enviar a SUNAT si aplica y registrar cobros.

---

## 5. Errores frecuentes y qué revisar

| Síntoma / mensaje | Revisar |
|-------------------|--------|
| “No existe serie activa para este tipo y serie” | Crear o activar una serie para ese tipo (01/03) y esa serie (ej. F001), asociada a un almacén. |
| No puedo abrir caja | Que exista al menos una caja creada. |
| No aparece stock / no se actualiza | Que la compra esté **confirmada** y tenga almacén; que el traspaso esté **validado**. |
| SUNAT rechaza el envío | RUC, usuario SOL, certificado y datos del comprobante (cliente, totales, IGV). En beta usar MODDATOS. |
| El reporte “Cuentas por cobrar” no cuadra | Que los cobros estén asociados a la factura correcta y con montos correctos. |
| No puedo convertir cotización en factura | Que haya serie activa para el tipo de documento y, si aplica, almacén. |

---

## 6. Resumen: orden y dependencias

```text
Empresa → Usuarios
    ↓
Categorías → Productos
Clientes    Proveedores
    ↓            ↓
Almacenes ←------+
    ↓
Series (facturación)   Cajas (contabilidad)
    ↓                        ↓
Facturas / Boletas    Aperturas / Cierres
Cotizaciones              Cobros (opcional)
    ↓                        ↓
Compras (→ stock)    Gastos (tipos, cuentas, monedas)
Traspasos
```

Seguir esta guía y el orden indicado evita la mayoría de errores de “falta de dato” o “recurso no encontrado” y permite usar el sistema de forma coherente de principio a fin.
