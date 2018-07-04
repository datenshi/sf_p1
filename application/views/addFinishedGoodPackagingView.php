<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
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

    $('#addFinishedGoodPackagingForm').submit(function(event) {
        var formData = $('#addFinishedGoodPackagingForm').serialize();

        $.ajax({
            url: "/finishedgoodpackaging/addFinishedGoodPackaging",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addFinishedGoodPackagingTable').remove();
                var row = JSON.parse(result);
                var header = ["成品", "包裝", "單位重量", "每棧板的成品數量"];
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
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="productSelection">
        <select id="productInPackaging" name="product">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        包裝
        <input type="text" name="packaging" size=20 maxlength=16>
        單位重量
        <input type="number" name="unitWeight">
        每棧板的成品數量
        <input type="number" name="packageNumberOfPallet">
        <input type="submit" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="finishedGoodPackagingList"></div>
