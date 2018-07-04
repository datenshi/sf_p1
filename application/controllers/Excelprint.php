<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excelprint extends CI_Controller {

    protected static $viewname = 'excelprint';

    public function index()
    {

        $db_data_test = self::getDBInfoTest(0,0);
        $header = ["成品入庫單編號", "倉儲流水號", "成品代號", "成品種類", "包裝", "單位重量", "每棧板的成品數量", "狀態", "儲放區域", "入庫日期", "棧板數", "入庫數量", "入庫重量", "待入庫棧板數", "待入庫數量"];

        $this->load->helper('print_helper');
        print_excel($db_data_test, $header);
    }

    public function getDBInfoTest ($isConfirmed, $finishedGoodEntryID){
        $model = 'finishedgoodentrymodel';
        $query_function = 'queryFinishedGoodEntryData';

        $this->load->model($model);

        $query = $this->finishedgoodentrymodel->$query_function($isConfirmed, $finishedGoodEntryID);
        return $query->result_array();
    }

}
