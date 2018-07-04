<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if ( ! function_exists('print_excel')) {
    function print_excel($db_data_test, $header) {
        array_unshift($db_data_test, $header);

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getActiveSheet()
            ->fromArray(
                $db_data_test,  // The data to set
                NULL,           // Array values with this value will not be set
                'C3'            // Top left coordinate of the worksheet range where
                                // we want to set these values (default is A1)
        );

        $writer = new Xlsx($spreadsheet);
        $date = date("Y.m.d");
        $filename = 'Exceltest.'.$date.'.xlsx';
        $writer->save($filename);

        //echo "Excel file created and exported to the hardrive/n";
        //echo "Procceding to download the file ...";
        download_xlsx ($filename);
    }
}

if ( ! function_exists('download_xlsx')) {
    function download_xlsx($filename) {
        //$file = 'Exceltest.xlsx';

        if(!file_exists($filename)){ // file does not exist
            die('file not found');
        } else {
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: binary");

            // read the file from disk
            readfile($filename);
        }
    }
}