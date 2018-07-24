<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function deleteFinishedGood(deleteURL) {
    $.ajax({
        url: deleteURL,
        success: function(result) {
            queryFinishedGood();
        }
    });
}

function queryFinishedGood() {
    $.ajax({
        url: "/finishedgood/queryFinishedGood",
        success: function(result) {
            $('#queryFinishedGoodTable').remove();
            var row = JSON.parse(result);
            var header = ["成品代號", "成品種類"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryFinishedGoodTable');
            table.appendTo($('#finishedGoodList'));
            var tr = $(document.createElement('tr'));
            tr.appendTo(table);
            for(var i in header)
            {
                var th = $(document.createElement('th'));
		th.attr('class', 'sortable');
                th.attr('style', 'cursor:pointer');
                th.text(header[i]);
                th.appendTo(tr);
            }

            for(var j in row)
            {
                tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var k in row[j])
                {
                    if ("finishedGoodID" == k) {
                        var finishedGoodID = row[j][k];
                    }

                    var td = $(document.createElement('td'));
                    td.text(row[j][k]);
                    td.appendTo(tr);

                    if ("totalWeight" == k) {
                        break;
                    }
                }

/*
                var deleteButton = $(document.createElement('button'));
                var onclickFunction = "deleteFinishedGood(\"/finishedGood/deleteFinishedGood/" + finishedGoodID + "\")";
                deleteButton.attr({"class":"selfButton", "onclick":onclickFunction});
                deleteButton.text("刪除");

                td = $(document.createElement('td'));
                deleteButton.appendTo(td);
                td.appendTo(tr);*/
            }
	sortable_headers();    
        }
    });
}
function sortable_headers (){
    $('th').click(function(){
        var table = $(this).parents('table').eq(0)
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
        this.asc = !this.asc
        if (!this.asc){rows = rows.reverse()}
        for (var i = 0; i < rows.length; i++){table.append(rows[i])}
    });
}

function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index)
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
    }
}
function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgood/addFinishedGoodView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgood/queryFinishedGoodView');?>" data-role="button" data-icon="flat-bubble" data-theme="f">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="f" onclick="queryFinishedGood()">成品查詢</button>
</div>

<br><br>
<div id="finishedGoodList"></div>
