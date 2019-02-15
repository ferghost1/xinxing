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
            <h3 class="panel-title">Thêm Tài Khoản</h3>
        </div>
        <div class="panel-body">
        <form class="form-horizontal" id='form' enctype="multipart/form-data">
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập Tên:</label>
            <div class="col-sm-10"> 
                <input id='name' name='name' type="text" class="form-control" placeholder="Điền Tên Người Sử Dụng" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập SDT:</label>
            <div class="col-sm-10"> 
                <input id='phonenumber' name='phonenumber' type="text" class="form-control" placeholder="Điền Số Điện Thoại (có thể bỏ trống)" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Nhập Tên Đăng Nhập:</label>
            <div class="col-sm-10">
                <input class="form-control" id='user' name='user' type="text" placeholder="Điền Tên Đăng Nhập" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập Mật Khẩu:</label>
            <div class="col-sm-10"> 
                <input type="password" class="form-control" id="pass" placeholder="Điền Mật Khẩu">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập Tên Ngân Hàng:</label>
            <div class="col-sm-10"> 
                <input id='bank' type="text" class="form-control" placeholder="Điền Tên Ngân Hàng (có thể bỏ trống)" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập Tên Chủ Khoản:</label>
            <div class="col-sm-10"> 
                <input id='bankaccountname' type="text" class="form-control" placeholder="Điền Tên Chủ Khoản (có thể bỏ trống)" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập Số Tài Khoản:</label>
            <div class="col-sm-10"> 
                <input id='bankaccountnumber' type="text" class="form-control" placeholder="Điền Số Tài Khoản (có thể bỏ trống)" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập CMND:</label>
            <div class="col-sm-10"> 
                <input id='identitycard' name='identitycard' type="text" class="form-control" placeholder="Điền Chứng Minh Nhân Dân (có thể bỏ trống)" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập Maill:</label>
            <div class="col-sm-10"> 
                <input id='maill' name='maill' type="email" class="form-control" placeholder="Điền Email (có thể bỏ trống)" />
            </div>
        </div>
        <div id="type" class="form-group" style="display:none">
            <label class="control-label col-sm-2" for="pwd">Loại Tài Khoản:</label>
            <div class="col-sm-10"> 
                <select id='types' name='type' class="form-control">
                    <option>user</option>    
                    <option>admin</option>  
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Ảnh đại diện</label>
            <div class="col-sm-10"> 
                <input id='file' name='file' type="file" onchange="upload_img()" class="btn btn-default" />
                <input id='imgname' name='imgname' type="text" style="display:none" />
            </div>
        </div>
        <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
                <input id='submit' name='submit' value="Lưu Lại" type="button" onclick="senddata()"  class="btn btn-primary"/>            
            </div>
        </div>
    </form>
        </div>
    </div>
    
    
    <script src="js/jquery.js" type="text/javascript">
    </script>
    <script>
        $("#timecreate").val(get_time_now());
        
        function senddata() {
            $("#nobox").css("display","none");
            if(cheack_data)
                load_ajax();
            else 
            {
                $("#result").val('Chưa nhập đủ dữ liệu');
                $("#nobox").css("display","block");
            }
        }
        function load_ajax() {
            $("#submit").val('Đang Lưu...');
            $('#submit').attr('disabled', true);    
            $.ajax({
                url: "acc/create/save.php",
                type: "post",
                dataType: "text",
                data: {
                    submit:$('#submit').val(),
                    user:$('#user').val(),
                    pass:$('#pass').val(),
                    name:$('#name').val(),
                    identitycard:$('#identitycard').val(),
                    maill:$('#maill').val(),
                    phonenumber:$('#phonenumber').val(),
                    type:$('#types').val(),
                    imgname:$('#imgname').val(),
                    bank:$('#bank').val(),
                    bankaccountname:$('#bankaccountname').val(),
                    bankaccountnumber:$('#bankaccountnumber').val()
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

        function cheack_data()
        {
            if($('#user').val()==""||$('#pass').val()==""||$('#name').val()==""||$('#identitycard').val()==""||$('#maill').val()==""||$('#phonenumber').val()=="")
                return false;
            else return true;
        }
        function upload_img()
        {
        //Lấy ra files
        var file_data = $('#file').prop('files')[0];
        //lấy ra kiểu file
        var type = file_data.type;
        //Xét kiểu file được upload
        var match= ["image/png","image/jpg","image/jpeg"];
        //kiểm tra kiểu file
        if(type == match[0] || type == match[1]|| type == match[2])
        {
            //khởi tạo đối tượng form data
            var form_data = new FormData();
            //thêm files vào trong form data
            form_data.append('file', file_data);
            //sử dụng ajax post
            $.ajax({
                url: 'acc/uploadimg.php', // gửi đến file upload.php 
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                       
                type: 'post',
                success: function(res){
                    $('#imgname').val(res);
                    //$('#resultfile').html(res);
            }
        });
        } else{
                $('#file').val('');
                alert("Không hỗ trợ loại ảnh này");
        }
        return false;
        }
    </script>

<?php
    if((new apps_libs_UserLogin())->CheckRoot())
    {
        echo '<script>
            $("#type").css("display","block");
        </script>';
    }
?>