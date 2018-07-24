<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    div#productInFinishedGoodEntry-button {
        display: none;
    }

    div#packagingInFinishedGoodEntry-button {
        display: none;
    }

    div#status-button {
        display: none;
    }

    span.select2-selection.select2-selection--single{
        width:150px;
    }
</style>
<script>
$(document).ready(function() {
    function autoGenerateFinishedGoodEntrySerialNumber() {
        // Auto-generate serial number
        $.ajax({
            url: "/finishedgoodentry/getSerialNumber",
            success: function(serialNumber) {
                $("input[name = 'finishedGoodEntryID']").attr({"value":"D" + serialNumber, "readonly":true});
                $("input[name = 'serialNumber']").attr({"value":serialNumber, "readonly":true});
            }
        });
    }

    function autoFillProduct() {
        $.ajax({
            url: "/finishedgood/queryFinishedGoodIDType",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        var finishedGoodID;
                        if ("finishedGoodID" == j) {
                            finishedGoodID = row[i][j];
                            selectOption.attr('value', row[i][j]);
                        }
                        if ("finishedGoodType" == j) {
                            var productText = row[i][j] + '(' + finishedGoodID + ')';
                            selectOption.text(productText);
                        }
                    }
                    selectOption.appendTo($('#productInFinishedGoodEntry'));
                }
            }
        });
    }

    autoGenerateFinishedGoodEntrySerialNumber();
    autoFillProduct();

    var productID = "";
    // Auto-fill in packaging when product ID is selected
    $('#productInFinishedGoodEntrySelection').on("change", '#productInFinishedGoodEntry', function() {
        productID = $('select#productInFinishedGoodEntry').find("option:selected").val();

        if ("請選擇" != productID) {
            $.ajax({
                url: "/finishedgoodpackaging/queryFinishedGoodPackagingbyProductID/" + productID,
                success: function(result) {
                    var row = JSON.parse(result);

                    $('select#packagingInFinishedGoodEntry option').each( function() {
                        $(this).remove();
                    });
                    var selectOption = $(document.createElement('option'));
                    selectOption.text("請選擇");
                    selectOption.appendTo($('#packagingInFinishedGoodEntry'));

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
                        selectOption.appendTo($('#packagingInFinishedGoodEntry'));
                    }
                }
            });
        }
    });

    $('#addFinishedGoodEntryForm').submit(function(event) {
        var formData = $('#addFinishedGoodEntryForm').serialize();

        $.ajax({
            url: "/finishedgoodentry/addFinishedGoodEntry",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addFinishedGoodEntryTable').remove();
                var row = JSON.parse(result);
                var header = ["入庫單編號", "倉儲流水號", "批號", "成品", "包裝", "狀態", "儲放區域", "入庫日期", "棧板數", "入庫數量", "入庫重量", "尚餘數量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addFinishedGoodEntryTable');
                table.appendTo($('#finishedGoodEntryList'));
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

                $.ajax({
                    url: "/finishedgoodentry/increaseSerialNumber"
                });
            }
        });
        event.preventDefault();
    });

    // When click reset button
    $('input[type="reset"]').click(function() {
        // Generate serial button again
        autoGenerateFinishedGoodEntrySerialNumber();

        // Remove options of product then create again
        $('select#productInFinishedGoodEntry option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillProduct();

        // Remove options of packaging
        $('select#packagingInFinishedGoodEntry option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });

        // Remove added material entry information table
        $('#addFinishedGoodEntryTable').remove();
    });
    $('.js-example-basic-single').select2();
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgoodentry/addFinishedGoodEntryView');?>" data-role="button" data-icon="flat-plus" data-theme="f">成品入庫</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgoodinwarehouse/queryFinishedGoodInWarehouseView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢成品庫存</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addFinishedGoodEntryForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        入庫單編號
        <input type="text" name="finishedGoodEntryID" size=20 maxlength=16>
        倉儲流水號
        <input type="text" name="serialNumber" size=20 maxlength=16>
        批號
        <input type="text" name="batchNumber" size=20 maxlength=16>
        成品
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f" id="productInFinishedGoodEntrySelection">
        <select id="productInFinishedGoodEntry" class="js-example-basic-single" name="product">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        包裝
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f" id="packagingInFinishedGoodEntrySelection">
        <select id="packagingInFinishedGoodEntry" class="js-example-basic-single" name="packagingID">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        狀態
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f" id="statusSelection">
        <select id="status" class="js-example-basic-single" name="status">
        <option value="正常" selected>正常</option>
        <option value="急貨">急貨</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        儲放區域
        <input type="text" name="storedArea" size=20 maxlength=16>
        成品數量
        <input type="text" name="storedPackageNumber">
        棧板數
        <input type="number" name="palletNumber">
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="finishedGoodEntryList"></div>
