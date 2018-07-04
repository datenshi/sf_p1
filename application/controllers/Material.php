<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome');
            return;
        }*/
    }

    public function addMaterialView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增原料'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addMaterialView');
        $this->load->view('footer');
    }

    public function addMaterial()
    {
        $this->load->model('materialmodel');

        $materialData['materialID'] = $this->input->post('materialID');
        $materialData['materialName'] = $this->input->post('materialName');

        $result = $this->materialmodel->insertMaterialData($materialData);
        if (true == $result) {
            echo json_encode($materialData);
        }
    }

    public function queryMaterialView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢原料'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryMaterialView');
        $this->load->view('footer');
    }

    public function queryMaterial()
    {
        $this->load->model('materialmodel');

        $query = $this->materialmodel->queryMaterialData();
        echo json_encode($query->result_array());
    }

    public function queryMaterialNameWithID()
    {
        $this->load->model('materialmodel');

        $queryData = 'SELECT materialID, materialName FROM material';
        $query = $this->materialmodel->queryMaterialSpecificColumn($queryData, false);
        echo json_encode($query->result_array());
    }

    public function deleteMaterial($materialID)
    {
        $this->load->model('materialmodel');

        $materialData['materialID'] = $materialID;
        $result = $this->materialmodel->deleteMaterialData($materialData);
    }
}
