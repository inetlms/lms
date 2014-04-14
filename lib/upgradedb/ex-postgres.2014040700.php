<?php

/*
 *  iNET LMS
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

$DB->Execute('ALTER TABLE tariffs ADD dlimit_n INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_limit_up INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_threshold_up INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_time_up INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_limit_dn INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_threshold_dn INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_time_dn INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_limit_up_n INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_threshold_up_n INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_time_up_n INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_limit_dn_n INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_threshold_dn_n INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD burst_time_dn_n INTEGER DEFAULT NULL;');
$DB->Execute('ALTER TABLE tariffs ADD start_night_h SMALLINT DEFAULT 0;');
$DB->Execute('ALTER TABLE tariffs ADD start_night_m SMALLINT DEFAULT 0;');
$DB->Execute('ALTER TABLE tariffs ADD stop_night_h SMALLINT DEFAULT 0;');
$DB->Execute('ALTER TABLE tariffs ADD stop_night_m SMALLINT DEFAULT 0;');



$DB->Execute("DROP VIEW IF EXISTS monit_vnodes;");

$DB->Execute("ALTER TABLE monitnodes ADD disabled SMALLINT DEFAULT 0;");
$DB->Execute("ALTER TABLE monitnodes ADD src_netdev INTEGER DEFAULT '0';");

$DB->Execute("
 CREATE VIEW monit_vnodes AS SELECT 
 m.id AS id, m.test_type, m.test_port, m.send_timeout, m.send_ptime, inet_ntoa(n.ipaddr) AS ipaddr, m.maxptime, 
 COALESCE((SELECT 1 FROM nodes WHERE nodes.id = m.id AND nodes.netdev != 0),0) AS netdev, n.name 
 FROM monitnodes m 
 JOIN nodes n ON (n.id = m.id) 
 WHERE m.active = 1 AND n.ipaddr !=0 AND disabled = 0;
");

$DB->addconfig('monit','netdev_time_max','100');
$DB->addconfig('monit','autocreate_chart','0');



$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014040700', 'dbvex'));
$DB->CommitTrans();

?>