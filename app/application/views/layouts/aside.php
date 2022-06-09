 <!-- ========== Left Sidebar Start ========== -->
 <div class="left side-menu">
  <div class="slimscroll-menu" id="remove-scroll">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
      <!-- Left Menu Start -->
      <ul class="metismenu" id="side-menu">
        <li class="menu-title">Menú de navegación</li>        

        <li>
          <a href="<?php echo base_url();?>reportes/regdashboard"><i class="fas fa-home"></i> <span> Inicio </span> </a>
         
        </li> 
        
<?php if($this->session->userdata('perfil')==1): ?> 
        <li>
          <a href="javascript: void(0);"><i class=" fas fa-clipboard-list"></i> <span class="badge badge-danger float-right">new</span><span> Catalogo </span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
            <li><a href="<?php echo base_url();?>administrador/regcategoria"><i class="ion ion-ios-clipboard"></i>Categorias</a></li>
            <li><a href="<?php echo base_url();?>administrador/regmarca"><i class="ion ion-ios-cube"></i>Marca</a></li>
            <li><a href="<?php echo base_url();?>administrador/regproducto"><i class=" ion ion-ios-cart"></i>Articulos</a></li> 
            <li><a href="<?php echo base_url();?>administrador/regtiparticulo"><i class="ion ion-ios-basket"></i>Tipo articulos</a></li>         
            <li><a href="<?php echo base_url();?>administrador/regunidad"><i class="ion ion-ios-journal"></i>Unidades</a></li>            
          </ul>
        </li>

        <?php endif ?>
<?php if($this->session->userdata('perfil')==1): ?> 
        <li>
          <a href="javascript: void(0);"><i class="fas fa-boxes"></i> <span>Almacén unidades</span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
			<li><a href="<?= base_url()?>administrador/motivorecepcion"><i class="fas fa-dolly"></i>Motivos Recepcion</a></li>  
            <li><a href="<?= base_url()?>administrador/reginventarioinicial"><i class="fas fa-dolly"></i>Inventario Inicial</a></li>  
            <li><a href="<?= base_url() ?>administrador/regtraspasos"><i class="fas fa-exchange-alt"></i> Traspasos</a></li>                                 
            <li><a href="<?php echo base_url();?>administrador/regtipalmacen"><i class="fas fa-hdd"></i>Tipo almacen</a></li>
            <li><a href="<?php echo base_url();?>administrador/regalmacen"><i class="fas fa-dolly-flatbed"></i>Almacenes</a></li>
            <li><a href="<?php echo base_url();?>reportes/kardex/kardexFisico"><i class="fas fa-dolly-flatbed"></i>Kardex Fisico</a></li>
			<li><a href="<?= base_url()?>administrador/Notaunidad"><i class="fas fa-dolly"></i>Boleta almacen</a></li>
			<li><a href="<?= base_url()?>reportes/kardex/CierrekardexFisico"><i class="fas fa-dolly"></i>Cierre mensual</a></li>
          </ul>
        </li>
        <li>
          <a href="javascript: void(0);"><i class="fas fa-boxes"></i> <span>Almacén valorizado</span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
			<li><a href="<?= base_url()?>administrador/motivorecepcion"><i class="fas fa-dolly"></i>Motivos Recepcion</a></li>  
            <li><a href="<?= base_url()?>administrador/reginventarioinicial"><i class="fas fa-dolly"></i>Inventario Inicial</a></li>  
			<li><a href="<?php echo base_url();?>reportes/kardex/kardexValorado"><i class="fas fa-dolly-flatbed"></i>Kardex Valorado</a></li>
			<li><a href="<?= base_url()?>administrador/Notavalorizado"><i class="fas fa-dolly"></i>Nota almacén</a></li> 
			<li><a href="<?= base_url()?>reportes/kardex/CierrekardexValorizado"><i class="fas fa-dolly"></i>Cierre mensual</a></li>
          </ul>
        </li>

<?php endif ?>

<?php if($this->session->userdata('perfil')==1): ?> 
        <li>
          <a href="javascript: void(0);"><i class="fas fa-cubes"></i> <span>Caja</span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
           

            <li><a href="<?php echo base_url();?>administrador/regcaja"><i class="fas fa-donate"></i>Caja</a></li>
            
            <li><a href="<?php echo base_url();?>administrador/regmoneda"><i class="fas fa-dollar-sign"></i>Moneda</a></li>
            <li><a href="<?php echo base_url();?>administrador/regtipcuenta"><i class="fas fa-money-check-alt"></i>Tipo de cuenta</a></li>
            <li><a href="<?php echo base_url();?>administrador/regcuenta"><i class="fas fa-university"></i>Cuentas</a></li>
            

            
          </ul>
        </li>
<?php endif ?>

    



<?php if($this->session->userdata('perfil')==1): ?> 
        <li>
          <a href="javascript: void(0);"><i class="fas fa-shopping-cart"></i> <span> Compras </span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
           
            <li><a href="<?= base_url('administrador/regcompras') ?>"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i>Compras</a></li>
            <li><a href="<?= base_url();?>administrador/regcuentaspagar"><i class="fas fa-dollar-sign"></i>Cuentas por pagar</a></li> 
           
          </ul>
          
          
          
        </li>
<?php endif ?>
        <li>
          <a href="javascript: void(0);"><i class="fas fa-shopping-basket"></i><span class="badge badge-danger float-right">new</span><span> Ventas </span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
            <li><a href="<?= base_url('administrador/regcotizacion') ?>"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i>Cotización</a></li>
            <li><a href="<?= base_url('administrador/regventas') ?>"><i class="far fa-money-bill-alt" aria-hidden="true"></i>Ventas</a></li>
            <li><a href="<?= base_url('administrador/regcuentascobrar') ?>"><i class="fab fa-cc-mastercard" aria-hidden="true"></i>Cuentas por cobrar</a></li>
            <li><a href="<?php echo base_url();?>administrador/regcajaapertura"><i class="fas fa-box-open"></i>Apertura Caja</a></li>
            <li><a href="<?php echo base_url();?>administrador/regcajacierre"><i class="fab fa-expeditedssl"></i>Cierre Caja</a></li>
           
          </ul>
        </li>
<?php if($this->session->userdata('perfil')==1): ?> 
        <li>
          <a href="javascript: void(0);"><i class="ion ion-ios-albums"></i><span>Gestion de doc. elec. </span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
            <li><a href="<?= base_url('administrador/regfacturacion') ?>"><i class="ion ion-ios-paper"></i>Facturas & Boletas</a></li>
            <li><a href="<?= base_url('administrador/regdocumentoelectronico/resumen') ?>"><i class="fas fa-clipboard-list"></i>Resumen Boletas</a></li>
            <li><a href="<?= base_url('administrador/regdocumentoelectronico/bajas') ?>"><i class="fas fa-retweet"></i>Bajas Sunat</a></li>
            <li><a href="<?= base_url('administrador/regdocumentoelectronico/guia') ?>"><i class="fas fa-paste"></i>Guía de Remisión</a></li>
            <li><a href="<?= base_url('administrador/regdocumentoelectronico/debito') ?>"><i class="fas fa-edit"></i>Nota de Débito</a></li>
             <li><a href="<?= base_url('administrador/regdocumentoelectronico/credito') ?>"><i class="fas fa-exchange-alt"></i>Nota de Crédito</a></li>            
           
          </ul>
        </li>
        <?php endif ?>
<?php if($this->session->userdata('perfil')==1): ?> 
        <li>
          <a href="javascript: void(0);"><i class=" fas fa-clipboard-list"></i><span> Reporte Compras </span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">        
            <li><a href="<?= base_url('reportes/regreportpagos') ?>">Compras - Pagos</a></li>
            <li><a href="<?= base_url('reportes/regreportcomprove') ?>">Compras por proveedor</a></li>
            <li><a href="<?= base_url('reportes/regreportcomproduct') ?>">Productos comprados</a></li>
            <li><a href="<?= base_url('reportes/regreportedetallado/Compras') ?>">Compras detalladas</a></li>
           
          </ul>
        </li>
<?php endif ?>
        <li>
          <a href="javascript: void(0);"><i class="fas fa-clipboard"></i><span> Reporte Ventas </span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
           <?php if($this->session->userdata('perfil')==1): ?> 
            <li><a href="<?= base_url('reportes/regreportcotipagos') ?>">Cotizaciones - Pagos</a></li>
            <li><a href="<?= base_url('reportes/regreportcoticlientes') ?>">Cotizaciones x Clientes</a></li>
            <li><a href="<?= base_url('reportes/regreportcotiproductos') ?>">Productos Cotizados</a></li>
              <?php endif ?>
            <li><a href="<?= base_url('reportes/regreportventotal') ?>">Ventas Realizadas</a></li>
            <li><a href="<?= base_url('reportes/regreportedetallado/Ventas') ?>">Ventas detalladas</a></li>
            <?php if($this->session->userdata('perfil')==1): ?> 
            <li><a href="<?= base_url('reportes/regreportventpago') ?>">Ventas Formas de Pago</a></li>
            <li><a href="<?= base_url('reportes/regreportventdetalle') ?>">Ventas Pago</a></li>
            <li><a href="<?= base_url('reportes/regreportventcliente') ?>">Ventas Por Clientes</a></li>
            <li><a href="<?= base_url('reportes/regreportventproducto') ?>">Ventas Por Productos</a></li>
            <li><a href="<?= base_url('reportes/reganancvent') ?>">Ganancia ventas</a></li>            
            <?php endif ?>
           
          </ul>
        </li>
<?php if($this->session->userdata('perfil')==1): ?> 
          <li>
          <a href="javascript: void(0);"><i class=" fas fa-chart-bar"></i><span> Graficos </span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
            <li><a href="<?= base_url('reportes/regventasanio') ?>">Ventas por año</a></li>
            <li><a href="<?= base_url('reportes/regventasanio') ?>">Compras por año</a></li>
           
          </ul>
        </li>
   
   <?php endif ?>

<?php if($this->session->userdata('perfil')==1): ?> 
        <li>
          <a href="javascript: void(0);"><i class="fas fa-users"></i> <span class="badge badge-danger float-right">new</span><span>Gestion de clientes</span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
           

            <li><a href="<?php echo base_url();?>administrador/regcliente"><i class="fas fa-user-tie"></i>Clientes</a></li>
            <li><a href="<?php echo base_url();?>administrador/regclientecobertura"><i class="fas fa-user-tag"></i>Cobertura Cliente</a></li>
            <li><a href="<?php echo base_url();?>administrador/regproveedor"><i class="fas fa-handshake"></i>Proveedores</a></li>
            <li><a href="<?php echo base_url();?>administrador/regtipogastos"><i class="fas fa-hand-holding-usd"></i>Tipo gastos</a></li>
            <li><a href="<?php echo base_url();?>administrador/regastos"><i class="fas fa-user-friends"></i>Gastos</a></li>            

          </ul>
        </li>
<?php endif ?>
<?php if($this->session->userdata('perfil')==1): ?> 
        <li>
          <a href="javascript: void(0);"><i class="fas fa-users-cog"></i> <span>Gestion de usuarios</span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
           


            <li><a href="<?php echo base_url();?>administrador/regrupo"><i class=" ion ion-md-contacts"></i>Grupos</a></li>
            <li><a href="<?php echo base_url();?>administrador/regperfil"><i class="ion ion-md-create"></i>Perfiles</a></li>
            <li><a href="<?php echo base_url();?>administrador/regusuario"><i class="ion ion-md-person"></i>Usuarios</a></li>
            <li><a href="<?php echo base_url();?>administrador/regasignpuntoventa"><i class="ion ion-md-pin"></i>Asig.sucursal</a></li>
            <li><a href="<?php echo base_url();?>administrador/permisos"><i class="fa fa-unlock-alt"></i>Permisos</a></li>
            
          </ul>
        </li>
    <?php endif ?>
<?php if($this->session->userdata('perfil')==1): ?> 
        <li>
                <a href="javascript: void(0);"><i class="ion-md-pin"></i> <span> Gestion de sucursales</span> <span class="menu-arrow"></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                  <li><a href="<?php echo base_url();?>administrador/regsede"><i class="ion ion-md-business"></i>Sede</a></li>
                  <li><a href="<?php echo base_url();?>administrador/regpventa"><i class="fas fa-location-arrow"></i>Sucursales</a></li>                
                 
                </ul>
        </li>
<?php endif ?>
         <?php if($this->session->userdata('perfil')==1): ?> 

        <li>
          <a href="javascript: void(0);"><i class="fab fa-whmcs"></i> <span> Configuracion </span> <span class="menu-arrow"></span></a>
          <ul class="nav-second-level" aria-expanded="false">
                       
            <li><a href="<?php echo base_url();?>empresa/regempresa"><i class="ion ion-md-briefcase"></i>Empresa</a></li>            
            <li><a href="<?php echo base_url();?>administrador/regimpresora"><i class="ion ion-md-print"></i>Impresoras</a></li>
            <li><a href="<?php echo base_url();?>administrador/regtipodocum"><i class="ion ion-md-today"></i>Tipo de documentos</a></li>
            <li><a href="<?php echo base_url();?>administrador/regtalonario"><i class="ion ion-md-cube"></i>Admin. talonario</a></li>
			<li><a href="<?php echo base_url();?>administrador/serie_almacen"><i class="ion ion-md-cube"></i>Serie almacén</a></li>
            <li><a href="<?php echo base_url();?>administrador/regtarjeta"><i class="fab fa-cc-visa"></i>Tarjetas</a></li>
            <li><a href="<?php echo base_url();?>administrador/regbanco"><i class="fas fa-clipboard-list"></i>Banco</a></li>                 
        
            <li><a href="<?php echo base_url();?>administrador/regnuevo"><i class="ion ion-ios-create"></i>Editor wysiwyg</a></li>
           
          </ul>
        </li>

         <?php endif ?>
           <li>
          <a href="javascript: void(0);"><i class="ion ion-md-play-circle"></i><span class="badge badge-danger float-right">new</span> <span> Tutoria y Soporte </span>
          </a>          
          <ul class="nav-second-level" aria-expanded="false">
           
            
            <li><a href="<?php echo base_url();?>tutoria"><i class="ion ion-md-play-circle"></i>Tutorias</a></li>
            
           

          </ul>
        </li>
      </ul>
    </div>
    <!-- Sidebar -->
    <div class="clearfix"></div>

  </div>
  <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->

