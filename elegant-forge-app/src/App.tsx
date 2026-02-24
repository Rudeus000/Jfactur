import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { AuthProvider } from "@/contexts/AuthContext";
import ProtectedRoute from "@/components/ProtectedRoute";
import RequireSucursal from "@/components/RequireSucursal";
import AppLayout from "@/components/AppLayout";
import Login from "@/pages/Login";
import Dashboard from "@/pages/Dashboard";
import Customers from "@/pages/catalogo/Customers";
import Suppliers from "@/pages/catalogo/Suppliers";
import Categories from "@/pages/catalogo/Categories";
import Products from "@/pages/catalogo/Products";
import Warehouses from "@/pages/inventario/Warehouses";
import Stock from "@/pages/inventario/Stock";
import Kardex from "@/pages/inventario/Kardex";
import Transfers from "@/pages/inventario/Transfers";
import Currencies from "@/pages/contabilidad/Currencies";
import Accounts from "@/pages/contabilidad/Accounts";
import Banks from "@/pages/contabilidad/Banks";
import CashRegisters from "@/pages/contabilidad/CashRegisters";
import CashOpenings from "@/pages/contabilidad/CashOpenings";
import Expenses from "@/pages/contabilidad/Expenses";
import Purchases from "@/pages/compras/Purchases";
import PurchasePayments from "@/pages/compras/PurchasePayments";
import Invoices from "@/pages/facturacion/Invoices";
import Quotes from "@/pages/facturacion/Quotes";
import CustomerPayments from "@/pages/facturacion/CustomerPayments";
import InvoiceSeries from "@/pages/facturacion/InvoiceSeries";
import LibroVentas from "@/pages/reportes/LibroVentas";
import VentasCliente from "@/pages/reportes/VentasCliente";
import VentasProducto from "@/pages/reportes/VentasProducto";
import AgedReceivable from "@/pages/reportes/AgedReceivable";
import AgedPayable from "@/pages/reportes/AgedPayable";
import ComoUsar from "@/pages/ComoUsar";
import PuntoVenta from "@/pages/ventas/PuntoVenta";
import MiEmpresa from "@/pages/config/MiEmpresa";
import NotFound from "@/pages/NotFound";
import ElegirSucursal from "@/pages/ElegirSucursal";

const queryClient = new QueryClient();

const ProtectedLayout: React.FC<{ children: React.ReactNode }> = ({ children }) => (
  <ProtectedRoute>
    <RequireSucursal>
      <AppLayout>{children}</AppLayout>
    </RequireSucursal>
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
            <Route path="/elegir-sucursal" element={<ProtectedRoute><ElegirSucursal /></ProtectedRoute>} />

            <Route path="/" element={<ProtectedLayout><Dashboard /></ProtectedLayout>} />
            <Route path="/como-usar" element={<ProtectedLayout><ComoUsar /></ProtectedLayout>} />
            <Route path="/ventas" element={<ProtectedLayout><PuntoVenta /></ProtectedLayout>} />
            <Route path="/config/mi-empresa" element={<ProtectedLayout><MiEmpresa /></ProtectedLayout>} />

            {/* Catálogo */}
            <Route path="/catalogo/clientes" element={<ProtectedLayout><Customers /></ProtectedLayout>} />
            <Route path="/catalogo/proveedores" element={<ProtectedLayout><Suppliers /></ProtectedLayout>} />
            <Route path="/catalogo/categorias" element={<ProtectedLayout><Categories /></ProtectedLayout>} />
            <Route path="/catalogo/productos" element={<ProtectedLayout><Products /></ProtectedLayout>} />

            {/* Inventario */}
            <Route path="/inventario/almacenes" element={<ProtectedLayout><Warehouses /></ProtectedLayout>} />
            <Route path="/inventario/stock" element={<ProtectedLayout><Stock /></ProtectedLayout>} />
            <Route path="/inventario/kardex" element={<ProtectedLayout><Kardex /></ProtectedLayout>} />
            <Route path="/inventario/traspasos" element={<ProtectedLayout><Transfers /></ProtectedLayout>} />

            {/* Contabilidad */}
            <Route path="/contabilidad/monedas" element={<ProtectedLayout><Currencies /></ProtectedLayout>} />
            <Route path="/contabilidad/cuentas" element={<ProtectedLayout><Accounts /></ProtectedLayout>} />
            <Route path="/contabilidad/bancos" element={<ProtectedLayout><Banks /></ProtectedLayout>} />
            <Route path="/contabilidad/cajas" element={<ProtectedLayout><CashRegisters /></ProtectedLayout>} />
            <Route path="/contabilidad/aperturas" element={<ProtectedLayout><CashOpenings /></ProtectedLayout>} />
            <Route path="/contabilidad/gastos" element={<ProtectedLayout><Expenses /></ProtectedLayout>} />

            {/* Compras */}
            <Route path="/compras/ordenes" element={<ProtectedLayout><Purchases /></ProtectedLayout>} />
            <Route path="/compras/pagos" element={<ProtectedLayout><PurchasePayments /></ProtectedLayout>} />

            {/* Facturación */}
            <Route path="/facturacion/facturas" element={<ProtectedLayout><Invoices /></ProtectedLayout>} />
            <Route path="/facturacion/cotizaciones" element={<ProtectedLayout><Quotes /></ProtectedLayout>} />
            <Route path="/facturacion/cobros" element={<ProtectedLayout><CustomerPayments /></ProtectedLayout>} />
            <Route path="/facturacion/series" element={<ProtectedLayout><InvoiceSeries /></ProtectedLayout>} />

            {/* Reportes */}
            <Route path="/reportes/libro-ventas" element={<ProtectedLayout><LibroVentas /></ProtectedLayout>} />
            <Route path="/reportes/ventas-cliente" element={<ProtectedLayout><VentasCliente /></ProtectedLayout>} />
            <Route path="/reportes/ventas-producto" element={<ProtectedLayout><VentasProducto /></ProtectedLayout>} />
            <Route path="/reportes/cuentas-cobrar" element={<ProtectedLayout><AgedReceivable /></ProtectedLayout>} />
            <Route path="/reportes/cuentas-pagar" element={<ProtectedLayout><AgedPayable /></ProtectedLayout>} />

            <Route path="*" element={<NotFound />} />
          </Routes>
        </AuthProvider>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
