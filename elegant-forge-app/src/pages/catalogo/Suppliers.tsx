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

interface Supplier {
  id: string;
  tipo_documento: string;
  numero_documento: string;
  razon_social: string;
  direccion?: string;
  email?: string;
  telefono?: string;
  is_active?: boolean;
  [key: string]: unknown;
}

const TIPO_DOC = [
  { value: "1", label: "DNI" },
  { value: "6", label: "RUC" },
];

const columns: Column<Supplier>[] = [
  { key: "numero_documento", label: "Documento" },
  { key: "razon_social", label: "Razón Social" },
  { key: "email", label: "Email" },
  { key: "telefono", label: "Teléfono" },
  { key: "direccion", label: "Dirección", className: "hidden lg:table-cell" },
];

const Suppliers = () => {
  const { data, isLoading, refresh, deleteItem } = useApiList<Supplier>({ endpoint: "/suppliers/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Supplier | null>(null);
  const [saving, setSaving] = useState(false);
  const [toDelete, setToDelete] = useState<Supplier | null>(null);
  const { toast } = useToast();

  const [form, setForm] = useState({
    tipo_documento: "6",
    numero_documento: "",
    razon_social: "",
    nombre_comercial: "",
    direccion: "",
    email: "",
    telefono: "",
    is_active: true,
  });

  const openCreate = () => {
    setEditing(null);
    setForm({
      tipo_documento: "6",
      numero_documento: "",
      razon_social: "",
      nombre_comercial: "",
      direccion: "",
      email: "",
      telefono: "",
      is_active: true,
    });
    setOpen(true);
  };

  const openEdit = (row: Supplier) => {
    setEditing(row);
    setForm({
      tipo_documento: (row.tipo_documento as string) || "6",
      numero_documento: (row.numero_documento as string) || "",
      razon_social: (row.razon_social as string) || "",
      nombre_comercial: (row.nombre_comercial as string) || "",
      direccion: (row.direccion as string) || "",
      email: (row.email as string) || "",
      telefono: (row.telefono as string) || "",
      is_active: row.is_active !== false,
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.numero_documento.trim() || !form.razon_social.trim()) {
      toast({ title: "Documento y razón social son obligatorios", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      const payload = {
        tipo_documento: form.tipo_documento,
        numero_documento: form.numero_documento.trim(),
        razon_social: form.razon_social.trim(),
        nombre_comercial: form.nombre_comercial.trim() || undefined,
        direccion: form.direccion.trim() || undefined,
        email: form.email.trim() || undefined,
        telefono: form.telefono.trim() || undefined,
        is_active: form.is_active,
      };
      if (editing) {
        await api.patch(`/suppliers/${editing.id}/`, payload);
        toast({ title: "Proveedor actualizado" });
      } else {
        await api.post("/suppliers/", payload);
        toast({ title: "Proveedor creado" });
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
    (s) =>
      s.razon_social?.toLowerCase().includes(search.toLowerCase()) ||
      s.numero_documento?.includes(search)
  );

  const columnsWithActions: Column<Supplier>[] = [
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
          <h1 className="erp-page-title">Proveedores</h1>
          <p className="erp-page-subtitle">Directorio de proveedores para compras</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo Proveedor
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar proveedor" : "Nuevo proveedor"}</DialogTitle>
                <DialogDescription>Documento y razón social.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Tipo documento</Label>
                    <Select value={form.tipo_documento} onValueChange={(v) => setForm((f) => ({ ...f, tipo_documento: v }))}>
                      <SelectTrigger><SelectValue /></SelectTrigger>
                      <SelectContent>
                        {TIPO_DOC.map((t) => (
                          <SelectItem key={t.value} value={t.value}>{t.label}</SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="grid gap-2">
                    <Label>Nº documento *</Label>
                    <Input
                      value={form.numero_documento}
                      onChange={(e) => setForm((f) => ({ ...f, numero_documento: e.target.value }))}
                      placeholder="RUC / DNI"
                      required
                    />
                  </div>
                </div>
                <div className="grid gap-2">
                  <Label>Razón social *</Label>
                  <Input value={form.razon_social} onChange={(e) => setForm((f) => ({ ...f, razon_social: e.target.value }))} required />
                </div>
                <div className="grid gap-2">
                  <Label>Dirección</Label>
                  <Input value={form.direccion} onChange={(e) => setForm((f) => ({ ...f, direccion: e.target.value }))} />
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Email</Label>
                    <Input type="email" value={form.email} onChange={(e) => setForm((f) => ({ ...f, email: e.target.value }))} />
                  </div>
                  <div className="grid gap-2">
                    <Label>Teléfono</Label>
                    <Input value={form.telefono} onChange={(e) => setForm((f) => ({ ...f, telefono: e.target.value }))} />
                  </div>
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
          <Input placeholder="Buscar por nombre o documento…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columnsWithActions} data={filtered} isLoading={isLoading} />

      <AlertDialog open={!!toDelete} onOpenChange={(open) => !open && setToDelete(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Eliminar proveedor?</AlertDialogTitle>
            <AlertDialogDescription>
              Se eliminará el proveedor &quot;{toDelete?.razon_social}&quot; ({toDelete?.tipo_documento === "1" ? "DNI" : "RUC"} {toDelete?.numero_documento}). Esta acción no se puede deshacer.
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

export default Suppliers;
