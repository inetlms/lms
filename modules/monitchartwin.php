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
 *  $Id: monitchartwin.php,v 1.00 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */

$layout['popup'] = true;



$chart['type'] = (isset($_GET['type']) ? $_GET['type'] : 'node');
$chart['chart'] = (isset($_GET['chart']) ? $_GET['chart'] : 'ping');
$chart['id'] = $_GET['id'];
$chart['image'] = true;
//$chart['from'] = (isset($_GET['from']) ? $_GET['from'] : '');

// sprawdzamy jakie wykresy są dostępne
$chart['ping_test'] = $chart['signal_test'] = $chart['signal_exp_test'] = $chart['transfer_test'] = $chart['first_time'] = $chart['last_time'] = false;

if (file_exists(RRD_DIR.'/ping.'.$chart['type'].'.'.$chart['id'].'.rrd')) $chart['ping_test'] = true;
if (file_exists(RRD_DIR.'/signal.'.$chart['type'].'.'.$chart['id'].'.rrd')) $chart['signal_test'] = true;
if (file_exists(RRD_DIR.'/signal.exp.'.$chart['type'].'.'.$chart['id'].'.rrd')) $chart['signal_exp_test'] = true;
if (file_exists(RRD_DIR.'/transfer.'.$chart['type'].'.'.$chart['id'].'.rrd')) $chart['transfer_test'] = true;

if ($chart['chart'] == 'ping' && !$chart['ping_test']) $chart['chart'] = 'signal';
if ($chart['chart'] == 'signal' && !$chart['signal_test']) $chart['chart'] = 'packets';

if (in_array($chart['type'],array('node','netdev'))) 
    $host_ipaddr = $DB->GetOne('SELECT inet_ntoa(ipaddr) AS ipaddr FROM nodes WHERE id = ? '.$DB->Limit(1).' ;',array($chart['id']));
else 
    $host_ipaddr = $DB->GetOne('SELECT ipaddr FROM monitown WHERE id = ? '.$DB->Limit(1).' ;',array($chart['id']));


function genImage($typwykresu)
{
    global $SMARTY,$AUTH,$chart,$host_ipaddr,$LMS,$PROFILE;

    $czas = time();
    
    if ($oldtime = get_profile('monit_tmp_win_time'))
	    @unlink(TMP_DIR.'/tmp.'.$AUTH->id.'.'.$oldtime.'.chartwin.png');
    
    $objResponse = new xajaxResponse();

    switch ($typwykresu)
    {
	case 'ping'	: $LMS->RRD_CreatePingImage($chart['type'].'.'.$chart['id'],"Ping ".$host_ipaddr.' Last 24h','-1d','now',600,310,'tmp.'.$AUTH->id.'.'.$czas.'.chartwin'); break;
	case 'signal'	: $LMS->RRD_CreateSignalImage($chart['type'].'.'.$chart['id'],"Signal ".$host_ipaddr.' Last 24h','-1d','now',600,310,'tmp.'.$AUTH->id.'.'.$czas.'.chartwin',$chart['signal_exp_test']); break;
	case 'packets'	: $LMS->RRD_CreatePacketsImage($chart['type'].'.'.$chart['id'],"Pakiety ".$host_ipaddr." Last 24h",'-1d','now',600,310,'tmp.'.$AUTH->id.'.'.$czas.'.chartwin'); break;
	case 'bits'	: $LMS->RRD_CreateBitsImage($chart['type'].'.'.$chart['id'],"Bity ".$host_ipaddr." Last 24h",'-1d','now',600,310,'tmp.'.$AUTH->id.'.'.$czas.'.chartwin'); break;
    }

    $PROFILE->nowsave('monit_tmp_win_time',$czas);
    $objResponse->assign("id_wykres","innerHTML","<img src='tmp/"."tmp.".$AUTH->id.".".$czas.".chartwin.png' border='0'>");

    return $objResponse;

}


$LMS->InitXajax();
$LMS->RegisterXajaxFunction('genImage');
$SMARTY->assign('xajax',$LMS->RunXajax());

$SMARTY->assign('chart',$chart);
$SMARTY->display('monitchartwin.html');

?>