import { TrendingUp, TrendingDown, DollarSign, FileText, Users, Package } from "lucide-react";

const kpis = [
  { label: "Ventas del Día", value: "S/ 12,450.00", change: "+8.2%", up: true, icon: DollarSign },
  { label: "Facturas Emitidas", value: "24", change: "+3", up: true, icon: FileText },
  { label: "Por Cobrar", value: "S/ 45,200.00", change: "-2.1%", up: false, icon: TrendingDown },
  { label: "Por Pagar", value: "S/ 18,750.00", change: "+5.4%", up: true, icon: TrendingUp },
  { label: "Clientes Activos", value: "156", change: "+12", up: true, icon: Users },
  { label: "Productos en Stock", value: "1,240", change: "-15", up: false, icon: Package },
];

const recentInvoices = [
  { id: "F001-00245", client: "Distribuidora Lima SAC", amount: "S/ 2,340.00", status: "Enviada", date: "19/02/2026" },
  { id: "B001-00198", client: "Juan Pérez García", amount: "S/ 450.00", status: "Pagada", date: "19/02/2026" },
  { id: "F001-00244", client: "Comercial Arequipa EIRL", amount: "S/ 8,920.00", status: "Pendiente", date: "18/02/2026" },
  { id: "F001-00243", client: "Exportadora del Sur SA", amount: "S/ 15,600.00", status: "Enviada", date: "18/02/2026" },
  { id: "B001-00197", client: "María López Torres", amount: "S/ 120.00", status: "Pagada", date: "18/02/2026" },
];

const statusColors: Record<string, string> = {
  Enviada: "bg-info/10 text-info",
  Pagada: "bg-success/10 text-success",
  Pendiente: "bg-warning/10 text-warning",
  Rechazada: "bg-destructive/10 text-destructive",
};

const Dashboard = () => {
  return (
    <div>
      <div className="erp-page-header">
        <h1 className="erp-page-title">Dashboard</h1>
        <p className="erp-page-subtitle">Resumen general de operaciones</p>
      </div>

      {/* KPIs */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        {kpis.map((kpi) => (
          <div key={kpi.label} className="erp-kpi-card animate-fade-in">
            <div className="flex items-center justify-between">
              <span className="erp-kpi-label">{kpi.label}</span>
              <kpi.icon className="h-4 w-4 text-muted-foreground" />
            </div>
            <span className="erp-kpi-value">{kpi.value}</span>
            <span className={`text-xs font-medium ${kpi.up ? "text-success" : "text-destructive"}`}>
              {kpi.change} vs ayer
            </span>
          </div>
        ))}
      </div>

      {/* Recent invoices */}
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
              {recentInvoices.map((inv) => (
                <tr key={inv.id} className="border-t hover:bg-muted/30 transition-colors">
                  <td className="px-5 py-3 font-medium">{inv.id}</td>
                  <td className="px-5 py-3">{inv.client}</td>
                  <td className="px-5 py-3 text-right font-medium">{inv.amount}</td>
                  <td className="px-5 py-3 text-center">
                    <span className={`erp-status-badge ${statusColors[inv.status] || ""}`}>
                      {inv.status}
                    </span>
                  </td>
                  <td className="px-5 py-3 text-muted-foreground">{inv.date}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
