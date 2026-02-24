import { useState, useEffect } from "react";
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

const NO_PARENT = "__none__";

interface Category {
  id: string;
  name: string;
  parent?: string | null;
  is_active?: boolean;
  [key: string]: unknown;
}

const columns: Column<Category>[] = [
  { key: "name", label: "Nombre" },
  {
    key: "is_active",
    label: "Activo",
    render: (item) => (
      <span className={`erp-status-badge ${item.is_active !== false ? "bg-success/10 text-success" : "bg-muted text-muted-foreground"}`}>
        {item.is_active !== false ? "Sí" : "No"}
      </span>
    ),
  },
];

const Categories = () => {
  const { data, isLoading, refresh, deleteItem } = useApiList<Category>({ endpoint: "/categories/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Category | null>(null);
  const [saving, setSaving] = useState(false);
  const [toDelete, setToDelete] = useState<Category | null>(null);
  const [categories, setCategories] = useState<Category[]>([]);
  const { toast } = useToast();

  const [form, setForm] = useState({ name: "", parent: NO_PARENT, is_active: true });

  useEffect(() => {
    api.get<Category[]>("/categories/").then((r) => setCategories(Array.isArray(r.data) ? r.data : [])).catch(() => setCategories([]));
  }, [open]);

  const openCreate = () => {
    setEditing(null);
    setForm({ name: "", parent: NO_PARENT, is_active: true });
    setOpen(true);
  };

  const openEdit = (row: Category) => {
    setEditing(row);
    setForm({
      name: (row.name as string) || "",
      parent: (row.parent as string) || NO_PARENT,
      is_active: row.is_active !== false,
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.name.trim()) {
      toast({ title: "Nombre es obligatorio", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      const payload = { name: form.name.trim(), parent: form.parent === NO_PARENT || !form.parent ? null : form.parent, is_active: form.is_active };
      if (editing) {
        await api.patch(`/categories/${editing.id}/`, payload);
        toast({ title: "Categoría actualizada" });
      } else {
        await api.post("/categories/", payload);
        toast({ title: "Categoría creada" });
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

  const filtered = data.filter((c) => c.name?.toLowerCase().includes(search.toLowerCase()));

  const columnsWithActions: Column<Category>[] = [
    ...columns,
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
          <h1 className="erp-page-title">Categorías</h1>
          <p className="erp-page-subtitle">Categorías de productos para organizar el catálogo</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nueva Categoría
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar categoría" : "Nueva categoría"}</DialogTitle>
                <DialogDescription>Nombre y categoría padre opcional.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                  <Label>Nombre *</Label>
                  <Input value={form.name} onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))} placeholder="Ej. Bebidas" required />
                </div>
                <div className="grid gap-2">
                  <Label>Categoría padre</Label>
                  <Select value={form.parent || NO_PARENT} onValueChange={(v) => setForm((f) => ({ ...f, parent: v }))}>
                    <SelectTrigger><SelectValue placeholder="Ninguna" /></SelectTrigger>
                    <SelectContent>
                      <SelectItem value={NO_PARENT}>Ninguna</SelectItem>
                      {categories.filter((c) => !editing || c.id !== editing.id).map((c) => (
                        <SelectItem key={c.id} value={c.id}>{c.name as string}</SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
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
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por nombre…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columnsWithActions} data={filtered} isLoading={isLoading} />

      <AlertDialog open={!!toDelete} onOpenChange={(open) => !open && setToDelete(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Eliminar categoría?</AlertDialogTitle>
            <AlertDialogDescription>
              Se eliminará la categoría &quot;{toDelete?.name}&quot;. Esta acción no se puede deshacer.
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

export default Categories;
