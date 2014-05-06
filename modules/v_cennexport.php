<?php
setlocale(LC_ALL, 'C');
$exp = $voip->wsdl->CennExport($_GET['id']);
$data = array();
$i = 1; $suma = 0; $poz = 0;
foreach($exp as $val)
{
	$el = array();
	$el['L.p.'] = $i++;
	$el['Kierunek'] = $voip->toiso($val['desc']);
	if($val['days'] == 510 && $val['from'] == '00:00' && $val['to'] == '23:59') $el['Kiedy'] = 'zawsze';
	else
	{
	        $x = decbin($val['days']);
	        $x = sprintf('%09s', $x);
	        $x = $voip->str_split($x);
		$txt = $voip->wsdl->days($x);
	        $el['Kiedy'] = $val['from'] . '-' . $val['to'] . ' ' . $voip->toiso($txt);
	}
	$txt = 'Cena za minutę połączenia';
	$el[$voip->toiso($txt)] = sprintf("%.3f", round($val['price'] * 60, 3));
	$data[] = $el;
}
if($_GET['csv'])
{
	$fname = tempnam("/tmp", "CSV");
	$f = fopen($fname, 'w');
	foreach((array)$data as $key => $val)
	{
		$line = ''; $line1 = '';
		if($key == 0) 
		{
			foreach((array)$val as $key1 => $val1)
			{
				$line1 .= $key1 . ';';
				$line .= $val1 . ';';
			}
		$line = substr($line1, 0, -1) . "\n" . $line;
		}
		else foreach((array)$val as $key1 => $val1) $line .= $val1 . ';';
		$line = substr($line, 0, -1) . "\n";
		fwrite($f, $line);
	}
	fclose($f);
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="cenn.csv"');
	readfile($fname);
	unlink($fname);
	exit();
}

require_once(LIB_DIR . '/ezpdf.php');

$pdf =& init_pdf('A4', 'portrait', trans('Invoices'));
$pdf->ezTable($data, '', '', array('fontSize' => 5));
$pdf->ezStream();
close_pdf($pdf);

?>
