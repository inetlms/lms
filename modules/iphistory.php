<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2012 LMS iNET Developers
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
 *  $Id: v 1.00 2013/01/03 22:01:35 Sylwester Kondracki Exp $
 */

$layout['pagetitle'] = 'Historia zmian adresów IP';

function gethistorylist($order = NULL,$direction = NULL, $cid = NULL, $datefrom = NULL, $dateto = NULL, $search = NULL)
{
    global $DB;
    
    if (!is_null($search) || !empty($search)) $search = strtoupper(str_replace(',','.',$search));
    
    if (empty($order)) $order = 'id';
    if (empty($direction)) $direction = 'desc';
    switch ($order) {
	case 'fromdate'		: $order = 'h.fromdate'; break;
	case 'todate'		: $order = 'h.todate'; break;
	case 'ipaddr'		: $order = 'h.ipaddr'; break;
	case 'ipaddr_pub'	: $order = 'h.ipaddr_pub'; break;
	case 'node'		: $order = 'n.name'; break;
	case 'customer'		: $order = 'customername'; break;
    }
    return $DB->GetAll('SELECT 
		h.id, h.nodeid, inet_ntoa(h.ipaddr) AS ipaddr, inet_ntoa(h.ipaddr_pub) AS ipaddr_pub, h.ownerid, h.fromdate, h.todate,
		n.name AS nodesname, '.$DB->Concat('c.lastname', "' '", 'c.name').' AS customername 
		FROM iphistory h 
		LEFT JOIN nodes n ON (n.id = h.nodeid) 
		JOIN customers c ON (c.id = h.ownerid) 
		WHERE h.ownerid != 0 '
		.($cid ? 'AND h.ownerid = '.$cid.' ' : '')
		.($datefrom ? ' AND h.fromdate >= '.$datefrom.' ' : '')
		.($dateto ? 'AND h.todate > 0 AND h.todate <= '.$dateto.' ' : '')
		.($search ? 'AND ( inet_ntoa(h.ipaddr) ?LIKE? '.$DB->Escape("%$search%").' OR inet_ntoa(h.ipaddr_pub) ?LIKE? '.$DB->Escape("%$search%").') ' :'')
		.' ORDER BY '.$order.' '.$direction.' '
		.' ;'
	    );
}


if (!isset($_GET['o'])) 
    $SESSION->restore('iph_o',$listdata['order']);
else 
    $listdata['order'] = $_GET['o'];
$SESSION->save('iph_o',$listdata['order']);


if (!isset($_GET['cid'])) 
    $SESSION->restore('iph_cid',$listdata['cid']);
else 
    $listdata['cid'] = $_GET['cid'];
$SESSION->save('iph_cid',$listdata['cid']);

if (!isset($_GET['dfrom'])) 
    $SESSION->restore('iph_dfrom',$listdata['dfrom']);
else 
    $listdata['dfrom'] = $_GET['dfrom'];
$SESSION->save('iph_dfrom',$listdata['dfrom']);

if (!isset($_GET['dto'])) 
    $SESSION->restore('iph_dto',$listdata['dto']);
else 
    $listdata['dto'] = $_GET['dto'];
$SESSION->save('iph_dto',$listdata['dto']);

if (!isset($_GET['sip'])) 
    $SESSION->restore('iph_sip',$listdata['searchip']);
else 
    $listdata['searchip'] = $_GET['sip'];
$SESSION->save('iph_sip',$listdata['searchip']);


list($listdata['order'],$listdata['direction']) = sscanf($listdata['order'],'%[^,],%s');
($listdata['direction'] != 'desc') ? $listdata['direction'] = 'asc' : $listdata['direction'] = 'desc';


if (empty($listdata['order'])) $listdata['order'] = 'id';

$dfrom = $dto = NULL;
if (!empty($listdata['dfrom']))
    $dfrom = strtotime($listdata['dfrom']);

if (!empty($listdata['dto']))
    $dto = strtotime($listdata['dto'])+86399;

$iplist = gethistorylist($listdata['order'],$listdata['direction'],$listdata['cid'],$dfrom,$dto,$listdata['searchip']);

if (!isset($_GET['page'])) $SESSION->restore('iph_page',$_GET['page']);
$page = (!isset($_GET['page']) ? 1 : $_GET['page']);
$pagelimit = get_conf('phpui.iphistory_pagelimit',50);
$start = ($page - 1) * $pagelimit;
$listdata['total'] = sizeof($iplist);

$SMARTY->assign('customername',($listdata['cid'] ? $LMS->getcustomername($listdata['cid']) : ''));

$SMARTY->assign('listdata',$listdata);

$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);

$SMARTY->assign('iplist',$iplist);
$SMARTY->display('iphistory.html');
?>