<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    function autoFillRequisition() {
        $.ajax({
            url: "/finishedgoodrequisition/queryFinishedGoodRequisitionID",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    var selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        selectOption.attr('value', row[i][j]);
                        selectOption.text(row[i][j]);
                    }
                    selectOption.appendTo($('#requisitionOutWarehouse'));
                }
            }
        });
    }
    autoFillRequisition();

    function autoFillArea(product, packagingID) {
        $.ajax({
            url: "/finishedgoodinwarehouse/queryAreaInWarehouseByProductPackagingID/" + product + "\/" + packagingID,
            success: function(result) {
                $('select#takenOutAreaOutWarehouse option').each( function() {
                    if ("請選擇" != $(this).text()) {
                        $(this).remove();
                    }
                });
                var row = JSON.parse(result);

                for(var i in row)
                {
                    var selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        selectOption.attr('value', row[i][j]);
                        selectOption.text(row[i][j]);
                    }
                    selectOption.appendTo($('#takenOutAreaOutWarehouse'));
                }
            }
        });
    }

    var requisitionID = "", product, packagingID;
    // Auto-generate the requisition information by requisition ID
    $('#requisitionOutWarehouseSelection').on("change", '#requisitionOutWarehouse', function() {
        requisitionID = $('select#requisitionOutWarehouse').find("option:selected").val();

        if ("請選擇" != requisitionID) {
            $.ajax({
                url: "/finishedgoodrequisition/queryFinishedGoodRequisitionByRequisitionID/" + requisitionID,
                success: function(result) {
                    $('#queryFinishedGoodOutWarehouseTable').remove();
                    var row = JSON.parse(result);
                    var header = ["領貨單編號", "成品代號", "成品種類", "包裝", "單位重量", "每棧板的成品數量", "領貨數量", "尚未領取數量"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'queryFinishedGoodOutWarehouseTable');
                    table.appendTo($('#finishedGoodRequisitionOutWarehouseList'));
                    var tr = $(document.createElement('tr'));
                    tr.appendTo(table);
                    for(var i in header)
                    {
                        var th = $(document.createElement('th'));
                        th.text(header[i]);
                        th.appendTo(tr);
                    }

                    for(var j in row)
                    {
                        tr = $(document.createElement('tr'));
                        tr.appendTo(table);
                        for(var k in row[j])
                        {
                            if ("product" == k) {
                                product = row[j][k];
                            }
                            if ("packagingID" == k) {
                                packagingID = row[j][k];
                                continue;
                            }
                            var td = $(document.createElement('td'));
                            td.text(row[j][k]);
                            td.appendTo(tr);
                        }
                    }
                    autoFillArea(product, packagingID);
                }
            });
        }
    });

    var storedArea = "";
    // Auto-generate the finished good in warehouse information by product packaging ID and stored area
    $('#takenOutAreaSelection').on("change", '#takenOutAreaOutWarehouse', function() {
        storedArea = $('select#takenOutAreaOutWarehouse').find("option:selected").val();

        if ("請選擇" != storedArea) {
            $.ajax({
                url: "/finishedgoodinwarehouse/queryFinishedGoodInWarehouseByProductPackagingIDArea/" + product + "\/" + packagingID + "\/" + storedArea,
                success: function(result) {
                    $('#queryFinishedGoodInWarehouseTable').remove();
                    var row = JSON.parse(result);
                    var header = ["成品入庫單編號", "成品代號", "成品種類", "包裝", "單位重量", "每棧板的成品數量", "儲放區域", "入庫日期", "棧板數", "入庫數量", "入庫重量", "尚餘數量"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'queryFinishedGoodInWarehouseTable');
                    table.appendTo($('#finishedGoodInWarehouseList'));
                    var tr = $(document.createElement('tr'));
                    tr.appendTo(table);
                    for(var i in header)
                    {
                        var th = $(document.createElement('th'));
                        th.text(header[i]);
                        th.appendTo(tr);
                    }

                    for(var j in row)
                    {
                        tr = $(document.createElement('tr'));
                        tr.appendTo(table);
                        for(var k in row[j])
                        {
                            if ("storedFinishedGoodID" == k) {
                                $("input[name = 'inWarehouseID']").val(row[j][k]);
                                continue;
                            }
                            var td = $(document.createElement('td'));
                            td.text(row[j][k]);
                            td.appendTo(tr);
                        }
                    }
                }
            });
        }
    });

    $('#addFinishedGoodOutWarehouseForm').submit(function(event) {
        var formData = $('#addFinishedGoodOutWarehouseForm').serialize();

        $.ajax({
            url: "/finishedgoodoutwarehouse/addFinishedGoodOutWarehouse",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addFinishedGoodOutWarehouseTable').remove();
                var row = JSON.parse(result);
                var header = ["領貨單編號", "取貨區域", "領貨時間", "領貨單位", "領貨人員", "領貨數量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addFinishedGoodOutWarehouseTable');
                table.appendTo($('#finishedGoodOutWarehouseList'));
                var tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var i in header)
                {
                    var th = $(document.createElement('th'));
                    th.text(header[i]);
                    th.appendTo(tr);
                }

                tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var j in row)
                {
                    if ("inWarehouseID" == j){
                        continue;
                    }

                    td = $(document.createElement('td'));
                    td.text(row[j]);
                    td.appendTo(tr);
                }
            }
        });
        event.preventDefault();
    });

    // When click reset button
    $('input[type="reset"]').click(function() {
        // Remove options of requisition form then create again
        $('select#requisitionOutWarehouse option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillRequisition();

        // Remove options of stored area
        $('select#takenOutAreaOutWarehouse option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });

        // Remove finished good will be out of warehouse information table
        $('#queryFinishedGoodOutWarehouseTable').remove();
        // Remove finished good in warehouse information table
        $('#queryFinishedGoodInWarehouseTable').remove();
        // Remove finished good out warehouse information table
        $('#addFinishedGoodOutWarehouseTable').remove();
    });
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgoodoutwarehouse/addFinishedGoodOutWarehouseView');?>" data-role="button" data-icon="flat-plus" data-theme="f">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgoodoutwarehouse/queryFinishedGoodOutWarehouseView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addFinishedGoodOutWarehouseForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        領貨單編號
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f" id="requisitionOutWarehouseSelection">
        <select id="requisitionOutWarehouse" name="finishedGoodRequisition">
        <option>請選擇</option>
        </select>
    </div>
    <div id="finishedGoodRequisitionOutWarehouseList"></div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        取貨區域
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f" id="takenOutAreaSelection">
        <select id="takenOutAreaOutWarehouse" name="takenOutArea">
        <option>請選擇</option>
        </select>
    </div>
    <div id="finishedGoodInWarehouseList"></div>
    <input type="hidden" name="inWarehouseID">
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        領貨單位
        <input type="text" name="takingOutDepartment" size=20 maxlength=16>
        領貨人員
        <input type="text" name="takingOutMember" size=20 maxlength=16>
        領貨數量
        <input type="number" name="takenOutPackageNumber">
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="finishedGoodOutWarehouseList"></div>
