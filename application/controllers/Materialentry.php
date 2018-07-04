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
            'title' => '新增入料'
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
        $materialEntryData['packageNumberOfPallet'] = $this->input->post('packageNumberOfPallet');
        $materialEntryData['palletNumber'] = $this->input->post('palletNumber');
        $materialEntryData['expectedStoredPackageNumber'] = $materialEntryData['packageNumberOfPallet'] * $materialEntryData['palletNumber'];

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
            'title' => "查詢已確認入料"
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryMaterialEntryView');
        $this->load->view('footer');
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
        $packageNumberOfPalletString,
        $palletNumberString,
        $originalPackageNumberOfPalletString,
        $originalPalletNumberString
    )
    {
        $this->load->model('materialentrymodel');
        $this->load->model('purchaseordermodel');

        $packageNumberOfPallet = intval($packageNumberOfPalletString);
        $palletNumber = intval($palletNumberString);
        $originalPackageNumberOfPallet = intval($originalPackageNumberOfPalletString);
        $originalPalletNumber = intval($originalPalletNumberString);

        $purchaseOrderData = $this->purchaseordermodel->queryPurchaseOrderForUnitWeightUnitPrice($purchaseOrder);

        $originalStoredPackageNumber = $originalPackageNumberOfPallet * $originalPalletNumber;
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
            (-$originalPackageNumberOfPallet),
            (-$originalPalletNumber),
            (-$originalStoredPackageNumber),
            (-$originalStoredWeight),
            (-$originalStoredMoney)
        );

        $storedPackageNumber = $packageNumberOfPallet * $palletNumber;
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
            $packageNumberOfPallet,
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
        $this->load->helper('string');

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
