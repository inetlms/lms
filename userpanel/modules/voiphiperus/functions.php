<?php

require_once(LIB_DIR.'/LMS.Hiperus.class.php');

if (defined('USERPANEL_SETUPMODE'))
{
    function module_setup()
    {
	global $SMARTY,$LMS;
        $SMARTY->assign('hide_c5_passwd', $LMS->CONFIG['userpanel']['hide_c5_passwd']);
	$SMARTY->display('module:voiphiperus:setup.html');
    }

    function module_submit_setup()
    {
	global $SMARTY,$DB;
	
	if ($DB->GetOne('SELECT 1 FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('userpanel','hide_c5_passwd'))) {
	    $DB->Execute('UPDATE uiconfig SET value = ? WHERE section = ? AND var = ?;',array(($_POST['hide_c5_passwd'] ? 1 : 0),'userpanel','hide_c5_passwd'));
	} else {
	    $DB->Execute('INSERT INTO uiconfig (section,var,value) VALUES (?,?,?);',array('userpanel','hide_c5_passwd',($_POST['hide_c5_passwd'] ? 1 : 0)));
	}
	header("Location: ?m=userpanel&module=voiphiperus");
    }
}

function module_main() {

    global $LMS,$HIPERUS,$SMARTY,$SESSION, $DB, $CONFIG;

    $SMARTY->assign('hiperusaccountcustomerlist',$HIPERUS->GetCustomerListList('name,asc',array('extid' => $SESSION->id)));

    $voipallinfo = false;
    $voipid = ($_GET['voipid'] ? $_GET['voipid'] : '');
    
    if (isset($_GET['voipid']) && !isset($_GET['print'])) {

	$cusid = intval($_GET['voipid']);
	$voipallinfo = true;
	
	if (isset($_GET['vprint'])) $vprint = true; 
	    else $vprint = false;
	    
	if (isset($_GET['rok']) && !empty($_GET['rok'])) $rok = (int)$_GET['rok']; 
	    else $rok = date('Y',time());
	    
	if (isset($_GET['msc']) && !empty($_GET['msc'])) $msc = (int)$_GET['msc']; 
	    else $msc = date('m',time());
	    
	if (isset($_GET['terminal']) && !empty($_GET['terminal'])) $terminal = $_GET['terminal']; 
	    else $terminal = $DB->GetOne('SELECT username FROM hv_terminal WHERE customerid=? LIMIT 1 ;',array($cusid));
	
	$SMARTY->assign('stvat',((intval(get_conf('hiperus_c5.taxrate',get_conf('phpui.default_taxrate','23')))/100)+1));
	$SMARTY->assign('terminallist',$HIPERUS->GetTerminalOneOrList(NULL,$cusid));
	$SMARTY->assign('terminal',$terminal);
	$SMARTY->assign('rok',$rok);
	$SMARTY->assign('msc',$msc);
	$SMARTY->assign('cusid',$cusid);
	$SMARTY->assign('subscription',$HIPERUS->GetSubscriptionByTerminalName($terminal));
	$SMARTY->assign('billing',$HIPERUS->GetBillingByCustomer($cusid,$rok,$msc,NULL,NULL,$terminal));
	$SMARTY->assign('listyear',$DB->GetAll('SELECT '.$DB->YEAR('start_time').' AS rok FROM hv_billing GROUP BY rok'));
	$SMARTY->assign('cusname',strtoupper($DB->GetOne('SELECT name FROM hv_customers WHERE id=? LIMIT 1 ;',array($cusid))));
	$SMARTY->assign('ctypename',array('incoming'=>'przy','outgoing'=>'wych','internal'=>'wew','disa'=>'disa','vpbx'=>'vpbx','forwarded'=>'prze'));

	$info['terminal'] = $HIPERUS->GetTerminalOneOrList(NULL,$cusid);
	$info['pstn'] = $HIPERUS->GetPSTNOneOrList(NULL,$cusid);
	
	$SMARTY->assign('hide_c5_passwd',$DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('userpanel','hide_c5_passwd')));
	$SMARTY->assign('info',$info);
	$SMARTY->assign('miesiace',array('1'=>'styczeń','2'=>'luty','3'=>'marzec','4'=>'kwiecień','5'=>'maj','6'=>'czerwiec','7'=>'lipiec','8'=>'sierpień','9'=>'wrzesień','10'=>'październik','11'=>'listopad','12'=>'grudzień'));
    }
    
    if (isset($_GET['voipid']) && isset($_GET['print'])) {
	include ('billingprint.php');
    }
    
    $SMARTY->assign('voipid',$voipid);
    $SMARTY->assign('voipallinfo',$voipallinfo);
    $SMARTY->display('module:infovoip.html');
}

?>