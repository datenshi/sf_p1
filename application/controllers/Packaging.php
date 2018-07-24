<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packaging extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addPackagingView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增包裝'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addPackagingView');
        $this->load->view('footer');
    }

    public function addPackaging()
    {
        $this->load->model('packagingmodel');

        $packagingData['packaging'] = $this->input->post('packaging');
        $packagingData['material'] = $this->input->post('material');
        $packagingData['unitWeight'] = $this->input->post('unitWeight');

        $result = $this->packagingmodel->insertPackagingData($packagingData);
        if (true == $result) {
            echo json_encode($packagingData);
        }
    }

    public function queryPackagingView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢包裝'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryPackagingView');
        $this->load->view('footer');
    }

    public function queryPackaging()
    {
        $this->load->model('packagingmodel');

        $query = $this->packagingmodel->queryPackagingData();
        echo json_encode($query->result_array());
    }

    public function queryPackagingbyMaterialID($materialID)
    {
        $this->load->model('packagingmodel');

        $queryData = 'SELECT packagingID, packaging FROM packaging WHERE material = ' . "\"" . $materialID . "\"";
        $query = $this->packagingmodel->queryPackagingSpecificColumn($queryData, false);
        echo json_encode($query->result_array());
    }

    public function queryPackagingUnitWeightbyMaterialID($materialID)
    {
        $this->load->model('packagingmodel');

        $query = $this->packagingmodel->queryPackagingUnitWeightbyMaterialIDData($materialID);
        echo json_encode($query->result_array());
    }

    public function deletePackaging($packagingID)
    {
        $this->load->model('packagingmodel');

        $packagingData['packagingID'] = $packagingID;
        $result = $this->packagingmodel->deletePackagingData($packagingData);
    }
}
