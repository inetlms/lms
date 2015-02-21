<?php

/*
 *  iNET LMS
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

$layout['pagetitle'] = trans('IP Networks');

// status
if(!isset($_GET['s'])) $SESSION->restore('netlist_s', $s); else $s = $_GET['s']; $SESSION->save('netlist_s', $s);

// hostid
if(!isset($_GET['h'])) $SESSION->restore('netlist_h', $h); else $h = $_GET['h']; $SESSION->save('netlist_h', $h);

//sort
if (!isset($_GET['o'])) $SESSION->restore('netlist_o',$o); else $o = $_GET['o']; $SESSION->save('netlist_o', $o);

if (!isset($_GET['page'])) $SESSION->restore('netlist_page',$_GET['page']);
if (empty($_GET['page']) || !isset($_GET['page'])) $_GET['page'] = 1;
$SESSION->save('netlist_page',$_GET['page']);

$page = $_GET['page'];
$pagelimit = get_conf('phpui.netlist_pagelimit','50');
$start = ($page - 1) * $pagelimit;

function GetNetworkList($status = NULL, $hostid = NULL, $order='id,asc', $start=0) 
{ 
    global $DB;

    if($order=='')
	$order='id,asc';
    
    list($order,$direction) = sscanf($order, '%[^,],%s');
    ($direction=='desc') ? $direction = 'desc' : $direction = 'asc';

    switch($order)
    {
	case 'name':		$sqlord = ' ORDER BY n.name';		break;
	case 'id':		$sqlord = ' ORDER BY n.id';		break;
	case 'address': 	$sqlord = ' ORDER BY n.address';	break;
	case 'mask':		$sqlord = ' ORDER BY n.mask';		break;
	case 'interface':	$sqlord = ' ORDER BY n.interface';	break;
	case 'host':		$sqlord = ' ORDER BY hostname';		break;
	case 'size':		$sqlord = ' ORDER BY size';		break;
	case 'assigned':	$sqlord = ' ORDER BY assigned';		break;
    }
    
    $preload = $DB->getAll('SELECT n.id, n.name, h.name AS hostname, inet_ntoa(address) AS address, 
	mask, interface, pow(2,(32 - mask2prefix(inet_aton(mask)))) AS size '
	.($order == 'assigned' ? ',(SELECT COUNT(*) 
		FROM nodes 
		WHERE netid = n.id AND ((ipaddr >= address AND ipaddr <= broadcast(address, inet_aton(mask))) 
		OR (ipaddr_pub >= address AND ipaddr_pub <= broadcast(address, inet_aton(mask))))
	) AS assigned ' : '')
	.' FROM networks n 
	LEFT JOIN hosts h ON h.id = n.hostid'
	.' WHERE 1=1 '
	.($status == '1' ? ' AND disabled=1' : '')
	.($status == '0' ? ' AND disabled=0' : '')
	.($hostid ? ' AND hostid = '.intval($hostid) : '')
	.($sqlord != '' ? $sqlord.' '.$direction : '')
	);
    
    $idlist = array();
    $pageend = $start + get_conf('phpui.netlist_pagelimit','50');
    
    for ($i=$start; $i<$pageend; $i++) {
	if ($preload[$i]['id']) 
	    $idlist[] = $preload[$i]['id'];
    }
    
    if (empty($idlist))
	$idlist[0] = 0;
    
    $_idlist = implode(',',$idlist);

    $networks = $DB->getAll('SELECT n.id, n.name, h.name AS hostname, inet_ntoa(address) AS address, 
	address AS addresslong, mask, interface, gateway, dns, dns2, 
	domain, wins, dhcpstart, dhcpend, ipnat,
	mask2prefix(inet_aton(mask)) AS prefix,
	broadcast(address, inet_aton(mask)) AS broadcastlong,
	inet_ntoa(broadcast(address, inet_aton(mask))) AS broadcast,
	pow(2,(32 - mask2prefix(inet_aton(mask)))) AS size, disabled, 
	(SELECT COUNT(*) 
		FROM nodes 
		WHERE netid = n.id AND ((ipaddr >= address AND ipaddr <= broadcast(address, inet_aton(mask))) 
		OR (ipaddr_pub >= address AND ipaddr_pub <= broadcast(address, inet_aton(mask))))
	) AS assigned '
	.' FROM networks n 
	LEFT JOIN hosts h ON h.id = n.hostid'
	.' WHERE 1=1 '
	.'AND n.id IN ('.$_idlist.') '
	.($sqlord != '' ? $sqlord.' '.$direction : '')
	);

    $networks['total'] = sizeof($preload);
    $networks['order'] = $order;
    $networks['direction'] = $direction;
    return $networks;
}


$listdata['state'] = $s;
$listdata['hostid'] = $h;
$listdata['order'] = $o;

$netlist = GetNetworkList($s,$h,$o,$start);

$listdata['total'] = $netlist['total'];
$listdata['size'] = $netlist['size'];
$listdata['assigned'] = $netlist['assigned'];
$listdata['online'] = $netlist['online'];
$listdata['order'] = $netlist['order'];
$listdata['direction'] = $netlist['direction'];

unset($netlist['total']);
unset($netlist['assigned']);
unset($netlist['size']);
unset($netlist['online']);
unset($netlist['order']);
unset($netlist['direction']);

$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('netlist',$netlist);
$SMARTY->assign('hostlist',$DB->GetAll('SELECT id, name FROM hosts ORDER BY name;'));
$SMARTY->assign('start',$start);
$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->display('netlist.html');

?>
