<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckRoot()) $rt->LoginPage();
?>
<div class="no-box alert alert-success" id="nobox">
    <div class="content-no-box" id='result'>
        Thong Bao
    </div>
    <div class="close-no-box">
        <span class="icon-cancel-circle" onclick="close_box('nobox')"></span>
    </div>
    <div class="clearfix-no-box">
    </div>
</div>
<form class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-2" for="pwd">Chọn Năm:</label>
        <div class="col-sm-10"> 
            <select id='select_year' onchange="select_years()" class="form-control">
                <option>2000</option>    
                <option>2001</option> 
                <option>2002</option> 
                <option>2003</option> 
                <option>2004</option> 
                <option>2005</option> 
                <option>2006</option> 
                <option>2007</option> 
                <option>2008</option> 
                <option>2009</option> 
                <option>2010</option> 
                <option>2011</option> 
                <option>2012</option> 
                <option>2013</option> 
                <option>2014</option> 
                <option>2015</option> 
                <option>2016</option> 
                <option>2017</option> 
                <option selected>2018</option> 
                <option>2019</option> 
                <option>2020</option> 
                <option>2021</option> 
                <option>2022</option> 
                <option>2023</option> 
                <option>2024</option> 
                <option>2025</option> 
                <option>2026</option> 
                <option>2027</option> 
                <option>2028</option> 
                <option>2029</option> 
                <option>2030</option> 
            </select>
        </div>
    </div>
</form>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Cài Đặt Giới Hạn Một Đồng Chia Theo Tháng</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id='form4'>
            <table id="table" class='table table-striped table-hover'>
                <tr>
                    <th>Mốc Đồng Chia</th>
                    <th>Thời Gian</th>
                    <th>Delete</th>
                <tr>
            </table>
        </form>
        <input id="number" type="text" value=0 style="display:none" />
        <div class="row" style="padding-bottom: 10px">
            <div class="col-sm-1">
                <button type="button" style="font-size:15px;" class="form-control" onclick="addnewcl()">
                    <span class="icon-plus"></span>
                </button>
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-10">
            <input id='submit' value="Lưu Lại" type="button" onclick="load_ajax()"  class="btn btn-primary"/>      
        </div>
    </div>
</div>

<script>
    function select_years()
    {
        $("#table").html("<tr><th>Mốc Đồng Chia</th><th>Thời Gian</th><th>Delete</th><tr>");
        var year=$("#select_year").val();
        load_data(year);
    }
    function load_ajax() {
            var json=json_share();
            var time=$("#select_year").val();
            $("#submit").val('Đang Lưu...');
            $('#submit').attr('disabled', true);    
            
            $.ajax({
                url: "setting/share/save.php",
                type: "post",
                dataType: "text",
                data: {
                    submit:$("#submit").val(),
                    data:json,
                    time:time
                },
                success: function (result) {
                    $('#result').html(result);
                    $("#nobox").css("display","block");
                    $("#submit").val('Lưu Lại');
                    $('#submit').removeAttr('disabled');
                    up_page();
                }
            });
        }
    function load_data(time) {
        //$("#data").html('<img style="margin-left:45%;" src="img/pleasewait/plw.gif" />');
        $.ajax({
            url: "setting/share/load.php",
            type: "post",
            dataType: "json",
            data: {
                time:time
            },
            success: function (result) {
                //alert(result);
                load_share(result);
            }
        });
    }
    function load_share(list)
    {
        var table="";
        for(var i=0;i<list.length;i++)
        {
            var tr="<tbody><tr id=\"tr"+(i+1)+"\">";
            var money = '<td><input class="form-control" onkeyup="editnumber(\'money' + (i+1) + '\')" type="text" id="money' + (i+1) + '" value="'+list[i].money+'" /></td>';
            var timecreate = '<td><input class="form-control" type="month" id="timecreate' + (i+1) + '" value="'+list[i].timecreate+'" /></td>';
            var deletes = '<td><button class="form-control"  onclick="dele(\'tr' + (i+1) + '\')"><span style="font-size:13px;" class="icon-cancel-circle"></span></button></td>';
            tr+=money+timecreate+deletes+"</tr></tbody>";

            table+=tr;
        }

        $("#number").val(list.length);
        $("#table").html($("#table").html()+table);

        for(var i=0;i<list.length;i++)
        {
            editnumber("money"+(i+1));
        }
    }
    function json_share() 
    {
        var number = parseInt($('#number').val()) + parseInt(1);
        var list = "[";

        for (var j = 1; j <= number; j++) {
            if ($('#tr' + j).html()) {
                var money = "\"money\":"+"\""+cupnumber($("#money"+j).val())+"\"";
                var timecreate = "\"timecreate\":"+"\""+$("#timecreate"+j).val()+"\"";
                
                list+="{"+money+","+timecreate+"},";
            }
        }

        if(list.length>8) list = list.substring(0, list.length - 1);

        list+="]";

        var json=list;
        return json;
    }
    function addnewcl()
    {
        var number = $('#number');
        var table = $('#table');
        var i = parseInt(number.val()) + parseInt(1);

        var list = new Array();
        for (var j = 1; j <= i; j++) {
            if ($('#tr' + j).html()) {
                var money = $('#money' + j).val();
                var timecreate = $('#timecreate' + j).val();
                var obj = new create(j, money, timecreate);
                list.splice(list.length, 1, obj);
            }
        }

        var money = '<td><input class="form-control" onkeyup="editnumber(\'money' + i + '\')" type="text" id="money' + i + '" value="" /></td>';
        var timecreate = '<td><input class="form-control" type="month" id="timecreate' + i + '" value="" /></td>';
        var deletes = '<td><button class="form-control"  onclick="dele(\'tr' + i + '\')"><span style="font-size:13px;" class="icon-cancel-circle"></span></button></td>';
        table.html(table.html() + '<tr id="tr' + i + '">' + money + timecreate + deletes + '</tr>');
        number.val(i);

        for (var i = 0; i < list.length; i++) {
            $('#money' + list[i].number).val(list[i].money);
            $('#timecreate' + list[i].number).val(list[i].timecreate);
            editnumber("money"+(i+1));
        }
    }
    function dele(id) {
        $("#"+id).remove();
    }
    function create(number, money, timecreate) {
        this.number = number;
        this.money = money;
        this.timecreate = timecreate;
    }
    load_data($("#select_year").val());
</script>