import { useState } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, Search, RefreshCw, Pencil, Trash2 } from "lucide-react";
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
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { useToast } from "@/hooks/use-toast";

const movementLabels: Record<string, string> = {
  in: "Entrada",
  out: "Salida",
  adjust: "Ajuste",
  purchase: "Compra",
  sale: "Venta",
  transfer: "Traspaso",
};

const MOVEMENT_TYPES = [
  { value: "in", label: "Entrada" },
  { value: "out", label: "Salida" },
  { value: "adjust", label: "Ajuste" },
];

interface StockMovement {
  id: string;
  product: string;
  product_name?: string;
  warehouse: string;
  warehouse_name?: string;
  movement_type: string;
  quantity: number | string;
  quantity_after?: number | string;
  reference?: string;
  reference_model?: string;
  reference_id?: string | null;
  date: string;
  unit_cost?: number | string | null;
  total_cost?: number | string | null;
  notes?: string;
  [key: string]: unknown;
}

interface Product {
  id: string;
  sku?: string;
  nombre: string;
  [key: string]: unknown;
}

interface Warehouse {
  id: string;
  name: string;
  code?: string;
  [key: string]: unknown;
}

const columnsBase: Column<StockMovement>[] = [
  { key: "product_name", label: "Producto" },
  { key: "warehouse_name", label: "Almacén" },
  {
    key: "movement_type",
    label: "Tipo",
    render: (item) => movementLabels[item.movement_type as string] || item.movement_type,
  },
  {
    key: "quantity",
    label: "Cantidad",
    className: "text-right",
    render: (item) => Number(item.quantity ?? 0).toLocaleString(),
  },
  { key: "reference", label: "Referencia" },
  { key: "date", label: "Fecha" },
];

function toDatetimeLocal(iso: string | undefined): string {
  if (!iso) return "";
  const d = new Date(iso);
  if (isNaN(d.getTime())) return "";
  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2, "0");
  const day = String(d.getDate()).padStart(2, "0");
  const h = String(d.getHours()).padStart(2, "0");
  const min = String(d.getMinutes()).padStart(2, "0");
  return `${y}-${m}-${day}T${h}:${min}`;
}

function fromDatetimeLocal(local: string): string {
  if (!local) return new Date().toISOString();
  return new Date(local).toISOString();
}

const ALL_FILTER = "__all__";

const Kardex = () => {
  const { data, isLoading, refresh, deleteItem } = useApiList<StockMovement>({ endpoint: "/stock-movements/" });
  const { data: warehouses } = useApiList<Warehouse>({ endpoint: "/warehouses/" });
  const { data: products } = useApiList<Product>({ endpoint: "/products/" });

  const [search, setSearch] = useState("");
  const [warehouseFilter, setWarehouseFilter] = useState<string>(ALL_FILTER);
  const [productFilter, setProductFilter] = useState<string>(ALL_FILTER);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<StockMovement | null>(null);
  const [saving, setSaving] = useState(false);
  const [toDelete, setToDelete] = useState<StockMovement | null>(null);
  const { toast } = useToast();

  const [form, setForm] = useState({
    product: "",
    warehouse: "",
    movement_type: "in",
    quantity: "",
    date: toDatetimeLocal(new Date().toISOString()),
    reference: "",
    notes: "",
  });

  const openCreate = () => {
    setEditing(null);
    setForm({
      product: "",
      warehouse: "",
      movement_type: "in",
      quantity: "",
      date: toDatetimeLocal(new Date().toISOString()),
      reference: "",
      notes: "",
    });
    setOpen(true);
  };

  const openEdit = (row: StockMovement) => {
    setEditing(row);
    setForm({
      product: (row.product as string) ?? "",
      warehouse: (row.warehouse as string) ?? "",
      movement_type: (row.movement_type as string) || "in",
      quantity: String(Math.abs(Number(row.quantity ?? 0))),
      date: toDatetimeLocal(row.date as string),
      reference: (row.reference as string) ?? "",
      notes: (row.notes as string) ?? "",
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const qty = form.quantity.trim() ? parseFloat(form.quantity.replace(",", ".")) : 0;
    const quantity = form.movement_type === "out" ? -Math.abs(qty) : Math.abs(qty);
    if (qty === 0) {
      toast({ title: "La cantidad debe ser distinta de cero", variant: "destructive" });
      return;
    }
    if (!editing && (!form.product || !form.warehouse)) {
      toast({ title: "Producto y almacén son obligatorios", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      const payload = {
        product: form.product,
        warehouse: form.warehouse,
        movement_type: form.movement_type,
        quantity,
        date: fromDatetimeLocal(form.date),
        reference: form.reference.trim() || undefined,
        notes: form.notes.trim() || undefined,
      };
      if (editing) {
        await api.patch(`/stock-movements/${editing.id}/`, payload);
        toast({ title: "Movimiento actualizado" });
      } else {
        await api.post("/stock-movements/", payload);
        toast({ title: "Movimiento creado" });
      }
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const handleDeleteConfirm = async () => {
    if (!toDelete) return;
    await deleteItem(toDelete.id);
    setToDelete(null);
  };

  const filtered = data.filter((m) => {
    const matchSearch =
      !search ||
      (m.product_name as string)?.toLowerCase().includes(search.toLowerCase()) ||
      (m.reference as string)?.toLowerCase().includes(search.toLowerCase());
    const matchWarehouse = warehouseFilter === ALL_FILTER || (m.warehouse as string) === warehouseFilter;
    const matchProduct = productFilter === ALL_FILTER || (m.product as string) === productFilter;
    return matchSearch && matchWarehouse && matchProduct;
  });

  const columns: Column<StockMovement>[] = [
    ...columnsBase,
    {
      key: "_actions",
      label: "",
      render: (row) => (
        <div className="flex items-center gap-1" onClick={(e) => e.stopPropagation()}>
          <Button variant="ghost" size="icon" className="h-8 w-8" onClick={() => openEdit(row)} aria-label="Editar">
            <Pencil className="h-4 w-4" />
          </Button>
          <Button variant="ghost" size="icon" className="h-8 w-8 text-destructive hover:text-destructive" onClick={() => setToDelete(row)} aria-label="Eliminar">
            <Trash2 className="h-4 w-4" />
          </Button>
        </div>
      ),
    },
  ];

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Kardex</h1>
          <p className="erp-page-subtitle">Historial de entradas, salidas, ajustes y traspasos por producto y almacén.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo movimiento
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar movimiento" : "Nuevo movimiento"}</DialogTitle>
                <DialogDescription>
                  {editing ? "Ajuste tipo, cantidad, fecha o notas." : "Registre una entrada, salida o ajuste manual."}
                </DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                  <Label>Producto *</Label>
                  <Select value={form.product} onValueChange={(v) => setForm((f) => ({ ...f, product: v }))} required={!editing} disabled={!!editing}>
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccione producto" />
                    </SelectTrigger>
                    <SelectContent>
                      {products.map((p) => (
                        <SelectItem key={p.id} value={p.id}>
                          {(p.sku as string) || ""} – {(p.nombre as string) || p.id}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  {editing && <p className="text-xs text-muted-foreground">El producto no se puede cambiar.</p>}
                </div>
                <div className="grid gap-2">
                  <Label>Almacén *</Label>
                  <Select value={form.warehouse} onValueChange={(v) => setForm((f) => ({ ...f, warehouse: v }))} required={!editing} disabled={!!editing}>
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccione almacén" />
                    </SelectTrigger>
                    <SelectContent>
                      {warehouses.map((w) => (
                        <SelectItem key={w.id} value={w.id}>
                          {(w.code as string) || ""} – {(w.name as string) || w.id}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  {editing && <p className="text-xs text-muted-foreground">El almacén no se puede cambiar.</p>}
                </div>
                <div className="grid gap-2">
                  <Label>Tipo de movimiento *</Label>
                  <Select value={form.movement_type} onValueChange={(v) => setForm((f) => ({ ...f, movement_type: v }))}>
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      {MOVEMENT_TYPES.map((t) => (
                        <SelectItem key={t.value} value={t.value}>
                          {t.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="kardex-quantity">Cantidad (valor absoluto) *</Label>
                  <Input
                    id="kardex-quantity"
                    type="text"
                    inputMode="decimal"
                    value={form.quantity}
                    onChange={(e) => setForm((f) => ({ ...f, quantity: e.target.value }))}
                    placeholder="0"
                    required
                  />
                  <p className="text-xs text-muted-foreground">Para salida se enviará como cantidad negativa.</p>
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="kardex-date">Fecha y hora *</Label>
                  <Input id="kardex-date" type="datetime-local" value={form.date} onChange={(e) => setForm((f) => ({ ...f, date: e.target.value }))} required />
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="kardex-reference">Referencia</Label>
                  <Input id="kardex-reference" value={form.reference} onChange={(e) => setForm((f) => ({ ...f, reference: e.target.value }))} placeholder="Ej: AJ-001" />
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="kardex-notes">Notas</Label>
                  <Input id="kardex-notes" value={form.notes} onChange={(e) => setForm((f) => ({ ...f, notes: e.target.value }))} placeholder="Opcional" />
                </div>
              </div>
              <DialogFooter>
                <Button type="button" variant="outline" onClick={() => setOpen(false)}>
                  Cancelar
                </Button>
                <Button type="submit" disabled={saving}>
                  {saving ? "Guardando…" : "Guardar"}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>
      <div className="flex flex-wrap gap-3 mb-4">
        <div className="relative flex-1 min-w-[200px] max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por producto o referencia…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Select value={warehouseFilter} onValueChange={setWarehouseFilter}>
          <SelectTrigger className="w-[200px]">
            <SelectValue placeholder="Todos los almacenes" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value={ALL_FILTER}>Todos los almacenes</SelectItem>
            {warehouses.map((w) => (
              <SelectItem key={w.id} value={w.id}>
                {(w.name as string) || w.code || w.id}
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
        <Select value={productFilter} onValueChange={setProductFilter}>
          <SelectTrigger className="w-[200px]">
            <SelectValue placeholder="Todos los productos" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value={ALL_FILTER}>Todos los productos</SelectItem>
            {products.map((p) => (
              <SelectItem key={p.id} value={p.id}>
                {(p.nombre as string) || p.sku || p.id}
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay movimientos. Cree uno o realice compras/ventas/traspasos." />

      <AlertDialog open={!!toDelete} onOpenChange={(open) => !open && setToDelete(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Eliminar movimiento?</AlertDialogTitle>
            <AlertDialogDescription>
              Se eliminará el movimiento de &quot;{toDelete?.product_name}&quot; ({movementLabels[toDelete?.movement_type as string] || toDelete?.movement_type}). Esta acción no se puede deshacer.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancelar</AlertDialogCancel>
            <AlertDialogAction onClick={handleDeleteConfirm} className="bg-destructive text-destructive-foreground hover:bg-destructive/90">
              Eliminar
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
};

export default Kardex;
