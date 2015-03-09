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
 *  $Id: v 1.00 2013/04/11 20:01:35 Sylwester Kondracki Exp $
 */

$layout['pagetitle'] = 'Historia operacji importów płatności masowych';

$tucklist = array(
	array('tuck' => 'settled',     'name' => 'Wpłaty zaksięgowane', 'link' => '?m=cashimportlist&tuck=settled', 'tip' => 'Lista zaksięgowanych wpłat, które zostały prawidłowo przypisane do klienta'),
	array('tuck' => 'notsettled',  'name' => 'Wpłaty niezaksięgowane', 'link' => '?m=cashimportlist&tuck=notsettled', 'tip' => 'Lista wpłat które niezostały zaksięgowane a są prawidłowo skojarzone z klientem '),
	array('tuck' => 'unidentified','name' => 'Wpłaty niezidentyfikowane', 'link' => '?m=cashimportlist&tuck=unidentified', 'tip' => 'Lista wpłat które wpłyneły na nasze konto, gdzie nadawca nie został prawidłowo rozpoznany'),
	array('tuck' => 'duplicate',   'name' => 'Wpłaty zdublowane', 'link' => '?m=cashimportlist&tuck=duplicate', 'tip' => 'Lista rozliczonych wpłat, które mogą być zdublowane'),
	array('tuck' => 'base',        'name' => 'Pliki importu', 'link' => '?m=cashimportlist&tuck=base', 'tip' => 'Lista zaimportowanych plików z płatnościami'),
);


$SMARTY->assign('tucklist',$tucklist);

$tuck = (isset($_GET['tuck']) ? $_GET['tuck'] : NULL);
$PROFILE->nowsave('cashimportlist_tuck',$tuck);

if ($tuck == 'base') {
    $layout['popup'] = true;
    
    if(!isset($_GET['o']))
	$SESSION->restore('cil_base_o', $o);
    else
	$o = $_GET['o'];
    $SESSION->save('cil_base_o', $o);
    
    if (isset($_GET['dfrom']))
	$dfrom = $_GET['dfrom'];
    else
	$SESSION->restore('cil_base_dfrom',$dfrom);
	
    if (!isset($dfrom)) $dfrom = date("Y/m", time())."/01";

    $SESSION->save('cil_base_dfrom',$dfrom);

    if (!isset($_GET['dto']))
	$SESSION->restore('cil_base_dto',$dto);
    else
	$dto = $_GET['dto'];
    $SESSION->save('cil_base_dto',$dto);

    $listdata['dfrom'] = $dfrom;
    $listdata['dto'] = $dto;
    
    if (!isset($_GET['page'])) $SESSION->restore('cil_base_page',$_GET['page']);
    
    $listdata['ajax'] = true;
    
    // te dwie zmienne uzupełniamy jak ma być inny div lub link do danych
    // DEF: ajax_id = 'id_data'; ajax_link = $layout.module;
    // $listdata['ajax_id'] = 'id_data';
    // $listdata['ajax_link'] = 'cashimportlist';

    if ($o == '') $o = 'idate,asc';
    list($order, $direction) = sscanf($o, '%[^,],%s');
    ($direction == 'desc') ? $direction = 'desc' : $direction = 'asc';
    
    $listdata['order'] = $order;
    $listdata['direction'] = $direction;
    
    $sql = 'SELECT f.id, (SELECT u.name FROM users u WHERE u.id = f.userid) AS user, f.name, f.idate FROM sourcefiles f WHERE 1=1 '
			    .($dfrom!='' ? ' AND f.idate > '.strtotime($dfrom.' 00:00:00') : '')
			    .($dto!='' ? ' AND f.idate < '.strtotime($dto.' 23:59:59') : '')
			    .' ORDER BY '.$order.' '.$direction.' '
			    .' ;';

    $filelist = $DB->GetAll($sql);
    $listdata['total'] = sizeof($filelist);
    
    $page = (!$_GET['page'] ? 1 : $_GET['page']);
    $pagelimit = get_conf('phpui.balancelist_pagelimit',50);
    $start = ($page -1) * $pagelimit;
    
    $SESSION->save('cil_base_page',$page);
    $SESSION->_saveSession();
    
    $SMARTY->assign('adlink','&tuck=base');
    $SMARTY->assign('start',$start);
    $SMARTY->assign('page',$page);
    $SMARTY->assign('pagelimit',$pagelimit);
    $SMARTY->assign('listdata',$listdata);
    $SMARTY->assign('filtr',$filtr);
    $SMARTY->assign('filelist',$filelist);
    $SMARTY->assign('tuck',$tuck);
    $SMARTY->display('cashimportlist_box.html');
    die;

} 

elseif ($tuck == 'settled' || $tuck == 'notsettled') {
    
    $layout['popup'] = true;
    
    if ($tuck == 'notsettled' && isset($_GET['ksieguj']) && isset($_GET['idw']) && isset($_GET['idc']) && intval($_GET['idw']) && intval($_GET['idc'])) {
	$idw = intval($_GET['idw']);
	$idc = intval($_GET['idc']);
	
	if ($wplata = $DB->GetRow('SELECT * FROM cashimport WHERE id = ? LIMIT 1;',array($idw)))
	{
		$balance = array(
		    'time' 		=> $wplata['date'],
		    'userid' 		=> $AUTH->id,
		    'value'		=> $wplata['value'],
		    'type'		=> 1,
		    'taxid'		=> 0,
		    'customerid'	=> $idc,
		    'comment'		=> $wplata['description'],
		    'docid'		=> 0,
		    'itemid'		=> 0,
		    'importid'		=> $idw,
		    'sourceid'		=> $wplata['sourceid']
		);
		if ($LMS->addBalance($balance))
		    $DB->Execute('UPDATE cashimport SET customerid = ?, closed = ?  WHERE id = ? ;',array($idc,1,$idw));
	}
    }
    
    if (!isset($_GET['o'])) 
	$SESSION->restore('cil_'.$tuck.'_o', $o); 
    else 
	$o = $_GET['o'];
    $SESSION->save('cil_'.$tuck.'_o', $o);
    
    if (isset($_GET['cid'])) 
	$cid = $_GET['cid']; 
    else 
	$SESSION->restore('cil_'.$tuck.'_cid',$cid);
    $SESSION->save('cil_'.$tuck.'_cid',$cid);
    
    if (isset($_GET['sf'])) 
	$sf = $_GET['sf']; 
    else 
	$SESSION->restore('cil_'.$tuck.'_sf',$sf);
    $SESSION->save('cil_'.$tuck.'_sf',$sf);
    
    if (isset($_GET['dfrom'])) 
	$dfrom = $_GET['dfrom']; 
    else 
	$SESSION->restore('cil_'.$tuck.'_dfrom',$dfrom); 
    if (!isset($dfrom)) $dfrom = date("Y/m", time())."/01"; 
    $SESSION->save('cil_'.$tuck.'_dfrom',$dfrom);
    
    if (!isset($_GET['dto'])) 
	$SESSION->restore('cil_'.$tuck.'_dto',$dto); 
    else 
	$dto = $_GET['dto']; 
    $SESSION->save('cil_'.$tuck.'_dto',$dto);
    
    if (!isset($_GET['division']))
	$SESSION->restore('cil_'.$tuck.'_division',$division);
    else
	$division = $_GET['division'];
    $SESSION->save('cli_'.$tuck.'_division',$division);
    
    if (!isset($_GET['srcfile']))
	$SESSION->restore('cil_'.$tuck.'_srcfile',$srcfile);
    else
	$srcfile = $_GET['srcfile'];
    $SESSION->save('cli_'.$tuck.'_srcfile',$srcfile);
    

    $listdata['dfrom'] = $dfrom;
    $listdata['dto'] = $dto;
    $listdata['cid'] = $cid;
    $listdata['sc'] = (!empty($cid) ? $LMS->GetCustomerName(intval($cid)) : '');
    $listdata['sf'] = $sf;
    $listdata['division'] = $division;
    $listdata['srcfile'] = $srcfile;

    if (!isset($_GET['page'])) $SESSION->restore('cil_'.$tuck.'_page',$_GET['page']);
    
    $listdata['ajax'] = true;
    
    if ($o == '') $o = 'date,asc';
    list($order, $direction) = sscanf($o, '%[^,],%s');
    ($direction == 'desc') ? $direction = 'desc' : $direction = 'asc';
    
    $listdata['order'] = $order;
    $listdata['direction'] = $direction;
    
    $sql_search = NULL;

    if ($sf != '')
    {
	$sql_search = " AND (".(intval($sf) ? "l.id = ".intval($sf)." OR" : "")
	    .(" l.value = ".($DB->Escape(str_replace(",",".",$sf))))
	    .(" OR UPPER(l.customer) ?LIKE? UPPER(".($DB->Escape('%'.str_replace('+',' ',$sf).'%')).")")
	    .(" OR UPPER(l.description) ?LIKE? UPPER(".($DB->Escape('%'.str_replace('+',' ',$sf).'%')).")")
	    .") ";
    }
    
    if ($tuck == 'settled')
    {
	$sql = 'SELECT l.id, l.date, l.value, l.customer, l.description, l.customerid, l.closed, l.sourcefileid, '
	    .($DB->concat('UPPER(c.lastname)',"' '",'c.name')).' AS customers, '
	    .'c.type, c.status, c.deleted, c.message, s.account, s.warncount, s.blockadecount,
	    (CASE WHEN s.account = s.acsum THEN 1 WHEN s.acsum > 0 THEN 2 ELSE 0 END) AS nodeac,
	    (CASE WHEN s.warncount = s.warnsum THEN 1 WHEN s.warnsum > 0 THEN 2 ELSE 0 END) AS nodewarn,
	    (CASE WHEN s.blockadecount = s.blockadesum THEN 1 WHEN s.blockadesum > 0 THEN 2 ELSE 0 END) AS nodeblockade, 
	    COALESCE(b.value, 0) AS balance, 
	    COALESCE((SELECT SUM(cv.value) FROM cash cv WHERE cv.customerid = c.id AND cv.time <= l.date), 0) AS bbalance '
	    .' FROM cashimport l 
	    LEFT JOIN customersview c ON (c.id = l.customerid) 
	    LEFT JOIN (SELECT ownerid,
		SUM(access) AS acsum, COUNT(access) AS account,
		SUM(warning) AS warnsum, COUNT(warning) AS warncount,
		SUM(blockade) AS blockadesum, COUNT(blockade) AS blockadecount 
		FROM nodes
		WHERE ownerid > 0
		GROUP BY ownerid
	    ) s ON (s.ownerid = c.id) '
	    .(in_array($CONFIG['database']['type'],array('mysql','mysqli')) && !$time ? ' LEFT JOIN customercash b ON (b.customerid = c.id) ' : 
				 'LEFT JOIN (SELECT
					SUM(value) AS value, customerid
					FROM cash 
					GROUP BY customerid
				) b ON (b.customerid = c.id) ')
	    .'WHERE l.customerid != 0 AND (l.customerid IS NOT NULL) AND closed = 1 '
	    .($dfrom!='' ? ' AND l.date > '.(strtotime($dfrom)-1) : '')
	    .($dto!='' ? ' AND l.date < '.strtotime($dto.' 23:59:59') : '')
	    .($cid ? ' AND l.customerid = '.$cid.' ' : '')
	    .($division ? ' AND c.divisionid = \''.$division.'\'' : '')
	    .($srcfile ? ' AND l.sourcefileid = \''.$srcfile.'\'' : '')
	    .($sql_search ? $sql_search : '')
	    .' ORDER BY '.$order.' '.$direction.' '
	    .' ;';
    }
    elseif ($tuck == 'notsettled')
    {
	$sql = 'SELECT l.id, l.date, l.value, l.customer, l.description, l.customerid, l.closed, l.sourcefileid, '
	    .($DB->concat('UPPER(c.lastname)',"' '",'c.name')).' AS customers, '
	    .'c.type, c.status, c.deleted, c.message, s.account, s.warncount, s.blockadecount,
	    (CASE WHEN s.account = s.acsum THEN 1 WHEN s.acsum > 0 THEN 2 ELSE 0 END) AS nodeac,
	    (CASE WHEN s.warncount = s.warnsum THEN 1 WHEN s.warnsum > 0 THEN 2 ELSE 0 END) AS nodewarn,
	    (CASE WHEN s.blockadecount = s.blockadesum THEN 1 WHEN s.blockadesum > 0 THEN 2 ELSE 0 END) AS nodeblockade, 
	    COALESCE(b.value, 0) AS balance, 
	    COALESCE((SELECT SUM(cv.value) FROM cash cv WHERE cv.customerid = c.id AND cv.time <= l.date), 0) AS bbalance '
	    .' FROM cashimport l 
	    LEFT JOIN customersview c ON (c.id = l.customerid) 
	    LEFT JOIN (SELECT ownerid,
		SUM(access) AS acsum, COUNT(access) AS account,
		SUM(warning) AS warnsum, COUNT(warning) AS warncount,
		SUM(blockade) AS blockadesum, COUNT(blockade) AS blockadecount 
		FROM nodes
		WHERE ownerid > 0
		GROUP BY ownerid
	    ) s ON (s.ownerid = c.id) '
	    .(in_array($CONFIG['database']['type'],array('mysql','mysqli')) && !$time ? ' LEFT JOIN customercash b ON (b.customerid = c.id) ' : 
				 'LEFT JOIN (SELECT
					SUM(value) AS value, customerid
					FROM cash 
					GROUP BY customerid
				) b ON (b.customerid = c.id) ')
	    .'WHERE l.customerid != 0 AND (l.customerid IS NOT NULL) AND closed = 0'
	    .($dfrom!='' ? ' AND l.date > '.(strtotime($dfrom)-1) : '')
	    .($dto!='' ? ' AND l.date < '.strtotime($dto.' 23:59:59') : '')
	    .($cid ? ' AND l.customerid = '.$cid.' ' : '')
	    .($division ? ' AND c.divisionid = \''.$division.'\'' : '')
	    .($srcfile ? ' AND l.sourcefileid = \''.$srcfile.'\'' : '')
	    .($sql_search ? $sql_search : '')
	    .' ORDER BY '.$order.' '.$direction.' '
	    .' ;';
    }

    $lista = $DB->GetAll($sql);

    $listdata['total'] = sizeof($lista);
    $sum = 0;
    if ($lista) for ($i=0;$i<$listdata['total'];$i++) $sum += $lista[$i]['value'];
    
    $listdata['sumvalue'] = $sum;
    
    $page = (!$_GET['page'] ? 1 : $_GET['page']);
    $pagelimit = get_conf('phpui.balancelist_pagelimit',50);
    $start = ($page -1) * $pagelimit;
    
    $SESSION->save('cil_'.$tuck.'_page',$page);
    $SESSION->save('backto', 'm=cashimportlist');
    $SESSION->_saveSession();
    
    $SMARTY->assign('adlink','&tuck='.$tuck);
    $SMARTY->assign('start',$start);
    $SMARTY->assign('page',$page);
    $SMARTY->assign('pagelimit',$pagelimit);
    $SMARTY->assign('listdata',$listdata);
    $SMARTY->assign('filtr',$filtr);
    $SMARTY->assign('lista',$lista);
    $SMARTY->assign('tuck',$tuck);
    $SMARTY->assign('srcfile',$DB->getAll('SELECT id,name FROM sourcefiles;'));
    $SMARTY->assign('divisions',$DB->getAll('SELECT id,shortname FROM divisions;'));
    $SMARTY->display('cashimportlist_box.html');
    die;
} 

elseif ($tuck == 'unidentified') {
    
    $layout['popup'] = true;
    
    if ($tuck == 'unidentified' && isset($_GET['ksieguj']) && isset($_GET['idw']) && isset($_GET['idc']) && intval($_GET['idw']) && intval($_GET['idc'])) {
	$idw = intval($_GET['idw']);
	$idc = intval($_GET['idc']);
	
	if ($wplata = $DB->GetRow('SELECT * FROM cashimport WHERE id = ? LIMIT 1;',array($idw)))
	{
		$balance = array(
		    'time' 		=> $wplata['date'],
		    'userid' 		=> $AUTH->id,
		    'value'		=> $wplata['value'],
		    'type'		=> 1,
		    'taxid'		=> 0,
		    'customerid'	=> $idc,
		    'comment'		=> $wplata['description'],
		    'docid'		=> 0,
		    'itemid'		=> 0,
		    'importid'		=> $idw,
		    'sourceid'		=> $wplata['sourceid']
		);
		if ($LMS->addBalance($balance))
		    $DB->Execute('UPDATE cashimport SET customerid = ?, closed = ? WHERE id = ? ;',array($idc,1,$idw));
	}
    }
    
    if (!isset($_GET['o'])) $SESSION->restore('cil_'.$tuck.'_o', $o); else $o = $_GET['o']; 		$SESSION->save('cil_'.$tuck.'_o', $o);
    if (isset($_GET['cid'])) $cid = $_GET['cid']; else $SESSION->restore('cil_'.$tuck.'_cid',$cid); 	$SESSION->save('cil_'.$tuck.'_cid',$cid);
    if (isset($_GET['sf'])) $sf = $_GET['sf']; else $SESSION->restore('cil_'.$tuck.'_sf',$sf); 		$SESSION->save('cil_'.$tuck.'_sf',$sf);
    if (isset($_GET['dfrom'])) $dfrom = $_GET['dfrom']; else $SESSION->restore('cil_'.$tuck.'_dfrom',$dfrom); if (!isset($dfrom)) $dfrom = date("Y/m", time())."/01"; $SESSION->save('cil_'.$tuck.'_dfrom',$dfrom);
    if (!isset($_GET['dto'])) $SESSION->restore('cil_'.$tuck.'_dto',$dto); else $dto = $_GET['dto']; $SESSION->save('cil_'.$tuck.'_dto',$dto);

    $listdata['dfrom'] = $dfrom;
    $listdata['dto'] = $dto;
    $listdata['cid'] = $cid;
    $listdata['sc'] = (!empty($cid) ? $LMS->GetCustomerName($cid) : '');
    $listdata['sf'] = $sf;

    if (!isset($_GET['page'])) $SESSION->restore('cil_'.$tuck.'_page',$_GET['page']);
    
    $listdata['ajax'] = true;
    
    if ($o == '') $o = 'date,asc';
    list($order, $direction) = sscanf($o, '%[^,],%s');
    ($direction == 'desc') ? $direction = 'desc' : $direction = 'asc';
    
    $listdata['order'] = $order;
    $listdata['direction'] = $direction;
    
    $sql_search = NULL;

    if ($sf != '')
    {
	$sql_search = " AND (".(intval($sf) ? "l.id = ".intval($sf)." OR" : "")
	    .(" l.value = ".($DB->Escape(str_replace(",",".",$sf))))
	    .(" OR UPPER(l.customer) ?LIKE? UPPER(".($DB->Escape('%'.str_replace('+',' ',$sf).'%')).")")
	    .(" OR UPPER(l.description) ?LIKE? UPPER(".($DB->Escape('%'.str_replace('+',' ',$sf).'%')).")")
	    .") ";
    }

	    $sql = 'SELECT l.id, l.date, l.value, l.customer, l.description, l.customerid, l.closed, l.sourcefileid,
	    (SELECT '.$DB->concat('UPPER(c.lastname)',"' '",'c.name').' FROM customersview c WHERE c.id = l.customerid) AS customers
	     FROM cashimport l 
	    WHERE (l.customerid IS NULL) '
			    .($dfrom!='' ? ' AND l.date > '.(strtotime($dfrom)-1) : '')
			    .($dto!='' ? ' AND l.date < '.strtotime($dto.' 23:59:59') : '')
			    .($sql_search ? $sql_search : '')
			    .' ORDER BY '.$order.' '.$direction.' '
			    .' ;';
//    echo $sql;
    $lista = $DB->GetAll($sql);

    $listdata['total'] = sizeof($lista);
    $sum = 0;
    if ($lista) for ($i=0;$i<$listdata['total'];$i++) $sum += $lista[$i]['value'];
    
    $listdata['sumvalue'] = $sum;
    
    $page = (!$_GET['page'] ? 1 : $_GET['page']);
    $pagelimit = get_conf('phpui.balancelist_pagelimit',50);
    $start = ($page -1) * $pagelimit;
    
    $SESSION->save('cil_'.$tuck.'_page',$page);
    $SESSION->save('backto', 'm=cashimportlist');
    $SESSION->_saveSession();
    
    $SMARTY->assign('adlink','&tuck='.$tuck);
    $SMARTY->assign('start',$start);
    $SMARTY->assign('page',$page);
    $SMARTY->assign('pagelimit',$pagelimit);
    $SMARTY->assign('listdata',$listdata);
    $SMARTY->assign('filtr',$filtr);
    $SMARTY->assign('lista',$lista);
    $SMARTY->assign('tuck',$tuck);
    $SMARTY->display('cashimportlist_box.html');
    die;
} 

elseif ($tuck == 'duplicate') {
    
    if (isset($_GET['odksieguj']) && isset($_GET['idw']) && isset($_GET['idc']) && intval($_GET['idw']) && intval($_GET['idc'])) {
	if (SYSLOG) {
	    $info = $DB->GetRow('SELECT * FROM cash WHERE id = ? LIMIT 1;',array(intval($_GET['idw'])));
	    addlogs('usunięto zduplikowaną wpłatę z importu na kwotę :'.moneyf($info['value']).' z dnia: '.date('Y/m/d',$info['time']).' dla '.$LMS->GetCustomerName($info['customerid']),'e=rm;m=fin;c='.$info['customerid']);
	}
	$DB->Execute('DELETE FROM cash WHERE id = ? AND customerid = ? ;',array(intval($_GET['idw']),intval($_GET['idc'])));
    }
    
    if (!isset($_GET['o'])) $SESSION->restore('cil_'.$tuck.'_o', $o); else $o = $_GET['o']; 		$SESSION->save('cil_'.$tuck.'_o', $o);
    if (isset($_GET['cid'])) $cid = $_GET['cid']; else $SESSION->restore('cil_'.$tuck.'_cid',$cid); 	$SESSION->save('cil_'.$tuck.'_cid',$cid);
    if (isset($_GET['sf'])) $sf = $_GET['sf']; else $SESSION->restore('cil_'.$tuck.'_sf',$sf); 		$SESSION->save('cil_'.$tuck.'_sf',$sf);
    if (isset($_GET['dfrom'])) $dfrom = $_GET['dfrom']; else $SESSION->restore('cil_'.$tuck.'_dfrom',$dfrom); if (!isset($dfrom)) $dfrom = date("Y/m", time())."/01"; $SESSION->save('cil_'.$tuck.'_dfrom',$dfrom);
    if (!isset($_GET['dto'])) $SESSION->restore('cil_'.$tuck.'_dto',$dto); else $dto = $_GET['dto']; $SESSION->save('cil_'.$tuck.'_dto',$dto);

    $listdata['dfrom'] = $dfrom;
    $listdata['dto'] = $dto;
    $listdata['cid'] = $cid;
    $listdata['sc'] = (!empty($cid) ? $LMS->GetCustomerName($cid) : '');
    $listdata['sf'] = $sf;

    if (!isset($_GET['page'])) $SESSION->restore('cil_'.$tuck.'_page',$_GET['page']);
    
    $listdata['ajax'] = true;
    
    if ($o == '') $o = 'time,asc';
    list($order, $direction) = sscanf($o, '%[^,],%s');
    ($direction == 'desc') ? $direction = 'desc' : $direction = 'asc';
    
    $listdata['order'] = $order;
    $listdata['direction'] = $direction;
    
    $sql_search = NULL;
    if ($sf != '')
    {
	$sql_search = " AND (".(intval($sf) ? "d.id = ".intval($sf)." OR" : "")
	    .(" d.value = ".($DB->Escape(str_replace(",",".",$sf))))
	    .(" OR UPPER(i.customer) ?LIKE? UPPER(".($DB->Escape('%'.str_replace('+',' ',$sf).'%')).")")
	    .(" OR UPPER(i.description) ?LIKE? UPPER(".($DB->Escape('%'.str_replace('+',' ',$sf).'%')).")")
	    .(" OR UPPER(d.comment) ?LIKE? UPPER(".($DB->Escape('%'.str_replace('+',' ',$sf).'%')).")")
	    .") ";
    }

	$did = $DB->GetCol('SELECT c.importid 
			    FROM cash c WHERE 1=1'
//			    .' AND c.comment = (SELECT ci.description FROM cashimport ci WHERE ci.id = c.importid)'
			    .' AND importid IS NOT NULL 
			    GROUP BY importid 
			    HAVING count( id ) > 1');

	if ($did) $dubleid = implode(',',$did); else $dubleid = '0';

	$sql = 'SELECT d.id, d.time, d.value, d.customerid, d.comment, d.importid, i.customer, i.description, 
	    (SELECT '.$DB->concat('UPPER(c.lastname)',"' '",'c.name').' FROM customersview c WHERE c.id = d.customerid LIMIT 1) AS customers ,
	    (SELECT MIN(cc.id) FROM cash cc WHERE cc.customerid = d.customerid AND cc.importid = d.importid LIMIT 1) AS minid 
	    FROM cash d
	    JOIN cashimport i ON (i.id = d.importid) 
	     WHERE d.importid IN ('.$dubleid.') '
	    .($dfrom!='' ? ' AND d.time > '.(strtotime($dfrom)-1) : '')
	    .($dto!='' ? ' AND d.time < '.strtotime($dto.' 23:59:59') : '')
	    .($cid ? ' AND d.customerid = '.$cid.' ' : '')
	    .($sql_search ? $sql_search : '')
	    .' ORDER BY '.$order.' '.$direction.', importid ASC '
	    .';';
	    
	    
    $lista = $DB->GetAll($sql);
    $listdata['total'] = sizeof($lista);
    $sum = 0;
    if ($lista) for ($i=0;$i<$listdata['total'];$i++) $sum += $lista[$i]['value'];
    
    $listdata['sumvalue'] = $sum;
    
    $page = (!$_GET['page'] ? 1 : $_GET['page']);
    $pagelimit = get_conf('phpui.balancelist_pagelimit',50);
    $start = ($page -1) * $pagelimit;
    
    $SESSION->save('cil_'.$tuck.'_page',$page);
    $SESSION->_saveSession();
    
    $SMARTY->assign('adlink','&tuck='.$tuck);
    $SMARTY->assign('start',$start);
    $SMARTY->assign('page',$page);
    $SMARTY->assign('pagelimit',$pagelimit);
    $SMARTY->assign('listdata',$listdata);
    $SMARTY->assign('filtr',$filtr);
    $SMARTY->assign('lista',$lista);
    $layout['popup'] = true;
    $SMARTY->assign('tuck',$tuck);
    $SMARTY->display('cashimportlist_box.html');
    die;

}

$tuck = (isset($_GET['tuck']) ? $_GET['tuck'] : $PROFILE->get('cashimportlist_tuck','base'));

$tuckcount = sizeof($tucklist);

// sprawdzamy czy dana zakładka istnieje, jeżeli nie to ustawiamy na base
$err = true;;
for ($i=0; $i<$tuckcount; $i++) {
    if ($tucklist[$i]['tuck'] == $tuck) { $err = false; break; }
}
if ($err) $tuck = 'settled';

for ($i=0;$i<=sizeof($tucklist);$i++)
    if ($tucklist[$i]['tuck'] == $tuck) $tucklink = $tucklist[$i]['link'];

$PROFILE->nowsave('cashimportlist_tuck',$tuck);
$SMARTY->assign('tuck',$tuck);
$SMARTY->assign('tucklink',$tucklink);
$SMARTY->display('cashimportlist.html');
?>