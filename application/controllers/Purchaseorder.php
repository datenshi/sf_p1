<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchaseorder extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addPurchaseOrderView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增採購單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addPurchaseOrderView');
        $this->load->view('footer');
    }

    public function addPurchaseOrder()
    {
        $this->load->model('purchaseordermodel');
        $this->load->model('materialmodel');
        $this->load->model('suppliermodel');
        $this->load->model('packagingmodel');

        $purchaseOrderData['purchaseOrderID'] = $this->input->post('purchaseOrderID');
        $purchaseOrderData['material'] = $this->input->post('material');
        $purchaseOrderData['supplier'] = $this->input->post('supplier');
        $purchaseOrderData['packaging'] = $this->input->post('packaging');
        $purchaseOrderData['purchaseCondition'] = $this->input->post('purchaseCondition');
        $purchaseOrderData['purchasedPackageNumber'] = $this->input->post('purchasedPackageNumber');
        $purchaseOrderData['notEnteredPackageNumber'] = $this->input->post('purchasedPackageNumber');

        $result = $this->purchaseordermodel->insertPurchaseOrderData($purchaseOrderData);

        // Prepare the data for UI display
        // Get material name by material ID
        $listPreparedData = $this->materialmodel->queryMaterialNameByMaterialID($purchaseOrderData['material']);
        $purchaseOrderData['material'] = $listPreparedData['materialName'];
        // Get supplier name by supplier ID
        $listPreparedData = $this->suppliermodel->querySupplierNameBySupplierID($purchaseOrderData['supplier']);
        $purchaseOrderData['supplier'] = $listPreparedData['supplierName'];
        // Get packaging by packaging ID
        $listPreparedData = $this->packagingmodel->queryPackagingByPackagingID($purchaseOrderData['packaging']);
        $purchaseOrderData['packaging'] = $listPreparedData['packaging'];

        if (true == $result) {
            echo json_encode($purchaseOrderData);
        }
    }

    public function queryPurchaseOrderView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢採購單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryPurchaseOrderView');
        $this->load->view('footer');
    }

    public function queryPurchaseOrder($purchaseOrderID)
    {
        $this->load->model('purchaseordermodel');

        if ("false" != $purchaseOrderID) {
            $query = $this->purchaseordermodel->queryPurchaseOrderData($purchaseOrderID);
        }
        else {
            $query = $this->purchaseordermodel->queryPurchaseOrderData(false);
        }
        echo json_encode($query->result_array());
    }

    public function queryPurchaseOrderID()
    {
        $this->load->model('purchaseordermodel');

        $queryData = 'SELECT purchaseOrderID FROM purchaseorder WHERE notEnteredPackageNumber > 0 ORDER BY purchaseOrderID';
        $query = $this->purchaseordermodel->queryPurchaseOrderSpecificColumn($queryData, false);
        echo json_encode($query->result_array());
    }

    public function queryPurchaseOrderIDbyMaterialID($materialID)
    {
        $this->load->model('purchaseordermodel');

        $queryData = 'SELECT purchaseOrderID FROM purchaseorder WHERE material = ' . "\"" . $materialID . "\"";
        $query = $this->purchaseordermodel->queryPurchaseOrderSpecificColumn($queryData, false);
        echo json_encode($query->result_array());
    }

    public function deletePurchaseOrder($purchaseOrderID)
    {
        $this->load->model('purchaseordermodel');

        $purchaseOrderData['purchaseOrderID'] = $purchaseOrderID;
        $result = $this->purchaseordermodel->deletePurchaseOrderData($purchaseOrderData);
    }
}
