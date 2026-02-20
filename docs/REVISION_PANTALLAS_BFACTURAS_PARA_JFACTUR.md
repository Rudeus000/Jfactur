# Revisión detallada de pantallas bfacturasnew para JFactur

Documento de análisis de diseño, usabilidad, reglas y pequeños detalles de todas las pantallas de bfacturasnew, para reutilizar y mejorar en el frontend de JFactur.

---

## 1. Resumen de pantallas

| Módulo | Pantalla | Propósito principal |
|--------|----------|---------------------|
| **Auth** | Login | Inicio de sesión con email/contraseña |
| **Inicio** | Dashboard | KPIs, alertas, últimas facturas, guía rápida |
| **Ayuda** | Cómo usar | Orden de configuración, flujos, errores frecuentes |
| **Catálogo** | Clientes, Proveedores, Categorías, Productos | CRUD con búsqueda y filtros |
| **Inventario** | Almacenes, Stock, Kardex, Traspasos | Listados y movimientos |
| **Contabilidad** | Monedas, Cuentas, Bancos, Cajas, Aperturas, Gastos | Maestros y operaciones de caja |
| **Compras** | Órdenes de compra, Pagos a proveedores | Crear/confirmar y pagar |
| **Facturación** | Facturas/Boletas, Cotizaciones, Cobros, Series | Emisión SUNAT, cobros |
| **Ventas** | Punto de venta | Carrito, cliente, serie, emisión e impresión |
| **Reportes** | Libro ventas, Ventas cliente/producto, Aged cobrar/pagar | Filtros por fechas, tablas |
| **Config** | Mi empresa | Logo, razón social, domicilio, colores |

---

## 2. Patrones de diseño y clases reutilizables

### 2.1 Cabecera de página (erp-page-header)

- **Uso:** En todas las pantallas.
- **Estructura:** `div.erp-page-header` con:
  - `h1.erp-page-title` — Título de la página (ej. "Facturas / Boletas").
  - `p.erp-page-subtitle` — Una línea que explica para qué sirve la pantalla.
- **Variante con acción:** `flex items-start justify-between` cuando hay botón principal a la derecha (Nueva Factura, Nuevo Producto, etc.).
- **Regla para JFactur:** Siempre título + subtítulo descriptivo; el botón principal alineado a la derecha en la misma fila.

```css
/* Definición en index.css */
.erp-page-header { @apply mb-6; }
.erp-page-title { @apply text-2xl font-semibold tracking-tight; }
.erp-page-subtitle { @apply text-sm text-muted-foreground mt-1; }
```

### 2.2 Cards y contenedores

- **erp-card:** Contenedor de bloques (tablas, contenido). `bg-card rounded-lg border shadow-sm`.
- **erp-table-container:** Envuelve tablas. Mismo estilo + `overflow-hidden`.
- **erp-kpi-card:** Para métricas en el Dashboard. `p-5 flex flex-col gap-2` + mismo base.
- **erp-kpi-label / erp-kpi-value:** Texto pequeño uppercase para etiqueta y valor grande para el número.

### 2.3 Badges de estado (erp-status-badge)

- **Uso:** Estados de facturas (Borrador, Aceptada, Enviada, Pagada, Rechazada), IGV Sí/No, Activo/Inactivo, estados de traspasos/órdenes.
- **Clase base:** `inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium`.
- **Colores semánticos:** `bg-success/10 text-success`, `bg-warning/10 text-warning`, `bg-destructive/10 text-destructive`, `bg-muted text-muted-foreground`, `bg-info/10 text-info`.
- **Regla para JFactur:** Un mapa `statusColors` y `statusLabels` por entidad (facturas, cotizaciones, compras, etc.) y reutilizar el mismo componente de badge.

### 2.4 Tablas

- **Cabecera:** `bg-muted/50`, `text-xs font-semibold uppercase tracking-wider text-muted-foreground`.
- **Filas:** `border-t hover:bg-muted/30 transition-colors`.
- **Empty state:** `colSpan` total, `py-12` (o `py-8`), `text-center text-muted-foreground` con mensaje tipo "No hay registros" o "No hay datos para el periodo".
- **Loading:** Skeletons en filas (DataTable) o una fila con "Cargando…".

---

## 3. Componentes compartidos y su aporte al usuario

### 3.1 AppLayout (barra superior)

- **Búsqueda global** a la izquierda (placeholder "Buscar…"). Actualmente sin funcionalidad; en JFactur se puede conectar a búsqueda de clientes/productos/facturas.
- **Notificaciones** (icono campana) — lugar para alertas del sistema.
- **Mi almacén / Mi caja:** Selectores que persisten en el usuario (`PATCH /users/me/`). Dan contexto sin cambiar de pantalla.
- **Nombre de empresa** a la derecha (razón social o nombre comercial).

**Comodidad:** El usuario no tiene que elegir almacén/caja en cada pantalla; queda por defecto para facturación, cobros y reportes.

### 3.2 AppSidebar

- **Grupos colapsables** (Catálogo, Inventario, Contabilidad, etc.) con ChevronDown/ChevronRight.
- **Estado activo:** Ruta actual con `bg-sidebar-accent text-sidebar-primary`; si el ítem tiene hijos, el grupo se marca activo si alguna ruta hija está activa.
- **Footer:** Avatar con inicial, nombre (o email), rol y botón cerrar sesión.
- **Móvil:** Botón hamburguesa fijo `top-3 left-3 z-50`, overlay `bg-black/50` al abrir, sidebar deslizable.

**Regla para JFactur:** Misma estructura de navegación: ítems directos (Dashboard, Punto de venta, Cómo usar, Mi empresa) y grupos con hijos (Catálogo, Inventario, etc.).

### 3.3 DataTable

- **Props:** `columns`, `data`, `isLoading`, `onRowClick` (opcional), `emptyMessage` (default: "No hay registros").
- **Columnas:** `key`, `label`, `render?`, `className?` (para alineación o ocultar en móvil con `hidden lg:table-cell`).
- **Loading:** 5 filas de Skeleton.
- **Empty:** Una fila con mensaje centrado.
- **Acciones por fila:** Se añade una columna `_action` con botones (Editar, Ticket, SUNAT, etc.).

**Comodidad:** Listados homogéneos, mismo comportamiento de carga y vacío en toda la app.

### 3.4 MiAlmacenSelector / MiCajaSelector

- Select compacto (`w-[160px] h-8 text-xs`), opción "Sin asignar" (`value="none"`).
- Al cambiar: `PATCH /users/me/` y `refreshUser()`; toast si falla.
- Ocultos en pantallas muy pequeñas: `hidden sm:flex`.

**Regla para JFactur:** Cualquier pantalla que dependa de almacén o caja debe usar estos valores por defecto (facturas, cobros, punto de venta, gastos).

---

## 4. Formularios y validación

### 4.1 Estructura de formularios en modal

- **Dialog** con `DialogHeader` (DialogTitle + DialogDescription), luego `grid gap-4 py-4` para los campos, y `DialogFooter` con Cancelar (outline) + acción principal.
- **Labels:** Siempre con `<Label>` asociado al input; campos obligatorios con `*` en el label y `required` en el input cuando aplica.
- **Descripción del modal:** Explica en una línea qué se va a hacer (ej. "Tipo, serie, cliente y líneas. Guarde en borrador y luego envíe a SUNAT.").

### 4.2 Validación en frontend

- **Antes de enviar:** Comprobar campos obligatorios y reglas de negocio; si falla, `toast({ title: "...", variant: "destructive" })` y `return`.
- **Ejemplos en bfacturasnew:**
  - Facturas: serie activa, cliente, al menos una línea con producto, cantidad y valor unitario; SUNAT solo si status draft; clave SOL no vacía.
  - Productos: nombre obligatorio.
  - Clientes: documento y razón social obligatorios.
- **Errores de API:** Siempre con `getErrorMessage(e)` en el toast (detail, non_field_errors, o mensaje genérico de conexión).

### 4.3 Estados de botones

- **Envío:** `disabled={saving}` y texto dinámico: "Guardando…", "Enviando…", "Ingresando…".
- **Acciones condicionales:** "Nueva Factura" deshabilitado si no hay series o clientes; "SUNAT" solo para borrador y deshabilitado mientras se envía.

---

## 5. Mensajes y feedback

### 5.1 Toasts

- **Éxito:** `toast({ title: "Factura creada en borrador. Puede enviarla a SUNAT." })` — mensaje claro y siguiente paso.
- **Error:** `toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" })`.
- **Validación:** `toast({ title: "Seleccione una serie activa", variant: "destructive" })`.
- Uso consistente del hook `useToast()` en todas las pantallas que hacen mutaciones.

### 5.2 Errores en bloque (no toast)

- **Login:** Bloque con icono AlertCircle, `bg-destructive/10 border border-destructive/20`, `animate-fade-in`.
- **Dashboard:** Alertas del backend en bloques similares, encima de los KPIs.
- **Regla:** Errores que deben permanecer visibles en la misma pantalla (login, resumen) en bloque; acciones puntuales (guardar, enviar) en toast.

### 5.3 Empty states

- Tablas: mensaje único en la fila (ej. "No hay facturas recientes", "No hay datos para el periodo").
- DataTable: prop `emptyMessage` para personalizar por pantalla.

---

## 6. Detalles por pantalla

### Login

- Panel izquierdo (solo `lg`): marca, texto descriptivo y bullets (Facturación SUNAT, Control de inventario, etc.).
- Panel derecho: título "Iniciar sesión", subtítulo, bloque de error si existe, formulario con iconos (Mail, Lock) en inputs, botón ancho "Ingresar" deshabilitado durante carga con texto "Ingresando…".
- Placeholders: "usuario@empresa.com", "••••••••"; `autoComplete="email"` y `"current-password"`.
- Footer: copyright con año dinámico.

### Dashboard

- Card de guía: "¿Primera vez aquí?" con descripción y botón "Ver cómo usar el sistema" → `/como-usar`.
- Alertas: lista de `kpis.alertas` con icono y mensaje.
- KPIs en grid responsive (1/2/4 columnas), cada uno con icono, etiqueta y valor (moneda formateada con `toLocaleString("es-PE", { minimumFractionDigits: 2 })`).
- Últimas facturas: tabla con Serie-Número, Cliente, Monto (S/), Estado (badge), Fecha; sin datos: "No hay facturas recientes".
- Loading: solo cabecera con "Cargando…".

### Facturas / Boletas

- Búsqueda por serie o cliente; botón refrescar.
- Modal nueva factura: Tipo (Factura/Boleta), Serie (filtrada por tipo y almacén por defecto), Cliente, Fecha, Hora; líneas con Producto, Cantidad, P. unit., Descripción y botón quitar (deshabilitado si solo hay una). Botón "Línea" para agregar.
- Modal SUNAT: solo clave SOL (password), descripción con número de comprobante.
- Acciones por fila: Ticket (Factura/Boleta) y SUNAT (solo draft, con estado "Enviando…" por fila).
- Deshabilitar "Nueva Factura" si no hay series o clientes.

### Productos

- Filtros: búsqueda por nombre/SKU y selector "En almacén" (opcional) para filtrar por stock en un almacén.
- Modal: SKU, código de barras, Nombre*, Categoría, Unidad (NIU, KGM, etc.), Precio/Costo (S/), Descripción, Afecto a IGV (checkbox), Activo (solo edición).
- Columna IGV y opcionalmente Activo como badges.

### Clientes

- Búsqueda por nombre o documento.
- Modal: Tipo documento (DNI/RUC), Nº documento con botón "Buscar" para DNI (consulta RENIEC `/dni-lookup/`), Razón social, Dirección, Email, Teléfono, Activo (solo edición).
- Subtítulo: "Use DNI para buscar nombre por RENIEC."

### Cómo usar

- Cards con: Uso diario, Orden recomendado (lista numerada con enlaces a almacenes, cajas, series, aperturas), Flujos típicos (acordeón: facturar, caja, comprar, cotizar, traspaso), Qué tener listo antes de cada cosa, Si algo falla (errores frecuentes), Reportes.
- Enlaces internos con `Link to="..."` y clase `text-primary underline`.
- Iconos por sección (BookOpen, FileText, CreditCard, etc.).

### Reportes (ej. Libro de Ventas)

- Filtros: Desde / Hasta (type="date"), botón "Actualizar" con icono RefreshCw que hace `animate-spin` cuando `loading`.
- Tabla con columna "Comprobante" y botón por fila para abrir ticket en nueva ventana.
- Estados: "Cargando…" y "No hay datos para el periodo".

### Punto de venta

- Uso de "Mi almacén" para filtrar productos con stock; selector de tipo (Factura/Boleta) y serie; cliente opcional con búsqueda DNI; carrito con +/- y quitar; total; modal cobrar y emitir.
- Misma lógica de series por tipo y almacén por defecto que en Facturas.

---

## 7. Reglas y convenciones para JFactur

1. **Cabecera:** Todas las pantallas con `erp-page-header`, título y subtítulo que explique el propósito.
2. **Listados:** DataTable (o mismo patrón) con loading (skeletons), empty message y columnas con `render` para formato (moneda, fechas, estados).
3. **Formularios en modal:** Dialog con descripción, validación antes de submit, toasts de éxito/error, botones con estado loading.
4. **Errores:** `getErrorMessage(e)` en toasts; bloques con estilo destructivo solo donde el error debe permanecer visible (login, dashboard).
5. **Contexto de usuario:** Selectores de "Mi almacén" y "Mi caja" en header; usarlos por defecto en facturas, cobros, punto de venta, gastos.
6. **Estados:** Badges con paleta única (success, warning, destructive, muted, info) y etiquetas en español (Borrador, Enviada, etc.).
7. **Moneda:** Formato `S/ X,XXX.00` con `toLocaleString("es-PE", { minimumFractionDigits: 2 })` o `.toFixed(2)` según contexto.
8. **Accesibilidad:** Labels asociados a inputs, placeholders útiles, `required` y mensajes claros en validación.
9. **Responsive:** Columnas secundarias con `hidden lg:table-cell`; sidebar colapsable y overlay en móvil; filtros en `flex-wrap`.

---

## 8. Mejoras sugeridas (aplicables en bfacturasnew o JFactur)

1. **DataTable:** El import de `cn` ya está corregido al inicio del archivo.
2. **Productos:** Las columnas usan `categories` en el `render` de la columna "Categoría" a nivel de módulo; `categories` es estado del componente. Conviene definir las columnas dentro del componente o pasar `categories` para evitar referencias obsoletas.
3. **Búsqueda global:** Implementar búsqueda en el header (clientes, productos, facturas por número) para mayor comodidad.
4. **Confirmación antes de acciones destructivas:** En eliminaciones o "Enviar a SUNAT", opcionalmente usar un AlertDialog de confirmación.
5. **Placeholders en reportes:** Por defecto "Desde" = primer día del mes actual, "Hasta" = hoy; ya se hace en LibroVentas; replicar en todos los reportes con fechas.
6. **Punto de venta:** Mostrar stock disponible por producto en el listado (si hay almacén por defecto) para evitar vender sin stock.
7. **Cómo usar:** Mantener este tipo de guía integrada (no solo documentación externa) y actualizarla cuando se añadan módulos.

---

## 9. Qué utilizar en JFactur

- **Clases CSS:** Copiar o adaptar `erp-page-header`, `erp-page-title`, `erp-page-subtitle`, `erp-card`, `erp-table-container`, `erp-kpi-card`, `erp-kpi-label`, `erp-kpi-value`, `erp-status-badge` y la paleta de colores (success, warning, destructive, info, muted).
- **Componentes:** AppLayout (header con búsqueda, selectores, empresa), AppSidebar (navegación y footer de usuario), DataTable, MiAlmacenSelector, MiCajaSelector.
- **Hooks:** useApiList (listados con refresh y manejo de error), useToast para todo el feedback.
- **Utilidad:** getErrorMessage en lib/api para mensajes de error consistentes.
- **Pantalla de ayuda:** Estructura tipo "Cómo usar" con orden de configuración, flujos y errores frecuentes.
- **Patrones de formulario:** Modal con descripción, validación en frontend, botones con estado de carga y toasts.

Con esto se mantiene una experiencia uniforme y orientada a la comodidad del usuario en todas las pantallas de JFactur.
