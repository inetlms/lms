<?php

/*
 *  iLMS version 1.0.3
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
 
function setnodeaccess($idek)
{
    global $DB, $LMS;
    $obj = new xajaxResponse();
    
    $tmp = $DB->GetOne('SELECT access FROM nodes WHERE id = ? LIMIT 1 ;',array($idek));
    $tmp = intval($tmp);
    if ($tmp === 1) $tmp = 0; else $tmp = 1;
    
    if ($DB->Execute('UPDATE nodes SET access = ? WHERE id = ? ;',array($tmp,$idek)));
    $nodename = $LMS->GetNodeName($idek); 
    $customerid = $LMS->GetNodeOwner($idek); //pobiera ID właścieiela komputera
    $customername = $LMS->GetCustomerName($customerid); // pobiera Imię i Nazwisko właściiela komputera na podstawie ID
    if (SYSLOG) {
	addlogs(($tmp ? 'włączono' : 'wyłączono').' dostęp dla komputera '.$nodename.', Użytkownik: '.$customername.'','e=acl;m=node;n='.$idek.';c='.$customerid);
    }
    
    if ($tmp === 0) {
	$obj->script("addClassId('idtr".$idek."','blend');");
	$obj->script("addClassId('idtra".$idek."','blend');");
	$obj->script("document.getElementById('src_access".$idek."').src='img/noaccess.gif';");
    } else {
	$obj->script("removeClassId('idtr".$idek."','blend');");
	$obj->script("removeClassId('idtra".$idek."','blend');");
	$obj->script("document.getElementById('src_access".$idek."').src='img/access.gif';");
  }
  return $obj;
}

function setnodewarning($idek)
{
    global $DB, $LMS;
    $obj = new xajaxResponse();
    $tmp = $DB->GetOne('SELECT warning FROM nodes WHERE id = ? LIMIT 1 ;',array($idek));
    $tmp = intval($tmp);
    if ($tmp === 1) $tmp = 0 ; else $tmp = 1;
      
    if ($DB->Execute('UPDATE nodes SET warning = ? WHERE id = ? ;',array($tmp,$idek)));
    $nodename = $LMS->GetNodeName($idek); 
    $customerid = $LMS->GetNodeOwner($idek); //pobiera ID właścieiela komputera
    $customername = $LMS->GetCustomerName($customerid); // pobiera Imię i Nazwisko właściiela komputera na podstawie ID
    if (SYSLOG) {        
	addlogs(($tmp ? 'włączono' : 'wyłączono').' wiadomość dla komputera '.$nodename.', Użytkownik: '.$customername.'','e=warn;m=node;n='.$idek.';c='.$customerid);
    }
    
    if ($tmp === 0) {
      $obj->script("document.getElementById('src_warning".$idek."').src='img/warningoff.gif';");
    } else {
      $obj->script("document.getElementById('src_warning".$idek."').src='img/warningon.gif';");
    }
  
  return $obj;
}

function setnodeblockade($idek)
{
    global $DB, $LMS;
    $obj = new xajaxResponse();
    $tmp = $DB->GetOne('SELECT blockade FROM nodes WHERE id = ? LIMIT 1 ;',array($idek));
    $tmp = intval($tmp);
    if ($tmp == 1) $tmp = 0 ; else $tmp = 1;
    
    if ($DB->Execute('UPDATE nodes SET blockade = ? WHERE id = ? ;',array($tmp,$idek)));
    $nodename = $LMS->GetNodeName($idek); 
    $customerid = $LMS->GetNodeOwner($idek); //pobiera ID właścieiela komputera
    $customername = $LMS->GetCustomerName($customerid); // pobiera Imię i Nazwisko właściiela komputera na podstawie ID
    if (SYSLOG) {
	addlogs(($tmp ? 'włączono' : 'wyłączono').' blokadę dla komputera'.$nodename.', Użytkownik: '.$customername.'','e=warn;m=node;n='.$idek.';c='.$customerid);
    }
    
    if ($tmp == 0) {
      $obj->script("document.getElementById('src_blockade".$idek."').src='img/padlockoff.png';");
    } else {
      $obj->script("document.getElementById('src_blockade".$idek."').src='img/padlock.png';");
    }
  
  return $obj;
}


function GetNodeList($order = 'name,asc', $search = NULL, $sqlskey = 'AND', $network = NULL, $status = NULL, $customergroup = NULL, $nodegroup = NULL, $nas = NULL, $pagestart) 
{
    global $DB,$LMS;
    
    $_order = $order;
    if ($order == '') $order = 'name,asc';
    list($order, $direction) = sscanf($order, '%[^,],%s');
    ($direction == 'desc') ? $direction = 'desc' : $direction = 'asc';
    $_node = array();
    
    if ($status)
    $_node = $DB->GetRow('SELECT 
			COUNT(CASE WHEN access=1 THEN 1 END) AS connected,
			COUNT(CASE WHEN warning=1 THEN 1 END) AS warning,
			COUNT(CASE WHEN blockade=1 THEN 1 END) AS blockade 
			FROM nodes WHERE ownerid > 0;');
    $_nodecount = $DB->getone('SELECT COUNT(id) FROM nodes WHERE ownerid > 0;');
    $_nodegroup = $DB->getone('SELECT COUNT(id) FROM nodegroupassignments;');

    switch ($order) {
	case 'name': $sqlord = ' ORDER BY n.name'; break;
	case 'id': $sqlord = ' ORDER BY n.id'; 	break;
	case 'mac': $sqlord = ' ORDER BY n.mac'; break;
	case 'ip': $sqlord = ' ORDER BY n.ipaddr'; break;
	case 'ip_pub': $sqlord = ' ORDER BY n.ipaddr_pub'; break;
	case 'ownerid': $sqlord = ' ORDER BY n.ownerid'; break;
	case 'owner': $sqlord = ' ORDER BY owner'; break;
    }

    if (sizeof($search))
	foreach ($search as $idx => $value) {
	    if ($value != '') {
		switch ($idx) {
		    case 'ipaddr': $searchargs[] = '(inet_ntoa(n.ipaddr) ?LIKE? ' . $DB->Escape('%' . trim($value) . '%') . ' OR inet_ntoa(n.ipaddr_pub) ?LIKE? ' . $DB->Escape('%' . trim($value) . '%') . ')'; break;
		    case 'state': if ($value != '0') $searchargs[] = 'n.location_city IN (SELECT lc.id FROM location_cities lc JOIN location_boroughs lb ON lb.id = lc.boroughid JOIN location_districts ld ON ld.id = lb.districtid JOIN location_states ls ON ls.id = ld.stateid WHERE ls.id = ' . $DB->Escape($value) . ')'; break;
		    case 'district':if ($value != '0') $searchargs[] = 'n.location_city IN (SELECT lc.id FROM location_cities lc JOIN location_boroughs lb ON lb.id = lc.boroughid JOIN location_districts ld ON ld.id = lb.districtid WHERE ld.id = ' . $DB->Escape($value) . ')';break;
		    case 'borough':if ($value != '0') $searchargs[] = 'n.location_city IN (SELECT lc.id FROM location_cities lc WHERE lc.boroughid = '. $DB->Escape($value) . ')';break;
		    default: $searchargs[] = 'n.' . $idx . ' ?LIKE? ' . $DB->Escape("%$value%");
		}
	    }
	}

    if (isset($searchargs))
	$searchargs = ' AND (' . implode(' ' . $sqlskey . ' ', $searchargs) . ')';

    $totalon = 0;
    $totaloff = 0;

    if ($network)
	$net = $LMS->GetNetworkParams($network);

    $md5 = md5(
	($_order ? $_order : '')
	.($search ? $search : '')
	.($sqlskey ? $sqlskey : '')
	.($network ? $network : '')
	.($status ? $status : '')
	.($customergroup ? $customergroup : '')
	.($nodegroup ? $nodegroup : '') 
	.($_node['connected'] ? $_node['connected'] : '0')
	.($_node['warning'] ? $_node['warning'] : '0')
	.($_node['blockade'] ? $_node['blockade'] : '0')
	.($_nodecount ? $_nodecount : '0')
	.($_nodegroup ? $_nodegroup : '0')
	.($nas ? $nas : '0')
    );
    
    $_cache = $LMS->loadcache('nodelist',$md5);

    if (!$_cache)
    {
	$preload = $DB->GetAll('SELECT n.id AS id, n.access '
	.(!$search ? ', nd.name AS devname, nd.location AS devlocation ' : '')
	.' FROM vnodes n 
	JOIN customersview c ON (n.ownerid = c.id) '
	.(!$search ? ' LEFT JOIN netdevices nd ON (nd.id = n.netdev) ' : '')
	. ($customergroup ? 'JOIN customerassignments ON (customerid = c.id) ' : '')
	. ($nodegroup ? 'LEFT JOIN nodegroupassignments ng ON (ng.nodeid = n.id) ' : '')
	
	. ' WHERE 1=1 '
	. ($network ? ' AND ((n.ipaddr > ' . $net['address'] . ' AND n.ipaddr < ' . $net['broadcast'] . ') OR (n.ipaddr_pub > ' . $net['address'] . ' AND n.ipaddr_pub < ' . $net['broadcast'] . '))' : '')
	. ($status == 1 ? ' AND n.access = 1' : '') //connected
	. ($status == 2 ? ' AND n.access = 0' : '') //disconnected
	. ($status == 3 ? ' AND n.lastonline > ?NOW? - ' . intval(get_conf('phpui.lastonline_limit',600)) : '') //online
	. ($status == 4 ? ' AND NOT EXISTS (SELECT * FROM nodeassignments na  WHERE n.id = na.nodeid)' : '') //without nodeassignments
	. ($status == 5 ? ' AND n.blockade = 1' : '') // z blokadą
	. ($status == 6 ? ' AND n.warning = 1' : '') // z powiadomieniem
	. ($customergroup ? ' AND customergroupid = ' . intval($customergroup) : '')
	. ($nodegroup ? ' AND ng.nodegroupid = ' . intval($nodegroup) : '')
	. ($nas ? ' AND n.nasid = \''.$nas.'\' ' : '')
	. (isset($searchargs) ? $searchargs : '')
	. ($sqlord != '' ? $sqlord . ' ' . $direction : ''));
	
//	$LMS->saveCache('nodelist',$md5,$preload);
	$tmp = array();
	for ($i=0; $i<sizeof($preload); $i++) {
	    $tmp[$i]['id'] = $preload[$i]['id'];
	    $tmp[$i]['access'] = $preload[$i]['access'];
	}
	$LMS->saveCache('nodelist',$md5,$tmp);
	
    } else {
	$preload = $_cache;
    }
    
    $idlist = array();
    
    if ($preload) {
	$pageend = $pagestart + get_conf('phpui.nodelist_pagelimit','50');
	for ($p1=$pagestart; $p1<$pageend; $p1++)
	{
	    if ($preload[$p1]['id'])
		$idlist[] = $preload[$p1]['id'];
	}
    } else {
	$idlist[0] = 0;
    }
    
    $_idlist = implode(',',$idlist);
    
    $nodelist = $DB->GetAll('SELECT n.id AS id, n.ipaddr, inet_ntoa(n.ipaddr) AS ip, n.ipaddr_pub,
	inet_ntoa(n.ipaddr_pub) AS ip_pub, n.mac, n.name, n.ownerid, n.access, n.warning, n.blockade, 
	n.linktype, n.linkspeed, n.linktechnology, n.producer, n.model, nas.name AS nasname, 
	n.netdev, n.lastonline, n.info, (SELECT 1 FROM monitnodes WHERE monitnodes.id = n.id LIMIT 1) AS monitoring, '
	. $DB->Concat('c.lastname', "' '", 'c.name') . ' AS owner '
	.(!$search ? ', nd.name AS devname, nd.location AS devlocation ' : '')
	.' FROM vnodes n 
	JOIN customersview c ON (n.ownerid = c.id) 
	LEFT JOIN nodes nas ON (nas.id = n.nasid AND nas.ownerid = 0) '
	.(!$search ? ' LEFT JOIN netdevices nd ON (nd.id = n.netdev) ' : '')
//	. ($customergroup ? 'JOIN customerassignments ON (customerid = c.id) ' : '')
//	. ($nodegroup ? 'JOIN nodegroupassignments ON (nodeid = n.id) ' : '')
	. ' WHERE 1=1 '
	.' AND n.id IN ('.$_idlist.') '
	. ($sqlord != '' ? $sqlord . ' ' . $direction : ''));
	
    if ($preload) {
	foreach ($preload as $idx => $row) {
	    ($row['access']) ? $totalon++ : $totaloff++;
	}
    }

	$nodelist['total'] = sizeof($preload);
	$nodelist['order'] = $order;
	$nodelist['direction'] = $direction;
	$nodelist['totalon'] = $totalon;
	$nodelist['totaloff'] = $totaloff;

		return $nodelist;
}



$layout['pagetitle'] = trans('Nodes List');

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(!isset($_GET['o']))
	$SESSION->restore('nlo', $o);
else
	$o = $_GET['o'];
$SESSION->save('nlo', $o);

if(!isset($_GET['s']))
	$SESSION->restore('nls', $s);
else
	$s = $_GET['s'];
$SESSION->save('nls', $s);

if(!isset($_GET['n']))
	$SESSION->restore('nln', $n);
else
	$n = $_GET['n'];
$SESSION->save('nln', $n);

if(!isset($_GET['g']))
	$SESSION->restore('nlg', $g);
else
	$g = $_GET['g'];
$SESSION->save('nlg', $g);

if(!isset($_GET['ng']))
	$SESSION->restore('nlng', $ng);
else
	$ng = $_GET['ng'];
$SESSION->save('nlng', $ng);

if (!isset($_GET['nas']))
    $SESSION->restore('nlnas',$nas);
else
    $nas = $_GET['nas'];
$SESSION->save('nlnas',$nas);

if ($SESSION->is_set('nlp') && !isset($_GET['page']))
	$SESSION->restore('nlp', $_GET['page']);
	
$page = (!isset($_GET['page']) ? 1 : $_GET['page']);
$pagelimit = get_conf('phpui.nodelist_pagelimit','50');
$start = ($page - 1) * $pagelimit;


$nodelist = GetNodeList($o, NULL, NULL, $n, $s, $g, $ng, $nas, $start);
//$nodelist = $LMS->GetNodeList($o, NULL, NULL, $n, $s, $g, $ng);

$listdata['total'] = $nodelist['total'];
$listdata['order'] = $nodelist['order'];
$listdata['direction'] = $nodelist['direction'];
$listdata['totalon'] = $nodelist['totalon'];
$listdata['totaloff'] = $nodelist['totaloff'];
$listdata['network'] = $n;
$listdata['customergroup'] = $g;
$listdata['nodegroup'] = $ng;
$listdata['state'] = $s;
$listdata['nas'] = $nas;

unset($nodelist['total']);
unset($nodelist['order']);
unset($nodelist['direction']);
unset($nodelist['totalon']);
unset($nodelist['totaloff']);


$SESSION->save('nlp', $page);

$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);
$SMARTY->assign('nodelist',$nodelist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('networks',$LMS->GetNetworks());
$SMARTY->assign('nodegroups', $LMS->GetNodeGroupNames());
$SMARTY->assign('customergroups', $LMS->CustomergroupGetAll());
$SMARTY->assign('naslist',$LMS->getNasList());

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('setnodeaccess','setnodewarning','setnodeblockade'));
$SMARTY->assign('xajax',$LMS->RunXajax());

$SMARTY->display('nodelist.html');

?>
