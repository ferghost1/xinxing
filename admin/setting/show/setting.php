<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
$db = new apps_libs_Dbconn();
$revenue = $db->query('select * from revenue_share where id = 1',true);
$revenue_rate = json_decode($revenue->revenue,true);
$repurchase_rate = $revenue->repurchase_rate;
$agency_rate = $revenue->agency_rate;
$agency_gthieu_rate = $revenue->agency_gthieu_rate;

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
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Cài Đặt Cùng Chia Lợi Nhuân</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id='form1'>
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">Mốc Tích Lũy:</label>
                <div class="col-sm-10">
                    <input class="form-control" id='accumulate' onkeyup="editnumber('accumulate')" type="text" placeholder="Số tiền để đạt một đơn vị đồng chia" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phần Trăm Số Tiền Công Ty Trích Ra:</label>
                <div class="col-sm-10"> 
                    <input type="text" class="form-control" id="percentcompanyreturn" onkeyup="editnumberpercent(event,'percentcompanyreturn')" placeholder="% Số tiền công ty trích từ lợi nhuận">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Giới Hạn Một Đồng Chia:</label>
                <div class="col-sm-10"> 
                    <input type="text" class="form-control" id="limitshare" onkeyup="editnumber('limitshare')" placeholder="Số tiền đồng chia lớn nhất được phép">
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10">
                <input id='submit1' value="Lưu Lại" type="button" onclick="load_ajax(1)"  class="btn btn-primary"/>      
            </div>
        </form>
    </div>
</div>

<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Cài Đặt Chính Sách Tái Mua Hàng</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id='form2'>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Đồng Chia Lần 1:</label>
                <div class="col-sm-10"> 
                    <input type="text" class="form-control" id="firstreturnshare" onkeyup="editnumberpercent(event,'firstreturnshare')" placeholder="% Số tiền đồng chia nhận lại lần 1">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Đồng Chia Các Lần Sau:</label>
                <div class="col-sm-10"> 
                    <input type="text" class="form-control" id="nextreturnshare" onkeyup="editnumberpercent(event,'nextreturnshare')" placeholder="% Số tiền đồng chia nhận lại các lần sau">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Đồng Chia Nhận Lại Tối Đa:</label>
                <div class="col-sm-10"> 
                    <input type="text" class="form-control" id="limitreturnshare" onkeyup="editnumberpercent(event,'limitreturnshare')" placeholder="% Số tiền đồng chia nhận lại tối đa">
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10">
                <input id='submit2' value="Lưu Lại" type="button" onclick="load_ajax(2)"  class="btn btn-primary"/>      
            </div>
        </form>
    </div>
</div>
<!--
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Cài Đặt Chính Sách Đại Lý Giới Thiệu</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id='form3'>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Nhận Doanh Thu F1:</label>
                <div class="col-sm-10"> 
                    <input type="text" class="form-control" id="levelf1return" onkeyup="editnumberpercent(event,'levelf1return')" placeholder="% Số tiền nhân được từ doanh thu của F1">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Nhận Doanh Thu F2-F5:</label>
                <div class="col-sm-10"> 
                    <input type="text" class="form-control" id="levelf2f5return" onkeyup="editnumberpercent(event,'levelf2f5return')" placeholder="% Số tiền nhân được từ doanh thu từ F2 tới F5">
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10">
                <input id='submit3' value="Lưu Lại" type="button" onclick="load_ajax(3)"  class="btn btn-primary"/>      
            </div>
        </form>
    </div>
</div>
-->
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Cài Đặt Chính Sách Đại Lý 20 Cấp</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" action="setting/share/revenue20f.php" method="post">
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Nhận Doanh Thu F1:</label>
                <div class="col-sm-10"> 
                    <input type="number" class="form-control" id="lv1" name="revenue[1]" placeholder="% Số tiền nhân được từ doanh thu của F1" value="<?= $revenue_rate[1] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Nhận Doanh Thu F2:</label>
                <div class="col-sm-10"> 
                    <input type="number" class="form-control" id="lv2" name="revenue[2]"  placeholder="% Số tiền nhân được từ doanh thu F2"  value="<?= $revenue_rate[2] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Nhận Doanh Thu F3:</label>
                <div class="col-sm-10"> 
                    <input type="number" class="form-control" id="lv2" name="revenue[3]"  placeholder="% Số tiền nhân được từ doanh thu F3" value="<?= $revenue_rate[3] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Nhận Doanh Thu F4:</label>
                <div class="col-sm-10"> 
                    <input type="number" class="form-control" id="lv2" name="revenue[4]"  placeholder="% Số tiền nhân được từ doanh thu F4" value="<?= $revenue_rate[4] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Nhận Doanh Thu F5:</label>
                <div class="col-sm-10"> 
                    <input type="number" class="form-control" id="lv2" name="revenue[5]" placeholder="% Số tiền nhân được từ doanh thu F5" value="<?= $revenue_rate[5] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phầm Trăm Nhận Doanh Thu F6-F20:</label>
                <div class="col-sm-10"> 
                    <input type="number" class="form-control" id="lv2" name="revenue[6]" placeholder="% Số tiền nhân được từ doanh thu từ F6 - F20" value="<?= $revenue_rate[6] ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">% Tái Mua:</label>
                <div class="col-sm-10"> 
                    <input type="number" step="any" class="form-control" id="repurchase_rate" name="repurchase_rate" placeholder="% Tái Mua" value="<?= $repurchase_rate ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">% Đại Lý:</label>
                <div class="col-sm-10"> 
                    <input type="number" step="any" class="form-control" id="repurchase_rate" name="agency_rate" placeholder="% Đại Lý" value="<?= $agency_rate ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">% Giới Thiệu Đại Lý:</label>
                <div class="col-sm-10"> 
                    <input type="number" step="any" class="form-control" id="repurchase_rate" name="agency_gthieu_rate" placeholder="% Giới Thiệu Đại Lý" value="<?= $agency_gthieu_rate ?>">
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10">
                <input name="submit_revenue" class="btn btn-primary" value="Lưu Lại" type="submit" />      
            </div>
        </form>
    </div>
</div>

<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Cài Đặt Chính Sách Tái Mua Hàng Đại Lý</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id='form4'>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Tổng Chiết Khấu Tối Đa:</label>
                <div class="col-sm-10"> 
                    <input type="text" class="form-control" id="extractmax" onkeyup="editnumberpercent(event,'extractmax')" placeholder="% Số tiền chiết khấu tối đa">
                </div>
            </div>     
            <table id="table" class='table table-striped table-hover'>
                <tr>
                    <th>Doanh Số</th>
                    <th>Chiết Khấu Nhận</th>
                    <th>Delete</th>
                <tr>
            </table>
            <input id="number" type="text" value=0 style="display:none" />
            <div class="row" style="padding-bottom: 10px">
                <div class="col-sm-1">
                    <button type="button" style="font-size:15px;" class="form-control" onclick="addnewcl()">
                        <span class="icon-plus"></span>
                    </button>
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-10">
                <input id='submit4' value="Lưu Lại" type="button" onclick="load_ajax(4)"  class="btn btn-primary"/>      
            </div>
        </form>
    </div>
</div>


<script>
    function load_ajax(i) {
            var json;

            switch(i)
            {
                case 1:
                    json=json_share_profits();
                    break;
                case 2:
                    json=json_repurchase();
                    break;
                case 3:
                    json=json_agency_introduce();
                    break;
                case 4:
                    json=json_agency_repurchase();
                    break;
            }

            $("#submit"+i).val('Đang Lưu...');
            $('#submit'+i).attr('disabled', true);    
            
            $.ajax({
                url: "setting/show/save"+i+".php",
                type: "post",
                dataType: "text",
                data: {
                    submit:$("#submit"+i).val(),
                    data:json
                },
                success: function (result) {
                    $('#result').html(result);
                    $("#nobox").css("display","block");
                    $("#submit"+i).val('Lưu Lại');
                    $('#submit'+i).removeAttr('disabled');
                    up_page();
                }
            });
        }

    function json_share_profits() //1
    {
        var accumulate = "\"accumulate\":"+"\""+cupnumber($("#accumulate").val())+"\"";
        var percentcompanyreturn = "\"percentcompanyreturn\":"+"\""+cupnumberpercent($("#percentcompanyreturn").val())+"\"";
        var limitshare = "\"limitshare\":"+"\""+cupnumber($("#limitshare").val())+"\"";
        var json="{"+accumulate+","+percentcompanyreturn+","+limitshare+"}";
        return json;
    }
    function json_repurchase() //2
    {
        var firstreturnshare = "\"firstreturnshare\":"+"\""+cupnumberpercent($("#firstreturnshare").val())+"\"";
        var nextreturnshare = "\"nextreturnshare\":"+"\""+cupnumberpercent($("#nextreturnshare").val())+"\"";
        var limitreturnshare = "\"limitreturnshare\":"+"\""+cupnumberpercent($("#limitreturnshare").val())+"\"";
        var json="{"+firstreturnshare+","+nextreturnshare+","+limitreturnshare+"}";
        return json;
    }

    function json_agency_introduce() //3
    {
        var levelf1return = "\"levelf1return\":"+"\""+cupnumberpercent($("#levelf1return").val())+"\"";
        var levelf2f5return = "\"levelf2f5return\":"+"\""+cupnumberpercent($("#levelf2f5return").val())+"\"";
        var json="{"+levelf1return+","+levelf2f5return+"}";
        return json;
    }
    function json_agency_repurchase() //4
    {
        var number = parseInt($('#number').val()) + parseInt(1);
        
        var extractmax = "\"extractmax\":"+"\""+cupnumberpercent($("#extractmax").val())+"\"";
        var list = "\"list\":[";

        for (var j = 1; j <= number; j++) {
            if ($('#tr' + j).html()) {
                var money = "\"money\":"+"\""+cupnumber($("#money"+j).val())+"\"";
                var percentsend = "\"percentsend\":"+"\""+cupnumberpercent($("#percentsend"+j).val())+"\"";
                
                list+="{"+money+","+percentsend+"},";
            }
        }

        if(list.length>8) list = list.substring(0, list.length - 1);

        list+="]";

        var json="{"+extractmax+","+list+"}";
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
                var percentsend = cupnumber($('#percentsend' + j).val());
                var obj = new create(j, money, percentsend);
                list.splice(list.length, 1, obj);
            }
        }

        var money = '<td><input class="form-control" onkeyup="editnumber(\'money' + i + '\')" type="text" id="money' + i + '" value="" /></td>';
        var percentsend = '<td><input class="form-control" onkeyup="editnumberpercent(event,\'percentsend' + i + '\')" type="text" id="percentsend' + i + '" value="" /></td>';
        var deletes = '<td><button class="form-control"  onclick="dele(\'tr' + i + '\')"><span style="font-size:13px;" class="icon-cancel-circle"></span></button></td>';
        table.html(table.html() + '<tr id="tr' + i + '">' + money + percentsend + deletes + '</tr>');
        number.val(i);

        for (var i = 0; i < list.length; i++) {
            $('#money' + list[i].number).val(list[i].money);
            $('#percentsend' + list[i].number).val(list[i].percentsend);
            editnumber("money"+(i+1));
            editnumberpercent("","percentsend"+(i+1));
        }
    }
    function create(number, money, percentsend) {
        this.number = number;
        this.money = money;
        this.percentsend = percentsend;
    }
    function dele(id) {
        /*var tr = document.getElementById(id);
        tr.innerHTML = "";
        tr.style = "display:none";*/
        $("#"+id).remove();
    }


    function load_data(i) {
        //$("#data").html('<img style="margin-left:45%;" src="img/pleasewait/plw.gif" />');
        $.ajax({
            url: "setting/show/load"+i+".php",
            type: "post",
            dataType: "json",
            data: {
            },
            success: function (result) {
                switch(i)
                {
                    case 1:
                        load_share_profits(result["accumulate"],result["percentcompanyreturn"],result["limitshare"]);
                        break;
                    case 2:
                        load_repurchase(result["firstreturnshare"],result["nextreturnshare"],result["limitreturnshare"]);
                        break;
                    case 3:
                        load_agency_introduce(result["levelf1return"],result["levelf2f5return"]);
                        break;
                    case 4:
                        load_agency_repurchase(result["extractmax"],result["list"]);
                        break;
                }
            }
        });
    }
    function load_share_profits(accumulate,percentcompanyreturn,limitshare) // show 1
    {
        $("#accumulate").val(accumulate);
        $("#percentcompanyreturn").val(percentcompanyreturn);
        $("#limitshare").val(limitshare);

        editnumber("accumulate");
        editnumberpercent("","percentcompanyreturn");
        editnumber("limitshare");
    }

    function load_repurchase(firstreturnshare,nextreturnshare,limitreturnshare) // show 2
    {
        $("#firstreturnshare").val(firstreturnshare);
        $("#nextreturnshare").val(nextreturnshare);
        $("#limitreturnshare").val(limitreturnshare);

        editnumberpercent("","firstreturnshare");
        editnumberpercent("","nextreturnshare");
        editnumberpercent("","limitreturnshare");
    }

    function load_agency_introduce(levelf1return,levelf2f5return)
    {
        $("#levelf1return").val(levelf1return);
        $("#levelf2f5return").val(levelf2f5return);

        editnumberpercent("","levelf1return");
        editnumberpercent("","levelf2f5return");
    }

    function load_agency_repurchase(extractmax,list)
    {
        $("#extractmax").val(extractmax);

        editnumberpercent("","extractmax");
        
        var table="";
        for(var i=0;i<list.length;i++)
        {
            var tr="<tbody><tr id=\"tr"+(i+1)+"\">";
            var money = '<td><input class="form-control" onkeyup="editnumber(\'money' + (i+1) + '\')" type="text" id="money' + (i+1) + '" value="'+list[i].money+'" /></td>';
            var percentsend = '<td><input class="form-control" onkeyup="editnumberpercent(event,\'percentsend' + (i+1) + '\')" type="text" id="percentsend' + (i+1) + '" value="'+list[i].percentsend+'" /></td>';
            var deletes = '<td><button class="form-control"  onclick="dele(\'tr' + (i+1) + '\')"><span style="font-size:13px;" class="icon-cancel-circle"></span></button></td>';
            tr+=money+percentsend+deletes+"</tr></tbody>";

            table+=tr;
        }

        $("#number").val(list.length);
        $("#table").html($("#table").html()+table);

        for(var i=0;i<list.length;i++)
        {
            editnumber("money"+(i+1));
            editnumberpercent("","percentsend"+(i+1));
        }
    }
    load_data(1);
    load_data(2);
    load_data(3);
    load_data(4);
</script>