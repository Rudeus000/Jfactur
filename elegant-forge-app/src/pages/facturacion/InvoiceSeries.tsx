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

const tipoLabels: Record<string, string> = {
  "01": "Factura",
  "03": "Boleta",
};

const TIPOS = [
  { value: "01", label: "Factura" },
  { value: "03", label: "Boleta" },
];

interface InvoiceSeriesItem {
  id: string;
  serie: string;
  tipo_documento: string;
  warehouse?: string;
  warehouse_name?: string;
  next_number?: number;
  last_number?: number;
  is_default?: boolean;
  is_active?: boolean;
  [key: string]: unknown;
}

interface Warehouse {
  id: string;
  name: string;
  code?: string;
}

const InvoiceSeries = () => {
  const { data, isLoading, refresh } = useApiList<InvoiceSeriesItem>({ endpoint: "/invoice-series/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<InvoiceSeriesItem | null>(null);
  const [saving, setSaving] = useState(false);
  const [warehouses, setWarehouses] = useState<Warehouse[]>([]);
  const [form, setForm] = useState({
    tipo_documento: "01",
    serie: "",
    warehouse: "__none__",
    is_default: false,
    is_active: true,
  });
  const { toast } = useToast();

  useEffect(() => {
    if (open) {
      api.get<Warehouse[]>("/warehouses/").then((r) => {
        setWarehouses(Array.isArray(r.data) ? r.data : []);
      }).catch(() => setWarehouses([]));
    }
  }, [open]);

  const openCreate = () => {
    setEditing(null);
    setForm({
      tipo_documento: "01",
      serie: "",
      warehouse: warehouses[0]?.id || "__none__",
      is_default: false,
      is_active: true,
    });
    setOpen(true);
  };

  const openEdit = (row: InvoiceSeriesItem) => {
    setEditing(row);
    setForm({
      tipo_documento: row.tipo_documento || "01",
      serie: row.serie || "",
      warehouse: (row.warehouse as string) || "__none__",
      is_default: row.is_default || false,
      is_active: row.is_active !== false,
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.serie.trim()) {
      toast({ title: "La serie es obligatoria (ej. F001, B001)", variant: "destructive" });
      return;
    }
    if (form.warehouse === "__none__") {
      toast({ title: "Seleccione un almacén", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      const payload = {
        tipo_documento: form.tipo_documento,
        serie: form.serie.trim().toUpperCase(),
        warehouse: form.warehouse,
        is_default: form.is_default,
        is_active: form.is_active,
      };
      if (editing) {
        await api.patch(`/invoice-series/${editing.id}/`, payload);
        toast({ title: "Serie actualizada" });
      } else {
        await api.post("/invoice-series/", payload);
        toast({ title: "Serie creada" });
      }
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const columns: Column<InvoiceSeriesItem>[] = [
    { key: "serie", label: "Serie" },
    {
      key: "tipo_documento",
      label: "Tipo",
      render: (item) => tipoLabels[item.tipo_documento as string] || item.tipo_documento,
    },
    {
      key: "next_number",
      label: "Siguiente Nº",
      className: "text-right",
    },
    {
      key: "is_default",
      label: "Por defecto",
      render: (item) => (item.is_default ? "Sí" : "—"),
    },
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

  const filtered = data.filter((s) => (s.serie as string)?.toLowerCase().includes(search.toLowerCase()));

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Series</h1>
          <p className="erp-page-subtitle">Series de facturación (F001, B001…). Cada serie se asocia a un almacén.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nueva Serie
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar serie" : "Nueva serie"}</DialogTitle>
                <DialogDescription>Tipo de documento, código de serie y almacén asociado.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Tipo *</Label>
                    <Select value={form.tipo_documento} onValueChange={(v) => setForm((f) => ({ ...f, tipo_documento: v }))}>
                      <SelectTrigger>
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        {TIPOS.map((t) => (
                          <SelectItem key={t.value} value={t.value}>
                            {t.label}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="grid gap-2">
                    <Label>Serie *</Label>
                    <Input
                      value={form.serie}
                      onChange={(e) => setForm((f) => ({ ...f, serie: e.target.value }))}
                      placeholder="F001"
                      maxLength={10}
                      required
                    />
                  </div>
                </div>
                <div className="grid gap-2">
                  <Label>Almacén *</Label>
                  <Select value={form.warehouse} onValueChange={(v) => setForm((f) => ({ ...f, warehouse: v }))}>
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccionar almacén" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="__none__">Seleccionar</SelectItem>
                      {warehouses.map((w) => (
                        <SelectItem key={w.id} value={w.id}>
                          {w.name} {w.code ? `(${w.code})` : ""}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="flex items-center gap-4">
                  <div className="flex items-center gap-2">
                    <input
                      type="checkbox"
                      id="is_default"
                      checked={form.is_default}
                      onChange={(e) => setForm((f) => ({ ...f, is_default: e.target.checked }))}
                      className="rounded border-input"
                    />
                    <Label htmlFor="is_default">Por defecto</Label>
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
              </div>
              <DialogFooter>
                <Button type="button" variant="outline" onClick={() => setOpen(false)}>
                  Cancelar
                </Button>
                <Button type="submit" disabled={saving}>
                  {saving ? "Guardando…" : editing ? "Guardar" : "Crear serie"}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por serie…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
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
        emptyMessage="No hay series. Cree una serie de factura o boleta asociada a un almacén."
      />
    </div>
  );
};

export default InvoiceSeries;
