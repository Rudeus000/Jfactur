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

interface PurchasePayment {
  id: string;
  date: string;
  amount: number | string;
  payment_method?: string;
  reference?: string;
  purchase?: string;
  [key: string]: unknown;
}

interface Purchase {
  id: string;
  number?: string;
  date: string;
  total: number | string;
  status: string;
  supplier_name?: string;
}

const PAYMENT_METHODS = [
  { value: "efectivo", label: "Efectivo" },
  { value: "transferencia", label: "Transferencia" },
  { value: "tarjeta", label: "Tarjeta" },
  { value: "cheque", label: "Cheque" },
  { value: "otro", label: "Otro" },
];

const columns: Column<PurchasePayment>[] = [
  { key: "date", label: "Fecha" },
  {
    key: "amount",
    label: "Monto",
    className: "text-right font-medium",
    render: (item) => `S/ ${Number(item.amount ?? 0).toFixed(2)}`,
  },
  { key: "payment_method", label: "Medio" },
  { key: "reference", label: "Referencia", className: "hidden lg:table-cell" },
];

const PurchasePayments = () => {
  const { data, isLoading, refresh } = useApiList<PurchasePayment>({ endpoint: "/purchase-payments/" });
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [purchases, setPurchases] = useState<Purchase[]>([]);
  const [banks, setBanks] = useState<{ id: string; name: string }[]>([]);
  const { toast } = useToast();

  const [form, setForm] = useState({
    purchase: "",
    date: new Date().toISOString().slice(0, 10),
    amount: "",
    payment_method: "transferencia",
    reference: "",
    bank: "",
  });

  // #region agent log
  fetch('http://127.0.0.1:7540/ingest/19ec7c1b-2775-421b-b4c9-517a7bc396b9',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'976499'},body:JSON.stringify({sessionId:'976499',location:'PurchasePayments.tsx:formState',message:'PurchasePayments form state',data:{formPurchase:form.purchase,formBank:form.bank,open,purchasesLen:purchases.length,banksLen:banks.length},timestamp:Date.now(),hypothesisId:'H1'})}).catch(()=>{});
  // #endregion

  useEffect(() => {
    const load = async () => {
      try {
        const [pRes, bRes] = await Promise.all([
          api.get<Purchase[]>("/purchases/"),
          api.get<{ id: string; name: string }[]>("/banks/"),
        ]);
        const list = Array.isArray(pRes.data) ? pRes.data : [];
        setPurchases(list);
        setBanks(Array.isArray(bRes.data) ? bRes.data : []);
      } catch {
        setPurchases([]);
        setBanks([]);
      }
    };
    if (open) load();
  }, [open]);

  const openCreate = () => {
    setForm({
      purchase: purchases[0]?.id ?? "",
      date: new Date().toISOString().slice(0, 10),
      amount: "",
      payment_method: "transferencia",
      reference: "",
      bank: "",
    });
    setOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.purchase || !form.amount) {
      toast({ title: "Compra y monto son obligatorios", variant: "destructive" });
      return;
    }
    const amount = parseFloat(form.amount);
    if (isNaN(amount) || amount <= 0) {
      toast({ title: "Monto debe ser mayor a 0", variant: "destructive" });
      return;
    }
    setSaving(true);
    try {
      await api.post("/purchase-payments/", {
        purchase: form.purchase,
        date: form.date,
        amount,
        payment_method: form.payment_method,
        reference: form.reference.trim() || undefined,
        bank: form.bank || undefined,
      });
      toast({ title: "Pago registrado correctamente" });
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
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
          <h1 className="erp-page-title">Pagos a Proveedores</h1>
          <p className="erp-page-subtitle">Registrar pagos contra órdenes de compra.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate} disabled={purchases.length === 0}>
            <Plus className="h-4 w-4 mr-2" />
            Nuevo Pago
          </Button>
          <DialogContent className="max-w-md">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>Nuevo pago a proveedor</DialogTitle>
                <DialogDescription>Seleccione la compra y el monto. Opcional: banco para transferencia.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid gap-2">
                  <Label>Orden de compra *</Label>
                  <Select value={form.purchase} onValueChange={(v) => setForm((f) => ({ ...f, purchase: v }))} required>
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccionar compra" />
                    </SelectTrigger>
                    <SelectContent>
                      {purchases.map((p) => (
                        <SelectItem key={p.id} value={p.id}>
                          {(p as Purchase).number || p.id.slice(0, 8)} — S/ {Number(p.total ?? 0).toFixed(2)}
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
                {form.payment_method === "transferencia" && banks.length > 0 && (
                  <div className="grid gap-2">
                    <Label>Banco</Label>
                    <Select value={form.bank} onValueChange={(v) => setForm((f) => ({ ...f, bank: v }))}>
                      <SelectTrigger>
                        <SelectValue placeholder="Opcional" />
                      </SelectTrigger>
                      <SelectContent>
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
                  <Label>Referencia</Label>
                  <Input
                    value={form.reference}
                    onChange={(e) => setForm((f) => ({ ...f, reference: e.target.value }))}
                    placeholder="Nº operación, cheque, etc."
                  />
                </div>
              </div>
              <DialogFooter>
                <Button type="button" variant="outline" onClick={() => setOpen(false)}>
                  Cancelar
                </Button>
                <Button type="submit" disabled={saving}>
                  {saving ? "Guardando…" : "Registrar pago"}
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
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay pagos registrados a proveedores." />
    </div>
  );
};

export default PurchasePayments;
