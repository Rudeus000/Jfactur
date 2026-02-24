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

interface StockQuant {
  id: string;
  product: string;
  product_name: string;
  product_sku: string;
  warehouse: string;
  warehouse_name: string;
  quantity: number | string;
  product_unit?: string;
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

const columnsBase: Column<StockQuant>[] = [
  { key: "product_sku", label: "SKU" },
  { key: "product_name", label: "Producto" },
  { key: "warehouse_name", label: "Almacén" },
  {
    key: "quantity",
    label: "Cantidad",
    className: "text-right font-medium",
    render: (item) => Number(item.quantity ?? 0).toLocaleString(),
  },
  { key: "product_unit", label: "Unidad" },
];

const Stock = () => {
  const { data, isLoading, refresh, deleteItem } = useApiList<StockQuant>({ endpoint: "/stock-quants/" });
  const { data: warehouses } = useApiList<Warehouse>({ endpoint: "/warehouses/" });
  const { data: products } = useApiList<Product>({ endpoint: "/products/" });

  const [search, setSearch] = useState("");
  const ALL_WAREHOUSES = "__all__";
  const [warehouseFilter, setWarehouseFilter] = useState<string>(ALL_WAREHOUSES);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<StockQuant | null>(null);
  const [saving, setSaving] = useState(false);
  const [toDelete, setToDelete] = useState<StockQuant | null>(null);
  const { toast } = useToast();

  const [form, setForm] = useState({ product: "", warehouse: "", quantity: "" });

  const openCreate = () => {
    setEditing(null);
    setForm({ product: "", warehouse: "", quantity: "" });
    setOpen(true);
  };

  const openEdit = (row: StockQuant) => {
    setEditing(row);
    setForm({
      product: (row.product as string) ?? "",
      warehouse: (row.warehouse as string) ?? "",
      quantity: String(row.quantity ?? 0),
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const quantity = form.quantity.trim() ? parseFloat(form.quantity.replace(",", ".")) : 0;
    if (quantity < 0) {
      toast({ title: "La cantidad no puede ser negativa", variant: "destructive" });
      return;
    }
    if (!editing && (!form.product || !form.warehouse)) {
      toast({ title: "Producto y almacén son obligatorios", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      if (editing) {
        await api.patch(`/stock-quants/${editing.id}/`, { quantity });
        toast({ title: "Stock actualizado" });
      } else {
        await api.post("/stock-quants/", {
          product: form.product,
          warehouse: form.warehouse,
          quantity,
        });
        toast({ title: "Stock creado" });
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

  const filtered = data.filter((q) => {
    const matchSearch =
      !search ||
      (q.product_name as string)?.toLowerCase().includes(search.toLowerCase()) ||
      (q.product_sku as string)?.toLowerCase().includes(search.toLowerCase());
    const matchWarehouse = warehouseFilter === ALL_WAREHOUSES || (q.warehouse as string) === warehouseFilter;
    return matchSearch && matchWarehouse;
  });

  const columns: Column<StockQuant>[] = [
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
          <h1 className="erp-page-title">Stock</h1>
          <p className="erp-page-subtitle">Existencias actuales por almacén. Crear o editar cantidades manualmente.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo registro de stock
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar cantidad" : "Nuevo registro de stock"}</DialogTitle>
                <DialogDescription>
                  {editing ? "Ajuste la cantidad en almacén." : "Producto, almacén y cantidad inicial."}
                </DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                  <Label htmlFor="stock-product">Producto *</Label>
                  <Select
                    value={form.product}
                    onValueChange={(v) => setForm((f) => ({ ...f, product: v }))}
                    required={!editing}
                    disabled={!!editing}
                  >
                    <SelectTrigger id="stock-product">
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
                  {editing && <p className="text-xs text-muted-foreground">El producto no se puede cambiar al editar.</p>}
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="stock-warehouse">Almacén *</Label>
                  <Select
                    value={form.warehouse}
                    onValueChange={(v) => setForm((f) => ({ ...f, warehouse: v }))}
                    required={!editing}
                    disabled={!!editing}
                  >
                    <SelectTrigger id="stock-warehouse">
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
                  {editing && <p className="text-xs text-muted-foreground">El almacén no se puede cambiar al editar.</p>}
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="stock-quantity">Cantidad *</Label>
                  <Input
                    id="stock-quantity"
                    type="text"
                    inputMode="decimal"
                    value={form.quantity}
                    onChange={(e) => setForm((f) => ({ ...f, quantity: e.target.value }))}
                    placeholder="0"
                    required
                  />
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
          <Input placeholder="Buscar por producto o SKU…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Select value={warehouseFilter} onValueChange={setWarehouseFilter}>
          <SelectTrigger className="w-[220px]">
            <SelectValue placeholder="Todos los almacenes" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value={ALL_WAREHOUSES}>Todos los almacenes</SelectItem>
            {warehouses.map((w) => (
              <SelectItem key={w.id} value={w.id}>
                {(w.name as string) || w.code || w.id}
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay existencias. Cree un registro o realice compras/traspasos." />

      <AlertDialog open={!!toDelete} onOpenChange={(open) => !open && setToDelete(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Eliminar registro de stock?</AlertDialogTitle>
            <AlertDialogDescription>
              Se eliminará el registro de &quot;{toDelete?.product_name}&quot; en &quot;{toDelete?.warehouse_name}&quot;. Esta acción no se puede deshacer.
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

export default Stock;
