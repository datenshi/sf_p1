<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodoutwarehouse extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addFinishedGoodOutWarehouseView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '新增成品出庫'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addFinishedGoodOutWarehouseView');
        $this->load->view('footer');
    }

    public function addFinishedGoodOutWarehouse()
    {
        $this->load->model('finishedgoodoutwarehousemodel');
        $this->load->model('finishedgoodrequisitionmodel');
        $this->load->model('finishedgoodinwarehousemodel');

        $finishedGoodOutWarehouseData['inWarehouseID'] = $this->input->post('inWarehouseID');
        $finishedGoodOutWarehouseData['finishedGoodRequisition'] = $this->input->post('finishedGoodRequisition');
        $finishedGoodOutWarehouseData['takenOutArea'] = $this->input->post('takenOutArea');
        // For Taiwan GMT+8
        $currentDateTime = gmdate("Y-m-d H:i:s", (time() + (28800)));
        $finishedGoodOutWarehouseData['takenOutDate'] = $currentDateTime;
        $finishedGoodOutWarehouseData['takingOutDepartment'] = $this->input->post('takingOutDepartment');
        $finishedGoodOutWarehouseData['takingOutMember'] = $this->input->post('takingOutMember');
        $finishedGoodOutWarehouseData['takenOutPackageNumber'] = $this->input->post('takenOutPackageNumber');

        $result = $this->finishedgoodoutwarehousemodel->insertFinishedGoodOutWarehouseData($finishedGoodOutWarehouseData);

        if (true == $result) {
            echo json_encode($finishedGoodOutWarehouseData);
        }

        // Minus notOutPackageNumber in finished good requisition table
        $this->finishedgoodrequisitionmodel->updateNotOutPackageNumberData($finishedGoodOutWarehouseData['finishedGoodRequisition'], (-$finishedGoodOutWarehouseData['takenOutPackageNumber']));

        // Minus remaining package number in warehouse by storedFinishedGoodID
        $this->finishedgoodinwarehousemodel->updateRemainingPackageNumberData($finishedGoodOutWarehouseData['inWarehouseID'], (-$finishedGoodOutWarehouseData['takenOutPackageNumber']));
    }

    public function queryFinishedGoodOutWarehouseView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '查詢成品出庫'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryFinishedGoodOutWarehouseView');
        $this->load->view('footer');
    }

    public function queryFinishedGoodOutWarehouse()
    {
        $this->load->model('finishedgoodoutwarehousemodel');

        $query = $this->finishedgoodoutwarehousemodel->queryFinishedGoodOutWarehouseData();
        echo json_encode($query->result_array());
    }

    public function queryFinishedGoodInWarehouseView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '查詢成品庫存'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryFinishedGoodInWarehouseView');
        $this->load->view('footer');
    }

    public function queryFinishedGoodInWarehouse()
    {
        $this->load->model('finishedgoodinwarehousemodel');

        $query = $this->finishedgoodinwarehousemodel->queryFinishedGoodInWarehouseData();
        echo json_encode($query->result_array());
    }

    public function queryProductInWarehouse()
    {
        $this->load->model('finishedgoodinwarehousemodel');

        $query = $this->finishedgoodinwarehousemodel->queryProductInWarehouseData();
        echo json_encode($query->result_array());
    }

    public function queryPackagingInWarehouseByProductID($product)
    {
        $this->load->model('finishedgoodinwarehousemodel');

        $query = $this->finishedgoodinwarehousemodel->queryPackagingInWarehouseByProductIDData($product);
        echo json_encode($query->result_array());
    }

    public function queryPackagNumberInWarehouseByProductPackagingID($product, $packaging)
    {
        $this->load->model('finishedgoodinwarehousemodel');

        $query = $this->finishedgoodinwarehousemodel->queryPackagNumberInWarehouseByProductPackagingIDData($product, $packaging);
        echo json_encode($query->result_array());
    }
}
