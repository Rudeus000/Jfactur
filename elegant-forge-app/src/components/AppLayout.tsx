import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "@/contexts/AuthContext";
import AppSidebar from "@/components/AppSidebar";
import { MiCajaSelector } from "@/components/MiCajaSelector";
import { MiAlmacenSelector } from "@/components/MiAlmacenSelector";
import { ContextoSucursal } from "@/components/ContextoSucursal";
import { Bell, Search } from "lucide-react";
import { Input } from "@/components/ui/input";

interface AppLayoutProps {
  children: React.ReactNode;
}

const AppLayout: React.FC<AppLayoutProps> = ({ children }) => {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [globalSearch, setGlobalSearch] = useState("");

  const handleGlobalSearch = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key !== "Enter") return;
    const term = globalSearch.trim();
    if (!term) return;
    e.preventDefault();
    navigate(`/facturacion/facturas?search=${encodeURIComponent(term)}`);
    setGlobalSearch("");
  };

  return (
    <div className="flex min-h-screen w-full bg-background">
      <AppSidebar />

      <div className="flex-1 flex flex-col min-w-0">
        {/* Top bar */}
        <header className="sticky top-0 z-30 h-14 border-b bg-card/95 backdrop-blur-md shadow-sm flex items-center px-4 sm:px-6 gap-3 shrink-0">
          <div className="flex-1 max-w-md min-w-0 ml-10 lg:ml-0">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground shrink-0" />
              <Input
                placeholder="Buscar facturas (Enter) o ir a Clientes/Productos…"
                className="pl-9 h-9 bg-muted/50 border-transparent focus:border-border focus:ring-1"
                value={globalSearch}
                onChange={(e) => setGlobalSearch(e.target.value)}
                onKeyDown={handleGlobalSearch}
                aria-label="Búsqueda global"
              />
            </div>
          </div>

          <div className="flex items-center gap-2 sm:gap-3 ml-auto shrink-0">
            <button type="button" className="relative p-2 rounded-md hover:bg-muted transition-colors text-muted-foreground hover:text-foreground" aria-label="Notificaciones">
              <Bell className="h-4 w-4" />
            </button>
            <div className="hidden sm:flex items-center gap-2 pl-2 border-l border-border">
              <MiAlmacenSelector />
              <MiCajaSelector />
            </div>
            <ContextoSucursal />
            <div className="hidden md:flex items-center gap-2 text-sm border-l border-border pl-3">
              <span className="text-muted-foreground max-w-[180px] truncate" title={user?.company?.razon_social || user?.company?.nombre_comercial || ""}>
                {user?.company?.razon_social || user?.company?.nombre_comercial || "Empresa"}
              </span>
            </div>
          </div>
        </header>

        {/* Main content */}
        <main className="flex-1 p-4 sm:p-6 animate-fade-in overflow-auto">
          {children}
        </main>
      </div>
    </div>
  );
};

export default AppLayout;
