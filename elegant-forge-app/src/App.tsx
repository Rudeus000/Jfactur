import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { AuthProvider } from "@/contexts/AuthContext";
import ProtectedRoute from "@/components/ProtectedRoute";
import AppLayout from "@/components/AppLayout";
import Login from "@/pages/Login";
import Dashboard from "@/pages/Dashboard";
import Customers from "@/pages/catalogo/Customers";
import Products from "@/pages/catalogo/Products";
import Invoices from "@/pages/facturacion/Invoices";
import Stock from "@/pages/inventario/Stock";
import PlaceholderPage from "@/pages/PlaceholderPage";
import NotFound from "@/pages/NotFound";

const queryClient = new QueryClient();

const ProtectedLayout: React.FC<{ children: React.ReactNode }> = ({ children }) => (
  <ProtectedRoute>
    <AppLayout>{children}</AppLayout>
  </ProtectedRoute>
);

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <AuthProvider>
          <Routes>
            <Route path="/login" element={<Login />} />

            {/* Protected routes */}
            <Route path="/" element={<ProtectedLayout><Dashboard /></ProtectedLayout>} />

            {/* Catálogo */}
            <Route path="/catalogo/clientes" element={<ProtectedLayout><Customers /></ProtectedLayout>} />
            <Route path="/catalogo/proveedores" element={<ProtectedLayout><PlaceholderPage title="Proveedores" subtitle="Directorio de proveedores" /></ProtectedLayout>} />
            <Route path="/catalogo/categorias" element={<ProtectedLayout><PlaceholderPage title="Categorías" subtitle="Categorías de productos" /></ProtectedLayout>} />
            <Route path="/catalogo/productos" element={<ProtectedLayout><Products /></ProtectedLayout>} />

            {/* Inventario */}
            <Route path="/inventario/almacenes" element={<ProtectedLayout><PlaceholderPage title="Almacenes" subtitle="Gestión de almacenes" /></ProtectedLayout>} />
            <Route path="/inventario/stock" element={<ProtectedLayout><Stock /></ProtectedLayout>} />
            <Route path="/inventario/kardex" element={<ProtectedLayout><PlaceholderPage title="Kardex" subtitle="Movimientos de stock" /></ProtectedLayout>} />
            <Route path="/inventario/traspasos" element={<ProtectedLayout><PlaceholderPage title="Traspasos" subtitle="Transferencias entre almacenes" /></ProtectedLayout>} />

            {/* Contabilidad */}
            <Route path="/contabilidad/monedas" element={<ProtectedLayout><PlaceholderPage title="Monedas" subtitle="Configuración de monedas" /></ProtectedLayout>} />
            <Route path="/contabilidad/cuentas" element={<ProtectedLayout><PlaceholderPage title="Plan de Cuentas" subtitle="Tipos de cuenta y cuentas contables" /></ProtectedLayout>} />
            <Route path="/contabilidad/bancos" element={<ProtectedLayout><PlaceholderPage title="Bancos" subtitle="Gestión de bancos" /></ProtectedLayout>} />
            <Route path="/contabilidad/cajas" element={<ProtectedLayout><PlaceholderPage title="Cajas" subtitle="Cajas registradoras y aperturas/cierres" /></ProtectedLayout>} />
            <Route path="/contabilidad/gastos" element={<ProtectedLayout><PlaceholderPage title="Gastos" subtitle="Tipos de gasto y registro" /></ProtectedLayout>} />

            {/* Compras */}
            <Route path="/compras/ordenes" element={<ProtectedLayout><PlaceholderPage title="Órdenes de Compra" subtitle="Gestión de compras" /></ProtectedLayout>} />
            <Route path="/compras/pagos" element={<ProtectedLayout><PlaceholderPage title="Pagos a Proveedores" subtitle="Registro de pagos" /></ProtectedLayout>} />

            {/* Facturación */}
            <Route path="/facturacion/facturas" element={<ProtectedLayout><Invoices /></ProtectedLayout>} />
            <Route path="/facturacion/cotizaciones" element={<ProtectedLayout><PlaceholderPage title="Cotizaciones" subtitle="Gestión de cotizaciones" /></ProtectedLayout>} />
            <Route path="/facturacion/cobros" element={<ProtectedLayout><PlaceholderPage title="Cobros" subtitle="Cobros de clientes" /></ProtectedLayout>} />
            <Route path="/facturacion/series" element={<ProtectedLayout><PlaceholderPage title="Series" subtitle="Series de facturación" /></ProtectedLayout>} />

            {/* Reportes */}
            <Route path="/reportes/libro-ventas" element={<ProtectedLayout><PlaceholderPage title="Libro de Ventas" subtitle="Reporte de ventas por periodo" /></ProtectedLayout>} />
            <Route path="/reportes/ventas-cliente" element={<ProtectedLayout><PlaceholderPage title="Ventas por Cliente" subtitle="Análisis de ventas por cliente" /></ProtectedLayout>} />
            <Route path="/reportes/ventas-producto" element={<ProtectedLayout><PlaceholderPage title="Ventas por Producto" subtitle="Análisis de ventas por producto" /></ProtectedLayout>} />
            <Route path="/reportes/cuentas-cobrar" element={<ProtectedLayout><PlaceholderPage title="Cuentas por Cobrar" subtitle="Aging receivable report" /></ProtectedLayout>} />
            <Route path="/reportes/cuentas-pagar" element={<ProtectedLayout><PlaceholderPage title="Cuentas por Pagar" subtitle="Aging payable report" /></ProtectedLayout>} />

            <Route path="*" element={<NotFound />} />
          </Routes>
        </AuthProvider>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
