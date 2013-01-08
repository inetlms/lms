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

if(!isset($_GET['s']))
	$SESSION->restore('cls', $s);
else
	$s = $_GET['s'];
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
    
	    
$customerlist = $LMS->GetCustomerList($o, $s, $n, $g, NULL, NULL, 'AND', $ng, $d, $fletter, $st, $cetmp);

$listdata['total'] = $customerlist['total'];
$listdata['order'] = $customerlist['order'];
$listdata['below'] = $customerlist['below'];
$listdata['over'] = $customerlist['over'];
$listdata['direction'] = $customerlist['direction'];
$listdata['network'] = $n;
$listdata['nodegroup'] = $ng;
$listdata['customergroup'] = $g;
$listdata['division'] = $d;
$listdata['state'] = $s;
$listdata['status'] = $st;
$listdata['fletter'] = $fletter;
$listdata['contractend'] = $ce;

$page = (! $_GET['page'] ? 1 : $_GET['page']); 
$pagelimit = (!$CONFIG['phpui']['customerlist_pagelimit'] ? $listdata['total'] : $CONFIG['phpui']['customerlist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('clp', $page);

unset($customerlist['total']);
unset($customerlist['state']);
unset($customerlist['order']);
unset($customerlist['below']);
unset($customerlist['over']);
unset($customerlist['direction']);

$SMARTY->assign('customerlist',$customerlist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('networks', $LMS->GetNetworks());
$SMARTY->assign('customergroups', $LMS->CustomergroupGetAll());
$SMARTY->assign('nodegroups', $LMS->GetNodeGroupNames());
$SMARTY->assign('divisions', $DB->GetAll('SELECT id, shortname FROM divisions ORDER BY shortname'));
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);

$SMARTY->display('customerlist.html');

?>
