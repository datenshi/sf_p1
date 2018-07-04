<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialinwarehouse extends CI_Controller {

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
