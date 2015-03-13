<?php

/*
 *  iNET LMS
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
 *  $Id: v 2.00 Sylwester Kondracki Exp $
 */

$SESSION->save('backto',$_SERVER['QUERY_STRING']);

$layout['pagetitle'] = trans('Lista węzłów');

if (!isset($_GET['page']))
    $SESSION->restore('ntk_list_page',$_GET['page']);

if (!isset($_GET['status'])) $SESSION->restore('ntk_status',$status); else $status = $_GET['status'];
if (is_null($status)) $status = '-1';
$SESSION->save('ntk_status',$status);

if (!isset($_GET['project'])) $SESSION->restore('ntk_project',$project); else $project = $_GET['project'];
if (is_null($project)) $project = '-1';
$SESSION->save('ntk_project',$project);

if (!isset($_GET['owner'])) $SESSION->restore('ntk_owner',$owner); else $owner = $_GET['owner'];
if (is_null($owner)) $owner = '-1';
$SESSION->save('ntk_owner',$owner);

if (!isset($_GET['group'])) $SESSION->restore('ntk_group',$group); else $group = $_GET['group'];
if (is_null($group)) $group = '-1';
$SESSION->save('ntk_group',$group);

$page = (!$_GET['page'] ? 1 : $_GET['page']);
$pagelimit = get_conf('phpui.networknode_pagelimit','50');
$start = ($page - 1) * $pagelimit;

$netlist = $LMS->GetListnetworknode(
    ($status != '-1' ? $status : NULL),
    ($project != '-1' ? $project : NULL),
    ($owner != '-1' ? $owner : NULL),
    ($group != '-1' ? $group : NULL)
);

$listdata['total'] = sizeof($netlist);
$listdata['status'] = $status;
$listdata['project'] = $project;
$listdata['owner'] = $owner;
$listdata['group'] = $group;

$SESSION->save('ntk_list_page',$_GET['page']);

$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('netlist',$netlist);
$SMARTY->assign('page',$page);
$SMARTY->assign('start',$start);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('projectlist',$DB->getAll('SELECT id,name FROM invprojects WHERE type = 0 ORDER BY name ASC;'));
$SMARTY->assign('grouplist',$DB->getall('SELECT id,name FROM networknodegroups ORDER BY name ASC;'));
$SMARTY->display('networknodelist.html');
?>