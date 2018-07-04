<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialoutwarehouse extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome');
            return;
        }*/
    }

    public function addMaterialOutWarehouseView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增原料出庫'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addMaterialOutWarehouseView');
        $this->load->view('footer');
    }

    public function addMaterialOutWarehouse()
    {
        $this->load->model('materialoutwarehousemodel');
        $this->load->model('materialusagemodel');
        $this->load->model('materialrequisitionmodel');
        $this->load->model('materialinwarehousemodel');

        $materialOutWarehouseData['materialInWarehouseID'] = $this->input->post('materialInWarehouseID');
        $materialOutWarehouseData['materialRequisition'] = $this->input->post('materialRequisition');
        $materialOutWarehouseData['outWarehouseArea'] = $this->input->post('outWarehouseArea');
        // For Taiwan GMT+8
        $currentDateTime = gmdate("Y-m-d H:i:s", (time() + (28800)));
        $materialOutWarehouseData['outWarehouseDate'] = $currentDateTime;
        $materialOutWarehouseData['outWarehouseDepartment'] = $this->input->post('outWarehouseDepartment');
        $materialOutWarehouseData['outWarehouseMember'] = $this->input->post('outWarehouseMember');
        $materialOutWarehouseData['outWarehousePackageNumber'] = $this->input->post('outWarehousePackageNumber');

        $result = $this->materialoutwarehousemodel->insertMaterialOutWarehouseData($materialOutWarehouseData);

        $queryData = $this->materialusagemodel->queryUsingDepartmentByMaterialUsageID($materialOutWarehouseData['outWarehouseDepartment']);
        $materialOutWarehouseData['outWarehouseDepartment'] = $queryData['usingDepartment'];
        if (true == $result) {
            echo json_encode($materialOutWarehouseData);
        }

        // Minus notOutPackageNumber in material requisition table
        $this->materialrequisitionmodel->updateNotOutPackageNumberData($materialOutWarehouseData['materialRequisition'], (-$materialOutWarehouseData['outWarehousePackageNumber']));

        // Minus remaining package number in warehouse by storedMaterialID
        $this->materialinwarehousemodel->updateRemainingPackageNumberData($materialOutWarehouseData['materialInWarehouseID'], (-$materialOutWarehouseData['outWarehousePackageNumber']));
    }

    public function queryMaterialOutWarehouseView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => "查詢原料出庫"
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryMaterialOutWarehouseView');
        $this->load->view('footer');
    }

    public function queryMaterialOutWarehouse()
    {
        $this->load->model('materialoutwarehousemodel');

        $query = $this->materialoutwarehousemodel->queryMaterialOutWarehouseData();
        echo json_encode($query->result_array());
    }

    public function deleteMaterialEntry($materialEntryID)
    {
        $this->load->model('materialentrymodel');

        $materialEntryData['materialEntryID'] = $materialEntryID;
        $result = $this->materialentrymodel->deleteMaterialEntryData($materialEntryData);
    }
}
