<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialentry extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome');
            return;
        }*/
    }

    public function addMaterialEntryView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增入料單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addMaterialEntryView');
        $this->load->view('footer');
    }

    public function addMaterialEntry()
    {
        $this->load->model('materialentrymodel');
        $this->load->model('purchaseordermodel');

        $materialEntryData['materialEntryID'] = $this->input->post('materialEntryID');
        $materialEntryData['serialNumber'] = $this->input->post('serialNumber');
        $materialEntryData['purchaseOrder'] = $this->input->post('purchaseOrder');
        $materialEntryData['expectedStoredArea'] = $this->input->post('expectedStoredArea');
        //$materialEntryData['QRCode'] = $this->input->post('QRCode');
        $materialEntryData['expectedStoredDate'] = $this->input->post('expectedStoredDate');
        $materialEntryData['palletNumber'] = $this->input->post('palletNumber');
        $materialEntryData['expectedStoredPackageNumber'] = $this->input->post('expectedStoredPackageNumber');;

        $purchaseOrderData = $this->purchaseordermodel->queryPurchaseOrderForUnitWeightUnitPrice($materialEntryData['purchaseOrder']);
        $materialEntryData['expectedStoredWeight'] = $materialEntryData['expectedStoredPackageNumber'] * $purchaseOrderData['unitWeight'];
        $materialEntryData['expectedStoredMoney'] = $materialEntryData['expectedStoredWeight'] * $purchaseOrderData['unitPrice'];

        $this->purchaseordermodel->updatePurchaseOrderQuantityData(
            $materialEntryData['purchaseOrder'],
            (-$materialEntryData['expectedStoredPackageNumber'])
        );

        $result = $this->materialentrymodel->insertMaterialEntryData($materialEntryData);
        if (true == $result) {
            echo json_encode($materialEntryData);
        }
    }

    public function queryMaterialEntryView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => "查詢已確認入料單"
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryMaterialEntryView');
        $this->load->view('footer');
    }

    public function downloadMaterialEntryExcel($isConfirmed = 0, $materialEntryID = 0, $filterByDate = 0)
    {
        $obj = $this->input->post('excelBuildData');
        $db_data_test = self::getDBInfo($obj['isConfirmed'], $obj['materialEntryID'], $obj['model'], $obj['queryfunction']);
        $header = $obj['header'];
        $this->load->helper('print_helper');
        $response = only_print_excel($db_data_test, $header);
        die(json_encode($response));
    }

    public function getDBInfo($isConfirmed, $materialEntryID, $model, $queryFunction){
        $model_local = $model;
        $query_function = $queryFunction;

        $this->load->model($model_local);

        $query = $this->$model_local->$query_function($isConfirmed, $materialEntryID);
        return $query->result_array();
    }

    public function queryMaterialEntry($isConfirmed, $materialEntryID)
    {
        $this->load->model('materialentrymodel');

        $query = $this->materialentrymodel->queryMaterialEntryData($isConfirmed, $materialEntryID);
        echo json_encode($query->result_array());
    }

    public function queryUnconfirmedMaterialEntryView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => "原料入庫管理"
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryUnconfirmedMaterialEntryView');
        $this->load->view('footer');
    }

    public function deleteMaterialEntry($materialEntryID)
    {
        $this->load->model('materialentrymodel');

        $materialEntryData['materialEntryID'] = $materialEntryID;
        $result = $this->materialentrymodel->deleteMaterialEntryData($materialEntryData);
    }

    public function confirmMaterialEntry($materialEntryID)
    {
        $this->load->model('materialentrymodel');
        $this->load->model('materialmodel');
        $this->load->model('materialinwarehousemodel');

        $materialEntryData = $this->materialentrymodel->queryMaterialEntryDataToUpdateMaterial($materialEntryID);

        $this->materialmodel->updateMaterialQuantityData(
            $materialEntryData['material'],
            $materialEntryData['expectedStoredPackageNumber'],
            $materialEntryData['expectedStoredWeight'],
            $materialEntryData['expectedStoredMoney']
        );

        $this->materialinwarehousemodel->insertMaterialInWarehouseData($materialEntryData);
        $this->materialentrymodel->updateMaterialEntryConfirmationData($materialEntryID);

        echo json_encode($materialEntryData);
    }

    public function updateMaterialEntryPackageNumber(
        $materialEntryID,
        $purchaseOrder,
        $storedArea,
        $storedPackageNumberString,
        $palletNumberString,
        $originalStoredPackageNumberString,
        $originalPalletNumberString
    )
    {
        $this->load->model('materialentrymodel');
        $this->load->model('purchaseordermodel');

        $storedPackageNumber = intval($storedPackageNumberString);
        $palletNumber = intval($palletNumberString);
        $originalStoredPackageNumber = intval($originalStoredPackageNumberString);
        $originalPalletNumber = intval($originalPalletNumberString);

        $purchaseOrderData = $this->purchaseordermodel->queryPurchaseOrderForUnitWeightUnitPrice($purchaseOrder);

        $originalStoredWeight = $originalStoredPackageNumber * $purchaseOrderData['unitWeight'];
        $originalStoredMoney = $originalStoredWeight * $purchaseOrderData['unitPrice'];

        // Restore the notEnteredPackageNumber because the information will be updated later
        $this->purchaseordermodel->updatePurchaseOrderQuantityData(
            $purchaseOrder,
            $originalStoredPackageNumber
        );

        // Restore the related stored information because the information will be updated later
        $result = $this->materialentrymodel->updateMaterialEntryPackageNumberData(
            $materialEntryID,
            null,
            (-$originalPalletNumber),
            (-$originalStoredPackageNumber),
            (-$originalStoredWeight),
            (-$originalStoredMoney)
        );

        $storedWeight = $storedPackageNumber * $purchaseOrderData['unitWeight'];
        $storedMoney = $storedWeight * $purchaseOrderData['unitPrice'];

        // Re-calculate the stored package number into notEnteredPackageNumber of purchase order
        $this->purchaseordermodel->updatePurchaseOrderQuantityData(
            $purchaseOrder,
            (-$storedPackageNumber)
        );

        // Re-fill the related stored information
        $result = $this->materialentrymodel->updateMaterialEntryPackageNumberData(
            $materialEntryID,
            $storedArea,
            $palletNumber,
            $storedPackageNumber,
            $storedWeight,
            $storedMoney
        );
    }

    public function getSerialNumber()
    {
        $this->load->helper('file');
        $this->load->helper('date');
        $this->load->helper('string');

        $dateString = '%Y%m%d';
        $time = time();
        $currentDate = mdate($dateString, $time);
        $fileName = 'EntrySN';
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
        $fileName = 'EntrySN';
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
