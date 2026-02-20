import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
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

interface Warehouse {
  id: string;
  name: string;
  code?: string;
}

interface CashRegister {
  id: string;
  name: string;
  code?: string;
  warehouse?: string;
  warehouse_name?: string;
  is_active?: boolean;
  [key: string]: unknown;
}

const columns: Column<CashRegister>[] = [
  { key: "code", label: "Código" },
  { key: "name", label: "Caja" },
  { key: "warehouse_name", label: "Almacén (punto de venta)" },
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

const CashRegisters = () => {
  const [data, setData] = useState<CashRegister[]>([]);
  const [warehouses, setWarehouses] = useState<Warehouse[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<CashRegister | null>(null);
  const [form, setForm] = useState({ name: "", code: "", warehouse: "", is_active: true });
  const [saving, setSaving] = useState(false);
  const { toast } = useToast();

  const fetchData = async () => {
    setLoading(true);
    try {
      const [regRes, whRes] = await Promise.all([
        api.get<CashRegister[]>("/cash-registers/"),
        api.get<Warehouse[]>("/warehouses/"),
      ]);
      setData(Array.isArray(regRes.data) ? regRes.data : []);
      setWarehouses(Array.isArray(whRes.data) ? whRes.data : []);
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, []);

  const filtered = data.filter(
    (c) =>
      c.name?.toLowerCase().includes(search.toLowerCase()) ||
      (c.code as string)?.toLowerCase().includes(search.toLowerCase())
  );

  const openCreate = () => {
    setEditing(null);
    setForm({ name: "", code: "", warehouse: "", is_active: true });
    setOpen(true);
  };

  const openEdit = (row: CashRegister) => {
    setEditing(row);
    setForm({
      name: (row.name as string) || "",
      code: (row.code as string) || "",
      warehouse: (row.warehouse as string) || "",
      is_active: row.is_active !== false,
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = {
        name: form.name.trim(),
        code: form.code.trim() || undefined,
        warehouse: form.warehouse || null,
        is_active: form.is_active,
      };
      if (editing) {
        await api.patch(`/cash-registers/${editing.id}/`, payload);
        toast({ title: "Caja actualizada" });
      } else {
        await api.post("/cash-registers/", payload);
        toast({ title: "Caja creada" });
      }
      setOpen(false);
      fetchData();
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
          <h1 className="erp-page-title">Cajas</h1>
          <p className="erp-page-subtitle">Cajas registradoras. Asocia cada caja a un almacén (punto de venta) y asígnala a usuarios para aperturas.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button
            onClick={() => {
              openCreate();
              setOpen(true);
            }}
          >
            <Plus className="h-4 w-4 mr-2" />
            Nueva Caja
          </Button>
          <DialogContent>
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar caja" : "Nueva caja"}</DialogTitle>
                <DialogDescription>Nombre, código y almacén al que pertenece la caja.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                  <Label htmlFor="name">Nombre *</Label>
                  <Input
                    id="name"
                    value={form.name}
                    onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))}
                    placeholder="Ej. Caja Principal"
                    required
                  />
                </div>
                <div className="grid gap-2">
                  <Label htmlFor="code">Código</Label>
                  <Input
                    id="code"
                    value={form.code}
                    onChange={(e) => setForm((f) => ({ ...f, code: e.target.value }))}
                    placeholder="Ej. CAJA01"
                  />
                </div>
                <div className="grid gap-2">
                  <Label>Almacén (punto de venta)</Label>
                  <Select
                    value={form.warehouse || "none"}
                    onValueChange={(v) => setForm((f) => ({ ...f, warehouse: v === "none" ? "" : v }))}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Sin almacén" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="none">Sin almacén</SelectItem>
                      {warehouses.map((w) => (
                        <SelectItem key={w.id} value={w.id}>
                          {w.name} {w.code ? `(${w.code})` : ""}
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
                  {editing ? "Guardar" : "Crear caja"}
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
        <Button variant="outline" size="icon" onClick={() => fetchData()}>
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
              <Button
                variant="ghost"
                size="icon"
                onClick={() => {
                  openEdit(item);
                  setOpen(true);
                }}
                title="Editar"
              >
                <Pencil className="h-4 w-4" />
              </Button>
            ),
          },
        ]}
        data={filtered}
        isLoading={loading}
      />
    </div>
  );
};

export default CashRegisters;
