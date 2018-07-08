<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ob_start();
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

        download_xlsx4($filename);
    }
}

if ( ! function_exists('download_xlsx')) {
    function download_xlsx($filename) {
        //$file = 'Exceltest.xlsx';
        if(!file_exists($filename)){ // file does not exist
            die('file not found');
        } else {
            header("Cache-Control: private");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=".$filename);
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Transfer-Encoding: binary");

            // read the file from disk
            readfile($filename);
            exit;
        }
    }
}

if ( ! function_exists('download_xlsx2')) {
    function download_xlsx2($filename) {
        //$file = 'Exceltest.xlsx';
        if(!file_exists($filename)){ // file does not exist
            die('file not found');
        } else {
           
            header("location: http://sf.test/".$filename);
            die();
        }
    }
}

if ( ! function_exists('download_xlsx3')) {
    function download_xlsx3($filename) {
        //$file = 'Exceltest.xlsx';
        if(!file_exists($filename)){ // file does not exist
            die('file not found');
        } else {
           
            if (!send_file($filename)) {
                log_message("ERROR", "FILE TRANSFER FAILED");
                die ("file transfer failed");
             
                // either the file transfer was incomplete
                // or the file was not found
                
             
            } else {
                log_message("ERROR", "FILE TRANSFER SUCCESS");
                // the download was a success
                // log, or do whatever else
            }
        }
    }
}

if ( ! function_exists('send_file')) {
    function send_file($name) {
      ob_end_clean();
      $path = $name;
      if (!is_file($path) or connection_status()!=0) return(FALSE);
      header("Cache-Control: no-store, no-cache, must-revalidate");
      header("Cache-Control: post-check=0, pre-check=0", FALSE);
      header("Pragma: no-cache");
      header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")+2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
      header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
      header("Content-Type: application/octet-stream");
      header("Content-Length: ".(string)(filesize($path)));
      header("Content-Disposition: inline; filename=$name");
      header("Content-Transfer-Encoding: binary\n");
      if ($file = fopen($path, 'rb')) {
       while(!feof($file) and (connection_status()==0)) {
         print(fread($file, 1024*8));
         flush();
       }
       fclose($file);
      }
      return((connection_status()==0) and !connection_aborted());
    }
}

if ( ! function_exists('download_xlsx4')) {
    function download_xlsx4($filename) {
        if (file_exists($filename)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($filename));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            ob_clean();
            flush();
            if (readfile($filename) !== FALSE) return TRUE;
        } else {
            die('File does not exist');
        }
    }
}

