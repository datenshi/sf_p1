<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    $('#addUserForm').submit(function(event) {
        var formData = $('#addUserForm').serialize();

        $.ajax({
            url: "/user/addUser",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addUserTable').remove();
                var row = JSON.parse(result);
                var header = ["使用者ID", "使用者名稱", "權限"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addUserTable');
                table.appendTo($('#userList'));
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
                    if ("password" != j) {
                        td = $(document.createElement('td'));
                        td.text(row[j]);
                        td.appendTo(tr);
                    }
                }
            }
        });
        event.preventDefault();
    });
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('user/addUserView');?>" data-role="button" data-icon="flat-plus" data-theme="b">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('user/queryUserView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addUserForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="b">
        使用者ID
        <input type="text" name="userID" size=20 maxlength=16>
        使用者名稱
        <input type="text" name="userName" size=20 maxlength=16>
        密碼
        <input type="password" name="password" size=20 maxlength=16>
        <div data-role="controlgroup" data-type="horizontal" data-theme="b">
        使用者權限
        </div>
        <select name="authority" data-native-menu="false">
            <option value="admin">管理者</option>
            <option value="normal">使用者</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="b">
        <input type="submit" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="userList"></div>
