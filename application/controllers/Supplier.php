<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addSupplierView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增供應商'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addSupplierView');
        $this->load->view('footer');
    }

    public function addSupplier()
    {
        $this->load->model('suppliermodel');

        $supplierData['supplierName'] = $this->input->post('supplierName');
        $supplierData['material'] = $this->input->post('material');
        $supplierData['unitPrice'] = $this->input->post('unitPrice');

        $result = $this->suppliermodel->insertSupplierData($supplierData);
        if (true == $result) {
            echo json_encode($supplierData);
        }
    }

    public function querySupplierView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢供應商'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('querySupplierView');
        $this->load->view('footer');
    }

    public function querySupplier()
    {
        $this->load->model('suppliermodel');

        $query = $this->suppliermodel->querySupplierData();
        echo json_encode($query->result_array());
    }

    public function querysSupplierNameIDByMaterialID($materialID)
    {
        $this->load->model('suppliermodel');

        $query = $this->suppliermodel->querysSupplierNameIDByMaterialIDData($materialID);
        echo json_encode($query->result_array());
    }

    public function deleteSupplier($supplierID)
    {
        $this->load->model('suppliermodel');

        $supplierData['supplierID'] = $supplierID;
        $result = $this->suppliermodel->deleteSupplierData($supplierData);
    }
}
