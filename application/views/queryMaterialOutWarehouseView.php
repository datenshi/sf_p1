<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function queryMaterialOutWarehouse() {
    $.ajax({
        url: "/materialoutwarehouse/queryMaterialOutWarehouse",
        success: function(result) {
            $('#queryMaterialOutWarehouseTable').remove();
            var row = JSON.parse(result);
            var header = ["原料代號", "原料", "供應商", "包裝", "取料區域", "領料時間", "領料單位", "領料人員", "領料數量"];

            var table = $(document.createElement('table'));
            table.attr('id', 'queryMaterialOutWarehouseTable');
            table.appendTo($('#queryMaterialOutWarehouseList'));
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
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('materialoutwarehouse/addMaterialOutWarehouseView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('materialoutwarehouse/queryMaterialOutWarehouseView');?>" data-role="button" data-icon="flat-bubble" data-theme="d">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="d" onclick="queryMaterialOutWarehouse()">原料出庫查詢</button>
</div>

<br><br>
<div id="queryMaterialOutWarehouseList"></div>
