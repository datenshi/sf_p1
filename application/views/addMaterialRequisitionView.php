<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    // Auto-fill in material ID and display material name
    function autoFillMaterial() {
        $.ajax({
            url: "/materialinwarehouse/queryMaterialNameIDInWarehouse",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        if ("material" == j) {
                            selectOption.attr('value', row[i][j]);
                        }
                        if ("materialName" == j) {
                            selectOption.text(row[i][j]);
                        }
                    }
                    selectOption.appendTo($('#materialInMaterialRequisition'));
                }
            }
        });
    }

    function autoFillStoredDate() {
        // Auto-fill current date into storeDate
        var dateObject = new Date();
        var month = (dateObject.getMonth() + 1);
        var date = dateObject.getDate();

        if (2 > month.toString().length) {
            month = '0' + month;
        }
        if (2 > date.toString().length) {
            date = '0' + date;
        }
        currentDate = dateObject.getFullYear() + "-" + month + "-" + date;
        $("input[name = 'requisitioningDate']").attr('value', currentDate);
    }

    autoFillMaterial();
    autoFillStoredDate();

    var materialID = "";
    // Auto-fill in supplier and material usage when material ID is selected
    $('#materialInMaterialRequisitionSelection').on("change", '#materialInMaterialRequisition', function() {
        materialID = $('select#materialInMaterialRequisition').find("option:selected").val();

        if ("請選擇" != materialID) {
            // For supplier
            $.ajax({
                url: "/materialinwarehouse/querySupplierNameIDInWareHouseByMaterialID/" + materialID,
                success: function(result) {
                    var row = JSON.parse(result);

                    $('select#supplierInMaterialRequisition option').each( function() {
                        $(this).remove();
                    });
                    var selectOption = $(document.createElement('option'));
                    selectOption.text("請選擇");
                    selectOption.appendTo($('#supplierInMaterialRequisition'));

                    for(var i in row)
                    {
                        selectOption = $(document.createElement('option'));
                        for(var j in row[i])
                        {
                            if ("supplier" == j) {
                                selectOption.attr('value', row[i][j]);
                            }
                            if ("supplierName" == j) {
                                selectOption.text(row[i][j]);
                            }
                        }
                        selectOption.appendTo($('#supplierInMaterialRequisition'));
                    }
                }
            });

            // For material usage
            $.ajax({
                url: "/materialusage/queryMaterialUsageUsingDepartmentByMaterialID/" + materialID,
                success: function(result) {
                    $('select#usingDepartmentInMaterialRequisition option').  each( function() {
                        if ("請選擇" != $(this).text()) {
                            $(this).remove();
                        }
                    });
                    var row = JSON.parse(result);

                    for(var i in row)
                    {
                        selectOption = $(document.createElement('option'));
                        for(var j in row[i])
                        {
                            if ("materialUsageID" == j) {
                                selectOption.attr('value', row[i][j]);
                            }
                            if ("usingDepartment" == j) {
                                selectOption.text(row[i][j]);
                            }
                        }
                        selectOption.appendTo($('#usingDepartmentInMaterialRequisition'));
                    }
                }
            });
        }
    });

    var supplierID = "";
    // Auto-fill in packaging when supplier ID is selected
    $('#supplierInMaterialRequisitionSelection').on("change", '#supplierInMaterialRequisition', function() {
        supplierID = $('select#supplierInMaterialRequisition').find("option:selected").val();

        if (("請選擇" != supplierID) && ("" != materialID)) {
            $.ajax({
                url: "/materialinwarehouse/queryPackagingNameIDInWareHouseByMaterialSupplierID/" + materialID + "/" + supplierID,
                success: function(result) {
                    var row = JSON.parse(result);

                    $('select#packagingInMaterialRequisition option').each( function() {
                            $(this).remove();
                        });
                    var selectOption = $(document.createElement('option'));
                    selectOption.text("請選擇");
                    selectOption.appendTo($('#packagingInMaterialRequisition'));

                    for(var i in row)
                    {
                        selectOption = $(document.createElement('option'));
                        for(var j in row[i])
                        {
                            if ("packagingID" == j) {
                                selectOption.attr('value', row[i][j]);
                            }
                            if ("packaging" == j) {
                                    selectOption.text(row[i][j]);
                            }
                        }
                        selectOption.appendTo($('#packagingInMaterialRequisition'));
                    }
                }
            });
        }
    });

    var packagingID = "";
    // List the information of material in warehouse
    $('#packagingInMaterialRequisitionSelection').on("change", '#packagingInMaterialRequisition', function() {
        packagingID = $('select#packagingInMaterialRequisition').find("option:selected").val();

        if (("請選擇" != packagingID) && ("" != supplierID) && ("" != materialID)) {
            $.ajax({
                url: "/materialinwarehouse/queryMaterialInWarehouseDataByMaterialSupplierPackagingID/" + materialID + "/" + supplierID + "/" + packagingID,
                success: function(result) {
                    $('#queryMaterialInWarehouseTable').remove();

                    var row = JSON.parse(result);
                    var header = ["入料單編號", "原料", "供應商", "包裝", "儲放區域", "儲放數量", "尚餘重量"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'queryMaterialInWarehouseTable');
                    table.appendTo($('#materialInWarehouseList'));
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
                            var td = $(document.createElement('td'));
                            td.text(row[j][k]);
                            td.appendTo(tr);
                        }
                    }
                }
            });
        }
    });

    $('#addMaterialRequisitionForm').submit(function(event) {
        var formData = $('#addMaterialRequisitionForm').serialize();

        $.ajax({
            url: "/materialrequisition/addMaterialRequisition",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addMaterialRequisitionTable').remove();
                var row = JSON.parse(result);
                var header = ["領料單編號", "原料", "供應商", "包裝", "領料時間", "領料單位", "領料人員", "領料數量", "尚未領取數量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addMaterialRequisitionTable');
                table.appendTo($('#addMaterialRequisitionList'));
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
        // Remove options of material then create again
        $('select#materialInMaterialRequisition option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillMaterial();

        // Remove options of supplier
        $('select#supplierInMaterialRequisition option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });

        // Remove options of packaging
        $('select#packagingInMaterialRequisition option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });

        // Remove options of using department
        $('select#usingDepartmentInMaterialRequisition option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });

        // Remove material in warehouse information table
        $('#queryMaterialInWarehouseTable').remove();

        // Remove added material requisition information table
        $('#addMaterialRequisitionTable').remove();
    });
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('materialrequisition/addMaterialRequisitionView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('materialrequisition/queryMaterialRequisitionView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addMaterialRequisitionForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        領料單編號
        <input type="text" name="materialRequisitionID" size=20 maxlength=16>
        原料
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="materialInMaterialRequisitionSelection">
        <select id="materialInMaterialRequisition" name="material">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        供應商
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="supplierInMaterialRequisitionSelection">
        <select id="supplierInMaterialRequisition" name="supplier">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        包裝
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="packagingInMaterialRequisitionSelection">
        <select id="packagingInMaterialRequisition" name="packaging">
        <option>請選擇</option>
        </select>
    </div>
    <div id="materialInWarehouseList"></div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        領貨日期
        <input type="date" name="requisitioningDate" min="2017-01-01">
        領料單位
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="usingDepartmentInMaterialRequisitionSelection">
        <select id="usingDepartmentInMaterialRequisition" name="requisitioningDepartment">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        領料人員
        <input type="text" name="requisitioningMember" size=20 maxlength=16>
        領料數量
        <input type="number" name="requisitionedPackageNumber">
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="addMaterialRequisitionList"></div>
