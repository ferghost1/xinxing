<?php
class apps_libs_Utilities
{
    public function apps_libs_Utilities()
    {

    }
    public function GetMonthNow()
    {
        $date = Date('m/Y');
        $tach = explode("/", $date);
        $result = [
            "month" => $tach[0],
            "year" => $tach[1]
        ];
        return $result;
    }
    public function GetDateMonth($time)
    {
        if (!$time) return null;
        if (count(explode("-", $time)) != 2) return null;
        $cup = explode("-", $time);
        $result = [
            "month" => $cup[1],
            "year" => $cup[0]
        ];
        return $result;
    }
    public function PlusDateMonth($time)
    {
        if (!$time) return null;

        if ($time["month"] == 12) {
            $time["month"] = 1;
            $time["year"]++;
        } else $time["month"]++;
        return $time;
    }

    public function MinusDateMonth($time)
    {
        if (!$time) return null;

        if ($time["month"] == 1) {
            $time["month"] = 12;
            $time["year"]--;
        } else $time["month"]--;
        return $time;
    }

    public function GetDateMonthInDataBase($time, $edit = false)
    {
        if (!$time) return null;
        $time = explode(" ", $time)[0];
        $time = explode("-", $time);
        $time = $time[0] . "-" . $time[1];
        return $edit ? $this->GetDateMonth($time) : $time;
    }

    public function EditNumber($string)
    {
        $string = (string)$string;
        $new_string = '';
        $j = 0;
        for ($i = strlen($string) - 1; $i >= 0; $i--) {
            if ($j % 3 == 0 && $j != 0) $new_string .= ',';
            $new_string .= $string[$i];
            $j++;
        }
        $new_string = strrev($new_string);
        return $new_string;
        //return strrev($string);
    }

    public function EditNumberPercent($string)
    {
        $string = (string)$string;
        return $string += " %";
    }

    public function EditDataImportDB($data)
    {
        $data = (string)$data;
        $data = str_replace("\"", "", $data);
        $data = str_replace("'", "", $data);
        return $data;
    }
}

?>