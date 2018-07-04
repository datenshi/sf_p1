<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodrequisition extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addFinishedGoodRequisitionView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '新增領貨單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addFinishedGoodRequisitionView');
        $this->load->view('footer');
    }

    public function addFinishedGoodRequisition()
    {
        $this->load->model('finishedgoodrequisitionmodel');
        $this->load->model('finishedgoodpackagingmodel');

        $finishedGoodRequisitionData['finishedGoodRequisitionID'] = $this->input->post('finishedGoodRequisitionID');
        $finishedGoodRequisitionData['product'] = $this->input->post('product');
        $finishedGoodRequisitionData['packagingID'] = $this->input->post('packagingID');
        $finishedGoodRequisitionData['requisitioningDate'] = $this->input->post('requisitioningDate');
        $finishedGoodRequisitionData['requisitioningDepartment'] = $this->input->post('requisitioningDepartment');
        $finishedGoodRequisitionData['requisitioningMember'] = $this->input->post('requisitioningMember');
        $finishedGoodRequisitionData['requisitionedPackageNumber'] = $this->input->post('requisitionedPackageNumber');
        $finishedGoodRequisitionData['notOutPackageNumber'] = $finishedGoodRequisitionData['requisitionedPackageNumber'];

        $result = $this->finishedgoodrequisitionmodel->insertFinishedGoodRequisitionData($finishedGoodRequisitionData);

        $queryData = $this->finishedgoodpackagingmodel->queryFinishedGoodPackagingByPackagingID($finishedGoodRequisitionData['packagingID']);
        $finishedGoodRequisitionData['packagingID'] = $queryData['packaging'];
        if (true == $result) {
            echo json_encode($finishedGoodRequisitionData);
        }
    }

    public function queryFinishedGoodRequisitionView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '查詢領貨單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryFinishedGoodRequisitionView');
        $this->load->view('footer');
    }

    public function queryFinishedGoodRequisition()
    {
        $this->load->model('finishedgoodrequisitionmodel');

        $query = $this->finishedgoodrequisitionmodel->queryFinishedGoodRequisitionData();
        echo json_encode($query->result_array());
    }

    public function queryFinishedGoodRequisitionID()
    {
        $this->load->model('finishedgoodrequisitionmodel');

        $query = $this->finishedgoodrequisitionmodel->queryFinishedGoodRequisitionIDData();
        echo json_encode($query->result_array());
    }

    public function queryFinishedGoodRequisitionByRequisitionID($requisitionID)
    {
        $this->load->model('finishedgoodrequisitionmodel');

        $query = $this->finishedgoodrequisitionmodel->queryFinishedGoodRequisitionByRequisitionIDData($requisitionID);
        echo json_encode($query->result_array());
    }

    public function deleteFinishedGoodRequisition($finishedGoodRequisitionID)
    {
        $this->load->model('finishedgoodrequisitionmodel');

        $finishedGoodRequisitionData['finishedGoodRequisitionID'] = $finishedGoodRequisitionID;
        $result = $this->finishedgoodrequisitionmodel->deleteFinishedGoodRequisitionData($finishedGoodRequisitionData);
    }
}
