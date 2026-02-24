import { useState } from "react";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Plus, Search, RefreshCw } from "lucide-react";

const tipoLabels: Record<string, string> = {
  "01": "Factura",
  "03": "Boleta",
};

interface InvoiceSeriesItem {
  id: string;
  serie: string;
  tipo_documento: string;
  next_number?: number;
  last_number?: number;
  is_default?: boolean;
  is_active?: boolean;
  [key: string]: unknown;
}

const columns: Column<InvoiceSeriesItem>[] = [
  { key: "serie", label: "Serie" },
  {
    key: "tipo_documento",
    label: "Tipo",
    render: (item) => tipoLabels[item.tipo_documento as string] || item.tipo_documento,
  },
  {
    key: "next_number",
    label: "Siguiente Nº",
    className: "text-right",
  },
  {
    key: "is_default",
    label: "Por defecto",
    render: (item) => (item.is_default ? "Sí" : "—"),
  },
  {
    key: "is_active",
    label: "Activo",
    render: (item) => (
      <span className={`erp-status-badge ${item.is_active !== false ? "bg-success/10 text-success" : "bg-muted text-muted-foreground"}`}>
        {item.is_active !== false ? "Sí" : "No"}
      </span>
    ),
  },
];

const InvoiceSeries = () => {
  const { data, isLoading, refresh } = useApiList<InvoiceSeriesItem>({ endpoint: "/invoice-series/" });
  const [search, setSearch] = useState("");

  const filtered = data.filter((s) => (s.serie as string)?.toLowerCase().includes(search.toLowerCase()));

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Series</h1>
          <p className="erp-page-subtitle">Series de facturación</p>
        </div>
        <Button>
          <Plus className="h-4 w-4 mr-2" />
          Nueva Serie
        </Button>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por serie…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay series. Cree una serie de factura o boleta asociada a un almacén." />
    </div>
  );
};

export default InvoiceSeries;
