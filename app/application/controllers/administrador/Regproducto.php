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
        $this->load->model('modelgeneral');
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
            'prec_venta', 'stockmin_product', 'fecha_modificacion','est_product'
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




    function addProducto()
    {
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
        if ($this->form_validation->run() == TRUE) {

            (empty($this->input->post('productAssignment'))) ? $data['typeAssignmentProduct'] = 'N' : $data['typeAssignmentProduct'] = $this->input->post('productAssignment');
            (empty($this->input->post('selectAssignmentDad'))) ? $data['idTypeAssignmentProduct'] = null : $data['idTypeAssignmentProduct'] = $this->input->post('selectAssignmentDad');

            $data['cod_tiparticulo'] = $this->input->post('tipoarticulo');
            $data['nomb_product'] = $this->input->post('nombre');
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
            $data['fecha_registro'] = date("Y-m-d H:i:s");
            $data['fecha_modificacion'] = date("Y-m-d H:i:s");
            $data['dispo_venta'] = $this->input->post('dispventa');
            $data['dispo_compra'] = $this->input->post('dispcompra');
            $data['cod_parametros'] = $this->input->post('parametros');
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

            if ($objectCheckedDad == null && $objectCheckedSon == null) {
                $data['typeAssignmentProduct'] = 'N';
                $data['idTypeAssignmentProduct'] = null;
            } else if ($objectCheckedDad == null && $objectCheckedSon !== null) {
                $data['idTypeAssignmentProduct'] = $this->input->post('editselectAssignmentDad');
                $data['typeAssignmentProduct'] = 'H';
            } else if ($objectCheckedDad !== null && $objectCheckedSon == null) {
                $data['idTypeAssignmentProduct'] = null;
                $data['typeAssignmentProduct'] = 'P';
            }

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
            $data['fecha_modificacion'] = date("Y-m-d H:i:s");
            $data['dispo_venta'] = $this->input->post('dispventa');
            $data['dispo_compra'] = $this->input->post('dispcompra');
            $data['cod_parametros'] = $this->input->post('parametros');
            $data['est_product'] = $this->input->post('estado');
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
}
