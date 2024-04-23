<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Regproducto extends CI_Controller
{
    private $permisos;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata("login")) {
            redirect(base_url());
        }
        $this->load->model('usuario_model');
        $this->load->model('productos_model');
        $this->load->model('empresa_model');
        $this->load->model('modelgeneral');
        $this->load->model('notaunidad_model');
        $this->load->model('notavalorizado_model');
        $this->load->helper('general');
        $this->permisos = $this->backend_lib->control();
    }

    public function index()
    {
        $data['permisos'] = $this->permisos;
        $data['categoria'] = $this->modelgeneral->getTable('tb_categoria');
        $data['marca'] = $this->modelgeneral->getTable('tb_marca');
        $data['articulo'] = $this->modelgeneral->getTable('tb_tiparticulo');
        $data['linea'] = $this->modelgeneral->getTable('tb_linea');
        $data['sublinea'] = $this->modelgeneral->getTable('tb_sublinea');
        $data['talla'] = $this->modelgeneral->getTable('tb_talla');
        $data['medida'] = $this->modelgeneral->getTable('tb_unidades');
        $data['parametros'] = $this->modelgeneral->getTable('parametros');
        $data['presentacion'] = $this->modelgeneral->getTable('tb_presentacion');
        $data['producto'] = $this->modelgeneral->getTable('tb_producto');
        $data['TypeproductAssignments'] = $this->modelgeneral->getTableWhere('tb_producto', ['typeAssignmentProduct' => 'P'], ['cod_producto', 'nomb_product']);
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/producto/listgetproducto', $data);
        $this->load->view('layouts/footer');
    }

    public function jsonProducto()
    {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns = array(
            'TipoArticulo', 'NombreProducto',
            'categoria', 'marca', 'barra_product', 'prec_costo',
            'prec_venta', 'stockmin_product', 'fecha_modificacion', 'est_product'
        );
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $desde = $this->input->get_post('desde');
        $hasta = $this->input->get_post('hasta');
        $tb_producto = $this->input->get_post('tb_producto');
        $tb_categoria = $this->input->get_post('tb_categoria');
        $tb_marca = $this->input->get_post('tb_marca');
        $tb_tiparticulo = $this->input->get_post('tb_tiparticulo');
        if ($desde != '' and $hasta != '') {
            $data['desde'] = $desde;
            $data['hasta'] = $hasta;
        }
        if ($tb_producto != '') {
            $data['tb_producto'] = $tb_producto;
        }
        //       elseif  ($tb_producto!='') {
        // 	$data['tb_producto'] = $tb_producto;
        // }

        if ($tb_categoria != '') {
            $data['tb_categoria'] = $tb_categoria;
        }
        if ($tb_marca != '') {
            $data['tb_marca'] = $tb_marca;
        }
        if ($tb_tiparticulo != '') {
            $data['tb_tiparticulo'] = $tb_tiparticulo;
        }
        $datos = $this->productos_model->getProductos($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
    }
    // AGREGAR MARCA 
    function insertMarcaprod()
    {

        $this->form_validation->set_rules('descripcion', '', 'required|trim|is_unique[tb_marca.nomb_marca]');
        if ($this->form_validation->run() == TRUE) {

            $data['nomb_marca'] = $this->input->post('descripcion');
            $data['est_marca'] =  1;
            $insert = $this->modelgeneral->insertRegist('tb_marca', $data);
            $resp = [];
            if (!is_null($insert)) {
                // $insert = $this->modelgeneral->insertRegist('tb_usuario',$data);
                $resp['marca'] = $this->modelgeneral->getTableWhereRow('tb_marca', ['cod_marca' => $insert]);
                $resp['success'] = true;
            } else {
                $resp['success'] = false;
            }
            echo json_encode($resp);
        }
    }
    // FIN DE AGREGAR MARCA

    // AGREGAR CATEGORIA 
    function insertCategoriaprod()
    {

        $this->form_validation->set_rules('descripcion', '', 'required|trim|is_unique[tb_categoria.nomb_categoria]');
        if ($this->form_validation->run() == TRUE) {

            $data['nomb_categoria'] = $this->input->post('descripcion');
            $data['est_categoria'] =  1;
            $insert = $this->modelgeneral->insertRegist('tb_categoria', $data);
            $resp = [];
            if (!is_null($insert)) {
                // $insert = $this->modelgeneral->insertRegist('tb_usuario',$data);
                $resp['categoria'] = $this->modelgeneral->getTableWhereRow('tb_categoria', ['cod_categoria' => $insert]);
                $resp['success'] = true;
            } else {
                $resp['success'] = false;
            }
            echo json_encode($resp);
        }
    }
    // FIN DE AGREGAR CATEGORIA

    function addProducto()
    {
        $this->form_validation->set_rules('tipoarticulo', '', 'required');
        $this->form_validation->set_rules('nombre', '', 'required');
        $this->form_validation->set_rules('marcas', '', 'required');
        $this->form_validation->set_rules('categorias', '', 'required');
        $this->form_validation->set_rules('unidad', '', 'required');
        $this->form_validation->set_rules('linea', '', 'required');
        $this->form_validation->set_rules('sublinea', '', 'required');
        $this->form_validation->set_rules('talla', '', 'required');
        $this->form_validation->set_rules('presentacion', '', 'required');
        $this->form_validation->set_rules('codigobarra', '', '');
        $this->form_validation->set_rules('preciocosto', '', 'required');
        $this->form_validation->set_rules('precioventa', '', 'required');
        $this->form_validation->set_rules('stock', '', 'required');
        $this->form_validation->set_rules('dispventa', '', 'required');
        $this->form_validation->set_rules('dispcompra', '', 'required');
        $this->form_validation->set_rules('parametros', '', 'required');
        if ($this->form_validation->run() == TRUE) {

            (empty($this->input->post('productAssignment'))) ? $data['typeAssignmentProduct'] = 'N' : $data['typeAssignmentProduct'] = $this->input->post('productAssignment');
            (empty($this->input->post('selectAssignmentDad'))) ? $data['idTypeAssignmentProduct'] = null : $data['idTypeAssignmentProduct'] = $this->input->post('selectAssignmentDad');
            $data['typeAssignmentProducto'] = $this->input->post('productoConasignacion');
            $data['typeAssignmentProductoBipay'] = $this->input->post('productAssignmentDebit');
            $data['bipay'] = $this->input->post('bipay');
            $data['descuento_prod'] = $this->input->post('descuento_prod');
            $data['cod_tiparticulo'] = $this->input->post('tipoarticulo');
            $data['nomb_product'] = $this->input->post('nombre');
            $data['cod_marca'] = $this->input->post('marcas');
            $data['cod_categoria'] = $this->input->post('categorias');
            $data['cod_unid'] = $this->input->post('unidad');
            $data['cod_linea'] = $this->input->post('linea');
            $data['cod_sublinea'] = $this->input->post('sublinea');
            $data['cod_talla'] =  $this->input->post('talla');
            $data['cod_present'] = $this->input->post('presentacion');
            $data['barra_product'] =  $this->input->post('codigobarra');
            $data['prec_costo'] =  $this->input->post('preciocosto');
            $data['prec_venta'] =  $this->input->post('precioventa');
            $data['prec_mayor_venta'] = $this->input->post('precioventa_mayor');
            $data['prec_especial_venta'] = $this->input->post('precioventa_especial');
            $data['stockmin_product'] =  $this->input->post('stock');
            $data['comision_product'] =  $this->input->post('comision');
            $data['fecha_registro'] = date("Y-m-d H:i:s");
            $data['fecha_modificacion'] = date("Y-m-d H:i:s");
            $data['dispo_venta'] = $this->input->post('dispventa');
            $data['dispo_compra'] = $this->input->post('dispcompra');
            $data['cod_parametros'] = $this->input->post('parametros');
            $data['fecha_vencimiento'] = $this->input->post('fecha_vencimiento');
            $data['est_product'] =  1;
            $insert = $this->modelgeneral->insertRegist('tb_producto', $data);
            $resp = [];
            if (!is_null($insert)) {
                //  $insert = $this->modelgeneral->insertRegist('tb_usuario',$data);
                $resp['success'] = true;
            } else {
                $resp['success'] = false;
            }
            echo json_encode($resp);
        }
    }

    function getProducto()
    {
        $id = $this->input->get('id');
        $producto = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $id]);
        echo json_encode($producto);
    }


    function editProducto()
    {
        $this->form_validation->set_rules('id', '', 'required');
        $this->form_validation->set_rules('tipoarticulo', '', 'required');
        $this->form_validation->set_rules('nombre', '', 'required');
        $this->form_validation->set_rules('marca', '', 'required');
        $this->form_validation->set_rules('categoria', '', 'required');
        $this->form_validation->set_rules('unidad', '', 'required');
        $this->form_validation->set_rules('linea', '', 'required');
        $this->form_validation->set_rules('sublinea', '', 'required');
        $this->form_validation->set_rules('talla', '', 'required');
        $this->form_validation->set_rules('presentacion', '', 'required');
        $this->form_validation->set_rules('codigobarra', '', '');
        $this->form_validation->set_rules('preciocosto', '', 'required');
        $this->form_validation->set_rules('precioventa', '', 'required');
        $this->form_validation->set_rules('stock', '', 'required');
        $this->form_validation->set_rules('dispventa', '', 'required');
        $this->form_validation->set_rules('dispcompra', '', 'required');
        $this->form_validation->set_rules('parametros', '', 'required');
        $this->form_validation->set_rules('estado', '', 'required');
        if ($this->form_validation->run() == TRUE) {

            $data['cod_tiparticulo'] = $this->input->post('tipoarticulo');
            $data['nomb_product'] = $this->input->post('nombre');

            $objectCheckedDad = $this->input->post('editproductAssignmentDad');
            $objectCheckedSon = $this->input->post('editproductAssignmentSon');
            $objectCheckedGson = $this->input->post('editproductAssignmentGson');
            $objectCheckedD = $this->input->post('editproductAssignmentDebit');

            if ($objectCheckedDad == null && $objectCheckedSon == null && $objectCheckedGson == null) {
                $data['typeAssignmentProduct'] = 'N';
                $data['typeAssignmentProducto'] = 'N';
                $data['idTypeAssignmentProduct'] = null;
            } else if ($objectCheckedDad == null && $objectCheckedSon !== null && $objectCheckedGson == null) {
                $data['idTypeAssignmentProduct'] = $this->input->post('editselectAssignmentDad');
                $data['typeAssignmentProduct'] = 'H';
                $data['typeAssignmentProducto'] = 'H';
            } else if ($objectCheckedDad !== null && $objectCheckedSon == null && $objectCheckedGson == null) {
                $data['idTypeAssignmentProduct'] = null;
                $data['typeAssignmentProduct'] = 'P';
                $data['typeAssignmentProducto'] = 'P';
            } else if ($objectCheckedDad == null && $objectCheckedSon !== null && $objectCheckedGson !== null && $objectCheckedD !== null) {
                $data['idTypeAssignmentProduct'] = $this->input->post('editselectAssignmentDad');
                $data['typeAssignmentProduct'] = 'H';
                $data['typeAssignmentProducto'] = 'G';
                $data['typeAssignmentProductoBipay'] = 'D';
            }
            $data['descuento_prod'] = $this->input->post('descuento_prod');
            $data['bipay'] = $this->input->post('bipay');
            $data['cod_marca'] = $this->input->post('marca');
            $data['cod_categoria'] = $this->input->post('categoria');
            $data['cod_unid'] = $this->input->post('unidad');
            $data['cod_linea'] = $this->input->post('linea');
            $data['cod_sublinea'] = $this->input->post('sublinea');
            $data['cod_talla'] =  $this->input->post('talla');
            $data['cod_present'] = $this->input->post('presentacion');
            $data['barra_product'] =  $this->input->post('codigobarra');
            $data['prec_costo'] =  $this->input->post('preciocosto');
            $data['prec_venta'] =  $this->input->post('precioventa');
            $data['prec_mayor_venta'] = $this->input->post('precioventa_mayor');
            $data['prec_especial_venta'] = $this->input->post('precioventa_especial');
            $data['stockmin_product'] =  $this->input->post('stock');
            $data['comision_product'] =  $this->input->post('comision');
            $data['fecha_modificacion'] = date("Y-m-d H:i:s");
            $data['dispo_venta'] = $this->input->post('dispventa');
            $data['dispo_compra'] = $this->input->post('dispcompra');
            $data['cod_parametros'] = $this->input->post('parametros');
            $data['est_product'] = $this->input->post('estado');
            $data['fecha_vencimiento'] = $this->input->post('fecha_vencimiento');
            $where['cod_producto'] = $this->input->post('id');

            $edit = $this->modelgeneral->editRegist('tb_producto', $where, $data);
            $resp = [];
            if (!is_null($edit)) {
                $resp['success'] = true;
            } else {
                $resp['success'] = false;
            }

            echo json_encode($resp);
        }
    }

    function anularProducto()
    {
        $data['est_product'] = 2; //ANULAR	
        $where['cod_producto'] = $this->input->get('id');

        $edit = $this->modelgeneral->editRegist('tb_producto', $where, $data);
        $resp = [];
        if ($edit) {
            $resp['success'] = true;
        } else {
            $resp['success'] = false;
        }
        echo json_encode($resp);
    }
    public function descargarPlantillaProducto()
    {
        $data['pestanas'] = $this->getPestanasProductos();
        $this->load->view('admin/producto/descargar_plantilla_productos', $data);
    }

    public function descargarPlantillaStock()
    {
        $data['pestanas'] = $this->getPestanasStock();
        $this->load->view('admin/producto/descargar_plantilla_stock', $data);
    }

    public function getPestanasStock()
    {
        $data['ALMACEN']['datos'] = $this->db->from('tb_almacen')
            ->select('cod_almacen as codigo, nomb_almacen as nombre')
            ->where('est_almacen', 1)
            ->order_by('nomb_almacen', 'asc')
            ->get()->result();
        $data['ALMACEN']['row'] = 2;

        $data['PRODUCTO']['datos'] = $this->db->from('tb_producto')
            ->select('cod_producto as codigo, nomb_product as nombre')
            ->where('est_product', 1)
            ->order_by('nomb_product', 'asc')
            ->get()->result();
        $data['PRODUCTO']['row'] = 2;

        return $data;
    }

    private function getPestanasProductos()
    {
        $data['ALMACEN']['datos'] = $this->db->from('tb_almacen')
            ->select('cod_almacen as codigo, nomb_almacen as nombre')
            ->where('est_almacen', 1)
            ->order_by('nomb_almacen', 'asc')
            ->get()->result();
        $data['ALMACEN']['row'] = 2;

        $data['MARCA']['datos'] = $this->db->from('tb_marca')
            ->select('cod_marca as codigo, nomb_marca as nombre')
            ->where('est_marca', 1)
            ->order_by('nomb_marca', 'asc')
            ->get()->result();
        $data['MARCA']['row'] = 2;

        $data['UNIDAD']['datos'] = $this->db->from('tb_unidades')
            ->select('cod_unid as codigo, nomb_unid as nombre')
            ->where('est_unidad', 1)
            ->order_by('nomb_unid', 'asc')
            ->get()->result();
        $data['UNIDAD']['row'] = 2;

        $data['CATEGORIA']['datos'] = $this->db->from('tb_categoria')
            ->select('cod_categoria as codigo, nomb_categoria as nombre')
            ->where('est_categoria', 1)
            ->order_by('nomb_categoria', 'asc')
            ->get()->result();
        $data['CATEGORIA']['row'] = 2;

        $data['TIPO_ARTICULO']['datos'] = $this->db->from('tb_tiparticulo')
            ->select('cod_tiparticulo as codigo, nomb_tiparticulo as nombre')
            ->where('est_tiparticulo', 1)
            ->order_by('nomb_tiparticulo', 'asc')
            ->get()->result();
        $data['TIPO_ARTICULO']['row'] = 2;

        $data['TIPO_IGV']['datos'] = $this->db->from('parametros')
            ->select('cod_parametros as codigo, nom_paramt as nombre')
            ->where('est_paramt', 1)
            ->order_by('nom_paramt', 'asc')
            ->get()->result();
        $data['TIPO_IGV']['row'] = 2;


        return $data;
    }

    public function uploadPlantilla()
    {
        $config['upload_path'] = APP_TENANTPATH . 'assets/importar_productos';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = '40000';
        $config['max_width'] = '40000';
        $config['max_height'] = '40000';
        $this->upload->initialize($config);
        $resp = [];
        if ($this->upload->do_upload('plantilla')) {
            $upload = $this->upload->data();
            $resp['success'] = true;
            $resp['name'] = $upload['file_name'];
            $resp['importar'] = $this->importar($resp['name']);
        } else {
            $resp['ruta'] = $config['upload_path'];
            $resp['success'] = false;
            $resp['error'] = $this->upload->display_errors();
        }

        echo json_encode($resp);
    }

    public function importar($archivo)
    {
        $ruta = APP_TENANTPATH . "assets/importar_productos/" . $archivo;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($ruta);
        $sheet = $spreadsheet->getSheetByName('PRODUCTOS'); //HOJA A PROCESAR

        foreach ($sheet->getRowIterator(2, 1000) as $index => $row) {
            $data[$index]['almacen'] = $sheet->getCellByColumnAndRow(2, $row->getRowIndex())->getCalculatedValue();
            $data[$index]['marca'] = $sheet->getCellByColumnAndRow(4, $row->getRowIndex())->getCalculatedValue();
            $data[$index]['unidad'] = $sheet->getCellByColumnAndRow(6, $row->getRowIndex())->getCalculatedValue();
            $data[$index]['categoria'] = $sheet->getCellByColumnAndRow(8, $row->getRowIndex())->getCalculatedValue();
            $data[$index]['tipo_articulo'] = $sheet->getCellByColumnAndRow(10, $row->getRowIndex())->getCalculatedValue();
            $data[$index]['tipo_igv'] = $sheet->getCellByColumnAndRow(12, $row->getRowIndex())->getCalculatedValue();
            $data[$index]['producto'] = $sheet->getCellByColumnAndRow(13, $row->getRowIndex())->getValue();
            $data[$index]['precio_compra'] = $sheet->getCellByColumnAndRow(14, $row->getRowIndex())->getValue();
            $data[$index]['precio_venta'] = $sheet->getCellByColumnAndRow(15, $row->getRowIndex())->getValue();
            $data[$index]['precio_mayor'] = $sheet->getCellByColumnAndRow(16, $row->getRowIndex())->getValue();
            $data[$index]['precio_especial'] = $sheet->getCellByColumnAndRow(17, $row->getRowIndex())->getValue();
            $data[$index]['stock_minimo'] = $sheet->getCellByColumnAndRow(18, $row->getRowIndex())->getValue();
            $data[$index]['stock'] = $sheet->getCellByColumnAndRow(19, $row->getRowIndex())->getValue();
            $data[$index]['codigo_barra'] = $sheet->getCellByColumnAndRow(20, $row->getRowIndex())->getValue();
        }

        $procesado = $this->erroresPlantillaProducto($data);
        $resp = [];
        if (count($procesado['errores']) > 0) {
            $resp['success'] = false;
            $resp['errores'] = $procesado['errores'];
            return $resp;
            exit();
        }

        $this->insertProducto($procesado['productos']);

        $resp['success'] = true;
        $resp['num_filas'] = count($procesado['productos']);
        $resp['procesados'] = $procesado;
        return $resp;
    }

    private function insertProducto($productos)
    {
        $arr_almacen = array();
        $arr = array();
        foreach ($productos as $indice => $value) {
            $data = [];
            $data['cod_tiparticulo'] = $value['tipo_articulo'];
            $data['nomb_product'] = $value['producto'];
            $data['cod_marca'] = $value['marca'];
            $data['cod_categoria'] = $value['categoria'];
            $data['cod_unid'] = $value['unidad'];
            $data['cod_linea'] = 1;
            $data['cod_sublinea'] = 1;
            $data['cod_talla'] =  1;
            $data['cod_present'] = 1;
            $data['barra_product'] =  $value['codigo_barra'];
            $data['prec_costo'] =  $value['precio_compra'];
            $data['prec_venta'] =  $value['precio_venta'];
            $data['prec_mayor_venta'] = $value['precio_mayor'];
            $data['prec_especial_venta'] = $value['precio_especial'];
            $data['stockmin_product'] =  $value['stock_minimo'];
            $data['fecha_registro'] = date("Y-m-d H:i:s");
            $data['fecha_modificacion'] = date("Y-m-d H:i:s");
            $data['dispo_venta'] = 'S';
            $data['dispo_compra'] = 'S';
            $data['cod_parametros'] = $value['tipo_igv'];
            $data['est_product'] =  1;

            $insert = $this->modelgeneral->insertRegist('tb_producto', $data);

            if ($value['stock'] > 0) {
                $dataStock['cod_producto'] = $insert;
                $dataStock['cod_almacen'] = $value['almacen'];
                $dataStock['stock'] = $value['stock'];
                $dataStock['stock_inicial'] = $value['stock'];
                $this->modelgeneral->insertRegist('tb_producto_stock', $dataStock);

                /*******Ingreso nuevo********************************/
                $producto = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $insert]);
                /*Poblamos el detalle para la boleta de ingreso */
                $undmed_prod = $this->modelgeneral->getTableWhereRow('tb_unidades', ['cod_unid' => $producto->cod_unid]);
                $arr_det[$value]['nund'] = $value['stock'];
                $arr_det[$value]['ccod_undmed'] = $undmed_prod->abreviatura_unid;
                $arr_det[$value]['ccod_art'] = $insert;
                $arr_det[$value]['cdsc_art'] = $producto->nomb_product;
                $arr_det[$value]['bind_lote'] = 'N';
                $arr_det[$value]['cnro_lote'] = '';
                /*FIN Poblamos el detalle para la boleta de ingreso */
                /*Poblamos el detalle para la nota de ingreso */
                $arr_detval[$value]['nund'] = $value['stock'];
                $arr_detval[$value]['ccod_undmed'] = $undmed_prod->abreviatura_unid;
                $arr_detval[$value]['ccod_art'] = $insert;
                $arr_detval[$value]['cdsc_art'] = $producto->nomb_product;
                $arr_detval[$value]['ncosto'] = $value['precio_compra'];
                /*FIN Poblamos el detalle para la nota de ingreso */
            }
        }
        if (sizeof($arr_det) > 0) {
            //**************************Ingresamos la boleta de ingreso***********************************/
            /*poblamos array para boleta de ingreso*/
            $datos_empresa = $this->modelgeneral->getTableWhereRow('tb_empresa', ['cod_empresa' => 1]);
            $_SESSION['ALM_Kardex_det'] = $arr_det;
            foreach ($arr_almacen as $ind => $val) {
                $arr_serie = $this->notaunidad_model->ProxCorrelativoAlmacen(array('tipo' => 'BI', 'codalm' => $val));
                if (sizeof($arr_serie) > 0) {
                    $arrboleta['Serie_Nota'] = $arr_serie[0]['serie'];
                    $arrboleta['Num_Nota'] = ($arr_serie[0]['correlativo'] + 1);
                } else {
                    $resp['success'] = false;
                    echo json_encode($resp);
                    exit(0);
                }
                $arrboleta['Tipo_Nota'] = 'I';
                $arrboleta['Ruc_Cliente'] = $datos_empresa->ruc_emp;
                $arrboleta['Fecha_Nota'] = date('Y-m-d');
                $arrboleta['Motivo_Recep'] = '11';
                $arrboleta['obs_Nota'] = 'Ingresado desde importacion de articulo';
                $arrboleta['Cod_Almacen'] = $val;
                $arrboleta['tip_doc_ref'] = "32";
                $arrboleta['serie_doc_ref'] = '';
                $arrboleta['num_doc_ref'] = '';
                $arrboleta['Estado'] = 'R';
                $arrboleta['usu_reg'] = $this->session->cod_usu;
                $arrboleta['Fec_Reg'] = date('Y-m-d');
                $dins = $this->notaunidad_model->Insnotaunidad($arrboleta, "N");
                if (sizeof($dins) > 0) {
                    if ($dins['status'] != "1") {
                        $resp['success'] = false;
                    } else {
                        $this->notaunidad_model->ActualizarCorrelativoAlmacen(array('tipo' => 'BI', 'codalm' => $val, 'Numero' => $arrboleta['Num_Nota']));
                    }
                } else {
                    $result['status'] = 2;
                    $result['msg'] = 'PROBLEMAS AL GUARDAR EL REGISTRO';
                }
                /*fin poblamos array para boleta de ingreso*/
            }
            /*poblamos array para nota de ingreso*/
            $_SESSION['ALM_Kardexval_det'] = $arr_detval;
            $arr_serie = $this->notaunidad_model->ProxCorrelativoAlmacen(array('tipo' => 'NI', 'codalm' => ''));
            if (sizeof($arr_serie) > 0) {
                $arrnota['Serie_Nota'] = $arr_serie[0]['serie'];
                $arrnota['Num_Nota'] = ($arr_serie[0]['correlativo'] + 1);
            } else {
                $resp['success'] = false;
                echo json_encode($resp);
                exit(0);
            }
            $arrnota['Tipo_Nota'] = 'I';
            $arrnota['Ruc_Cliente'] = $datos_empresa->ruc_emp;
            $arrnota['Fecha_Nota'] = date('Y-m-d');
            $arrnota['CodMotivo'] = '11';
            $arrnota['obs_Nota'] = 'Ingresado desde importacion de articulo';
            $arrnota['tip_doc_ref'] = "32";
            $arrnota['serie_doc_ref'] = '';
            $arrnota['num_doc_ref'] = '';
            $arrnota['ccod_mon'] = 'S';
            $arrnota['nt_cambio'] = '3.50';
            $arrnota['Estado'] = 'R';
            $arrnota['usu_reg'] = $this->session->cod_usu;
            $arrnota['Fec_Reg'] = date('Y-m-d');
            $dins = $this->notavalorizado_model->Insnotaunidad($arrnota, "N");
            if (sizeof($dins) > 0) {
                if ($dins['status'] != "1") {
                    $resp['success'] = false;
                } else {
                    $this->notaunidad_model->ActualizarCorrelativoAlmacen(array('tipo' => 'NI', 'codalm' => '', 'Numero' => $arrnota['Num_Nota']));
                }
            } else {
                $resp['success'] = false;
            }
            /*fin poblamos array para nota de ingreso*/
        }
    }

    private function erroresPlantillaProducto($array)
    {
        $errores = [];
        $productos = [];

        foreach ($array as $index => $data) {
            if (is_numeric($data['tipo_igv']) or is_numeric($data['marca']) or is_numeric($data['unidad']) or is_numeric($data['categoria']) or is_numeric($data['tipo_articulo'])) {
                if (!is_numeric($data['almacen'])) {
                    $errores['almacen'][] = 'Seleccione una almacen en la fila ' . $index;
                }
                if (!is_numeric($data['marca'])) {
                    $errores['marca'][] = 'Seleccione una marca en la fila ' . $index;
                }
                if (!is_numeric($data['unidad'])) {
                    $errores['unidad'][] = 'Seleccione una unidad en la fila ' . $index;
                }
                if (!is_numeric($data['categoria'])) {
                    $errores['categoria'][] = 'Seleccione una categoria en la fila ' . $index;
                }
                if (!is_numeric($data['tipo_articulo'])) {
                    $errores['tipo_articulo'][] = 'Seleccione un tipo de artículo en la fila ' . $index;
                }
                if (!is_numeric($data['tipo_igv'])) {
                    $errores['tipo_igv'][] = 'Seleccione un tipo de IGV en la fila ' . $index;
                }
                if ($data['producto'] == '') {
                    $errores['producto'][] = 'Ingrese el nombre de un producto en la fila ' . $index;
                }
                if ($data['precio_compra'] == '') {
                    $errores['precio_compra'][] = 'Ingrese el precio compra en la fila ' . $index;
                }
                if (!is_numeric($data['precio_compra'])) {
                    $errores['precio_compra'][] = 'Precio compra debe ser un número en la fila ' . $index;
                } else {
                    if (intval($data['precio_compra']) < 0) {
                        $errores['precio_compra'][] = 'Precio compra debe ser un número positivo en la fila ' . $index;
                    }
                }

                if ($data['precio_venta'] == '') {
                    $errores['precio_venta'][] = 'Ingrese el precio venta en la fila ' . $index;
                }
                if (!is_numeric($data['precio_venta'])) {
                    $errores['precio_venta'][] = 'Precio venta debe ser un número en la fila ' . $index;
                } else {
                    if (intval($data['precio_venta']) < 0) {
                        $errores['precio_venta'][] = 'Precio venta debe ser un número positivo en la fila ' . $index;
                    }
                }
                if ($data['precio_mayor'] == '') {
                    $errores['precio_mayor'][] = 'Ingrese el precio mayor en la fila ' . $index;
                }
                if (!is_numeric($data['precio_mayor'])) {
                    $errores['precio_mayor'][] = 'Precio mayor debe ser un número en la fila ' . $index;
                } else {
                    if (intval($data['precio_mayor']) < 0) {
                        $errores['precio_mayor'][] = 'Precio mayor debe ser un número positivo en la fila ' . $index;
                    }
                }
                if ($data['precio_especial'] == '') {
                    $errores['precio_especial'][] = 'Ingrese el precio especial en la fila ' . $index;
                }
                if (!is_numeric($data['precio_especial'])) {
                    $errores['precio_especial'][] = 'Precio especial debe ser un número en la fila ' . $index;
                } else {
                    if (intval($data['precio_especial']) < 0) {
                        $errores['precio_especial'][] = 'Precio especial debe ser un número positivo en la fila ' . $index;
                    }
                }
                if ($data['stock_minimo'] == '') {
                    $errores['stock_minimo'][] = 'Ingrese el stock mínimo en la fila ' . $index;
                }
                if (!is_numeric($data['stock_minimo'])) {
                    $errores['stock_minimo'][] = 'Stock mínimo debe ser un número en la fila ' . $index;
                } else {
                    if (intval($data['stock_minimo']) < 0) {
                        $errores['stock_minimo'][] = 'Stock mínimo debe ser un número positivo en la fila ' . $index;
                    }
                }

                if (is_null($data['stock'])) {
                    $errores['stock'][] = 'Ingrese el stock en la fila ' . $index;
                } else {

                    if (!is_numeric($data['stock'])) {
                        $errores['stock'][] = 'Stock debe ser un número en la fila ' . $index;
                    } else {
                        if ($data['stock'] < 0) {
                            $errores['stock'][] = 'Stock no puede ser negativo en la fila ' . $index;
                        }
                    }
                }

                if ($data['codigo_barra'] == '') {
                    $errores['codigo_barra'][] = 'Ingrese el código de barra en la fila ' . $index;
                }


                $productos[] = $data;
            }
        }

        $resp = [];
        $resp['errores'] = $errores;
        $resp['productos'] = $productos;

        return $resp;
    }

    public function uploadPlantillaStock()
    {
        $config['upload_path'] = APP_TENANTPATH . 'assets/importar_productos';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = '40000';
        $config['max_width'] = '40000';
        $config['max_height'] = '40000';
        $this->upload->initialize($config);
        $resp = [];
        if ($this->upload->do_upload('plantillaStock')) {
            $upload = $this->upload->data();
            $resp['success'] = true;
            $resp['name'] = $upload['file_name'];
            $resp['importar'] = $this->importarSeries($resp['name']);
        } else {
            $resp['ruta'] = $config['upload_path'];
            $resp['success'] = false;
            $resp['error'] = $this->upload->display_errors();
        }

        echo json_encode($resp);
    }

    public function importarSeries($archivo)
    {
        $ruta = APP_TENANTPATH . "assets/importar_productos/" . $archivo;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($ruta);
        $sheet = $spreadsheet->getSheetByName('STOCK_SERIE'); //NUMERO DE PESTAÑA

        foreach ($sheet->getRowIterator(2, 1000) as $index => $row) {
            $data[$index]['almacen'] = $sheet->getCellByColumnAndRow(2, $row->getRowIndex())->getCalculatedValue();
            $data[$index]['producto'] = $sheet->getCellByColumnAndRow(4, $row->getRowIndex())->getCalculatedValue();
            $data[$index]['pregunta'] = $sheet->getCellByColumnAndRow(5, $row->getRowIndex())->getCalculatedValue();
            $data[$index]['stock_serie'] = $sheet->getCellByColumnAndRow(6, $row->getRowIndex())->getCalculatedValue();
        }
        $procesado = $this->erroresPlantillaStock($data);

        $resp = [];
        if (count($procesado['errores']) > 0) {
            $resp['success'] = false;
            $resp['errores'] = $procesado['errores'];
            return $resp;
            exit();
        }

        $this->insertStockSerie($procesado['productos']);

        $resp['success'] = true;
        $resp['num_filas'] = count($procesado['productos']);
        $resp['procesados'] = $procesado;

        return $resp;
    }

    private function insertStockSerie($productos)
    {
        foreach ($productos as $indice => $value) {
            $almacen = $value['almacen'];
            $producto = $value['producto'];
            $pregunta = $value['pregunta'];
            if ($pregunta == 'STOCK') {
                $this->insertStock($value);
            } else {
                $this->insertSerie($value);
            }
        }
    }

    private function insertStock($data)
    {
        $query = $this->db->from('tb_producto_stock')
            ->where('cod_almacen', $data['almacen'])
            ->where('cod_producto', $data['producto'])
            ->get();
        if ($query->num_rows() == 1) {
            $this->modelgeneral->editRegist(
                'tb_producto_stock',
                [
                    'cod_almacen' => $data['almacen'],
                    'cod_producto' => $data['producto']
                ],
                [
                    'stock' => $query->row()->stock + $data['stock_serie']
                ]
            );
        } else {
            $dataStock['cod_almacen'] = $data['almacen'];
            $dataStock['cod_producto'] = $data['producto'];
            $dataStock['stock'] = $data['stock_serie'];
            $dataStock['stock_inicial'] = $data['stock_serie'];
            $this->modelgeneral->insertRegist('tb_producto_stock', $dataStock);
        }
    }

    private function insertSerie($data)
    {
        $query = $this->db->from('tb_producto_stock')
            ->where('cod_almacen', $data['almacen'])
            ->where('cod_producto', $data['producto'])
            ->get();


        if ($query->num_rows() == 1) {
            $histcompstock_serie = $query->row()->stock + 1;
        } else {
            $histcompstock_serie = 1;
        }

        if ($query->num_rows() == 1) {
            $whereStock['cod_almacen'] = $data['almacen'];
            $whereStock['cod_producto'] = $data['producto'];
            $dataStock['stock'] = $histcompstock_serie;
            $this->modelgeneral->editRegist('tb_producto_stock', $whereStock, $dataStock);
        } else {
            $dataStock['cod_almacen'] = $data['almacen'];
            $dataStock['cod_producto'] = $data['producto'];
            $dataStock['stock'] = $histcompstock_serie;
            $dataStock['stock_inicial'] = $histcompstock_serie;
            $this->modelgeneral->insertRegist('tb_producto_stock', $dataStock);
        }


        $dataSerie['cod_almacen'] = $data['almacen'];
        $dataSerie['cod_producto'] = $data['producto'];
        $dataSerie['serie_descripcion'] = $data['stock_serie'];
        $dataSerie['cod_comp'] = null;
        $dataSerie['cod_vent'] = null;
        $dataSerie['serie_estado'] = 'D';
        $dataSerie['histcompstock_serie'] = $histcompstock_serie;
        $this->modelgeneral->insertRegist('tb_producto_serie', $dataSerie);
    }

    private function erroresPlantillaStock($array)
    {
        $errores = [];
        $productos = [];

        foreach ($array as $index => $data) {
            if (is_numeric($data['almacen']) or is_numeric($data['producto']) or !is_null($data['pregunta']) or !is_null($data['stock_serie'])) {
                if (!is_numeric($data['almacen'])) {
                    $errores['almacen'][] = 'Escriba el código de un almacen en la fila ' . $index;
                }
                if (!is_numeric($data['producto'])) {
                    $errores['producto'][] = 'Escriba el codigo de un almacen en la fila ' . $index;
                }
                if (is_null($data['pregunta'])) {
                    $errores['pregunta'][] = 'Seleccione si es en stock o serie en la fila ' . $index;
                }
                if (is_null($data['stock_serie'])) {
                    $errores['stock_serie'][] = 'Escriba la serie o stock en la fila ' . $index;
                } else {
                    if ($data['pregunta'] == 'SERIE') {
                        $unico = $this->db->from('tb_producto_serie')
                            ->where('cod_producto', $data['producto'])
                            ->where('serie_descripcion', $data['stock_serie'])
                            ->get();
                        if ($unico->num_rows() > 0) {
                            $errores['stock_serie'][] = 'La serie ' . $data['stock_serie'] . ' ya existe en la base de datos, fila ' . $index;
                        }
                    }
                }


                $productos[] = $data;
            }
        }

        $resp = [];
        $resp['errores'] = $errores;
        $resp['productos'] = $productos;
        return $resp;
    }
}
