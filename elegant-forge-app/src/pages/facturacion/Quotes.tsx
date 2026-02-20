import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, Search, RefreshCw, FileText } from "lucide-react";
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

const statusLabels: Record<string, string> = {
  draft: "Borrador",
  sent: "Enviada",
  accepted: "Aceptada",
  rejected: "Rechazada",
  expired: "Vencida",
};

interface Quote {
  id: string;
  number?: string;
  date: string;
  cliente_razon_social?: string;
  total: number | string;
  status: string;
  invoice?: string | null;
  [key: string]: unknown;
}

interface Customer {
  id: string;
  tipo_documento: string;
  numero_documento: string;
  razon_social: string;
  direccion?: string;
}

interface Product {
  id: string;
  nombre: string;
  sku?: string;
  precio_venta?: number | string;
}

interface LineRow {
  product: string;
  quantity: string;
  unit_price: string;
  description: string;
}

const columns: Column<Quote>[] = [
  { key: "number", label: "Número", render: (item) => (item.number as string) || "—" },
  { key: "date", label: "Fecha" },
  { key: "cliente_razon_social", label: "Cliente" },
  {
    key: "total",
    label: "Total",
    className: "text-right font-medium",
    render: (item) => `S/ ${Number(item.total ?? 0).toFixed(2)}`,
  },
  {
    key: "status",
    label: "Estado",
    render: (item) => (
      <span
        className={`erp-status-badge ${
          item.status === "accepted" ? "bg-success/10 text-success" : item.status === "rejected" ? "bg-destructive/10 text-destructive" : "bg-muted text-muted-foreground"
        }`}
      >
        {statusLabels[item.status as string] || item.status}
      </span>
    ),
  },
];

function round2(n: number) {
  return Math.round(n * 100) / 100;
}

const Quotes = () => {
  const { data, isLoading, refresh } = useApiList<Quote>({ endpoint: "/quotes/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [convertingId, setConvertingId] = useState<string | null>(null);
  const [customers, setCustomers] = useState<Customer[]>([]);
  const [products, setProducts] = useState<Product[]>([]);
  const { toast } = useToast();

  const [form, setForm] = useState({
    customer: "",
    date: new Date().toISOString().slice(0, 10),
    valid_until: "",
    lines: [{ product: "", quantity: "1", unit_price: "", description: "" }] as LineRow[],
  });

  useEffect(() => {
    const load = async () => {
      try {
        const [cRes, pRes] = await Promise.all([
          api.get<Customer[]>("/customers/"),
          api.get<Product[]>("/products/"),
        ]);
        setCustomers(Array.isArray(cRes.data) ? cRes.data : []);
        setProducts(Array.isArray(pRes.data) ? pRes.data : []);
      } catch {
        setCustomers([]);
        setProducts([]);
      }
    };
    if (open) load();
  }, [open]);

  const openCreate = () => {
    setForm({
      customer: customers[0]?.id ?? "",
      date: new Date().toISOString().slice(0, 10),
      valid_until: "",
      lines: [{ product: "", quantity: "1", unit_price: "", description: "" }],
    });
    setOpen(true);
  };

  const addLine = () => {
    setForm((f) => ({
      ...f,
      lines: [...f.lines, { product: "", quantity: "1", unit_price: "", description: "" }],
    }));
  };

  const updateLine = (index: number, field: keyof LineRow, value: string) => {
    setForm((f) => ({
      ...f,
      lines: f.lines.map((l, i) => (i === index ? { ...l, [field]: value } : l)),
    }));
  };

  const removeLine = (index: number) => {
    setForm((f) => ({ ...f, lines: f.lines.filter((_, i) => i !== index) }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.customer) {
      toast({ title: "Seleccione un cliente", variant: "destructive" });
      return;
    }
    const validLines = form.lines.filter((l) => l.product && l.quantity && l.unit_price);
    if (validLines.length === 0) {
      toast({ title: "Agregue al menos una línea con producto, cantidad y precio", variant: "destructive" });
      return;
    }
    const customer = customers.find((c) => c.id === form.customer);
    const IGV_RATE = 0.18;
    const linesPayload = validLines.map((l, i) => {
      const q = parseFloat(l.quantity) || 0;
      const up = parseFloat(l.unit_price) || 0;
      const subtotal = round2(q * up);
      const igv_amount = round2(subtotal * IGV_RATE);
      const total = round2(subtotal + igv_amount);
      const product = products.find((p) => p.id === l.product);
      return {
        product: l.product,
        quantity: q,
        unit_price: up,
        description: l.description?.trim() || product?.nombre || "Producto",
        subtotal,
        igv_amount,
        total,
      };
    });
    const subtotal = round2(linesPayload.reduce((s, l) => s + l.subtotal, 0));
    const igv_total = round2(linesPayload.reduce((s, l) => s + l.igv_amount, 0));
    const total = round2(linesPayload.reduce((s, l) => s + l.total, 0));

    setSaving(true);
    try {
      await api.post("/quotes/", {
        customer: form.customer,
        date: form.date,
        valid_until: form.valid_until || null,
        status: "draft",
        cliente_tipo_documento: customer?.tipo_documento || "",
        cliente_numero_documento: customer?.numero_documento || "",
        cliente_razon_social: customer?.razon_social || "",
        cliente_direccion: customer?.direccion || "",
        subtotal,
        igv_total,
        total,
        lines: linesPayload,
      });
      toast({ title: "Cotización creada. Puede convertirla en factura cuando el cliente acepte." });
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const handleConvertToInvoice = async (row: Quote) => {
    if (row.invoice) {
      toast({ title: "Esta cotización ya tiene una factura asociada", variant: "destructive" });
      return;
    }
    setConvertingId(row.id);
    try {
      const { data: invoice } = await api.post<{ id: string; serie: string; numero: number }>(
        `/quotes/${row.id}/convert-to-invoice/`,
        { tipo_documento: "01", serie: "" }
      );
      toast({ title: `Factura ${invoice.serie}-${invoice.numero} creada` });
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setConvertingId(null);
    }
  };

  const filtered = data.filter(
    (q) =>
      (q.number as string)?.toLowerCase().includes(search.toLowerCase()) ||
      (q.cliente_razon_social as string)?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Cotizaciones</h1>
          <p className="erp-page-subtitle">Crear cotizaciones y convertirlas en factura cuando el cliente acepte.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate} disabled={customers.length === 0}>
            <Plus className="h-4 w-4 mr-2" />
            Nueva Cotización
          </Button>
          <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>Nueva cotización</DialogTitle>
                <DialogDescription>Cliente y líneas. Luego use "Convertir a factura" cuando el cliente acepte.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                  <Label>Cliente *</Label>
                  <Select value={form.customer} onValueChange={(v) => setForm((f) => ({ ...f, customer: v }))} required>
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccionar" />
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
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Fecha *</Label>
                    <Input type="date" value={form.date} onChange={(e) => setForm((f) => ({ ...f, date: e.target.value }))} required />
                  </div>
                  <div className="grid gap-2">
                    <Label>Válida hasta</Label>
                    <Input type="date" value={form.valid_until} onChange={(e) => setForm((f) => ({ ...f, valid_until: e.target.value }))} />
                  </div>
                </div>
                <div>
                  <div className="flex justify-between items-center mb-2">
                    <Label>Líneas</Label>
                    <Button type="button" variant="outline" size="sm" onClick={addLine}>
                      <Plus className="h-3 w-3 mr-1" />
                      Línea
                    </Button>
                  </div>
                  <div className="space-y-2 border rounded-md p-2">
                    {form.lines.map((line, idx) => (
                      <div key={idx} className="grid grid-cols-12 gap-2 items-end">
                        <div className="col-span-4">
                          <Select value={line.product} onValueChange={(v) => updateLine(idx, "product", v)}>
                            <SelectTrigger className="h-8">
                              <SelectValue placeholder="Producto" />
                            </SelectTrigger>
                            <SelectContent>
                              {products.map((p) => (
                                <SelectItem key={p.id} value={p.id}>
                                  {p.nombre} {p.sku ? `(${p.sku})` : ""}
                                </SelectItem>
                              ))}
                            </SelectContent>
                          </Select>
                        </div>
                        <div className="col-span-2">
                          <Input
                            type="number"
                            min="0"
                            step="1"
                            placeholder="Cant"
                            value={line.quantity}
                            onChange={(e) => updateLine(idx, "quantity", e.target.value)}
                            className="h-8"
                          />
                        </div>
                        <div className="col-span-2">
                          <Input
                            type="number"
                            min="0"
                            step="0.01"
                            placeholder="P. unit."
                            value={line.unit_price}
                            onChange={(e) => updateLine(idx, "unit_price", e.target.value)}
                            className="h-8"
                          />
                        </div>
                        <div className="col-span-3">
                          <Input
                            placeholder="Descripción"
                            value={line.description || ""}
                            onChange={(e) => updateLine(idx, "description", e.target.value)}
                            className="h-8"
                          />
                        </div>
                        <div className="col-span-1">
                          <Button
                            type="button"
                            variant="ghost"
                            size="icon"
                            className="h-8 w-8"
                            onClick={() => removeLine(idx)}
                            disabled={form.lines.length === 1}
                          >
                            ×
                          </Button>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
              <DialogFooter>
                <Button type="button" variant="outline" onClick={() => setOpen(false)}>
                  Cancelar
                </Button>
                <Button type="submit" disabled={saving}>
                  {saving ? "Guardando…" : "Crear cotización"}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por número o cliente…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable
        columns={[
          ...columns,
          {
            key: "_action",
            label: "Acción",
            render: (item) =>
              !item.invoice && item.status !== "rejected" ? (
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => handleConvertToInvoice(item)}
                  disabled={!!convertingId}
                >
                  <FileText className="h-4 w-4 mr-1" />
                  {convertingId === item.id ? "Convirtiendo…" : "Convertir a factura"}
                </Button>
              ) : null,
          },
        ]}
        data={filtered}
        isLoading={isLoading}
      />
    </div>
  );
};

export default Quotes;
