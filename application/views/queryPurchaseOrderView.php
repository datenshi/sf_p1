<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function deletePurchaseOrder(deleteURL) {
    $.ajax({
        url: deleteURL,
        success: function(result) {
            queryPurchaseOrder();
        }
    });
}

function queryPurchaseOrder() {
    $.ajax({
        url: "/purchaseorder/queryPurchaseOrder/false",
        success: function(result) {
            $('#queryPurchaseOrderTable').remove();
            var row = JSON.parse(result);
            //var header = ["採購單編號", "原料", "進貨條件", "刪除];
            var header = ["採購單編號", "原料", "供應商", "單價", "包裝", "單位重量", "進貨條件", "採購數量", "未入料數量"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryPurchaseOrderTable');
            table.appendTo($('#purchaseOrderList'));
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
                    if ("purchaseOrderID" == k) {
                        var purchaseOrderID = row[j][k];
                    }

                    var td = $(document.createElement('td'));
                    td.text(row[j][k]);
                    td.appendTo(tr);
                }

/*
                var deleteButton = $(document.createElement('button'));
                var onclickFunction = "deletePurchaseOrder(\"/purchaseorder/deletePurchaseOrder/" + purchaseOrderID + "\")";
                deleteButton.attr({"class":"selfButtonR", "onclick":onclickFunction});
                deleteButton.text("刪除");

                td = $(document.createElement('td'));
                deleteButton.appendTo(td);
                td.appendTo(tr);
*/
            }
        }
    });
}
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('purchaseorder/addPurchaseOrderView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('purchaseorder/queryPurchaseOrderView');?>" data-role="button" data-icon="flat-bubble" data-theme="d">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="d" onclick="queryPurchaseOrder()">採購單查詢</button>
</div>

<br><br>
<div id="purchaseOrderList"></div>
