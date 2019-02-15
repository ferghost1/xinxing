<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckAdmin()) $rt->LoginPage();


$id = $rt->GetPost("id");
$number = $rt->GetPost('number');
$s = $rt->GetPost('s');
$max = 10;


if ($number) {
    if ($s) {
        $number = 1;
        $page = new apps_libs_Page([
            "table" => "historyproduct",
            "where" => "idacc='" . $id . "' and name LIKE '" . $s . "%'",
            "col" => [
                "in" => "timecreate",
                "out" => "id",
                "link" => "?r=acc&p=productcrt&id=" . $id . "&idhtr="
            ],
            "function" => [
                "in" => "timecreate",
                "out" => "id",
                "link" => "?r=acc&p=productcrt&id=" . $id . "&idhtr="
            ],
            "break" => [
                "id"
            ]
        ], $number, $max, $_SERVER['QUERY_STRING']);
        echo CreateTableA($page);
    } else {
        $page = new apps_libs_Page([
            "table" => "historyproduct",
            "where" => "idacc='" . $id . "' ORDER BY timecreate DESC",
            "col" => [
                "ID" => "id",
                "Thời Gian Tạo" => "timecreate",
                "Ghi Chú" => "note",
            ],
            "function" => [
                "in" => "timecreate",
                "out" => "id",
                "link" => "?r=acc&p=productcrt&id=" . $id . "&idhtr="
            ],
            "break" => [
                "id"
            ]
        ], $number, $max, $_SERVER['QUERY_STRING']);
        echo CreateTableA($page);
        echo CreateListNumberFunction($page);
    }
}

function CreateTableA($page)
{
    $data = $page->GetData();
    $table = "<table class='table table-bordered table-hover'>";
    $table .= "<tr>";
    foreach ($page->col['col'] as $key => $item) {
        if ($page->CheckBreak($item)) continue;
        $table .= "<th style='word-wrap: break-word;width:100px;'>";
        $table .= $key;
        $table .= "</th>";
    }
    $table .= "<th style='word-wrap: break-word;width:100px;'>";
    $table .= "Xóa";
    $table .= "</th>";
    $table .= "</tr>";

    if ($data)
        foreach ($data as $item) {
        $table .= "<tr id=\"".$item["id"]."\">";
        foreach ($page->col['col'] as $key => $it) {
            if ($page->CheckBreak($it)) continue;
            if ($page->CupString($it) == $page->col['function']['in']) {
                $table .= "<td style='word-wrap: break-word; width:100px;'><a href=\"" . $page->col['function']['link'] . $item[$page->col['function']['out']] . "\">";
                if ($page->CupString($it) == "timecreate")
                    $table .= $page->FomatDate($item[$page->CupString($it)]);
                else $table .= $item[$page->CupString($it)];
                $table .= "</a></td>";
            } else {
                if ($page->CupString($it) == "timecreate") {
                    $table .= "<td>";
                    $table .= $page->FomatDate($item[$page->CupString($it)]);
                    $table .= "</td>";
                } else {
                    $table .= "<td>";
                    $table .= $item[$page->CupString($it)];
                    $table .= "</td>";
                }
            }
        }
        $table .= "<td>";
        $table .= "<button class=\"form-control\" onclick=\"dele('".$item["id"]."','".$page->FomatDate($item["timecreate"])."')\"><span style=\"font-size:13px;\" class=\"icon-cancel-circle\"></span></button>";
        $table .= "</td>";
        $table .= "</tr>";
    }

    $table .= "</table> ";
    return $table;
}

function CreateListNumberFunction($page)
{
    $count = $page->GetCount();
    $maxindex = (int)($count / $page->maxrow);
    if ($maxindex != $count / $page->maxrow) $maxindex += 1;

    $div = "<div class=\"\">";
    if ($page->index - 3 > 1) {
        $div .= "<button onclick=\"load_ajax('1')\" class='list-index btn' ><span>1</span></button>";
        $div .= "<button class='list-index btn' ><span>...</span></button>";
    }
    for ($i = 1; $i <= $maxindex; $i++)
        if ($i == $page->index)
        $div .= "<button onclick=\"load_ajax('" . $i . "')\" style='background-color:#337ab7;color:#fff' class='list-index btn'><span>" . $i . "</span></button>";
    else {
        if ($i >= $page->index - 3 && $i <= $page->index + 3)
            $div .= "<button onclick=\"load_ajax('" . $i . "')\" class='list-index btn' ><span>" . $i . "</span></button>";
    }
    if ($page->index + 3 < $maxindex) {
        $div .= "<button class='list-index btn' ><span>...</span></button>";
        $div .= "<button onclick=\"load_ajax('" . $maxindex . "')\" class='list-index btn' ><span>" . $maxindex . "</span></button>";
    }
    $div .= "</div>";

    return $div;
}
?>