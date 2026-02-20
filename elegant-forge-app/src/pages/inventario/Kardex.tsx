import { useState } from "react";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Search, RefreshCw } from "lucide-react";

const movementLabels: Record<string, string> = {
  in: "Entrada",
  out: "Salida",
  adjust: "Ajuste",
  purchase: "Compra",
  sale: "Venta",
  transfer: "Traspaso",
};

interface StockMovement {
  id: string;
  product_name?: string;
  warehouse_name?: string;
  movement_type: string;
  quantity: number | string;
  quantity_after?: number | string;
  reference?: string;
  date: string;
  [key: string]: unknown;
}

const columns: Column<StockMovement>[] = [
  { key: "product_name", label: "Producto" },
  { key: "warehouse_name", label: "Almacén" },
  {
    key: "movement_type",
    label: "Tipo",
    render: (item) => movementLabels[item.movement_type as string] || item.movement_type,
  },
  {
    key: "quantity",
    label: "Cantidad",
    className: "text-right",
    render: (item) => Number(item.quantity ?? 0).toLocaleString(),
  },
  { key: "reference", label: "Referencia" },
  { key: "date", label: "Fecha" },
];

const Kardex = () => {
  const { data, isLoading, refresh } = useApiList<StockMovement>({ endpoint: "/stock-movements/" });
  const [search, setSearch] = useState("");

  const filtered = data.filter(
    (m) =>
      (m.product_name as string)?.toLowerCase().includes(search.toLowerCase()) ||
      (m.reference as string)?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Kardex</h1>
          <p className="erp-page-subtitle">Historial de entradas, salidas, ajustes y traspasos por producto y almacén.</p>
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por producto o referencia…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
      </div>
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay movimientos. Las compras y ventas generan movimientos automáticamente." />
    </div>
  );
};

export default Kardex;
