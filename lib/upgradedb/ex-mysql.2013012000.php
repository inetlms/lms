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

$DB->Execute("DROP VIEW monit_vnodes;");

$DB->Execute("ALTER TABLE monittime  ENGINE = InnoDB ;");
$DB->Execute("ALTER TABLE iphistory  ENGINE = InnoDB ;");
$DB->Execute("ALTER TABLE monitwarn  ENGINE = InnoDB ;");
$DB->Execute("ALTER TABLE syslog  ENGINE = InnoDB ;");
$DB->Execute("ALTER TABLE monitnodes ADD pingtest TINYINT( 1 ) NOT NULL DEFAULT '1';");
$DB->Execute("ALTER TABLE monitnodes ADD signaltest TINYINT( 1 ) NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE netdevices ADD monit_nastype INT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE netdevices ADD monit_login VARCHAR( 60 ) NOT NULL DEFAULT '';");
$DB->Execute("ALTER TABLE netdevices ADD monit_passwd VARCHAR( 60 ) NOT NULL DEFAULT '';");
$DB->Execute("ALTER TABLE netdevices ADD monit_port SMALLINT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE users ADD profiles TEXT NOT NULL DEFAULT '';");
$DB->Execute("ALTER TABLE up_customers ADD content TEXT NOT NULL DEFAULT '' ;");

$tmp = array(
    'active_monitoring' 	=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','active_monitoring')),
    'netdev_test' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','netdev_test')),
    'netdev_test_type' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','netdev_test_type')),
    'nodes_test' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','nodes_test')),
    'nodes_test_type' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','nodes_test_type')),
    'owner_test' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','owner_test')),
    'owner_test_type' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','owner_test_type')),
    'packetsize' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','packetsize')),
    'step_test_netdev' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','step_test_netdev')),
    'step_test_nodes' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','step_test_nodes')),
    'step_test_owner' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','step_test_owner')),
    'test_script_dir' 		=> $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','test_script_dir'))
);

$DB->Execute('DELETE FROM uiconfig WHERE section=? ;',array('monit'));
$DB->addconfig('monit','active_monitoring',$tmp['active_monitoring'],0);
$DB->addconfig('monit','display_chart_in_node_box','1');
$DB->addconfig('monit','live_ping','1');
$DB->addconfig('monit','netdev_test',$tmp['netdev_test'],1);
$DB->addconfig('monit','netdev_test_type',$tmp['netdev_test-type'],'icmp');
$DB->addconfig('monit','node_test',$tmp['node_test'],1);
$DB->addconfig('monit','node_test_type',$tmp['node_test_type'],'icmp');
$DB->addconfig('monit','owner_test',$tmp['owner_test'],1);
$DB->addconfig('monit','owner_test_type',$tmp['owner_test_type'],'icmp');
$DB->addconfig('monit','packetsize',$tmp['packetsize'],'32');
$DB->addconfig('monit','rrdtool_dir','/usr/bin/rrdtool');
$DB->addconfig('monit','signal_test','0','0');
$DB->addconfig('monit','step_test_netdev',$tmp['step_test_netdev'],5);
$DB->addconfig('monit','step_test_nodes',$tmp['step_test_nodes'],10);
$DB->addconfig('monit','step_test_owner',$tmp['step_test_owner'],10);
$DB->addconfig('monit','step_test_signal','5');
$DB->addconfig('monit','test_script_dir',$tmp['test_script_dir'],'/usr/local/sbin/lms-monitoring.pl');
unset($tmp);
$DB->addconfig('userpanel','boxinfo1','','');
$DB->addconfig('userpanel','boxinfo2','','');
$DB->addconfig('userpanel','boxinfo3','','');
$DB->addconfig('userpanel','disable_modules','a:0:{}');
$DB->addconfig('invoices','default_type_of_documents','');

$DB->Execute("INSERT INTO up_rights (module, name, description, setdefault) VALUES ('info', 'ping_node', 'The client can PING test computers', '0');");
$DB->Execute("INSERT INTO up_rights (module,name,description,setdefault) VALUES ('info', 'print_traffic', 'The customer can print the link stats', '0');");

$DB->Execute("
CREATE TABLE monitsignal (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    cdate INT NOT NULL DEFAULT '0',
    nodeid INT NOT NULL ,
    rx_signal SMALLINT NOT NULL DEFAULT '0',
    tx_signal SMALLINT NOT NULL DEFAULT '0',
    signal_noise SMALLINT NOT NULL DEFAULT '0',
    rx_rate SMALLINT NOT NULL DEFAULT '0',
    tx_rate SMALLINT NOT NULL DEFAULT '0',
    rx_ccq SMALLINT  NOT NULL DEFAULT '0',
    tx_ccq SMALLINT  NOT NULL DEFAULT '0',
    ack_timeout SMALLINT  NOT NULL DEFAULT '0'
) ENGINE = InnoDB;
");

$DB->Execute("ALTER TABLE monitsignal ADD INDEX (cdate)");

$DB->Execute("
CREATE VIEW monit_vnodes AS 
    SELECT  m.id AS id, m.test_type, m.test_port, m.send_timeout, m.send_ptime, inet_ntoa(n.ipaddr) AS ipaddr, m.maxptime, m.pingtest, m.signaltest, mc.mac, 
    COALESCE((SELECT 1 FROM nodes WHERE nodes.id = m.id AND nodes.netdev != 0 AND nodes.ownerid = 0),0) AS netdev, n.name 
    FROM monitnodes m 
    JOIN nodes n ON (n.id = m.id) 
    JOIN macs mc ON (nodeid = m.id) 
    WHERE m.active = 1 AND n.ipaddr !=0 ;
");

$DB->Execute("
CREATE TABLE messagestemplate (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
name VARCHAR(255) NOT NULL DEFAULT '',
theme VARCHAR( 255 ) NOT NULL DEFAULT '',
message TEXT NOT NULL DEFAULT ''
) ENGINE = InnoDB;
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013012000', 'dbvex'));

$DB->CommitTrans();
?>