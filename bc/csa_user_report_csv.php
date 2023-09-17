<?php
include('inc/include.inc.php');
include('csa_function.php');

$print_q_id = intval($_GET['print_q_id']);
$print_s21_id = intval($_GET['print_s21_id']);
$print_s22_id = intval($_GET['print_s22_id']);
$period = intval($_GET['period']);

$data1 = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
</head>

<body>';
$data2 = '</body></html>';


if ($print_q_id>0) {
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Disposition: attachment; filename="rms_user_part1_export_'.date('Y-m-d_His').'.xls"');
	echo $data1;
	gen_print_part1($print_q_id, true, false, true);
	echo $data2;

} else if ($print_s21_id>0 && $period>0) {
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Disposition: attachment; filename="rms_user_part2_1_export_'.date('Y-m-d_His').'.xls"');
	echo $data1;
	gen_print_part2_1_xls($print_s21_id, $period);
	echo $data2;

} else if ($print_s22_id>0 && $period>0) {
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Disposition: attachment; filename="rms_user_part2_2_export_'.date('Y-m-d_His').'.xls"');
	echo $data1;
	gen_print_part2_2_xls($print_s22_id, $period);
	echo $data2;
}

exit;		

?>