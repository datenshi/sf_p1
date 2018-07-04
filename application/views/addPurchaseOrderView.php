<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    // Auto-fill in material ID and display material name
    $.ajax({
        url: "/material/queryMaterialNameWithID",
        success: function(result) {
            var row = JSON.parse(result);

            for(var i in row)
            {
                selectOption = $(document.createElement('option'));
                for(var j in row[i])
                {
                    if ("materialID" == j) {
                        selectOption.attr('value', row[i][j]);
                    }
                    if ("materialName" == j) {
                        selectOption.text(row[i][j]);
                    }
                }
                selectOption.appendTo($('#materialInPurchaseOrder'));
            }
        }
    });

    // Auto-fill in supplier and packaging when material ID is selected
    $('#materialInPurchaseOrderSelection').on("change", '#materialInPurchaseOrder', function() {
        var materialID = $('select#materialInPurchaseOrder').find("option:selected").val();

        if ("請選擇" != materialID) {
            // For supplier
            $.ajax({
                url: "/supplier/querysSupplierNameIDByMaterialID/" + materialID,
                success: function(result) {
                    var row = JSON.parse(result);

                    $('select#supplierInPurchaseOrder option').each( function() {
                        $(this).remove();
                    });
                    var selectOption = $(document.createElement('option'));
                    selectOption.text("請選擇");
                    selectOption.appendTo($('#supplierInPurchaseOrder'));

                    for(var i in row)
                    {
                        selectOption = $(document.createElement('option'));
                        for(var j in row[i])
                        {
                            if ("supplierID" == j) {
                                selectOption.attr('value', row[i][j]);
                            }
                            if ("supplierName" == j) {
                                selectOption.text(row[i][j]);
                            }
                        }
                        selectOption.appendTo($('#supplierInPurchaseOrder'));
                    }
                }
            });

            // For packaging
            $.ajax({
                url: "/packaging/queryPackagingbyMaterialID/" + materialID,
                success: function(result) {
                    var row = JSON.parse(result);

                    $('select#packagingInPurchaseOrder option').each( function() {
                        $(this).remove();
                    });
                    var selectOption = $(document.createElement('option'));
                    selectOption.text("請選擇");
                    selectOption.appendTo($('#packagingInPurchaseOrder'));

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
                        selectOption.appendTo($('#packagingInPurchaseOrder'));
                    }
                }
            });
        }
    });

    $('#addPurchaseOrderForm').submit(function(event) {
        var formData = $('#addPurchaseOrderForm').serialize();

        $.ajax({
            url: "/purchaseorder/addPurchaseOrder",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addPurchaseOrderTable').remove();
                var row = JSON.parse(result);
                var header = ["採購單編號", "原料", "供應商", "包裝", "進貨條件", "採購數量", "未入料數量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addPurchaseOrderTable');
                table.appendTo($('#purchaseOrderList'));
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
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('purchaseorder/addPurchaseOrderView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('purchaseorder/queryPurchaseOrderView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addPurchaseOrderForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        採購單編號
        <input type="text" name="purchaseOrderID" size=20 maxlength=16>
        原料
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="materialInPurchaseOrderSelection">
        <select id="materialInPurchaseOrder" name="material">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        供應商
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="supplierSelection">
        <select id="supplierInPurchaseOrder" name="supplier">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        包裝
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="packagingSelection">
        <select id="packagingInPurchaseOrder" name="packaging">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        進貨條件
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="purchaseConditionSelection">
        <select id="purchaseCondition" name="purchaseCondition">
        <option value="一般" selected>一般</option>
        <option value="特採">特採</option>
        <option value="回收料">回收料</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        採購數量
        <input type="number" name="purchasedPackageNumber">
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        <input type="submit" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="purchaseOrderList"></div>
