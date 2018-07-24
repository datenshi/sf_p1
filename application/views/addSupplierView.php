<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    div#materialInSupplier-button {
        display: none;
    }

    span.select2-selection.select2-selection--single{
        width:150px;
    }
</style>
<script>
$(document).ready(function() {
    function autoFillMaterial() {
    // Auto-fill in material ID and display material name
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
                    selectOption.appendTo($('#materialInSupplier'));
                }
            }
        });
    }
    autoFillMaterial();

    // Display added supplier information when material selected
    $('#materialInSupplierSelection').on("change", '#materialInSupplier', function() {
        var materialID = $('select#materialInSupplier').find("option:selected").val();

        if ("請選擇" != materialID) {
            $.ajax({
                url: "/supplier/querysSupplierNameUnitPriceByMaterialID/" + materialID,
                success: function(result) {
                    $('#addedSupplierTable').remove();
                    var row = JSON.parse(result);
                    var header = ["已新增的供應商", "單位價格"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'addedSupplierTable');
                    table.appendTo($('#addedSupplierList'));
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
                            if ("supplierID" == k) {
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

    $('#addSupplierForm').submit(function(event) {
        var formData = $('#addSupplierForm').serialize();

        $.ajax({
            url: "/supplier/addSupplier",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addSupplierTable').remove();
                var row = JSON.parse(result);
                var header = ["供應商", "產品", "單價"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addSupplierTable');
                table.appendTo($('#supplierList'));
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
        $('select#materialInSupplier option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillMaterial();

        // Remove added packaging information table
        $('#addedSupplierTable').remove();
        // Remove current adding packaging information table
        $('#addSupplierTable').remove();
    });
    $('.js-example-basic-single').select2();
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('supplier/addSupplierView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('supplier/querySupplierView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addSupplierForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        原料
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="materialInSupplierSelection">
        <select id="materialInSupplier" class="js-example-basic-single" name="material">
        <option>請選擇</option>
        </select>
    </div>
    <div id="addedSupplierList"></div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        供應商
        <input type="text" name="supplierName" size=20 maxlength=16>
        價格
        <input type="text" name="unitPrice">
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="supplierList"></div>
