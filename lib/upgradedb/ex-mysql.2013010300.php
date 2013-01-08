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

$DB->Execute("
CREATE TABLE iphistory (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
nodeid int not null default 0,
ipaddr INT UNSIGNED NOT NULL ,
ipaddr_pub INT UNSIGNED NOT NULL ,
ownerid INT NOT NULL ,
netdev INT NOT NULL ,
fromdate INT NOT NULL DEFAULT 0,
todate INT NOT NULL DEFAULT 0
) ENGINE = MyISAM ;
");

if ($iplist = $DB->GetAll('SELECT id, ipaddr, ipaddr_pub, ownerid, creationdate, netdev FROM nodes ')) {
    $count = sizeof($iplist);

    for ($i=0; $i<$count; $i++) {

	if (!empty($iplist[$i]['ipaddr']))
	$DB->Execute('INSERT INTO iphistory (nodeid, ipaddr, ipaddr_pub, ownerid, netdev, fromdate, todate) VALUES (?,?,?,?,?,?,?) ;',
		array($iplist[$i]['id'],$iplist[$i]['ipaddr'],0,$iplist[$i]['ownerid'],$iplist[$i]['netdev'],$iplist[$i]['creationdate'],0));
	
	if (!empty($iplist[$i]['ipaddr_pub']))
	$DB->Execute('INSERT INTO iphistory (nodeid, ipaddr, ipaddr_pub, ownerid, netdev, fromdate, todate) VALUES (?,?,?,?,?,?,?) ;',
		array($iplist[$i]['id'],0,$iplist[$i]['ipaddr_pub'],$iplist[$i]['ownerid'],$iplist[$i]['netdev'],$iplist[$i]['creationdate'],0));

    } // end for
} //end if $iplist

$DB->Execute("ALTER TABLE iphistory ADD INDEX (nodeid); ");

$DB->Execute("ALTER TABLE info_center_post ADD FOREIGN KEY (infoid) REFERENCES info_center (id) ON DELETE CASCADE ON UPDATE CASCADE ;");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013010500', 'dbvex'));

$DB->CommitTrans();
?>