import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { ShoppingCart, Plus, Minus, Trash2, Receipt, FileText, Search as SearchIcon } from "lucide-react";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { useToast } from "@/hooks/use-toast";


interface Product {
  id: string;
  nombre: string;
  sku?: string;
  precio_venta: number | string;
  unidad_medida?: string;
}

interface CartLine {
  product: Product;
  quantity: number;
  price: number;
}

interface InvoiceSeries {
  id: string;
  tipo_documento: string;
  serie: string;
  warehouse: string;
  is_active?: boolean;
}

interface Customer {
  id: string;
  tipo_documento: string;
  numero_documento: string;
  razon_social: string;
  direccion?: string;
}

function round2(n: number) {
  return Math.round(n * 100) / 100;
}

const PuntoVenta = () => {
  const { user } = useAuth();
  const [products, setProducts] = useState<Product[]>([]);
  const [cart, setCart] = useState<CartLine[]>([]);
  const [search, setSearch] = useState("");
  const [openCobrar, setOpenCobrar] = useState(false);
  const [saving, setSaving] = useState(false);
  const [lastInvoiceId, setLastInvoiceId] = useState<string | null>(null);
  const [lastInvoiceType, setLastInvoiceType] = useState<"01" | "03">("03");
  const [lookingUpDni, setLookingUpDni] = useState(false);
  const [filterByWarehouse, setFilterByWarehouse] = useState(false);
  const [stockQuants, setStockQuants] = useState<{ product: string; quantity: number }[]>([]);
  const [customers, setCustomers] = useState<Customer[]>([]);
  const [series, setSeries] = useState<InvoiceSeries[]>([]);
  const [montoRecibido, setMontoRecibido] = useState("");
  const { toast } = useToast();

  const [emitForm, setEmitForm] = useState({
    tipo_documento: "03",
    serie: "",
    customer: "",
    cliente_razon_social: "",
    cliente_numero_documento: "",
    cliente_direccion: "",
  });

  useEffect(() => {
    const load = async () => {
      try {
        const [pRes, cRes, sRes] = await Promise.all([
          api.get<Product[]>("/products/"),
          api.get<Customer[]>("/customers/"),
          api.get<InvoiceSeries[]>("/invoice-series/"),
        ]);
        setProducts(Array.isArray(pRes.data) ? pRes.data : []);
        setCustomers(Array.isArray(cRes.data) ? cRes.data : []);
        const sList = Array.isArray(sRes.data) ? (sRes.data as InvoiceSeries[]).filter((s) => s.is_active !== false) : [];
        setSeries(sList);
      } catch {
        setProducts([]);
        setCustomers([]);
        setSeries([]);
      }
    };
    load();
  }, []);

  useEffect(() => {
    if (!filterByWarehouse || !user?.default_warehouse) {
      setStockQuants([]);
      return;
    }
    api.get<{ product: string; quantity: number }[]>("/stock-quants/", { params: { warehouse_id: user.default_warehouse } })
      .then((r) => setStockQuants(Array.isArray(r.data) ? r.data : []))
      .catch(() => setStockQuants([]));
  }, [filterByWarehouse, user?.default_warehouse]);

  const seriesForType = series.filter((s) => s.tipo_documento === emitForm.tipo_documento);
  const defaultSerie = seriesForType.find((s) => s.warehouse === user?.default_warehouse) || seriesForType[0];

  const productIdsInWarehouse = new Set(stockQuants.filter((q) => Number(q.quantity) > 0).map((q) => q.product));
  const stockByProduct = new Map(stockQuants.map((q) => [q.product, Number(q.quantity) || 0]));
  const filteredProducts = products
    .filter(
      (p) =>
        p.nombre?.toLowerCase().includes(search.toLowerCase()) ||
        (p.sku ?? "")?.toString().toLowerCase().includes(search.toLowerCase())
    )
    .filter((p) => !filterByWarehouse || !user?.default_warehouse || productIdsInWarehouse.has(p.id));

  const addToCart = (product: Product) => {
    const price = Number(product.precio_venta ?? 0);
    setCart((prev) => {
      const existing = prev.find((l) => l.product.id === product.id);
      if (existing) {
        return prev.map((l) => (l.product.id === product.id ? { ...l, quantity: l.quantity + 1 } : l));
      }
      return [...prev, { product, quantity: 1, price }];
    });
  };

  const updateQty = (productId: string, delta: number) => {
    setCart((prev) =>
      prev
        .map((l) => (l.product.id === productId ? { ...l, quantity: l.quantity + delta } : l))
        .filter((l) => l.quantity > 0)
    );
  };

  const removeFromCart = (productId: string) => {
    setCart((prev) => prev.filter((l) => l.product.id !== productId));
  };

  const subtotal = round2(cart.reduce((s, l) => s + l.price * l.quantity, 0));
  const IGV_RATE = 0.18;
  const igv = round2(subtotal * IGV_RATE);
  const total = round2(subtotal + igv);

  const montoNum = montoRecibido.trim() ? parseFloat(montoRecibido.replace(",", ".")) : NaN;
  const vuelto = !Number.isNaN(montoNum) && montoNum >= total ? round2(montoNum - total) : null;
  const falta = !Number.isNaN(montoNum) && montoNum > 0 && montoNum < total ? round2(total - montoNum) : null;

  const openCobrarDialog = () => {
    if (cart.length === 0) {
      toast({ title: "Agregue productos al carrito", variant: "destructive" });
      return;
    }
    setEmitForm({
      tipo_documento: "03",
      serie: defaultSerie?.id ?? seriesForType[0]?.id ?? "",
      customer: "",
      cliente_razon_social: "CONTADO",
      cliente_numero_documento: "",
      cliente_direccion: "",
    });
    setOpenCobrar(true);
  };

  const lookupDni = async () => {
    const dni = emitForm.cliente_numero_documento.trim();
    if (!dni) return;
    setLookingUpDni(true);
    try {
      const { data: res } = await api.get<{ razon_social?: string; nombres?: string; apellido_paterno?: string; apellido_materno?: string }>("/dni-lookup/", { params: { dni } });
      const name = res.razon_social || [res.nombres, res.apellido_paterno, res.apellido_materno].filter(Boolean).join(" ");
      if (name) setEmitForm((f) => ({ ...f, cliente_razon_social: name }));
      else toast({ title: "No se encontró el DNI", variant: "destructive" });
    } catch (e) {
      toast({ title: "Error al consultar DNI", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setLookingUpDni(false);
    }
  };

  const handleEmit = async () => {
    const selSeries = series.find((s) => s.id === emitForm.serie);
    if (!selSeries) {
      toast({ title: "Seleccione una serie", variant: "destructive" });
      return;
    }
    const customer = emitForm.customer ? customers.find((c) => c.id === emitForm.customer) : null;
    const invoiceLines = cart.map((l, i) => {
      const valor_venta = round2(l.price * l.quantity);
      const igv_monto = round2(valor_venta * IGV_RATE);
      const importe_total = round2(valor_venta + igv_monto);
      return {
        product: l.product.id,
        descripcion: l.product.nombre,
        cantidad: l.quantity,
        valor_unitario: l.price,
        valor_venta,
        codigo_tipo_afectacion: "10",
        igv_monto,
        importe_total,
      };
    });
    const subtotalInv = round2(invoiceLines.reduce((s, l) => s + l.valor_venta, 0));
    const igv_total = round2(invoiceLines.reduce((s, l) => s + l.igv_monto, 0));
    const totalInv = round2(invoiceLines.reduce((s, l) => s + l.importe_total, 0));

    setSaving(true);
    try {
      const now = new Date();
      const { data: invoice } = await api.post<{ id: string; serie: string; numero: number }>("/invoices/", {
        tipo_documento: emitForm.tipo_documento,
        serie: selSeries.serie,
        fecha_emision: now.toISOString().slice(0, 10),
        hora_emision: now.toTimeString().slice(0, 8),
        customer: emitForm.customer || null,
        cliente_tipo_documento: customer?.tipo_documento || "1",
        cliente_numero_documento: emitForm.cliente_numero_documento || customer?.numero_documento || "0",
        cliente_razon_social: emitForm.cliente_razon_social || customer?.razon_social || "CONTADO",
        cliente_direccion: emitForm.cliente_direccion || customer?.direccion || "",
        subtotal: subtotalInv,
        igv_total,
        total: totalInv,
        status: "draft",
        warehouse: selSeries.warehouse || undefined,
        invoice_lines: invoiceLines,
      });
      setLastInvoiceId(invoice.id);
      setLastInvoiceType(emitForm.tipo_documento as "01" | "03");
      toast({ title: `Comprobante ${invoice.serie}-${invoice.numero} emitido. Puede imprimir ticket, boleta o factura.` });
      setCart([]);
      setMontoRecibido("");
      setOpenCobrar(false);
      openTicket(invoice.id);
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const openTicket = (invoiceId: string) => {
    api.get(`/invoices/${invoiceId}/ticket/`, { responseType: "text" }).then(({ data }) => {
      const w = window.open("", "_blank");
      if (w) {
        w.document.write(data);
        w.document.close();
      }
    }).catch(() => {
      toast({ title: "No se pudo abrir el ticket", variant: "destructive" });
    });
  };

  return (
    <div className="space-y-4">
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Punto de venta</h1>
          <p className="erp-page-subtitle">
            Agregue productos, cobre y emita boleta o factura. Luego imprima el ticket o volante.
          </p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Productos */}
        <div className="lg:col-span-2 border rounded-lg p-4">
          <div className="flex flex-wrap gap-2 mb-4 items-center">
            <Input
              placeholder="Buscar producto por nombre o SKU…"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="flex-1 min-w-[200px]"
            />
            {user?.default_warehouse && (
              <label className="flex items-center gap-2 text-sm cursor-pointer whitespace-nowrap">
                <input
                  type="checkbox"
                  checked={filterByWarehouse}
                  onChange={(e) => setFilterByWarehouse(e.target.checked)}
                  className="rounded"
                />
                Solo en mi almacén
              </label>
            )}
          </div>
          <div className="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-[60vh] overflow-y-auto">
            {filteredProducts.map((p) => {
              const stock = filterByWarehouse ? stockByProduct.get(p.id) ?? 0 : null;
              return (
                <Button
                  key={p.id}
                  variant="outline"
                  className="h-auto py-3 flex flex-col items-center text-left"
                  onClick={() => addToCart(p)}
                >
                  <span className="font-medium truncate w-full">{p.nombre}</span>
                  <span className="text-sm text-muted-foreground">S/ {Number(p.precio_venta ?? 0).toFixed(2)}</span>
                  {stock !== null && (
                    <span className={`text-xs mt-0.5 ${stock === 0 ? "text-destructive font-medium" : "text-muted-foreground"}`}>
                      Stock: {stock}
                    </span>
                  )}
                </Button>
              );
            })}
          </div>
        </div>

        {/* Carrito */}
        <div className="border rounded-lg p-4 flex flex-col">
          <h3 className="font-semibold flex items-center gap-2 mb-3">
            <ShoppingCart className="h-5 w-5" />
            Carrito
          </h3>
          <div className="flex-1 overflow-y-auto space-y-2 min-h-[200px]">
            {cart.length === 0 ? (
              <p className="text-sm text-muted-foreground">Agregue productos desde la lista.</p>
            ) : (
              cart.map((l) => (
                <div key={l.product.id} className="flex items-center justify-between gap-2 text-sm border-b pb-2">
                  <div className="flex-1 min-w-0">
                    <p className="font-medium truncate">{l.product.nombre}</p>
                    <p className="text-muted-foreground">S/ {l.price.toFixed(2)} × {l.quantity}</p>
                  </div>
                  <div className="flex items-center gap-1">
                    <Button variant="ghost" size="icon" className="h-7 w-7" onClick={() => updateQty(l.product.id, -1)}>
                      <Minus className="h-3 w-3" />
                    </Button>
                    <span className="w-6 text-center">{l.quantity}</span>
                    <Button variant="ghost" size="icon" className="h-7 w-7" onClick={() => updateQty(l.product.id, 1)}>
                      <Plus className="h-3 w-3" />
                    </Button>
                    <Button variant="ghost" size="icon" className="h-7 w-7 text-destructive" onClick={() => removeFromCart(l.product.id)}>
                      <Trash2 className="h-3 w-3" />
                    </Button>
                  </div>
                </div>
              ))
            )}
          </div>
          <div className="mt-4 pt-4 border-t space-y-1 text-sm">
            <p>Subtotal: S/ {subtotal.toFixed(2)}</p>
            <p>IGV: S/ {igv.toFixed(2)}</p>
            <p className="font-bold text-base">Total: S/ {total.toFixed(2)}</p>
          </div>
          {cart.length > 0 && (
            <div className="mt-3 space-y-2">
              <Label htmlFor="monto-recibido" className="text-sm">Monto recibido (S/)</Label>
              <Input
                id="monto-recibido"
                type="text"
                inputMode="decimal"
                placeholder="Ej. 50"
                value={montoRecibido}
                onChange={(e) => setMontoRecibido(e.target.value)}
                className="font-medium"
              />
              {vuelto !== null && (
                <p className="text-base font-bold text-green-600 dark:text-green-500">
                  Vuelto: S/ {vuelto.toFixed(2)}
                </p>
              )}
              {falta !== null && (
                <p className="text-sm text-destructive font-medium">
                  Falta: S/ {falta.toFixed(2)}
                </p>
              )}
            </div>
          )}
          <Button className="w-full mt-4" onClick={openCobrarDialog} disabled={cart.length === 0}>
            <Receipt className="h-4 w-4 mr-2" />
            Cobrar (Boleta / Factura)
          </Button>
          {lastInvoiceId && (
            <>
              <Button variant="outline" className="w-full mt-2" onClick={() => openTicket(lastInvoiceId)}>
                <FileText className="h-4 w-4 mr-2" />
                Imprimir {lastInvoiceType === "03" ? "boleta" : "factura"} (ticket/volante)
              </Button>
            </>
          )}
        </div>
      </div>

      {/* Diálogo Cobrar */}
      <Dialog open={openCobrar} onOpenChange={setOpenCobrar}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle>Emitir comprobante</DialogTitle>
            <DialogDescription>
              Elija Ticket/Boleta (venta al público, con DNI opcional) o Factura (con RUC). Al emitir se imprimirá el comprobante (ticket/volante).
            </DialogDescription>
          </DialogHeader>
          <div className="grid gap-4 py-4">
            <div className="grid grid-cols-2 gap-4">
              <div className="grid gap-2">
                <Label>Tipo</Label>
                <Select
                  value={emitForm.tipo_documento}
                  onValueChange={(v) => {
                    const list = series.filter((s) => s.tipo_documento === v);
                    setEmitForm((f) => ({ ...f, tipo_documento: v, serie: list[0]?.id ?? "" }));
                  }}
                >
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="03">Boleta / Ticket</SelectItem>
                    <SelectItem value="01">Factura</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div className="grid gap-2">
                <Label>Serie</Label>
                <Select value={emitForm.serie} onValueChange={(v) => setEmitForm((f) => ({ ...f, serie: v }))}>
                  <SelectTrigger>
                    <SelectValue placeholder="Serie" />
                  </SelectTrigger>
                  <SelectContent>
                    {seriesForType.map((s) => (
                      <SelectItem key={s.id} value={s.id}>
                        {s.serie}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
            </div>
            {emitForm.tipo_documento === "01" ? (
              <>
                <div className="grid gap-2">
                  <Label>Cliente (factura)</Label>
                  <Select
                    value={emitForm.customer}
                    onValueChange={(v) => {
                      const c = customers.find((x) => x.id === v);
                      setEmitForm((f) => ({
                        ...f,
                        customer: v,
                        cliente_razon_social: c?.razon_social || "",
                        cliente_numero_documento: c?.numero_documento || "",
                        cliente_direccion: c?.direccion || "",
                      }));
                    }}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccionar cliente" />
                    </SelectTrigger>
                    <SelectContent>
                      {customers.map((c) => (
                        <SelectItem key={c.id} value={c.id}>
                          {c.razon_social} — {c.numero_documento}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="grid gap-2">
                  <Label>RUC</Label>
                  <Input
                    value={emitForm.cliente_numero_documento}
                    onChange={(e) => setEmitForm((f) => ({ ...f, cliente_numero_documento: e.target.value }))}
                    placeholder="RUC del cliente"
                  />
                </div>
              </>
            ) : (
              <>
                <div className="grid gap-2">
                  <Label>DNI del cliente (opcional)</Label>
                  <div className="flex gap-2">
                    <Input
                      value={emitForm.cliente_numero_documento}
                      onChange={(e) => setEmitForm((f) => ({ ...f, cliente_numero_documento: e.target.value }))}
                      placeholder="Ej. 12345678"
                      maxLength={8}
                    />
                    <Button type="button" variant="outline" onClick={lookupDni} disabled={lookingUpDni || !emitForm.cliente_numero_documento.trim()} title="Buscar nombre por DNI">
                      {lookingUpDni ? "…" : <SearchIcon className="h-4 w-4" />}
                    </Button>
                  </div>
                </div>
                <div className="grid gap-2">
                  <Label>Nombre (para boleta/ticket)</Label>
                  <Input
                    value={emitForm.cliente_razon_social}
                    onChange={(e) => setEmitForm((f) => ({ ...f, cliente_razon_social: e.target.value }))}
                    placeholder="CONTADO o nombre (buscar por DNI arriba)"
                  />
                </div>
              </>
            )}
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setOpenCobrar(false)}>
              Cancelar
            </Button>
            <Button onClick={handleEmit} disabled={saving}>
              {saving ? "Emitiendo…" : "Emitir e imprimir ticket"}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
};

export default PuntoVenta;
