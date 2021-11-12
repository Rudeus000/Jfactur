<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solutions - Gastos</title>
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    </head>


    <body>

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
                                    <h4 class="page-title float-left"><i class="fas fa-cube"></i> Gastos administrativos</h4>
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="#">Gastos</a></li>
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
                                    <div class="card-body"> 
                                   <fieldset>
                  <legend>Filtro</legend>
                                    <form id="GastosFormBusqueda" autocomplete="off">  
                                      <div class="row">        
                                     
                                            
                                                <div class="col-md-4">
                        <div class="form-group">
                          <label class="control-label">Fecha</label>
                          <div class="input-group">
                            <input type="text" name="desde" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                            <input type="text" name="hasta" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
                          </div>
                        </div>
                      </div>

                                           
                                          

                                             <div class="form-group col-md-6">
                                                     <div class="form-group">
                                                        <label class="control-label " >Nombre gastos:</label>
                                                        
                                                        <div class="input-group">
                                                          <input type="text"  name="tb_gastos" class="form-control">
                                                 
                                                    </div>
                                                </div>
                                              </div>
                                                
                                                <div class="form-group col-md-4">
                                                    <label  class="col-form-label">Tipo gastos:</label>
                                                    <select class="form-control  select2 select2-hidden-accessible" name="tb_tipo_gastos" >
                                                    <option value="">--Todos--</option>
                                                  <?php foreach ($tipogastos as $t): ?>
                                                  <option value="<?= $t->cod_tipgastos ?>"><?= $t->descripcion ?></option>
                                                    <?php endforeach ?>
                                                   </select>
                                                </div> 

                                                 <div class="form-group col-md-3">
                                                    <label  class="col-form-label">Estado:</label>
                                                   <select name="estado" class="form-control">
                                                    <option value="">Todos</option>
                                                    <option value="1">Gastado</option>
                                                    <option value="2">Anulado</option>
                                                  </select>
                                                </div> 

                                                  <div class="row">
                                          <div class="col-md-12">
                                                <button class="btn btn-success waves-effect waves-light"   style="margin-top:34px"><i class="fa fa-search"></i> Buscar</button>
                                                <button type="button" class="btn btn-pink waves-effect waves-light"  data-toggle="modal" data-target="#ModalAgregarGastos"   style="margin-top:34px"><i class="fa fa-plus"></i> Agregar</button>
                                                <a type="button" id="GastosReportePdf" href="#" class="btn btn-info" target="_blank" style="margin-top:34px"><i class="fa fa-print"></i> Imprimir</a>
                                        </div>
                                        
                                    </div>

                                            
                            
                                       
                                            </div>  
                                 

                                    </form>

                                  
                                      </fieldset>
                                        <!-- End #wizard-vertical -->
                                    </div>
                                </div>
                            </div>
                  
                        </div><!-- End row -->  
        
        
                        <!-- Vertical Steps Example -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body table-responsive">
                                   
                                  
                                         
                                                <table id="TableMantenimientoGastos" class="table table-bordered table-condensed" cellspacing="0" width="100%">
                                   
                                                    <thead >
                                                        <tr class="bg-info text-white">
                                                            <th style="text-aling:center;">Secuencia</th>
                                                            <th  style="text-aling:center;">Tipo gastos</th>
                                                            <th  style="text-aling:center;">Descripcion</th>
                                                            <th  style="text-aling:center;">Fecha</th>
                                                            <th  style="text-aling:center;">Observacion</th>
                                                             <th  style="text-aling:center;">Monto</th>
                                                            <th  style="text-aling:center;">Estado</th>
                                                            <th  style="text-aling:center;">Acciones</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                         <tfoot>
                                                             <tr>
                                                                 <th colspan="5" style="text-align:right">Monto gastado:</th>
                                                                 <th ><b><span id="TotalPagosGastos"></span></b></th>
                                                                 <th colspan="2"></th>                                                                
                                                             </tr>
                                                         </tfoot>
                                                                                 
                                                </table>
                                        <!-- End #wizard-vertical -->
                                    </div>
                                </div>
                            </div>              
                     </div>
                    </div> <!-- container -->         
                </div> <!-- content -->
           
            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->


      

       


    </body>

      <div id="ModalAgregarGastos" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"   aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" >
                 <div class="modal-content">

                    <form id="FormGastos" action="<?= base_url('administrador/regastos/addGastos') ?>" method="post" autocomplete="off">
                                                <input type="hidden" > 
                                                    <div class="modal-header" >
                                                        <h4>Agregar Gastos</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                             <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre:<span class="text-danger"> *</label>
                                                                <input type="text" name="nombre" class="form-control">
                                                            </div>
                                                       </div>

                                                       <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo:<span class="text-danger"> *</label>
                                                               <select class="form-control select input-sm" name="tipogastos" >
                                                                 <option value="">--Selecciona--</option>
                                                                 <?php foreach ($tipogastos as $t): ?>
                                                                  <option value="<?= $t->cod_tipgastos ?>"><?= $t->descripcion ?></option>
                                                                 <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                       </div>

                                                       <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Banco:<span class="text-danger"> *</label>
                                                               <select class="form-control select2 select2-hidden-accessible input-sm" name="banco" >
                                                                 <option value="">--Selecciona--</option>
                                                                 <?php foreach ($banco as $b): ?>
                                                                  <option value="<?= $b->cod_ban ?>"><?= $b->nomb_ban ?></option>
                                                                 <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                       </div>

                                                       <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label class="control-label">Cuenta:</label>
                                                                <input type="text" name="cuenta" class="form-control" >
                                                            </div>
                                                       </div>

                                                
                                                    <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Nro operacion:</label>
                                                                <input type="text" name="operacion" class="form-control" >
                                                            </div>
                                                       </div>

                                                    
                                                    <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="control-label">Persona Gastos:</label>
                                                                <input type="text" name="persona" class="form-control" >
                                                            </div>
                                                    </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Documento:</label>
                                                                <input type="text" name="documentos" class="form-control" >
                                                            </div>
                                                       </div>
                                                  

                                                    

                                                       
                                                       <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Monto:<span class="text-danger"> *</label>
                                                                <input type="text" name="total" class="form-control" >
                                                            </div>
                                                       </div>

                                                      

                                                       <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label class="control-label">Observacion:</label>
                                                                <input type="text" name="observacion" class="form-control">
                                                            </div>
                                                       </div>
              
                                                                                                 
                                                    </div> 
                                              </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                                                    </div>
                                          </form>
                                     </div>
                             </div>
               </div><!-- /.modal -->


       
    
             
                       












         <div id="ModalEditarGastos" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="display: none;" aria-hidden="true">
             <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                                                <form id="FormEditarGastos" action="<?= base_url('administrador/regastos/editGastos') ?>" method="post" autocomplete="off">
                                                <input type="hidden" name="id" > 
                                                    <div class="modal-header">
                                                        <h4 class="custom-modal"  >Editar Gastos</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                             <div class="modal-body">
                                                    <div class="row">
                                                    
                                                    <div class="col-md-7">
                                                            <div class="form-group">
                                                                <label class="control-label">Nombre:<span class="text-danger"> *</label>
                                                                <input type="text" name="nombre" class="form-control">
                                                            </div>
                                                       </div>
                                                   
                                                       <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="control-label">Tipo gastos:<span class="text-danger"> *</label>
                                                               <select class="form-control select select-hidden-accessible input-sm" name="tipogastos" >
                                                                
                                                                 <?php foreach ($tipogastos as $t): ?>
                                                                  <option value="<?= $t->cod_tipgastos ?>"><?= $t->descripcion ?></option>
                                                                 <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                       </div>

                                                          <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Banco:<span class="text-danger"> *</label>
                                                               <select class="form-control select select-hidden-accessible input-sm" name="banco" >
                                                             
                                                                 <?php foreach ($banco as $b): ?>
                                                                  <option value="<?= $b->cod_ban ?>"><?= $b->nomb_ban ?></option>
                                                                 <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                       </div>

                                                       
                                                      <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label class="control-label">Cuenta:</label>
                                                                <input type="text" name="cuenta" class="form-control" >
                                                            </div>
                                                       </div>

                                                      
                                                        

                                                        
                                                      <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label">Nro operacion:</label>
                                                                <input type="text" name="operacion" class="form-control" >
                                                            </div>
                                                       </div>

                                                  
                                                      <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="control-label">Persona Gastos:</label>
                                                                <input type="text" name="persona" class="form-control" >
                                                            </div>
                                                    </div>

                                                     <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Documento:</label>
                                                                <input type="text" name="documento" class="form-control" >
                                                            </div>
                                                       </div>

                                                    

                                                     <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">Monto:<span class="text-danger"> *</label>
                                                                <input type="text" name="total" class="form-control" >
                                                            </div>
                                                       </div>

                                                      

                                                       <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label class="control-label">Observacion:</label>
                                                                <input type="text" name="observacion" class="form-control">
                                                            </div>
                                                       </div>

                                                    

                                                         <div class="col-md-3">
                                                            <div class="form-group">
                                                    <label  class="control-label">Estado</label>
                                                    <select class="form-control select" name="estado" >
                                                       <option value="1" <?php echo set_value('estado',$gastos->est_gastos)==1 ? "selected" : "" ?>>Gastado</option>
                                  <option value="2" <?php echo set_value('estado',$gastos->est_gastos)==2 ? "selected" : "" ?>>Anulado</option>
   
                                                   </select>
                                               </div>
                                                </div> 



                                                    
                                                    </div> 
                                              </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                                                    </div>
                                          </form>
                                     </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
         </div><!-- /.modal -->

    
</html>
<script>
    function soloLetras(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key).toLowerCase();
       letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }

        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }


</script>
