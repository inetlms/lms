<?php
/*
 * Aplikacja IPTV versja 1.2
 * 2011 ITMSOFT
 * 1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  
*/

$layout['pagetitle'] = trans('Lista zdarzeń bilingowych:');

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
$order 		= $_GET['order'] ? $_GET['order'] : 'beid';
if ($direction == '') $direction = 'desc';

//$start_date = $_GET['start_date'] ? $_GET['start_date'] : Date("Y/m/01");
//$end_date 	= $_GET['end_date'] ? $_GET['end_date'] : Date("Y/m/").cal_days_in_month(CAL_GREGORIAN, Date("m"), Date("Y"));
$start_date = isset($_GET['start_date']) ?  $_GET['start_date'] : Date("Y/m/01");
$end_date 	= isset($_GET['end_date']) ? $_GET['end_date'] : Date("Y/m/").cal_days_in_month(CAL_GREGORIAN, Date("m"), Date("Y"));
$group_id 	= $_GET['group_id'] ? $_GET['group_id'] : '';
$docid 	= $_GET['docid'] ? $_GET['docid'] : '';

$eventslist = $LMSTV->GetBillingEventsDB($start_date, $end_date, $group_id, $docid, $order, $direction);
//orderBy($eventslist, $order, $direction);

$SMARTY->assign('order', $order);
$SMARTY->assign('direction', $direction);
$SMARTY->assign('eventslist', $eventslist);
$SMARTY->assign('start_date', $start_date);
$SMARTY->assign('end_date', $end_date);
$SMARTY->assign('group_id', $group_id);
$SMARTY->assignByRef('smsurl', $LMSTV->smsurl);

$SMARTY->display('tvbillingevents.html');
?>
