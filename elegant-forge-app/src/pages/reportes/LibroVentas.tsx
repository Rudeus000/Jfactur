import { useState, useEffect } from "react";
import api from "@/lib/api";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { RefreshCw, Printer } from "lucide-react";
import { useToast } from "@/hooks/use-toast";
import { getErrorMessage } from "@/lib/api";

interface LibroRow {
  id?: string;
  tipo_documento?: string;
  serie?: string;
  numero?: number;
  fecha_emision?: string;
  cliente_documento?: string;
  cliente_razon_social?: string;
  total?: number;
  [key: string]: unknown;
}

const LibroVentas = () => {
  const [data, setData] = useState<LibroRow[]>([]);
  const [loading, setLoading] = useState(false);
  const [dateFrom, setDateFrom] = useState(() => new Date().toISOString().slice(0, 7) + "-01");
  const [dateTo, setDateTo] = useState(() => new Date().toISOString().slice(0, 10));
  const { toast } = useToast();

  const fetchReport = async () => {
    setLoading(true);
    try {
      const { data: res } = await api.get<{ data?: LibroRow[] }>("/reports/libro-ventas/", {
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

  const openTicket = (invoiceId: string) => {
    api.get(`/invoices/${invoiceId}/ticket/`, { responseType: "text" }).then(({ data }) => {
      const w = window.open("", "_blank");
      if (w) {
        w.document.write(data);
        w.document.close();
      }
    }).catch(() => {
      toast({ title: "No se pudo abrir el comprobante", variant: "destructive" });
    });
  };

  const tipoLabel = (tipo: string) => (tipo === "03" ? "Boleta" : tipo === "01" ? "Factura" : tipo);

  return (
    <div>
      <div className="erp-page-header">
        <h1 className="erp-page-title">Libro de Ventas</h1>
        <p className="erp-page-subtitle">Reporte de ventas por periodo. Use el botón de cada fila para ver o imprimir el ticket, boleta o factura.</p>
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
              <th className="text-left px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">Tipo</th>
              <th className="text-left px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">Serie-Nº</th>
              <th className="text-left px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">Fecha</th>
              <th className="text-left px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">Cliente</th>
              <th className="text-right px-4 py-3 text-xs font-semibold uppercase text-muted-foreground">Total</th>
              <th className="text-center px-4 py-3 text-xs font-semibold uppercase text-muted-foreground w-[120px]">Comprobante</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan={6} className="px-4 py-8 text-center text-muted-foreground">Cargando…</td></tr>
            ) : data.length === 0 ? (
              <tr><td colSpan={6} className="px-4 py-8 text-center text-muted-foreground">No hay datos para el periodo</td></tr>
            ) : (
              data.map((row, i) => (
                <tr key={row.id ?? i} className="border-t hover:bg-muted/30">
                  <td className="px-4 py-2">{tipoLabel(row.tipo_documento ?? "")}</td>
                  <td className="px-4 py-2">{row.serie}-{row.numero}</td>
                  <td className="px-4 py-2">{row.fecha_emision}</td>
                  <td className="px-4 py-2">{row.cliente_razon_social ?? "—"}</td>
                  <td className="px-4 py-2 text-right font-medium">S/ {Number(row.total ?? 0).toFixed(2)}</td>
                  <td className="px-4 py-2 text-center">
                    {row.id ? (
                      <Button
                        variant="outline"
                        size="sm"
                        className="gap-1"
                        onClick={() => openTicket(row.id!)}
                        title={row.tipo_documento === "03" ? "Ver o imprimir boleta" : "Ver o imprimir factura"}
                      >
                        <Printer className="h-4 w-4" />
                        {row.tipo_documento === "03" ? "Boleta" : row.tipo_documento === "01" ? "Factura" : "Ticket"}
                      </Button>
                    ) : (
                      "—"
                    )}
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default LibroVentas;
