<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function deleteMaterial(deleteURL) {
    $.ajax({
        url: deleteURL,
        success: function(result) {
            queryMaterial();
        }
    });
}

function queryMaterial() {
    $.ajax({
        url: "/material/queryMaterial",
        success: function(result) {
            $('#queryMaterialTable').remove();
            var row = JSON.parse(result);
            //var header = ["原料編號", "原料", "供應商", "包裝", "單位重量", "使用單位", "價格", "刪除"];
            var header = ["原料編號", "原料", "供應商", "包裝", "單位重量", "使用單位", "單價", "總金額"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryMaterialTable');
            table.appendTo($('#materialList'));
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
                    if ("materialID" == k) {
                        var materialID = row[j][k];
                    }

                    var td = $(document.createElement('td'));
                    td.text(row[j][k]);
                    td.appendTo(tr);
                }

/*
                var deleteButton = $(document.createElement('button'));
                var onclickFunction = "deleteMaterial(\"/material/deleteMaterial/" + materialID + "\")";
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
    <div class="ui-block-a"><a href="<?php echo base_url('material/addMaterialView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('material/queryMaterialView');?>" data-role="button" data-icon="flat-bubble" data-theme="d">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="d" onclick="queryMaterial()">原料查詢</button>
</div>

<br><br>
<div id="materialList"></div>
