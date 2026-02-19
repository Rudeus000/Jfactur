import { useState } from "react";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Plus, Search, RefreshCw } from "lucide-react";

interface Customer {
  id: string;
  tipo_documento: string;
  numero_documento: string;
  razon_social: string;
  email?: string;
  telefono?: string;
  direccion?: string;
  [key: string]: unknown;
}

const columns: Column<Customer>[] = [
  { key: "numero_documento", label: "Documento" },
  { key: "razon_social", label: "Razón Social" },
  { key: "email", label: "Email" },
  { key: "telefono", label: "Teléfono" },
  { key: "direccion", label: "Dirección", className: "hidden lg:table-cell" },
];

const Customers = () => {
  const { data, isLoading, refresh } = useApiList<Customer>({ endpoint: "/customers/" });
  const [search, setSearch] = useState("");

  const filtered = data.filter(
    (c) =>
      c.razon_social?.toLowerCase().includes(search.toLowerCase()) ||
      c.numero_documento?.includes(search)
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Clientes</h1>
          <p className="erp-page-subtitle">Gestión del directorio de clientes</p>
        </div>
        <Button>
          <Plus className="h-4 w-4 mr-2" />
          Nuevo Cliente
        </Button>
      </div>

      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Buscar por nombre o documento…"
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

export default Customers;
