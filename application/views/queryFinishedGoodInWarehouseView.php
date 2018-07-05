<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function queryFinishedGoodInWarehouse() {
    $.ajax({
        url: "/finishedgoodinwarehouse/queryFinishedGoodInWarehouse",
        success: function(result) {
            $('#queryFinishedGoodInWarehouseTable').remove();
            var row = JSON.parse(result);
            var header = ["成品入庫單編號", "成品代號", "成品種類", "包裝", "單位重量", "每棧板的成品數量", "儲放區域", "入庫日期", "棧板數", "入庫數量", "入庫重量", "尚餘數量"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryFinishedGoodInWarehouseTable');
            table.appendTo($('#finishedGoodInWarehouseList'));
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
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgoodentry/queryUnconfirmedFinishedGoodEntryView');?>" data-role="button" data-icon="flat-plus" data-theme="c">成品入庫</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgoodinwarehouse/queryFinishedGoodInWarehouseView');?>" data-role="button" data-icon="flat-bubble" data-theme="f">庫存查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="f" onclick="queryFinishedGoodInWarehouse()">成品庫存查詢</button>
</div>

<br><br>
<div id="finishedGoodInWarehouseList"></div>

<div class="ui-block-b"><a href="<?php echo base_url('finishedgoodinwarehouse/downExcelFinishedGoodInWarehouseView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">Excel Download FGIW</a></div>
