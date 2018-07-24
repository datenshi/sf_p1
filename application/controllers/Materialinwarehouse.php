<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialinwarehouse extends CI_Controller {

    public function queryMaterialInWarehouseView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => "原料入庫管理"
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryMaterialInWarehouseView');
        $this->load->view('footer');
    }

    public function downloadMaterialInWarehouseExcel($filterByDate = 0)
    {
        $obj = $this->input->post('excelBuildData');
        $db_data_test = self::getDBInfo($obj['model'],$obj['queryfunction']);
        $header = $obj['header'];
        $this->load->helper('print_helper');
        $response = only_print_excel($db_data_test, $header);
        die(json_encode($response));
    }

    public function getDBInfo($model, $queryFunction){
        $model_local = $model;
        $query_function = $queryFunction;

        $this->load->model($model_local);

        $query = $this->$model_local->$query_function();
        return $query->result_array();
    }

    public function queryMaterialInWarehouse()
    {
        $this->load->model('materialinwarehousemodel');

        $query = $this->materialinwarehousemodel->queryMaterialInWarehouseData();
        echo json_encode($query->result_array());
    }

    public function queryMaterialNameIDInWarehouse(){
        $this->load->model('materialinwarehousemodel');

        $query = $this->materialinwarehousemodel->queryMaterialNameIDInWarehouseData();
        echo json_encode($query->result_array());
    }

    public function querySupplierNameIDInWareHouseByMaterialID($materialID){
        $this->load->model('materialinwarehousemodel');

        $query = $this->materialinwarehousemodel->querySupplierNameIDInWareHouseByMaterialIDData($materialID);
        echo json_encode($query->result_array());
    }

    public function queryPackagingNameIDInWareHouseByMaterialSupplierID($materialID, $supplierID){
        $this->load->model('materialinwarehousemodel');

        $query = $this->materialinwarehousemodel->queryPackagingNameIDInWareHouseByMaterialSupplierIDData($materialID, $supplierID);
        echo json_encode($query->result_array());
    }

    public function queryMaterialInWarehouseDataByMaterialSupplierID($materialID, $supplierID){
        $this->load->model('materialinwarehousemodel');

        $query = $this->materialinwarehousemodel->queryMaterialInWarehouseDataByMaterialSupplierIDData($materialID, $supplierID);
        echo json_encode($query->result_array());
    }

    public function queryMaterialInWarehouseDataByMaterialSupplierPackagingID($materialID, $supplierID, $packagingID){
        $this->load->model('materialinwarehousemodel');

        $query = $this->materialinwarehousemodel->queryMaterialInWarehouseDataByMaterialSupplierPackagingIDData($materialID, $supplierID, $packagingID);
        echo json_encode($query->result_array());
    }

    public function queryMaterialInWarehouseDataByMaterialSupplierPackagingArea($materialID, $supplierID, $packagingID, $area){
        $this->load->model('materialinwarehousemodel');

        $query = $this->materialinwarehousemodel->queryMaterialInWarehouseDataByMaterialSupplierPackagingAreaData($materialID, $supplierID, $packagingID, $area);
        echo json_encode($query->result_array());
    }

    public function queryAreaInWarehouseByMaterialSupplierPackagingID($materialID, $supplierID, $packagingID){
        $this->load->model('materialinwarehousemodel');

        $query = $this->materialinwarehousemodel->queryAreaInWarehouseByMaterialSupplierPackagingIDData($materialID, $supplierID, $packagingID);
        echo json_encode($query->result_array());
    }
}
