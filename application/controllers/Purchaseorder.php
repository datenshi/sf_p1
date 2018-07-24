<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchaseorder extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addPurchaseOrderView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增採購單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addPurchaseOrderView');
        $this->load->view('footer');
    }

    public function addPurchaseOrder()
    {
        $this->load->model('purchaseordermodel');
        $this->load->model('materialmodel');
        $this->load->model('suppliermodel');
        $this->load->model('packagingmodel');

        $purchaseOrderData['purchaseOrderID'] = $this->input->post('purchaseOrderID');
        $purchaseOrderData['material'] = $this->input->post('material');
        $purchaseOrderData['supplier'] = $this->input->post('supplier');
        $purchaseOrderData['packaging'] = $this->input->post('packaging');
        $purchaseOrderData['purchaseCondition'] = $this->input->post('purchaseCondition');
        // For Taiwan GMT+8
        $currentDateTime = gmdate("Y-m-d H:i:s", (time() + (28800)));
        $purchaseOrderData['issueDate'] = $currentDateTime;
        $purchaseOrderData['purchasedPackageNumber'] = $this->input->post('purchasedPackageNumber');
        $purchaseOrderData['notEnteredPackageNumber'] = $this->input->post('purchasedPackageNumber');

        $result = $this->purchaseordermodel->insertPurchaseOrderData($purchaseOrderData);

        // Prepare the data for UI display
        // Get material name by material ID
        $listPreparedData = $this->materialmodel->queryMaterialNameByMaterialID($purchaseOrderData['material']);
        $purchaseOrderData['material'] = $listPreparedData['materialName'] . "(" . $purchaseOrderData['material'] . ")";
        // Get supplier name by supplier ID
        $listPreparedData = $this->suppliermodel->querySupplierNameBySupplierID($purchaseOrderData['supplier']);
        $purchaseOrderData['supplier'] = $listPreparedData['supplierName'];
        // Get packaging by packaging ID
        $listPreparedData = $this->packagingmodel->queryPackagingByPackagingID($purchaseOrderData['packaging']);
        $purchaseOrderData['packaging'] = $listPreparedData['packaging'];

        if (true == $result) {
            echo json_encode($purchaseOrderData);
        }
    }

    public function queryPurchaseOrderView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢採購單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryPurchaseOrderView');
        $this->load->view('footer');
    }

    public function downloadPurchaseOrderExcel($purchaseOrderID = 0, $filterByDate = 0)
    {
        $obj = $this->input->post('excelBuildData');
        $db_data_test = self::getDBInfo($obj['purchaseOrderID'],$obj['model'],$obj['queryfunction']);
        $header = $obj['header'];
        $this->load->helper('print_helper');
        $response = only_print_excel($db_data_test, $header);
        die(json_encode($response));
    }

    public function getDBInfo($purchaseOrderID, $model, $queryFunction){
        $model_local = $model;
        $query_function = $queryFunction;

        $this->load->model($model_local);

        $query = $this->$model_local->$query_function($purchaseOrderID);
        return $query->result_array();
    }

    public function queryPurchaseOrder($purchaseOrderID)
    {
        $this->load->model('purchaseordermodel');

        if ("false" != $purchaseOrderID) {
            $query = $this->purchaseordermodel->queryPurchaseOrderData($purchaseOrderID);
        }
        else {
            $query = $this->purchaseordermodel->queryPurchaseOrderData(false);
        }
        echo json_encode($query->result_array());
    }

    public function queryPurchaseOrderID()
    {
        $this->load->model('purchaseordermodel');

        $queryData = 'SELECT purchaseOrderID FROM purchaseorder WHERE notEnteredPackageNumber > 0 ORDER BY purchaseOrderID';
        $query = $this->purchaseordermodel->queryPurchaseOrderSpecificColumn($queryData, false);
        echo json_encode($query->result_array());
    }

    public function queryPurchaseOrderIDbyMaterialID($materialID)
    {
        $this->load->model('purchaseordermodel');

        $queryData = 'SELECT purchaseOrderID FROM purchaseorder WHERE material = ' . "\"" . $materialID . "\"";
        $query = $this->purchaseordermodel->queryPurchaseOrderSpecificColumn($queryData, false);
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
        $fileName = 'PurchaseSN';
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
        $fileName = 'PurchaseSN';
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

    public function deletePurchaseOrder($purchaseOrderID)
    {
        $this->load->model('purchaseordermodel');

        $purchaseOrderData['purchaseOrderID'] = $purchaseOrderID;
        $result = $this->purchaseordermodel->deletePurchaseOrderData($purchaseOrderData);
    }
}
