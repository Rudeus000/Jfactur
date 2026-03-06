import { useState } from "react";
import { useLocation, Link } from "react-router-dom";
import { useAuth } from "@/contexts/AuthContext";
import {
  LayoutDashboard,
  Users,
  Package,
  Warehouse,
  BookOpen,
  ShoppingCart,
  FileText,
  BarChart3,
  ChevronDown,
  ChevronRight,
  LogOut,
  Building2,
  Menu,
  X,
  Layers,
  DollarSign,
  Truck,
  CreditCard,
  ClipboardList,
  Receipt,
  TrendingUp,
  Banknote,
  HelpCircle,
} from "lucide-react";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";

interface NavItem {
  label: string;
  icon: React.ElementType;
  path?: string;
  children?: { label: string; path: string; icon: React.ElementType }[];
}

const navigation: NavItem[] = [
  { label: "Dashboard", icon: LayoutDashboard, path: "/" },
  { label: "Punto de venta", icon: Receipt, path: "/ventas" },
  { label: "Cómo usar el sistema", icon: HelpCircle, path: "/como-usar" },
  {
    label: "Catálogo",
    icon: Package,
    children: [
      { label: "Clientes", path: "/catalogo/clientes", icon: Users },
      { label: "Proveedores", path: "/catalogo/proveedores", icon: Truck },
      { label: "Categorías", path: "/catalogo/categorias", icon: Layers },
      { label: "Productos", path: "/catalogo/productos", icon: Package },
    ],
  },
  {
    label: "Inventario",
    icon: Warehouse,
    children: [
      { label: "Almacenes", path: "/inventario/almacenes", icon: Warehouse },
      { label: "Stock", path: "/inventario/stock", icon: ClipboardList },
      { label: "Kardex", path: "/inventario/kardex", icon: BookOpen },
      { label: "Traspasos", path: "/inventario/traspasos", icon: Layers },
    ],
  },
  {
    label: "Contabilidad",
    icon: BookOpen,
    children: [
      { label: "Monedas", path: "/contabilidad/monedas", icon: DollarSign },
      { label: "Plan de Cuentas", path: "/contabilidad/cuentas", icon: BookOpen },
      { label: "Bancos", path: "/contabilidad/bancos", icon: Banknote },
      { label: "Cajas", path: "/contabilidad/cajas", icon: CreditCard },
      { label: "Aperturas de caja", path: "/contabilidad/aperturas", icon: Receipt },
      { label: "Tipos de Gasto", path: "/contabilidad/tipos-gasto", icon: Layers },
      { label: "Gastos", path: "/contabilidad/gastos", icon: Receipt },
    ],
  },
  {
    label: "Compras",
    icon: ShoppingCart,
    children: [
      { label: "Órdenes de Compra", path: "/compras/ordenes", icon: ShoppingCart },
      { label: "Pagos a Proveedores", path: "/compras/pagos", icon: CreditCard },
    ],
  },
  {
    label: "Facturación",
    icon: FileText,
    children: [
      { label: "Facturas / Boletas", path: "/facturacion/facturas", icon: FileText },
      { label: "Cotizaciones", path: "/facturacion/cotizaciones", icon: ClipboardList },
      { label: "Cobros", path: "/facturacion/cobros", icon: DollarSign },
      { label: "Series", path: "/facturacion/series", icon: Layers },
    ],
  },
  {
    label: "Reportes",
    icon: BarChart3,
    children: [
      { label: "Libro de Ventas", path: "/reportes/libro-ventas", icon: BookOpen },
      { label: "Ventas por Cliente", path: "/reportes/ventas-cliente", icon: Users },
      { label: "Ventas por Producto", path: "/reportes/ventas-producto", icon: Package },
      { label: "Cuentas por Cobrar", path: "/reportes/cuentas-cobrar", icon: TrendingUp },
      { label: "Cuentas por Pagar", path: "/reportes/cuentas-pagar", icon: Receipt },
    ],
  },
  { label: "Mi empresa", icon: Building2, path: "/config/mi-empresa" },
];

const AppSidebar = () => {
  const [collapsed, setCollapsed] = useState(false);
  const [openGroups, setOpenGroups] = useState<string[]>(["Catálogo"]);
  const [mobileOpen, setMobileOpen] = useState(false);
  const location = useLocation();
  const { user, logout } = useAuth();

  const toggleGroup = (label: string) => {
    setOpenGroups((prev) =>
      prev.includes(label) ? prev.filter((g) => g !== label) : [...prev, label]
    );
  };

  const isActive = (path: string) => location.pathname === path;
  const isGroupActive = (item: NavItem) =>
    item.children?.some((c) => location.pathname === c.path) ?? false;

  const sidebarContent = (
    <div className="flex flex-col h-full bg-sidebar text-sidebar-foreground">
      {/* Header */}
      <div className="flex items-center gap-3 px-4 h-14 border-b border-sidebar-border shrink-0">
        <div className="flex items-center justify-center h-9 w-9 rounded-lg bg-sidebar-accent/50 shrink-0">
          <Building2 className="h-5 w-5 text-sidebar-primary" />
        </div>
        {!collapsed && (
          <span className="font-semibold text-sidebar-primary tracking-tight text-lg">Jfactur</span>
        )}
        <button
          onClick={() => {
            setCollapsed(!collapsed);
            setMobileOpen(false);
          }}
          className="ml-auto p-1 rounded hover:bg-sidebar-accent text-sidebar-muted hidden lg:block"
        >
          <Menu className="h-4 w-4" />
        </button>
        <button
          onClick={() => setMobileOpen(false)}
          className="ml-auto p-1 rounded hover:bg-sidebar-accent text-sidebar-muted lg:hidden"
        >
          <X className="h-4 w-4" />
        </button>
      </div>

      {/* Navigation */}
      <nav className="flex-1 overflow-y-auto py-2 px-2 space-y-0.5">
        {navigation.map((item) => {
          if (item.path) {
            return (
              <Link
                key={item.label}
                to={item.path}
                onClick={() => setMobileOpen(false)}
                className={cn(
                  "flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors",
                  isActive(item.path)
                    ? "bg-sidebar-accent text-sidebar-primary"
                    : "text-sidebar-foreground hover:bg-sidebar-accent/80 hover:text-sidebar-accent-foreground"
                )}
              >
                <item.icon className="h-4 w-4 shrink-0" />
                {!collapsed && <span>{item.label}</span>}
              </Link>
            );
          }

          const groupOpen = openGroups.includes(item.label) || isGroupActive(item);

          return (
            <div key={item.label}>
              <button
                onClick={() => !collapsed && toggleGroup(item.label)}
                className={cn(
                  "flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium w-full transition-colors",
                  isGroupActive(item)
                    ? "text-sidebar-primary"
                    : "text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                )}
              >
                <item.icon className="h-4 w-4 shrink-0" />
                {!collapsed && (
                  <>
                    <span className="flex-1 text-left">{item.label}</span>
                    {groupOpen ? (
                      <ChevronDown className="h-3.5 w-3.5 text-sidebar-muted" />
                    ) : (
                      <ChevronRight className="h-3.5 w-3.5 text-sidebar-muted" />
                    )}
                  </>
                )}
              </button>
              {!collapsed && groupOpen && item.children && (
                <div className="ml-4 mt-0.5 space-y-0.5 border-l border-sidebar-border pl-3">
                  {item.children.map((child) => (
                    <Link
                      key={child.path}
                      to={child.path}
                      onClick={() => setMobileOpen(false)}
                      className={cn(
                        "flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-sm transition-colors",
                        isActive(child.path)
                          ? "bg-sidebar-accent text-sidebar-primary font-medium"
                          : "text-sidebar-foreground hover:bg-sidebar-accent/80 hover:text-sidebar-accent-foreground"
                      )}
                    >
                      <child.icon className="h-3.5 w-3.5 shrink-0" />
                      <span>{child.label}</span>
                    </Link>
                  ))}
                </div>
              )}
            </div>
          );
        })}
      </nav>

      {/* User footer */}
      <div className="border-t border-sidebar-border p-3 shrink-0">
        {!collapsed ? (
          <div className="flex items-center gap-3">
            <div className="h-8 w-8 rounded-full bg-sidebar-accent flex items-center justify-center text-xs font-semibold text-sidebar-primary shrink-0">
              {user?.first_name?.[0] || user?.email?.[0]?.toUpperCase() || "U"}
            </div>
            <div className="flex-1 min-w-0">
              <p className="text-sm font-medium text-sidebar-primary truncate">
                {user?.first_name ? `${user.first_name} ${user.last_name}` : user?.email || "Usuario"}
              </p>
              {user?.role && (
                <p className="text-xs text-sidebar-muted truncate">{user.role}</p>
              )}
            </div>
            <button onClick={logout} className="p-1.5 rounded hover:bg-sidebar-accent text-sidebar-muted" title="Cerrar sesión">
              <LogOut className="h-4 w-4" />
            </button>
          </div>
        ) : (
          <button onClick={logout} className="w-full flex justify-center p-1.5 rounded hover:bg-sidebar-accent text-sidebar-muted" title="Cerrar sesión">
            <LogOut className="h-4 w-4" />
          </button>
        )}
      </div>
    </div>
  );

  return (
    <>
      {/* Mobile trigger */}
      <Button
        variant="ghost"
        size="icon"
        className="fixed top-3 left-3 z-50 lg:hidden"
        onClick={() => setMobileOpen(true)}
      >
        <Menu className="h-5 w-5" />
      </Button>

      {/* Mobile overlay */}
      {mobileOpen && (
        <div className="fixed inset-0 bg-black/50 z-40 lg:hidden" onClick={() => setMobileOpen(false)} />
      )}

      {/* Sidebar */}
      <aside
        className={cn(
          "fixed lg:sticky top-0 left-0 h-screen z-40 transition-all duration-200 shrink-0",
          collapsed ? "w-16" : "w-60",
          mobileOpen ? "translate-x-0" : "-translate-x-full lg:translate-x-0"
        )}
      >
        {sidebarContent}
      </aside>
    </>
  );
};

export default AppSidebar;
