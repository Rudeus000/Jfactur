import { useState } from "react";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Plus, Search, RefreshCw } from "lucide-react";

interface Invoice {
  id: string;
  serie: string;
  numero: number;
  cliente_razon_social: string;
  total: number | string;
  igv_total: number | string;
  status: string;
  fecha_emision: string;
  [key: string]: unknown;
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

const columns: Column<Invoice>[] = [
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

const Invoices = () => {
  const { data, isLoading, refresh } = useApiList<Invoice>({ endpoint: "/invoices/" });
  const [search, setSearch] = useState("");

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
          <p className="erp-page-subtitle">Emisión y gestión de comprobantes de pago</p>
        </div>
        <Button>
          <Plus className="h-4 w-4 mr-2" />
          Nueva Factura
        </Button>
      </div>

      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Buscar por serie o cliente…"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="pl-9"
          />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>

      <DataTable columns={columns} data={filtered} isLoading={isLoading} />
    </div>
  );
};

export default Invoices;
