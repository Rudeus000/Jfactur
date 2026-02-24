import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import api from "@/lib/api";
import { TrendingUp, TrendingDown, DollarSign, FileText, AlertCircle, HelpCircle, ArrowRight } from "lucide-react";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";

interface DashboardData {
  ventas_hoy: number;
  facturas_pendientes_sunat: number;
  total_por_cobrar: number;
  total_por_pagar: number;
  alertas: Array<{ tipo: string; mensaje: string }>;
}

interface InvoiceRow {
  id: string;
  serie: string;
  numero: number;
  cliente_razon_social: string;
  total: number | string;
  status: string;
  fecha_emision: string;
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

const Dashboard = () => {
  const [kpis, setKpis] = useState<DashboardData | null>(null);
  const [invoices, setInvoices] = useState<InvoiceRow[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [dashboardRes, invRes] = await Promise.all([
          api.get<DashboardData>("/reports/dashboard/"),
          api.get<InvoiceRow[]>("/invoices/"),
        ]);
        setKpis(dashboardRes.data);
        setInvoices(Array.isArray(invRes.data) ? invRes.data.slice(0, 8) : []);
      } catch {
        setKpis(null);
        setInvoices([]);
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, []);

  if (loading) {
    return (
      <div>
        <div className="erp-page-header">
          <h1 className="erp-page-title">Dashboard</h1>
          <p className="erp-page-subtitle">Cargando…</p>
        </div>
      </div>
    );
  }

  const kpiItems = kpis
    ? [
        { label: "Ventas del Día", value: `S/ ${(kpis.ventas_hoy ?? 0).toLocaleString("es-PE", { minimumFractionDigits: 2 })}`, icon: DollarSign },
        { label: "Facturas pendientes SUNAT", value: String(kpis.facturas_pendientes_sunat ?? 0), icon: FileText },
        { label: "Por Cobrar", value: `S/ ${(kpis.total_por_cobrar ?? 0).toLocaleString("es-PE", { minimumFractionDigits: 2 })}`, icon: TrendingDown },
        { label: "Por Pagar", value: `S/ ${(kpis.total_por_pagar ?? 0).toLocaleString("es-PE", { minimumFractionDigits: 2 })}`, icon: TrendingUp },
      ]
    : [];

  return (
    <div>
      <div className="erp-page-header">
        <h1 className="erp-page-title">Dashboard</h1>
        <p className="erp-page-subtitle">Resumen general de operaciones</p>
      </div>

      {/* Guía rápida */}
      <Card className="mb-4 border-primary/20 bg-primary/5">
        <CardHeader className="pb-2">
          <CardTitle className="text-base flex items-center gap-2">
            <HelpCircle className="h-4 w-4" />
            ¿Primera vez aquí?
          </CardTitle>
          <CardDescription>
            Orden de configuración, flujos (facturar, cobrar, caja, compras, traspasos) y qué revisar si algo falla.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <Button asChild variant="outline" size="sm">
            <Link to="/como-usar" className="flex items-center gap-2">
              Ver cómo usar el sistema
              <ArrowRight className="h-4 w-4" />
            </Link>
          </Button>
        </CardContent>
      </Card>

      {kpis?.alertas && kpis.alertas.length > 0 && (
        <div className="mb-4 flex flex-wrap gap-2">
          {kpis.alertas.map((a, i) => (
            <div key={i} className="flex items-center gap-2 rounded-md bg-destructive/10 border border-destructive/20 px-3 py-2 text-sm text-destructive">
              <AlertCircle className="h-4 w-4 shrink-0" />
              {a.mensaje}
            </div>
          ))}
        </div>
      )}

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {kpiItems.map((kpi) => (
          <div key={kpi.label} className="erp-kpi-card animate-fade-in">
            <div className="flex items-center justify-between">
              <span className="erp-kpi-label">{kpi.label}</span>
              <kpi.icon className="h-4 w-4 text-muted-foreground" />
            </div>
            <span className="erp-kpi-value">{kpi.value}</span>
          </div>
        ))}
      </div>

      <div className="erp-card">
        <div className="px-5 py-4 border-b">
          <h2 className="font-semibold">Últimas Facturas</h2>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="bg-muted/50">
                <th className="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Serie-Número</th>
                <th className="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Cliente</th>
                <th className="text-right px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Monto</th>
                <th className="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Estado</th>
                <th className="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">Fecha</th>
              </tr>
            </thead>
            <tbody>
              {invoices.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-5 py-8 text-center text-muted-foreground">
                    No hay facturas recientes
                  </td>
                </tr>
              ) : (
                invoices.map((inv) => (
                  <tr key={inv.id} className="border-t hover:bg-muted/30 transition-colors">
                    <td className="px-5 py-3 font-medium">{inv.serie}-{inv.numero}</td>
                    <td className="px-5 py-3">{inv.cliente_razon_social}</td>
                    <td className="px-5 py-3 text-right font-medium">S/ {Number(inv.total ?? 0).toFixed(2)}</td>
                    <td className="px-5 py-3 text-center">
                      <span className={`erp-status-badge ${statusColors[inv.status] || ""}`}>
                        {statusLabels[inv.status] || inv.status}
                      </span>
                    </td>
                    <td className="px-5 py-3 text-muted-foreground">{inv.fecha_emision}</td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
