<?php
setlocale(LC_ALL, 'C');
$docid = $_GET['docid'];

$res = $DB->GetAll('SELECT name, value FROM billing_details WHERE documents_id = ?', array($docid));
$data = array();
$i = 1; $suma = 0; $poz = 0; $sum_vat = 0; $sum_br = 0;
$taxes = $LMS->GetTaxes();
$tax = 0;
if(is_array($taxes)) foreach($taxes as $val) if($val['id'] == $voip->config['taxid']) $tax = $val['value'];
foreach($res as $val)
{
	$el = array();
	$el['L.p.'] = $i++;
	$el['Nazwa us³ugi'] = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $val['name']);
	$el['Liczba jedn.'] = '1.00';
	$el['Warto¶æ netto'] = $val['value'];
	$el['VAT'] = number_format(round($val['value'] * ($tax / 100), 2), 2, '.', '');
	$el['Warto¶æ brutto'] = number_format($val['value'] + $el['VAT'], 2, '.', '');
	$data[] = $el;
	$suma += $val['value']; $poz++;
}
$el = array();
$el['Nazwa us³ugi'] = 'Razem';
$el['Warto¶æ netto'] = number_format($suma, 2, '.', '');
$el['VAT'] = number_format(round($suma * ($tax / 100), 2), 2, '.', '');
$el['Warto¶æ brutto'] = number_format($suma + $el['VAT'], 2, '.', '');
$el['Liczba jedn.'] = number_format($poz, 2, '.', '');
$data[] = array();
$data[] = $el;

require_once(LIB_DIR . '/ezpdf.php');

$pdf =& init_pdf('A4', 'portrait', trans('Invoices'));
$inv = $LMS->GetInvoiceContent($docid);
$numer = docnumber($inv['number'], $inv['template'], $inv['cdate']);
$pdf->ezText("Za³±cznik do faktury VAT\nNr $numer\n", 30, array('left' => 130));
$pdf->ezTable($data);
if($_GET['is_sure'] == 1)
{
	$data = at_details($docid);
	if(count($data) > 0)
	{
		$pdf->ezNewPage();
		$pdf->ezTable($data);
	}
}
$pdf->ezStream();
close_pdf($pdf);

function at_details($id)
{
global $DB, $voip, $tax;
$res = $DB->GetRow('SELECT cdate, customerid FROM documents WHERE id = ?', array($id));
$data = $voip->wsdl->GetBilling($res['cdate'], $res['customerid']);
$out = array(); $lp = 1;
if(is_array($data)) foreach($data as $val)
{
	$el = array();
	$el['L.p.'] = $lp++;
	$el['Data'] = $val['calldate'];
	$el['Z numeru'] = $val['src'];
	$el['Na numer'] = $val['dst'];
	$el['Czas'] = $val['seconds'];
	$el['Koszt'] = $val['tmp_cost'];
	$el['Op³ata'] = $val['cost'];
	$el['Op³ata brutto'] = number_format(round($val['cost'] * ($tax/100) + $val['cost'], 2), 2, '.', '');
	$out[] = $el;
}
return $out;
}

?>
