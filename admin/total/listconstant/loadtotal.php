<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();
$number = $rt->GetPost('number');
$time=$rt->GetPost('time');
$s = $rt->GetPost('s');
$max = $rt->GetPost('max');
if (!$max) $max = 10;
if($time)$time=(new apps_libs_Utilities())->GetDateMonth($time);

$where=CreateBreakRow($time);

if ($number) {
    if ($s) {
        $page = new apps_libs_Page([
            "table" => "acc,detailacc,relationshipacc",
            "where" => "(acc.type='user' and acc.user LIKE '" . $s . "%' or detailacc.name LIKE '" . $s . "%') and detailacc.idacc=acc.id and acc.type=='user' and ".$where,
            "col" => [
                "ID" => "acc.id",
                "Tài Khoản" => "acc.user",
                "Tên" => "detailacc.name",
                "Số Thành Viên Cấp Dưới" => "(SELECT COUNT(*) FROM relationshipacc where acc.id=relationshipacc.dadacc) as number"
            ],
            "function" => [
                "in" => "user",
                "out" => "id",
                "link" => "?r=acc&p=menu&id="
            ],
            "break" => [
                "acc.id"
            ],
            "isnumber" =>"total",
            "tablechild"=>"a"
        ], $number, $max, $_SERVER['QUERY_STRING']);
        echo CreateTableFunction($page);
    } else {
        $page = new apps_libs_Page([
            "table" => "acc,detailacc,relationshipacc",
            "where" => "acc.type='user' and detailacc.idacc=acc.id and acc.type!='admin' and ".$where ." GROUP BY acc.id",
            "col" => [
                "ID" => "acc.id",
                "Tài Khoản" => "acc.user",
                "Tên" => "detailacc.name",
                "Số Thành Viên Cấp Dưới" => "(SELECT COUNT(*) FROM relationshipacc where acc.id=relationshipacc.dadacc) as number"
            ],
            "function" => [
                "in" => "user",
                "out" => "id",
                "link" => "?r=total&p=calacc&id="
            ],
            "break" => [
                "acc.id"
            ],
            "isnumber" =>"total",
            "tablechild"=>"a"
        ], $number, $max, $_SERVER['QUERY_STRING']);
        echo CreateTableFunction($page);
        echo CreateListNumberFunction($page);
    }
}

function CreateBreakRow($time)
{
    $param=[
        "select"=>"idacc",
        "from"=>"historyproduct",
        "where"=>"month(timecreate)=".$time["month"]." and year(timecreate)=".$time["year"]." GROUP BY idacc"
    ];
    $db=new apps_libs_Dbconn();
    $result=$db->Select($param);
    $where="";
    while($row=mysqli_fetch_assoc($result))
    {
        $where.="acc.id!='".$row["idacc"]."' and ";
    }
    $where=rtrim($where," and ");
    return $where;
}

function CreateTableFunction($page)
{
    $data = $page->GetData();
    if(!$data) return "Không có dữ liệu";
    $table = "<table id='table' class='table table-striped table-hover'>";
    $table .= "<tr>";
    foreach ($page->col['col'] as $key => $item) {
        if ($page->CheckBreak($item)) continue;
        $table .= "<th>";
        $table .= $key;
        $table .= "</th>";
    }
    $table .= "</tr>";

    $number=1;

    if($data)
    foreach ($data as $item) {
        if ($page->CheckBreakRow($item["id"])) continue;
        $table .= "<tbody><tr id=\"tr" . $item[$page->col['function']['out']] . "\">";
        foreach ($page->col['col'] as $key => $it) {
            if ($page->CheckBreak($it)) continue;
            if ($page->CupString($it) == $page->col['function']['in']) {
                $table.="<td style='word-wrap: break-word; width:100px;'><a href=\"".$page->col['function']['link'].$item[$page->col['function']['out']]."\">";
                $table .= $item[$page->CupString($it)];
                $table.="</a></td>";
            } 
            else if($page->col['isnumber']==$page->CupString($it))
            {
                $table .= "<td>";
                $table .=  (new apps_libs_Utilities())->EditNumber($item[$page->CupString($it)])." VND";
                $table .= "</td>";
                $number++;
            }
            else {
                $table .= "<td>";
                $table .= $item[$page->CupString($it)];
                $table .= "</td>";
            }
        }
        $table .= "</tr></tbody>";
    }
    else $table.= "<tr><td style=\"color:red\">Không có tài khoản nào phát sinh doanh thu trong khoảng thời gian này</td></tr>";
    $table .= "</table> ";

    return $table;
}
function CreateListNumberFunction($page,$count=null)
{
    if(!$count)
        $count = $page->GetCount();
    $maxindex = (int)($count / $page->maxrow);
    if ($maxindex != $count / $page->maxrow) $maxindex += 1;

    $div = "<div>";
    if ($page->index - 3 > 1) {
        $div .= "<button onclick=\"load_ajax('1')\" class='list-index btn' ><span>1</span></button>";
        $div .= "<button class='list-index btn' ><span>...</span></button>";
    }
    for ($i = 1; $i <= $maxindex; $i++)
        if ($i == $page->index)
        $div .= "<button onclick=\"load_ajax('" . $i . "',$('#select_month').val())\" style='background-color:#337ab7;color:#fff' class='list-index btn'><span>" . $i . "</span></button>";
    else {
        if ($i >= $page->index - 3 && $i <= $page->index + 3)
            $div .= "<button onclick=\"load_ajax('" . $i . "',$('#select_month').val())\" class='list-index btn' ><span>" . $i . "</span></button>";
    }
    if ($page->index + 3 < $maxindex) {
        $div .= "<button class='list-index btn' ><span>...</span></button>";
        $div .= "<button onclick=\"load_ajax('" . $maxindex . "',$('#select_month').val())\" class='list-index btn' ><span>" . $maxindex . "</span></button>";
    }
    $div .= "</div>";

    return $div;
}
?>