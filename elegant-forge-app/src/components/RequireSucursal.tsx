import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "@/contexts/AuthContext";
import api from "@/lib/api";

/**
 * Redirige a /elegir-sucursal si el usuario no tiene sucursal (default_branch) elegida
 * y existen sucursales. Si no hay sucursales, deja pasar para que pueda crearlas.
 */
const RequireSucursal: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [hasBranches, setHasBranches] = useState<boolean | null>(null);

  useEffect(() => {
    api.get<unknown[]>("/branches/").then((r) => {
      setHasBranches(Array.isArray(r.data) && r.data.length > 0);
    }).catch(() => setHasBranches(false));
  }, []);

  useEffect(() => {
    if (hasBranches === false || hasBranches === null) return;
    if (user && !user.default_branch) {
      navigate("/elegir-sucursal", { replace: true });
    }
  }, [user?.default_branch, hasBranches, navigate]);

  if (user && !user.default_branch) {
    if (hasBranches === null) {
      return (
        <div className="min-h-screen flex items-center justify-center bg-background">
          <div className="h-10 w-10 animate-spin rounded-full border-2 border-primary border-t-transparent" />
        </div>
      );
    }
    if (hasBranches === true) {
      return (
        <div className="min-h-screen flex items-center justify-center bg-background">
          <div className="h-10 w-10 animate-spin rounded-full border-2 border-primary border-t-transparent" />
        </div>
      );
    }
  }

  return <>{children}</>;
};

export default RequireSucursal;
