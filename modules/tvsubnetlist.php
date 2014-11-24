<?php
/*
 * Aplikacja IPTV versja 1.2
 * 2011 ITMSOFT
 * 1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  
*/

$layout['pagetitle'] = trans('Lista podsieci:');

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
$order =  $_GET['order'] ? $_GET['order'] : 'subnet_name';

if ($_POST['subnet_id'] && isset($_POST['subnet_name1']) && isset($_POST['subnet_name2'])){
	try{
		$LMSTV->SubnetSplit($_POST['subnet_id'], $_POST['subnet_name1'], $_POST['subnet_name2']);
		$SESSION->redirect('?m=tvsubnetlist');
	} catch (Exception $e){
		$SMARTY->assign('errormsg', $e->getMessage());
	}
}

$list = $LMSTV->SubnetList();

orderBy($list, $order, $direction);
$SMARTY->assign('order', $order);
$SMARTY->assign('direction', $direction);
$SMARTY->assignByRef('smsurl', $LMSTV->smsurl);

$SMARTY->assign('list', $list);
$SMARTY->display('tvsubnetlist.html');
?>
