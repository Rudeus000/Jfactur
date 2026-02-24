import { useState } from "react";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Plus, Search, RefreshCw } from "lucide-react";

interface Account {
  id: string;
  code: string;
  name: string;
  [key: string]: unknown;
}

const columns: Column<Account>[] = [
  { key: "code", label: "Código" },
  { key: "name", label: "Nombre" },
];

const Accounts = () => {
  const { data, isLoading, refresh } = useApiList<Account>({ endpoint: "/accounts/" });
  const [search, setSearch] = useState("");

  const filtered = data.filter(
    (a) => a.code?.toLowerCase().includes(search.toLowerCase()) || a.name?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Plan de Cuentas</h1>
          <p className="erp-page-subtitle">Plan de cuentas contables. Busque por código o nombre.</p>
        </div>
        <Button variant="outline" disabled title="Configuración desde administración">
          <Plus className="h-4 w-4 mr-2" />
          Nueva Cuenta
        </Button>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar por código o nombre…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay cuentas en el plan" />
    </div>
  );
};

export default Accounts;
