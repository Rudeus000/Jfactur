import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import api from "@/lib/api";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { RefreshCw, FileText } from "lucide-react";
import { useToast } from "@/hooks/use-toast";
import { getErrorMessage } from "@/lib/api";

interface VentasClienteRow {
  cliente_razon_social?: string;
  cliente_numero_documento?: string;
  total_ventas?: number;
  [key: string]: unknown;
}

const VentasCliente = () => {
  const [data, setData] = useState<VentasClienteRow[]>([]);
  const [loading, setLoading] = useState(false);
  const [dateFrom, setDateFrom] = useState(() => new Date().toISOString().slice(0, 7) + "-01");
  const [dateTo, setDateTo] = useState(() => new Date().toISOString().slice(0, 10));
  const { toast } = useToast();

  const fetchReport = async () => {
    setLoading(true);
    try {
      const { data: res } = await api.get<{ data?: VentasClienteRow[] }>("/reports/ventas-por-cliente/", {
        params: { date_from: dateFrom, date_to: dateTo },
      });
      const rows = res?.data ?? [];
      setData(rows);
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
        <h1 className="erp-page-title">Ventas por Cliente</h1>
        <p className="erp-page-subtitle">
          Análisis de ventas por cliente. Para ver o imprimir boletas y facturas use el{" "}
          <Link to="/reportes/libro-ventas" className="text-primary underline inline-flex items-center gap-1">
            <FileText className="h-4 w-4" /> Libro de Ventas
          </Link>.
        </p>
      </div>
      <div className="flex flex-wrap gap-3 mb-4 items-end">
        <div>
          <label className="text-xs font-medium text-muted-foreground block mb-1">Desde</label>
          <Input type="date" value={dateFrom} onChange={(e) => setDateFrom(e.target.value)} className="w-40" />
        </div>
        <div>
          <label className="text-xs font-medium text-muted-foreground block mb-1">Hasta</label>
          <Input type="date" value={dateTo} onChange={(e) => setDateTo(e.target.value)} className="w-40" />
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
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan={2} className="px-4 py-8 text-center text-muted-foreground">Cargando…</td></tr>
            ) : data.length === 0 ? (
              <tr><td colSpan={2} className="px-4 py-8 text-center text-muted-foreground">No hay datos</td></tr>
            ) : (
              data.map((row, i) => (
                <tr key={i} className="border-t hover:bg-muted/30">
                  <td className="px-4 py-2">{row.cliente_razon_social ?? row.cliente_numero_documento ?? "—"}</td>
                  <td className="px-4 py-2 text-right font-medium">S/ {Number(row.total_ventas ?? 0).toFixed(2)}</td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default VentasCliente;
