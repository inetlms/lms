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
$direction = $_GET['direction'] ? $_GET['direction'] : 'asc';
$order =  $_GET['order'] ? $_GET['order'] : 'cust_surname';

$layout['pagetitle'] = trans('Lista klientów');

$ppage = 25;
$list = $LMSTV->CustomerList();

$all = ceil((count($list)/$ppage));
$page = (int)$_GET['page'];
if ($page == -1) $ppage = count($list);
if ($page == 0) $page = 1;

orderBy($list, $order, $direction);

$list = Zend_Paginator::factory($list);
$list->setItemCountPerPage($ppage);
$list->setCurrentPageNumber($page);

$SMARTY->assign('order', $order);
$SMARTY->assign('direction', $direction);
$SMARTY->assign('list', $list);
$SMARTY->assign('page', $page);
$SMARTY->assign('all', $all);
$SMARTY->assignByRef('smsurl', $LMSTV->smsurl);

$SMARTY->display('tvcustomers.html');
?>
