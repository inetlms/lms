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
    global $DB;
    $obj = new xajaxResponse();
    
    $tmp = $DB->GetOne('SELECT access FROM nodes WHERE id = ? LIMIT 1 ;',array($idek));
    $tmp = intval($tmp);
    if ($tmp === 1) $tmp = 0; else $tmp = 1;
    
    $DB->Execute('UPDATE nodes SET access = ? WHERE id = ? ;',array($tmp,$idek));

    if (SYSLOG) {
	addlogs(($tmp ? 'włączono' : 'wyłączono').' dostęp dla komputera','e=acl;m=node;n='.$idek);
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
    global $DB;
    $obj = new xajaxResponse();
    $tmp = $DB->GetOne('SELECT warning FROM nodes WHERE id = ? LIMIT 1 ;',array($idek));
    $tmp = intval($tmp);
    if ($tmp === 1) $tmp = 0 ; else $tmp = 1;
    $DB->Execute('UPDATE nodes SET warning = ? WHERE id = ? ;',array($tmp,$idek));
    if (SYSLOG) {
	addlogs(($tmp ? 'włączono' : 'wyłączono').' wiadomość dla komputera','e=warn;m=node;n='.$idek);
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
    global $DB;
    $obj = new xajaxResponse();
    $tmp = $DB->GetOne('SELECT blockade FROM nodes WHERE id = ? LIMIT 1 ;',array($idek));
    $tmp = intval($tmp);
    if ($tmp == 1) $tmp = 0 ; else $tmp = 1;
    $DB->Execute('UPDATE nodes SET blockade = ? WHERE id = ? ;',array($tmp,$idek));
    if (SYSLOG) {
	addlogs(($tmp ? 'włączono' : 'wyłączono').' blokadę dla komputera','e=warn;m=node;n='.$idek);
    }
    
    if ($tmp == 0) {
      $obj->script("document.getElementById('src_blockade".$idek."').src='img/padlockoff.png';");
    } else {
      $obj->script("document.getElementById('src_blockade".$idek."').src='img/padlock.png';");
    }
  
  return $obj;
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

$nodelist = $LMS->GetNodeList($o, NULL, NULL, $n, $s, $g, $ng);
$listdata['total'] = $nodelist['total'];
$listdata['order'] = $nodelist['order'];
$listdata['direction'] = $nodelist['direction'];
$listdata['totalon'] = $nodelist['totalon'];
$listdata['totaloff'] = $nodelist['totaloff'];
$listdata['network'] = $n;
$listdata['customergroup'] = $g;
$listdata['nodegroup'] = $ng;
$listdata['state'] = $s;

unset($nodelist['total']);
unset($nodelist['order']);
unset($nodelist['direction']);
unset($nodelist['totalon']);
unset($nodelist['totaloff']);

if ($SESSION->is_set('nlp') && !isset($_GET['page']))
	$SESSION->restore('nlp', $_GET['page']);
	
$page = (!isset($_GET['page']) ? 1 : $_GET['page']);
$pagelimit = (!isset($CONFIG['phpui']['nodelist_pagelimit']) ? $listdata['total'] : $CONFIG['phpui']['nodelist_pagelimit']);
$start = ($page - 1) * $pagelimit;

$SESSION->save('nlp', $page);

$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);
$SMARTY->assign('nodelist',$nodelist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('networks',$LMS->GetNetworks());
$SMARTY->assign('nodegroups', $LMS->GetNodeGroupNames());
$SMARTY->assign('customergroups', $LMS->CustomergroupGetAll());

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('setnodeaccess','setnodewarning','setnodeblockade'));
$SMARTY->assign('xajax',$LMS->RunXajax());

$SMARTY->display('nodelist.html');

?>
