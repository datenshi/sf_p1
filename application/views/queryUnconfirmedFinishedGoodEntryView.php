<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$isConfirmed = 0;
$finishedGoodEntryID = 0;
$filterByDate = 'Date_to_by_filtered_by';
?>

<script>
var row;
var header = ["成品入庫單編號", "倉儲流水號", "成品代號", "成品種類", "包裝", "單位重量", "每棧板的成品數量", "狀態", "儲放區域", "入庫日期", "棧板數", "入庫數量", "入庫重量", "待入庫棧板數", "待入庫數量", "確認", "修改"];

function deleteFinishedGoodEntry(deleteURL) {
    $.ajax({
        url: deleteURL,
        success: function(result) {
            queryUnconfirmedFinishedGoodEntry();
        }
    });
}

function confirmFinishedGoodEntry(confirmURL) {
    $.ajax({
        url: confirmURL,
        success: function(result) {
            queryUnconfirmedFinishedGoodEntry(0);
        }
    });
}

function updateFinishedGoodEntryPackage() {
    var finishedGoodEntryID = $("input[name='finishedGoodEntryID']").val();
    var storedArea = $("input[name='expectedStoredArea']").val();
    var palletNumber = $("input[name='palletNumber']").val();
    var storedPackageNumber = $("input[name='expectedStoredPackageNumber']").val();
    var unitWeight = 0;

    $('#queryFinishedGoodEntryTable').remove();
    $('#reviseFinishedGoodEntryForm').remove();
    var table = $(document.createElement('table'));
    table.attr('id', 'queryFinishedGoodEntryTable');
    table.appendTo($('#queryFinishedGoodEntryList'));
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
        for(var k in row[j])
        {
            if ("finishedGoodEntryID" == k) {
                if (finishedGoodEntryID != row[j][k]) {
                    break;
                }
                else {
                    tr = $(document.createElement('tr'));
                    tr.appendTo(table);
                }
            }

            if ("unitWeight" == k) {
                unitWeight = row[j][k];
            }

            if ("expectedStoredArea" == k) {
                row[j][k] = storedArea;
            }

            if ("palletNumber" == k) {
                row[j][k] = palletNumber;
            }

            if ("expectedStoredPackageNumber" == k) {
                row[j][k] = storedPackageNumber;
            }

            if ("expectedStoredWeight" == k) {
                row[j][k] = storedPackageNumber * unitWeight;
            }

            if ("notEnteredPackageNumber" == k) {
                var td = $(document.createElement('td'));
                td.text(row[j][k]);
                td.appendTo(tr);

                // Create confirmed button after expectedStoredWeight
                var confirmedButton = $(document.createElement('button'));
                var onclickFunction = "confirmFinishedGoodEntry(\"/finishedgoodentry/confirmFinishedGoodEntry/" 
                + finishedGoodEntryID + "\/" 
                + storedArea + "\/" 
                + palletNumber + "\/" 
                + storedPackageNumber + "\")";
                confirmedButton.attr({"class":"selfButtonG", "onclick":onclickFunction});
                confirmedButton.text("確認");

                td = $(document.createElement('td'));
                confirmedButton.appendTo(td);
                td.appendTo(tr);

                // Create revised button
                var revisedButton = $(document.createElement('button'));
                var onclickFunction = "revisedFinishedGoodEntry(\"" 
                + finishedGoodEntryID + "\", \"" 
                + storedArea + "\", " 
                + palletNumber + ", \"" 
                + storedPackageNumber + "\")";
                revisedButton.attr({"class":"selfButtonB", "onclick":onclickFunction});
                revisedButton.text("修改");

                td = $(document.createElement('td'));
                revisedButton.appendTo(td);
                td.appendTo(tr);
            }
            else {
                var td = $(document.createElement('td'));
                td.text(row[j][k]);
                td.appendTo(tr);
            }
        }
    }
}

function revisedFinishedGoodEntry(
        finishedGoodEntryID,
        storedArea,
        palletNumber,
        storedPackageNumber) {
    $('#queryFinishedGoodEntryTable').remove();
    $('#reviseFinishedGoodEntryForm').remove();

    var divForm = $(document.createElement('div'));
    divForm.attr('id', 'reviseFinishedGoodEntryForm');
    divForm.appendTo($('#reviseFinishedGoodEntryArea'));

    var div = $(document.createElement('div'));
    div.html("成品入庫單編號");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"text", "name":"finishedGoodEntryID", "value":finishedGoodEntryID, "readonly":true});
    input.appendTo(divForm);

    var div = $(document.createElement('div'));
    div.html("儲放區域");
    div.appendTo(divForm);

    var input = $(document.createElement('input'));
    input.attr({"type":"text", "name":"expectedStoredArea", "value":storedArea});
    input.appendTo(divForm);

    div = $(document.createElement('div'));
    div.html("棧板數");
    div.appendTo(divForm);

    input = $(document.createElement('input'));
    input.attr({"type":"number", "name":"palletNumber", "value":palletNumber});
    input.appendTo(divForm);

    div = $(document.createElement('div'));
    div.html("");
    div.appendTo(divForm);

    div = $(document.createElement('div'));
    div.html("入庫數量");
    div.appendTo(divForm);

    input = $(document.createElement('input'));
    input.attr({"type":"number", "name":"expectedStoredPackageNumber", "value":storedPackageNumber});
    input.appendTo(divForm);

    div = $(document.createElement('div'));
    div.html("");
    div.appendTo(divForm);

    var button = $(document.createElement('button'));
    button.attr({'id':'revisionButton', 'class':'selfButtonB', 'onclick':'updateFinishedGoodEntryPackage()'});
    button.text("修改");
    button.appendTo(divForm);
}

function queryUnconfirmedFinishedGoodEntry(isFinishedGoodEntryID) {
    $.ajax({
        url: "/finishedgoodentry/queryFinishedGoodEntry/0/" + isFinishedGoodEntryID,
        async: false,
        success: function(result) {
            $('#queryFinishedGoodEntryTable').remove();
            $('#reviseFinishedGoodEntryForm').remove();
            row = JSON.parse(result);

            var table = $(document.createElement('table'));
            table.attr('id', 'queryFinishedGoodEntryTable');
            table.appendTo($('#queryFinishedGoodEntryList'));
            var tr = $(document.createElement('tr'));
            tr.appendTo(table);
            for(var i in header)
            {
                var th = $(document.createElement('th'));
                th.attr('class', 'sortable');
                th.attr('style', 'cursor:pointer');
                th.text(header[i]);
                th.appendTo(tr);
            }

            for(var j in row)
            {
                tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var k in row[j])
                {
                    if ("finishedGoodEntryID" == k) {
                        var finishedGoodEntryID = row[j][k];
                    }

                    if ("expectedStoredArea" == k) {
                        var storedArea = row[j][k];
                    }

                    if ("notEnteredPalletNumber" == k) {
                        var palletNumber = row[j][k];
                    }

                    if ("notEnteredPackageNumber" == k) {
                        var storedPackageNumber = row[j][k];
                        var td = $(document.createElement('td'));
                        td.text(row[j][k]);
                        td.appendTo(tr);

                        // Create confirmed button after expectedStoredWeight
                        var confirmedButton = $(document.createElement('button'));
                        var onclickFunction = "confirmFinishedGoodEntry(\"/finishedgoodentry/confirmFinishedGoodEntry/" 
                            + finishedGoodEntryID + "\/" 
                            + storedArea + "\/" 
                            + palletNumber + "\/" 
                            + storedPackageNumber + "\")";
                        confirmedButton.attr({"class":"selfButtonG", "onclick":onclickFunction});
                        confirmedButton.text("確認");

                        td = $(document.createElement('td'));
                        confirmedButton.appendTo(td);
                        td.appendTo(tr);

                        // Create revised button
                        var revisedButton = $(document.createElement('button'));
                        var onclickFunction = "revisedFinishedGoodEntry(\"" 
                        + finishedGoodEntryID + "\", \"" 
                        + storedArea + "\", " 
                        + palletNumber + ", \"" 
                        + storedPackageNumber + "\")";
                        revisedButton.attr({"class":"selfButtonB", "onclick":onclickFunction});
                        revisedButton.text("修改");

                        td = $(document.createElement('td'));
                        revisedButton.appendTo(td);
                        td.appendTo(tr);
                    }
                    else {
                        var td = $(document.createElement('td'));
                        td.text(row[j][k]);
                        td.appendTo(tr);
                    }
                }
            }
            sortable_headers_2();
        }
    });
}



function sortable_headers (){
    var table = $('table');

    $('.sortable')
        .wrapInner('<span title="sort this column"/>')
        .each(function(){

            var th = $(this),
                thIndex = th.index(),
                inverse = false;

            th.click(function(){

                table.find('td').filter(function(){

                    return $(this).index() === thIndex;

                }).sortElements(function(a, b){

                    if( $.text([a]) == $.text([b]) )
                        return 0;

                    return $.text([a]) > $.text([b]) ?
                        inverse ? -1 : 1
                        : inverse ? 1 : -1;

                }, function(){

                    // parentNode is the element we want to move
                    return this.parentNode; 

                });

                inverse = !inverse;

            });

        });
}

$(document).ready(function(){

    

    $('.down-excel').click( function(e) {
        e.preventDefault();  
        $.ajax({
            type: "POST",
            url: '../excelprint',
            dataType: 'json',
            data: {functionname: 'add', arguments: [0, 0]},

            success: function (data, textstatus) {
                          if( !('error' in data) ) {
                            // yourVariable = data.result;
                            var $a = $("#excel-download");
                            $a.attr("href",data.file);
                            $a.attr("download","file1.xlsx");
                            $a[0].click();
                            //$a.remove();
                          }
                          else {
                              console.log(data.error);
                          }
                    }
        });
        return false; 

    });

});

function sortable_headers_2 (){
    $('th').click(function(){
        var table = $(this).parents('table').eq(0)
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
        this.asc = !this.asc
        if (!this.asc){rows = rows.reverse()}
        for (var i = 0; i < rows.length; i++){table.append(rows[i])}
    });
}

function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index)
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
    }
}
function getCellValue(row, index){ return $(row).children('td').eq(index).text() }

</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('finishedgoodentry/queryUnconfirmedFinishedGoodEntryView');?>" data-role="button" data-icon="flat-plus" data-theme="f">成品入庫</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('finishedgoodinwarehouse/queryFinishedGoodInWarehouseView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">庫存查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button id="table-button" data-icon="flat-man" data-theme="f" onclick="queryUnconfirmedFinishedGoodEntry(0)">顯示入庫清單</button>
</div>

<br><br>
<div id="queryFinishedGoodEntryList"></div>
<div id="reviseFinishedGoodEntryArea"></div>

<div class="ui-block-b"><a id = "excel-download" style="display:none;" href="<?php //echo base_url('finishedgoodentry/downExcelFinishedGoodEntry/'.$isConfirmed.'/'.$finishedGoodEntryID.'/'.$filterByDate);?>" data-role="button" data-icon="flat-bubble" data-theme="c">Excel Download FGE</a></div>
<div class="ui-block-b down-excel"><a href="" data-role="button" data-icon="flat-bubble" data-theme="c">Excel Download</a></div>