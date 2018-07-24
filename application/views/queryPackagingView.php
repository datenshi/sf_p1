<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function deletePackaging(deleteURL) {
    $.ajax({
        url: deleteURL,
        success: function(result) {
            queryPackaging();
        }
    });
}

function queryPackaging() {
    $.ajax({
        url: "/packaging/queryPackaging",
        success: function(result) {
            $('#queryPackagingTable').remove();
            var row = JSON.parse(result);
            var header = ["原料", "包裝", "單位重量"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryPackagingTable');
            table.appendTo($('#packagingList'));
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
                    if ("packagingID" == k) {
                        var packagingID = row[j][k];
                        continue;
                    }
                    if ("material" == k) {
                        var materialID = row[j][k];
                        continue;
                    }
                    if ("materialName" == k) {
                        var listedName = row[j][k] + "[" + materialID + "]";
                        var td = $(document.createElement('td'));
                        td.text(listedName);
                        td.appendTo(tr);
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
    <div class="ui-block-a"><a href="<?php echo base_url('packaging/addPackagingView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('packaging/queryPackagingView');?>" data-role="button" data-icon="flat-bubble" data-theme="d">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="d" onclick="queryPackaging()">包裝查詢</button>
</div>

<br><br>
<div id="packagingList"></div>
