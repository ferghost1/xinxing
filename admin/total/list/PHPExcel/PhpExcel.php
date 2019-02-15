<?php
include("Classes/PHPExcel/IOFactory.php");
define("MAXROW", 5);
class WriteExcel
{
    private $fileType = 'Excel2007';
    private $fileName;
    private $objPHPExcel;
    public function WriteExcel($fileName)
    {
        $this->fileName=$fileName;
        $this->objPHPExcel= new PHPExcel();
    }
    public function SaveExcel($data)
    {
        $this->CreateCol();
        $this->CreateData($data);

    }
    private function CreateData($data)
    {
        $uti=new apps_libs_Utilities();
        $i=2;
        if($data)
        foreach($data as $item)
        {
            $this->objPHPExcel->setActiveSheetIndex(0)
								->setCellValue("A$i", $i-1)
								->setCellValue("B$i", $item['user'])
                                ->setCellValue("C$i", $item['username'])
                                ->setCellValue("D$i", $item['nameproduct'])
	                            ->setCellValue("E$i", $item['price'])
                                ->setCellValue("F$i", $item['quantity'])
                                ->setCellValue("G$i", $item['total'])
                                ->setCellValue("H$i", $item['repurchase_money'])
                                ->setCellValue("I$i", $item['repurchase_add'])
                                ->setCellValue("J$i", $item['repurchase_remain'])
                                ->setCellValue("K$i", $item['timecreate']);
            $i++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $this->fileType);
        $objWriter->save($this->fileName);
    }
    private function CreateCol()
    {
        $this->objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', "STT")
        ->setCellValue('B1', "HỌ TÊN")
        ->setCellValue('C1', "TÀI KHOẢN")
        ->setCellValue('D1', "TÊN SẢN PHẨM")
        ->setCellValue('E1', "GIÁ BÁN")
        ->setCellValue('F1', "SỐ LƯỢNG")
        ->setCellValue('G1', "TỔNG TIỀN")
        ->setCellValue('H1', "TÁI TIÊU SỬ DỤNG")
        ->setCellValue('I1', "TÁI TIÊU ĐƯỢC CỘNG")
        ->setCellValue('J1', "TÁI TIÊU CÒN LẠI")
        ->setCellValue('K1', "THỜI GIAN");     
    }
    
}

?>