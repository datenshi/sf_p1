<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function deleteFinishedGoodPackaging(deleteURL) {
    $.ajax({
        url: deleteURL,
        success: function(result) {
            queryFinishedGoodPackaging();
        }
    });
}

function queryFinishedGoodPackaging() {
    $.ajax({
        url: "/finishedgoodpackaging/queryFinishedGoodPackaging",
        success: function(result) {
            $('#queryFinishedGoodPackagingTable').remove();
            var row = JSON.parse(result);
            var header = ["成品", "包裝", "單位重量", "每棧板的成品數量"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryFinishedGoodPackagingTable');
            table.appendTo($('#finishedGoodPackagingList'));
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
                    if ("finishedGoodPackagingID" == k) {
                        var finishedGoodPackagingID = row[j][k];
                        continue;
                    }

                    var td = $(document.createElement('td'));
                    td.text(row[j][k]);
                    td.appendTo(tr);
                }

/*
                var deleteButton = $(document.createElement('button'));
                var onclickFunction = "deletePackaging(\"/packaging/deletePackaging/" + packagingID + "\")";
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
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgoodpackaging/addFinishedGoodPackagingView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgoodpackaging/queryFinishedGoodPackagingView');?>" data-role="button" data-icon="flat-bubble" data-theme="f">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="d" onclick="queryFinishedGoodPackaging()">成品包裝查詢</button>
</div>

<br><br>
<div id="finishedGoodPackagingList"></div>
