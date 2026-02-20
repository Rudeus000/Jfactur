import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, Search, RefreshCw } from "lucide-react";
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

interface Expense {
  id: string;
  date: string;
  amount: number | string;
  description?: string;
  expense_type_name?: string;
  [key: string]: unknown;
}

interface ExpenseType {
  id: string;
  name: string;
  code?: string;
}

interface Currency {
  id: string;
  code: string;
  name: string;
}

interface CashRegister {
  id: string;
  name: string;
}

const columns: Column<Expense>[] = [
  { key: "date", label: "Fecha" },
  {
    key: "expense_type_name",
    label: "Tipo",
    render: (item) => (item.expense_type_name as string) || "—",
  },
  {
    key: "amount",
    label: "Monto",
    className: "text-right font-medium",
    render: (item) => `S/ ${Number(item.amount ?? 0).toFixed(2)}`,
  },
  { key: "description", label: "Descripción" },
];

const Expenses = () => {
  const { data, isLoading, refresh } = useApiList<Expense>({ endpoint: "/expenses/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [types, setTypes] = useState<ExpenseType[]>([]);
  const [currencies, setCurrencies] = useState<Currency[]>([]);
  const [registers, setRegisters] = useState<CashRegister[]>([]);
  const { toast } = useToast();

  const [form, setForm] = useState({
    expense_type: "",
    date: new Date().toISOString().slice(0, 10),
    amount: "",
    currency: "",
    description: "",
    reference: "",
    cash_register: "",
  });

  useEffect(() => {
    const load = async () => {
      try {
        const [tRes, cRes, rRes] = await Promise.all([
          api.get<ExpenseType[]>("/expense-types/"),
          api.get<Currency[]>("/currencies/"),
          api.get<CashRegister[]>("/cash-registers/"),
        ]);
        setTypes(Array.isArray(tRes.data) ? tRes.data : []);
        setCurrencies(Array.isArray(cRes.data) ? cRes.data : []);
        setRegisters(Array.isArray(rRes.data) ? rRes.data : []);
      } catch {
        setTypes([]);
        setCurrencies([]);
        setRegisters([]);
      }
    };
    if (open) load();
  }, [open]);

  const openCreate = () => {
    const defaultCurrency = currencies.find((c) => (c as { is_default?: boolean }).is_default)?.id || currencies[0]?.id || "";
    setForm({
      expense_type: types[0]?.id ?? "",
      date: new Date().toISOString().slice(0, 10),
      amount: "",
      currency: defaultCurrency,
      description: "",
      reference: "",
      cash_register: "",
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.expense_type || !form.amount) {
      toast({ title: "Tipo de gasto y monto son obligatorios", variant: "destructive" });
      return;
    }
    const amount = parseFloat(form.amount);
    if (isNaN(amount) || amount <= 0) {
      toast({ title: "Monto debe ser mayor a 0", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      await api.post("/expenses/", {
        expense_type: form.expense_type,
        date: form.date,
        amount,
        currency: form.currency || undefined,
        description: form.description.trim() || undefined,
        reference: form.reference.trim() || undefined,
        cash_register: form.cash_register || undefined,
      });
      toast({ title: "Gasto registrado correctamente" });
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const filtered = data.filter((e) => (e.description as string)?.toLowerCase().includes(search.toLowerCase()));

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Gastos</h1>
          <p className="erp-page-subtitle">Registrar gastos por tipo, monto y fecha.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate} disabled={types.length === 0}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo Gasto
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>Nuevo gasto</DialogTitle>
                <DialogDescription>Tipo de gasto, fecha y monto. Opcional: moneda, descripción y caja.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                  <Label>Tipo de gasto *</Label>
                  <Select value={form.expense_type} onValueChange={(v) => setForm((f) => ({ ...f, expense_type: v }))} required>
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccionar tipo" />
                    </SelectTrigger>
                    <SelectContent>
                      {types.map((t) => (
                        <SelectItem key={t.id} value={t.id}>
                          {t.name} {t.code ? `(${t.code})` : ""}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Fecha *</Label>
                    <Input
                      type="date"
                      value={form.date}
                      onChange={(e) => setForm((f) => ({ ...f, date: e.target.value }))}
                      required
                    />
                  </div>
                  <div className="grid gap-2">
                    <Label>Monto *</Label>
                    <Input
                      type="number"
                      step="0.01"
                      min="0"
                      value={form.amount}
                      onChange={(e) => setForm((f) => ({ ...f, amount: e.target.value }))}
                      placeholder="0.00"
                      required
                    />
                  </div>
                </div>
                {currencies.length > 0 && (
                  <div className="grid gap-2">
                    <Label>Moneda</Label>
                    <Select value={form.currency} onValueChange={(v) => setForm((f) => ({ ...f, currency: v }))}>
                      <SelectTrigger>
                        <SelectValue placeholder="Opcional" />
                      </SelectTrigger>
                      <SelectContent>
                        {currencies.map((c) => (
                          <SelectItem key={c.id} value={c.id}>
                            {c.code} — {c.name}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                )}
                <div className="grid gap-2">
                  <Label>Descripción</Label>
                  <Input
                    value={form.description}
                    onChange={(e) => setForm((f) => ({ ...f, description: e.target.value }))}
                    placeholder="Opcional"
                  />
                </div>
                <div className="grid gap-2">
                  <Label>Referencia</Label>
                  <Input
                    value={form.reference}
                    onChange={(e) => setForm((f) => ({ ...f, reference: e.target.value }))}
                    placeholder="Opcional"
                  />
                </div>
                {registers.length > 0 && (
                  <div className="grid gap-2">
                    <Label>Caja</Label>
                    <Select value={form.cash_register} onValueChange={(v) => setForm((f) => ({ ...f, cash_register: v }))}>
                      <SelectTrigger>
                        <SelectValue placeholder="Sin caja" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="">Sin caja</SelectItem>
                        {registers.map((r) => (
                          <SelectItem key={r.id} value={r.id}>
                            {r.name}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                )}
              </div>
              <DialogFooter>
                <Button type="button" variant="outline" onClick={() => setOpen(false)}>
                  Cancelar
                </Button>
                <Button type="submit" disabled={saving}>
                  {saving ? "Guardando…" : "Registrar gasto"}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por descripción…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay gastos registrados. Registre salidas de caja en Gastos." />
    </div>
  );
};

export default Expenses;
