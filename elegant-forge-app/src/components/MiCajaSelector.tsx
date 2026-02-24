import { useState, useEffect, useMemo } from "react";
import api from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { useToast } from "@/hooks/use-toast";
import { getErrorMessage } from "@/lib/api";

interface CashRegisterOption {
  id: string;
  name: string;
  code?: string;
  warehouse?: string | null;
  warehouse_name?: string | null;
  branch?: string | null;
}

export function MiCajaSelector() {
  const { user, refreshUser } = useAuth();
  const [options, setOptions] = useState<CashRegisterOption[]>([]);
  const [loading, setLoading] = useState(false);
  const { toast } = useToast();

  useEffect(() => {
    api.get<CashRegisterOption[]>("/cash-registers/").then((r) => {
      setOptions(Array.isArray(r.data) ? r.data : []);
    }).catch(() => setOptions([]));
  }, []);

  const branchId = user?.default_branch || null;
  const optionsForSucursal = useMemo(() => {
    if (!branchId) return options;
    return options.filter((o) => o.branch == null || o.branch === branchId);
  }, [options, branchId]);

  const value = user?.default_cash_register || "none";
  const currentOption = value !== "none" ? options.find((o) => o.id === value) : null;
  const listToShow = useMemo(() => {
    if (!currentOption || optionsForSucursal.some((o) => o.id === currentOption.id)) return optionsForSucursal;
    return [currentOption, ...optionsForSucursal];
  }, [optionsForSucursal, currentOption]);

  const handleChange = async (v: string) => {
    const newId = v === "none" ? null : v;
    setLoading(true);
    try {
      await api.patch("/users/me/", { default_cash_register: newId });
      await refreshUser();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="hidden sm:flex items-center gap-2 text-sm">
      <span className="text-muted-foreground whitespace-nowrap">Caja:</span>
      <Select
        value={value}
        onValueChange={handleChange}
        disabled={loading || listToShow.length === 0}
      >
        <SelectTrigger className="w-[130px] sm:w-[150px] h-8 text-xs">
          <SelectValue placeholder="Elegir caja" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="none">Sin asignar</SelectItem>
          {listToShow.map((o) => (
            <SelectItem key={o.id} value={o.id}>
              {o.name} {o.warehouse_name ? ` — ${o.warehouse_name}` : o.code ? ` (${o.code})` : ""}
            </SelectItem>
          ))}
        </SelectContent>
      </Select>
    </div>
  );
}
