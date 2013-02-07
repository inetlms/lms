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
 *  $Id: monitchartshort.php,v 1.0.1 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */

$layout['popup'] = true;
$chart['type'] = $_GET['type'];
//$chart['time'] = $_GET['time'];
$chart['id'] = $_GET['id'];
$chart['chart'] = (isset($_GET['chart']) ? $_GET['chart'] : 'ping');
$chart['img'] = true; 
$chart['filename'] = '';

//include(LIB_DIR.'/Monitoring.inc.php');

if ($chart['chart'] == 'ping')
{
    if ($chart['type'] == 'node' || $chart['type'] == 'netdev')
    {
	if (file_exists(RRD_DIR.'/ping.node.'.$chart['id'].'.rrd') && !file_exists(TMP_DIR.'/ping.node.'.$chart['id'].'.small.png'))
	{
	    $nname = $DB->GetOne('SELECT inet_ntoa(ipaddr) FROM nodes WHERE id = ? LIMIT 1',array($chart['id']));
	    $LMS->RRD_CreatePingImage('node.'.$chart['id'],'Ping '.$nname.', Ostatnie 12h','-12h','now',450,200);
	}
	if (!file_exists(TMP_DIR.'/ping.node.'.$chart['id'].'.small.png')) 
	    $chart['img'] = false;
	else
	    $chart['filename'] = 'tmp/ping.node.'.$chart['id'].'.small.png';
    }
    
    if ($chart['type'] == 'owner')
    {
	if (file_exists(RRD_DIR.'/ping.owner.'.$chart['id'].'.rrd') && !file_exists(TMP_DIR.'/ping.owner.'.$chart['id'].'.small.png'))
	{
	    $nname = $DB->GetOne('SELECT ipaddr FROM monitown WHERE id = ? LIMIT 1',array($chart['id']));
	    $LMS->RRD_CreatePingImage('owner.'.$chart['id'],'Ping '.$nname.' Ostatnie 12h','-12h','now',450,200);
	}
	if (!file_exists(TMP_DIR.'/ping.owner.'.$chart['id'].'.small.png')) 
	    $chart['img'] = false;
	else
	    $chart['filename'] = 'tmp/ping.owner.'.$chart['id'].'.small.png';
    }
}

if ($chart['chart'] == 'signal')
{
    if ($chart['type'] == 'node')
    {
	if (file_exists(RRD_DIR.'/signal.node.'.$chart['id'].'.rrd') && !file_exists(TMP_DIR.'/signal.node.'.$chart['id'].'.small.png'))
	{
	    $nname = $DB->GetOne('SELECT inet_ntoa(ipaddr) FROM nodes WHERE id = ? LIMIT 1',array($chart['id']));
	    $LMS->RRD_CreateSignalImage('node.'.$chart['id'],'Ping '.$nname.', Ostatnie 12h','-12h','now',450,200);
	}
	if (!file_exists(TMP_DIR.'/signal.node.'.$chart['id'].'.small.png')) 
	    $chart['img'] = false;
	else
	    $chart['filename'] = 'tmp/signal.node.'.$chart['id'].'.small.png';
    }

}

$SMARTY->assign('chart',$chart);
$SMARTY->display('monitchartshort.html');

?>