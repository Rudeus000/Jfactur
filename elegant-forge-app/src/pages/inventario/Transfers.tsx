import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Plus, RefreshCw } from "lucide-react";

const statusLabels: Record<string, string> = {
  pending: "Pendiente",
  approved: "Aprobado",
  rejected: "Rechazado",
};

interface StockTransfer {
  id: string;
  warehouse_origin_name?: string;
  warehouse_dest_name?: string;
  date: string;
  status: string;
  notes?: string;
  [key: string]: unknown;
}

const columns: Column<StockTransfer>[] = [
  { key: "warehouse_origin_name", label: "Origen" },
  { key: "warehouse_dest_name", label: "Destino" },
  { key: "date", label: "Fecha" },
  {
    key: "status",
    label: "Estado",
    render: (item) => (
      <span
        className={`erp-status-badge ${
          item.status === "approved" ? "bg-success/10 text-success" : item.status === "rejected" ? "bg-destructive/10 text-destructive" : "bg-warning/10 text-warning"
        }`}
      >
        {statusLabels[item.status as string] || item.status}
      </span>
    ),
  },
  { key: "notes", label: "Notas", className: "hidden lg:table-cell" },
];

const Transfers = () => {
  const { data, isLoading, refresh } = useApiList<StockTransfer>({ endpoint: "/transfers/" });

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Traspasos</h1>
          <p className="erp-page-subtitle">Transferencias entre almacenes</p>
        </div>
        <Button>
          <Plus className="h-4 w-4 mr-2" />
          Nuevo Traspaso
        </Button>
      </div>
      <div className="mb-4">
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={data} isLoading={isLoading} emptyMessage="No hay traspasos. Cree uno desde Inventario → Traspasos." />
    </div>
  );
};

export default Transfers;
