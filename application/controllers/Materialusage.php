<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialusage extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addMaterialUsageView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增使用單位'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addMaterialUsageView');
        $this->load->view('footer');
    }

    public function addMaterialUsage()
    {
        $this->load->model('materialusagemodel');

        $materialUsageData['material'] = $this->input->post('material');
        $materialUsageData['usingDepartment'] = $this->input->post('usingDepartment');

        $result = $this->materialusagemodel->insertMaterialUsageData($materialUsageData);
        if (true == $result) {
            echo json_encode($materialUsageData);
        }
    }

    public function queryMaterialUsageView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢使用單位'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryMaterialUsageView');
        $this->load->view('footer');
    }

    public function queryMaterialUsage()
    {
        $this->load->model('materialusagemodel');

        $query = $this->materialusagemodel->queryMaterialUsageData();
        echo json_encode($query->result_array());
    }

    public function queryMaterialUsageUsingDepartmentByMaterialID($materialID)
    {
        $this->load->model('materialusagemodel');

        $query = $this->materialusagemodel->queryMaterialUsageUsingDepartmentByMaterialIDData($materialID);
        echo json_encode($query->result_array());
    }

    public function deleteMaterialUsage($materialUsageID)
    {
        $this->load->model('materialusagemodel');

        $materialUsageData['materialUsageID'] = $materialUsageID;
        $result = $this->materialusagemodel->deleteMaterialUsageData($materialUsageData);
    }
}
