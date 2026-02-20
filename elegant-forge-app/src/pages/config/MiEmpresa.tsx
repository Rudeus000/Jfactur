import { useState, useEffect } from "react";
import api, { getErrorMessage } from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { useToast } from "@/hooks/use-toast";

interface Company {
  id: string;
  ruc: string;
  razon_social: string;
  nombre_comercial: string;
  domicilio_fiscal: string;
  logo_url: string;
  primary_color: string;
  secondary_color: string;
}

const MiEmpresa = () => {
  const { user } = useAuth();
  const [company, setCompany] = useState<Company | null>(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [form, setForm] = useState({
    logo_url: "",
    nombre_comercial: "",
    domicilio_fiscal: "",
    primary_color: "",
    secondary_color: "",
  });
  const { toast } = useToast();

  useEffect(() => {
    const companyId = user?.company?.id;
    if (!companyId) {
      setLoading(false);
      return;
    }
    api.get<Company>(`/companies/${companyId}/`).then(({ data }) => {
      setCompany(data);
      setForm({
        logo_url: data.logo_url || "",
        nombre_comercial: data.nombre_comercial || "",
        domicilio_fiscal: data.domicilio_fiscal || "",
        primary_color: data.primary_color || "",
        secondary_color: data.secondary_color || "",
      });
    }).catch(() => toast({ title: "Error al cargar empresa", variant: "destructive" })).finally(() => setLoading(false));
  }, [user?.company?.id, toast]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!company) return;
    setSaving(true);
    try {
      await api.patch(`/companies/${company.id}/`, {
        logo_url: form.logo_url.trim() || undefined,
        nombre_comercial: form.nombre_comercial.trim() || undefined,
        domicilio_fiscal: form.domicilio_fiscal.trim() || undefined,
        primary_color: form.primary_color.trim() || undefined,
        secondary_color: form.secondary_color.trim() || undefined,
      });
      toast({ title: "Datos de empresa actualizados. El logo aparecerá en tickets y facturas." });
    } catch (e) {
      toast({ title: "Error", description: getErrorMessage(e), variant: "destructive" });
    } finally {
      setSaving(false);
    }
  };

  if (loading || !company) {
    return (
      <div className="erp-page-header">
        <h1 className="erp-page-title">Mi empresa</h1>
        <p className="erp-page-subtitle">{loading ? "Cargando…" : "No tiene empresa asignada."}</p>
        {loading && (
          <div className="mt-6 flex items-center gap-3 text-muted-foreground">
            <div className="h-5 w-5 animate-spin rounded-full border-2 border-primary border-t-transparent" />
            <span className="text-sm">Cargando datos…</span>
          </div>
        )}
      </div>
    );
  }

  return (
    <div className="max-w-2xl">
      <div className="erp-page-header mb-6">
        <h1 className="erp-page-title">Mi empresa</h1>
        <p className="erp-page-subtitle">
          Logo y datos que aparecen en tickets, volantes y facturas. Formato según SUNAT.
        </p>
      </div>
      <Card>
        <CardHeader>
          <CardTitle>Datos de presentación</CardTitle>
          <CardDescription>
            RUC y razón social no se modifican aquí. Configure el logo (URL de imagen) y el nombre comercial para que figuren en los comprobantes.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="grid gap-2">
              <Label>Logo (URL de la imagen)</Label>
              <Input
                value={form.logo_url}
                onChange={(e) => setForm((f) => ({ ...f, logo_url: e.target.value }))}
                placeholder="https://ejemplo.com/logo.png"
              />
              {form.logo_url && (
                <div className="mt-2 p-2 border rounded flex items-center gap-2">
                  <img src={form.logo_url} alt="Vista previa" className="max-h-16 max-w-32 object-contain" onError={(e) => (e.currentTarget.style.display = "none")} />
                  <span className="text-sm text-muted-foreground">Vista previa (aparecerá en tickets y facturas)</span>
                </div>
              )}
            </div>
            <div className="grid gap-2">
              <Label>Nombre comercial</Label>
              <Input
                value={form.nombre_comercial}
                onChange={(e) => setForm((f) => ({ ...f, nombre_comercial: e.target.value }))}
                placeholder="Nombre con el que aparece en comprobantes"
              />
            </div>
            <div className="grid gap-2">
              <Label>Domicilio fiscal</Label>
              <Input
                value={form.domicilio_fiscal}
                onChange={(e) => setForm((f) => ({ ...f, domicilio_fiscal: e.target.value }))}
                placeholder="Dirección en el comprobante"
              />
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div className="grid gap-2">
                <Label>Color primario (hex)</Label>
                <Input
                  value={form.primary_color}
                  onChange={(e) => setForm((f) => ({ ...f, primary_color: e.target.value }))}
                  placeholder="#000000"
                />
              </div>
              <div className="grid gap-2">
                <Label>Color secundario (hex)</Label>
                <Input
                  value={form.secondary_color}
                  onChange={(e) => setForm((f) => ({ ...f, secondary_color: e.target.value }))}
                  placeholder="#666666"
                />
              </div>
            </div>
            <Button type="submit" disabled={saving}>
              {saving ? "Guardando…" : "Guardar"}
            </Button>
          </form>
        </CardContent>
      </Card>
      <p className="text-sm text-muted-foreground mt-4">
        El comprobante electrónico se envía a SUNAT en formato UBL 2.1. La representación impresa (ticket/volante) incluye estos datos y el logo para que el cliente tenga una copia legible.
      </p>
    </div>
  );
};

export default MiEmpresa;
