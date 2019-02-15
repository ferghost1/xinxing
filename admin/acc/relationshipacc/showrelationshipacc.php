<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
?>

<?php
$id = '';
if ($rt->GetGet('id')) {
    $id = $rt->GetGet('id');
}
$param = [
    "select" => "acc.user,detailacc.name,detailacc.linkimg",
    "from" => "acc,detailacc",
    "where" => "acc.id='" . $id . "' and detailacc.idacc='" . $id . "'"
];
$db = new apps_libs_Dbconn();
$result_acc = $db->SelectOne($param);
$row_acc = mysqli_fetch_assoc($result_acc);
?>
<input id="id" type="text" value=<?php echo '"' . $id . '"'; ?> style="display:none" />
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Thông Tin Tài Khoản</h3>
    </div>
    <div class="panel-body">
        <div class="row alert alert-info alert-dismissable">
            <div class="col-sm-6">
                <from class="form-horizontal" style="border-bottom: 1px solid #DDDDDD;">
                    <div class="form-group">
                        <div class="row">
                            <label class="control-label col-sm-2"> Tài Khoản:</label>
                            <div class="col-sm-10">
                                <input disabled style="border:0px" value=<?php echo '"' . $row_acc['user'] . '"' ?> class="form-control" type="text" placeholder="Tên Đăng Nhập" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-sm-2">Tên:</label>
                            <div class="col-sm-10">
                                <input disabled style="border:0px" value=<?php echo '"' . $row_acc['name'] . '"' ?> type="text" class="form-control" placeholder="Tên" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                <img class="img-thumbnail" style="width:100px;height:80px" src=<?php echo '"' . $rt->GetLinkImg($row_acc['linkimg']) . '"' ?> />
            </div>
        </div>
    </div>
</div>
<p><a style="font-size:18px;" href=<?php echo '"?r=acc&p=relationshipacc&id=' . $id . '"'; ?>>Sửa đổi mối quan hệ <i class="fa fa-arrow-circle-right"></i></a></p>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">DANH SÁCH SPONSOR</h3>
        <span id="title-dadacc"></span>
    </div>
    <div class="panel-body">
        <button id="go-return-dadacc" onclick="go_return('dadacc')" class="btn btn-default" type="button">
            <i class="glyphicon glyphicon-menu-left"></i>
        </button>
        <div id="dadacc">
        </div>
    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">DANH SÁCH KHÁCH HÀNG</h3>
        <span id="title-children"></span>
    </div>
    <div class="panel-body">
        <button id="go-return-children" onclick="go_return()" class="btn btn-default" type="button">
            <i class="glyphicon glyphicon-menu-left"></i>
        </button>
        <div id="children">
        </div>
    </div>
</div>

<script>
    function load_ajax(data,where,value=null,max=null) {
        $('#'+where).html('<img style="margin-left:45%;" src="img/pleasewait/plw.gif" />');
        if(!max)max=$("#max-row").val();
        $.ajax({
            url: "acc/relationshipacc/loadshow.php",
            type: "post",
            dataType: "json",
            data: {
                id: $('#id').val(),
                data:data,
                where:where,
                s:value,
                max:max
            },
            success: function (result) {
                $('#'+where).html(result.table);
                $('#'+where).slideUp(50,'swing').fadeIn(200);
                $('#title-'+where).html(result.title);
            }
        });
    }    
    function next_acc(data,where)
    {
        load_ajax(data,where);
        $("#go-return-"+where).css("display","inline");
        if(data==$("#id").val()) $("#go-return-"+where).css("display","none");
    }
    function go_return(who=null)
    {
        if(who=="dadacc")
        {
            load_ajax($("#id").val(),"dadacc");
            $("#go-return-dadacc").css("display","none");
        }
        else
        {
            load_ajax($("#id").val(),"children");
            $("#go-return-children").css("display","none");
        }
    }
    $("#go-return-dadacc").css("display","none");
    $("#go-return-children").css("display","none");
    load_ajax($("#id").val(),"dadacc");
    load_ajax($("#id").val(),"children");
</script>