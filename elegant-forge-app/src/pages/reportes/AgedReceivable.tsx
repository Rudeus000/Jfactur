import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import api from "@/lib/api";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { RefreshCw, FileText, DollarSign } from "lucide-react";
import { useToast } from "@/hooks/use-toast";
import { getErrorMessage } from "@/lib/api";

interface AgedRow {
  cliente_numero_documento?: string;
  cliente_razon_social?: string;
  total: number;
  days_0_30: number;
  days_31_60: number;
  days_61_plus: number;
}

const AgedReceivable = () => {
  const [data, setData] = useState<AgedRow[]>([]);
  const [loading, setLoading] = useState(false);
  const [asOf, setAsOf] = useState(() => new Date().toISOString().slice(0, 10));
  const { toast } = useToast();

  const fetchReport = async () => {
    setLoading(true);
    try {
      const { data: res } = await api.get<{ data: AgedRow[] }>("/reports/aged-receivable/", { params: { as_of: asOf } });
      setData(res?.data ?? []);
    } catch (err) {
      toast({ title: "Error", description: getErrorMessage(err), variant: "destructive" });
      setData([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchReport();
  }, []);

  return (
    <div>
      <div className="erp-page-header">
        <h1 className="erp-page-title">Cuentas por Cobrar</h1>
        <p className="erp-page-subtitle">
          Saldos por cobrar (0-30, 31-60, 61+ días). Para registrar cobros vaya a{" "}
          <Link to="/facturacion/cobros" className="text-primary underline inline-flex items-center gap-1">
            <DollarSign className="h-4 w-4" /> Cobros
          </Link>
          ; para ver o imprimir boletas y facturas use el{" "}
          <Link to="/reportes/libro-ventas" className="text-primary underline inline-flex items-center gap-1">
            <FileText className="h-4 w-4" /> Libro de Ventas
          </Link>.
        </p>
      </div>
      <div className="flex flex-wrap gap-3 mb-4 items-end">
        <div>
          <label className="text-xs font-medium text-muted-foreground block mb-1">Fecha corte</label>
          <Input type="date" value={asOf} onChange={(e) => setAsOf(e.target.value)} className="w-40" />
        </div>
        <Button onClick={fetchReport} disabled={loading}>
          <RefreshCw className={`h-4 w-4 mr-2 ${loading ? "animate-spin" : ""}`} />
          Actualizar
        </Button>
      </div>
      <div className="erp-card overflow-x-auto">
        <table className="w-full text-sm">
          <thead>
            <tr className="bg-muted/50">
              <th className="text-left px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">Cliente</th>
              <th className="text-right px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">Total</th>
              <th className="text-right px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">0-30 días</th>
              <th className="text-right px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">31-60 días</th>
              <th className="text-right px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">61+ días</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan={5} className="px-4 py-8 text-center text-muted-foreground">Cargando…</td></tr>
            ) : data.length === 0 ? (
              <tr><td colSpan={5} className="px-4 py-8 text-center text-muted-foreground">No hay saldos por cobrar</td></tr>
            ) : (
              data.map((row, i) => (
                <tr key={i} className="border-t hover:bg-muted/30">
                  <td className="px-4 py-2">{row.cliente_razon_social ?? row.cliente_numero_documento ?? "—"}</td>
                  <td className="px-4 py-2 text-right font-medium">S/ {Number(row.total ?? 0).toFixed(2)}</td>
                  <td className="px-4 py-2 text-right">S/ {Number(row.days_0_30 ?? 0).toFixed(2)}</td>
                  <td className="px-4 py-2 text-right">S/ {Number(row.days_31_60 ?? 0).toFixed(2)}</td>
                  <td className="px-4 py-2 text-right">S/ {Number(row.days_61_plus ?? 0).toFixed(2)}</td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default AgedReceivable;
