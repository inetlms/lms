<?php 
//exit;
/*
 * Aplikacja IPTV versja 1.2
 * 2011 ITMSOFT
 * 1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  

*/

//phpinfo();
//exit;

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

//exit;
$list = $LMSTV->CustomerList();
//exit;

$all = ceil((count($list)/$ppage));
$page = (int)$_GET['page'];
if ($page == -1) $ppage = count($list);
if ($page == 0) $page = 1;

orderBy($list, $order, $direction);

//require_once "Zend/Debug.php";
require_once "Zend/Paginator.php";

//echo get_include_path();
//exit;
//Zend_Debug::dump("test");
//exit;

//Zend_Debug::dump("test");

try {
$list = Zend_Paginator::factory($list);
$list->setItemCountPerPage($ppage);
$list->setCurrentPageNumber($page);
} catch (Exception $e) {}


$SMARTY->assign('order', $order);
$SMARTY->assign('direction', $direction);
$SMARTY->assign('list', $list);
$SMARTY->assign('page', $page);
$SMARTY->assign('all', $all);
$SMARTY->assignByRef('smsurl', $LMSTV->smsurl);

$SMARTY->display('tvcustomers.html');
?>
