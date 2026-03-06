import { useState, useEffect } from "react";
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { useToast } from "@/hooks/use-toast";

interface AccountType {
  id: string;
  code: string;
  name: string;
}

interface Account {
  id: string;
  code: string;
  name: string;
  account_type?: string;
  account_type_name?: string;
  parent?: string | null;
  order?: number;
  is_active?: boolean;
  [key: string]: unknown;
}

const Accounts = () => {
  const { data, isLoading, refresh } = useApiList<Account>({ endpoint: "/accounts/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Account | null>(null);
  const [saving, setSaving] = useState(false);
  const [accountTypes, setAccountTypes] = useState<AccountType[]>([]);
  const [form, setForm] = useState({ code: "", name: "", account_type: "__none__", parent: "__none__", order: "0", is_active: true });
  const { toast } = useToast();

  useEffect(() => {
    if (open) {
      api.get<AccountType[]>("/account-types/").then((r) => {
        setAccountTypes(Array.isArray(r.data) ? r.data : []);
      }).catch(() => setAccountTypes([]));
    }
  }, [open]);

  const openCreate = () => {
    setEditing(null);
    setForm({ code: "", name: "", account_type: accountTypes[0]?.id || "__none__", parent: "__none__", order: "0", is_active: true });
    setOpen(true);
  };

  const openEdit = (row: Account) => {
    setEditing(row);
    setForm({
      code: row.code || "",
      name: row.name || "",
      account_type: row.account_type || "__none__",
      parent: row.parent || "__none__",
      order: String(row.order ?? 0),
      is_active: row.is_active !== false,
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.code.trim() || !form.name.trim()) {
      toast({ title: "Código y nombre son obligatorios", variant: "destructive" });
      return;
    }
    if (form.account_type === "__none__") {
      toast({ title: "Seleccione un tipo de cuenta", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      const payload = {
        code: form.code.trim(),
        name: form.name.trim(),
        account_type: form.account_type,
        parent: form.parent === "__none__" ? null : form.parent,
        order: parseInt(form.order) || 0,
        is_active: form.is_active,
      };
      if (editing) {
        await api.patch(`/accounts/${editing.id}/`, payload);
        toast({ title: "Cuenta actualizada" });
      } else {
        await api.post("/accounts/", payload);
        toast({ title: "Cuenta creada" });
      }
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const columns: Column<Account>[] = [
    { key: "code", label: "Código" },
    { key: "name", label: "Nombre" },
    { key: "account_type_name", label: "Tipo", render: (item) => (item.account_type_name as string) || "—" },
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
    (a) => a.code?.toLowerCase().includes(search.toLowerCase()) || a.name?.toLowerCase().includes(search.toLowerCase())
  );

  const parentAccounts = data.filter((a) => !editing || a.id !== editing.id);

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Plan de Cuentas</h1>
          <p className="erp-page-subtitle">Cuentas contables. Busque por código o nombre.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nueva Cuenta
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar cuenta" : "Nueva cuenta"}</DialogTitle>
                <DialogDescription>Código, nombre, tipo de cuenta y cuenta padre (opcional).</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Código *</Label>
                    <Input
                      value={form.code}
                      onChange={(e) => setForm((f) => ({ ...f, code: e.target.value }))}
                      placeholder="101"
                      required
                    />
                  </div>
                  <div className="grid gap-2">
                    <Label>Orden</Label>
                    <Input
                      type="number"
                      value={form.order}
                      onChange={(e) => setForm((f) => ({ ...f, order: e.target.value }))}
                      min="0"
                    />
                  </div>
                </div>
                <div className="grid gap-2">
                  <Label>Nombre *</Label>
                  <Input
                    value={form.name}
                    onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))}
                    placeholder="Efectivo y equivalentes"
                    required
                  />
                </div>
                <div className="grid gap-2">
                  <Label>Tipo de cuenta *</Label>
                  <Select value={form.account_type} onValueChange={(v) => setForm((f) => ({ ...f, account_type: v }))}>
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccionar tipo" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="__none__">Seleccionar</SelectItem>
                      {accountTypes.map((t) => (
                        <SelectItem key={t.id} value={t.id}>
                          {t.code ? `${t.code} — ` : ""}{t.name}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="grid gap-2">
                  <Label>Cuenta padre</Label>
                  <Select value={form.parent} onValueChange={(v) => setForm((f) => ({ ...f, parent: v }))}>
                    <SelectTrigger>
                      <SelectValue placeholder="Sin padre" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="__none__">Sin cuenta padre</SelectItem>
                      {parentAccounts.map((a) => (
                        <SelectItem key={a.id} value={a.id}>
                          {a.code} — {a.name}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="flex items-center gap-2">
                  <input
                    type="checkbox"
                    id="is_active"
                    checked={form.is_active}
                    onChange={(e) => setForm((f) => ({ ...f, is_active: e.target.checked }))}
                    className="rounded border-input"
                  />
                  <Label htmlFor="is_active">Activa</Label>
                </div>
              </div>
              <DialogFooter>
                <Button type="button" variant="outline" onClick={() => setOpen(false)}>
                  Cancelar
                </Button>
                <Button type="submit" disabled={saving}>
                  {saving ? "Guardando…" : editing ? "Guardar" : "Crear cuenta"}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por código o nombre…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
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
        emptyMessage="No hay cuentas en el plan"
      />
    </div>
  );
};

export default Accounts;
