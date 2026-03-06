import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, Search, RefreshCw, FileText } from "lucide-react";
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

interface CustomerPayment {
  id: string;
  invoice?: string;
  date: string;
  amount: number | string;
  payment_method?: string;
  reference?: string;
  invoice_display?: string;
  [key: string]: unknown;
}

interface Invoice {
  id: string;
  serie: string;
  numero: number;
  cliente_razon_social: string;
  total: number | string;
  status: string;
}

interface CashRegister {
  id: string;
  name: string;
  code?: string;
}

interface CashOpening {
  id: string;
  cash_register: string;
  cash_register_name?: string;
  opened_at: string;
  closed_at?: string | null;
}

const PAYMENT_METHODS = [
  { value: "efectivo", label: "Efectivo" },
  { value: "transferencia", label: "Transferencia" },
  { value: "tarjeta", label: "Tarjeta" },
  { value: "yape", label: "Yape" },
  { value: "plin", label: "Plin" },
  { value: "otro", label: "Otro" },
];

const columns: Column<CustomerPayment>[] = [
  { key: "date", label: "Fecha" },
  {
    key: "invoice_display",
    label: "Factura",
    render: (item) => (item.invoice_display as string) || "—",
  },
  {
    key: "amount",
    label: "Monto",
    className: "text-right font-medium",
    render: (item) => `S/ ${Number(item.amount ?? 0).toFixed(2)}`,
  },
  { key: "payment_method", label: "Medio" },
  { key: "reference", label: "Referencia", className: "hidden lg:table-cell" },
];

const CustomerPayments = () => {
  const { user } = useAuth();
  const { data, isLoading, refresh } = useApiList<CustomerPayment>({ endpoint: "/customer-payments/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [invoices, setInvoices] = useState<Invoice[]>([]);
  const [registers, setRegisters] = useState<CashRegister[]>([]);
  const [openings, setOpenings] = useState<CashOpening[]>([]);
  const [banks, setBanks] = useState<{ id: string; name: string }[]>([]);
  const { toast } = useToast();

  const [form, setForm] = useState({
    invoice: "__none__",
    date: new Date().toISOString().slice(0, 10),
    amount: "",
    payment_method: "efectivo",
    reference: "",
    bank: "__none__",
    cash_register: "__none__",
    cash_opening: "__none__",
    notes: "",
  });

  useEffect(() => {
    const load = async () => {
      try {
        const [invRes, regRes, openRes, bankRes] = await Promise.all([
          api.get<Invoice[]>("/invoices/"),
          api.get<CashRegister[]>("/cash-registers/"),
          api.get<CashOpening[]>("/cash-openings/"),
          api.get<{ id: string; name: string }[]>("/banks/"),
        ]);
        setInvoices(Array.isArray(invRes.data) ? invRes.data.filter((i: Invoice) => i.status !== "draft") : []);
        setRegisters(Array.isArray(regRes.data) ? regRes.data : []);
        setOpenings(Array.isArray(openRes.data) ? openRes.data.filter((o: CashOpening) => !o.closed_at) : []);
        setBanks(Array.isArray(bankRes.data) ? bankRes.data : []);
      } catch {
        setInvoices([]);
        setRegisters([]);
        setOpenings([]);
        setBanks([]);
      }
    };
    if (open) load();
  }, [open]);

  const defaultRegisterId = user?.default_cash_register || "";
  const activeCashRegister = form.cash_register !== "__none__" ? form.cash_register : "";
  const openingsForRegister = activeCashRegister
    ? openings.filter((o) => o.cash_register === activeCashRegister)
    : openings;

  const openCreate = () => {
    const regId = defaultRegisterId || (registers[0]?.id ?? "__none__");
    const firstOpen = regId !== "__none__" ? (openings.filter((o) => o.cash_register === regId)[0]?.id ?? "__none__") : "__none__";
    setForm({
      invoice: invoices[0]?.id ?? "__none__",
      date: new Date().toISOString().slice(0, 10),
      amount: "",
      payment_method: "efectivo",
      reference: "",
      bank: "__none__",
      cash_register: regId,
      cash_opening: firstOpen,
      notes: "",
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.invoice || form.invoice === "__none__" || !form.amount) {
      toast({ title: "Factura y monto son obligatorios", variant: "destructive" });
      return;
    }
    const amount = parseFloat(form.amount);
    if (isNaN(amount) || amount <= 0) {
      toast({ title: "Monto debe ser mayor a 0", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      await api.post("/customer-payments/", {
        invoice: form.invoice,
        date: form.date,
        amount,
        payment_method: form.payment_method,
        reference: form.reference.trim() || undefined,
        bank: form.bank === "__none__" ? undefined : form.bank || undefined,
        cash_register: form.cash_register === "__none__" ? undefined : form.cash_register || undefined,
        cash_opening: form.cash_opening === "__none__" ? undefined : form.cash_opening || undefined,
        notes: form.notes.trim() || undefined,
      });
      toast({ title: "Cobro registrado correctamente" });
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const openTicket = (invoiceId: string) => {
    api.get(`/invoices/${invoiceId}/ticket/`, { responseType: "text" })
      .then(({ data: html }) => {
        const w = window.open("", "_blank");
        if (w) {
          w.document.write(html);
          w.document.close();
        }
      })
      .catch(() => toast({ title: "No se pudo abrir el comprobante", variant: "destructive" }));
  };

  const filtered = data.filter(
    (p) =>
      (p.reference as string)?.toLowerCase().includes(search.toLowerCase()) ||
      (p.payment_method as string)?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Cobros</h1>
          <p className="erp-page-subtitle">Registrar cobros de clientes. Opcional: vincular a caja y apertura.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo Cobro
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>Nuevo cobro</DialogTitle>
                <DialogDescription>Factura, monto, fecha y medio de pago. Opcional: caja y apertura para cierre de caja.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                  <Label>Factura *</Label>
                  <Select value={form.invoice} onValueChange={(v) => setForm((f) => ({ ...f, invoice: v }))} required>
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccionar factura" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="__none__">Seleccionar</SelectItem>
                      {invoices.map((inv) => (
                        <SelectItem key={inv.id} value={inv.id}>
                          {inv.serie}-{inv.numero} — {inv.cliente_razon_social} — S/ {Number(inv.total ?? 0).toFixed(2)}
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
                    <Label>Monto (S/) *</Label>
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
                <div className="grid gap-2">
                  <Label>Medio de pago</Label>
                  <Select value={form.payment_method} onValueChange={(v) => setForm((f) => ({ ...f, payment_method: v }))}>
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      {PAYMENT_METHODS.map((m) => (
                        <SelectItem key={m.value} value={m.value}>
                          {m.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                {form.payment_method === "transferencia" && (
                  <div className="grid gap-2">
                    <Label>Banco</Label>
                    <Select value={form.bank} onValueChange={(v) => setForm((f) => ({ ...f, bank: v }))}>
                      <SelectTrigger>
                        <SelectValue placeholder="Opcional" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="__none__">Sin banco</SelectItem>
                        {banks.map((b) => (
                          <SelectItem key={b.id} value={b.id}>
                            {b.name}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                )}
                <div className="grid gap-2">
                  <Label>Caja (opcional)</Label>
                  <Select
                    value={form.cash_register}
                    onValueChange={(v) =>
                      setForm((f) => ({
                        ...f,
                        cash_register: v,
                        cash_opening: v === "__none__" ? "__none__" : (openings.filter((o: CashOpening) => o.cash_register === v)[0]?.id ?? "__none__"),
                      }))
                    }
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Sin caja" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="__none__">Sin caja</SelectItem>
                      {registers.map((r) => (
                        <SelectItem key={r.id} value={r.id}>
                          {r.name}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                {form.cash_register && form.cash_register !== "__none__" && openingsForRegister.length > 0 && (
                  <div className="grid gap-2">
                    <Label>Apertura de caja</Label>
                    <Select value={form.cash_opening} onValueChange={(v) => setForm((f) => ({ ...f, cash_opening: v }))}>
                      <SelectTrigger>
                        <SelectValue placeholder="Seleccionar apertura" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="__none__">Sin apertura</SelectItem>
                        {openingsForRegister.map((o) => (
                          <SelectItem key={o.id} value={o.id}>
                            {o.cash_register_name || o.opened_at}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                )}
                <div className="grid gap-2">
                  <Label>Referencia</Label>
                  <Input
                    value={form.reference}
                    onChange={(e) => setForm((f) => ({ ...f, reference: e.target.value }))}
                    placeholder="Nº operación, etc."
                  />
                </div>
                <div className="grid gap-2">
                  <Label>Notas</Label>
                  <Input
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
                  {saving ? "Guardando…" : "Registrar cobro"}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por referencia o medio…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable
        columns={[
          ...columns,
          {
            key: "_action",
            label: "Comprobante",
            render: (item) =>
              item.invoice ? (
                <Button variant="outline" size="sm" onClick={() => openTicket(item.invoice!)} title="Ver o imprimir boleta/factura">
                  <FileText className="h-4 w-4 mr-1" />
                  Ver
                </Button>
              ) : (
                "—"
              ),
          },
        ]}
        data={filtered}
        isLoading={isLoading}
      />
    </div>
  );
};

export default CustomerPayments;
