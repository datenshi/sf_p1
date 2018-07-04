<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    $('#addFinishedGoodForm').submit(function(event) {
        var formData = $('#addFinishedGoodForm').serialize();

        $.ajax({
            url: "/finishedgood/addFinishedGood",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addFinishedGoodTable').remove();
                var row = JSON.parse(result);
                var header = ["成品代號", "成品種類"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addFinishedGoodTable');
                table.appendTo($('#finishedGoodList'));
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
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgood/addFinishedGoodView');?>" data-role="button" data-icon="flat-plus" data-theme="f">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgood/queryFinishedGoodView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addFinishedGoodForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        成品代號
        <input type="text" name="finishedGoodID" size=20 maxlength=16>
        成品種類
        <input type="text" name="finishedGoodType" size=20 maxlength=16>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="f">
        <input type="submit" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="finishedGoodList"></div>
