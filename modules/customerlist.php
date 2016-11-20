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

function GetCustomerList($order = 'customername,asc', $state = NULL, $network = NULL, $customergroup = NULL, $search=NULL, $time = NULL, $sqlskey = 'AND', $nodegroup = NULL, $division = NULL, $firstletter = NULL, $status = NULL, $contractend = NULL, $odlaczeni = NULL, $warn = NULL, $origin = NULL, $osobowosc = NULL, $nodeblock = NULL, $pagestart) 
{
    global $DB,$LMS;
    
    if ($status && in_array($status,array(16,17,6,11,12)))
    $_sum = $DB->getone('SELECT SUM(value) FROM cash;');
    $_cuscount = $DB->getOne('SELECT COUNT(id) FROM customers WHERE type=0 OR type=1;');
    $_node = array();
    $_order = $order;
    
    if ($odlaczeni || $warn || $nodeblock)
    $_node = $DB->GetRow('SELECT 
			COUNT(CASE WHEN access=1 THEN 1 END) AS connected,
			COUNT(CASE WHEN warning=1 THEN 1 END) AS warning,
			COUNT(CASE WHEN blockade=1 THEN 1 END) AS blockade 
			FROM nodes WHERE ownerid > 0;');
    
    

    list($order, $direction) = sscanf($order, '%[^,],%s');

    ($direction != 'desc') ? $direction = 'asc' : $direction = 'desc';

    if ($origin) 
	$origin = intval($origin);

    switch ($order) 
    {
	case 'id'		: $sqlord = ' ORDER BY c.id'; break;
	case 'address'	: $sqlord = ' ORDER BY address';break;
	case 'balance'	: $sqlord = ' ORDER BY balance'; break;
	case 'tariff'	: $sqlord = ' ORDER BY tariffvalue'; break;
//	case 'tariff'	: $sqlord = ' ORDER BY t.value'; break;
	default		: $sqlord = ' ORDER BY customername'; break;
    }

    switch ($state) 
    {
	case 4:
		if (!empty($network) || !empty($customergroup) || !empty($nodegroup)) 
		{
		    $customerlist['total'] = 0;
		    $customerlist['state'] = 0;
		    $customerlist['order'] = $order;
		    $customerlist['direction'] = $direction;
		    return $customerlist;
		}
		$deleted = 1;
	break;
	case 5: $disabled = 1; break;
	case 6: $indebted = 1; break;
	case 7: $online = 1; break;
	case 8: $groupless = 1; break;
	case 9: $tariffless = 1; break;
	case 10: $suspended = 1; break;
	case 11: $indebted2 = 1; break;
	case 12: $indebted3 = 1; break;
	case 15: $tying = 1; break;
    }

    switch ($status) 
    {
	case 6: $indebted = 1; break;
	case 7: $online = 1; break;
	case 8: $groupless = 1; break;
	case 9: $tariffless = 1; break;
	case 10: $suspended = 1; break;
	case 11: $indebted2 = 1; break;
	case 12: $indebted3 = 1; break;
	case 15: $tying = 1; break;
	case 16: $balanceok = 1; break;
	case 17: $balanceok2 = 1; break;
    }


    switch ($odlaczeni) 
    {
	case 1 : $disabled = 1; break;
	case 2 : $disabled = 2; break;
	case 3 : $disabled = 3; break;
	case 4 : $disabled = 4; break;
    }

    switch ($warn) 
    {
	case 1 : $warning = 1; break;
	case 2 : $warning = 2; break;
	case 3 : $warning = 3; break;
	default: $warning = NULL; break;
    }

    switch ($nodeblock) 
    {
	case 1 : $blockade = 1; break;
	case 2 : $blockade = 2; break;
	case 3 : $blockade = 3; break;
	case 4 : $blockade = 4; break;
	default: $blockade = NULL; break;
    }

    if ($network)
	$net = $LMS->GetNetworkParams($network);

    $over = $below = 0;

    // pierwsza litera nazwiska
    $sqlfl = NULL;
    if (!empty($firstletter)) 
    {
	$firstletter = strtoupper($firstletter);
	switch ($firstletter) 
	{
	    case 'A' : $sqlfl = ' UPPER(lastname) ?LIKE? '.$DB->Escape("A%").' OR UPPER(lastname) ?LIKE? '.$DB->Escape("Ą%").' '; break;
	    case 'C' : $sqlfl = ' UPPER(lastname) ?LIKE? '.$DB->Escape("C%").' OR UPPER(lastname) ?LIKE? '.$DB->Escape("Ć%").' '; break;
	    case 'E' : $sqlfl = ' UPPER(lastname) ?LIKE? '.$DB->Escape("E%").' OR UPPER(lastname) ?LIKE? '.$DB->Escape("Ę%").' '; break;
	    case 'L' : $sqlfl = ' UPPER(lastname) ?LIKE? '.$DB->Escape("L%").' OR UPPER(lastname) ?LIKE? '.$DB->Escape("Ł%").' '; break;
	    case 'N' : $sqlfl = ' UPPER(lastname) ?LIKE? '.$DB->Escape("N%").' OR UPPER(lastname) ?LIKE? '.$DB->Escape("Ń%").' '; break;
	    case 'O' : $sqlfl = ' UPPER(lastname) ?LIKE? '.$DB->Escape("O%").' OR UPPER(lastname) ?LIKE? '.$DB->Escape("Ó%").' '; break;
	    case 'S' : $sqlfl = ' UPPER(lastname) ?LIKE? '.$DB->Escape("S%").' OR UPPER(lastname) ?LIKE? '.$DB->Escape("Ś%").' '; break;
	    case 'Z' : $sqlfl = ' UPPER(lastname) ?LIKE? '.$DB->Escape("Z%").' OR UPPER(lastname) ?LIKE? '.$DB->Escape("Ź%").' OR UPPER(lastname) ?LIKE? '.$DB->Escape("Ż%").' '; break;
	    default : $sqlfl = ' UPPER(lastname) ?LIKE? '.$DB->Escape("$firstletter%");
	}
    }
    
    if (sizeof($search))
	foreach ($search as $key => $value) {
		if ($value != '') {
			switch ($key) {
				case 'phone':
					$searchargs[] = 'EXISTS (SELECT 1 FROM customercontacts WHERE customerid = c.id AND phone ?LIKE? ' . $this->DB->Escape("%$value%") . ')';
				break;
				case 'zip':
				case 'city':
				case 'address':
					// UPPER here is a workaround for postgresql ILIKE bug
					$searchargs[] = "(UPPER($key) ?LIKE? UPPER(" . $this->DB->Escape("%$value%") . ") OR UPPER(post_$key) ?LIKE? UPPER(" . $this->DB->Escape("%$value%") . '))';
				break;
				case 'customername':
					// UPPER here is a workaround for postgresql ILIKE bug
					$searchargs[] = $this->DB->Concat('UPPER(c.lastname)', "' '", 'UPPER(c.name)') . ' ?LIKE? UPPER(' . $this->DB->Escape("%$value%") . ')';
				break;
				case 'createdfrom':
					if ($search['createdto']) {
						$searchargs['createdfrom'] = '(creationdate >= ' . intval($value)
						. ' AND creationdate <= ' . intval($search['createdto']) . ')';
						unset($search['createdto']);
					}
					else
						$searchargs[] = 'creationdate >= ' . intval($value);
				break;
				case 'createdto':
					if (!isset($searchargs['createdfrom']))
						$searchargs[] = 'creationdate <= ' . intval($value);
				break;
				case 'deletedfrom':
					if ($search['deletedto']) {
						$searchargs['deletedfrom'] = '(moddate >= ' . intval($value) . ' AND moddate <= ' . intval($search['deletedto']) . ')';
						unset($search['deletedto']);
					}
					else
						$searchargs[] = 'moddate >= ' . intval($value);
					$deleted = 1;
				break;
				case 'deletedto':
					if (!isset($searchargs['deletedfrom']))
						$searchargs[] = 'moddate <= ' . intval($value);
					$deleted = 1;
				break;
				case 'type':
					$searchargs[] = 'type = ' . intval($value);
				break;
				case 'linktype':
					$searchargs[] = 'EXISTS (SELECT 1 FROM nodes WHERE ownerid = c.id AND linktype = ' . intval($value) . ')';
				break;
				case 'linkspeed':
					$searchargs[] = 'EXISTS (SELECT 1 FROM nodes WHERE ownerid = c.id AND linkspeed = ' . intval($value) . ')';
				break;
				case 'doctype':
					$val = explode(':', $value); // <doctype>:<fromdate>:<todate>
					$searchargs[] = 'EXISTS (SELECT 1 FROM documents
						WHERE customerid = c.id '
						. (!empty($val[0]) ? ' AND type = ' . intval($val[0]) : '')
						. (!empty($val[1]) ? ' AND cdate >= ' . intval($val[1]) : '')
						. (!empty($val[2]) ? ' AND cdate <= ' . intval($val[2]) : '')
					. ')';
				break;
				case 'stateid':
					$searchargs[] = 'EXISTS (SELECT 1 FROM zipcodes z WHERE z.zip = c.zip AND z.stateid = ' . intval($value) . ')';
				break;
				case 'tariffs':
					$searchargs[] = 'EXISTS (SELECT 1 FROM assignments a 
					WHERE a.customerid = c.id 
					AND (datefrom <= ?NOW? OR datefrom = 0) 
					AND (dateto >= ?NOW? OR dateto = 0) 
					AND (tariffid IN (' . $value . ')))';
				break;
				default:
					$searchargs[] = "$key ?LIKE? " . $this->DB->Escape("%$value%");
			}
		}
	}

	if (isset($searchargs))
		$sqlsarg = implode(' ' . $sqlskey . ' ', $searchargs);


    $suspension_percentage = f_round(get_conf('finances.suspension_percentage'));
    
    $md5 = md5(
	($_order ? $_order : '')
	.($state ? $state : '')
	.($network ? $network : '')
	.($customergroup ? $customergroup : '')
	.($sqlskey ? $sqlskey : '')
	.($nodegroup ? $nodegroup : '')
	.($division ? $division : '')
	.($firstletter ? $firstletter : '')
	.($status ? $status : '')
	.($contractend ? $contractend : '')
	.($odlaczeni ? $odlaczeni : '')
	.($warn ? $warn : '')
	.($origin ? $origin : '')
	.($osobowosc ? $osobowosc : '')
	.($nodeblock ? $nodeblock : '')
	.($sqlsarg ? $sqlsarg : '')
	.($time ? $time : '')
	.($_sum ? $_sum : '0')
	.($_node['connected'] ? $_node['connected'] : '0')
	.($_node['warning'] ? $_node['warning'] : '0')
	.($_node['blockade'] ? $_node['blockade'] : '0')
	.($_cuscount ? $_cuscount : '0')
    );
    
    $_cache = $LMS->loadCache('customerlist',$md5);
    
    if (!$_cache) 
    {
    $preload = $DB->GetAll(
	'SELECT c.id AS id, ' . $DB->Concat('UPPER(lastname)', "' '", 'c.name') . ' AS customername , COALESCE(b.value, 0) AS balance, COALESCE(t.value, 0) AS tariffvalue 
	FROM customersview c
	LEFT JOIN countries ON (c.countryid = countries.id) '
	. ($customergroup ? 'LEFT JOIN customerassignments ON (c.id = customerassignments.customerid) ' : '')
	.(in_array(get_conf('database.type'),array('mysql','mysqli')) ? ' JOIN customercash b ON (b.customerid = c.id) ' : 
	 ' LEFT JOIN (SELECT
		SUM(value) AS value, customerid
		FROM cash 
		GROUP BY customerid
	) b ON (b.customerid = c.id) ')
	.' LEFT JOIN (SELECT a.customerid,
		SUM((CASE a.suspended
		WHEN 0 THEN (((100 - a.pdiscount) * (CASE WHEN t.value IS NULL THEN l.value ELSE t.value END) / 100) - a.vdiscount)
		ELSE ((((100 - a.pdiscount) * (CASE WHEN t.value IS NULL THEN l.value ELSE t.value END) / 100) - a.vdiscount) * ' . $suspension_percentage . ' / 100) END)
		* (CASE t.period
			WHEN ' . MONTHLY . ' THEN 1
			WHEN ' . YEARLY . ' THEN 1/12.0
			WHEN ' . HALFYEARLY . ' THEN 1/6.0
			WHEN ' . QUARTERLY . ' THEN 1/3.0
			ELSE (CASE a.period
			    WHEN ' . MONTHLY . ' THEN 1
			    WHEN ' . YEARLY . ' THEN 1/12.0
			    WHEN ' . HALFYEARLY . ' THEN 1/6.0
			    WHEN ' . QUARTERLY . ' THEN 1/3.0
			    ELSE 0 END)
			END)
		) AS value 
		FROM assignments a
		LEFT JOIN tariffs t ON (t.id = a.tariffid)
		LEFT JOIN liabilities l ON (l.id = a.liabilityid AND a.period != ' . DISPOSABLE . ')
		WHERE (a.datefrom <= ?NOW? OR a.datefrom = 0) AND (a.dateto > ?NOW? OR a.dateto = 0) 
		GROUP BY a.customerid
	) t ON (t.customerid = c.id)' 
	.((
	    ($online ) ||
	    (!$odlaczeni && $disabled) || ($odlaczeni && $disabled == 1) || ($odlaczeni && $disabled == 2) || ($odlaczeni && $disabled == 3) || 
	    ($warn && $warning == 1) || ($warn && $warning == 2) || ($warn && $warning == 3) || ($nodeblock && $blockade == 1) || 
	    ($nodeblock && $blockade == 2) || ($nodeblock && $blockade == 3) || ($nodeblock && $blockade == 4) || ($odlaczeni && $disabled == 4)
	  ) ? 
	    ' LEFT JOIN (SELECT ownerid,
	SUM(access) AS acsum, COUNT(access) AS account,
		SUM(warning) AS warnsum, COUNT(warning) AS warncount, 
		SUM(blockade) AS blocksum, COUNT(blockade) AS blockcount,
		(CASE WHEN MAX(lastonline) > ?NOW? - ' . intval(get_conf('phpui.lastonline_limit')) . '
			THEN 1 ELSE 0 END) AS online
		FROM nodes
		WHERE ownerid > 0
		GROUP BY ownerid
	) s ON (s.ownerid = c.id)' : '')
	.' WHERE c.deleted = ' . intval($deleted)
	. ($tying ? ' AND c.status=4 ' : '')
	. ($contractend ? ' AND c.id IN ('.$contractend.')' : '')
	. ($state <= 3 && $state > 0 ? ' AND c.status = ' . intval($state) : '')
	. ($division ? ' AND c.divisionid = ' . intval($division) : '')
	. ($online ? ' AND s.online = 1' : '')
	. ($indebted ? ' AND b.value < 0' : '')
	. ($indebted2 ? ' AND b.value < -t.value' : '')
	. ($indebted3 ? ' AND b.value < -t.value * 2' : '')
	. ($balanceok ? ' AND (b.value = 0 OR b.value IS NULL) ' : '')
	. ($balanceok2 ? ' AND b.value > 0' : '')
	. ($origin ? ' AND c.origin = '.$origin : '')
	. (!$odlaczeni && $disabled ? ' AND s.ownerid IS NOT NULL AND s.account > s.acsum' : '')
	. ($odlaczeni && $disabled == 1 ? ' AND s.ownerid IS NOT NULL AND s.acsum = 0 ' : '')
	. ($odlaczeni && $disabled == 2 ? ' AND s.ownerid IS NOT NULL AND s.account = s.acsum' : '')
	. ($odlaczeni && $disabled == 3 ? ' AND s.ownerid IS NOT NULL AND s.account > s.acsum AND s.acsum != 0' : '')
	. ($warn && $warning == 1 ? ' AND s.ownerid IS NOT NULL AND s.warnsum = 0 ' : '')
	. ($warn && $warning == 2 ? ' AND s.ownerid IS NOT NULL AND s.warncount = s.warnsum' : '')
	. ($warn && $warning == 3 ? ' AND s.ownerid IS NOT NULL AND s.warncount > s.warnsum AND s.warnsum != 0' : '')
	. ($nodeblock && $blockade == 1 ? ' AND s.ownerid IS NOT NULL AND s.blockcount = s.blocksum ' : '')
	. ($nodeblock && $blockade == 2 ? ' AND s.ownerid IS NOT NULL AND s.blocksum = 0 ' : '')
	. ($nodeblock && $blockade == 3 ? ' AND s.ownerid IS NOT NULL AND s.blockcount > s.blocksum AND s.blocksum != 0 ' : '')
        . ($nodeblock && $blockade == 4 ? ' AND s.ownerid IS NOT NULL AND c.cutoffstop >= ?NOW? ' : '')
	. ($osobowosc && $osobowosc == 1 ? ' AND c.type=0 ' : '')
	. ($osobowosc && $osobowosc == 2 ? ' AND c.type=1 ' : '')
	. ($odlaczeni && $disabled == 4 ? ' AND s.ownerid IS NULL' : '')
	. ($network ? ' AND EXISTS (SELECT 1 FROM nodes WHERE ownerid = c.id AND 
				((ipaddr > ' . $net['address'] . ' AND ipaddr < ' . $net['broadcast'] . ') 
				OR (ipaddr_pub > ' . $net['address'] . ' AND ipaddr_pub < ' . $net['broadcast'] . ')))' : '')
	. ($customergroup ? ' AND customergroupid=' . intval($customergroup) : '')
	. ($nodegroup ? ' AND EXISTS (SELECT 1 FROM nodegroupassignments na
				JOIN nodes n ON (n.id = na.nodeid) 
				WHERE n.ownerid = c.id AND na.nodegroupid = ' . intval($nodegroup) . ')' : '')
	. ($groupless ? ' AND NOT EXISTS (SELECT 1 FROM customerassignments a 
				WHERE c.id = a.customerid)' : '')
	. ($tariffless ? ' AND NOT EXISTS (SELECT 1 FROM assignments a 
				WHERE a.customerid = c.id
					AND (datefrom <= ?NOW? OR datefrom = 0) 
					AND (dateto >= ?NOW? OR dateto = 0)
					AND (tariffid != 0 OR liabilityid != 0))' : '')
	. ($suspended ? ' AND EXISTS (SELECT 1 FROM assignments a
				WHERE a.customerid = c.id AND (
					(tariffid = 0 AND liabilityid = 0
					    AND (datefrom <= ?NOW? OR datefrom = 0)
					    AND (dateto >= ?NOW? OR dateto = 0)) 
					OR ((datefrom <= ?NOW? OR datefrom = 0)
					    AND (dateto >= ?NOW? OR dateto = 0)
					    AND suspended = 1)
					))' : '')
	. (isset($sqlsarg) ? ' AND (' . $sqlsarg . ')' : '')
	. (isset($sqlfl) ? ' AND ('.$sqlfl.') ' : '')
	. ($sqlord != '' ? $sqlord . ' ' . $direction : '')
	); // end preload
	    
	$_tmp = array();
	
	for ($i=0; $i<sizeof($preload); $i++) {
	    $_tmp[$i]['id'] = $preload[$i]['id'];
	    $_tmp[$i]['balance'] = $preload[$i]['balance'];
	}
	    $LMS->saveCache('customerlist',$md5,$_tmp);
	    
	} else {
	
	    $preload = $_cache;
	
	}
	
	$idlist = array();
	
	if ($preload) {
	    $pageend = $pagestart + get_conf('phpui.customerlist_pagelimit','50');
	    for ($pl=$pagestart; $pl<$pageend; $pl++)
	    {
		    if ($preload[$pl]['id']) 
			$idlist[] = $preload[$pl]['id'];
	    }
	} else {
	    $idlist[0] = '0';
	}
    
	$_idlist = implode(',',$idlist);

    $customerlist = $DB->GetAll(
	'SELECT c.id AS id, ' . $DB->Concat('UPPER(lastname)', "' '", 'c.name') . ' AS customername, 
	status, address, zip, city, countryid, countries.name AS country, email, ten, ssn, c.info AS info, 
	message, c.divisionid, c.paytime AS paytime, COALESCE(b.value, 0) AS balance,
	COALESCE(t.value, 0) AS tariffvalue, s.account, s.warncount, s.online, s.blockcount,
	c.type AS customertype, cutoffstop, 
	(SELECT max(cash.time) FROM cash WHERE cash.customerid = c.id) AS lastcash,
	(CASE WHEN s.account = s.acsum THEN 1 WHEN s.acsum > 0 THEN 2 ELSE 0 END) AS nodeac,
	(CASE WHEN s.warncount = s.warnsum THEN 1 WHEN s.warnsum > 0 THEN 2 ELSE 0 END) AS nodewarn,
	(CASE WHEN s.blockcount = s.blocksum THEN 1 WHEN s.blocksum > 0 THEN 2 ELSE 0 END) as nodeblock 
	FROM customersview c
	LEFT JOIN countries ON (c.countryid = countries.id) '
	. ($customergroup ? 'LEFT JOIN customerassignments ON (c.id = customerassignments.customerid) ' : '')
	.(in_array(get_conf('database.type'),array('mysql','mysqli')) ? ' JOIN customercash b ON (b.customerid = c.id) ' : 
	' LEFT JOIN (SELECT
		SUM(value) AS value, customerid
		FROM cash 
		GROUP BY customerid
	) b ON (b.customerid = c.id) ')
	.' LEFT JOIN (SELECT a.customerid,
		SUM((CASE a.suspended
		WHEN 0 THEN (((100 - a.pdiscount) * (CASE WHEN t.value IS NULL THEN l.value ELSE t.value END) / 100) - a.vdiscount)
		ELSE ((((100 - a.pdiscount) * (CASE WHEN t.value IS NULL THEN l.value ELSE t.value END) / 100) - a.vdiscount) * ' . $suspension_percentage . ' / 100) END)
		* (CASE t.period
			WHEN ' . MONTHLY . ' THEN 1
			WHEN ' . YEARLY . ' THEN 1/12.0
			WHEN ' . HALFYEARLY . ' THEN 1/6.0
			WHEN ' . QUARTERLY . ' THEN 1/3.0
			ELSE (CASE a.period
			    WHEN ' . MONTHLY . ' THEN 1
			    WHEN ' . YEARLY . ' THEN 1/12.0
			    WHEN ' . HALFYEARLY . ' THEN 1/6.0
			    WHEN ' . QUARTERLY . ' THEN 1/3.0
			    ELSE 0 END)
			END)
		) AS value 
		FROM assignments a
		LEFT JOIN tariffs t ON (t.id = a.tariffid)
		LEFT JOIN liabilities l ON (l.id = a.liabilityid AND a.period != ' . DISPOSABLE . ')
		WHERE (a.datefrom <= ?NOW? OR a.datefrom = 0) AND (a.dateto > ?NOW? OR a.dateto = 0) 
		GROUP BY a.customerid
	) t ON (t.customerid = c.id)
	LEFT JOIN (SELECT ownerid,
	SUM(access) AS acsum, COUNT(access) AS account,
		SUM(warning) AS warnsum, COUNT(warning) AS warncount, 
		SUM(blockade) AS blocksum, COUNT(blockade) AS blockcount,
		(CASE WHEN MAX(lastonline) > ?NOW? - ' . intval(get_conf('phpui.lastonline_limit')) . '
			THEN 1 ELSE 0 END) AS online
		FROM nodes
		WHERE ownerid > 0
		GROUP BY ownerid
	) s ON (s.ownerid = c.id)
	WHERE 1=1 '
	.' AND c.id in ('.$_idlist.') '
	. ($sqlord != '' ? $sqlord . ' ' . $direction : '')
	);
    

    if ($preload) {
		foreach ($preload as $idx => $row) {
			// summary
			if ($row['balance'] > 0)
				$over += $row['balance'];
			elseif ($row['balance'] < 0)
				$below += $row['balance'];
		}
	}

    $customerlist['total'] = sizeof($preload);
    $customerlist['state'] = $state;
    $customerlist['order'] = $order;
    $customerlist['direction'] = $direction;
    $customerlist['below'] = $below;
    $customerlist['over'] = $over;

    return $customerlist;
}


$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('Customers List');

// first letter
if(!isset($_GET['fletter']))
	$SESSION->restore('clfl', $fletter);
else
	$fletter = $_GET['fletter'];
$SESSION->save('clfl', $fletter);

if(!isset($_GET['o']))
	$SESSION->restore('clo', $o);
else
	$o = $_GET['o'];
$SESSION->save('clo', $o);

if(!isset($_GET['or']))
	$SESSION->restore('clor', $or);
else
	$or = $_GET['or'];
$SESSION->save('clor', $or);

if(!isset($_GET['s']))
	$SESSION->restore('cls', $s);
else
	$s = $_GET['s'];
if (empty($s)) $s=3;
$SESSION->save('cls', $s);

if(!isset($_GET['st']))
	$SESSION->restore('clst', $st);
else
	$st = $_GET['st'];
$SESSION->save('clst', $st);

if(!isset($_GET['n']))
	$SESSION->restore('cln', $n);
else
	$n = $_GET['n'];
$SESSION->save('cln', $n);

if(!isset($_GET['g']))
	$SESSION->restore('clg', $g);
else
	$g = $_GET['g'];	
$SESSION->save('clg', $g);

if(!isset($_GET['ng']))
        $SESSION->restore('clng', $ng);
else
        $ng = $_GET['ng'];
$SESSION->save('clng', $ng);

if(!isset($_GET['ce']))
        $SESSION->restore('clce', $ce);
else
        $ce = $_GET['ce'];
$SESSION->save('clce', $ce);

if(!isset($_GET['d']))
        $SESSION->restore('cld', $d);
else
        $d = $_GET['d'];
$SESSION->save('cld', $d);

if(!isset($_GET['odl']))
        $SESSION->restore('clodl', $odl);
else
        $odl = $_GET['odl'];
$SESSION->save('clodl', $odl);

if(!isset($_GET['warn']))
        $SESSION->restore('clwarn', $warn);
else
        $warn = $_GET['warn'];
$SESSION->save('clwarn', $warn);

if (!isset($_GET['osp']))
    $SESSION->restore('closp',$osp);
else
    $osp = $_GET['osp'];
$SESSION->save('closp',$osp);

if (!isset($_GET['block']))
    $SESSION->restore('clblock',$block);
else
    $block = $_GET['block'];
$SESSION->save('clblock',$block);

		
if (! isset($_GET['page']))
	$SESSION->restore('clp', $_GET['page']);

if (!empty($ce)) {
    $idlist = $LMS->GetIdContractEnding($ce);
    if (!empty($idlist))
	$cetmp = implode(',',$idlist);
    else
	$cetmp = -1;
}
    else $cetmp = NULL;
    
$page = (! $_GET['page'] ? 1 : $_GET['page']); 
$pagelimit = get_conf('phpui.customerlist_pagelimit','50');
$start = ($page - 1) * $pagelimit;


$customerlist = GetCustomerList($o, $s, $n, $g, NULL, NULL, 'AND', $ng, $d, $fletter, $st, $cetmp, $odl, $warn, $or, $osp, $block, $start);

$listdata['total'] = $customerlist['total'];
$listdata['order'] = $customerlist['order'];
$listdata['below'] = $customerlist['below'];
$listdata['over'] = $customerlist['over'];
$listdata['direction'] = $customerlist['direction'];
$listdata['network'] = $n;
$listdata['nodegroup'] = $ng;
$listdata['customergroup'] = $g;
$listdata['customerorigin'] = $or;
$listdata['division'] = $d;
$listdata['state'] = $s;
$listdata['status'] = $st;
$listdata['fletter'] = $fletter;
$listdata['contractend'] = $ce;
$listdata['odlaczeni'] = $odl;
$listdata['warning'] = $warn;
$listdata['osp'] = $osp;
$listdata['block'] = $block;


$SESSION->save('clp', $page);

unset($customerlist['total']);
unset($customerlist['state']);
unset($customerlist['order']);
unset($customerlist['below']);
unset($customerlist['over']);
unset($customerlist['direction']);
/*
function setCustomerAccess($idc,$status)
{
    global $DB;
    $obj = new xajaxResponse();
    
    return $obj;
}

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('setcustomeraccess'));
$SMARTY->assign('xajax', $LMS->RunXajax());
*/

$SMARTY->assign('customerlist',$customerlist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('networks', $LMS->GetNetworks());
$SMARTY->assign('customergroups', $LMS->CustomergroupGetAll());
$SMARTY->assign('nodegroups', $LMS->GetNodeGroupNames());
$SMARTY->assign('divisions', $DB->GetAll('SELECT id, shortname FROM divisions ORDER BY shortname'));
$SMARTY->assign('originlist',$DB->GetAll('SELECT id, name FROM customerorigin ORDER BY name'));
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);

$SMARTY->display('customerlist.html');

?>
