import { useState } from "react";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Plus, Search, RefreshCw } from "lucide-react";

interface Product {
  id: string;
  sku: string;
  nombre: string;
  unidad_medida: string;
  precio_venta: number | string;
  costo_unitario?: number | string;
  category?: string;
  afecto_igv: boolean;
  [key: string]: unknown;
}

const columns: Column<Product>[] = [
  { key: "sku", label: "SKU" },
  { key: "nombre", label: "Producto" },
  { key: "category", label: "Categoría" },
  {
    key: "precio_venta",
    label: "Precio",
    className: "text-right",
    render: (item) => `S/ ${Number(item.precio_venta ?? 0).toFixed(2)}`,
  },
  {
    key: "costo_unitario",
    label: "Costo",
    className: "text-right hidden lg:table-cell",
    render: (item) => `S/ ${Number(item.costo_unitario ?? 0).toFixed(2)}`,
  },
  {
    key: "afecto_igv",
    label: "IGV",
    render: (item) => (
      <span className={`erp-status-badge ${item.afecto_igv ? "bg-success/10 text-success" : "bg-muted text-muted-foreground"}`}>
        {item.afecto_igv ? "Sí" : "No"}
      </span>
    ),
  },
];

const Products = () => {
  const { data, isLoading, refresh } = useApiList<Product>({ endpoint: "/products/" });
  const [search, setSearch] = useState("");

  const filtered = data.filter(
    (p) =>
      p.nombre?.toLowerCase().includes(search.toLowerCase()) ||
      p.sku?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Productos</h1>
          <p className="erp-page-subtitle">Catálogo de productos y servicios</p>
        </div>
        <Button>
          <Plus className="h-4 w-4 mr-2" />
          Nuevo Producto
        </Button>
      </div>

      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Buscar por nombre o SKU…"
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

export default Products;
