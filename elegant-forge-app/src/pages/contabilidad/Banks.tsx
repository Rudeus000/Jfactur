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

interface Bank {
  id: string;
  name: string;
  code?: string;
  account_number?: string;
  cci?: string;
  is_active?: boolean;
  [key: string]: unknown;
}

const Banks = () => {
  const { data, isLoading, refresh } = useApiList<Bank>({ endpoint: "/banks/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Bank | null>(null);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({ name: "", code: "", account_number: "", cci: "", is_active: true });
  const { toast } = useToast();

  const openCreate = () => {
    setEditing(null);
    setForm({ name: "", code: "", account_number: "", cci: "", is_active: true });
    setOpen(true);
  };

  const openEdit = (row: Bank) => {
    setEditing(row);
    setForm({
      name: row.name || "",
      code: row.code || "",
      account_number: row.account_number || "",
      cci: row.cci || "",
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
        account_number: form.account_number.trim() || undefined,
        cci: form.cci.trim() || undefined,
        is_active: form.is_active,
      };
      if (editing) {
        await api.patch(`/banks/${editing.id}/`, payload);
        toast({ title: "Banco actualizado" });
      } else {
        await api.post("/banks/", payload);
        toast({ title: "Banco creado" });
      }
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const columns: Column<Bank>[] = [
    { key: "code", label: "Código" },
    { key: "name", label: "Banco" },
    { key: "account_number", label: "Nº Cuenta" },
    { key: "cci", label: "CCI", className: "hidden lg:table-cell" },
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
    (b) => b.name?.toLowerCase().includes(search.toLowerCase()) || (b.code as string)?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Bancos</h1>
          <p className="erp-page-subtitle">Bancos para asociar a cuentas y pagos por transferencia.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo Banco
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar banco" : "Nuevo banco"}</DialogTitle>
                <DialogDescription>Nombre, código, número de cuenta y CCI.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Nombre *</Label>
                    <Input
                      value={form.name}
                      onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))}
                      placeholder="BCP"
                      required
                    />
                  </div>
                  <div className="grid gap-2">
                    <Label>Código</Label>
                    <Input
                      value={form.code}
                      onChange={(e) => setForm((f) => ({ ...f, code: e.target.value }))}
                      placeholder="BCP01"
                    />
                  </div>
                </div>
                <div className="grid gap-2">
                  <Label>Nº de cuenta</Label>
                  <Input
                    value={form.account_number}
                    onChange={(e) => setForm((f) => ({ ...f, account_number: e.target.value }))}
                    placeholder="191-12345678-0-12"
                  />
                </div>
                <div className="grid gap-2">
                  <Label>CCI</Label>
                  <Input
                    value={form.cci}
                    onChange={(e) => setForm((f) => ({ ...f, cci: e.target.value }))}
                    placeholder="00219100123456780012"
                  />
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
                  {saving ? "Guardando…" : editing ? "Guardar" : "Crear banco"}
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
        emptyMessage="No hay bancos registrados"
      />
    </div>
  );
};

export default Banks;
