<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
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
                selectOption.appendTo($('#materialInMaterialUsage'));
            }
        }
    });

    $('#addMaterialUsageForm').submit(function(event) {
        var formData = $('#addMaterialUsageForm').serialize();

        $.ajax({
            url: "/materialusage/addMaterialUsage",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addMaterialUsageTable').remove();
                var row = JSON.parse(result);
                var header = ["原料", "使用單位"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addMaterialUsageTable');
                table.appendTo($('#materialUsageList'));
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
    <div class="ui-block-a"><a href="<?php echo base_url('materialusage/addMaterialUsageView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('materialusage/queryMaterialUsageView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addMaterialUsageForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        原料
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="materialSelection">
        <select id="materialInMaterialUsage" name="material">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        使用單位
        <input type="text" name="usingDepartment" size=20 maxlength=16>
        <input type="submit" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="materialUsageList"></div>
