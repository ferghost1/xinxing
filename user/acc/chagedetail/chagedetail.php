<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
?>

<?php
    $user=new apps_libs_UserLogin();
    $id=$user->GetAcc();
    $param=[
        "select"=>"acc.user,acc.pass,acc.active,detailacc.name,detailacc.identitycard,detailacc.maill,detailacc.phonenumber,detailacc.linkimg",
        "from"=>"acc,detailacc",
        "where"=>"acc.id='".$id."' and detailacc.idacc='".$id."'"
    ];
    $db=new apps_libs_Dbconn();
    $result=$db->SelectOne($param);
    $row=mysqli_fetch_assoc($result);
?>
    <div class="no-box alert alert-success" id="nobox">
        <div class="content-no-box" id='result'>
            Thông Báo
        </div>
        <div class="close-no-box">
            <span class="icon-cancel-circle" onclick="close_box('nobox')"></span>
        </div>
        <div class="clearfix-no-box">
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Thông Tin Tài Khoản</h3>
        </div>
        <div class="panel-body">
        <form class="form-horizontal" id='form' enctype="multipart/form-data">
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Ảnh Đại Diện:</label>
            <div class="col-sm-2">
            <img id="imgavata" class="img-thumbnail" style="width:100px;height:100px" src=<?php echo '"'.$rt->GetLinkImg($row['linkimg']).'"' ?> />
            </div>
            <div class="col-sm-8"> 
                <input id='file' name='file' type="file" onchange="upload_img()" class="btn btn-default" />
                <input value=<?php echo '"'.$row['linkimg'].'"' ?> id='imgname' name='imgname' type="text" style="display:none" />
                <input value=<?php echo '"'.$id.'"' ?> id='id' name='id' type="text" style="display:none" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Tên Đăng Nhập:</label>
            <div class="col-sm-10">
                <input disabled  value=<?php echo '"'.$row['user'].'"' ?> class="form-control" id='user' name='user' type="text" placeholder="Tên Đăng Nhập" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Mã Giới Thiệu:</label>
            <div class="col-sm-10">
                <input disabled  value=<?php echo '"'.$id.'"' ?> class="form-control" type="text" placeholder="Mã Giới Thiệu" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập Tên:</label>
            <div class="col-sm-10"> 
                <input value=<?php echo '"'.$row['name'].'"' ?> id='name' name='name' type="text" class="form-control" placeholder="Điền Tên Người Sử Dụng Mới" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập CMND:</label>
            <div class="col-sm-10"> 
                <input value=<?php echo '"'.$row['identitycard'].'"' ?> id='identitycard' name='identitycard' type="text" class="form-control" placeholder="Điền Chứng Minh Nhân Dân Mới" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập Maill:</label>
            <div class="col-sm-10"> 
                <input value=<?php echo '"'.$row['maill'].'"' ?> id='maill' name='maill' type="email" class="form-control" placeholder="Điền Email Mới" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Nhập SDT:</label>
            <div class="col-sm-10"> 
                <input value=<?php echo '"'.$row['phonenumber'].'"' ?> id='phonenumber' name='phonenumber' type="text" class="form-control" placeholder="Điền Số Điện Thoại Mới" />
            </div>
        </div>
        <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
                <input id='submit' name='submit' value="Lưu Lại" type="button" onclick="senddata()"  class="btn btn-default"/>
            </div>
        </div>
    </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2" style="padding-left:0">
            <a href="?r=acc&p=chagepass" class="btn btn-info" style="width:100%;">
                <span style="float:left">Sửa mật khẩu</span>
                <i style="float:right" class="fa fa-arrow-circle-right"></i>
                <div class="clearfix"></div>
            </a>
        </div>
    </div>
    
    
    <script src="js/jquery.js" type="text/javascript">
    </script>
    <script>
        function senddata() {
            $("#nobox").css("display","none");
            if(cheack_data)
                
                //$("#submit").val('Đang Lưu...');
                //$('#submit').attr('disabled', true);  
                load_ajax();
                //$("#submit").val('Lưu Lại');
                //$('#submit').removeAttr('disabled');
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
                url: "acc/chagedetail/save.php",
                type: "post",
                dataType: "text",
                data: {
                    submit:$('#submit').val(),
                    name:$('#name').val(),
                    identitycard:$('#identitycard').val(),
                    maill:$('#maill').val(),
                    phonenumber:$('#phonenumber').val(),
                    imgname:$('#imgname').val()
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
                    $("#imgavata").attr("src","../"+res);
            }
        });
        } else{
                $('#file').val('');
                alert("Không hỗ trợ loại ảnh này");
        }
        return false;
        }
    </script>