<?php
global $LMS, $SMARTY, $SESSION, $DB, $voip;

$taxes = $LMS->GetTaxes();
$tax = 0;

if(is_array($taxes))
	foreach($taxes as $val)
		if($val['id'] == $voip->config['taxid'])
			$tax = $val['value'];

if($_POST['new'])
	$SMARTY->assign('serv_err', $voip->uiAddService($SESSION->id, $_POST['new']));

if($_POST['uinvoicec'] == 1)
	$voip->ui_setinvoice($SESSION->id, true);
elseif($_POST['notuinvoicec'] == 1)
	$voip->ui_setinvoice($SESSION->id, false);

$userinfo = $LMS->GetCustomer($SESSION->id);

$assignments = $voip->wsdl->uiGetCustomerNodes($SESSION->id);

foreach($assignments as $key => $val)
	$assignments[$key]['value'] = $val['value'] * ($tax / 100) + $val['value'];

$userinfo = $voip->wsdl->GetCustomer($userinfo, $SESSION->id);
$SMARTY->assign('userinfo', $userinfo);
$SMARTY->assign('assignments', $assignments);

/* CDR */
if(isset($_POST['from']))
	$from = $_POST['from'];
else
	$from = date('Y/m/d', time()-86400);

if(isset($_POST['to']))
	$to = $_POST['to'];
else
	$to = date('Y/m/d');
$c = $SESSION->id;
if($_POST['rategroups'])
	$_POST['dir'] = 2;
if($_POST['csv'])
{
	$cdr = $voip->GetCdrList($from, $to, $c, '', $_POST['fnr'], $_POST['tnr'], $_POST['dir'], $_POST['rategroups'], $_POST['stat']);
	$fname = tempnam("/tmp", "CSV");
	$f = fopen($fname,'w');
	fwrite($f, "Data;Z numeru;Na numer;Sekund;Klient;Strefa\n");
	foreach((array)$cdr as $key => $val) if(is_array($val))
	{
		$line = array();
		$line[] = $val['calldate'];
		$line[] = $val['src'];
		$line[] = $val['dst'];
		$line[] = $val['seconds'];
		$line[] = $voip->toiso($val['name']);
		$line[] = $voip->toiso($val['rate']);
		fwrite($f, implode(';', $line) . "\n");
	}
	fclose($f);
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="cdr.csv"');
	readfile($fname);
	unlink($fname);
	exit();
}

$cdr = $voip->GetCdrList($from, $to, $c, '', $_POST['fnr'], $_POST['tnr'], $_POST['dir'], $_POST['rategroups'], $_POST['stat'], $voip->config['cdrperpage'] * $_POST['page'], $voip->config['cdrperpage']);
$listdata = array('from' => $from, 'to' => $to, 'fnr' => $_POST['fnr'], 'tnr' => $_POST['tnr']);
$listdata['dir'] = $_POST['dir'];
$listdata['stat'] = $_POST['stat'];
$listdata['rategroups'] = $_POST['rategroups'];
$listdata['seconds'] = $cdr['sum_seconds'];
unset($cdr['sum_seconds']);
$listdata['cost'] = number_format((double)$cdr['sum_cost'], 2, '.', '');
unset($cdr['sum_cost']);
$listdata['tmp_cost'] = number_format((double)$cdr['sum_tmp_cost'], 2, '.', '');
unset($cdr['sum_tmp_cost']);
unset($cdr['zysk']);
unset($cdr['order']);
unset($cdr['direction']);

$pageopts = array();
for($i = 0; $i < $cdr['pages'];)
{
	$pageopts[$i++] = $i;
}
$listdata['pageopts'] = $pageopts;
unset($cdr['pages']);
$listdata['page'] = $_POST['page'];

foreach($cdr as $key => $val)
	if(is_array($val))
	{
		$cost_br = number_format(round($val['cost'] * ($tax/100) + $val['cost'], 2), 2, '.', '');
		$cdr[$key]['cost_br'] = $cost_br;
	}
$listdata['cost_br'] = number_format(round($listdata['cost'] * ($tax/100) + $listdata['cost'], 2), 2, '.', '');
$SMARTY->assign('listdata', $listdata);
$SMARTY->assign('rategroups', $voip->rategroups);
$SMARTY->assign('cdr', $cdr);
$SMARTY->display('module:voip.html');
?>
