import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "@/contexts/AuthContext";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Building2, Lock, Mail, AlertCircle } from "lucide-react";

const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setIsLoading(true);
    try {
      await login(email, password);
      navigate("/");
    } catch (err) {
      setError(err instanceof Error ? err.message : "Error al iniciar sesión");
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex">
      {/* Left panel */}
      <div className="hidden lg:flex lg:w-1/2 bg-primary items-center justify-center p-12">
        <div className="max-w-md text-primary-foreground">
          <div className="flex items-center gap-3 mb-8">
            <Building2 className="h-10 w-10" />
            <h1 className="text-3xl font-bold tracking-tight">Jfactur</h1>
          </div>
          <p className="text-lg opacity-90 leading-relaxed">
            Sistema integral de gestión empresarial. Controle sus operaciones de facturación, inventario, contabilidad y más desde un solo lugar.
          </p>
          <div className="mt-12 grid grid-cols-2 gap-4 text-sm opacity-75">
            {["Facturación SUNAT", "Control de inventario", "Contabilidad integrada", "Reportes en tiempo real"].map((f) => (
              <div key={f} className="flex items-center gap-2">
                <div className="h-1.5 w-1.5 rounded-full bg-primary-foreground" />
                {f}
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Right panel */}
      <div className="flex-1 flex items-center justify-center p-8 bg-background">
        <div className="w-full max-w-sm">
          <div className="lg:hidden flex items-center gap-2 mb-8 text-primary">
            <Building2 className="h-8 w-8" />
            <span className="text-2xl font-bold tracking-tight">Jfactur</span>
          </div>

          <div className="mb-8">
            <h2 className="text-2xl font-semibold tracking-tight">Iniciar sesión</h2>
            <p className="text-muted-foreground text-sm mt-1">Ingrese sus credenciales para acceder al sistema</p>
          </div>

          {error && (
            <div className="mb-4 flex items-center gap-2 rounded-md bg-destructive/10 border border-destructive/20 px-3 py-2.5 text-sm text-destructive animate-fade-in">
              <AlertCircle className="h-4 w-4 shrink-0" />
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="email">Correo electrónico</Label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input
                  id="email"
                  type="email"
                  placeholder="usuario@empresa.com"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className="pl-10"
                  required
                  autoComplete="email"
                />
              </div>
            </div>

            <div className="space-y-2">
              <Label htmlFor="password">Contraseña</Label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input
                  id="password"
                  type="password"
                  placeholder="••••••••"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className="pl-10"
                  required
                  autoComplete="current-password"
                />
              </div>
            </div>

            <Button type="submit" className="w-full" disabled={isLoading}>
              {isLoading ? "Ingresando…" : "Ingresar"}
            </Button>
          </form>

          <p className="mt-8 text-center text-xs text-muted-foreground">
            Sistema de gestión empresarial &copy; {new Date().getFullYear()}
          </p>
        </div>
      </div>
    </div>
  );
};

export default Login;
