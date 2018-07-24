<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    div#materialInMaterialRequisition-button {
        display: none;
    }

    div#supplierInMaterialRequisition-button {
        display: none;
    }

    span.select2-selection.select2-selection--single{
        width:150px;
    }
</style>
<script>
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
                        var materialID = row[i][j];
                        selectOption.attr('value', row[i][j]);
                    }
                    var listedName = row[i][j] + "[" + materialID + "]";
                    if ("materialName" == j) {
                        selectOption.text(listedName);
                    }
                }
                selectOption.appendTo($('#materialInMaterialRequisition'));
            }
        }
    });
}
autoFillMaterial();

// Auto-fill in supplier when material ID is selected
$('#materialInMaterialRequisitionSelection').on("change", '#materialInMaterialRequisition', function() {
    var materialID = $('select#materialInMaterialRequisition').find("option:selected").val();

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
    }
});

function addMaterialRequisition(storedMaterialID) {
    var materialRequisitionID = $("input[name='materialRequisitionID']").val();
    var requisitioningDepartment = $("input[name='requisitioningDepartment']").val();
    var requisitioningMember = $("input[name='requisitioningMember']").val();
    var requisitionedPackageNumber = $("input[name='requisitionedPackageNumber']").val();

    $.ajax({
        url: "/materialrequisition/addMaterialRequisition/" +storedMaterialID + "/" + 
        materialRequisitionID + "/" + 
        requisitioningDepartment + "/" + 
        requisitioningMember + "/" + 
        requisitionedPackageNumber,
        success: function(result) {
            $('#listMaterialRequisitionForm').remove();
            var row = JSON.parse(result);
            var header = ["領料單編號", "原料", "供應商", "包裝", "儲放區域", "領料日期", "領料單位", "領料人員", "領料數量"];
            var table = $(document.createElement('table'));
            table.attr('id', 'addMaterialRequisitionResultTable');
            table.appendTo($('#addMaterialRequisitionResultList'));
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

            $.ajax({
                url: "/materialrequisition/increaseSerialNumber"
            });
        }
    });
}

function listMaterialRequisition(storedMaterialID) {
    $('#queryMaterialInWarehouseTable').remove();
    $('#listMaterialRequisitionForm').remove();

    var divForm = $(document.createElement('div'));
    divForm.attr('id', 'listMaterialRequisitionForm');
    divForm.appendTo($('#addMaterialRequisitionArea'));

    var div = $(document.createElement('div'));
    div.html("領料單編號");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"text", "name":"materialRequisitionID", "size":"20", "maxlength":"16"});
    input.appendTo(divForm);

    $.ajax({
        url: "/materialrequisition/getSerialNumber",
        success: function(serialNumber) {
            $("input[name = 'materialRequisitionID']").attr({"value":"C" + serialNumber, "readonly":true});
        }
    });

    var div = $(document.createElement('div'));
    div.html("領料單位");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"text", "name":"requisitioningDepartment", "size":"20", "maxlength":"16"});
    input.appendTo(divForm);

    var div = $(document.createElement('div'));
    div.html("領料人員");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"text", "name":"requisitioningMember", "size":"20", "maxlength":"16"});
    input.appendTo(divForm);

    div = $(document.createElement('div'));
    div.html("領料數量");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"number", "name":"requisitionedPackageNumber"});
    input.appendTo(divForm);

    div = $(document.createElement('div'));
    div.html("");
    div.appendTo(divForm);

    var button = $(document.createElement('button'));
    button.attr({'id':'revisionButton', 'class':'selfButtonB', 'onclick':'addMaterialRequisition(' + storedMaterialID + ')'});
    button.text("新增領料");
    button.appendTo(divForm);
}

function queryMaterialInWarehouse() {
    var materialID = $('select#materialInMaterialRequisition').find("option:selected").val();
    if ("請選擇" == materialID) {
        alert("請先選擇原料");
        event.preventDefault();
        return;
    }

    supplierID = $('select#supplierInMaterialRequisition').find("option:selected").val();
    if ("請選擇" == supplierID) {
        queryURL = "/materialinwarehouse/queryMaterialInWarehouseDataByMaterialSupplierID/" + materialID + "/" + 0;
    }
    else {
        queryURL = "/materialinwarehouse/queryMaterialInWarehouseDataByMaterialSupplierID/" + materialID + "/" + supplierID;
    }

    $.ajax({
        url: queryURL,
        success: function(result) {
            $('#queryMaterialInWarehouseTable').remove();
            var row = JSON.parse(result);
            var header = ["入料單編號", "原料編號", "原料", "供應商", "包裝", "儲放區域", "儲放數量", "儲放重量", "尚餘數量", "領料"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryMaterialInWarehouseTable');
            table.appendTo($('#queryMaterialInWarehouseList'));
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
                        var storedMaterialID = row[j][k];
                        continue;
                    }
                    if ("remainingPackageNumber" == k) {
                        var td = $(document.createElement('td'));
                        td.text(row[j][k]);
                        td.appendTo(tr);

                        var confirmedButton = $(document.createElement('button'));
                        var onclickFunction = "listMaterialRequisition(" + storedMaterialID + ")";
                        confirmedButton.attr({"class":"selfButtonG", "onclick":onclickFunction});
                        confirmedButton.text("新增");

                        td = $(document.createElement('td'));
                        confirmedButton.appendTo(td);
                        td.appendTo(tr);

                        continue;
                    }
                    var td = $(document.createElement('td'));
                    td.text(row[j][k]);
                    td.appendTo(tr);
                }
            }
        }
    });
    event.preventDefault();
}

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

    // Remove material in warehouse information table
    $('#queryMaterialInWarehouseTable').remove();
    // Remove adding material requisition form
    $('#listMaterialRequisitionForm').remove();
    // Remove adding material requisition result table
    $('#addMaterialRequisitionResultTable').remove();
});
$('.js-example-basic-single').select2();
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('materialrequisition/addMaterialRequisitionView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('materialrequisition/queryMaterialRequisitionView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addMaterialRequisitionForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        待領原料
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="materialInMaterialRequisitionSelection">
        <select id="materialInMaterialRequisition" class="js-example-basic-single" name="material">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        供應商
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="supplierInMaterialRequisitionSelection">
        <select id="supplierInMaterialRequisition" class="js-example-basic-single" name="supplier">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        <button data-theme="d" onclick="queryMaterialInWarehouse()">查詢</button>
        <input type="reset" value="清除" data-role="button">
    </div>
</form>

<br><br>
<div id="queryMaterialInWarehouseList"></div>
<div id="addMaterialRequisitionArea"></div>
<div id="addMaterialRequisitionResultList"></div>
