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

$layout['pagetitle'] = trans('Interfejsy sieciowe');

if(!isset($_GET['o']))
	$SESSION->restore('ndlo', $o);
else
	$o = $_GET['o'];
$SESSION->save('ndlo', $o);

if (!isset($_GET['status'])) $SESSION->restore('ndlstatus',$status); else $status = $_GET['status'];
if (is_null($status)) $status = '-1';
$SESSION->save('ndlstatus',$status);

if (!isset($_GET['project'])) $SESSION->restore('ndlproject',$project); else $project = $_GET['project'];
if (is_null($project)) $project = '-1';
$SESSION->save('ndlproject',$project);

if (!isset($_GET['networknode'])) $SESSION->restore('ndlnetworknode',$networknode); else $networknode = $_GET['networknode'];
if (is_null($networknode)) $networknode = '-1';
$SESSION->save('ndlnetworknode',$networknode);

if (!isset($_GET['producer'])) $SESSION->restore('ndlproducer',$producer); else $producer = $_GET['producer'];
if (is_null($producer)) $producer = '-1';
$producer=strtoupper($producer);
$SESSION->save('ndlproducer',$producer);

if (!isset($_GET['model'])) $SESSION->restore('ndlmodel',$model); else $model = $_GET['model'];
if (is_null($model)) $model = '-1';
$model=strtoupper($model);
$SESSION->save('ndlmodel',$model);


$netdevlist = $LMS->GetNetDevList($o,
				($status != '-1' ? $status : NULL),
				($project != '-1' ? $project : NULL),
				($networknode != '-1' ? $networknode : NULL),
				($producer != '-1' ? $producer : NULL),
				($model != '-1' ? $model : NULL)
);
$listdata['total'] = $netdevlist['total'];
$listdata['order'] = $netdevlist['order'];
$listdata['direction'] = $netdevlist['direction'];
$listdata['status'] = $status;
$listdata['project'] = $project;
$listdata['networknode'] = $networknode;
$listdata['producer'] = $producer;
$listdata['model'] = $model;

unset($netdevlist['total']);
unset($netdevlist['order']);
unset($netdevlist['direction']);

if(!isset($_GET['page']))
        $SESSION->restore('ndlp', $_GET['page']);
	
$page = (! $_GET['page'] ? 1 : $_GET['page']);
$pagelimit = get_conf('phpui.nodelist_pagelimit','50');
$start = ($page - 1) * $pagelimit;

$SESSION->save('ndlp', $page);

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

$producerlist = $modellist = array();
$producerlist[]['producer'] = $modellist[]['model'] = '';

if ($tmp = $DB->GetAll('SELECT producer FROM netdevices GROUP BY producer ORDER BY producer ASC;')) {
    for ($i=0; $i<sizeof($tmp); $i++) 
	if (!empty($tmp[$i]['producer']))
	    $producerlist[]['producer'] = strtoupper($tmp[$i]['producer']);
}

if ($producer != '-1')
{
    if ($tmp = $DB->getAll('SELECT model FROM netdevices WHERE UPPER(producer) = ? GROUP BY model ORDER BY model ASC;',array(strtoupper($producer))))
	for ($i=0; $i<sizeof($tmp); $i++)
	    if (!empty($tmp[$i]['model']))
		$modellist[]['model'] = $tmp[$i]['model'];
}



$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);
$SMARTY->assign('netdevlist',$netdevlist);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('projectlist',$DB->getAll('SELECT id,name FROM invprojects WHERE type = 0 ORDER BY name ASC;'));
$SMARTY->assign('networknodelist',$LMS->GetListnetworknode());
$SMARTY->assign('producerlist',$producerlist);
$SMARTY->assign('modellist',$modellist);
$SMARTY->display('netdevlist.html');

?>
