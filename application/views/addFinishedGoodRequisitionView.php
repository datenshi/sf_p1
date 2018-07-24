<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    div#productInFinishedGoodRequisition-button {
        display: none;
    }

    div#packagingInFinishedGoodRequisition-button {
        display: none;
    }

    span.select2-selection.select2-selection--single{
        width:150px;
    }
</style>
<script>
// Auto-fill in product ID and display product name
function autoFillProduct() {
    $.ajax({
        url: "/finishedgoodinwarehouse/queryProductNameIDInWarehouse",
        success: function(result) {
            var row = JSON.parse(result);

            for(var i in row)
            {
                selectOption = $(document.createElement('option'));
                for(var j in row[i])
                {
                    if ("product" == j) {
                        productID = row[i][j];
                        selectOption.attr('value', row[i][j]);
                    }
                    if ("finishedGoodType" == j) {
                        productText = row[i][j] + "(" + productID + ")";
                        selectOption.text(productText);
                    }
                }
                selectOption.appendTo($('#productInFinishedGoodRequisition'));
            }
        }
    });
}
autoFillProduct();

// Auto-fill in packaging when product ID is selected
$('#productInFinishedGoodRequisitionSelection').on("change", '#productInFinishedGoodRequisition', function() {
    var productID = $('select#productInFinishedGoodRequisition').find("option:selected").val();

    if ("請選擇" != productID) {
        $.ajax({
            url: "/finishedgoodpackaging/queryFinishedGoodPackagingbyProductID/" + productID,
            success: function(result) {
                var row = JSON.parse(result);

                $('select#packagingInFinishedGoodRequisition option').each( function() {
                    $(this).remove();
                });
                var selectOption = $(document.createElement('option'));
                selectOption.text("請選擇");
                selectOption.appendTo($('#packagingInFinishedGoodRequisition'));

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        if ("finishedGoodPackagingID" == j) {
                            selectOption.attr('value', row[i][j]);
                        }
                        if ("packaging" == j) {
                            selectOption.text(row[i][j]);
                        }
                    }
                    selectOption.appendTo($('#packagingInFinishedGoodRequisition'));
                }
            }
        });
    }
});

function addFinishedGoodRequisition(storedFinishedGoodID) {
    var finishedGoodRequisitionID = $("input[name='finishedGoodRequisitionID']").val();
    var requisitioningDepartment = $("input[name='requisitioningDepartment']").val();
    var requisitioningMember = $("input[name='requisitioningMember']").val();
    var requisitionedPackageNumber = $("input[name='requisitionedPackageNumber']").val();

    $.ajax({
        url: "/finishedgoodrequisition/addFinishedGoodRequisition/" +storedFinishedGoodID + "/" + 
        finishedGoodRequisitionID + "/" + 
        requisitioningDepartment + "/" + 
        requisitioningMember + "/" + 
        requisitionedPackageNumber,
        success: function(result) {
            $('#listFinishedGoodRequisitionForm').remove();
            var row = JSON.parse(result);
            var header = ["領貨單編號", "成品", "包裝", "儲放區域", "領貨日期", "領貨單位", "領貨人員", "領貨數量"];
            var table = $(document.createElement('table'));
            table.attr('id', 'addFinishedGoodRequisitionResultTable');
            table.appendTo($('#addFinishedGoodRequisitionResultList'));
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
                if ("productInWarehouseID" == j) {
                    continue;
                }
                td = $(document.createElement('td'));
                td.text(row[j]);
                td.appendTo(tr);
            }

            $.ajax({
                url: "/finishedgoodrequisition/increaseSerialNumber"
            });
        }
    });
}

function listFinishedGoodRequisition(storedFinishedGoodID) {
    $('#queryFinishedGoodInWarehouseTable').remove();
    $('#listFinishedGoodRequisitionForm').remove();

    var divForm = $(document.createElement('div'));
    divForm.attr('id', 'listFinishedGoodRequisitionForm');
    divForm.appendTo($('#addFinishedGoodRequisitionArea'));

    var div = $(document.createElement('div'));
    div.html("領貨單編號");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"text", "name":"finishedGoodRequisitionID", "size":"20", "maxlength":"16"});
    input.appendTo(divForm);

    $.ajax({
        url: "/finishedgoodrequisition/getSerialNumber",
        success: function(serialNumber) {
            $("input[name = 'finishedGoodRequisitionID']").attr({"value":"E" + serialNumber, "readonly":true});
        }
    });

    var div = $(document.createElement('div'));
    div.html("領貨單位");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"text", "name":"requisitioningDepartment", "size":"20", "maxlength":"16"});
    input.appendTo(divForm);

    var div = $(document.createElement('div'));
    div.html("領貨人員");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"text", "name":"requisitioningMember", "size":"20", "maxlength":"16"});
    input.appendTo(divForm);

    div = $(document.createElement('div'));
    div.html("領貨數量");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"number", "name":"requisitionedPackageNumber"});
    input.appendTo(divForm);

    div = $(document.createElement('div'));
    div.html("");
    div.appendTo(divForm);

    var button = $(document.createElement('button'));
    button.attr({'id':'revisionButton', 'class':'selfButtonB', 'onclick':'addFinishedGoodRequisition(' + storedFinishedGoodID + ')'});
    button.text("新增領貨");
    button.appendTo(divForm);
}

function queryFinishedGoodInWarehouse() {
    var productID = $('select#productInFinishedGoodRequisition').find("option:selected").val();
    if ("請選擇" == productID) {
        alert("請先選擇成品");
        event.preventDefault();
        return;
    }

    packagingID = $('select#packagingInFinishedGoodRequisition').find("option:selected").val();
    if ("請選擇" == packagingID) {
        queryURL = "/finishedgoodinwarehouse/queryFinishedGoodInWarehouseByProductPackagingID/" + productID + "/" + 0;
    }
    else {
        queryURL = "/finishedgoodinwarehouse/queryFinishedGoodInWarehouseByProductPackagingID/" + productID + "/" + packagingID;
    }

    $.ajax({
        url: queryURL,
        success: function(result) {
            $('#queryFinishedGoodInWarehouseTable').remove();
            var row = JSON.parse(result);
            var header = ["入庫單編號", "倉儲流水號", "批號", "成品編號", "成品", "包裝", "儲放區域", "入庫數量", "入庫重量", "尚餘數量", "領貨"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryFinishedGoodInWarehouseTable');
            table.appendTo($('#queryFinishedGoodInWarehouseList'));
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
                        var storedFinishedGoodID = row[j][k];
                        continue;
                    }
                    if ("remainingPackageNumber" == k) {
                        var td = $(document.createElement('td'));
                        td.text(row[j][k]);
                        td.appendTo(tr);

                        var confirmedButton = $(document.createElement('button'));
                        var onclickFunction = "listFinishedGoodRequisition(" + storedFinishedGoodID + ")";
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
    // Remove options of product then create again
    $('select#productInFinishedGoodRequisition option').each( function() {
        if ("請選擇" != $(this).text()) {
            $(this).remove();
        }
    });
    autoFillProduct();

    // Remove options of packaging
    $('select#packagingInFinishedGoodRequisition option').each( function() {
        if ("請選擇" != $(this).text()) {
            $(this).remove();
        }
    });

    // Remove product in warehouse information table
    $('#queryFinishedGoodInWarehouseTable').remove();
    // Remove adding product requisition form
    $('#listFinishedGoodRequisitionForm').remove();
    // Remove adding product requisition result table
    $('#addFinishedGoodRequisitionResultTable').remove();
});
$('.js-example-basic-single').select2();
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgoodrequisition/addFinishedGoodRequisitionView');?>" data-role="button" data-icon="flat-plus" data-theme="f">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgoodrequisition/queryFinishedGoodRequisitionView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addFinishedGoodRequisitionForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        待領成品
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f" id="productInFinishedGoodRequisitionSelection">
        <select id="productInFinishedGoodRequisition" class="js-example-basic-single" name="product">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        包裝
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f" id="packagingInFinishedGoodRequisitionSelection">
        <select id="packagingInFinishedGoodRequisition" class="js-example-basic-single" name="packagingID">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        <button data-theme="d" onclick="queryFinishedGoodInWarehouse()">查詢</button>
        <input type="reset" value="清除" data-role="button">
    </div>
</form>

<br><br>
<div id="queryFinishedGoodInWarehouseList"></div>
<div id="addFinishedGoodRequisitionArea"></div>
<div id="addFinishedGoodRequisitionResultList"></div>
