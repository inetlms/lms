<?php
/*
 * Aplikacja IPTV versja 1.2
 * 2011 ITMSOFT
 * 1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  
*/

setlocale(LC_ALL,"pl");

function orderBy(&$data, $field, $dir) {
	if ($dir == "asc"){
		$code = "return strnatcmp(strtolower(\$a['$field']), strtolower(\$b['$field']));";
	} else {
		$code = "return strnatcmp(strtolower(\$b['$field']), strtolower(\$a['$field']));";
	}
	usort($data, create_function('$a,$b', $code));
}

$direction 	= $_GET['direction'] ? $_GET['direction'] : 'asc';
$order 		= $_GET['order'] ? $_GET['order'] : 'id';
if ($direction == '') $direction = 'desc';


$layout['pagetitle'] = 'Lista wysłanych wiadomości';

if ($_GET['meldinger_delete_all']){
	try{
		$list = $LMSTV->CustomerList();
		foreach ($list as $l) $LMSTV->MeldingerDel(null, $l['cust_number']);
		$SESSION->redirect('?m=tvmessages');
	} catch (Exception $e){
		$errormsg = $e->getMessage();
	}
}

if ($_GET['meldinger_delete']){
	try{
		$res = (int)$LMSTV->MeldingerDel(array($_GET['meldinger_delete']));
		$SESSION->redirect('?m=tvmessages');
	} catch (Exception $e){
		$errormsg = $e->getMessage();
	}
}

$ppage = 25;
$list = $LMSTV->MessagesList();

$all = ceil((count($list)/$ppage));
$page = (int)$_GET['page'];
foreach ($list as $key => $l){
	$list[$key]['customerid'] = $LMSTV->GetCustomerByCustNumber($l['cust_number']);
}
orderBy($list, $order, $direction);

$list = Zend_Paginator::factory($list);
$list->setItemCountPerPage(25);
$list->setCurrentPageNumber($page);

$SMARTY->assign('order', $order);
$SMARTY->assign('direction', $direction);
$SMARTY->assign('list', $list);
$SMARTY->assign('page', $page);
$SMARTY->assign('all', $all);
$SMARTY->assign('smsurl', $LMSTV->smsurl);

$SMARTY->display('tvmessages.html');
?>
