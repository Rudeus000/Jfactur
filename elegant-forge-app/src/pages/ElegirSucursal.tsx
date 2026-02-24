import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "@/contexts/AuthContext";
import api from "@/lib/api";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Building2, MapPin, Megaphone, Pin, Plus } from "lucide-react";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { useToast } from "@/hooks/use-toast";
import { getErrorMessage } from "@/lib/api";

interface Branch {
  id: string;
  name: string;
  code?: string;
}

interface Announcement {
  id: string;
  title: string;
  content: string;
  branch_name: string | null;
  is_pinned: boolean;
  valid_until: string | null;
  created_at: string;
}

const ElegirSucursal = () => {
  const { user, refreshUser } = useAuth();
  const navigate = useNavigate();
  const [branches, setBranches] = useState<Branch[]>([]);
  const [selected, setSelected] = useState<string>("");
  const [loading, setLoading] = useState(false);
  const [announcements, setAnnouncements] = useState<Announcement[]>([]);
  const [creating, setCreating] = useState(false);
  const [newName, setNewName] = useState("");
  const [newCode, setNewCode] = useState("");
  const { toast } = useToast();

  useEffect(() => {
    api.get<Branch[]>("/branches/").then((r) => {
      const list = Array.isArray(r.data) ? r.data : [];
      setBranches(list);
      if (user?.default_branch && list.some((b) => b.id === user.default_branch)) {
        setSelected(user.default_branch);
      } else if (list.length > 0) {
        setSelected(list[0].id);
      }
    }).catch(() => setBranches([]));
  }, [user?.default_branch]);

  useEffect(() => {
    const params = selected ? { params: { branch_id: selected } } : {};
    api.get<Announcement[] | { results: Announcement[] }>("/announcements/", params).then((r) => {
      const raw = r.data as { results?: Announcement[] } | Announcement[];
      const list = Array.isArray(raw) ? raw : (raw?.results ?? []);
      setAnnouncements(list);
    }).catch(() => setAnnouncements([]));
  }, [selected]);

  const handleContinuar = async () => {
    if (!selected) {
      toast({ title: "Seleccione una sucursal", variant: "destructive" });
      return;
    }
    setLoading(true);
    try {
      await api.patch("/users/me/", { default_branch: selected });
      await refreshUser();
      navigate("/", { replace: true });
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setLoading(false);
    }
  };

  const handleCrearSucursal = async () => {
    const name = newName.trim() || "Principal";
    setCreating(true);
    try {
      const { data } = await api.post<Branch>("/branches/", { name, code: newCode.trim() || undefined });
      setBranches((prev) => [...prev, data]);
      setSelected(data.id);
      setNewName("");
      setNewCode("");
      toast({ title: "Sucursal creada", description: `"${data.name}" agregada. Puede continuar al inicio.` });
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setCreating(false);
    }
  };

  const yaTieneSucursal = user?.default_branch && branches.some((b) => b.id === user.default_branch);
  const nombreSucursalActual = yaTieneSucursal
    ? branches.find((b) => b.id === user.default_branch)?.name
    : user?.default_branch_name ?? null;

  return (
    <div className="min-h-screen flex flex-col items-center justify-center p-6 bg-background">
      <div className="erp-card max-w-md w-full p-8 animate-fade-in">
        <div className="flex items-center gap-3 mb-6">
          <div className="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
            <MapPin className="h-6 w-6 text-primary" />
          </div>
          <div>
            <h1 className="text-xl font-semibold tracking-tight">Elegir sucursal</h1>
            <p className="text-sm text-muted-foreground">
              Seleccione la sucursal en la que va a trabajar. Luego podrá elegir almacén y caja de esa sucursal.
            </p>
          </div>
        </div>

        {branches.length === 0 ? (
          <>
            <p className="text-sm text-muted-foreground py-2">
              No hay sucursales. Cree la primera para poder trabajar.
            </p>
            <div className="grid gap-3 mt-4">
              <div className="grid gap-2">
                <Label htmlFor="branch-name">Nombre de la sucursal</Label>
                <Input
                  id="branch-name"
                  placeholder="Ej: Principal, Lima, Tienda Centro"
                  value={newName}
                  onChange={(e) => setNewName(e.target.value)}
                  onKeyDown={(e) => e.key === "Enter" && handleCrearSucursal()}
                />
              </div>
              <div className="grid gap-2">
                <Label htmlFor="branch-code">Código (opcional)</Label>
                <Input
                  id="branch-code"
                  placeholder="Ej: 001"
                  value={newCode}
                  onChange={(e) => setNewCode(e.target.value)}
                  onKeyDown={(e) => e.key === "Enter" && handleCrearSucursal()}
                />
              </div>
              <Button
                className="w-full"
                onClick={handleCrearSucursal}
                disabled={creating}
              >
                <Plus className="h-4 w-4 mr-2" />
                {creating ? "Creando…" : "Crear sucursal"}
              </Button>
            </div>
            <Button className="w-full mt-3" variant="ghost" size="sm" onClick={() => navigate("/", { replace: true })}>
              Continuar al inicio sin sucursal
            </Button>
          </>
        ) : (
          <>
            <div className="grid gap-2 mb-6">
              <Label>Sucursal</Label>
              <Select value={selected} onValueChange={setSelected}>
                <SelectTrigger>
                  <SelectValue placeholder="Seleccione sucursal" />
                </SelectTrigger>
                <SelectContent>
                  {branches.map((b) => (
                    <SelectItem key={b.id} value={b.id}>
                      {b.name} {b.code ? `(${b.code})` : ""}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <Button className="w-full" onClick={handleContinuar} disabled={loading}>
              {loading ? "Guardando…" : yaTieneSucursal ? "Continuar con esta sucursal" : "Continuar al inicio"}
            </Button>
          </>
        )}

        {nombreSucursalActual && (
          <p className="mt-4 text-center text-xs text-muted-foreground">
            Sucursal actual: <strong>{nombreSucursalActual}</strong>
          </p>
        )}
        <p className="mt-6 text-center text-xs text-muted-foreground">
          <Building2 className="inline h-3 w-3 mr-1" />
          Jfactur
        </p>
      </div>

      {/* Tablón de anuncios: avisos para empleados (reuniones, citas, etc.) */}
      {announcements.length > 0 && (
        <div className="erp-card max-w-md w-full mt-4 p-6 animate-fade-in">
          <h2 className="flex items-center gap-2 text-sm font-semibold text-foreground mb-4">
            <Megaphone className="h-4 w-4 text-primary" />
            Tablón de anuncios
          </h2>
          <ul className="space-y-3">
            {announcements.map((a) => (
              <li
                key={a.id}
                className="rounded-lg border border-border bg-muted/30 p-3 text-left"
              >
                <div className="flex items-start gap-2">
                  {a.is_pinned && (
                    <Pin className="h-4 w-4 shrink-0 text-primary mt-0.5" aria-hidden />
                  )}
                  <div className="min-w-0 flex-1">
                    <p className="font-medium text-sm text-foreground">{a.title}</p>
                    {a.content && (
                      <p className="mt-1 text-xs text-muted-foreground whitespace-pre-wrap line-clamp-3">
                        {a.content}
                      </p>
                    )}
                    <p className="mt-2 text-[11px] text-muted-foreground">
                      {a.branch_name ? `Sucursal: ${a.branch_name}` : "Para toda la empresa"}
                      {" · "}
                      {new Date(a.created_at).toLocaleDateString("es-PE", {
                        day: "numeric",
                        month: "short",
                        year: "numeric",
                      })}
                    </p>
                  </div>
                </div>
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
};

export default ElegirSucursal;
