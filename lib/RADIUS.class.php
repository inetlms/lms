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
 *  Sylwester Kondracki Exp $
*/


class RADIUS {

    var $DB;
    var $LMS;
    
	function radius(&$DB,&$LMS)
	{
		$this->DB = &$DB;
		$this->LMs =&$LMS;
	}
    

    function send_disconnect_user ($theUser, $nasaddr, $coaport, $sharedsecret) 
    {
	    $command = "echo \"User-Name=$theUser\"|radclient -x $nasaddr:$coaport disconnect $sharedsecret";
	    $result=`$command`;
	    return $result;
    }

    function disconnect_user($radacctid)
    {
	
	$radacct = $this->DB->GetRow('SELECT username, nasipaddress FROM radacct WHERE radacctid = ? LIMIT 1;',array($radacctid));
	$nas = $this->DB->GetRow('SELECT secret, coaport FROM nas WHERE nasname = ? LIMIT 1;',array($radacct['nasipaddress']));
	
	if ($radacct && $nas) {
	    return $this->send_disconnect_user($radacct['username'], $radacct['nasipaddress'], ($nas['coaport'] ? $nas['coaport'] : get_conf('radius.coa_port','3799')), $nas['secret']);
	} else
	    return FALSE;
	
    }
    
    
    function getradacctlist($status = 'all', $nullsession = 'all', $sessions = 'all', $cause = 'all', $startdatefrom = NULL, $startdateto = NULL, $enddatefrom = NULL, $enddateto = NULL, $cid = NULL, $nid = NULL)
    {
	$status = strtolower($status);
	$nullsession = strtolower($nullsession);
	$sessions = strtolower($sessions);
	$cause = strtoupper($cause);

	if (!empty($startdatefrom))
	    $startdatefrom = str_replace('/','-',$startdatefrom);
	
	if (!empty($startdateto))
	    $startdateto = str_replace('/','-',$startdateto);
	
	if (!empty($enddatefrom))
	    $enddatefrom = str_replace('/','-',$enddatefrom);
	
	if (!empty($enddateto))
	    $enddateto = str_replace('/','-',$enddateto);
	
	$return =
		$this->DB->GetAll('SELECT r.radacctid, r.acctsessionid, r.username, r.nasipaddress, r.nasporttype, r.acctstarttime, r.servicetype '
		.', r.acctstoptime, r.acctterminatecause '
		.', r.acctsessiontime, r.acctinputoctets, r.acctoutputoctets, r.framedipaddress, UPPER(r.callingstationid) AS callingstationid '
		.', nas.name AS nasname, nas.netdev AS nasid '
		.', n.id AS nodeid, n.location AS nodelocation, n.name AS nodename , c.id AS cid'
		.', '.$this->DB->Concat('c.lastname',"' '",'c.name').' AS customername '
		.', '.$this->DB->Concat('c.zip',"' '",'c.city',"' '",'c.address').' AS customeraddress '
		.', n.location AS nodelocation, n.producer AS nodeproducer, n.model AS nodemodel '
		.($status=='open' ? ', nd.maxid AS maxid ' : ', 0 AS maxid ')
		.'FROM radacct r '
		.'LEFT JOIN nodes nas ON (inet_ntoa(nas.ipaddr) = r.nasipaddress) '
		.($status=='open' ? 'JOIN ( SELECT MAX(radacctid) AS maxid, username FROM radacct GROUP BY username) nd ON (nd.username = r.username) ' : '')
		.(strtolower(get_conf('radius.auth_login','id')) == 'id' ? 'JOIN nodes n ON (n.id = r.username) ' : '')
		.(strtolower(get_conf('radius.auth_login')) == 'name' ? 'JOIN nodes n ON (UPPER(n.name) = UPPER(r.username)) ' : '')
		.(strtolower(get_conf('radius.auth_login')) == 'ip' ? 'JOIN nodes n ON (inet_ntoa(n.ipaddr) = r.username) ' : '')
		.'JOIN customers c ON (c.id = n.ownerid) '
		.'WHERE 1=1 '
		.(($cid) ? " AND c.id = '".$cid."'" : '')
		.(($nid) ? " AND n.id = '".$nid."'" : '')
		.($status=='open' ? ' AND (r.acctstoptime IS NULL OR r.acctstoptime=\'0000-00-00 00:00:00\') ' : '')
		.($status=='completed' ? ' AND r.acctstoptime IS NOT NULL AND r.acctstoptime!=\'0000-00-00 00:00:00\' ' : '')
		.($nullsession=='tak' ? ' AND r.acctsessiontime = 0 ' : '')
		.($nullsession=='nie' ? ' AND r.acctsessiontime != 0 ' : '')
		.($sessions=='cur' && $status=='open' ? ' AND r.radacctid = nd.maxid ' : '')
		.($sessions=='err' && $status=='open' ? ' AND r.radacctid != nd.maxid ' : '')
		.(!empty($startdatefrom) ? " AND DATE(r.acctstarttime) >= '".$startdatefrom."'" : '')
		.(!empty($startdateto) ? " AND DATE(r.acctstarttime) <= '".$startdateto."'" : '')
		.(!empty($enddatefrom) ? " AND DATE(r.acctstoptime) >= '".$enddatefrom."'" : '')
		.(!empty($enddateto) ? " AND DATE(r.acctstoptime) <= '".$enddateto."'" : '')
		.($status=='completed' && $cause=='NULL' ? " AND r.acctterminatecause=''" : '')
		.($status=='completed' && $cause!='NULL' && $cause!='ALL' ? " AND UPPER(r.acctterminatecause)='".$cause."'" : '')
		.' ORDER BY r.acctstarttime DESC'
		.';');

	return $return;
    }

    
    
    function closedradacct($id)
    {
	$this->DB->Execute('UPDATE radacct SET acctstoptime=?, acctterminatecause=? WHERE radacctid = ?;',
		array(date('%y-%m-%d H:i:s',time()),'User-Request',$id));
    }
}


?>