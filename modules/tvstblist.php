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
$order =  $_GET['order'] ? $_GET['order'] : 'stb_model';

if(isset($_GET['rem'])) {
	try{
		$res = (int)$LMSTV->StbRemove($_GET['rem']);
		$SESSION->redirect('?m=tvstblist');
	} catch (Exception $e){
		$SMARTY->assign('errormsg', $e->getMessage());
	}
}

if(isset($_POST['stb_model']) && isset($_POST['stb_mac']) && isset($_POST['stb_serial'])) {
	try{
		$res = (int)$LMSTV->StbRegister($_POST['stb_mac'], $_POST['stb_serial'], $_POST['stb_model']);
		//print_r("ok"); exit;
		$SESSION->redirect('?m=tvstblist');
	} catch (Exception $e){
		$SMARTY->assign('errormsg', $e->getMessage());
		//print_r($e->getMessage()); exit;
	}
}

$layout['pagetitle'] = trans('Lista STB');

$show_mode = 'stock';
if (isset($_GET['show_mode'])) {
	switch($_GET['show_mode']){
		case 'customer' : $show_mode = 'customer'; break;
		case 'stock' : $show_mode = 'stock'; break;
		case '' : $show_mode = ''; break;
		default : $show_mode = 'stock';
	}
}

$list = $LMSTV->StbGetRegistered($show_mode);

if ($show_mode == 'customer'){
	foreach ($list as $key => $l){
		$list[$key]['customerid'] = $LMSTV->GetCustomerByCustNumber($l['cust_number']);
	}
}

orderBy($list, $order, $direction);

$SMARTY->assign('order', $order);
$SMARTY->assign('direction', $direction);
$SMARTY->assign('list', $list);
$SMARTY->assign('show_mode', $show_mode);
$SMARTY->assignByRef('smsurl', $LMSTV->smsurl);

$SMARTY->display('tvstblist.html');
?>
