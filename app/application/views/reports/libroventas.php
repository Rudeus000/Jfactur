<!-- Begin page -->
<div id="wrapper">
   <!-- ============================================================== -->
   <!-- Start right Content here -->
   <!-- ============================================================== -->
   <div class="content-page">
      <!-- Start content -->
      <div class="content">
         <div class="container-fluid">
            <div class="row">
               <div class="col-12">
                  <div class="page-title-box">
                     <h4 class="page-title float-left"><i class="far fa-money-bill-alt" aria-hidden="true"></i> LIBRO ELECTRONICO DE VENTAS</h4>
                     <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="#">Utilidad</a></li>
                        <li class="breadcrumb-item active">Listado</li>
                     </ol>
                  </div>
               </div>
            </div>
            
            <!-- end row -->
            <!-- Vertical Steps Example -->
            <div class="row">
               <div class="col-sm-12">
                  <div class="card">
                     <div class="card-body table-responsive">
                        <fieldset>
                           <legend>Filtro</legend>
                           <form id="FormLibroElectronicoVentas" action="" method="post" autocomplete="off">
                              <div class="row">
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <label>Desde</label>
                                       <div>
                                          <div class="input-group">
                                             <input type="text" name="desde" class="form-control datepicker" value="2023-01-01">
                                             <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                             </div>
                                          </div>
                                          <!-- input-group -->
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <label>Hasta</label>
                                       <div>
                                          <div class="input-group">
                                             <input type="text" name="hasta" class="form-control datepicker" value="2023-01-28" >
                                             <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                             </div>
                                          </div>
                                          <!-- input-group -->
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <label class="control-label">Tipo comprobante</label>
                                       <select name="tipo_comprobante" class="form-control select2">
                                          <option value="">--Todos--</option>
                                          <option value="01">Facturas</option>
                                          <option value="06">Boletas</option>
                                          <option value="07">Nota de crédito</option>
                                          <option value="08">Nota de débito</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-2 form-group">
                                    <button type="submit" class="btn btn-primary btn-md" style="margin-top:27px">Generar</button>
                                 </div>
                                 
                              </div>
                           </form>
                        </fieldset>
                        

                        <div class="row">
                           <div class="col-md-12 my-4">
                              <div class="dropdown">
                                 <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                    Opciones
                                 </button>
                                 <div class="dropdown-menu">
                                    <a id="btn-generar-txt-sunat" class="dropdown-item" download>GENERAR TXT SUNAT</a>
                                    <a id="btn-generar-xlsx-sunat" class="dropdown-item">FORMATO SUNAT - EXCEL</a>
                                    <a id="btn-generar-ejb" class="dropdown-item" href="#">FORMATO EJB - EXCEL</a>
                                 </div>
                              </div>
                           </div>
                        </div>


                        <table id="TableLibroVentas" class="table table-bordered table-condensed"  cellspacing="0" width="100%">
                          <thead>
                            <tr class="btn-primary btn-xs">
                              <th style="text-align: center">PERIODO</th>
                              <th style="text-align: center">COD_UNIC</th>
                              <th style="text-align: center">TIPO_REGIMEN</th>
                              <th style="text-align: center">F_EMISION</th>
                              <th style="text-align: center">F_VENCIMIENTO</th>
                              <th style="text-align: center">TIPO_DOCUMENTO</th>
                              <th style="text-align: center">SERIE</th>
                              <th style="text-align: center">NUMERO</th>
                              <th style="text-align: center">NUM_MAQ_REG</th>
                              <th style="text-align: center">T_DOC</th>
                              <th style="text-align: center">NUMERO_CLIENTE</th>
                              <th style="text-align: center">RAZON_SOCIAL</th>
                              <th style="text-align: center">OP_EXPORT</th>
                              <th style="text-align: center">OP_GRAVADA</th>
                              <th style="text-align: center">DESCUENTO</th>
                              <th style="text-align: center">IGV</th>
                              <th style="text-align: center">DESC_IGV</th>
                              <th style="text-align: center">OP_EXONERADA</th>
                              <th style="text-align: center">OP_INAFECTA</th>
                              <th style="text-align: center">ISC</th>
                              <th style="text-align: center">OP_ARROZ_P</th>
                              <th style="text-align: center">IMP_ARROZ_OP</th>
                              <th style="text-align: center">ICB_PER</th>
                              <th style="text-align: center">OTROS_TRIBUTOS</th>
                              <th style="text-align: center">TOTAL</th>
                              <th style="text-align: center">MONEDA</th>
                              <th style="text-align: center">T_C</th>
                              <th style="text-align: center">FECHA_COM_MODIF</th>
                              <th style="text-align: center">TIPO_DOC_MODIF</th>
                              <th style="text-align: center">SERIE_DOC_MODIF</th>
                              <th style="text-align: center">NUM_DOC_MODIF</th>
                              <th style="text-align: center">ID_CONTR</th>
                              <th style="text-align: center">ERR_T_C</th>
                              <th style="text-align: center">COMP_M_P</th>
                              <th style="text-align: center">ESTADO</th>
                              <th style="text-align: center">CAMP_LIB</th>
                              <th style="text-align: center">ESTADO_COMP</th>
                            </tr>
                          </thead>
                           
                        </table>
                        <!-- End #wizard-vertical -->

                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- container -->         
      </div>
      <!-- content -->
   </div>
   <!-- ============================================================== -->
   <!-- End Right content here -->
   <!-- ============================================================== -->
</div>
<!-- END wrapper -->