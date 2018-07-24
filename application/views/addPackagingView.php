<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    div#materialInPackaging-button {
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
                    selectOption.appendTo($('#materialInPackaging'));
                }
            }
        });
    }
    autoFillMaterial();

    // Display added packaging information when material selected
    $('#materialInPackagingSelection').on("change", '#materialInPackaging', function() {
        var materialID = $('select#materialInPackaging').find("option:selected").val();

        if ("請選擇" != materialID) {
            $.ajax({
                url: "/packaging/queryPackagingUnitWeightbyMaterialID/" + materialID,
                success: function(result) {
                    $('#addedPackagingTable').remove();
                    var row = JSON.parse(result);
                    var header = ["已新增的包裝", "單位重量"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'addedPackagingTable');
                    table.appendTo($('#addedPackagingList'));
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
    });

    $('#addPackagingForm').submit(function(event) {
        var formData = $('#addPackagingForm').serialize();

        $.ajax({
            url: "/packaging/addPackaging",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addPackagingTable').remove();
                var row = JSON.parse(result);
                var header = ["原料", "包裝", "單位重量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addPackagingTable');
                table.appendTo($('#packagingList'));
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
        $('select#materialInPackaging option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillMaterial();

        // Remove added packaging information table
        $('#addedPackagingTable').remove();
        // Remove current adding packaging information table
        $('#addPackagingTable').remove();
    });
    $('.js-example-basic-single').select2();
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('packaging/addPackagingView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('packaging/queryPackagingView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addPackagingForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        原料
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="materialInPackagingSelection">
        <select id="materialInPackaging" class="js-example-basic-single" name="material">
        <option>請選擇</option>
        </select>
    </div>
    <div id="addedPackagingList"></div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        包裝
        <input type="text" name="packaging" size=20 maxlength=16>
        單位重量
        <input type="text" name="unitWeight">
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="packagingList"></div>
