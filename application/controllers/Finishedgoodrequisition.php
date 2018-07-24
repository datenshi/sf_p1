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
            'title' => '新增領貨'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addFinishedGoodRequisitionView');
        $this->load->view('footer');
    }

    public function addFinishedGoodRequisition(
        $storedFinishedGoodID,
        $finishedGoodRequisitionID,
        $requisitioningDepartment,
        $requisitioningMember,
        $requisitionedPackageNumber
    )
    {
        $this->load->model('finishedgoodrequisitionmodel');
        $this->load->model('finishedgoodinwarehousemodel');

        $finishedGoodRequisitionData['finishedGoodRequisitionID'] = $finishedGoodRequisitionID;
        $finishedGoodRequisitionData['productInWarehouseID'] = $storedFinishedGoodID;

        $queryData = $this->finishedgoodinwarehousemodel->queryFinishedGoodInWarehouseDataByStoredFinishedGoodID($storedFinishedGoodID);
        $finishedGoodRequisitionData['product'] = $queryData['product'];
        $finishedGoodRequisitionData['packagingID'] = $queryData['packagingID'];
        $finishedGoodRequisitionData['storedArea'] = $queryData['storedArea'];
        // For Taiwan GMT+8
        $currentDateTime = gmdate("Y-m-d H:i:s", (time() + (28800)));
        $finishedGoodRequisitionData['requisitioningDate'] = $currentDateTime;
        $finishedGoodRequisitionData['requisitioningDepartment'] = urldecode($requisitioningDepartment);
        $finishedGoodRequisitionData['requisitioningMember'] = urldecode($requisitioningMember);
        $finishedGoodRequisitionData['requisitionedPackageNumber'] = $requisitionedPackageNumber;

        $result = $this->finishedgoodrequisitionmodel->insertFinishedGoodRequisitionData($finishedGoodRequisitionData);

        $this->finishedgoodinwarehousemodel->updateRemainingPackageNumberData($storedFinishedGoodID, (-$requisitionedPackageNumber));

        $finishedGoodRequisitionData['product'] = $queryData['finishedGoodType'] . "(" . $queryData['product'] . ")";
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

    public function downloadFinishedGoodRequisitionExcel($filterByDate = 0)
    {
        $obj = $this->input->post('excelBuildData');
        $db_data_test = self::getDBInfo($obj['model'],$obj['queryfunction']);
        $header = $obj['header'];
        $this->load->helper('print_helper');
        $response = only_print_excel($db_data_test, $header);
        die(json_encode($response));
    }

    public function getDBInfo($model, $queryFunction){
        $model_local = $model;
        $query_function = $queryFunction;

        $this->load->model($model_local);

        $query = $this->$model_local->$query_function();
        return $query->result_array();
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

    public function getSerialNumber()
    {
        $this->load->helper('file');
        $this->load->helper('date');
        $this->load->helper('string');

        $dateString = '%Y%m%d';
        $time = time();
        $currentDate = mdate($dateString, $time);
        $fileName = 'ProductRequisitionSN';
        if (TRUE == file_exists($fileName)) {
            $currentSerialNumber = read_file($fileName);
            if (FALSE == strstr($currentSerialNumber, $currentDate)) {
                $newSerialNumber = $currentDate . "001";
                write_file($fileName, $newSerialNumber);

                echo $newSerialNumber;
            }
            else {
                echo $currentSerialNumber;
            }
        }
        else {
            $newSerialNumber = $currentDate . "001";
            write_file($fileName, $newSerialNumber);

            echo $newSerialNumber;
        }
    }

    public function increaseSerialNumber()
    {
        $this->load->helper('file');
        $this->load->helper('date');

        $dateString = '%Y%m%d';
        $time = time();
        $currentDate = mdate($dateString, $time);
        $fileName = 'ProductRequisitionSN';
        if (TRUE == file_exists($fileName)) {
            $currentSerialNumber = read_file($fileName);
            if (FALSE == strstr($currentSerialNumber, $currentDate)) {
                $newSerialNumber = $currentDate . "001";
            }
            else {
                $number = str_replace($currentDate, '', $currentSerialNumber);
                $number = (int)$number + 1;
                $length = strlen($number);
                if (3 >= $length) {
                    for($i = 0; $i < (3 - $length); $i++)
                    {
                        $number = '0' . $number;
                    }
                }
                else {
                    $number = '0' . $number;
                }
                $newSerialNumber = $currentDate . $number;
            }
            write_file($fileName, $newSerialNumber);
        }
        else {
            $newSerialNumber = $currentDate . "001";
            write_file($fileName, $newSerialNumber);
        }
    }

    public function deleteFinishedGoodRequisition($finishedGoodRequisitionID)
    {
        $this->load->model('finishedgoodrequisitionmodel');

        $finishedGoodRequisitionData['finishedGoodRequisitionID'] = $finishedGoodRequisitionID;
        $result = $this->finishedgoodrequisitionmodel->deleteFinishedGoodRequisitionData($finishedGoodRequisitionData);
    }
}
