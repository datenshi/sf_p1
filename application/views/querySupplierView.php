<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function deleteSupplier(deleteURL) {
    $.ajax({
        url: deleteURL,
        success: function(result) {
            querySupplier();
        }
    });
}

function querySupplier() {
    $.ajax({
        url: "/supplier/querySupplier",
        success: function(result) {
            $('#querySupplierTable').remove();
            var row = JSON.parse(result);
            //var header = ["供應商", "產品", "包裝", "單位重量", "價格", "刪除"];
            var header = ["供應商", "原料", "單價"];
            var table = $(document.createElement('table'));
            table.attr('id', 'querySupplierTable');
            table.appendTo($('#supplierList'));
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
                    if ("supplierID" == k) {
                        var supplierID = row[j][k];
                        continue;
                    }

                    var td = $(document.createElement('td'));
                    td.text(row[j][k]);
                    td.appendTo(tr);
                }

/*
                var deleteButton = $(document.createElement('button'));
                var onclickFunction = "deleteSupplier(\"/supplier/deleteSupplier/" + supplierID + "\")";
                deleteButton.attr({"class":"selfButton", "onclick":onclickFunction});
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
    <div class="ui-block-a"><a href="<?php echo base_url('supplier/addSupplierView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('supplier/querySupplierView');?>" data-role="button" data-icon="flat-bubble" data-theme="d">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="d" onclick="querySupplier()">供應商查詢</button>
</div>

<br><br>
<div id="supplierList"></div>
