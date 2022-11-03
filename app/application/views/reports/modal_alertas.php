<div id="ModalStockMinimos" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
  <div class="card-header bg-success"><h3 class="my-0 text-white"><i class="spinner-grow text-pink float-right"></i></h3></div>
    <div class="modal-content">
      <div class="modal-body">
            <div class="row">
                <div id="stock-minimo-contenido" class="col-lg-12">
                    <h4>Alerta de productos con stock mínimo</h4>
                    <table id="TableStockMinimos" class="table table-striped  table-condensed tblstockminimo">
                        <thead>
                            <tr class="bg-success text-white">
                                <th>Almacen</th>
                                <th>Producto</th>
                                <th>Categoria</th>
                                <th>Unidades</th>
                                <th>P. Costo</th>
                                <th>Stock</th>
                                <th class="bg-danger">Mínimo</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div id="stock-vencimiento-contenido" class="col-lg-12">
                    <h4>Alerta de productos próximos a vencer</h4>
                    <table id="TableProductoFechaVencimiento" class="table table-striped  table-condensed tblstockminimo">
                        <thead>
                            <tr class="bg-warning text-white">
                                <th>Almacen</th>
                                <th>Producto</th>
                                <th>Fec. Prod.</th>
                                <th>Fec. Venc.</th>
                                <th>Cant.</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-pink btn-rounded" id="posponer-stockminimo"><span class="m-r-5">Posponer</span><i class="fas fa-undo"></i></button>
        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button> -->
      </div>
    </div>
  </div>
</div>