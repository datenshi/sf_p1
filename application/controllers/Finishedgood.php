<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgood extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addFinishedGoodView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '新增成品'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addFinishedGoodView');
        $this->load->view('footer');
    }

    public function addFinishedGood()
    {
        $this->load->model('finishedgoodmodel');

        $finishedGoodData['finishedGoodID'] = $this->input->post('finishedGoodID');
        $finishedGoodData['finishedGoodType'] = $this->input->post('finishedGoodType');

        $result = $this->finishedgoodmodel->insertFinishedGoodData($finishedGoodData);
        if (true == $result) {
            echo json_encode($finishedGoodData);
        }
    }

    public function queryFinishedGoodView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '查詢成品'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryFinishedGoodView');
        $this->load->view('footer');
    }

    public function queryFinishedGood()
    {
        $this->load->model('finishedgoodmodel');

        $query = $this->finishedgoodmodel->queryFinishedGoodData();
        echo json_encode($query->result_array());
    }

    public function queryFinishedGoodIDType()
    {
        $this->load->model('finishedgoodmodel');

        $query = $this->finishedgoodmodel->queryFinishedGoodIDTypeData();
        echo json_encode($query->result_array());
    }
}
