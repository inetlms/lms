<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2012 LMS Developers
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
 *  $Id$
 */
if (get_conf('jambox.enabled',0))
    require_once(MODULES_DIR.'/customer.tv.inc.php');

if($layout['module'] != 'customeredit')
{
	$customerinfo = $LMS->GetCustomer($customerid);

    if(!$customerinfo)
    {
        $SESSION->redirect('?m=customerlist');
    }

	$SMARTY->assignByRef('customerinfo', $customerinfo);

}

$expired = !empty($_GET['expired']) ? true : false;
$assignments = $LMS->GetCustomerAssignments($customerid, !empty($expired) ? $expired : NULL);
$customergroups = $LMS->CustomergroupGetForCustomer($customerid);
$othercustomergroups = $LMS->GetGroupNamesWithoutCustomer($customerid);
$balancelist = $LMS->GetCustomerBalanceList($customerid);
$customervoipaccounts = $LMS->GetCustomerVoipAccounts($customerid);
$documents = $LMS->GetDocuments($customerid, 10);
$taxeslist = $LMS->GetTaxes();
$allnodegroups = $LMS->GetNodeGroupNames();
$messagelist = $LMS->GetMessages($customerid, 10);
$sysloglist = $LMS->GetCustomerSyslog($customerid, 20);
$eventlist = $LMS->EventSearch(array('customerid' => $customerid), 'date,desc', true);
$customernodes = $LMS->GetCustomerNodes($customerid);

if (!get_conf('privileges.hide_callcenter')) {
    $customercallcenter = $LMS->GetBoxListInfoCenter($customerid);
    $SMARTY->assign('openedcallcenter',$customercallcenter['opened']);
    unset($customercallcenter['opened']);

} else
    $customercallcenter = NULL;


if(!empty($documents))
{
        $SMARTY->assign('docrights', $DB->GetAllByKey('SELECT doctype, rights
	        FROM docrights WHERE userid = ? AND rights > 1', 'doctype', array($AUTH->id)));
}

if ($_pluginc['customer']) {
    
    $inclist = $_pluginc['customer'];
    for ($i=0; $i<sizeof($inclist); $i++)
	@include($inclist[$i]['modfile']);
}


$SMARTY->assign(array(
	'expired' => $expired, 
	'time' => $SESSION->get('addbt'),
	'value' => $SESSION->get('addbv'),
	'taxid' => $SESSION->get('addbtax'),
	'comment' => $SESSION->get('addbc'),
	'sourceid' => $SESSION->get('addsource'),
));

/*
//echo "<pre>"; print_r($customernodes); echo "</pre>"; die;
if (check_modules(110) && $customernodes) // sprawdzamy czy moduł monitoringu jest włączony i klient ma kompy
{
    $count = sizeof($customernodes);
    for ($i=0; $i<$count; $i++)
    {
	if (file_exists(RRD_DIR.'/ping.node.'.$customernodes[$i]['id'].'.rrd'))
	{
//	    $monit['ping_nodeid'][$i] = $customernodes[$i]['id'];
	    $monit['ping_test'] = true;
	}
	if (file_exists(RRD_DIR.'/signal.node.'.$customernodes[$i]['id'].'.rrd')) 
	{
//	    $monit['signal_nodeid'][$i] = $customernodes[$i]['id'];
	    $monit['signal_test'] = true;
	}
    }
}
*/
$SMARTY->assign('monit',$monit);
$SMARTY->assign('sourcelist', $DB->GetAll('SELECT id, name FROM cashsources ORDER BY name'));
$SMARTY->assignByRef('customernodes', $customernodes);
$SMARTY->assignByRef('assignments', $assignments);
$SMARTY->assignByRef('customergroups', $customergroups);
$SMARTY->assignByRef('othercustomergroups', $othercustomergroups);
$SMARTY->assignByRef('balancelist', $balancelist);
$SMARTY->assignByRef('customervoipaccounts', $customervoipaccounts);
$SMARTY->assignByRef('documents', $documents);
$SMARTY->assignByRef('taxeslist', $taxeslist);
$SMARTY->assignByRef('allnodegroups', $allnodegroups);
$SMARTY->assignByRef('messagelist', $messagelist);
$SMARTY->assignByRef('eventlist', $eventlist);
$SMARTY->assignByRef('customercallcenter',$customercallcenter);
$SMARTY->assignByRef('sysloglist',$sysloglist);

?>