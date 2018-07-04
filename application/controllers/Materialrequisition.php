<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialrequisition extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome');
            return;
        }*/
    }

    public function addMaterialRequisitionView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增領料單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addMaterialRequisitionView');
        $this->load->view('footer');
    }

    public function addMaterialRequisition()
    {
        $this->load->model('materialrequisitionmodel');
        $this->load->model('materialinwarehousemodel');
        $this->load->model('materialusagemodel');

        $materialRequisitionData['materialRequisitionID'] = $this->input->post('materialRequisitionID');
        $materialRequisitionData['material'] = $this->input->post('material');
        $materialRequisitionData['supplier'] = $this->input->post('supplier');
        $materialRequisitionData['packaging'] = $this->input->post('packaging');
        $materialRequisitionData['requisitioningDate'] = $this->input->post('requisitioningDate');
        $materialRequisitionData['requisitioningDepartment'] = $this->input->post('requisitioningDepartment');
        $materialRequisitionData['requisitioningMember'] = $this->input->post('requisitioningMember');
        $materialRequisitionData['requisitionedPackageNumber'] = $this->input->post('requisitionedPackageNumber');
        $materialRequisitionData['notOutPackageNumber'] = $this->input->post('requisitionedPackageNumber');

        $result = $this->materialrequisitionmodel->insertMaterialRequisitionData($materialRequisitionData);

        $queryResult = $this->materialinwarehousemodel->queryMaterialInWarehouseDataByMaterialSupplierPackagingIDData($materialRequisitionData['material'], $materialRequisitionData['supplier'], $materialRequisitionData['packaging']);

        $queryData = $queryResult->row_array();
        $materialRequisitionData['material'] = $queryData['materialName'];
        $materialRequisitionData['supplier'] = $queryData['supplierName'];
        $materialRequisitionData['packaging'] = $queryData['packaging'];

        $queryData = $this->materialusagemodel->queryUsingDepartmentByMaterialUsageID($materialRequisitionData['requisitioningDepartment']);
        $materialRequisitionData['requisitioningDepartment'] = $queryData['usingDepartment'];
        if (true == $result) {
            echo json_encode($materialRequisitionData);
        }
    }

    public function queryMaterialRequisitionView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢領料單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryMaterialRequisitionView');
        $this->load->view('footer');
    }

    public function queryMaterialRequisition()
    {
        $this->load->model('materialrequisitionmodel');

        $query = $this->materialrequisitionmodel->queryMaterialRequisitionData();
        echo json_encode($query->result_array());
    }

    public function queryMaterialRequisitionID()
    {
        $this->load->model('materialrequisitionmodel');

        $query = $this->materialrequisitionmodel->queryMaterialRequisitionIDData();
        echo json_encode($query->result_array());
    }

    public function queryMaterialRequisitionByRequisitionID($materialRequisitionID)
    {
        $this->load->model('materialrequisitionmodel');

        $query = $this->materialrequisitionmodel->queryMaterialRequisitionByRequisitionIDData($materialRequisitionID);
        echo json_encode($query->result_array());
    }

    public function deleteMaterialRequisition($materialRequisitionID)
    {
        $this->load->model('materialrequisitionmodel');

        $materialRequisitionData['materialRequisitionID'] = $materialRequisitionID;
        $result = $this->materialrequisitionmodel->deleteMaterialRequisitionData($materialRequisitionData);
    }
}
