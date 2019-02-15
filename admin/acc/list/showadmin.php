<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
$id = '';
if ($rt->GetGet('id')) {
    $id = $rt->GetGet('id');
}
?>  
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Danh Sách Tài Khoản</h3>
        </div>
        <div class="panel-body">
            <div class="serchform row">
                <div class="col-lg-8">
                    <div class="form-group input-group">
                        <input onkeydown="h_key_enter(event)" id="h-tf" class="form-control" placeholder="Nhập tài khoản hoặc tên bạn muốn tìm" type="text">
                        <span class="input-group-btn">
                            <button onclick="search_acc('h-tf')" class="btn btn-default" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                            <button id="go-return" onclick="go_return()" class="btn btn-default" type="button">
                                <i class="glyphicon glyphicon-menu-left"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <select onchange="set_max_row()" id="max-row" class="form-control">
                        <option>5</option>
                        <option selected>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>
            </div><div id="data">
            </div>
        </div>
    </div>
    <script>
        function load_ajax(number,value=null,max=null) {
            $("#data").html('<img style="margin-left:45%;" src="img/pleasewait/plw.gif" />');
            if(!max)max=$("#max-row").val();
                $.ajax({
                    url: "acc/list/loadaccadmin.php",
                    type: "post",
                    dataType: "text",
                    data: {
                        number:number,
                        s:value,
                        max:max
                    },
                    success: function (result) {
                        $('#data').html(result);
                        //$("#plw").css("display","none");
                        $('#data').slideUp(50,'swing').fadeIn(200);
                    }
                });
            }
        function search_acc(id) {
            var value = $("#" + id).val();
            load_ajax(1,value);
            $("#go-return").css("display","inline");
        }

        function h_key_enter(e) {
            var key = e.which;
            if (key == 13) {
                search_acc('h-tf');
            }
        }
        function go_return()
        {
            load_ajax(1);
            $("#go-return").css("display","none");
        }
        function set_max_row()
        {
            var max= $("#max-row").val();
            load_ajax(1,"",max);
        }
        $("#go-return").css("display","none");
        load_ajax(1);
    </script>