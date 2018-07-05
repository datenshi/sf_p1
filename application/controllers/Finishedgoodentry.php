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
            'title' => '新增入庫單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addFinishedGoodEntryView');
        $this->load->view('footer');
    }

    public function addFinishedGoodEntry()
    {
        $this->load->model('finishedgoodpackagingmodel');
        $this->load->model('finishedgoodentrymodel');

        $finishedGoodEntryData['finishedGoodEntryID'] = $this->input->post('finishedGoodEntryID');
        $finishedGoodEntryData['serialNumber'] = $this->input->post('serialNumber');
        $finishedGoodEntryData['product'] = $this->input->post('product');
        $finishedGoodEntryData['packaging'] = $this->input->post('packaging');
        $finishedGoodEntryData['status'] = $this->input->post('status');
        $finishedGoodEntryData['expectedStoredArea'] = $this->input->post('expectedStoredArea');
        $finishedGoodEntryData['expectedStoredDate'] = $this->input->post('expectedStoredDate');
        $finishedGoodEntryData['palletNumber'] = $this->input->post('palletNumber');
        $finishedGoodEntryData['expectedStoredPackageNumber'] = $this->input->post('expectedStoredPackageNumber');

        $queryData = $this->finishedgoodpackagingmodel->queryFinishedGoodPackagingbyPackagingIDData($finishedGoodEntryData['packaging']);
        $unitWeight = $queryData['unitWeight'];

        $finishedGoodEntryData['expectedStoredWeight'] = $finishedGoodEntryData['expectedStoredPackageNumber'] * $unitWeight;
        $finishedGoodEntryData['notEnteredPalletNumber'] = $finishedGoodEntryData['palletNumber'];
        $finishedGoodEntryData['notEnteredPackageNumber'] = $finishedGoodEntryData['expectedStoredPackageNumber'];

        $result = $this->finishedgoodentrymodel->insertFinishedGoodEntryData($finishedGoodEntryData);

        // Make the data for displaying the result
        $finishedGoodEntryData['product'] = $queryData['finishedGoodType'] . '(' . $finishedGoodEntryData['product'] . ')';
        $finishedGoodEntryData['packaging'] = $queryData['packaging'] . '(' . $unitWeight . '/' . $queryData['packageNumberOfPallet'] . ')';
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

    public function downExcelFinishedGoodEntry($isConfirmed = 0, $finishedGoodEntryID = 0)
    {
        $model = 'finishedgoodentrymodel';
        $query_function = 'queryFinishedGoodEntryData';

        $this->load->model($model);

        $query = $this->finishedgoodentrymodel->$query_function($isConfirmed, $finishedGoodEntryID);
         
        $db_data_test = $query->result_array();
        $header = ["成品入庫單編號", "倉儲流水號", "成品代號", "成品種類", "包裝", "單位重量", "每棧板的成品數量", "狀態", "儲放區域", "入庫日期", "棧板數", "入庫數量", "入庫重量", "待入庫棧板數", "待入庫數量"];

        $this->load->helper('print_helper');
        print_excel($db_data_test, $header);
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
        $this->load->helper('string');

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
                $newSerialNumber = increment_string($currentSerialNumber, '');
            }
            write_file($fileName, $newSerialNumber);
        }
        else {
            $newSerialNumber = $currentDate . "001";
            write_file($fileName, $newSerialNumber);
        }
    }
}
