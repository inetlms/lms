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
 *  $Id: 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */
$layout['x_ajax'] = true;
$layout['popup'] = true;

$cid = intval($_GET['cid']);
if (isset($_GET['nid'])) $nid = intval($_GET['nid']);
else $nid = $DB->GetOne('SELECT id FROM nodes WHERE ownerid = ? LIMIT 1;',array($cid));

if (!$LMS->nodeexists($nid))
{
    $nid = $DB->GetOne('SELECT id FROM nodes WHERE ownerid = ? LIMIT 1;',array($cid));
}

$chart['ping_test'] = $chart['signal_test'] = $chart['signal_exp_test'] = $chart['transfer_test'] = $chart['first_time'] = $chart['last_time'] = false;
$chart['nid'] = $nid;
if (file_exists(RRD_DIR.'/ping.node.'.$nid.'.rrd')) $chart['ping_test'] = true;
if (file_exists(RRD_DIR.'/signal.node.'.$nid.'.rrd')) $chart['signal_test'] = true;
if (file_exists(RRD_DIR.'/signal.exp.node.'.$nid.'.rrd')) $chart['signal_exp_test'] = true;
if (file_exists(RRD_DIR.'/transfer.node.'.$nid.'.rrd')) $chart['transfer_test'] = true;

$SMARTY->assign('chart',$chart);
$SMARTY->assign('nodes_monit',$nodes_monit);


function gen_chart($idelement,$nodeid,$charttype,$fromdate = NULL, $todate = NULL) 
{
	global $LMS,$DB,$PROFILE,$SMARTY,$AUTH;
	$layout['popup'] = true;
	$time = time();
	
	$node_info = $DB->GetRow('SELECT inet_ntoa(ipaddr) AS ipaddr, name FROM nodes WHERE id = ? LIMIT 1 ;',array($nodeid));
	$obj = new xajaxResponse();

	if (is_null($fromdate) || empty($fromdate)) $fromdate = time() - 86400;
	if (is_null($todate) || empty($todate)) $todate = time();
	
	$chart['nid'] = $nodeid;
	
	switch ($charttype)
	{
		case 'ping'	:
				if ($oldtime = get_profile('cmbcpot')) 
				    @unlink(TMP_DIR.'/tmp.ping.'.$AUTH->id.'.'.$oldtime.'.chartwin.png');
				
				$PROFILE->nowsave('cmbcpot',$time);
				
				$firstdate = $LMS->RRD_FirstTime('ping.node.'.$nodeid);
				$lastdate = $LMS->RRD_LastTime('ping.node.'.$nodeid);
				
				if ($fromdate < $firstdate) $fromdate = $firstdate;
				if ($todate > $lastdate) $todate = $lastdate;
				if ($fromdate > ($todate-43200)) $fromdate = $todate - 43200;
				
				$LMS->RRD_CreatePingImage('node.'.$nodeid,
				$node_info['name']." (".$node_info['ipaddr'].") ".date('Y/m/d H:i',$fromdate)." - ".date('Y/m/d H:i',$todate)
				,$fromdate,$todate,790,360,'tmp.ping.'.$AUTH->id.'.'.$time.'.chartwin');
				
				$innerIMG = '<img src="tmp/tmp.ping.'.$AUTH->id.'.'.$time.'.chartwin.png" border="0" alt="">';
		break;
		
		case 'signal'	:
				if ($oldtime = get_profile('cmbcsot')) 
				    @unlink(TMP_DIR.'/tmp.signal.'.$AUTH->id.'.'.$oldtime.'.chartwin.png');
				
				$PROFILE->nowsave('cmbcsot',$time);
				
				$firstdate = $LMS->RRD_FirstTime('signal.node.'.$nodeid);
				$lastdate = $LMS->RRD_LastTime('signal.node.'.$nodeid);
				
				if ($fromdate < $firstdate) $fromdate = $firstdate;
				if ($todate > $lastdate) $todate = $lastdate;
				if ($fromdate > ($todate-43200)) $fromdate = $todate - 43200;
				
				$LMS->RRD_CreateSignalImage('node.'.$nodeid,
				$node_info['name']." (".$node_info['ipaddr'].") ".date('Y/m/d H:i',$fromdate)." - ".date('Y/m/d H:i',$todate)
				,$fromdate,$todate,790,360,'tmp.signal.'.$AUTH->id.'.'.$time.'.chartwin',true);
				
				$innerIMG = '<img src="tmp/tmp.signal.'.$AUTH->id.'.'.$time.'.chartwin.png" border="0" alt="">';
		break;
		
		case 'packets'	:
				if ($oldtime = get_profile('cmbcpot')) 
				    @unlink(TMP_DIR.'/tmp.packets.'.$AUTH->id.'.'.$oldtime.'.chartwin.png');
				
				$PROFILE->nowsave('cmbcpot',$time);
				
				$firstdate = $LMS->RRD_FirstTime('transfer.node.'.$nodeid);
				$lastdate = $LMS->RRD_LastTime('transfer.node.'.$nodeid);
				
				if ($fromdate < $firstdate) $fromdate = $firstdate;
				if ($todate > $lastdate) $todate = $lastdate;
				if ($fromdate > ($todate-43200)) $fromdate = $todate - 43200;
				
				$LMS->RRD_CreatePacketsImage('node.'.$nodeid,
				$node_info['name']." (".$node_info['ipaddr'].") ".date('Y/m/d H:i',$fromdate)." - ".date('Y/m/d H:i',$todate)
				,$fromdate,$todate,790,360,'tmp.packets.'.$AUTH->id.'.'.$time.'.chartwin',true);
				
				$innerIMG = '<img src="tmp/tmp.packets.'.$AUTH->id.'.'.$time.'.chartwin.png" border="0" alt="">';
		break;
		
		case 'bits'	:
				if ($oldtime = get_profile('cmbcbot')) 
				    @unlink(TMP_DIR.'/tmp.bits.'.$AUTH->id.'.'.$oldtime.'.chartwin.png');
				
				$PROFILE->nowsave('cmbcbot',$time);
				
				$firstdate = $LMS->RRD_FirstTime('transfer.node.'.$nodeid);
				$lastdate = $LMS->RRD_LastTime('transfer.node.'.$nodeid);
				
				if ($fromdate < $firstdate) $fromdate = $firstdate;
				if ($todate > $lastdate) $todate = $lastdate;
				if ($fromdate > ($todate-43200)) $fromdate = $todate - 43200;
				
				$LMS->RRD_CreateBitsImage('node.'.$nodeid,
				$node_info['name']." (".$node_info['ipaddr'].") ".date('Y/m/d H:i',$fromdate)." - ".date('Y/m/d H:i',$todate)
				,$fromdate,$todate,790,360,'tmp.bits.'.$AUTH->id.'.'.$time.'.chartwin',true);
				
				$innerIMG = '<img src="tmp/tmp.bits.'.$AUTH->id.'.'.$time.'.chartwin.png" border="0" alt="">';
		break;
	}
	
	$SMARTY->assign('chart',$chart);
	$obj->assign('id_from_date','value',date('Y/m/d',$fromdate));
	$obj->assign('id_to_date','value',date('Y/m/d',$todate));
	$obj->assign($idelement,'innerHTML',$innerIMG);
	return $obj;
}

$LMS->InitXajax();
$LMS->RegisterXajaxFunction('gen_chart');
$SMARTY->assign('xajax',$LMS->RunXajax());

$SMARTY->display('customermonit.html');
?>