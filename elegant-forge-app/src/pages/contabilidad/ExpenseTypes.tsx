import { useState } from "react";
import api, { getErrorMessage } from "@/lib/api";
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
import { useToast } from "@/hooks/use-toast";

interface ExpenseType {
  id: string;
  code?: string;
  name: string;
  is_active?: boolean;
  [key: string]: unknown;
}

const ExpenseTypes = () => {
  const { data, isLoading, refresh } = useApiList<ExpenseType>({ endpoint: "/expense-types/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<ExpenseType | null>(null);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({ name: "", code: "", is_active: true });
  const { toast } = useToast();

  const openCreate = () => {
    setEditing(null);
    setForm({ name: "", code: "", is_active: true });
    setOpen(true);
  };

  const openEdit = (row: ExpenseType) => {
    setEditing(row);
    setForm({
      name: row.name || "",
      code: row.code || "",
      is_active: row.is_active !== false,
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.name.trim()) {
      toast({ title: "El nombre es obligatorio", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      const payload = {
        name: form.name.trim(),
        code: form.code.trim() || undefined,
        is_active: form.is_active,
      };
      if (editing) {
        await api.patch(`/expense-types/${editing.id}/`, payload);
        toast({ title: "Tipo de gasto actualizado" });
      } else {
        await api.post("/expense-types/", payload);
        toast({ title: "Tipo de gasto creado" });
      }
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const columns: Column<ExpenseType>[] = [
    { key: "code", label: "Código", render: (item) => (item.code as string) || "—" },
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

  const filtered = data.filter(
    (t) => t.name?.toLowerCase().includes(search.toLowerCase()) || (t.code as string)?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Tipos de Gasto</h1>
          <p className="erp-page-subtitle">Categorías de gasto para clasificar egresos. Cree tipos antes de registrar gastos.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo Tipo
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar tipo de gasto" : "Nuevo tipo de gasto"}</DialogTitle>
                <DialogDescription>Nombre y código opcional para clasificar gastos.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Nombre *</Label>
                    <Input
                      value={form.name}
                      onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))}
                      placeholder="Alquiler"
                      required
                    />
                  </div>
                  <div className="grid gap-2">
                    <Label>Código</Label>
                    <Input
                      value={form.code}
                      onChange={(e) => setForm((f) => ({ ...f, code: e.target.value }))}
                      placeholder="ALQ01"
                    />
                  </div>
                </div>
                <div className="flex items-center gap-2">
                  <input
                    type="checkbox"
                    id="is_active"
                    checked={form.is_active}
                    onChange={(e) => setForm((f) => ({ ...f, is_active: e.target.checked }))}
                    className="rounded border-input"
                  />
                  <Label htmlFor="is_active">Activo</Label>
                </div>
              </div>
              <DialogFooter>
                <Button type="button" variant="outline" onClick={() => setOpen(false)}>
                  Cancelar
                </Button>
                <Button type="submit" disabled={saving}>
                  {saving ? "Guardando…" : editing ? "Guardar" : "Crear tipo"}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable
        columns={[
          ...columns,
          {
            key: "_actions",
            label: "",
            render: (item) => (
              <Button variant="ghost" size="icon" onClick={() => openEdit(item)} title="Editar">
                <Pencil className="h-4 w-4" />
              </Button>
            ),
          },
        ]}
        data={filtered}
        isLoading={isLoading}
        emptyMessage="No hay tipos de gasto. Cree uno para poder registrar gastos."
      />
    </div>
  );
};

export default ExpenseTypes;
