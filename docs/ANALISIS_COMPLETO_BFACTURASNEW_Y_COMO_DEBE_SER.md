# Análisis completo de bfacturasnew y cómo debe ser en nuestro sistema

Documento que recorre **todo** el flujo de bfacturasnew: qué existe, qué falta, cómo inicia y termina cada proceso, y cómo debe quedar en nuestro frontend (JFactur).

---

## 1. Selección de sucursal al iniciar

### Cómo está hoy en bfacturasnew
- **No hay** una página ni pantalla inicial donde el usuario **deba** elegir sucursal antes de ver datos.
- Tras login se redirige directo a **Dashboard** (`/`).
- La sucursal se elige en el **header** con el selector "Sucursal" (opcional). Si no elige, puede ver todos los datos de la empresa sin filtrar por almacén.

### Cómo debe ser en el nuestro
- **Sí debe existir** una pantalla justo después del login (o como primer paso de sesión) donde el usuario **elija sucursal** (almacén / punto de trabajo).
- Hasta que no elija sucursal, **no** debe ver Dashboard ni datos operativos (o se muestran vacíos/filtrados).
- Flujo: **Login → Pantalla "Elegir sucursal" → Guardar en usuario (default_warehouse) → Redirigir a Dashboard**.
- Opcional: si el usuario tiene una sola sucursal, redirigir directo sin pantalla.

**Implementado:** Ruta `/elegir-sucursal`, página `ElegirSucursal.tsx`. Tras login se redirige a esta página; si hay almacenes el usuario debe elegir uno y pulsar "Continuar al inicio"; si no hay almacenes puede "Continuar al inicio" para ir a crear uno. El componente `RequireSucursal` redirige a `/elegir-sucursal` cuando el usuario no tiene `default_warehouse` y sí existen almacenes.

---

## 2. Usuarios y roles

### Cómo está hoy
- **Backend:** Existe modelo `User` con `role` (FK a `Role`) y `Role` con `permissions` (JSON), `is_system_role`. No hay endpoints que listen roles ni permisos.
- **GET/PATCH /users/me/** no devuelve `role`; solo `id`, `email`, `company`, `first_name`, `last_name`, `default_warehouse`, `default_cash_register`, etc.
- **Frontend:** Solo se muestra `user.role` en el pie del sidebar (y queda `undefined` porque el API no lo envía). No hay rutas ni componentes que oculten/muestren según rol.
- **Permisos:** Todos los ViewSets usan `IsAuthenticated` y filtro por `company_id`; no se usa `Role.permissions` ni `is_system_role` para restringir acciones.

### Cómo debe ser
- **API:** Incluir en `GET /users/me/` el **rol** (id y nombre) y, si se define, un objeto **permisos** (por módulo: ver, crear, editar, eliminar, reportes, config).
- **Frontend:** Según rol/permisos:
  - Ocultar o deshabilitar menús (ej. Config solo para Admin).
  - Ocultar botones (Nueva factura, Confirmar compra, Validar cierre, etc.) si el usuario no tiene permiso.
- Definir roles tipo: Administrador, Contador, Vendedor, Cajero, y qué puede hacer cada uno (ver listados, crear, editar, validar cierres, etc.).

---

## 3. Flujos de inicio y fin (resumen por área)

### Login
- **Inicio:** Usuario en `/login` ingresa email y contraseña.
- **Sistema:** Valida token; guarda tokens; obtiene `GET /users/me/` y redirige a `/`.
- **Fin:** Usuario en Dashboard. **Falta:** paso intermedio "Elegir sucursal" si no tiene default_warehouse.

### Apertura de caja
- **Inicio:** Usuario va a Contabilidad → Aperturas de caja → "Abrir caja".
- **Usuario:** Elige caja, saldo inicial (opcional), notas. Envía.
- **Sistema:** Crea `CashRegisterOpening` con `opened_by = request.user`. Cualquier usuario autenticado puede abrir.
- **Fin:** Apertura creada; la caja queda "abierta" hasta que se cree un cierre.

### Cierre de caja
- **Backend:** Existe `CashRegisterClosingViewSet`: `POST /cash-closings/`, `POST /cash-closings/{id}/validate/`, `GET /cash-closings/{id}/ticket/`. El cierre se asocia a una apertura; tiene totales (ventas, cobros, egresos, efectivo/tarjeta/otros), saldo cierre, destino, estado de validación.
- **Frontend:** **No hay** pantalla para crear o listar cierres de caja. Solo se menciona en Cómo usar y en Cobros.
- **Cómo debe ser:** Pantalla "Cierres de caja" (o dentro de Aperturas) donde: se listen aperturas abiertas; al elegir una apertura, formulario para crear cierre (totales, saldo cierre, notas); listado de cierres con botón Validar (para supervisor) y botón "Ticket" para imprimir.

### Productos y almacenes (cómo entran productos)
- **Compra confirmada:** Usuario en Compras → Órdenes → confirma una orden que tiene almacén. El backend en `_apply_purchase_to_stock` actualiza `StockQuant` (suma cantidad) y crea `StockMovement` tipo `purchase`. **Flujo implementado.**
- **Traspaso:** Backend tiene `POST /transfers/` y `POST /transfers/{id}/validate/`. Al validar se descuenta en origen y suma en destino. **Frontend:** Traspasos solo lista; el botón "Nuevo Traspaso" no abre formulario (no está implementado el alta).
- **Ajuste:** El modelo tiene `movement_type='adjust'` pero no hay pantalla en el front para crear ajustes manuales.
- **Cómo debe ser:** Formulario "Nuevo traspaso" (origen, destino, líneas producto–cantidad); opcional pantalla de "Ajuste de stock" para movimientos tipo ajuste.

### Facturación y SUNAT
- **Envío:** Usuario en Facturas → "SUNAT" en una factura borrador → ingresa clave SOL → confirmación → `POST /invoices/{id}/send-sunat/`.
- **Conformidad SUNAT:** Se recibe **de forma síncrona** en la respuesta al enviar. No hay webhook ni polling. El backend actualiza `Invoice.status` (`accepted` / `rejected`), `sunat_response_code`, `sunat_response_message`, `cdr_path`. La UI muestra badges (Aceptada, Rechazada) según `status`. **Correcto así.**

### Reportes y Excel
- **Hoy:** No hay export a Excel en ningún reporte (Libro de ventas, Ventas por cliente/producto, Cuentas por cobrar/pagar). Solo tablas en pantalla y en Libro de ventas botón para abrir ticket del comprobante (HTML).
- **Cómo debe ser:** Añadir en backend endpoints o acciones que generen Excel (xlsx) para libro de ventas y reportes principales; en frontend botón "Descargar Excel" en cada reporte.

### Configuración y estadísticas
- **Menú Config:** Solo "Mi empresa" (logo, nombre comercial, domicilio, colores).
- **Dashboard:** Datos de `GET /reports/dashboard/`: ventas del día, facturas pendientes SUNAT, total por cobrar, total por pagar, alertas (ej. sin certificado digital). No hay gráficos; solo KPIs y tabla "Últimas facturas".
- **Cómo debe ser:** Mantener KPIs; opcional añadir gráficos (ventas por día/semana, por sucursal). Config podría ampliarse (series, usuarios, roles) según permisos.

### Control de cajas (quién está en qué caja)
- **Backend:** En `CashRegisterOpening` está `opened_by` (FK a User). Al abrir, se guarda `opened_by = request.user`. El serializer de aperturas puede incluir `opened_by`; el listado del backend lo devuelve.
- **Frontend:** En Aperturas de caja **no** se muestra la columna "Abierto por" (usuario). Solo: Caja, Apertura, Saldo inicial, Cierre.
- **Cómo debe ser:** Incluir en el serializer `opened_by` y opcionalmente `opened_by_email` o nombre; en la tabla de Aperturas añadir columna "Abierto por" para saber qué usuario está en cada caja abierta. Opcional: vista "Cajas abiertas" resumen (caja, usuario, desde).

### Punto de venta (orden del flujo)
- **Orden actual:** (1) Usuario busca producto por nombre o SKU y agrega al carrito. (2) Opcional filtra "Solo en mi almacén" y ve stock. (3) Arma carrito (cantidades, total). (4) Pulsa "Cobrar (Boleta/Factura)". (5) En el diálogo elige tipo (Boleta/Factura), serie; **para Boleta:** DNI opcional (con "Buscar nombre por DNI") y nombre (por defecto "CONTADO"); **para Factura:** selector de cliente (RUC). (6) Emite; se crea factura y se puede imprimir ticket. El pago (efectivo/tarjeta) **no** se registra en Punto de venta; se hace aparte en Facturación → Cobros.
- **Resumen:** Primero se arman productos en el carrito; el cliente (DNI o RUC) se elige **dentro del paso Cobrar**, no antes. Búsqueda por **nombre o SKU** de producto; DNI solo para completar nombre en boleta.
- **Cómo debe ser:** Mantener este flujo; opcional permitir "buscar por DNI primero" (elegir cliente antes de armar carrito) como variante para ventas repetidas al mismo cliente.

---

## 4. Para qué sirven los campos y menús (referencia rápida)

| Área / Página | Campos / Opciones | Uso |
|---------------|-------------------|-----|
| **Dashboard** | Ventas del día, Facturas pendientes SUNAT, Por cobrar, Por pagar, Alertas, Últimas facturas | Resumen operativo y alertas (certificado, pendientes). |
| **Config → Mi empresa** | Logo (URL), Nombre comercial, Domicilio fiscal, Colores primario/secundario | Datos que salen en tickets y facturas. |
| **Header** | Sucursal, Caja, Búsqueda global, Empresa | Contexto de trabajo actual; búsqueda lleva a Facturas con filtro. |
| **Facturas** | Tipo, Serie, Cliente, Fecha/hora, Líneas (producto, cantidad, valor unit.), Estado (borrador, aceptada, rechazada) | Emitir y enviar a SUNAT; ver estado CDR. |
| **Cobros** | Factura, Monto, Fecha, Medio de pago, Caja/apertura | Registrar cobros; asociar a caja para cierre. |
| **Aperturas** | Caja, Saldo inicial, Notas | Abrir turno; `opened_by` identifica quién está en la caja. |
| **Compras** | Proveedor, Almacén (sucursal), Líneas, Confirmar | Crear compra y confirmar para que suba stock al almacén. |
| **Traspasos** | Origen, Destino, Líneas, Validar | Mover stock entre almacenes; validar para aplicar. |
| **Reportes** | Fechas (desde/hasta o fecha corte) | Filtrar libro de ventas, ventas por cliente/producto, aged. |

---

## 5. Lista de lo que hay que implementar o ajustar en nuestro sistema

1. **Pantalla "Elegir sucursal"** después del login (obligatoria o por defecto si hay una sola).
2. **Exponer rol (y permisos)** en `GET /users/me/` y usar en frontend para ocultar/deshabilitar por rol.
3. **Pantalla Cierres de caja:** listar aperturas abiertas, crear cierre (totales, saldo cierre), listar cierres, validar, ticket.
4. **Formulario Nuevo traspaso** en Inventario → Traspasos (origen, destino, líneas).
5. **Columna "Abierto por"** en Aperturas de caja (y que el backend envíe `opened_by` con nombre o email).
6. **Export a Excel** en reportes (libro de ventas y, si aplica, ventas por cliente/producto, aged).
7. **Opcional:** Ajustes de stock (pantalla que cree movimientos tipo `adjust`); gráficos en Dashboard; más opciones en Config según rol.

---

## 6. Resumen de flujos por caso de uso (inicio → fin)

| Caso | Inicio | Fin |
|------|--------|-----|
| **Login** | Usuario en login; ingresa credenciales | Token guardado; redirige a `/`. Falta: elegir sucursal si no tiene. |
| **Elegir sucursal** | (No existe como paso obligatorio) | Debe: pantalla tras login → guardar default_warehouse → ir a Dashboard. |
| **Apertura de caja** | Contabilidad → Aperturas → Abrir caja; elige caja y saldo | Apertura creada con opened_by = usuario actual. |
| **Cierre de caja** | (No hay UI) Backend: POST cash-closings con opening, totales, saldo | Cierre creado; luego POST validate; ticket disponible. Debe: pantalla completa. |
| **Productos a almacén** | Compras: orden con almacén → Confirmar. O Traspaso → Validar. | StockQuant y StockMovement actualizados. Traspaso: falta formulario "Nuevo traspaso". |
| **Emitir factura** | Facturas → Nueva → tipo, serie, cliente, líneas → Guardar borrador o Enviar SUNAT | Factura guardada; si SUNAT: status accepted/rejected según CDR. |
| **Registrar cobro** | Facturación → Cobros → factura, monto, medio, (caja/apertura) | CustomerPayment creado; saldo por cobrar actualizado. |
| **Punto de venta** | Ventas → productos al carrito → Cobrar → tipo/serie/cliente (DNI o RUC) → Emitir | Factura creada; ticket imprimible; cobro se registra aparte en Cobros. |
| **Reportes** | Reportes → elegir fechas → Actualizar | Tabla con datos. Falta: botón Descargar Excel. |

Con este documento se tiene el análisis completo de bfacturasnew y la guía de cómo debe ser el flujo y las pantallas en nuestro sistema (JFactur).
