import { useState, useEffect } from "react";
import { useSearchParams } from "react-router-dom";
import api, { getErrorMessage } from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Plus, Search, RefreshCw, Send, FileText } from "lucide-react";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { useToast } from "@/hooks/use-toast";

interface Invoice {
  id: string;
  serie: string;
  numero: number;
  tipo_documento?: string;
  cliente_razon_social: string;
  total: number | string;
  igv_total: number | string;
  status: string;
  fecha_emision: string;
  [key: string]: unknown;
}

interface InvoiceSeries {
  id: string;
  tipo_documento: string;
  serie: string;
  warehouse: string;
  is_active: boolean;
}

interface Customer {
  id: string;
  tipo_documento: string;
  numero_documento: string;
  razon_social: string;
  direccion?: string;
}

interface Product {
  id: string;
  nombre: string;
  sku?: string;
  precio_venta?: number | string;
}

interface LineRow {
  product: string;
  quantity: string;
  valor_unitario: string;
  description: string;
}

const statusColors: Record<string, string> = {
  draft: "bg-muted text-muted-foreground",
  accepted: "bg-success/10 text-success",
  sent: "bg-info/10 text-info",
  paid: "bg-success/10 text-success",
  pending: "bg-warning/10 text-warning",
  rejected: "bg-destructive/10 text-destructive",
};

const statusLabels: Record<string, string> = {
  draft: "Borrador",
  accepted: "Aceptada",
  sent: "Enviada",
  paid: "Pagada",
  pending: "Pendiente",
  rejected: "Rechazada",
};

const TIPOS = [
  { value: "01", label: "Factura" },
  { value: "03", label: "Boleta" },
];

const tipoDocLabel = (tipo: string) => (tipo === "03" ? "Boleta" : tipo === "01" ? "Factura" : tipo || "—");

const columns: Column<Invoice>[] = [
  {
    key: "tipo_documento",
    label: "Tipo",
    render: (item) => tipoDocLabel((item.tipo_documento as string) || ""),
  },
  {
    key: "serie",
    label: "Serie-Número",
    render: (item) => `${item.serie || ""}-${item.numero ?? ""}`,
  },
  { key: "cliente_razon_social", label: "Cliente" },
  { key: "fecha_emision", label: "Fecha" },
  {
    key: "igv_total",
    label: "IGV",
    className: "text-right hidden lg:table-cell",
    render: (item) => `S/ ${Number(item.igv_total ?? 0).toFixed(2)}`,
  },
  {
    key: "total",
    label: "Total",
    className: "text-right font-medium",
    render: (item) => `S/ ${Number(item.total ?? 0).toFixed(2)}`,
  },
  {
    key: "status",
    label: "Estado",
    render: (item) => (
      <span className={`erp-status-badge ${statusColors[item.status as string] || ""}`}>
        {statusLabels[item.status as string] || (item.status as string)}
      </span>
    ),
  },
];

function round2(n: number) {
  return Math.round(n * 100) / 100;
}

const Invoices = () => {
  const { user } = useAuth();
  const [searchParams] = useSearchParams();
  const q = searchParams.get("search") ?? "";
  const { data, isLoading, refresh } = useApiList<Invoice>({ endpoint: "/invoices/" });
  const [search, setSearch] = useState(q);
  useEffect(() => {
    setSearch(q);
  }, [q]);
  const [open, setOpen] = useState(false);
  const [saving, setSaving] = useState(false);
  const [sendingId, setSendingId] = useState<string | null>(null);
  const [sunatDialog, setSunatDialog] = useState<Invoice | null>(null);
  const [claveSol, setClaveSol] = useState("");
  const [confirmSendSunat, setConfirmSendSunat] = useState(false);
  const [series, setSeries] = useState<InvoiceSeries[]>([]);
  const [customers, setCustomers] = useState<Customer[]>([]);
  const [products, setProducts] = useState<Product[]>([]);
  const { toast } = useToast();

  const [form, setForm] = useState({
    tipo_documento: "01",
    serie: "",
    customer: "",
    fecha_emision: new Date().toISOString().slice(0, 10),
    hora_emision: new Date().toTimeString().slice(0, 8),
    warehouse: "",
    lines: [{ product: "", quantity: "1", valor_unitario: "", description: "" }] as LineRow[],
  });

  useEffect(() => {
    const load = async () => {
      try {
        const [sRes, cRes, pRes] = await Promise.all([
          api.get<InvoiceSeries[]>("/invoice-series/"),
          api.get<Customer[]>("/customers/"),
          api.get<Product[]>("/products/"),
        ]);
        const sList = Array.isArray(sRes.data) ? (sRes.data as InvoiceSeries[]).filter((s) => s.is_active) : [];
        setSeries(sList);
        setCustomers(Array.isArray(cRes.data) ? cRes.data : []);
        setProducts(Array.isArray(pRes.data) ? pRes.data : []);
      } catch {
        setSeries([]);
        setCustomers([]);
        setProducts([]);
      }
    };
    if (open) load();
  }, [open]);

  const seriesForType = series.filter((s) => s.tipo_documento === form.tipo_documento);

  useEffect(() => {
    if (open && seriesForType.length > 0 && (!form.serie || !seriesForType.find((s) => s.id === form.serie))) {
      const def = seriesForType.find((s) => s.warehouse === user?.default_warehouse) || seriesForType[0];
      setForm((f) => ({ ...f, serie: def?.id ?? "", warehouse: def?.warehouse ?? "" }));
    }
  }, [open, series, form.tipo_documento]);

  const openCreate = () => {
    setForm({
      tipo_documento: "01",
      serie: "",
      customer: customers[0]?.id ?? "",
      fecha_emision: new Date().toISOString().slice(0, 10),
      hora_emision: new Date().toTimeString().slice(0, 8),
      warehouse: "",
      lines: [{ product: "", quantity: "1", valor_unitario: "", description: "" }],
    });
    setOpen(true);
  };

  const addLine = () => {
    setForm((f) => ({
      ...f,
      lines: [...f.lines, { product: "", quantity: "1", valor_unitario: "", description: "" }],
    }));
  };

  const updateLine = (index: number, field: keyof LineRow, value: string) => {
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
    const selSeries = series.find((s) => s.id === form.serie);
    if (!selSeries) {
      toast({ title: "Seleccione una serie activa", variant: "destructive" });
      return;
    }
    if (!form.customer) {
      toast({ title: "Seleccione un cliente", variant: "destructive" });
      return;
    }
    const validLines = form.lines.filter((l) => l.product && l.quantity && l.valor_unitario);
    if (validLines.length === 0) {
      toast({ title: "Agregue al menos una línea", variant: "destructive" });
      return;
    }
    const customer = customers.find((c) => c.id === form.customer);
    const IGV_RATE = 0.18;
    const invoiceLines = validLines.map((l, i) => {
      const q = parseFloat(l.quantity) || 0;
      const vu = parseFloat(l.valor_unitario) || 0;
      const valor_venta = round2(q * vu);
      const igv_monto = round2(valor_venta * IGV_RATE);
      const importe_total = round2(valor_venta + igv_monto);
      const product = products.find((p) => p.id === l.product);
      return {
        product: l.product,
        descripcion: l.description?.trim() || product?.nombre || "Producto",
        cantidad: q,
        valor_unitario: vu,
        valor_venta,
        codigo_tipo_afectacion: "10",
        igv_monto,
        importe_total,
      };
    });
    const subtotal = round2(invoiceLines.reduce((s, l) => s + l.valor_venta, 0));
    const igv_total = round2(invoiceLines.reduce((s, l) => s + l.igv_monto, 0));
    const total = round2(invoiceLines.reduce((s, l) => s + l.importe_total, 0));

    setSaving(true);
    try {
      await api.post("/invoices/", {
        tipo_documento: form.tipo_documento,
        serie: selSeries.serie,
        fecha_emision: form.fecha_emision,
        hora_emision: form.hora_emision,
        customer: form.customer,
        cliente_tipo_documento: customer?.tipo_documento || "",
        cliente_numero_documento: customer?.numero_documento || "",
        cliente_razon_social: customer?.razon_social || "",
        cliente_direccion: customer?.direccion || "",
        subtotal,
        igv_total,
        total,
        status: "draft",
        warehouse: selSeries.warehouse || undefined,
        invoice_lines: invoiceLines,
      });
      toast({ title: "Factura creada en borrador. Puede enviarla a SUNAT." });
      setOpen(false);
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  const openSendSunat = (row: Invoice) => {
    if (row.status !== "draft") {
      toast({ title: "Solo se puede enviar a SUNAT una factura en borrador", variant: "destructive" });
      return;
    }
    setSunatDialog(row);
    setClaveSol("");
  };

  const openTicket = (invoiceId: string) => {
    api.get(`/invoices/${invoiceId}/ticket/`, { responseType: "text" })
      .then(({ data }) => {
        const w = window.open("", "_blank");
        if (w) {
          w.document.write(data);
          w.document.close();
        }
      })
      .catch(() => toast({ title: "No se pudo abrir el ticket", variant: "destructive" }));
  };

  const requestSendSunat = () => {
    if (!sunatDialog) return;
    if (!claveSol.trim()) {
      toast({ title: "Ingrese la clave SOL", variant: "destructive" });
      return;
    }
    setConfirmSendSunat(true);
  };

  const handleSendSunat = async () => {
    if (!sunatDialog) return;
    setConfirmSendSunat(false);
    setSendingId(sunatDialog.id);
    try {
      await api.post(`/invoices/${sunatDialog.id}/send-sunat/`, { clave_sol: claveSol.trim() });
      toast({ title: "Enviada a SUNAT correctamente" });
      setSunatDialog(null);
      setClaveSol("");
      refresh();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSendingId(null);
    }
  };

  const filtered = data.filter(
    (inv) =>
      inv.serie?.toLowerCase().includes(search.toLowerCase()) ||
      String(inv.numero ?? "").includes(search) ||
      inv.cliente_razon_social?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Facturas / Boletas</h1>
          <p className="erp-page-subtitle">Crear facturas en borrador y enviarlas a SUNAT.</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <Button onClick={openCreate} disabled={seriesForType.length === 0 || customers.length === 0}>
            <Plus className="h-4 w-4 mr-2" />
            Nueva Factura
          </Button>
          <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
            <form onSubmit={handleSubmit}>
              <DialogHeader>
                <DialogTitle>Nueva factura o boleta</DialogTitle>
                <DialogDescription>Tipo, serie, cliente y líneas. Guarde en borrador y luego envíe a SUNAT.</DialogDescription>
              </DialogHeader>
              <div className="grid gap-4 py-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Tipo</Label>
                    <Select
                      value={form.tipo_documento}
                      onValueChange={(v) => {
                        const list = series.filter((s) => s.tipo_documento === v);
                        setForm((f) => ({
                          ...f,
                          tipo_documento: v,
                          serie: list[0]?.id ?? "",
                          warehouse: list[0]?.warehouse ?? "",
                        }));
                      }}
                    >
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
                    <Select
                      value={form.serie}
                      onValueChange={(v) => {
                        const s = series.find((x) => x.id === v);
                        setForm((f) => ({ ...f, serie: v, warehouse: s?.warehouse ?? f.warehouse }));
                      }}
                    >
                      <SelectTrigger>
                        <SelectValue placeholder="Seleccionar serie" />
                      </SelectTrigger>
                      <SelectContent>
                        {seriesForType.map((s) => (
                          <SelectItem key={s.id} value={s.id}>
                            {s.serie}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </div>
                </div>
                <div className="grid gap-2">
                  <Label>Cliente *</Label>
                  <Select value={form.customer} onValueChange={(v) => setForm((f) => ({ ...f, customer: v }))} required>
                    <SelectTrigger>
                      <SelectValue placeholder="Seleccionar" />
                    </SelectTrigger>
                    <SelectContent>
                      {customers.map((c) => (
                        <SelectItem key={c.id} value={c.id}>
                          {c.razon_social} — {c.numero_documento}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div className="grid gap-2">
                    <Label>Fecha *</Label>
                    <Input type="date" value={form.fecha_emision} onChange={(e) => setForm((f) => ({ ...f, fecha_emision: e.target.value }))} required />
                  </div>
                  <div className="grid gap-2">
                    <Label>Hora</Label>
                    <Input type="time" value={form.hora_emision} onChange={(e) => setForm((f) => ({ ...f, hora_emision: e.target.value || "00:00:00" }))} />
                  </div>
                </div>
                <div>
                  <div className="flex justify-between items-center mb-2">
                    <Label>Líneas</Label>
                    <Button type="button" variant="outline" size="sm" onClick={addLine}>
                      <Plus className="h-3 w-3 mr-1" />
                      Línea
                    </Button>
                  </div>
                  <div className="space-y-2 border rounded-md p-2">
                    {form.lines.map((line, idx) => (
                      <div key={idx} className="grid grid-cols-12 gap-2 items-end">
                        <div className="col-span-4">
                          <Select value={line.product || "__none__"} onValueChange={(v) => updateLine(idx, "product", v === "__none__" ? "" : v)}>
                            <SelectTrigger className="h-8">
                              <SelectValue placeholder="Producto" />
                            </SelectTrigger>
                            <SelectContent>
                              <SelectItem value="__none__">Seleccionar</SelectItem>
                              {products.map((p) => (
                                <SelectItem key={p.id} value={p.id}>
                                  {p.nombre} {p.sku ? `(${p.sku})` : ""}
                                </SelectItem>
                              ))}
                            </SelectContent>
                          </Select>
                        </div>
                        <div className="col-span-2">
                          <Input
                            type="number"
                            min="0"
                            step="1"
                            placeholder="Cant"
                            value={line.quantity}
                            onChange={(e) => updateLine(idx, "quantity", e.target.value)}
                            className="h-8"
                          />
                        </div>
                        <div className="col-span-2">
                          <Input
                            type="number"
                            min="0"
                            step="0.01"
                            placeholder="P. unit."
                            value={line.valor_unitario}
                            onChange={(e) => updateLine(idx, "valor_unitario", e.target.value)}
                            className="h-8"
                          />
                        </div>
                        <div className="col-span-3">
                          <Input
                            placeholder="Descripción"
                            value={line.description || ""}
                            onChange={(e) => updateLine(idx, "description", e.target.value)}
                            className="h-8"
                          />
                        </div>
                        <div className="col-span-1">
                          <Button
                            type="button"
                            variant="ghost"
                            size="icon"
                            className="h-8 w-8"
                            onClick={() => removeLine(idx)}
                            disabled={form.lines.length === 1}
                          >
                            ×
                          </Button>
                        </div>
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
                  {saving ? "Guardando…" : "Crear (borrador)"}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>

        <Dialog open={!!sunatDialog} onOpenChange={(open) => !open && setSunatDialog(null)}>
          <DialogContent className="max-w-sm">
            <DialogHeader>
              <DialogTitle>Enviar a SUNAT</DialogTitle>
              <DialogDescription>
                Ingrese la clave SOL del usuario de la empresa. La factura {sunatDialog ? `${sunatDialog.serie}-${sunatDialog.numero}` : ""} se enviará al servicio de SUNAT.
              </DialogDescription>
            </DialogHeader>
            <div className="py-4">
              <Label>Clave SOL</Label>
              <Input
                type="password"
                value={claveSol}
                onChange={(e) => setClaveSol(e.target.value)}
                placeholder="Clave del usuario SOL"
                className="mt-2"
                autoComplete="off"
              />
            </div>
            <DialogFooter>
              <Button variant="outline" onClick={() => setSunatDialog(null)}>
                Cancelar
              </Button>
              <Button onClick={requestSendSunat} disabled={sendingId !== null || !claveSol.trim()}>
                {sendingId ? "Enviando…" : "Enviar"}
              </Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>

        <AlertDialog open={confirmSendSunat} onOpenChange={setConfirmSendSunat}>
          <AlertDialogContent>
            <AlertDialogHeader>
              <AlertDialogTitle>¿Confirmar envío a SUNAT?</AlertDialogTitle>
              <AlertDialogDescription>
                La factura {sunatDialog ? `${sunatDialog.serie}-${sunatDialog.numero}` : ""} quedará registrada ante SUNAT. Esta acción no se puede deshacer. Asegúrese de que los datos del comprobante y del cliente sean correctos.
              </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
              <AlertDialogCancel>Cancelar</AlertDialogCancel>
              <AlertDialogAction onClick={() => void handleSendSunat()} className="bg-primary text-primary-foreground">
                Sí, enviar a SUNAT
              </AlertDialogAction>
            </AlertDialogFooter>
          </AlertDialogContent>
        </AlertDialog>
      </div>

      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por serie o cliente…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
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
            label: "Acción",
            render: (item) => (
                <div className="flex gap-1 flex-wrap">
                  <Button variant="outline" size="sm" onClick={() => openTicket(item.id)} title="Ver o imprimir comprobante">
                    <FileText className="h-4 w-4 mr-1" />
                    {(item.tipo_documento === "03" ? "Boleta" : item.tipo_documento === "01" ? "Factura" : "Ticket")}
                  </Button>
                  {item.status === "draft" && (
                    <Button variant="outline" size="sm" onClick={() => openSendSunat(item)} disabled={!!sendingId}>
                      <Send className="h-4 w-4 mr-1" />
                      {sendingId === item.id ? "Enviando…" : "SUNAT"}
                    </Button>
                  )}
                </div>
              ),
          },
        ]}
        data={filtered}
        isLoading={isLoading}
        emptyMessage="No hay facturas o boletas. Cree una en borrador y envíela a SUNAT."
      />
    </div>
  );
};

export default Invoices;
