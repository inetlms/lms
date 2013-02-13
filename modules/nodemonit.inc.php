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

$node_monit['ping'] = $node_monit['signal'] = NULL;

if (get_conf('monit.display_chart_in_node_box','1'))
{
	if (!file_exists(TMP_DIR.'/ping.node.'.$nodeid.'.small.png') && file_exists(RRD_DIR.'/ping.node.'.$nodeid.'.rrd'))
		$LMS->RRD_CreateSmallPingImage('node.'.$nodeid,'-1d','now');

	if (file_exists(TMP_DIR.'/ping.node.'.$nodeid.'.small.png')) 
		$node_monit['ping'] = true;;

	if (!file_exists(TMP_DIR.'/signal.node.'.$nodeid.'.small.png'))
		if (file_exists(RRD_DIR.'/signal.node.'.$nodeid.'.rrd'))
			$LMS->RRD_CreateSmallSignalImage('node.'.$nodeid,'-1d','now',NULL,(file_exists(RRD_DIR.'/signal.exp.node.'.$nodeid.'.rrd') ? true : false));

	if (file_exists(TMP_DIR.'/signal.node.'.$nodeid.'.small.png'))
		$node_monit['signal'] = true;
}

if ($date = $DB->GetOne('SELECT MAX(cdate) FROM monittime WHERE nodeid = ? '.$DB->Limit(1).' ;',array($nodeid)))
{
    $node_monit['ping_date'] = $date;
    $node_monit['ping_time'] = $DB->GetOne('SELECT ptime FROM monittime WHERE nodeid = ? AND cdate = ? '.$DB->Limit(1).' ;',array($nodeid,$date));
    $node_monit['ping'] = true;
}

if ($date = $DB->GetOne('SELECT MAX(cdate) FROM monitsignal WHERE nodeid = ? '.$DB->Limit(1).' ;',array($nodeid)))
{
	$node_monit['signal_date'] = $date;
	
	$tmp = $DB->GetRow('SELECT rx_signal, tx_signal, signal_noise, tx_rate, rx_rate, 
		rx_ccq, tx_ccq, ack_timeout FROM monitsignal WHERE nodeid = ? AND cdate = ? '.$DB->Limit(1).' ;',
		array($nodeid,$date));
	
	$node_monit['rx_signal'] = $tmp['tx_signal'];
	$node_monit['tx_signal'] = $tmp['rx_signal'];
	$node_monit['tx_rate'] = $tmp['rx_rate'];
	$node_monit['rx_rate'] = $tmp['tx_rate'];
	$node_monit['rx_ccq'] = $tmp['tx_ccq'];
	$node_monit['tx_ccq'] = $tmp['rx_ccq'];
	$node_monit['signal_noise'] = $tmp['signal_noise'];
	$node_monit['ack_timeout'] = $tmp['ack_timeout'];
	$node_monit['signal'] = true;
	unset($tmp);
}
$node_monit['pingtest'] = $node_monit['signaltest'] = 0;
if ($tmp = $DB->GetRow('SELECT pingtest, signaltest FROM monitnodes WHERE id = ? LIMIT 1;',array($nodeid)))
{
    if ($tmp['pingtest']) $node_monit['pingtest'] = 1;
    if ($tmp['signaltest']) $node_monit['signaltest'] = 1;
}

$SMARTY->assign('node_monit',$node_monit);

?>