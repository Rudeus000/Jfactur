import { useAuth } from "@/contexts/AuthContext";
import AppSidebar from "@/components/AppSidebar";
import { Bell, Search } from "lucide-react";
import { Input } from "@/components/ui/input";

interface AppLayoutProps {
  children: React.ReactNode;
}

const AppLayout: React.FC<AppLayoutProps> = ({ children }) => {
  const { user } = useAuth();

  return (
    <div className="flex min-h-screen w-full bg-background">
      <AppSidebar />

      <div className="flex-1 flex flex-col min-w-0">
        {/* Top bar */}
        <header className="sticky top-0 z-30 h-14 border-b bg-card/80 backdrop-blur-sm flex items-center px-6 gap-4 shrink-0">
          <div className="flex-1 max-w-md ml-10 lg:ml-0">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
              <Input
                placeholder="Buscar…"
                className="pl-9 h-9 bg-muted/50 border-transparent focus:border-border"
              />
            </div>
          </div>

          <div className="flex items-center gap-3 ml-auto">
            <button className="relative p-2 rounded-md hover:bg-muted transition-colors">
              <Bell className="h-4 w-4 text-muted-foreground" />
            </button>
            <div className="hidden sm:flex items-center gap-2 text-sm">
              <span className="text-muted-foreground">
                {user?.company?.razon_social || user?.company?.nombre_comercial || "Empresa"}
              </span>
            </div>
          </div>
        </header>

        {/* Main content */}
        <main className="flex-1 p-6 animate-fade-in">
          {children}
        </main>
      </div>
    </div>
  );
};

export default AppLayout;
