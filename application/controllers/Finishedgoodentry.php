<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finishedgoodentry extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addFinishedGoodEntryView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '成品入庫管理'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addFinishedGoodEntryView');
        $this->load->view('footer');
    }

    public function addFinishedGoodEntry()
    {
        $this->load->model('finishedgoodpackagingmodel');
        $this->load->model('finishedgoodinwarehousemodel');

        $finishedGoodEntryData['finishedGoodEntryID'] = $this->input->post('finishedGoodEntryID');
        $finishedGoodEntryData['serialNumber'] = $this->input->post('serialNumber');
        $finishedGoodEntryData['batchNumber'] = $this->input->post('batchNumber');
        $finishedGoodEntryData['product'] = $this->input->post('product');
        $finishedGoodEntryData['packagingID'] = $this->input->post('packagingID');
        $finishedGoodEntryData['status'] = $this->input->post('status');
        $finishedGoodEntryData['storedArea'] = $this->input->post('storedArea');
        // For Taiwan GMT+8
        $currentDateTime = gmdate("Y-m-d H:i:s", (time() + (28800)));
        $finishedGoodEntryData['storedDate'] = $currentDateTime;
        $finishedGoodEntryData['palletNumber'] = $this->input->post('palletNumber');
        $finishedGoodEntryData['storedPackageNumber'] = $this->input->post('storedPackageNumber');

        $queryData = $this->finishedgoodpackagingmodel->queryFinishedGoodPackagingbyPackagingIDData($finishedGoodEntryData['packagingID']);
        $unitWeight = $queryData['unitWeight'];

        $finishedGoodEntryData['storedWeight'] = $finishedGoodEntryData['storedPackageNumber'] * $unitWeight;
        $finishedGoodEntryData['remainingPackageNumber'] = $finishedGoodEntryData['storedPackageNumber'];

        $result = $this->finishedgoodinwarehousemodel->insertFinishedGoodInWarehouseData($finishedGoodEntryData);

        // Make the data for displaying the result
        $finishedGoodEntryData['product'] = $queryData['finishedGoodType'] . '(' . $finishedGoodEntryData['product'] . ')';
        $finishedGoodEntryData['packagingID'] = $queryData['packaging'];
        if (true == $result) {
            echo json_encode($finishedGoodEntryData);
        }
    }

    public function queryFinishedGoodEntryView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '查詢入庫單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryFinishedGoodEntryView');
        $this->load->view('footer');
    }

    public function downExcelFinishedGoodEntry($isConfirmed = 0, $finishedGoodEntryID = 0, $filterByDate = 0)
    {
        $obj = $_POST['excelBuildData'];
        $db_data_test = self::getDBInfo($obj['isConfirmed'],$obj['finishedGoodEntryID'],$obj['model'],$obj['queryfunction']);
        $header = $obj['header'];
        $this->load->helper('print_helper');
        $response = only_print_excel($db_data_test, $header);
        die(json_encode($response));
    }

    public function getDBInfo ($isConfirmed, $finishedGoodEntryID, $model, $queryFunction){
        $model_local = $model;
        $query_function = $queryFunction;

        $this->load->model($model_local);

        $query = $this->finishedgoodentrymodel->$query_function($isConfirmed, $finishedGoodEntryID);
        return $query->result_array();
    }

    public function queryFinishedGoodEntry($isConfirmed, $finishedGoodEntryID)
    {
        $this->load->model('finishedgoodentrymodel');

        $query = $this->finishedgoodentrymodel->queryFinishedGoodEntryData($isConfirmed, $finishedGoodEntryID);
        echo json_encode($query->result_array());
    }

    public function queryUnconfirmedFinishedGoodEntryView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => "成品入庫管理"
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryUnconfirmedFinishedGoodEntryView');
        $this->load->view('footer');
    }

    public function confirmFinishedGoodEntry($finishedGoodEntryID, $storedArea, $storedPalletNumber, $storedPackageNumber)
    {
        $this->load->model('finishedgoodinwarehousemodel');
        $this->load->model('finishedgoodentrymodel');

        $queryData = $this->finishedgoodentrymodel->queryProductPackagingUnitWeightByFinishedGoodEntryIDData($finishedGoodEntryID);
        $finishedGoodEntryData['finishedGoodEntry'] = $finishedGoodEntryID;
        $finishedGoodEntryData['product'] = $queryData['product'];
        $finishedGoodEntryData['packagingID'] = $queryData['packaging'];
        $finishedGoodEntryData['storedArea'] = $storedArea;
        // For Taiwan GMT+8
        $currentDateTime = gmdate("Y-m-d H:i:s", (time() + (28800)));
        $finishedGoodEntryData['storedDate'] = $currentDateTime;
        $finishedGoodEntryData['storedPalletNumber'] = $storedPalletNumber;
        $finishedGoodEntryData['storedPackageNumber'] = $storedPackageNumber;
        $unitWeight = $queryData['unitWeight'];
        $finishedGoodEntryData['storedWeight'] = $finishedGoodEntryData['storedPackageNumber'] * $unitWeight;
        $finishedGoodEntryData['remainingPackageNumber'] = $finishedGoodEntryData['storedPackageNumber'];

        $result = $this->finishedgoodinwarehousemodel->insertFinishedGoodInWarehouseData($finishedGoodEntryData);
        if (true == $result) {
            echo json_encode($finishedGoodEntryData);
        }

        $this->finishedgoodentrymodel->updateFinishedGoodEntryNotEnteredData($finishedGoodEntryID, (-$storedPalletNumber), (-$storedPackageNumber));
    }

    public function deleteFinishedGoodEntry($finishedGoodEntryID)
    {
        $this->load->model('finishedgoodentrymodel');

        $finishedGoodEntryData['finishedGoodEntryID'] = $finishedGoodEntryID;
        $result = $this->finishedgoodentrymodel->deleteFinishedGoodEntryData($finishedGoodEntryData);
    }

    public function getSerialNumber()
    {
        $this->load->helper('file');
        $this->load->helper('date');
        $this->load->helper('string');

        $dateString = '%Y%m%d';
        $time = time();
        $currentDate = mdate($dateString, $time);
        $fileName = 'ProductEntrySN';
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
        $fileName = 'ProductEntrySN';
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
}
