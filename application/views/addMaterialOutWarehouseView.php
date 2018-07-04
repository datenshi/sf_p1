<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    function autoFillMaterialRequisition() {
        // Auto-fill material requisition ID
        $.ajax({
            url: "/materialrequisition/queryMaterialRequisitionID",
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
                    selectOption.appendTo($('#materialRequisitionOutWarehouse'));
                }
            }
        });
    }
    autoFillMaterialRequisition();

    function autoFillArea(material, supplier, packagingID) {
        $.ajax({
            url: "/materialinwarehouse/queryAreaInWarehouseByMaterialSupplierPackagingID/" + material + "\/" + supplier + "\/" + packagingID,
            success: function(result) {
                $('select#outWarehouseArea option').each( function() {
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
                    selectOption.appendTo($('#outWarehouseArea'));
                }
            }
        });
    }

    function autoFillMaterialUsage(material) {
        $.ajax({
            url: "/materialusage/queryMaterialUsageUsingDepartmentByMaterialID/" + material,
            success: function(result) {
                $('select#usingDepartmentInWarehouse option').each( function() {
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
                        if ("materialUsageID" == j) {
                                selectOption.attr('value', row[i][j]);
                            }
                        if ("usingDepartment" == j) {
                                selectOption.text(row[i][j]);
                        }
                    }
                    selectOption.appendTo($('#usingDepartmentInWarehouse'));
                }
            }
        });
    }

    var materialRequisitionID = "", material, supplier, packagingID;
    // Auto-generate the requisition information by requisition ID
    $('#materialRequisitionOutWarehouseSelection').on("change", '#materialRequisitionOutWarehouse', function() {
        materialRequisitionID = $('select#materialRequisitionOutWarehouse').find("option:selected").val();

        if ("請選擇" != materialRequisitionID) {
            $.ajax({
                url: "/materialrequisition/queryMaterialRequisitionByRequisitionID/" + materialRequisitionID,
                success: function(result) {
                    $('#queryMaterialOutWarehouseTable').remove();
                    var row = JSON.parse(result);
                    var header = ["領料單編號", "原料", "供應商", "包裝", "尚未領取數量"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'queryMaterialOutWarehouseTable');
                    table.appendTo($('#materialRequisitionOutWarehouseList'));
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
                            if ("material" == k) {
                                material = row[j][k];
                                continue;
                            }
                            if ("supplier" == k) {
                                supplier = row[j][k];
                                continue;
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
                    autoFillArea(material, supplier, packagingID);
                    autoFillMaterialUsage(material);
                }
            });
        }
    });

    var storedArea = "";
    // List the information of material in warehouse
    $('#outWarehouseAreaSelection').on("change", '#outWarehouseArea', function() {
        storedArea = $('select#outWarehouseArea').find("option:selected").val();

        if (("請選擇" != material) && ("" != supplier) && ("" != packagingID)) {
            $.ajax({
                url: "/materialinwarehouse/queryMaterialInWarehouseDataByMaterialSupplierPackagingArea/" + material + "/" + supplier + "/" + packagingID + "/" + storedArea,
                success: function(result) {
                    $('#queryMaterialInWarehouseAndOutTable').remove();

                    var row = JSON.parse(result);
                    var header = ["入料單編號", "原料", "供應商", "包裝", "儲放區域", "儲放數量", "尚餘重量"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'queryMaterialInWarehouseAndOutTable');
                    table.appendTo($('#materialInWarehouseAndOutList'));
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
                            if ("storedMaterialID" == k) {
                                $("input[name = 'materialInWarehouseID']").val(row[j][k]);
                                continue;
                            }
                            if ("materialInWarehouseID" == k) {
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

    $('#addMaterialOutWarehouseForm').submit(function(event) {
        var formData = $('#addMaterialOutWarehouseForm').serialize();

        $.ajax({
            url: "/materialoutwarehouse/addMaterialOutWarehouse",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addMaterialOutWarehouseTable').remove();
                var row = JSON.parse(result);
                var header = ["領料單編號", "取料區域", "領料時間", "領料單位", "領料人員", "領料數量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addMaterialOutWarehouseTable');
                table.appendTo($('#addMaterialOutWarehouseList'));
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
                    if ("materialInWarehouseID" == j) {
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
        // Remove options of material requisition ID then create again
        $('select#materialRequisitionOutWarehouse option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillMaterialRequisition();

        // Remove material requisition information table
        $('#queryMaterialOutWarehouseTable').remove();

        // Remove options of stored area
        $('select#outWarehouseArea option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });

        // Remove material in warehouse information table
        $('#queryMaterialInWarehouseAndOutTable').remove();

        // Remove options of using department area
        $('select#usingDepartmentInWarehouse option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });

        // Remove added material entry information table
        $('#addMaterialOutWarehouseTable').remove();
    });
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('materialoutwarehouse/addMaterialOutWarehouseView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('materialoutwarehouse/queryMaterialOutWarehouseView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addMaterialOutWarehouseForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        領料單編號
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="materialRequisitionOutWarehouseSelection">
        <select id="materialRequisitionOutWarehouse" name="materialRequisition">
        <option>請選擇</option>
        </select>
    </div>
    <div id="materialRequisitionOutWarehouseList"></div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        取料區域
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="outWarehouseAreaSelection">
        <select id="outWarehouseArea" name="outWarehouseArea">
        <option>請選擇</option>
        </select>
    </div>
    <div id="materialInWarehouseAndOutList"></div>
    <input type="hidden" name="materialInWarehouseID">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        領料單位
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="usingDepartmentInWarehouseSelection">
        <select id="usingDepartmentInWarehouse" name="outWarehouseDepartment">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        領料人員
        <input type="text" name="outWarehouseMember" size=20 maxlength=16>
        領料數量
        <input type="number" name="outWarehousePackageNumber">
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="addMaterialOutWarehouseList"></div>
