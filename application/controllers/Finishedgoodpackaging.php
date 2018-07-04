<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodpackaging extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addFinishedGoodPackagingView()
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
        $this->load->view('addFinishedGoodPackagingView');
        $this->load->view('footer');
    }

    public function addFinishedGoodPackaging()
    {
        $this->load->model('finishedgoodpackagingmodel');

        $finishedGoodPackagingData['product'] = $this->input->post('product');
        $finishedGoodPackagingData['packaging'] = $this->input->post('packaging');
        $finishedGoodPackagingData['unitWeight'] = $this->input->post('unitWeight');
        $finishedGoodPackagingData['packageNumberOfPallet'] = $this->input->post('packageNumberOfPallet');

        $result = $this->finishedgoodpackagingmodel->insertFinishedGoodPackagingData($finishedGoodPackagingData);
        if (true == $result) {
            echo json_encode($finishedGoodPackagingData);
        }
    }

    public function queryFinishedGoodPackagingView()
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
        $this->load->view('queryFinishedGoodPackagingView');
        $this->load->view('footer');
    }

    public function queryFinishedGoodPackaging()
    {
        $this->load->model('finishedgoodpackagingmodel');

        $query = $this->finishedgoodpackagingmodel->queryFinishedGoodPackagingData();
        echo json_encode($query->result_array());
    }

    public function queryFinishedGoodPackagingbyProductID($productID)
    {
        $this->load->model('finishedgoodpackagingmodel');

        $query = $this->finishedgoodpackagingmodel->queryFinishedGoodPackagingbyProductIDData($productID);
        echo json_encode($query->result_array());
    }

    public function deleteFinishedGoodPackaging($packagingID)
    {
        $this->load->model('packagingmodel');

        $packagingData['packagingID'] = $packagingID;
        $result = $this->packagingmodel->deleteFinishedGoodPackagingData($packagingData);
    }
}
