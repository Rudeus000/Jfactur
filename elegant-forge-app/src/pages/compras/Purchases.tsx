import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, Search, RefreshCw, CheckCircle } from "lucide-react";
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
  confirmed: "Confirmada",
  cancelled: "Anulada",
};

interface Purchase {
  id: string;
  number?: string;
  date: string;
  total: number | string;
  status: string;
  supplier_name?: string;
  warehouse?: string | null;
  [key: string]: unknown;
}

interface Supplier {
  id: string;
  razon_social: string;
  ruc?: string;
}

interface Warehouse {
  id: string;
  name: string;
  code?: string;
}

interface Product {
  id: string;
  nombre: string;
  sku?: string;
  precio_venta?: number | string;
  costo_unitario?: number | string;
}

interface LineRow {
  product: string;
  quantity: string;
  unit_price: string;
  description?: string;
}

const getColumns = (warehouses: Warehouse[]): Column<Purchase>[] => [
  { key: "number", label: "Número", render: (item) => (item.number as string) || "—" },
  {
    key: "warehouse",
    label: "Sucursal",
    render: (item) => {
      const wid = item.warehouse as string | undefined;
      if (!wid) return "—";
      return warehouses.find((w) => w.id === wid)?.name ?? wid;
    },
  },
  { key: "date", label: "Fecha" },
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
          item.status === "confirmed"
            ? "bg-success/10 text-success"
            : item.status === "cancelled"
              ? "bg-destructive/10 text-destructive"
              : "bg-muted text-muted-foreground"
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

const Purchases = () => {
  const { user } = useAuth();
  const { data, isLoading, refresh } = useApiList<Purchase>({ endpoint: "/purchases/" });
  const [search, setSearch] = useState("");
  const [sucursalFilter, setSucursalFilter] = useState<string>("");
  const [open, setOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [suppliers, setSuppliers] = useState<Supplier[]>([]);
  const [warehouses, setWarehouses] = useState<Warehouse[]>([]);
  const [products, setProducts] = useState<Product[]>([]);
  const { toast } = useToast();

  useEffect(() => {
    api.get<Warehouse[]>("/warehouses/").then((r) => {
      const list = Array.isArray(r.data) ? r.data : [];
      setWarehouses(list);
      setSucursalFilter((prev) => {
        if (prev && list.some((w) => w.id === prev)) return prev;
        if (list.length > 0 && user?.default_warehouse && list.some((w) => w.id === user.default_warehouse)) {
          return user.default_warehouse;
        }
        if (list.length > 0) return list[0].id;
        return "";
      });
    }).catch(() => setWarehouses([]));
  }, [user?.default_warehouse]);

  const [form, setForm] = useState({
    supplier: "",
    warehouse: "",
    date: new Date().toISOString().slice(0, 10),
    due_date: "",
    lines: [{ product: "", quantity: "1", unit_price: "", description: "" }] as LineRow[],
  });

  useEffect(() => {
    const load = async () => {
      try {
        const [sRes, wRes, pRes] = await Promise.all([
          api.get<Supplier[]>("/suppliers/"),
          api.get<Warehouse[]>("/warehouses/"),
          api.get<Product[]>("/products/"),
        ]);
        setSuppliers(Array.isArray(sRes.data) ? sRes.data : []);
        setWarehouses(Array.isArray(wRes.data) ? wRes.data : []);
        setProducts(Array.isArray(pRes.data) ? pRes.data : []);
      } catch {
        setSuppliers([]);
        setWarehouses([]);
        setProducts([]);
      }
    };
    if (open) load();
  }, [open]);

  const openCreate = () => {
    setForm({
      supplier: suppliers[0]?.id ?? "",
      warehouse: user?.default_warehouse ?? warehouses[0]?.id ?? "",
      date: new Date().toISOString().slice(0, 10),
      due_date: "",
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
    if (!form.supplier) {
      toast({ title: "Seleccione un proveedor", variant: "destructive" });
      return;
    }
    const validLines = form.lines.filter((l) => l.product && l.quantity && l.unit_price);
    if (validLines.length === 0) {
      toast({ title: "Agregue al menos una línea con producto, cantidad y precio", variant: "destructive" });
      return;
    }
    const IGV_RATE = 0.18;
    const linesPayload = validLines.map((l) => {
      const q = parseFloat(l.quantity) || 0;
      const up = parseFloat(l.unit_price) || 0;
      const subtotal = round2(q * up);
      const igv_amount = round2(subtotal * IGV_RATE);
      const total = round2(subtotal + igv_amount);
      return {
        product: l.product,
        quantity: q,
        unit_price: up,
        description: l.description?.trim() || "",
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
      await api.post("/purchases/", {
        supplier: form.supplier,
        warehouse: form.warehouse || null,
        date: form.date,
        due_date: form.due_date || null,
        status: "draft",
        subtotal,
        igv_total,
        total,
        lines: linesPayload,
      });
      toast({ title: "Compra creada (borrador). Puede confirmarla para actualizar stock." });
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const handleConfirm = async (row: Purchase) => {
    if (row.status !== "draft") {
      toast({ title: "Solo se puede confirmar una compra en borrador", variant: "destructive" });
      return;
    }
    try {
      await api.patch(`/purchases/${row.id}/`, { status: "confirmed" });
      toast({ title: "Compra confirmada. Stock actualizado." });
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    }
  };

  const filtered = data
    .filter((p) => (p.number as string)?.toLowerCase().includes(search.toLowerCase()))
    .filter((p) => !sucursalFilter || (p.warehouse as string) === sucursalFilter);

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Órdenes de Compra</h1>
          <p className="erp-page-subtitle">Compras por sucursal (almacén). Cree en borrador y confirme para actualizar stock.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate} disabled={suppliers.length === 0}>
            <Plus className="h-4 w-4 mr-2" />
            Nueva Compra
          </Button>
          <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>Nueva orden de compra</DialogTitle>
                <DialogDescription>Proveedor, almacén (opcional) y líneas. Al confirmar se actualiza el stock.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Proveedor *</Label>
                    <Select value={form.supplier} onValueChange={(v) => setForm((f) => ({ ...f, supplier: v }))} required>
                      <SelectTrigger>
                        <SelectValue placeholder="Seleccionar" />
                      </SelectTrigger>
                      <SelectContent>
                        {suppliers.map((s) => (
                          <SelectItem key={s.id} value={s.id}>
                            {s.razon_social} {s.ruc ? `(${s.ruc})` : ""}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="grid gap-2">
                    <Label>Almacén</Label>
                    <Select value={form.warehouse} onValueChange={(v) => setForm((f) => ({ ...f, warehouse: v }))}>
                      <SelectTrigger>
                        <SelectValue placeholder="Opcional" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="">Sin almacén</SelectItem>
                        {warehouses.map((w) => (
                          <SelectItem key={w.id} value={w.id}>
                            {w.name}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Fecha *</Label>
                    <Input type="date" value={form.date} onChange={(e) => setForm((f) => ({ ...f, date: e.target.value }))} required />
                  </div>
                  <div className="grid gap-2">
                    <Label>Vencimiento</Label>
                    <Input type="date" value={form.due_date} onChange={(e) => setForm((f) => ({ ...f, due_date: e.target.value }))} />
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
                        <div className="col-span-5">
                          <Select
                            value={line.product}
                            onValueChange={(v) => updateLine(idx, "product", v)}
                          >
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
                        <div className="col-span-2">
                          <Input
                            placeholder="Desc."
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
                  {saving ? "Guardando…" : "Crear (borrador)"}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>
      <div className="flex flex-wrap gap-3 mb-4 items-center">
        <div className="relative flex-1 min-w-[200px] max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por número…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <div className="flex items-center gap-2">
          <Label className="text-sm text-muted-foreground whitespace-nowrap">Sucursal:</Label>
          <Select value={sucursalFilter} onValueChange={setSucursalFilter}>
            <SelectTrigger className="w-[180px]">
              <SelectValue placeholder="Todas" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="">Todas las sucursales</SelectItem>
              {warehouses.map((w) => (
                <SelectItem key={w.id} value={w.id}>{w.name} {w.code ? `(${w.code})` : ""}</SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable
        columns={[
          ...getColumns(warehouses),
          {
            key: "_action",
            label: "Acción",
            render: (item) =>
              item.status === "draft" ? (
                <Button variant="outline" size="sm" onClick={() => handleConfirm(item)}>
                  <CheckCircle className="h-4 w-4 mr-1" />
                  Confirmar
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

export default Purchases;
