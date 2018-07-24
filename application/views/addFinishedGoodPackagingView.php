<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    div#productInPackaging-button {
        display: none;
    }

    span#select2-productInPackaging-container{
        width:200px;
    }
</style>
<script>
$(document).ready(function() {
    function autoFillProduct() {
        $.ajax({
            url: "/finishedgood/queryFinishedGoodIDType",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        var finishedGoodID;
                        if ("finishedGoodID" == j) {
                            finishedGoodID = row[i][j];
                            selectOption.attr('value', row[i][j]);
                        }
                        if ("finishedGoodType" == j) {
                            var productText = row[i][j] + '(' + finishedGoodID + ')';
                            selectOption.text(productText);
                        }
                    }
                    selectOption.appendTo($('#productInPackaging'));
                }
            }
        });
    }
    autoFillProduct();

    // Display added product packaging information when product selected
    $('#productInPackagingSelection').on("change", '#productInPackaging', function() {
        var productID = $('select#productInPackaging').find("option:selected").val();

        if ("請選擇" != productID) {
            $.ajax({
                url: "/finishedgoodpackaging/queryFinishedGoodPackagingUnitWeightbyProductID/" + productID,
                success: function(result) {
                    $('#addedProductPackagingTable').remove();
                    var row = JSON.parse(result);
                    var header = ["已新增的包裝", "單位重量"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'addedProductPackagingTable');
                    table.appendTo($('#addedProductPackagingList'));
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

    $('#addFinishedGoodPackagingForm').submit(function(event) {
        var formData = $('#addFinishedGoodPackagingForm').serialize();

        $.ajax({
            url: "/finishedgoodpackaging/addFinishedGoodPackaging",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addFinishedGoodPackagingTable').remove();
                var row = JSON.parse(result);
                var header = ["成品", "包裝", "單位重量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addFinishedGoodPackagingTable');
                table.appendTo($('#finishedGoodPackagingList'));
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
        // Remove options of product then create again
        $('select#productInPackaging option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillProduct();

        // Remove added product packaging information table
        $('#addedProductPackagingTable').remove();
        // Remove current adding product packaging information table
        $('#addFinishedGoodPackagingTable').remove();
    });
    $('.js-example-basic-single').select2();
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgoodpackaging/addFinishedGoodPackagingView');?>" data-role="button" data-icon="flat-plus" data-theme="f">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgoodpackaging/queryFinishedGoodPackagingView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addFinishedGoodPackagingForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        成品
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="productInPackagingSelection">
        <select id="productInPackaging" class="js-example-basic-single" name="product">
        <option>請選擇</option>
        </select>
    </div>
    <div id="addedProductPackagingList"></div>
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
<div id="finishedGoodPackagingList"></div>
