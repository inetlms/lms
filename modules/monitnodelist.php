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
 *  $Id: monitnodelist.php,v 1.01 2013/01/20 22:01:35 Sylwester Kondracki Exp $
 */

if (isset($_POST['settesttype']))
{
    $LMS->SetTestTypeByMonit(intval($_POST['id']),$_POST['settesttype']);
    die;
}
if (isset($_POST['setpingtest']))
{
    $LMS->SetPingTestMonit(intval($_POST['id']),intval($_POST['setpingtest']));
    die;
}
if (isset($_POST['setsignaltest']))
{
    $LMS->SetSignalTestMonit(intval($_POST['id']),intval($_POST['setsignaltest'])); 
    die;
}

if (!isset($_GET['td']))
    $SESSION->restore('mltd',$node['typedev']);
else 
    $node['typedev'] = $_GET['td'];

if (empty($node['typedev'])) $node['typedev'] = 'netdev';
$SESSION->save('mltd',$node['typedev']);

if (!isset($_GET['d']))
    $SESSION->restore('mld',$listdata['direction']);
else
    $listdata['direction'] = $_GET['d'];
if (empty($listdata['direction'])) $listdata['direction'] = 'asc';
$SESSION->save('mld',$listdata['direction']);

if (!isset($_GET['o']))
    $SESSION->restore('mlo',$listdata['order']);
else
    $listdata['order'] = $_GET['o'];
if (empty($listdata['order'])) $listdata['order'] = 'name';
$SESSION->save('mlo',$listdata['order']);



$SESSION->save('backto', 'm=monitnodelist&td='.$node['typedev']);

$layout['pagetitle'] = 'Lista monitorowanych hostów '.($node['typedev']=='netdev' ? 'sieciowych' : 'klientów');

if (isset($_GET['action']))
{
    switch ($_GET['action'])
    {
	case 'setaccess'	: $LMS->SetMonit(intval($_GET['nid']),$_GET['access']); $SESSION->redirect('?'.$SESSION->get('backto'));break;
	case 'adddev'		: $LMS->SetMonit(intval($_GET['nid']),1); $SESSION->redirect('?'.$SESSION->get('backto')); break;
	case 'deldev'		: $LMS->DelMonit(intval($_GET['nid']),'netdev'); $SESSION->redirect('?'.$SESSION->get('backto')); break;
	case 'clearstat'	: $LMS->ClearStatMonit(intval($_GET['nid']),'netdev'); $SESSION->redirect('?'.$SESSION->get('backto')); break;
    }
}



$SMARTY->assign('devlist',$LMS->GetListNodesNotMonit(($node['typedev'] == 'netdev' ? false : true)));
$SMARTY->assign('monitlist',$LMS->GetListNodesMonit(($node['typedev'] == 'netdev' ? false : true),$listdata['order'].','.$listdata['direction']));
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('backto',$SESSION->get('backto'));
$SMARTY->assign('node',$node);
$SMARTY->display('monitnodelist.html');

?>