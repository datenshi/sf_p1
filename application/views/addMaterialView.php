<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    $('#addMaterialForm').submit(function(event) {
        var formData = $('#addMaterialForm').serialize();

        $.ajax({
            url: "/material/addMaterial",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addMaterialTable').remove();
                var row = JSON.parse(result);
                var header = ["原料編號", "原料名稱"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addMaterialTable');
                table.appendTo($('#materialList'));
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
    <div class="ui-block-a"><a href="<?php echo base_url('material/addMaterialView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('material/queryMaterialView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addMaterialForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        原料編號
        <input type="text" name="materialID" size=20 maxlength=16>
        原料名稱
        <input type="text" name="materialName" size=20 maxlength=16>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        <input type="submit" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="materialList"></div>
