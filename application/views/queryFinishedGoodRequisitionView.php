<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function queryFinishedGoodRequisition() {
    $.ajax({
        url: "/finishedgoodrequisition/queryFinishedGoodRequisition",
        success: function(result) {
            $('#queryFinishedGoodRequisitionTable').remove();
            var row = JSON.parse(result);
            var header = ["領貨單編號", "成品代號", "包裝", "領貨日期", "領貨單位", "領貨人員", "領貨數量", "尚未領取數量"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryFinishedGoodRequisitionTable');
            table.appendTo($('#queryFinishedGoodRequisitionList'));
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
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgoodrequisition/addFinishedGoodRequisitionView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgoodrequisition/queryFinishedGoodRequisitionView');?>" data-role="button" data-icon="flat-bubble" data-theme="f">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="f" onclick="queryFinishedGoodRequisition()">領貨單查詢</button>
</div>

<br><br>
<div id="queryFinishedGoodRequisitionList"></div>
