<?php
include_once('../../../apps/bootstrap.php');
$rt=new apps_libs_Router();
$user=new apps_libs_UserLogin();
if (!$user->CheckUser()) $rt->LoginPage();
$uti=new apps_libs_Utilities();
$data = $uti->EditDataImportDB($rt->GetPost("data"));

$where = $uti->EditDataImportDB($rt->GetPost('where'));
if (!$data || !$where) die();

if (!CheckLimit($data)) {
    echo json_encode(CreateJson("",""));
} else {
    $s;
    $max;
    $id = CheckData($data);
    if ($where == "dadacc") {
        $page = new apps_libs_Page([
            "table" => "detailacc,acc,relationshipacc",
            "where" => "relationshipacc.children='" . $id . "' and acc.id=relationshipacc.dadacc and detailacc.idacc=relationshipacc.dadacc",
            "col" => [
                "ID" => "acc.id",
                "Tài Khoản" => "acc.user",
                "Tên" => "detailacc.name",
            ],
            "function" => [
                "in" => "user",
                "out" => "id"
            ],
            "break" => [
                "acc.id"
            ]
        ], 1, 100, $_SERVER['QUERY_STRING']);
        echo json_encode(CreateJson(CreateTableDad($data, $page, $where), CreateTitleDad($data)));
        die();
    }
    $page = new apps_libs_Page([
        "table" => "detailacc,acc,relationshipacc",
        "where" => "relationshipacc.dadacc='" . $id . "' and acc.id=relationshipacc.children and detailacc.idacc=relationshipacc.children",
        "col" => [
            "ID" => "acc.id",
            "Tài Khoản" => "acc.user",
            "Tên" => "detailacc.name",
        ],
        "function" => [
            "in" => "user",
            "out" => "id"
        ],
        "break" => [
            "acc.id"
        ]
    ], 1, 100, $_SERVER['QUERY_STRING']);
    echo json_encode(CreateJson(CreateTableChild($data, $page, $where), CreateTitleChild($data)));
}

?>

<?php
function CheckLimit($data)
{
    $e = explode(" ", $data);
    if (sizeof($e) < 6) return true;
    return false;
}
function CheckData($data)
{
    $e = explode(" ", $data);
    return $e[count($e) - 1];
}

function CreateTableDad($listid, $page, $where)
{
    $data = $page->GetData();
    $table = "<table id='table' class='table table-striped table-hover'>";
    $table .= "<tr>";

    foreach ($page->col['col'] as $key => $item) {
        if ($page->CheckBreak($item)) continue;
        $table .= "<th>";
        $table .= $key;
        $table .= "</th>";
    }
    $table .= "</tr>";

    if ($data)
        foreach ($data as $item) {
        $table .= "<tbody><tr id=\"tr" . $item[$page->col['function']['out']] . "\">";
        foreach ($page->col['col'] as $key => $it) {
            if ($page->CheckBreak($it)) continue;
            if ($page->CupString($it) == $page->col['function']['in']) {
                $table .= "<td><button class='btn btn-link' onclick=\"next_acc('" . $listid . " " . $item['id'] . "','" . $where . "')\" >";
                $table .= $item[$page->CupString($it)];
                $table .= "</button></td>";
            } else {
                $table .= "<td>";
                $table .= $item[$page->CupString($it)];
                $table .= "</td>";
            }
        }
        $table .= "</tr></tbody>";
    }

    $table .= "</table> ";

    return $table;
}

function CreateTitleDad($data)
{
    $db = new apps_libs_Dbconn();
    $string = "";
    $e = explode(" ", $data);
    $newstring = "";
    foreach ($e as $item) {
        $newstring .= $item;
        $string .= "<span class='btn-link h-btn-link' onclick=\"next_acc('" . $newstring . "','dadacc')\">" . $db->GetValue("acc", "user", "id='" . $item . "'") . "</span>" . " -> ";
        $newstring .= " ";
    }
    return rtrim($string, " -> ");;
}


//==========================================================

function CreateTableChild($listid, $page, $where)
{
    $data = $page->GetData();
    $table = "<table id='table' class='table table-striped table-hover'>";
    $table .= "<tr>";

    foreach ($page->col['col'] as $key => $item) {
        if ($page->CheckBreak($item)) continue;
        $table .= "<th>";
        $table .= $key;
        $table .= "</th>";
    }
    $table .= "</tr>";

    if ($data)
        foreach ($data as $item) {
        $table .= "<tbody><tr id=\"tr" . $item[$page->col['function']['out']] . "\">";
        foreach ($page->col['col'] as $key => $it) {
            if ($page->CheckBreak($it)) continue;
            if ($page->CupString($it) == $page->col['function']['in']) {
                $table .= "<td><button class='btn btn-link' onclick=\"next_acc('" . $listid . " " . $item['id'] . "','" . $where . "')\" >";
                $table .= $item[$page->CupString($it)];
                $table .= "</button></td>";
            } else {
                $table .= "<td>";
                $table .= $item[$page->CupString($it)];
                $table .= "</td>";
            }
        }
        $table .= "</tr></tbody>";
    }

    $table .= "</table> ";

    return $table;
}

function CreateTitleChild($data)
{
    $db = new apps_libs_Dbconn();
    $string = "";
    $e = explode(" ", $data);
    $newstring = "";
    foreach ($e as $item) {
        $newstring .= $item;
        $string .= "<span class='h-btn-link btn-link' onclick=\"next_acc('" . $newstring . "','children')\">" . $db->GetValue("acc", "user", "id='" . $item . "'") . "</span>" . " -> ";
        $newstring .= " ";
    }
    return rtrim($string, " -> ");;
}

function CreateJson($table, $title)
{
    $data = [
        "table" => $table,
        "title" => $title
    ];
    return $data;
}

?>