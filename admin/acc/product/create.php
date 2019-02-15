<?php
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
$db = new apps_libs_Dbconn();
if (!$user->CheckAdmin()) $rt->LoginPage();
// try{
//     $db->transaction('autocommit');
//     $db->trans_voidQuery('insert into test_trans(value) values("chóa1")');
//     $db->trans_voidQuery('insert into test_trans(value) values("chóa2")');
//     // $db->transaction('commit');
    
//     // $db->Connect();
//     $db->trans_voidQuery('insert into test_trans(value) values("chóa3")');
//     $db->trans_voidQuery('update test_trans set value = 1 where id = 1');
//     $db->transaction('commit');
    
// }
// catch(Exception $e){
//    $db->transaction('rollback');
// }
$number=1;
if (!$rt->GetGet('id')) die();
$id = $rt->GetGet('id');

$idhtr="";
$result_lpd=NULL;
$result_htr=NULL;
// Tiền tái mua của acc
$acc_repurchase = $db->query("select * from acc where id = '{$id}'",true)->repurchase_money;
// Nếu có idhistory thì update
if($rt->GetGet('idhtr'))
{
    $idhtr=$rt->GetGet('idhtr');
    $param=[
        "select"=>"*",
        "from"=>"listproduct",
        "where"=>"idhp='".$idhtr."'"
        ];
    $db=new apps_libs_Dbconn();
    $result_lpd=$db->Select($param);

    $param1=[
        "select"=>"*",
        "from"=>"historyproduct",
        "where"=>"id='".$idhtr."'"
        ];

    $result_htr=$db->Select($param1);
}
$param = [
    "select" => "acc.user,detailacc.name,detailacc.linkimg",
    "from" => "acc,detailacc",
    "where" => "acc.id='" . $id . "' and detailacc.idacc='" . $id . "'"
];
$db = new apps_libs_Dbconn();
$result_acc = $db->SelectOne($param);
$row_acc = mysqli_fetch_assoc($result_acc);
$row_htr=NULL;
if($result_htr) $row_htr=mysqli_fetch_assoc($result_htr);
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
                <h3 class="panel-title">Thêm Giao Dịch</h3>
            </div>
            <div class="panel-body">
                <from class="form-horizontal" id='form'>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Thời Gian Giao Dịch:</label>
                        <div class="col-sm-4">
                            <input value=<?php echo '"' . explode( " ",$row_htr[ 'timecreate'])[0] . '"' ?> id='time' name='time' type="date" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Ghi Chú:</label>
                        <div class="col-sm-10">
                            <textarea id='note' name='note' class="form-control" placeholder="Ghi chú"><?php echo $row_htr['note'] ?></textarea>
                        </div>
                    </div>
                    <table style="border-bottom:10px solid #DDDDDD" id="table" class='table table-striped table-hover'>
                        <tr>
                            <th>Tên</th>
                            <th>Giá</th>
                            <th>Số Lượng</th>
                            <th>Delete</th>
                            <tr>
                                <?php
        if($result_lpd!=NULL)
        {
            while($row=mysqli_fetch_assoc($result_lpd))
            {
                ?>
                                    <tr <?php echo 'id="tr'.$number. '"' ?>>
                                        <td>
                                            <?php
            echo '<input class="form-control" type="text" id="name'.$number.'" value="'.$row["name"].'" />';
            ?>
                                        </td>
                                        <td>
                                            <?php
            echo '<input class="form-control" type="text" id="price'.$number.'" onkeyup="totalmoney(\'price'.$number.'\')" value="'.$row["price"].'" />';
            ?>
                                        </td>
                                        <td>
                                            <?php
            echo '<input class="form-control" type="text" id="quantity'.$number.'" onkeyup="totalmoney(\'quantity'.$number.'\')" value="'.$row["quantity"].'" />';
            ?>
                                        </td>
                                        <td>
                                            <?php
            echo '<button class="form-control"  onclick="dele(\'tr'.$number.'\')"><span style="font-size:13px;" class="icon-cancel-circle"></span></button>';
            ?>
                                        </td>
                                    </tr>
                                    <?php 
            $number++;
            }
            ?>
                                    <?php
        }
        ?>
                    </table>
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-sm-1">
                            <input id="number" value=<?php echo '"' . ($number-1) . '"' ?> type="text" name="number" style="display: none"/>
                            <input id="id" value=<?php echo '"' . $id . '"' ?> type="text" style="display: none"/>
                            <input id="idhtr" value=<?php echo '"'.$idhtr. '"' ?> type="text" style="display: none"/>
                            <button style="font-size:15px;" class="form-control" onclick="addnewcl()">
                                <span class="icon-plus"></span>
                            </button>
                        </div>
                    </div>
                    <div class="row" style="padding:5px">
                        <label class="col-sm-2"> Tổng Tiền:</label>
                        <div class="col-sm-4">
                            <input disabled style="border:0px" id="totalmoney" value="0" class="form-control" type="text" placeholder="Tổng tiền" />
                        </div>
                    </div>
                    <div class="row" style="padding:5px">
                        <label class="col-sm-2"> Tiền Tái Mua:</label>
                        <div class="col-sm-4">
                            <input disabled style="border:0px" id="repurchase_money" value="<?=number_format($row_htr['repurchase_money'])?>" class="form-control" type="text" placeholder="Tổng tiền" />
                            
                        </div>
                        <div class="col-sm-12" >
                            <label>
                                <input type="checkbox" name="is_repurchase">
                                Dùng tiền tái mua hàng(Còn <?= number_format($acc_repurchase) ?>)
                            </label>
                        </div>
                    </div>
                    <div class="row" style="padding:5px">
                        <label class="col-sm-2"> Tổng Thu:</label>
                        <div class="col-sm-4">
                            <input disabled style="border:0px" id="final_money" value="0" class="form-control" type="text" placeholder="Tổng tiền" />
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-sm-3">
                            <input onclick="save()" style="font-size:20px;height:50px;" value="Lưu Lại" id='submit' name='submit' type="submit" class="btn btn-primary"
                            />
                        </div>
                    </div>
                    </form>
            </div>
        </div>


        <script>
            function addnewcl() {
                var number = $('#number');
                var table = $('#table');
                var i = parseInt(number.val()) + parseInt(1);

                var list = new Array();
                for (var j = 1; j <= i; j++) {
                    if ($('#tr' + j).html()) {
                        var name = $('#name' + j).val();
                        var price = cupnumber($('#price' + j).val());
                        var quantity = cupnumber($('#quantity' + j).val());
                        var obj = new create(j, name, price, quantity);
                        list.splice(list.length, 1, obj);
                    }
                }

                var name = '<td><input class="form-control" type="text" id="name' + i + '" value="" /></td>';
                var price = '<td><input class="form-control" onkeyup="totalmoney(\'price' + i + '\')" type="text" id="price' + i + '" value="" /></td>';
                var quantity = '<td><input class="form-control" onkeyup="totalmoney(\'quantity' + i + '\')" type="text" id="quantity' + i + '" value="" /></td>';
                var deletes = '<td><button class="form-control"  onclick="dele(\'tr' + i + '\')"><span style="font-size:13px;" class="icon-cancel-circle"></span></button></td>';
                table.html(table.html() + '<tr id="tr' + i + '">' + name + price + quantity + deletes + '</tr>');
                number.val(i);

                for (var i = 0; i < list.length; i++) {
                    $('#name' + list[i].number).val(list[i].name);
                    $('#price' + list[i].number).val(list[i].price);
                    $('#quantity' + list[i].number).val(list[i].quantity);
                }

                editallnumber();
            }

            function dele(id) {
                var tr = document.getElementById(id);
                tr.innerHTML = "";
                tr.style = "display:none";
            }

            function totalmoney(id) {
                editnumber(id);
                total();
            }

            function total() {
                var money = 0;

                var number = $('#number');
                var i = parseInt(number.val());
                for (var j = 1; j <= i; j++) {
                    if ($('#tr' + j).html()) {
                        var price = cupnumber($('#price' + j).val());
                        var quantity = cupnumber($('#quantity' + j).val());
                        if (price == "" || quantity == "") continue;
                        money += parseInt(cupnumber(price)) * parseInt(cupnumber(quantity));
                    }
                }
                $('#totalmoney').val(money);
                editnumber('totalmoney');
            }

            function save() {
                var number = $('#number');
                var i = parseInt(number.val());

                var json = "[";
                for (var j = 1; j <= i; j++) {
                    if ($('#tr' + j).html()) {
                        var name = $('#name' + j).val();
                        var price = cupnumber($('#price' + j).val());
                        var quantity = cupnumber($('#quantity' + j).val());

                        json += "{\"name\":\"" + name + "\",\"price\":\"" + price + "\",\"quantity\":\"" + quantity + "\"},";
                    }
                }
                if (json.length > 1)
                    json = json.substring(0, json.length - 1);
                json += "]";
                //alert(document.getElementById('time').value);
                //alert($('#time').val());
                $("#nobox").css("display", "none");
                $("#submit").val('Đang Lưu...');
                $('#submit').attr('disabled', true);
                //alert($('#idhtr').val());
                $.ajax({
                    url: "acc/product/save.php",
                    type: "post",
                    dataType: "json",
                    data: {
                        submit: $('#submit').val(),
                        id: $('#id').val(),
                        name: $('#name').val(),
                        time: $('#time').val(),
                        note: $('#note').val(),
                        idhtr: $('#idhtr').val(),
                        is_repurchase: $('[name="is_repurchase"]').prop('checked'),
                        data: json
                    },
                    success: function (result) {
                        $('#result').html(result.mess);

                        if (result.id != "") {
                            $("#idhtr").val(result.id);
                            $("#submit").val('Cập Nhập');
                        } else $("#submit").val('Lưu Lại');
                        $("#nobox").css("display", "block");
                        $('#submit').removeAttr('disabled');
                        up_page();
                    }
                });
            }

            function editallnumber() {
                var number = $('#number');
                var i = parseInt(number.val());
                for (var j = 1; j <= i; j++) {
                    if ($('#tr' + j).html()) {
                        editnumber('price' + j);
                        editnumber('quantity' + j);
                    }
                }
            }

            function create(number, name, price, quantity) {
                this.number = number;
                this.name = name;
                this.price = price;
                this.quantity = quantity;
            }
            total();
            editallnumber();

            setTimeout(function(){
                total_m = parseInt(cupnumber($('#totalmoney').val()));
                repurchase = parseInt(cupnumber($('#repurchase_money').val()));
                final = total_m - repurchase;
                $('#final_money').val(new Intl.NumberFormat().format(final));
            },800);
        </script>
