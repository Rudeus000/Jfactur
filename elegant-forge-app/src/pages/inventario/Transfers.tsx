import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, RefreshCw, Pencil, Trash2, CheckCircle } from "lucide-react";
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

const statusLabels: Record<string, string> = {
  pending: "Pendiente",
  approved: "Aprobado",
  rejected: "Rechazado",
};

interface TransferLine {
  id?: string;
  line_number?: number;
  product: string;
  product_name?: string;
  quantity: number | string;
}

interface StockTransfer {
  id: string;
  warehouse_origin: string;
  warehouse_origin_name?: string;
  warehouse_dest: string;
  warehouse_dest_name?: string;
  date: string;
  status: string;
  notes?: string;
  lines?: TransferLine[];
  [key: string]: unknown;
}

interface Warehouse {
  id: string;
  name: string;
  code?: string;
  [key: string]: unknown;
}

interface Product {
  id: string;
  sku?: string;
  nombre: string;
  [key: string]: unknown;
}

const columnsBase: Column<StockTransfer>[] = [
  { key: "warehouse_origin_name", label: "Origen" },
  { key: "warehouse_dest_name", label: "Destino" },
  { key: "date", label: "Fecha" },
  {
    key: "status",
    label: "Estado",
    render: (item) => (
      <span
        className={`erp-status-badge ${
          item.status === "approved" ? "bg-success/10 text-success" : item.status === "rejected" ? "bg-destructive/10 text-destructive" : "bg-warning/10 text-warning"
        }`}
      >
        {statusLabels[item.status as string] || item.status}
      </span>
    ),
  },
  { key: "notes", label: "Notas", className: "hidden lg:table-cell" },
];

const Transfers = () => {
  const { data, isLoading, refresh, deleteItem } = useApiList<StockTransfer>({ endpoint: "/transfers/" });
  const { data: warehouses } = useApiList<Warehouse>({ endpoint: "/warehouses/" });
  const { data: products } = useApiList<Product>({ endpoint: "/products/" });

  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<StockTransfer | null>(null);
  const [saving, setSaving] = useState(false);
  const [validatingId, setValidatingId] = useState<string | null>(null);
  const [toDelete, setToDelete] = useState<StockTransfer | null>(null);
  const { toast } = useToast();

  const [form, setForm] = useState({
    warehouse_origin: "",
    warehouse_dest: "",
    date: new Date().toISOString().slice(0, 10),
    notes: "",
    lines: [{ product: "", quantity: "1" }] as { product: string; quantity: string }[],
  });

  useEffect(() => {
    if (!open) return;
    if (warehouses.length > 0 && !form.warehouse_origin) {
      setForm((f) => ({ ...f, warehouse_origin: warehouses[0].id, warehouse_dest: warehouses.length > 1 ? warehouses[1].id : warehouses[0].id }));
    }
  }, [open, warehouses, form.warehouse_origin]);

  const openCreate = () => {
    setEditing(null);
    setForm({
      warehouse_origin: warehouses[0]?.id ?? "",
      warehouse_dest: warehouses[1]?.id ?? warehouses[0]?.id ?? "",
      date: new Date().toISOString().slice(0, 10),
      notes: "",
      lines: [{ product: "", quantity: "1" }],
    });
    setOpen(true);
  };

  const openEdit = async (row: StockTransfer) => {
    try {
      const { data: full } = await api.get<StockTransfer>(`/transfers/${row.id}/`);
      setEditing(full);
      const lines = (full.lines as TransferLine[] | undefined) || [];
      setForm({
        warehouse_origin: (full.warehouse_origin as string) ?? "",
        warehouse_dest: (full.warehouse_dest as string) ?? "",
        date: (full.date as string).slice(0, 10),
        notes: (full.notes as string) ?? "",
        lines: lines.length > 0 ? lines.map((l) => ({ product: (l.product as string) ?? "", quantity: String(l.quantity ?? 0) })) : [{ product: "", quantity: "1" }],
      });
      setOpen(true);
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    }
  };

  const addLine = () => {
    setForm((f) => ({ ...f, lines: [...f.lines, { product: "", quantity: "1" }] }));
  };

  const updateLine = (index: number, field: "product" | "quantity", value: string) => {
    setForm((f) => ({
      ...f,
      lines: f.lines.map((l, i) => (i === index ? { ...l, [field]: value } : l)),
    }));
  };

  const removeLine = (index: number) => {
    setForm((f) => ({ ...f, lines: f.lines.filter((_, i) => i !== index) }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.warehouse_origin || !form.warehouse_dest) {
      toast({ title: "Seleccione almacén origen y destino", variant: "destructive" });
      return;
    }
    if (form.warehouse_origin === form.warehouse_dest) {
      toast({ title: "Origen y destino deben ser distintos", variant: "destructive" });
      return;
    }
    const validLines = form.lines.filter((l) => l.product && l.quantity && parseFloat(String(l.quantity).replace(",", ".")) > 0);
    if (validLines.length === 0) {
      toast({ title: "Agregue al menos una línea con producto y cantidad", variant: "destructive" });
      return;
    }
    const linesPayload = validLines.map((l, i) => ({
      product: l.product,
      quantity: parseFloat(String(l.quantity).replace(",", ".")) || 0,
      line_number: i + 1,
    }));
    setSaving(true);
    try {
      const payload = {
        warehouse_origin: form.warehouse_origin,
        warehouse_dest: form.warehouse_dest,
        date: form.date,
        notes: form.notes.trim() || undefined,
        lines: linesPayload,
      };
      if (editing) {
        await api.patch(`/transfers/${editing.id}/`, payload);
        toast({ title: "Traspaso actualizado" });
      } else {
        await api.post("/transfers/", payload);
        toast({ title: "Traspaso creado (pendiente). Valide para mover stock." });
      }
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const handleValidate = async (row: StockTransfer) => {
    if (row.status !== "pending") {
      toast({ title: "Solo se puede validar un traspaso pendiente", variant: "destructive" });
      return;
    }
    setValidatingId(row.id);
    try {
      await api.post(`/transfers/${row.id}/validate/`, {});
      toast({ title: "Traspaso validado. Stock actualizado." });
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setValidatingId(null);
    }
  };

  const handleDeleteConfirm = async () => {
    if (!toDelete) return;
    await deleteItem(toDelete.id);
    setToDelete(null);
  };

  const columns: Column<StockTransfer>[] = [
    ...columnsBase,
    {
      key: "_actions",
      label: "",
      render: (row) => (
        <div className="flex items-center gap-1" onClick={(e) => e.stopPropagation()}>
          {row.status === "pending" && (
            <>
              <Button variant="ghost" size="icon" className="h-8 w-8" onClick={() => openEdit(row)} aria-label="Editar">
                <Pencil className="h-4 w-4" />
              </Button>
              <Button
                variant="ghost"
                size="icon"
                className="h-8 w-8 text-success hover:text-success"
                onClick={() => handleValidate(row)}
                disabled={validatingId === row.id}
                aria-label="Validar"
                title="Validar traspaso (mueve stock)"
              >
                <CheckCircle className="h-4 w-4" />
              </Button>
            </>
          )}
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
          <h1 className="erp-page-title">Traspasos</h1>
          <p className="erp-page-subtitle">Transferencias entre almacenes. Cree en pendiente, valide para mover stock.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate} disabled={warehouses.length < 2}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo Traspaso
          </Button>
          <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>{editing ? "Editar traspaso" : "Nuevo traspaso"}</DialogTitle>
                <DialogDescription>Almacén origen, destino, fecha y líneas (producto + cantidad). Solo se editan traspasos pendientes.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Almacén origen *</Label>
                    <Select value={form.warehouse_origin} onValueChange={(v) => setForm((f) => ({ ...f, warehouse_origin: v }))} required disabled={!!editing}>
                      <SelectTrigger>
                        <SelectValue placeholder="Seleccione" />
                      </SelectTrigger>
                      <SelectContent>
                        {warehouses.map((w) => (
                          <SelectItem key={w.id} value={w.id}>
                            {(w.name as string) || w.code || w.id}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                  <div className="grid gap-2">
                    <Label>Almacén destino *</Label>
                    <Select value={form.warehouse_dest} onValueChange={(v) => setForm((f) => ({ ...f, warehouse_dest: v }))} required disabled={!!editing}>
                      <SelectTrigger>
                        <SelectValue placeholder="Seleccione" />
                      </SelectTrigger>
                      <SelectContent>
                        {warehouses.map((w) => (
                          <SelectItem key={w.id} value={w.id}>
                            {(w.name as string) || w.code || w.id}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label htmlFor="transfer-date">Fecha *</Label>
                    <Input id="transfer-date" type="date" value={form.date} onChange={(e) => setForm((f) => ({ ...f, date: e.target.value }))} required />
                  </div>
                  <div className="grid gap-2">
                    <Label htmlFor="transfer-notes">Notas</Label>
                    <Input id="transfer-notes" value={form.notes} onChange={(e) => setForm((f) => ({ ...f, notes: e.target.value }))} placeholder="Opcional" />
                  </div>
                </div>
                <div>
                  <div className="flex items-center justify-between mb-2">
                    <Label>Líneas *</Label>
                    <Button type="button" variant="outline" size="sm" onClick={addLine}>
                      <Plus className="h-4 w-4 mr-1" />
                      Añadir línea
                    </Button>
                  </div>
                  <div className="space-y-2 max-h-48 overflow-y-auto">
                    {form.lines.map((line, idx) => (
                      <div key={idx} className="flex gap-2 items-center">
                        <Select value={line.product} onValueChange={(v) => updateLine(idx, "product", v)} required={idx === 0}>
                          <SelectTrigger className="flex-1 min-w-0">
                            <SelectValue placeholder="Producto" />
                          </SelectTrigger>
                          <SelectContent>
                            {products.map((p) => (
                              <SelectItem key={p.id} value={p.id}>
                                {(p.sku as string) || ""} – {(p.nombre as string) || p.id}
                              </SelectItem>
                            ))}
                          </SelectContent>
                        </Select>
                        <Input
                          type="text"
                          inputMode="decimal"
                          placeholder="Cant."
                          className="w-24"
                          value={line.quantity}
                          onChange={(e) => updateLine(idx, "quantity", e.target.value)}
                        />
                        <Button type="button" variant="ghost" size="icon" className="h-8 w-8 shrink-0" onClick={() => removeLine(idx)} aria-label="Quitar línea" disabled={form.lines.length <= 1}>
                          <Trash2 className="h-4 w-4" />
                        </Button>
                      </div>
                    ))}
                  </div>
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
      <div className="mb-4">
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={data} isLoading={isLoading} emptyMessage="No hay traspasos. Cree uno con al menos dos almacenes." />

      <AlertDialog open={!!toDelete} onOpenChange={(open) => !open && setToDelete(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Eliminar traspaso?</AlertDialogTitle>
            <AlertDialogDescription>
              Se eliminará el traspaso de &quot;{toDelete?.warehouse_origin_name}&quot; a &quot;{toDelete?.warehouse_dest_name}&quot;. Solo se recomienda eliminar si está pendiente.
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

export default Transfers;
