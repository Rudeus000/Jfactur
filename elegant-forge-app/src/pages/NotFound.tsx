import { Link } from "react-router-dom";
import { Building2, Home } from "lucide-react";
import { Button } from "@/components/ui/button";

const NotFound = () => {
  return (
    <div className="min-h-screen flex flex-col items-center justify-center bg-background p-6">
      <div className="erp-card max-w-md w-full p-8 text-center animate-fade-in">
        <div className="flex justify-center mb-4">
          <div className="h-16 w-16 rounded-full bg-muted flex items-center justify-center">
            <Building2 className="h-8 w-8 text-muted-foreground" />
          </div>
        </div>
        <h1 className="text-4xl font-bold tracking-tight text-foreground mb-2">404</h1>
        <p className="text-muted-foreground mb-1">Página no encontrada</p>
        <p className="text-sm text-muted-foreground mb-6">
          La ruta a la que intenta acceder no existe o fue movida.
        </p>
        <Button asChild className="gap-2">
          <Link to="/">
            <Home className="h-4 w-4" />
            Volver al inicio
          </Link>
        </Button>
        <p className="mt-6 text-xs text-muted-foreground">
          Jfactur · Sistema de gestión empresarial
        </p>
      </div>
    </div>
  );
};

export default NotFound;
