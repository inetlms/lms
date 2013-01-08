<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2001-2012 LMS Developers
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
 */

$DB->BeginTrans();

$DB->addconfig('phpui','installation_name','');
$DB->addconfig('phpui','iphistory','1');
$DB->addconfig('phpui','iphistory_pagelimit','50');

$DB->Execute("CREATE SEQUENCE iphistory_id_seq;");
$DB->Execute("
CREATE TABLE iphistory (
    id integer default nextval('iphistory_id_seq'::text) NOT NULL,
    nodeid integer default 0,
    ipaddr bigint default 0 not null ,
    ipaddr_pub bigint default 0 not null,
    ownerid integer default NULL ,
    netdev integer default NULL ,
    fromdate integer DEFAULT 0,
    todate integer DEFAULT 0,
    PRIMARY KEY (id));
");

if ($iplist = $DB->GetAll('SELECT id, ipaddr, ipaddr_pub, ownerid, creationdate, netdev FROM nodes ')) {
    $count = sizeof($iplist);

    for ($i=0; $i<$count; $i++) {

	if (!empty($iplist[$i]['ipaddr']) && $iplist[$i]['ipaddr'] != '0')
	$DB->Execute('INSERT INTO iphistory (nodeid, ipaddr, ipaddr_pub, ownerid, netdev, fromdate, todate) VALUES (?,?,?,?,?,?,?) ;',
		array($iplist[$i]['id'],$iplist[$i]['ipaddr'],0,$iplist[$i]['ownerid'],$iplist[$i]['netdev'],$iplist[$i]['creationdate'],0));
	
	if (!empty($iplist[$i]['ipaddr_pub']) && $iplist[$i]['ipaddr_pub'] != '0')
	$DB->Execute('INSERT INTO iphistory (nodeid, ipaddr, ipaddr_pub, ownerid, netdev, fromdate, todate) VALUES (?,?,?,?,?,?,?) ;',
		array($iplist[$i]['id'],0,$iplist[$i]['ipaddr_pub'],$iplist[$i]['ownerid'],$iplist[$i]['netdev'],$iplist[$i]['creationdate'],0));

    } // end for
} //end if $iplist

$DB->Execute("CREATE INDEX iphistory_nodeid_idx ON iphistory (nodeid); ");
$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013010500', 'dbvex'));

$DB->CommitTrans();

?>