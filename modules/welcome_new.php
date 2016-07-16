<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012-2015 iNET LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  Sylwester Kondracki
 *  sylwester.kondracki@gmail.com
 *  gadu-gadu : 6164816
 *
*/

$layout['pagetitle'] = 'iNET LAN Management System v. '.$layout['lmsvr'];

$pageview = array();

if (check_conf('privileges.superuser') && $registers = $LMS->CheckRegister()) {
	
	$czas = $DB->getone('select keyvalue FROM dbinfo WHERE keytype=? LIMIT 1;',array('inetlms_last_update'));
	
	if (!$czas) {
		$czas = time();
		$DB->Execute('INSERT INTO dbinfo (keytype,keyvalue) VALUES (?,?);',array('inetlms_last_update',$czas));
	}
	
	$uiid = $DB->GetOne('SELECT keyvalue FROM dbinfo WHERE keytype = ? LIMIT 1;',array('inetlms_uiid'));
	$tmp = $DB->GetOne('SELECT keyvalue FROM dbinfo WHERE keytype=? LIMIT 1;',array('inetlms_regdata_infocustomer'));
	$czas = $czas + 1209600;
	
	if (time() > $czas) {
		if ($tmp == '1')
		{
			$customercount = $DB->GetOne('SELECT COUNT(id) FROM customers WHERE status=3 AND (type=0 OR type=1) AND deleted=0;');
			fetch_url(INETLMS_REGISTER_URL.'?uiid='.$uiid.'&updatecustomer='.$customercount);
		}
		
		if (LMSV != $DB->GetOne('SELECT keyvalue FROM dbinfo WHERE keytype = ? LIMIT 1;',array('inetlms_version'))) {
		    fetch_url(INETLMS_REGISTER_URL.'?uiid='.$uiid.'&updateversion='.LMSV);
		    $DB->Execute('UPDATE dbinfo SET keyvalue=? WHERE keytype = ?;',array(LMSV,'inetlms_version'));
		}
		
		$DB->Execute('UPDATE dbinfo SET keyvalue=? WHERE keytype = ?;',array(time(),'inetlms_last_update'));
		
	}
	
} else {
	
	$registers = NULL;
	
}


function homepage_start()
{
	global $pageview,$SMARTY,$LMS,$DB,$CONFIG,$layout,$_language,$PROFILE,$AUTH,$SESSION,$voip, $registers;
	
	if (get_conf('homepage.box_customer') && !check_conf('privileges.hide_summaries')) $pageview[] = 'box_customer';
	if (get_conf('homepage.box_nodes') && !check_conf('privileges.hide_summaries')) $pageview[] = 'box_nodes';
	if (get_conf('homepage.box_helpdesk') && $LMS->RTStats()) $pageview[] = 'box_helpdesk';
	if (get_conf('homepage.box_callcenter')) $pageview[] = 'box_callcenter';
	if (get_conf('homepage.box_links') && !check_conf('privileges.hide_links')) $pageview[] = 'box_links';
	if (get_conf('homepage.box_board')) $pageview[] = 'box_board';
	if (get_conf('homepage.box_system') && !check_conf('privileges.hide_sysinfo')) $pageview[] = 'box_system';
	if (get_conf('homepage.box_lms') && !check_conf('privileges.hide_sysinfo')) $pageview[] = 'box_lms';
	if (get_conf('homepage.box_totd')) $pageview[] = 'box_totd';
	if (get_conf('homepage.box_smscenter')) $pageview[] = 'box_smscenter';
	
	$obj = new xajaxResponse();
	
	if (check_conf('privileges.superuser') && !$registers) {
		$obj->assign("id_box_0a","innerHTML",$SMARTY->fetch("welcome_box_registers.html"));
	}
	
	$count = sizeof($pageview);
	for ($i=0; $i<$count; $i++) {
		if ($pageview[$i] == 'box_customer') {
			
			$customerstats = $LMS->CustomerStats();
			$SMARTY->assign('customerstats', $customerstats);
			$SMARTY->assign('contractending30',$LMS->getIdContractEnding('30'));
			$SMARTY->assign('contractending7',$LMS->getIdContractEnding('7'));
			$SMARTY->assign('contractnodata',$LMS->getIdContractEnding('-2'));
			
		} elseif ($pageview[$i] == 'box_smscenter') {

if($cfg = $DB->GetAll('SELECT section, var, value FROM uiconfig WHERE disabled=0'))
    foreach($cfg as $row)
	$config[$row['section']][$row['var']] = $row['value'];

$login=$config['sms']['username'];
$pass=$config['sms']['password'];
$smsy['warn']=$config['sms']['warn'];

$balance_page="http://api.mobitex.pl/balance.php?user=".$login."&pass=".$pass;
$curl = curl_init($balance_page);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$odpowiedz=explode(" ",curl_exec($curl));
curl_close($curl);
$smsy['saldo']=$odpowiedz[1];
if ($odpowiedz[0]=="ERROR") $smsy['saldo']="BŁĄD POBIERANIA DANYCH";
$SMARTY->assign('smscenter', $smsy);

		} elseif ($pageview[$i] == 'box_nodes') {
			
			$SMARTY->assign('nodestats', $LMS->NodeStats());
			
		} elseif ($pageview[$i] == 'box_helpdesk') {
			
			$SMARTY->assign('rtstats', $LMS->RTStats());
			
		} elseif ($pageview[$i] == 'box_callcenter') {
			
			$result = $DB->GetRow('SELECT COUNT(id) AS total,
				COUNT(CASE WHEN closed = 1 THEN 1 END) AS zamkniete,
				COUNT(CASE WHEN closed = 0 THEN 1 END) AS otwarte
				FROM info_center WHERE deleted=0');
			$SMARTY->assign('callcenter_stats',$result);
			
		} elseif ($pageview[$i] == 'box_links') {
		} elseif ($pageview[$i] == 'box_board') {
			
			$userlist = $DB->GetAll('SELECT id, name FROM users WHERE deleted = ? ORDER BY name;',array(0));
			//$read = $PROFILE->get('board_status');
			if (!$SESSION->check_exists_key('board_status')){
				$itemdata['board_status'] = 2;
				$SESSION->save('board_status',$itemdata['board_status']);
			} else
				$itemdata['board_status'] = $SESSION->get('board_status');
			
			if (!$SESSION->check_exists_key('board_prio')) {
				$itemdata['board_prio'] = '';
				$SESSION->save('board_prio',$itemdata['board_prio']);
			} else
				$itemdata['board_prio'] = $SESSION->get('board_prio');
			
			if (!$SESSION->check_exists_key('board_author')) {
				$itemdata['board_author'] = '';
				$SESSION->save('board_author','');
			} else
				$itemdata['board_author'] = $SESSION->get('board_author');
			
			$SESSION->_saveSession();
			
			$SMARTY->assign('messlist',$messlist);
			$SMARTY->assign('itemdata',$itemdata);
			$SMARTY->assign('userlist',$userlist);
			$obj->script("xajax_board_view_list('-2','-2','-2');");
			
		} elseif ($pageview[$i] == 'box_lms') {
			
			$layout['dbversion'] = $DB->GetDBVersion();
			$layout['dbtype'] = $CONFIG['database']['type'];
			
		} elseif ($pageview[$i] == 'box_system') {
			
			require_once LIB_DIR.'/Sysinfo.class.php';
			$SI = new Sysinfo;
			$SMARTY->assign('sysinfo', $SI->get_sysinfo());
			
		} elseif ($pageview[$i] == 'box_totd') {
			
			@include(LIB_DIR.'/locale/'.$_language.'/fortunes.php');
			
		}
		
		$obj->assign("id_box_".$i,"innerHTML",$SMARTY->fetch("welcome_".$pageview[$i].".html"));
	}
	
	return $obj;
}


function board_view_list($status=NULL,$prio=NULL,$author=NULL)
{
	global $DB,$SMARTY,$AUTH, $SESSION;
	$obj = new xajaxResponse();
	$status_read = $status_notread = $priorytet = $user = false;
	
	if (is_null($status) || $status=='-2')
		$status  = $SESSION->get('board_status');
	
	if (is_null($prio) || $prio == '-2') $priorytet = $SESSION->get('board_prio');
	else $priorytet = $prio;
	
	if (is_null($author) || $author == '-2') $user = $SESSION->get('board_author');
	else $user = $author;
	
	if ($status == '1') $status_read = true;
	if ($status == '2') $status_notread = true;
	
	$SESSION->save('board_prio',$priorytet);
	$SESSION->save('board_author',$user);
	$SESSION->save('board_status',$status);
	$SESSION->_saveSession();
	
	if (is_null($prio)) 
		$prio = $SESSION->get('board_prio');
	
	$messlist = $DB->GetAll('SELECT ta.idtablica AS id, t.ownerid, t.cdate, t.prio, t.description, t.message, ta.readmessage,
				    (SELECT u.name FROM users u WHERE u.id = t.ownerid) AS username 
				    FROM tablicaassign ta
				    JOIN tablica t ON (t.id = ta.idtablica)
				    WHERE t.active=1 AND t.deleted=0 AND ta.deleted = 0 
				    AND ta.iduser = '.$AUTH->id
				    .($status_read ? ' AND ta.readmessage=1' : '')
				    .($status_notread ? ' AND ta.readmessage=0' : '')
				    .($priorytet ? ' AND t.prio='.$priorytet : '')
				    .($user ? ' AND t.ownerid = '.$user : '')
				    .' ;');
	
	$lista = '';
	$cylce = 'light';
	$lista .= '<table width="100%" cellpadding="3">';
	
	if (sizeof($messlist)) {
	
		for ($i=0; $i<sizeof($messlist); $i++) {
			
			if ($cycle === 'light') $cycle = 'lucid';
			else $cycle = 'light';
			
			$lista .= '<tr class="'.$cycle.' lista"  onmouseover="addClass(this, \'highlight\')" onmouseout="removeClass(this, \'highlight\')">';
			$lista .= '<td width="1%"><img src="img/circle_';
			
			if ($messlist[$i]['prio'] == '1') $lista .= 'yellow';
			elseif ($messlist[$i]['prio'] == '2') $lista .= 'green';
			elseif ($messlist[$i]['prio'] == '3') $lista .= 'red';
			
			$lista .= '.png"></td>';
			
			$lista .= '<td width="1%" class="pad5" align="left">'.$messlist[$i]['username'].'<br>'.date('Y/m/d',$messlist[$i]['cdate']).'</td>';
			$lista .= '<td width="97%" class="pad5" align="left">';
			
			if (!empty($messlist[$i]['description']))
				$lista .= '<b>'.$messlist[$i]['description'].'</b><br>';
			
			$lista .= nl2br($messlist[$i]['message']);
			$lista .= '</td>';
			$lista .= '<td width="1%" nowrap>';
			
			if ($messlist[$i]['ownerid'] == $AUTH->id) 
				$lista .= '<img src="img/delete.gif" title="Skasuj wiadomość" style="cursor:pointer;" onclick="xajax_board_set_read('.$messlist[$i]['id'].',2,confirm(\'Napweno ?\'));">&nbsp;';
			
			if ($messlist[$i]['readmessage'] == '0')
				$lista .= '<img src="img/Apply.png" style="cursor:pointer;" title="oznacz jako przeczytaną" onclick="xajax_board_set_read('.$messlist[$i]['id'].',1);">';
			else
				$lista .= '<img src="img/Undo.png" style="cursor:pointer;" title="oznacz jako nieprzeczytaną" onclick="xajax_board_set_read('.$messlist[$i]['id'].',1);">';
			
			$lista .= '</td>';
			$lista .= '</tr>';
		}
	} else {
		$lista .= '<tr><td width="100%" align="center"><p><h2 style="color:#666666">Brak wiadomości</h2></p></td></tr>';
	}
	
	$lista .= '</table>';
	
	$obj->assign("id_board_list","innerHTML",$lista);
	
	if ($status_read || $status_notread) $obj->script("addClassId('id_board_status','active');");
	else $obj->script("removeClassId('id_board_status','active');");
	
	if ($priorytet) $obj->script("addClassId('id_board_prio','active');");
	else $obj->script("removeClassId('id_board_prio','active');");
	
	if ($user) $obj->script("addClassId('id_board_author','active');");
	else $obj->script("removeClassId('id_board_author','active');");
	
	return $obj;
}


function board_set_read($idb, $co = 1, $confirm = NULL) // id board
{
	global $DB,$AUTH;
	$obj = new xajaxResponse();
	$co = intval($co);
	
	if ($co == '1' && $tmp = $DB->GetRow('SELECT id,readmessage FROM tablicaassign WHERE idtablica = ? AND iduser = ? LIMIT 1;',array(intval($idb),$AUTH->id))) {
		$DB->Execute('UPDATE tablicaassign SET readmessage = ? WHERE id = ?;',array(
			($tmp['readmessage'] ? 0 : 1),
			$tmp['id']
		));
	}
	
	if ($co =='2' && $confirm == TRUE) {
		$DB->Execute('DELETE FROM tablicaassign WHERE idtablica = ? ;',array($idb));
		$DB->Execute('DELETE FROM tablica WHERE id = ? ;',array($idb));
	}
	
	$obj->script("xajax_board_view_list('-2','-2','-2');");
	return $obj;
}


function board_save($forms) 
{
	global $SMARTY,$DB,$PROFILE,$AUTH;
	$obj = new xajaxResponse();
	$blad = false;
	
	$obj->assign("id_alert_user","innerHTML","");
	$obj->script("removeClassId('wiadomosc','alerts');");
	
	if ($forms) $form = $forms['boardedit'];
	else $form = NULL;
	
	if ($form) {
		
		$user = $form['userbox'];
		
		if (empty($user)) {
			$blad = true;
			$obj->assign("id_alert_user","innerHTML","<br>WYBIERZ ODBIORCÓW<br><br>");
		} else {
			$userchk = implode("|",$user);
			$PROFILE->nowsave('board_default_user',$userchk);
		}
		
		if (!$blad && empty($form['message'])) {
			$blad = true;
			$obj->script("addClassId('wiadomosc','alerts');");
		}
		
		if (!$blad) {
			
			if ($DB->Execute('INSERT INTO tablica (ownerid, cdate, prio, description, message, active, deleted) 
				VALUES (?, ?, ?, ?, ?, ?, ?) ;',array($AUTH->id,time(),intval($form['prio']),($form['description'] ? $form['description'] : NULL),$form['message'],1,0))) 
			{
				$id = $DB->GetLastInsertId('tablica');
				$user[] = $AUTH->id;
				for ($i=0; $i<sizeof($user); $i++)
					$DB->Execute('INSERT INTO tablicaassign (idtablica,iduser,deleted,readmessage) VALUES (?,?,?,?);',array($id,$user[$i],0,0));
			}
			
			$obj->script("window.parent.parent.popclick();");
			$obj->script("window.parent.self.location='?m=welcome_new';");
		}
	} 
	else
		$obj->script("window.parent.parent.popclick();");
	
	return $obj;
}


$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('homepage_start','board_save','board_view_list','board_set_read'));
$SMARTY->assign('xajax',$LMS->RunXajax());

if (isset($_GET['boardadd'])) {

    $SMARTY->assign('boardadd',true);
    $SMARTY->assign('boardedit',false);
    $SMARTY->assign('userlist',$DB->GetAll('SELECT id, name FROM users WHERE deleted = ? AND id != ? ORDER BY name;',array(0,$AUTH->id)));
    $defaultuser = explode("|",$PROFILE->get('board_default_user',$AUTH->id));
    $SMARTY->assign('defaultuser',$defaultuser);
    $SMARTY->display('welcome_box_board.html');

} else {

    $SMARTY->display('welcome_new.html');
    
}

?>

