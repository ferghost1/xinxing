<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
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
        <h3 class="panel-title">Sửa Mật Khẩu</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id='form'>
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">Nhập Mật Khẩu Hiện Tại:</label>
                <div class="col-sm-10">
                    <input class="form-control" id='pass' type="password" placeholder="Điền mật khẩu hiện tại" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">Nhập Mật Khẩu Mới:</label>
                <div class="col-sm-10">
                    <input class="form-control" id='newpass' type="password" placeholder="Điền mật khẩu mới" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">Nhập Lại Mật Khẩu Mới:</label>
                <div class="col-sm-10">
                    <input class="form-control" id='repeatnewpass' type="password" placeholder="Điền lại mật khẩu mới" />
                </div>
            </div>
            <div class="form-group"> 
                <div class="col-sm-offset-2 col-sm-10">
                    <input id='submit' name='submit' value="Lưu Lại" type="button" onclick="load_ajax()"  class="btn btn-primary"/>               
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function load_ajax() 
    {
        $("#submit").val('Đang Lưu...');
        $('#submit').attr('disabled', true);    
        $.ajax({
            url: "acc/chagepass/save.php",
            type: "post",
            dataType: "text",
            data: {
                submit:$('#submit').val(),
                pass:$('#pass').val(),
                newpass:$('#newpass').val(),
                repeatnewpass:$('#repeatnewpass').val(),
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
</script>