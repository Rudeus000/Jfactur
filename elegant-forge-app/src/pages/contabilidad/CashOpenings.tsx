import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, RefreshCw } from "lucide-react";
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

const CashOpenings = () => {
  const [data, setData] = useState<CashOpening[]>([]);
  const [registers, setRegisters] = useState<CashRegister[]>([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({ cash_register: "", opening_balance: "0", notes: "" });
  const [saving, setSaving] = useState(false);
  const { toast } = useToast();

  const fetchData = async () => {
    setLoading(true);
    try {
      const [openRes, regRes] = await Promise.all([
        api.get<CashOpening[]>("/cash-openings/"),
        api.get<CashRegister[]>("/cash-registers/"),
      ]);
      setData(Array.isArray(openRes.data) ? openRes.data : []);
      setRegisters(Array.isArray(regRes.data) ? regRes.data : []);
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
          <p className="erp-page-subtitle">Abra una caja para iniciar turno. Los cobros en efectivo pueden asociarse a esta apertura.</p>
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
      <DataTable columns={columns} data={data} isLoading={loading} emptyMessage="No hay aperturas. Abra una caja para iniciar turno." />

      <Dialog open={open} onOpenChange={setOpen}>
        <DialogContent>
          <form onSubmit={handleSubmit}>
            <DialogHeader>
              <DialogTitle>Abrir caja</DialogTitle>
              <DialogDescription>Seleccione la caja y el monto inicial (opcional). Usted quedará como responsable de la apertura.</DialogDescription>
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
    </div>
  );
};

export default CashOpenings;
