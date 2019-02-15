<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
$db = new apps_libs_Dbconn();
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

$param = [
    "select" => "acc.id,acc.user,detailacc.name",
    "from" => "acc,detailacc,relationshipacc",
    "where" => "relationshipacc.children='".$id."' and acc.id=relationshipacc.dadacc and detailacc.idacc=relationshipacc.dadacc"
];
$result_dad = $db->Select($param);

$param = [
    "select" => "acc.id,acc.user,detailacc.name",
    "from" => "acc,detailacc,relationshipacc",
    "where" => "relationshipacc.dadacc='".$id."' and acc.id=relationshipacc.children and detailacc.idacc=relationshipacc.children"
];
$result_child = $db->Select($param);


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
                <h3 class="panel-title">Tạo Quan Hệ</h3>
            </div>
            <div class="panel-body">
                <div class="row">
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
                </div>
                <input type="text" id="id" style="display:none" value=<?php echo '"' . $id . '"'; ?> />
                <div class="row" id="data">

                </div>
                <div class="row" >
                    <form class="form-horizontal" style="margin:25px auto 20px auto;" id='form'>
                        <input class="btn btn-primary" type="button" value="Thêm Vào Cha" onclick="add_dad()" />
                        <input class="btn btn-primary" type="button" value="Thêm Vào Con" onclick="add_child()" />
                        <input id="submit" class="btn btn-primary" type="button" value="Lưu Lại" onclick="send_data()" />                   
                    </form>
                </div>
                <div class="row">
                    <div id="list-dad" class="col-sm-6 h-border">
                        <h3>DANH SÁCH SPONSOR</h3>
                        <table id="tabledad" class="table table-striped table-hover">
                            <tr>
                                <th>Tài Khoản</th>
                                <th>Tên</th>
                                <th>Xóa</th>
                            </tr>
                            <?php
                                
                                while($row_dad = mysqli_fetch_assoc($result_dad))
                                {
                                    echo "<tbody>";
                                    echo '<tr id="tr'.$row_dad['id'].'">
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input class="h-cheack" type="checkbox" id="'.$row_dad['id'].'" style="display: none;">
                                                    '.$row_dad['user'].'
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            '.$row_dad['name'].'
                                        </td>
                                        <td>
                                            <button onclick="move_dc_tolist(\'tr'.$row_dad['id'].'\')" class="form-control">
                                                <span style="font-size:13px;" class="icon-cancel-circle">
                                                </span>
                                            </button>
                                        </td>
                                    </tr>';
                                    echo "</tbody>";
                                }
                            ?>
                        </table>
                    </div>
                    <div id="list-child" class="col-sm-6 h-border">
                        <h3>DANH SÁCH KHÁCH HÀNG</h3>
                        <table id="tablechild" class="table table-striped table-hover">
                            <tr>
                                <th>Tài Khoản</th>
                                <th>Tên</th>
                                <th>Xóa</th>
                            </tr>
                            <?php
                                
                                while($row_child = mysqli_fetch_assoc($result_child))
                                {
                                    echo "<tbody>";
                                    echo '<tr id="tr'.$row_child['id'].'">
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input class="h-cheack" type="checkbox" id="'.$row_child['id'].'" style="display: none;">
                                                    '.$row_child['user'].'
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            '.$row_child['name'].'
                                        </td>
                                        <td>
                                            <button onclick="move_dc_tolist(\'tr'.$row_child['id'].'\')" class="form-control">
                                                <span style="font-size:13px;" class="icon-cancel-circle">
                                                </span>
                                            </button>
                                        </td>
                                    </tr>';
                                    echo "</tbody>";
                                }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function load_ajax(number,value=null,max=null) {
                $("#data").html('<img style="margin-left:45%;" src="img/pleasewait/plw.gif" />');
                if(!max)max=$("#max-row").val();
                json=create_json();
                $.ajax({
                    url: "acc/relationshipacc/loadacc.php",
                    type: "post",
                    dataType: "text",
                    data: {
                        id: $('#id').val(),
                        data: json,
                        number:number,
                        s:value,
                        max:max
                    },
                    success: function (result) {
                        $('#data').html(result);
                        $('#data').slideUp(50,'swing').fadeIn(200);
                    }
                });
            }
            function add_dad() {
                var height = $('#data').css('height');

                var x = $(".h-cheack");
                for (var i = 0; i < x.length; i++) {
                    if ($(x[i]).is(":checked")) {
                        move_list_todc('dad', x[i]);
                    }
                }
                $('#data').css('height', height);
            }
            function add_child() {
                var height = $('#data').css('height');
                var x = $(".h-cheack");
                for (var i = 0; i < x.length; i++) {
                    if ($(x[i]).is(":checked")) {
                        move_list_todc('child', x[i]);
                    }
                }
                $('#data').css('height', height);
            }

            function move_list_todc(where, x) {
                var id = 'tr' + $(x).attr('id');
                $(x).css("display", "none");
                if (where == 'dad') {
                    var dele = '<td><button onclick="move_dc_tolist(\'' + id + '\')" class="form-control"><span style="font-size:13px;" class="icon-cancel-circle"></span></button></td>';
                    var html = $('#tr' + $(x).attr('id')).html();
                    $('#tr' + $(x).attr('id')).remove();
                    $("#tabledad").html($("#tabledad").html() + "<tr id='" + id + "' >" + html + dele + "</tr>");
                    $('#tabledad').slideUp(50,'swing').fadeIn(200);
                }
                else {
                    var dele = '<td><button onclick="move_dc_tolist(\'' + id + '\')" class="form-control"><span style="font-size:13px;" class="icon-cancel-circle"></span></button></td>';
                    var html = $('#tr' + $(x).attr('id')).html();
                    $('#tr' + $(x).attr('id')).remove();
                    $("#tablechild").html($("#tablechild").html() + "<tr id='" + id + "' >" + html + dele + "</tr>");
                    $('#tablechild').slideUp(50,'swing').fadeIn(200);
                }
                set_height_dc();
            }

            function move_dc_tolist(id) {
                var child = $("#" + id).children();
                $(child[child.length - 1]).remove();

                check = $("#" + id.slice(2, id.length)).css("display", "inline");

                var html = $("#" + id).html();
                html = "<tr id= '" + id + "'>" + html + "</tr>";

                $("#" + id).remove();

                $("#table").html($("#table").html() + html);
                $('#table').slideUp(50,'swing').fadeIn(200);
                set_height_dc();
                $('#data').css('height', "auto");
            }
            
            function set_height_dc()
            {
                var h_dad=$('#tabledad').css('height');
                var h_child=$('#tablechild').css('height');
                h_dad=parseInt(cupnumber(h_dad));
                h_child=parseInt(cupnumber(h_child));
                var max=h_child;
                if(h_dad>h_child) max=h_dad;
                $("#list-child").css("height",max+60+"px");
                $("#list-dad").css("height",max+60+"px");
            }

            function get_json_table_dad()
            {
                var child = $("#tabledad").children("tbody");
                var json='[';
                for(var i=0;i<child.length;i++) 
                    if($(child[i]).html())
                    {
                        var tr=$(child[i]).children("tr")[0];
                        if($(tr).attr('id')) 
                        {
                            id=$(tr).attr('id');
                            json+="\""+id.slice(2,id.length)+"\",";
                        }
                    }
                if (json.length > 1)
                    json = json.substring(0, json.length - 1);
                json+=']';
                return json;
            }

            function get_json_table_child()
            {
                var child = $("#tablechild").children("tbody");
                var json='[';
                for(var i=0;i<child.length;i++) 
                    if($(child[i]).html())
                    {
                        var tr=$(child[i]).children("tr")[0];
                        if($(tr).attr('id')) 
                        {
                            id=$(tr).attr('id');
                            json+="\""+id.slice(2,id.length)+"\",";
                        }
                    }
                if (json.length > 1)
                    json = json.substring(0, json.length - 1);
                json+=']';
                return json;
            }

            function create_json()
            {
                var json="{\"dad\":";
                json+=get_json_table_dad();
                json+=",\"child\":";
                json+=get_json_table_child();
                json+="}";

                return json;
            }

            function send_data()
            {
                json=create_json();
                //alert(json);
                //return;
                $("#nobox").css("display", "none");
                $("#submit").val('Đang Lưu...');
                $('#submit').attr('disabled', true);
                $.ajax({
                    url: "acc/relationshipacc/save.php",
                    type: "post",
                    dataType: "text",
                    data: {
                        submit: $('#submit').val(),
                        id: $('#id').val(),
                        data: json
                    },
                    success: function (result) {
                        $('#result').html(result);
                        $("#nobox").css("display", "block");
                        $('#submit').removeAttr('disabled');
                        $("#submit").val('Lưu Lại');
                        up_page();
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
            load_ajax(1);
            set_height_dc();
            $("#go-return").css("display","none");
        </script>