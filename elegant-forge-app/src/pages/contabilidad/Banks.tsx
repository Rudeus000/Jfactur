import { useState } from "react";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Plus, Search, RefreshCw } from "lucide-react";

interface Bank {
  id: string;
  name: string;
  code?: string;
  account_number?: string;
  [key: string]: unknown;
}

const columns: Column<Bank>[] = [
  { key: "code", label: "Código" },
  { key: "name", label: "Banco" },
  { key: "account_number", label: "Nº Cuenta", className: "hidden lg:table-cell" },
];

const Banks = () => {
  const { data, isLoading, refresh } = useApiList<Bank>({ endpoint: "/banks/" });
  const [search, setSearch] = useState("");

  const filtered = data.filter(
    (b) => b.name?.toLowerCase().includes(search.toLowerCase()) || (b.code as string)?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Bancos</h1>
          <p className="erp-page-subtitle">Bancos para asociar a cuentas y pagos. Busque por nombre o código.</p>
        </div>
        <Button variant="outline" disabled title="Configuración desde administración">
          <Plus className="h-4 w-4 mr-2" />
          Nuevo Banco
        </Button>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por nombre o código…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay bancos registrados" />
    </div>
  );
};

export default Banks;
