<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    var productDisplayName;
    var packagingDisplayName;

    function autoFillProduct() {
        $.ajax({
            url: "/finishedgoodinwarehouse/queryProductInWarehouse",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        var finishedGoodID;
                        if ("product" == j) {
                            finishedGoodID = row[i][j];
                            selectOption.attr('value', row[i][j]);
                        }
                        if ("finishedGoodType" == j) {
                            var productText = row[i][j] + '(' + finishedGoodID + ')';
                            selectOption.text(productText);
                        }
                    }
                    selectOption.appendTo($('#productInFinishedGoodRequisition'));
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

    autoFillProduct();
    autoFillStoredDate();

    var productID = "";
    // Auto-fill in packaging when product ID is selected
    $('#productInFinishedGoodRequisitionSelection').on("change", '#productInFinishedGoodRequisition', function() {
        productID = $('select#productInFinishedGoodRequisition').find("option:selected").val();
        productDisplayName = $('select#productInFinishedGoodRequisition').find("option:selected").html()

        if ("請選擇" != productID) {
            $.ajax({
                url: "/finishedgoodinwarehouse/queryPackagingInWarehouseByProductID/" + productID,
                success: function(result) {
                    var row = JSON.parse(result);

                    $('select#packagingInFinishedGoodRequisition option').each( function() {
                        $(this).remove();
                    });
                    var selectOption = $(document.createElement('option'));
                    selectOption.text("請選擇");
                    selectOption.appendTo($('#packagingInFinishedGoodRequisition'));

                    var productText = "";
                    for(var i in row)
                    {
                        selectOption = $(document.createElement('option'));
                        for(var j in row[i])
                        {
                            if ("packagingID" == j) {
                                selectOption.attr('value', row[i][j]);
                                continue;
                            }
                            if ("packaging" == j) {
                                productText += row[i][j] + '(';
                                continue;
                            }
                            if ("unitWeight" == j) {
                                productText += row[i][j] + '\/';
                                continue;
                            }
                            if ("packageNumberOfPallet" == j) {
                                productText += row[i][j] + ')';
                                continue;
                            }
                        }
                        selectOption.text(productText);
                        selectOption.appendTo($('#packagingInFinishedGoodRequisition'));
                        productText = "";
                    }
                }
            });
        }
    });

    var packagingID = "";
    // Auto-generate finished good in warehouse with product and packaging ID
    $('#packagingInFinishedGoodRequisitionSelection').on("change", '#packagingInFinishedGoodRequisition', function() {
        packagingID = $('select#packagingInFinishedGoodRequisition').find("option:selected").val();
        packagingDisplayName = $('select#packagingInFinishedGoodRequisition').find("option:selected").text();

        if (("請選擇" != productID) && ("請選擇" != packagingID)) {
            $.ajax({
                url: "/finishedgoodinwarehouse/queryPackagNumberInWarehouseByProductPackagingID/" + productID + "\/" + packagingID,
                success: function(result) {
                    $('#finishedGoodInWarehouseTable').remove();
                    var row = JSON.parse(result);
                    var header = ["成品", "包裝", "尚存數量"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'finishedGoodInWarehouseTable');
                    table.appendTo($('#finishedGoodInWarehouseList'));
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
                    td = $(document.createElement('td'));
                    td.text(productDisplayName);
                    td.appendTo(tr);

                    td = $(document.createElement('td'));
                    td.text(packagingDisplayName);
                    td.appendTo(tr);

                    td = $(document.createElement('td'));
                    td.text(row[0]['remainingPackageNumber']);
                    td.appendTo(tr);
                }
            });
            event.preventDefault();
        }
    });

    $('#addFinishedGoodRequisitionForm').submit(function(event) {
        var formData = $('#addFinishedGoodRequisitionForm').serialize();

        $.ajax({
            url: "/finishedgoodrequisition/addFinishedGoodRequisition",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addFinishedGoodRequisitionTable').remove();
                var row = JSON.parse(result);
                var header = ["領貨單編號", "成品代號", "包裝", "領貨日期", "領貨單位", "領貨人員", "領貨數量", "尚未領取數量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addFinishedGoodRequisitionTable');
                table.appendTo($('#addFinishedGoodRequisitionList'));
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
                    if ("remainingPackageNumber" == j) {
                        break;
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

        // Remove remaining package table
        $('#finishedGoodInWarehouseTable').remove();

        // Fill stored date again
        autoFillStoredDate();

        // Remove added material entry information table
        $('#addFinishedGoodRequisitionTable').remove();
    });
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgoodrequisition/addFinishedGoodRequisitionView');?>" data-role="button" data-icon="flat-plus" data-theme="f">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgoodrequisition/queryFinishedGoodRequisitionView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addFinishedGoodRequisitionForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        領貨單編號
        <input type="text" name="finishedGoodRequisitionID" size=20 maxlength=16>
        成品
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f" id="productInFinishedGoodRequisitionSelection">
        <select id="productInFinishedGoodRequisition" name="product">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        包裝
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f" id="packagingInFinishedGoodRequisitionSelection">
        <select id="packagingInFinishedGoodRequisition" name="packagingID">
        <option>請選擇</option>
        </select>
    </div>
    <div id="finishedGoodInWarehouseList"></div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        領貨日期
        <input type="date" name="requisitioningDate" min="2017-01-01">
        領貨單位
        <input type="text" name="requisitioningDepartment" size=20 maxlength=16>
        領貨人員
        <input type="text" name="requisitioningMember" size=20 maxlength=16>
        領貨數量
        <input type="number" name="requisitionedPackageNumber">
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="addFinishedGoodRequisitionList"></div>
