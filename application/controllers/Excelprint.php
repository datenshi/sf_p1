<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excelprint extends CI_Controller {

    protected static $viewname = 'excelprint';

    public function index()
    {
        $obj = $_POST['excelBuildData'];
        $db_data_test = self::getDBInfoTest($obj['isConfirmed'],$obj['finishedGoodEntryID'],$obj['model'],$obj['queryfunction']);
        $header = $obj['header'];
        $this->load->helper('print_helper');
        $response = only_print_excel($db_data_test, $header);
        die(json_encode($response));
    }

    public function getDBInfoTest ($isConfirmed, $finishedGoodEntryID, $model, $queryFunction){
        $model_local = $model;
        $query_function = $queryFunction;

        $this->load->model($model_local);

        $query = $this->finishedgoodentrymodel->$query_function($isConfirmed, $finishedGoodEntryID);
        return $query->result_array();
    }

}
