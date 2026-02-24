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
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { Switch } from "@/components/ui/switch";
import { useToast } from "@/hooks/use-toast";

interface Warehouse {
  id: string;
  name: string;
  code: string;
  is_active?: boolean;
  [key: string]: unknown;
}

const columnsBase: Column<Warehouse>[] = [
  { key: "code", label: "Código" },
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

const Warehouses = () => {
  const { data, isLoading, refresh, deleteItem } = useApiList<Warehouse>({ endpoint: "/warehouses/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Warehouse | null>(null);
  const [saving, setSaving] = useState(false);
  const [toDelete, setToDelete] = useState<Warehouse | null>(null);
  const { toast } = useToast();

  const [form, setForm] = useState({ code: "", name: "", is_active: true });

  const openCreate = () => {
    setEditing(null);
    setForm({ code: "", name: "", is_active: true });
    setOpen(true);
  };

  const openEdit = (row: Warehouse) => {
    setEditing(row);
    setForm({
      code: (row.code as string) ?? "",
      name: (row.name as string) ?? "",
      is_active: row.is_active !== false,
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const code = form.code.trim();
    const name = form.name.trim();
    if (!code || !name) {
      toast({ title: "Código y nombre son obligatorios", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      const payload = { code, name, is_active: form.is_active };
      if (editing) {
        await api.patch(`/warehouses/${editing.id}/`, payload);
        toast({ title: "Almacén actualizado" });
      } else {
        await api.post("/warehouses/", payload);
        toast({ title: "Almacén creado" });
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

  const filtered = data.filter(
    (w) => w.name?.toLowerCase().includes(search.toLowerCase()) || w.code?.toLowerCase().includes(search.toLowerCase())
  );

  const columns: Column<Warehouse>[] = [
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
          <h1 className="erp-page-title">Almacenes</h1>
          <p className="erp-page-subtitle">Gestión de almacenes</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo Almacén
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar almacén" : "Nuevo almacén"}</DialogTitle>
                <DialogDescription>Código, nombre y estado activo del almacén.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                  <Label htmlFor="wh-code">Código *</Label>
                  <Input
                    id="wh-code"
                    value={form.code}
                    onChange={(e) => setForm((f) => ({ ...f, code: e.target.value }))}
                    placeholder="Ej: ALM01"
                    required
                  />
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="wh-name">Nombre *</Label>
                  <Input
                    id="wh-name"
                    value={form.name}
                    onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))}
                    placeholder="Nombre del almacén"
                    required
                  />
                </div>
                <div className="flex items-center gap-2">
                  <Switch id="wh-active" checked={form.is_active} onCheckedChange={(v) => setForm((f) => ({ ...f, is_active: v }))} />
                  <Label htmlFor="wh-active">Activo</Label>
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
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por nombre o código…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay almacenes. Cree al menos uno para series y stock." />

      <AlertDialog open={!!toDelete} onOpenChange={(open) => !open && setToDelete(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Eliminar almacén?</AlertDialogTitle>
            <AlertDialogDescription>
              Se eliminará el almacén &quot;{toDelete?.name}&quot; ({toDelete?.code}). Esta acción no se puede deshacer.
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

export default Warehouses;
