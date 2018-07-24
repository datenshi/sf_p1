<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    div#materialInMaterialUsage-button {
        display: none;
    }

    span.select2-selection.select2-selection--single{
        width:150px;
    }
</style>
<script>
$(document).ready(function() {
    function autoFillMaterial() {
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
                            var materialID = row[i][j];
                            selectOption.attr('value', row[i][j]);
                        }
                        var listedName = row[i][j] + "[" + materialID + "]";
                        if ("materialName" == j) {
                            selectOption.text(listedName);
                        }
                    }
                    selectOption.appendTo($('#materialInMaterialUsage'));
                }
            }
        });
    }
    autoFillMaterial();

    // Display added using department information when material selected
    $('#materialInMaterialUsageSelection').on("change", '#materialInMaterialUsage', function() {
        var materialID = $('select#materialInMaterialUsage').find("option:selected").val();

        if ("請選擇" != materialID) {
            $.ajax({
                url: "/materialusage/queryMaterialUsageUsingDepartmentByMaterialID/" + materialID,
                success: function(result) {
                    $('#addedUsingDepartmentTable').remove();
                    var row = JSON.parse(result);
                    var header = ["已新增的使用單位"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'addedUsingDepartmentTable');
                    table.appendTo($('#addedMaterialUsageList'));
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
                            if ("materialUsageID" == k) {
                                continue;
                            }
                            var td = $(document.createElement('td'));
                            td.text(row[j][k]);
                            td.appendTo(tr);
                        }
                    }
                }
            });
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

    // When click reset button
    $('input[type="reset"]').click(function() {
        // Remove options of material then create again
        $('select#materialInMaterialUsage option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillMaterial();

        // Remove added using department information table
        $('#addedUsingDepartmentTable').remove();
        // Remove current adding material usage information table
        $('#addMaterialUsageTable').remove();
    });
    $('.js-example-basic-single').select2();
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
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="materialInMaterialUsageSelection">
        <select id="materialInMaterialUsage" class="js-example-basic-single" name="material">
        <option>請選擇</option>
        </select>
    </div>
    <div id="addedMaterialUsageList"></div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        使用單位
        <input type="text" name="usingDepartment" size=20 maxlength=16>
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="materialUsageList"></div>
