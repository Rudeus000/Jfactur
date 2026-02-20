import { useState } from "react";
import { useApiList } from "@/hooks/useApiList";
import { DataTable, Column } from "@/components/DataTable";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Plus, Search, RefreshCw } from "lucide-react";

interface Currency {
  id: string;
  code: string;
  name: string;
  symbol?: string;
  is_default?: boolean;
  [key: string]: unknown;
}

const columns: Column<Currency>[] = [
  { key: "code", label: "Código" },
  { key: "name", label: "Nombre" },
  { key: "symbol", label: "Símbolo" },
  {
    key: "is_default",
    label: "Por defecto",
    render: (item) => (item.is_default ? "Sí" : "—"),
  },
];

const Currencies = () => {
  const { data, isLoading, refresh } = useApiList<Currency>({ endpoint: "/currencies/" });
  const [search, setSearch] = useState("");

  const filtered = data.filter(
    (c) => c.code?.toLowerCase().includes(search.toLowerCase()) || c.name?.toLowerCase().includes(search.toLowerCase())
  );

  return (
    <div>
      <div className="erp-page-header flex items-start justify-between">
        <div>
          <h1 className="erp-page-title">Monedas</h1>
          <p className="erp-page-subtitle">Monedas disponibles para comprobantes y reportes. Suele incluir PEN por defecto.</p>
        </div>
        <Button variant="outline" disabled title="Configuración desde administración">
          <Plus className="h-4 w-4 mr-2" />
          Nueva Moneda
        </Button>
      </div>
      <div className="flex gap-3 mb-4">
        <div className="relative flex-1 max-w-sm">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Buscar…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-9" />
        </div>
        <Button variant="outline" size="icon" onClick={() => refresh()}>
          <RefreshCw className="h-4 w-4" />
        </Button>
      </div>
      <DataTable columns={columns} data={filtered} isLoading={isLoading} emptyMessage="No hay monedas configuradas" />
    </div>
  );
};

export default Currencies;
