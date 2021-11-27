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
            <!-- <div class="page-title-box">
              <h4 class="page-title float-left"><i class="fas fa-cart-arrow-down" aria-hidden="true"></i> Resumen de Boletas</h4> -->
              <ol class="breadcrumb float-right">

                <li class="breadcrumb-item"><a href="#">Resumen de Boletas</a></li>
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
            <div class="card-header bg-success"><h3 class="my-0 text-white">Resumen diario de boletas<a class="btn btn-rounded btn-pink float-right" id="modal-resumen" ><i class="fa fa-plus m-r-5"></i>Agregar</a></h3></div>
              <div class="card-body">
                <!-- <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button id="modal-resumen" type="button" class="btn btn-pink"><i class="fa fa-plus"></i> Agregar</button>
                    </div>
                  </div>
                </div> -->

                <div class="table-responsive">
                  <table id="TableResumen" class="table mb-0 table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr class="bg-success text-white">
                        <th>Fecha</th>
                        <th>Código</th>                      
                        <th>Serie</th>
                        <th>Secuencia</th>
                        <th>Ver Boletas</th>
                        <th>Ticket</th>
												<th style="width: 10px;">XML</th>
                        <th style="width: 10px;">CDR</th>
                        <th style="text-aling:center;">Sunat</th>
                      </tr>
                    </thead>
										<tbody>
                    <?php foreach($datos as $d): ?>
											<tr>
												<td><?= $d->fechadocumento_res ?></td>
												<td><?= $d->codigo_res ?></td>
												<td><?= $d->serie_res ?></td>
                        <td><?= $d->secuencia_res ?></td>
                        <td><button data-id="<?= $d->cod_res ?>" class="btn btn-rounded btn-bordered btn-purple btn-sm ver-boletas"><span class="fas fa-eye"></span> Ver Boletas</button></td>
                        <td><?= $d->ticket_res ?></td>
												<td style="width: 10px;">
													<a href="<?= base_url_app('facturacion/'.$d->rutaxml_res.'/'.$d->archivoxml_res.'.XML') ?>" target="_blank" class="fas fa-file-excel text-primary fa-2x"></a>
												</td>
                        <td style="width: 10px;">
                        <?php 
                          if(trim($d->RutaCdrXML)!=""){
                          ?>
                        <a href="<?= base_url_app('facturacion/'.$d->rutaxml_res.'/R-'.$d->archivoxml_res.'.XML') ?>" target="_blank" class="fas fa-file-code text-success fa-2x"></a>
                        <?php                         
                          }
                          else{
                            ?>
                            <span style="cursor:pointer;" id="td<?php echo $d->cod_res;?>"><a href="javascript:ConsultarEstado('<?php echo $d->ticket_res;?>','<?php echo $d->cod_res;?>','<?php echo $d->archivoxml_res;?>');" class="btn btn-info btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                              <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                            </svg>
                          </a>
                          </span> 
                              <?php
                          }
                          ?>
                        </td>                    
                        <!-- <td>
                          <span><a href="<?= base_url_app('facturacion/'.$d->rutaxml_res.'/'.$d->archivoxml_res.'.XML') ?>" target="_blank" class="btn btn-info btn-sm">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-code" viewBox="0 0 16 16">
                            <path d="M6.646 5.646a.5.5 0 1 1 .708.708L5.707 8l1.647 1.646a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2zm2.708 0a.5.5 0 1 0-.708.708L10.293 8 8.646 9.646a.5.5 0 0 0 .708.708l2-2a.5.5 0 0 0 0-.708l-2-2z"/>
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                          </svg>
                          </a>
                          </span>
                          <?php 
                          if(trim($d->RutaCdrXML)!=""){
                          ?>
                          <span style="cursor:pointer;" id="td<?php echo $d->cod_res;?>"><a href="<?= base_url_app('facturacion/'.$d->RutaCdrXML) ?>" target="_blank" class="btn btn-info btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-download" viewBox="0 0 16 16">
                              <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                              <path d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                            </svg>
                          </a>
                          </span>
                          <?php                         
                          }
                          else{
                            ?>
                          <span style="cursor:pointer;" id="td<?php echo $d->cod_res;?>"><a href="javascript:ConsultarEstado('<?php echo $d->ticket_res;?>','<?php echo $d->cod_res;?>','<?php echo $d->archivoxml_res;?>');" class="btn btn-info btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                              <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                            </svg>
                          </a>
                          </span>   
                            <?php
                          }
                          ?>
                        </td> -->
                        <td align="center">
						
						<?php 
						if(trim($d->CodRptaSunat)!=""){
							if((int)($d->CodRptaSunat)==0){
							?>
							<!-- <strong style="color:green">Valido</strong> -->
              <span class="label label-primary">Aceptado</span>
							<?php													
							}else{
							?>
							<!-- <strong style="color:red">Rechazado</strong> -->
              <span class="label label-danger">Rechazado</span>
							<?php
							}
						}else{
							?>
							<span class="label label-warning">Sin respuesta</span>
							<?php													
						}
						?>
		
                        </td>
											</tr>
											<?php endforeach ?>
										</tbody>
                  </table>

                </div>
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



<div class="modal fade" id="ModalAgregarResumen" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="FormResumenFecha" action="<?= base_url('administrador/regdocumentoelectronico/agregarResumen') ?>" method="post" autocomplete="off">
        <div class="modal-header bg-success">
          <h5 class="modal-title text-white" id="exampleModalLabel">Agregar Resumen</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Fecha</label>
								<input type="text" name="fecha" class="form-control datepicker" value="<?= date('Y-m-d') ?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<button type="button" id="filtrarResumen" style="margin-top:30px" class="btn btn-success ">Filtrar</button>
							</div>
						</div>

					</div>
						<table id="TableResumenFecha" class="table table-striped">
							<thead>
								<tr class="bg-success text-white">
									<th>Fecha</th>
									<th>Cliente</th>
									<th>IGV</th>
									<th>Subtotal</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary">Procesar</button>
					</div>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="ModalListaBoletas" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-white" id="exampleModalLabel">Listado de Boletas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="lista-boletas">
          
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function ConsultarEstado(idticket,idres,nombre){
  $.ajax({
    url:path + 'administrador/regventas/ConsultarEstadoTicket',type:'post',dataType:'json',
    data:{
      idticket:idticket,
      idres:idres,
      nombre:nombre
    },
    beforeSend:function(){
      //obj.disabled=true;
      $('#td'+idres).empty().append("Espere..")
    },
    success:function(result){
      if(result.respuesta!="error"){
        
        var texto="<a href='"+path+"facturacion/"+result.ruta_cdr+"' target='_blank' class='btn btn-info btn-sm'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-cloud-download' viewBox='0 0 16 16'>";
        texto=texto+"<path d='M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z'/>"
        texto=texto+"<path d='M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z'/>";
        texto=texto+"</svg></a>";
        $('#td'+idres).empty().append(texto);
      }
      else{
        var texto="<a href='javascript:ConsultarEstado(\""+idticket+"\",\""+idres+"\",\""+nombre+"\");' class='btn btn-info btn-sm'>";
        texto=texto+"<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-arrow-clockwise' viewBox='0 0 16 16'>";
        texto=texto+"<path fill-rule='evenodd' d='M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z'/>";
        texto=texto+"<path d='M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z'/>";
        texto=texto+"</svg>";
        texto=texto+"</a>";
        $('#td'+idres).empty().append(texto);
      }
    }
  });
} 
</script>