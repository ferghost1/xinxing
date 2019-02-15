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
								->setCellValue("B$i", $item['name'])
                                ->setCellValue("C$i", $item['user'])
                                ->setCellValue("D$i", $item['phonenumber'])
	                            ->setCellValue("E$i", $item['revenue'])
                                ->setCellValue("F$i", $item['buya'])
                                ->setCellValue("G$i", $item['getmoneysun'])
                                ->setCellValue("H$i", $item['getmoneybuyaagency'])
                                ->setCellValue("I$i", $item['buya']+$item['getmoneysun']+$item['getmoneybuyaagency'])
                                ->setCellValue("J$i", $item['bank']["bank"])
                                ->setCellValue("K$i", $item['bank']["bankaccountname"])
                                ->setCellValue("L$i", $item['bank']["bankaccountnumber"]);
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
        ->setCellValue('D1', "SỐ ĐIỆN THOẠI")
        ->setCellValue('E1', "DOANH SỐ")
        ->setCellValue('F1', "GIÁ TRỊ ĐỒNG CHIA")
        ->setCellValue('G1', "HOA HỒNG ĐẠI LÝ")
        ->setCellValue('H1', "HOA HỒNG TÁI TIÊU DÙNG")
        ->setCellValue('I1', "TỔNG TIỀN")
        ->setCellValue('J1', "TÊN NGÂN HÀNG")
        ->setCellValue('K1', "TÊN CHỦ KHOẢN")
        ->setCellValue('L1', "SỐ TÀI KHOẢN");     
    }
    
}

?>