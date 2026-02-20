# Plan: Casos de uso detallados (formato correcto)

## Formato obligatorio de cada caso de uso

Cada caso de uso debe incluir de forma explícita:

| Campo | Descripción |
|-------|-------------|
| **Usuario** | Quién realiza la acción: rol o tipo de usuario (ej. Vendedor, Contador, Administrador, Cajero). |
| **Cliente** | Si aplica: la parte externa en el escenario (ej. el **cliente** que recibe la factura, el **proveedor** al que se le compra). En casos internos (ej. configurar monedas) puede ser "N/A". |
| **Qué debe hacer** | Requisitos previos: qué debe estar configurado, qué permisos, qué datos existen antes de empezar. |
| **Qué hace el usuario** | Pasos numerados: acciones concretas que realiza el usuario en pantalla (ej. "Abre Facturación → Facturas", "Selecciona cliente X"). |
| **Qué hace el sistema** | Por cada paso (o agrupado): qué hace el sistema (valida, guarda, calcula totales, muestra mensaje, asigna número, envía a SUNAT, actualiza stock, etc.). |
| **Postcondición** | Estado del sistema al terminar (ej. "La factura queda emitida y con número asignado"). |

Ejemplo de estructura en tabla para el flujo:

```markdown
### CU-19: Emitir factura o boleta

**Usuario:** Vendedor / Usuario de facturación.
**Cliente:** Cliente de la empresa (quien recibe la factura/boleta).

**Qué debe hacer (antes):**
- Tener al menos un almacén, una serie activa (F001/B001), clientes y productos cargados.
- Para SUNAT: certificado y usuario SOL (en homologación: MODDATOS).

**Flujo:**

| Paso | Qué hace el usuario | Qué hace el sistema |
|------|--------------------|----------------------|
| 1 | Entra a Facturación → Facturas / Boletas y pulsa "Nueva". | Muestra formulario con tipo, serie, cliente, líneas. |
| 2 | Elige tipo (01 Factura / 03 Boleta), serie, cliente y agrega líneas (producto, cantidad). | Valida serie activa; calcula subtotal, IGV y total. |
| 3 | Guarda como borrador o "Enviar a SUNAT". | Si borrador: guarda sin número. Si enviar: asigna número, genera XML, envía a SUNAT y muestra CDR (aceptado/rechazado). |
| 4 | (Opcional) Registra cobro en Facturación → Cobros. | Asocia cobro a la factura; actualiza saldo por cobrar. |

**Postcondición:** La factura queda emitida (con o sin envío SUNAT); si se envió y fue aceptada, tiene número oficial y no debe editarse.
```

---

## Listado de casos de uso a redactar con este formato

Cada uno con: **Usuario**, **Cliente** (si aplica), **Qué debe hacer**, tabla **Qué hace el usuario / Qué hace el sistema**, **Postcondición**.

1. **Acceso**  
   - CU-01: Iniciar sesión  
   - CU-02: Asignar Mi almacén y Mi caja (header)

2. **Catálogo**  
   - CU-03: Mantener clientes (alta, edición, listado)  
   - CU-04: Mantener proveedores  
   - CU-05: Mantener categorías de productos  
   - CU-06: Mantener productos  

3. **Inventario**  
   - CU-07: Mantener almacenes  
   - CU-08: Consultar stock  
   - CU-09: Consultar kardex  
   - CU-10: Traspaso entre almacenes (crear y validar)

4. **Contabilidad**  
   - CU-11: Configurar monedas, plan de cuentas y bancos  
   - CU-12: Mantener cajas  
   - CU-13: Apertura de caja  
   - CU-14: Cierre de caja  
   - CU-15: Registrar gastos  

5. **Compras**  
   - CU-16: Crear y confirmar orden de compra (cliente = proveedor)  
   - CU-17: Registrar pago a proveedor  

6. **Facturación**  
   - CU-18: Configurar series  
   - CU-19: Emitir factura o boleta (cliente = cliente que compra)  
   - CU-20: Registrar cobro del cliente  
   - CU-21: Cotización y convertir a factura  

7. **Reportes**  
   - CU-22: Consultar dashboard  
   - CU-23: Libro de ventas  
   - CU-24: Ventas por cliente / por producto  
   - CU-25: Cuentas por cobrar / por pagar  

---

## Entregables

1. **Actualizar** [docs/CASOS_DE_USO.md](docs/CASOS_DE_USO.md): reescribir el contenido con el formato anterior. Mantener sección de "Orden recomendado" y "Errores frecuentes" al final, pero la parte central serán los casos de uso con **Usuario**, **Cliente**, **Qué debe hacer**, **Qué hace el usuario**, **Qué hace el sistema**, **Postcondición**.

2. **Opcional:** Página en el front "Casos de uso" (`/casos-de-uso`) que muestre el mismo contenido (por ejemplo, acordeones por CU con las mismas secciones).

3. En "Cómo usar el sistema": enlace a "Ver casos de uso detallados" apuntando a `/casos-de-uso` o al doc.

---

## Resumen

- **Usuario** = quién hace la acción (rol/persona).  
- **Cliente** = quién es el cliente en el caso (cliente de negocio, proveedor, o N/A).  
- **Qué debe hacer** = requisitos y precondiciones.  
- **Qué hace el usuario** = pasos del usuario.  
- **Qué hace el sistema** = respuesta del sistema en cada paso.  
- **Postcondición** = estado final del sistema.

Con esto se cumple lo que pide el usuario: casos de uso detallados donde se vea claro el **usuario**, el **cliente**, qué hace cada uno y **qué debe hacerse**.
