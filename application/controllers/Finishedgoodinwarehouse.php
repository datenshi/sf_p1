<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodinwarehouse extends CI_Controller {

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

    public function downExcelFinishedGoodInWarehouseView()
    {
        
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

    public function queryAreaInWarehouseByProductPackagingID($product, $packaging)
    {
        $this->load->model('finishedgoodinwarehousemodel');

        $query = $this->finishedgoodinwarehousemodel->queryAreaInWarehouseByProductPackagingIDData($product, $packaging);
        echo json_encode($query->result_array());
    }

    public function queryFinishedGoodInWarehouseByProductPackagingIDArea($product, $packaging, $area)
    {
        $this->load->model('finishedgoodinwarehousemodel');

        $query = $this->finishedgoodinwarehousemodel->queryFinishedGoodInWarehouseByProductPackagingIDAreaData($product, $packaging, $area);
        echo json_encode($query->result_array());
    }
}
