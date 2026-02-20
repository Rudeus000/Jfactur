import { useState, useEffect } from "react";
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

interface Option {
  id: string;
  name: string;
  code?: string;
  branch?: string | null;
}

export function MiAlmacenSelector() {
  const { user, refreshUser } = useAuth();
  const [options, setOptions] = useState<Option[]>([]);
  const [loading, setLoading] = useState(false);
  const { toast } = useToast();

  useEffect(() => {
    api.get<Option[]>("/warehouses/").then((r) => {
      setOptions(Array.isArray(r.data) ? r.data : []);
    }).catch(() => setOptions([]));
  }, []);

  const branchId = user?.default_branch ?? null;
  const optionsForBranch = branchId
    ? options.filter((o) => o.branch == null || o.branch === branchId)
    : options;

  const value = user?.default_warehouse || "none";

  const handleChange = async (v: string) => {
    const newId = v === "none" ? null : v;
    setLoading(true);
    try {
      await api.patch("/users/me/", { default_warehouse: newId });
      await refreshUser();
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="hidden sm:flex items-center gap-2 text-sm">
      <span className="text-muted-foreground whitespace-nowrap">Almacén:</span>
      <Select value={value} onValueChange={handleChange} disabled={loading || optionsForBranch.length === 0}>
        <SelectTrigger className="w-[140px] sm:w-[160px] h-8 text-xs">
          <SelectValue placeholder="Elegir almacén" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="none">Sin asignar</SelectItem>
          {optionsForBranch.map((o) => (
            <SelectItem key={o.id} value={o.id}>
              {o.name} {o.code ? `(${o.code})` : ""}
            </SelectItem>
          ))}
        </SelectContent>
      </Select>
    </div>
  );
}
