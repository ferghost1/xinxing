<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
$db = new apps_libs_Dbconn();
if (!$user->CheckAdmin()) $rt->LoginPage();
$id = '';
if ($rt->GetGet('id')) {
    $id = $rt->GetGet('id');
}
$his_pro = $db->query("select * from historyproduct where idacc = '{$id}' ");

$param = [
    "select" => "acc.user,detailacc.name,detailacc.linkimg,acc.repurchase_money",
    "from" => "acc,detailacc",
    "where" => "acc.id='" . $id . "' and detailacc.idacc='" . $id . "'"
];

$result_acc = $db->SelectOne($param);
$row_acc = mysqli_fetch_assoc($result_acc);
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
            <div class="row alert alert-info alert-dismissable">
                <div class="col-sm-6">
                    <from class="form-horizontal" style="border-bottom: 1px solid #DDDDDD;">
                        <div class="form-group">
                            <div class="row">
                                <label class="control-label col-sm-2"> Tài Khoản:</label>
                                <div class="col-sm-10">
                                    <input disabled style="border:0px" value=<?php echo '"' . $row_acc[ 'user'] . '"' ?> class="form-control" type="text" placeholder="Tên Đăng Nhập" />
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-sm-2">Tên:</label>
                                <div class="col-sm-10">
                                    <input disabled style="border:0px" value=<?php echo '"' . $row_acc[ 'name'] . '"' ?> type="text" class="form-control" placeholder="Tên" />
                                </div>
                            </div><br>
                            <div class="row">
                                <label class="control-label col-sm-2">Tiền tái mua:</label>
                                <div class="col-sm-10">
                                    <input disabled style="border:0px" value=<?= number_format($row_acc['repurchase_money'])?> type="text" class="form-control" placeholder="Tên" />
                                </div>
                            </div>
                        </div>
                        </form>
                </div>
                <div class="col-sm-6">
                    <img class="img-thumbnail" style="width:100px;height:80px" src=<?php echo '"'.$rt->GetLinkImg($row_acc['linkimg']).'"' ?> />
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Lịch Sử Giao Dịch</h3>
        </div>
        <div class="panel-body">
        <div class="col-sm-4">
                    <a href=<?php echo '"?r=acc&p=productcrt&id=' . $id . '"' ?>>
                        <span class="icon-plus" style="font-size:30px;color:#339966;"></span>
                    </a>
                </div>
            <div class="h-sf" style="display:none">
                <div class="serchform col-sm-4">
                    <div class="form-group input-group">
                        <input onkeydown="h_key_enter(event)" id="h-tf" class="form-control" placeholder="Nhập tên lịch sử bạn muốn tìm" type="text">
                        <span class="input-group-btn">
                            <button onclick="search_acc('h-tf')" class="btn btn-default" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <a href=<?php echo '"?r=acc&p=productcrt&id=' . $id . '"' ?>>
                        <span class="icon-plus" style="font-size:30px;color:#339966;"></span>
                    </a>
                </div>
            </div>
            <div id="table">
                <table class="table table-bordered table-hover">
                    <thead>
                        <th>Thời gian tạo</th>
                        <th>Tiền tái mua</th>
                        <th>Ghi chú</th>
                        <th>Xóa</th>
                    </thead>
                    <tbody>
                        <?php foreach($his_pro as $v):?>
                            <tr id ="<?= $v->id?>">
                                <td><a href="?r=acc&p=productcrt&id=<?= $id?>&idhtr=<?= $v->id ?>"><?= date("Y.m.d",strtotime($v->timecreate))?></a> </td>
                                <td><?= $v->repurchase_money?> </td>
                                <td><?= $v->note?> </td>
                                <td><button class="form-control" onclick="dele('<?=$v->id?>','<?=$v->timecreate?>')"><span style="font-size:13px;" class="icon-cancel-circle"></span></button> </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function search_acc(id) {
            var value = $("#" + id).val();
            load_ajax(1,value);
        }

        function h_key_enter(e) {
            var key = e.which;
            if (key == 13) {
                search_acc('h-tf');
            }
        }
        function load_ajax(number,s) {
            var id=<?php echo "\"".$id."\"" ?>;
            $.ajax({
                url: "acc/product/loadlist.php",
                type: "post",
                dataType: "text",
                data: {
                    id:id,
                    number:number,
                    s:s
                },
                success: function (result) {
                    $('#table').html(result);
                }
            });
        }
        function dele(id,name)
        {
            if(confirm("Bạn có muốn xóa: '"+name+"' không? THAO TÁC NÀY KHÔNG THỂ PHỤC HỒI!"))
            {
                $("#"+id).remove();

                $.ajax({
                    url: "acc/product/deletelist.php",
                    type: "post",
                    dataType: "text",
                    data: {
                        id:id,
                    },
                    success: function (result) {
                        console.log(result);
                        $('#result').html(result);
                        up_page();
                    }
                });
            }
        }
        // load_ajax(1);
    </script>