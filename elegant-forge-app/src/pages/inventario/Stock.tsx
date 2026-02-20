import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { RefreshCw } from "lucide-react";

interface StockQuant {
  id: string;
  product_name: string;
  product_sku: string;
  warehouse_name: string;
  quantity: number | string;
  product_unit?: string;
  [key: string]: unknown;
}

const columns: Column<StockQuant>[] = [
  { key: "product_sku", label: "SKU" },
  { key: "product_name", label: "Producto" },
  { key: "warehouse_name", label: "Almacén" },
  {
    key: "quantity",
    label: "Cantidad",
    className: "text-right font-medium",
    render: (item) => Number(item.quantity ?? 0).toLocaleString(),
  },
  { key: "product_unit", label: "Unidad" },
];

const Stock = () => {
  const { data, isLoading, refresh } = useApiList<StockQuant>({ endpoint: "/stock-quants/" });

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Stock</h1>
          <p className="erp-page-subtitle">Existencias actuales por almacén</p>
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>

      <DataTable columns={columns} data={data} isLoading={isLoading} emptyMessage="No hay existencias. Realice compras o traspasos para ver stock." />
    </div>
  );
};

export default Stock;
