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
 *  $Id: contractorlist.php,v 1.00 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('Contractors List');

if(!isset($_GET['fletter'])) $SESSION->restore('conlfl', $fletter); else $fletter = $_GET['fletter']; $SESSION->save('conlfl', $fletter);
if(!isset($_GET['g'])) $SESSION->restore('conlg', $g); else $g = $_GET['g']; $SESSION->save('conlg', $g); 
if(!isset($_GET['o'])) $SESSION->restore('conlo', $o); else $o = $_GET['o']; $SESSION->save('conlo', $o);
if(!isset($_GET['s'])) $SESSION->restore('conls', $s); else $s = $_GET['s']; $SESSION->save('conls', $s);
if (! isset($_GET['page'])) $SESSION->restore('conlp', $_GET['page']);


$customerlist = $LMS->GetContractorList(
    $o, 		// sortowanie
    $s,			// status
    $g, 		// grupa
    $fletter		// pierwsza litera
);

$listdata['total'] = $customerlist['total'];
$listdata['order'] = $customerlist['order'];
$listdata['direction'] = $customerlist['direction'];
$listdata['customergroup'] = $g;
$listdata['state'] = $s;
$listdata['fletter'] = $fletter;

$page = (! $_GET['page'] ? 1 : $_GET['page']); 
$pagelimit = (!$CONFIG['phpui']['customerlist_pagelimit'] ? $listdata['total'] : $CONFIG['phpui']['customerlist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('clp', $page);

unset($customerlist['total']);
unset($customerlist['state']);
unset($customerlist['order']);
unset($customerlist['direction']);

$SMARTY->assign('customerlist',$customerlist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('customergroups', $LMS->ContractorGroupShortList());
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);

$SMARTY->display('contractorlist.html');

?>