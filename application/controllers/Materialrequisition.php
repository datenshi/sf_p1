<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materialrequisition extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome');
            return;
        }*/
    }

    public function addMaterialRequisitionView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增領料'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addMaterialRequisitionView');
        $this->load->view('footer');
    }

    public function addMaterialRequisition(
        $storedMaterialID,
        $materialRequisitionID,
        $requisitioningDepartment,
        $requisitioningMember,
        $requisitionedPackageNumber
    )
    {
        $this->load->model('materialrequisitionmodel');
        $this->load->model('materialinwarehousemodel');
        $this->load->model('materialusagemodel');

        $materialRequisitionData['materialRequisitionID'] = $materialRequisitionID;
        $materialRequisitionData['materialInWarehouseID'] = $storedMaterialID;

        $queryData = $this->materialinwarehousemodel->queryMaterialInWarehouseDataByStoredMaterialID($storedMaterialID);
        $materialRequisitionData['material'] = $queryData['material'];
        $materialRequisitionData['supplier'] = $queryData['supplier'];
        $materialRequisitionData['packaging'] = $queryData['packagingID'];
        $materialRequisitionData['storedArea'] = $queryData['storedArea'];
        // For Taiwan GMT+8
        $currentDateTime = gmdate("Y-m-d H:i:s", (time() + (28800)));
        $materialRequisitionData['requisitioningDate'] = $currentDateTime;
        $materialRequisitionData['requisitioningDepartment'] = urldecode($requisitioningDepartment);
        $materialRequisitionData['requisitioningMember'] = urldecode($requisitioningMember);
        $materialRequisitionData['requisitionedPackageNumber'] = $requisitionedPackageNumber;

        $result = $this->materialrequisitionmodel->insertMaterialRequisitionData($materialRequisitionData);

        $this->materialinwarehousemodel->updateRemainingPackageNumberData($storedMaterialID, (-$requisitionedPackageNumber));

        $materialRequisitionData['material'] = $queryData['materialName'] . "(" . $queryData['material'] . ")";
        $materialRequisitionData['supplier'] = $queryData['supplierName'];
        $materialRequisitionData['packaging'] = $queryData['packaging'];
        if (true == $result) {
            echo json_encode($materialRequisitionData);
        }
    }

    public function queryMaterialRequisitionView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢領料單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryMaterialRequisitionView');
        $this->load->view('footer');
    }

    public function downloadMaterialRequisitionExcel($filterByDate = 0)
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

    public function queryMaterialRequisition()
    {
        $this->load->model('materialrequisitionmodel');

        $query = $this->materialrequisitionmodel->queryMaterialRequisitionData();
        echo json_encode($query->result_array());
    }

    public function queryMaterialRequisitionID()
    {
        $this->load->model('materialrequisitionmodel');

        $query = $this->materialrequisitionmodel->queryMaterialRequisitionIDData();
        echo json_encode($query->result_array());
    }

    public function queryMaterialRequisitionByRequisitionID($materialRequisitionID)
    {
        $this->load->model('materialrequisitionmodel');

        $query = $this->materialrequisitionmodel->queryMaterialRequisitionByRequisitionIDData($materialRequisitionID);
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
        $fileName = 'RequisitionSN';
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
        $fileName = 'RequisitionSN';
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

    public function deleteMaterialRequisition($materialRequisitionID)
    {
        $this->load->model('materialrequisitionmodel');

        $materialRequisitionData['materialRequisitionID'] = $materialRequisitionID;
        $result = $this->materialrequisitionmodel->deleteMaterialRequisitionData($materialRequisitionData);
    }
}
