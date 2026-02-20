import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, Search, RefreshCw, Pencil } from "lucide-react";
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
  sku: string;
  barcode?: string;
  nombre: string;
  descripcion?: string;
  unidad_medida: string;
  precio_venta: number | string;
  costo_unitario?: number | string;
  afecto_igv: boolean;
  codigo_tipo_afectacion?: string;
  category?: string;
  category_name?: string;
  is_active?: boolean;
  [key: string]: unknown;
}

interface Category {
  id: string;
  name: string;
}

interface Warehouse {
  id: string;
  name: string;
}

interface StockQuant {
  product: string;
  warehouse: string;
  quantity: number | string;
  product_name?: string;
}

const UNIDADES = [
  { value: "NIU", label: "Unidad (NIU)" },
  { value: "KGM", label: "Kilogramo" },
  { value: "LTR", label: "Litro" },
  { value: "MTK", label: "m²" },
  { value: "MTR", label: "Metro" },
  { value: "BX", label: "Caja" },
  { value: "PK", label: "Paquete" },
];

const getColumns = (categories: Category[]): Column<Product>[] => [
  { key: "sku", label: "SKU", render: (p) => (p.sku as string) || "—" },
  { key: "nombre", label: "Producto" },
  { key: "category", label: "Categoría", render: (p) => categories.find((c) => c.id === p.category)?.name || "—" },
  {
    key: "precio_venta",
    label: "Precio",
    className: "text-right",
    render: (item) => `S/ ${Number(item.precio_venta ?? 0).toFixed(2)}`,
  },
  {
    key: "costo_unitario",
    label: "Costo",
    className: "text-right hidden lg:table-cell",
    render: (item) => `S/ ${Number(item.costo_unitario ?? 0).toFixed(2)}`,
  },
  {
    key: "afecto_igv",
    label: "IGV",
    render: (item) => (
      <span className={`erp-status-badge ${item.afecto_igv ? "bg-success/10 text-success" : "bg-muted text-muted-foreground"}`}>
        {item.afecto_igv ? "Sí" : "No"}
      </span>
    ),
  },
];

const Products = () => {
  const { user } = useAuth();
  const { data, isLoading, refresh } = useApiList<Product>({ endpoint: "/products/" });
  const [search, setSearch] = useState("");
  const [warehouseFilter, setWarehouseFilter] = useState<string>("");
  const [warehouses, setWarehouses] = useState<Warehouse[]>([]);
  const [stockQuants, setStockQuants] = useState<StockQuant[]>([]);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Product | null>(null);
  const [saving, setSaving] = useState(false);
  const [categories, setCategories] = useState<Category[]>([]);
  const { toast } = useToast();

  const [form, setForm] = useState({
    sku: "",
    barcode: "",
    nombre: "",
    descripcion: "",
    unidad_medida: "NIU",
    precio_venta: "",
    costo_unitario: "",
    afecto_igv: true,
    codigo_tipo_afectacion: "10",
    category: "",
    is_active: true,
  });

  useEffect(() => {
    api.get<Warehouse[]>("/warehouses/").then((r) => setWarehouses(Array.isArray(r.data) ? r.data : [])).catch(() => setWarehouses([]));
  }, []);

  useEffect(() => {
    api.get<Category[]>("/categories/").then((r) => setCategories(Array.isArray(r.data) ? r.data : [])).catch(() => setCategories([]));
  }, []);

  useEffect(() => {
    if (!warehouseFilter) {
      setStockQuants([]);
      return;
    }
    api.get<StockQuant[]>("/stock-quants/", { params: { warehouse_id: warehouseFilter } })
      .then((r) => setStockQuants(Array.isArray(r.data) ? r.data : []))
      .catch(() => setStockQuants([]));
  }, [warehouseFilter]);


  const productIdsInWarehouse = new Set(stockQuants.map((q) => q.product));
  const filteredBySearch = data.filter(
    (p) =>
      p.nombre?.toLowerCase().includes(search.toLowerCase()) ||
      (p.sku as string)?.toLowerCase().includes(search.toLowerCase())
  );
  const filtered =
    warehouseFilter && productIdsInWarehouse.size > 0
      ? filteredBySearch.filter((p) => productIdsInWarehouse.has(p.id))
      : filteredBySearch;

  const openCreate = () => {
    setEditing(null);
    setForm({
      sku: "",
      barcode: "",
      nombre: "",
      descripcion: "",
      unidad_medida: "NIU",
      precio_venta: "",
      costo_unitario: "",
      afecto_igv: true,
      codigo_tipo_afectacion: "10",
      category: "",
      is_active: true,
    });
    setOpen(true);
  };

  const openEdit = (row: Product) => {
    setEditing(row);
    setForm({
      sku: (row.sku as string) || "",
      barcode: (row.barcode as string) || "",
      nombre: (row.nombre as string) || "",
      descripcion: (row.descripcion as string) || "",
      unidad_medida: (row.unidad_medida as string) || "NIU",
      precio_venta: String(row.precio_venta ?? ""),
      costo_unitario: String(row.costo_unitario ?? ""),
      afecto_igv: row.afecto_igv !== false,
      codigo_tipo_afectacion: (row.codigo_tipo_afectacion as string) || "10",
      category: (row.category as string) || "",
      is_active: row.is_active !== false,
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.nombre.trim()) {
      toast({ title: "Nombre es obligatorio", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      const payload = {
        sku: form.sku.trim() || undefined,
        barcode: form.barcode.trim() || undefined,
        nombre: form.nombre.trim(),
        descripcion: form.descripcion.trim() || undefined,
        unidad_medida: form.unidad_medida,
        precio_venta: form.precio_venta ? parseFloat(form.precio_venta) : 0,
        costo_unitario: form.costo_unitario ? parseFloat(form.costo_unitario) : 0,
        afecto_igv: form.afecto_igv,
        codigo_tipo_afectacion: form.codigo_tipo_afectacion,
        category: form.category || null,
        is_active: form.is_active,
      };
      if (editing) {
        await api.patch(`/products/${editing.id}/`, payload);
        toast({ title: "Producto actualizado" });
      } else {
        await api.post("/products/", payload);
        toast({ title: "Producto creado" });
      }
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Productos</h1>
          <p className="erp-page-subtitle">Catálogo de productos. Filtre por almacén para ver solo los que tienen stock en ese almacén.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo Producto
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar producto" : "Nuevo producto"}</DialogTitle>
                <DialogDescription>SKU, nombre, categoría, precios y unidad.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>SKU</Label>
                    <Input value={form.sku} onChange={(e) => setForm((f) => ({ ...f, sku: e.target.value }))} placeholder="Código" />
                  </div>
                  <div className="grid gap-2">
                    <Label>Código de barras</Label>
                    <Input value={form.barcode} onChange={(e) => setForm((f) => ({ ...f, barcode: e.target.value }))} />
                  </div>
                </div>
                <div className="grid gap-2">
                  <Label>Nombre *</Label>
                  <Input value={form.nombre} onChange={(e) => setForm((f) => ({ ...f, nombre: e.target.value }))} required />
                </div>
                <div className="grid gap-2">
                  <Label>Categoría</Label>
                  <Select value={form.category} onValueChange={(v) => setForm((f) => ({ ...f, category: v }))}>
                    <SelectTrigger><SelectValue placeholder="Ninguna" /></SelectTrigger>
                    <SelectContent>
                      <SelectItem value="">Ninguna</SelectItem>
                      {categories.map((c) => (
                        <SelectItem key={c.id} value={c.id}>{c.name}</SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="grid gap-2">
                  <Label>Unidad de medida</Label>
                  <Select value={form.unidad_medida} onValueChange={(v) => setForm((f) => ({ ...f, unidad_medida: v }))}>
                    <SelectTrigger><SelectValue /></SelectTrigger>
                    <SelectContent>
                      {UNIDADES.map((u) => (
                        <SelectItem key={u.value} value={u.value}>{u.label}</SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Precio venta (S/)</Label>
                    <Input type="number" step="0.01" min="0" value={form.precio_venta} onChange={(e) => setForm((f) => ({ ...f, precio_venta: e.target.value }))} />
                  </div>
                  <div className="grid gap-2">
                    <Label>Costo (S/)</Label>
                    <Input type="number" step="0.01" min="0" value={form.costo_unitario} onChange={(e) => setForm((f) => ({ ...f, costo_unitario: e.target.value }))} />
                  </div>
                </div>
                <div className="grid gap-2">
                  <Label>Descripción</Label>
                  <Input value={form.descripcion} onChange={(e) => setForm((f) => ({ ...f, descripcion: e.target.value }))} />
                </div>
                <div className="flex items-center gap-2">
                  <input type="checkbox" id="afecto_igv" checked={form.afecto_igv} onChange={(e) => setForm((f) => ({ ...f, afecto_igv: e.target.checked }))} />
                  <Label htmlFor="afecto_igv">Afecto a IGV</Label>
                </div>
                {editing && (
                  <div className="flex items-center gap-2">
                    <input type="checkbox" id="is_active" checked={form.is_active} onChange={(e) => setForm((f) => ({ ...f, is_active: e.target.checked }))} />
                    <Label htmlFor="is_active">Activo</Label>
                  </div>
                )}
              </div>
              <DialogFooter>
                <Button type="button" variant="outline" onClick={() => setOpen(false)}>Cancelar</Button>
                <Button type="submit" disabled={saving}>{saving ? "Guardando…" : "Guardar"}</Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>
      <div className="flex flex-wrap gap-3 mb-4">
        <div className="relative flex-1 min-w-[200px] max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por nombre o SKU…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <div className="flex items-center gap-2">
          <Label className="text-sm text-muted-foreground whitespace-nowrap">En almacén:</Label>
          <Select value={warehouseFilter} onValueChange={setWarehouseFilter}>
            <SelectTrigger className="w-[180px]">
              <SelectValue placeholder="Todos" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="">Todos los productos</SelectItem>
              {warehouses.map((w) => (
                <SelectItem key={w.id} value={w.id}>{w.name}</SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable
        columns={[...getColumns(categories), { key: "_action", label: "", render: (row) => <Button variant="ghost" size="sm" onClick={() => openEdit(row)}><Pencil className="h-4 w-4" /></Button> }]}
        data={filtered}
        isLoading={isLoading}
      />
    </div>
  );
};

export default Products;
