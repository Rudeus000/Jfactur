import { useAuth } from "@/contexts/AuthContext";
import api from "@/lib/api";
import { useState, useEffect } from "react";

interface CashRegisterOption {
  id: string;
  name: string;
}

export function ContextoSucursal() {
  const { user } = useAuth();
  const [cashRegisters, setCashRegisters] = useState<CashRegisterOption[]>([]);

  useEffect(() => {
    api.get<CashRegisterOption[]>("/cash-registers/").then((r) => setCashRegisters(Array.isArray(r.data) ? r.data : [])).catch(() => setCashRegisters([]));
  }, []);

  const sucursalName = user?.default_branch_name ?? null;
  const cajaName = user?.default_cash_register ? cashRegisters.find((c) => c.id === user.default_cash_register)?.name : null;

  if (!sucursalName && !cajaName) return null;

  return (
    <div className="hidden lg:flex items-center gap-2 text-xs text-muted-foreground border-l border-border pl-3">
      <span className="font-medium text-foreground/80">Trabajando en:</span>
      {sucursalName && <span>{sucursalName}</span>}
      {sucursalName && cajaName && <span>·</span>}
      {cajaName && <span>Caja {cajaName}</span>}
    </div>
  );
}
