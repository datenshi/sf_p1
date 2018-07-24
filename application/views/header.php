<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url('asset/css/jquery.mobile.flatui.css');?>" />
    <script src="<?php echo base_url('asset/js/jquery-2.1.4.min.js');?>"></script>
    <script src="<?php echo base_url('asset/js/jquery.mobile-1.4.5.min.js');?>"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <title>倉儲系統</title>

<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

.selfButtonR {
    background-color: red;
    color: white;
    border: none;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    padding: 5px 8px;
    margin: 4px 2px;
}

.selfButtonG {
    background-color: green;
    color: white;
    border: none;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    padding: 5px 8px;
    margin: 4px 2px;
}

.selfButtonB {
    background-color: blue;
    color: white;
    border: none;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    padding: 5px 8px;
    margin: 4px 2px;
}
</style>
</head>
<body>
