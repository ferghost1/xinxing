<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
if (!$rt->GetGet('id')) die();
$id = $rt->GetGet('id');

$param = [
    "select" => "acc.user,acc.pass,acc.active,detailacc.name,detailacc.identitycard,detailacc.maill,detailacc.phonenumber,detailacc.linkimg",
    "from" => "acc,detailacc",
    "where" => "acc.id='" . $id . "' and detailacc.idacc='" . $id . "'"
];
$db = new apps_libs_Dbconn();
$result = $db->SelectOne($param);
$row = mysqli_fetch_assoc($result);
?>

    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Thông Tin Tài Khoản</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" id='form' enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email">Ảnh Đại Diện:</label>
                    <div class="col-sm-4">
                        <img class="img-thumbnail" style="width:100px;height:100px" src=<?php echo '"' . $rt->GetLinkImg($row['linkimg']) . '"' ?> />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="email"> Tên Đăng Nhập:</label>
                    <div class="col-sm-4">
                        <input disabled style="border:0px" value=<?php echo '"' . $row[ 'user'] . '"' ?> class="form-control" id='user' name='user' type="text" />
                    </div>
                    <label class="control-label col-sm-2" for="pwd"> Tên:</label>
                    <div class="col-sm-4">
                        <input disabled style="border:0px" value=<?php echo '"' . $row[ 'name'] . '"' ?> id='name' name='name' type="text" class="form-control"
                        />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd"> CMND:</label>
                    <div class="col-sm-4">
                        <input disabled style="border:0px" value=<?php echo '"' . $row[ 'identitycard'] . '"' ?> id='identitycard' name='identitycard' type="text" class="form-control" />
                    </div>
                    <label class="control-label col-sm-2" for="pwd"> Maill:</label>
                    <div class="col-sm-4">
                        <input disabled style="border:0px" value=<?php echo '"' . $row[ 'maill'] . '"' ?> id='maill' name='maill' type="email" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pwd"> SDT:</label>
                    <div class="col-sm-4">
                        <input disabled style="border:0px" value=<?php echo '"' . $row[ 'phonenumber'] . '"' ?> id='phonenumber' name='phonenumber' type="text" class="form-control"/>
                    </div>
                    <label class="control-label col-sm-2" for="pwd">Tình Trạng Kích Hoạt:</label>
                    <div class="col-sm-4">
                        <input disabled style="border:0px" value=<?php echo '"' . $row[ 'active'] . '"' ?> id='phonenumber' name='phonenumber' type="text" class="form-control" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Chức Năng</h3>
        </div>
        <div class="panel-body">
            <div class="">
                <div class="row" style="margin-top:20px;">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <span class="huge icon-pencil"></span>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"></div>
                                        <div>Sửa Thông Tin Tài Khoản</div>
                                    </div>
                                </div>
                            </div>
                            <a href=<?php echo "?r=acc&p=detail&id=" . $id ?>>
                                <div class="panel-footer">
                                    <span class="pull-left">Sửa</span>
                                    <span class="pull-right">
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <span class="huge icon-magnet"></span>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"></div>
                                        <div>Tạo Mối Liên Hệ</div>
                                    </div>
                                </div>
                            </div>
                            <a href=<?php echo "?r=acc&p=showrelationshipacc&id=" . $id ?>>
                                <div class="panel-footer">
                                    <span class="pull-left">Tạo</span>
                                    <span class="pull-right">
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-1"></div>
                </div>



                <div class="row" style="margin-top:20px;">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <span class="huge icon-database"></span>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"></div>
                                        <div>Thêm Sản Phẩm</div>
                                    </div>
                                </div>
                            </div>
                            <a href=<?php echo "?r=acc&p=product&id=" . $id ?>>
                                <div class="panel-footer">
                                    <span class="pull-left">Thêm</span>
                                    <span class="pull-right">
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <span class="huge icon-coin-dollar"></span>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"></div>
                                        <div>Xem Doanh Thu</div>
                                    </div>
                                </div>
                            </div>
                            <a href=<?php echo "?r=total&p=calacc&id=" . $id ?>>
                                <div class="panel-footer">
                                    <span class="pull-left">Xem</span>
                                    <span class="pull-right">
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <p><button onclick=dele() class="btn btn-lg btn-link" style="color:red">Xóa Tài Khoản <i class="fa fa-arrow-circle-right"></i></a></button>
    </div>

    <script>
        function dele()
        {
            var id=<?php echo "'".$id."'" ?>;
            if(confirm("Bạn chắc chắn xóa không? THAO TÁC NÀY KHÔNG THỂ PHỤC HỒI!"))
            {         
                $.ajax({
                    url: "acc/menufunction/deleacc.php",
                    type: "post",
                    dataType: "text",
                    data: {
                        id:id,
                    },
                    success: function (result) {
                        window.location="?r=acc&p=show";
                    }
                });
            }
        }
    </script>