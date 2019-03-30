<?php 
    require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	//include_once($_SERVER["DOCUMENT_ROOT"]."ws_gr1f0sp3ruanos/system/ws/ws_basic_autenticate.php");
    //require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsWSGrifoPeruano::PATH_ROOT_APP."/ws/helper_service.php");
    require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_report.php");
    require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/controller/controller_report.php");
    require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/vendor/autoload.php");
    
    use PhpOffice\PhpSpreadsheet\Helper\Sample;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    
    $company                = "DESOFTPE";
    $nameFile               = "Reporte Atencion Pacientes.xlsx";
    $spreadsheet            = new Spreadsheet();
    $token	                = $_REQUEST["token"];
	$patient	            = $_REQUEST["patient"];
	$dateInitial	        = $_REQUEST["date_ini"];
	$dateEnd		        = $_REQUEST["date_end"];
    $ctrl 		            = new ControllerReport();
    $data  		            = $ctrl->getAttentionReport($token,$patient,$dateInitial,$dateEnd);
    $nameSheet              = "Reporte de Atencion Pacientes";
    $spreadsheet->getProperties()->setCreator($company)
    ->setLastModifiedBy($company)
    ->setTitle($nameSheet)
    ->setSubject($company)
    ->setDescription('Reporte de Atencion de Pacientes')
    ->setKeywords("office 2007 openxml php")
    ->setCategory('Reportes');

    $spreadsheet->getActiveSheet()->setTitle($nameSheet);
    $spreadsheet->getActiveSheet()->getStyle('B2:N8')->applyFromArray(
        array(
           'fill' => array(
               'type' => Fill::FILL_SOLID,
               'color' => array('rgb' => '00b292' )
           ),
           'font'  => array(
               'bold'  =>  true
           )
        )
      );
    
    $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
    $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(12);
    $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
    $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
    
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('B2', 'REPORTE DE ATENCIONES')
    ->setCellValue('C4', "Fecha Inicio:  ".formatDate($dateInitial))
    ->setCellValue('C5', "Fecha Fin:    ".formatDate($dateEnd));
    
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('B8', 'N°')
    ->setCellValue('C8', 'PROPIETARIO')
    ->setCellValue('D8', 'TIPO')
    ->setCellValue('E8', 'PACIENTE')
    ->setCellValue('F8', 'SEXO')
    ->setCellValue('G8', 'RAZA')
    ->setCellValue('H8', 'COLOR')
    ->setCellValue('I8', 'FECHA NACIMIENTO')
    ->setCellValue('J8', 'EDAD')
    ->setCellValue('K8', 'FECHA ATENCION')
    ->setCellValue('L8', 'DIAGNOSTICO')
    ->setCellValue('M8', 'SIGNOS CLINICOS')
    ->setCellValue('N8', 'MONTO PAGO');
    $indexRow = 9;
    $sheet                  = $spreadsheet->getActiveSheet();
    $finalRow = count($data)+$indexRow;
    $totalAmount = 0;
    for($i=0;$i<count($data);$i++){
        $sheet->setCellValue('B'.($i+$indexRow), ($i+1));
        $sheet->setCellValue('C'.($i+$indexRow), $data[$i]->owner);
        $sheet->setCellValue('D'.($i+$indexRow), $data[$i]->kind);
        $sheet->setCellValue('E'.($i+$indexRow), $data[$i]->patient);
        $sheet->setCellValue('F'.($i+$indexRow), $data[$i]->sex);
        $sheet->setCellValue('G'.($i+$indexRow), $data[$i]->breed);
        $sheet->setCellValue('H'.($i+$indexRow), $data[$i]->color);
        $sheet->setCellValue('I'.($i+$indexRow), formatDate($data[$i]->dateBorn));
        $sheet->setCellValue('J'.($i+$indexRow), $data[$i]->year." años y ".$data[$i]->month." meses");
        $sheet->setCellValue('K'.($i+$indexRow), formatDate($data[$i]->dateAttention));
        $sheet->setCellValue('L'.($i+$indexRow), $data[$i]->diagnostic);
        $sheet->setCellValue('M'.($i+$indexRow), $data[$i]->state);
        $sheet->setCellValue('N'.($i+$indexRow), $data[$i]->payment);
        $totalAmount += $data[$i]->payment;
    }
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('M'.$finalRow, 'TOTAL:')
    ->setCellValue('N'.$finalRow, $totalAmount);
    $spreadsheet->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$nameFile.'"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    ob_end_clean();
    $writer->save('php://output');
    exit;
    function formatDate($dateValue){
        $dtv = "-";
        if($dateValue!=null){
            $dfe = explode("-",$dateValue);
            $dtv = $dfe[2]."/".$dfe[1]."/".$dfe[0];
        }
        return $dtv;;
    }

?>