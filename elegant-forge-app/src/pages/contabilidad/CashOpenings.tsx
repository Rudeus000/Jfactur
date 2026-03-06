import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, RefreshCw, Lock } from "lucide-react";
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

interface CashRegister {
  id: string;
  name: string;
  code?: string;
}

interface Bank {
  id: string;
  name: string;
}

interface CashOpening {
  id: string;
  opened_at: string;
  opening_balance: number | string;
  cash_register?: string;
  cash_register_name?: string;
  closed_at?: string | null;
  closing_balance?: string | null;
  [key: string]: unknown;
}

interface ClosureSummary {
  opening_id: string;
  opening_balance: number;
  total_sales: number;
  total_payments_in: number;
  breakdown: {
    cash_total: number;
    card_total: number;
    other_total: number;
  };
}

const CashOpenings = () => {
  const [data, setData] = useState<CashOpening[]>([]);
  const [registers, setRegisters] = useState<CashRegister[]>([]);
  const [banks, setBanks] = useState<Bank[]>([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({ cash_register: "", opening_balance: "0", notes: "" });
  const [saving, setSaving] = useState(false);
  const { toast } = useToast();

  const [closeOpen, setCloseOpen] = useState(false);
  const [closingOpening, setClosingOpening] = useState<CashOpening | null>(null);
  const [summary, setSummary] = useState<ClosureSummary | null>(null);
  const [loadingSummary, setLoadingSummary] = useState(false);
  const [closingForm, setClosingForm] = useState({
    closing_balance: "",
    total_payments_out: "0",
    destination_type: "__none__",
    destination_bank: "__none__",
    notes: "",
  });
  const [closingSaving, setClosingSaving] = useState(false);

  const fetchData = async () => {
    setLoading(true);
    try {
      const [openRes, regRes, bRes] = await Promise.all([
        api.get<CashOpening[]>("/cash-openings/"),
        api.get<CashRegister[]>("/cash-registers/"),
        api.get<Bank[]>("/banks/"),
      ]);
      setData(Array.isArray(openRes.data) ? openRes.data : []);
      setRegisters(Array.isArray(regRes.data) ? regRes.data : []);
      setBanks(Array.isArray(bRes.data) ? bRes.data : []);
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, []);

  const handleOpen = () => {
    setForm({
      cash_register: registers[0]?.id || "",
      opening_balance: "0",
      notes: "",
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.cash_register) {
      toast({ title: "Seleccione una caja", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      const now = new Date();
      const openedAt = now.toISOString().slice(0, 19).replace("T", " ");
      await api.post("/cash-openings/", {
        cash_register: form.cash_register,
        opened_at: openedAt,
        opening_balance: form.opening_balance ? parseFloat(form.opening_balance) : 0,
        notes: form.notes.trim() || undefined,
      });
      toast({ title: "Caja abierta correctamente" });
      setOpen(false);
      fetchData();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const startClose = async (row: CashOpening) => {
    setClosingOpening(row);
    setSummary(null);
    setClosingForm({ closing_balance: "", total_payments_out: "0", destination_type: "__none__", destination_bank: "__none__", notes: "" });
    setCloseOpen(true);
    setLoadingSummary(true);
    try {
      const res = await api.get<ClosureSummary>(`/cash-openings/${row.id}/closure-summary/`);
      setSummary(res.data);
      const expectedBalance = (res.data.opening_balance + res.data.breakdown.cash_total).toFixed(2);
      setClosingForm((f) => ({ ...f, closing_balance: expectedBalance }));
    } catch (e) {
      toast({ title: "Error obteniendo resumen", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setLoadingSummary(false);
    }
  };

  const handleClose = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!closingOpening) return;
    const balance = parseFloat(closingForm.closing_balance);
    if (isNaN(balance) || balance < 0) {
      toast({ title: "Ingrese un saldo de cierre válido", variant: "destructive" });
      return;
    }
    setClosingSaving(true);
    try {
      const now = new Date().toISOString().slice(0, 19).replace("T", " ");
      await api.post("/cash-closings/", {
        opening: closingOpening.id,
        closed_at: now,
        closing_balance: balance,
        total_sales: summary?.total_sales ?? 0,
        total_payments_in: summary?.total_payments_in ?? 0,
        total_payments_out: parseFloat(closingForm.total_payments_out) || 0,
        cash_total: summary?.breakdown.cash_total ?? 0,
        card_total: summary?.breakdown.card_total ?? 0,
        other_total: summary?.breakdown.other_total ?? 0,
        destination_type: closingForm.destination_type === "__none__" ? "" : closingForm.destination_type,
        destination_bank: closingForm.destination_bank === "__none__" ? null : closingForm.destination_bank,
        notes: closingForm.notes.trim() || "",
      });
      toast({ title: "Caja cerrada correctamente" });
      setCloseOpen(false);
      fetchData();
    } catch (e) {
      toast({ title: "Error al cerrar caja", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setClosingSaving(false);
    }
  };

  const columns: Column<CashOpening>[] = [
    { key: "cash_register_name", label: "Caja" },
    { key: "opened_at", label: "Apertura" },
    {
      key: "opening_balance",
      label: "Saldo inicial",
      className: "text-right",
      render: (item) => `S/ ${Number(item.opening_balance ?? 0).toFixed(2)}`,
    },
    {
      key: "closed_at",
      label: "Cierre",
      render: (item) => (item.closed_at ? String(item.closed_at) : "— Abierta"),
    },
  ];

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Aperturas de caja</h1>
          <p className="erp-page-subtitle">Abra una caja para iniciar turno. Ciérrela al finalizar para cuadrar montos.</p>
        </div>
        <Button onClick={handleOpen} disabled={registers.length === 0}>
          <Plus className="h-4 w-4 mr-2" />
          Abrir caja
        </Button>
      </div>
      {registers.length === 0 && (
        <p className="text-sm text-muted-foreground mb-4">Cree al menos una caja en Contabilidad → Cajas para poder abrir turno.</p>
      )}
      <div className="mb-4">
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
            render: (item) =>
              !item.closed_at ? (
                <Button variant="outline" size="sm" onClick={() => startClose(item)}>
                  <Lock className="h-4 w-4 mr-1" />
                  Cerrar
                </Button>
              ) : null,
          },
        ]}
        data={data}
        isLoading={loading}
        emptyMessage="No hay aperturas. Abra una caja para iniciar turno."
      />

      {/* Diálogo: Abrir caja */}
      <Dialog open={open} onOpenChange={setOpen}>
        <DialogContent>
          <form onSubmit={handleSubmit}>
            <DialogHeader>
              <DialogTitle>Abrir caja</DialogTitle>
              <DialogDescription>Seleccione la caja y el monto inicial (opcional).</DialogDescription>
            </DialogHeader>
            <div className="grid gap-4 py-4">
              <div className="grid gap-2">
                <Label>Caja *</Label>
                <Select
                  value={form.cash_register}
                  onValueChange={(v) => setForm((f) => ({ ...f, cash_register: v }))}
                  required
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Seleccione una caja" />
                  </SelectTrigger>
                  <SelectContent>
                    {registers.map((r) => (
                      <SelectItem key={r.id} value={r.id}>
                        {r.name} {r.code ? `(${r.code})` : ""}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              <div className="grid gap-2">
                <Label htmlFor="opening_balance">Saldo inicial (S/)</Label>
                <Input
                  id="opening_balance"
                  type="number"
                  step="0.01"
                  min="0"
                  value={form.opening_balance}
                  onChange={(e) => setForm((f) => ({ ...f, opening_balance: e.target.value }))}
                />
              </div>
              <div className="grid gap-2">
                <Label htmlFor="notes">Notas</Label>
                <Input
                  id="notes"
                  value={form.notes}
                  onChange={(e) => setForm((f) => ({ ...f, notes: e.target.value }))}
                  placeholder="Opcional"
                />
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setOpen(false)}>
                Cancelar
              </Button>
              <Button type="submit" disabled={saving}>
                Abrir caja
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Diálogo: Cerrar caja */}
      <Dialog open={closeOpen} onOpenChange={setCloseOpen}>
        <DialogContent className="max-w-lg">
          <form onSubmit={handleClose}>
            <DialogHeader>
              <DialogTitle>Cerrar caja</DialogTitle>
              <DialogDescription>
                {closingOpening?.cash_register_name ?? "Caja"} — Apertura: {closingOpening?.opened_at}
              </DialogDescription>
            </DialogHeader>
            <div className="grid gap-4 py-4">
              {loadingSummary ? (
                <p className="text-sm text-muted-foreground">Cargando resumen…</p>
              ) : summary ? (
                <div className="erp-card p-4 space-y-1 text-sm">
                  <div className="flex justify-between"><span className="text-muted-foreground">Saldo apertura:</span> <span className="font-medium">S/ {summary.opening_balance.toFixed(2)}</span></div>
                  <div className="flex justify-between"><span className="text-muted-foreground">Total ventas:</span> <span>S/ {summary.total_sales.toFixed(2)}</span></div>
                  <div className="flex justify-between"><span className="text-muted-foreground">Total cobros:</span> <span>S/ {summary.total_payments_in.toFixed(2)}</span></div>
                  <hr className="my-2" />
                  <div className="flex justify-between"><span className="text-muted-foreground">Efectivo:</span> <span>S/ {summary.breakdown.cash_total.toFixed(2)}</span></div>
                  <div className="flex justify-between"><span className="text-muted-foreground">Tarjeta:</span> <span>S/ {summary.breakdown.card_total.toFixed(2)}</span></div>
                  <div className="flex justify-between"><span className="text-muted-foreground">Otros:</span> <span>S/ {summary.breakdown.other_total.toFixed(2)}</span></div>
                </div>
              ) : null}
              <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label>Saldo de cierre (S/) *</Label>
                  <Input
                    type="number"
                    step="0.01"
                    min="0"
                    value={closingForm.closing_balance}
                    onChange={(e) => setClosingForm((f) => ({ ...f, closing_balance: e.target.value }))}
                    required
                  />
                </div>
                <div className="grid gap-2">
                  <Label>Egresos (S/)</Label>
                  <Input
                    type="number"
                    step="0.01"
                    min="0"
                    value={closingForm.total_payments_out}
                    onChange={(e) => setClosingForm((f) => ({ ...f, total_payments_out: e.target.value }))}
                  />
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="grid gap-2">
                  <Label>Destino del dinero</Label>
                  <Select value={closingForm.destination_type} onValueChange={(v) => setClosingForm((f) => ({ ...f, destination_type: v }))}>
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="__none__">Sin destino</SelectItem>
                      <SelectItem value="bank">Banco</SelectItem>
                      <SelectItem value="safe">Caja fuerte</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                {closingForm.destination_type === "bank" && banks.length > 0 && (
                  <div className="grid gap-2">
                    <Label>Banco</Label>
                    <Select value={closingForm.destination_bank} onValueChange={(v) => setClosingForm((f) => ({ ...f, destination_bank: v }))}>
                      <SelectTrigger>
                        <SelectValue placeholder="Seleccionar" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="__none__">Seleccionar</SelectItem>
                        {banks.map((b) => (
                          <SelectItem key={b.id} value={b.id}>
                            {b.name}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                )}
              </div>
              <div className="grid gap-2">
                <Label>Notas</Label>
                <Input
                  value={closingForm.notes}
                  onChange={(e) => setClosingForm((f) => ({ ...f, notes: e.target.value }))}
                  placeholder="Observaciones del cierre"
                />
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setCloseOpen(false)}>
                Cancelar
              </Button>
              <Button type="submit" disabled={closingSaving || loadingSummary}>
                {closingSaving ? "Cerrando…" : "Cerrar caja"}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
};

export default CashOpenings;
