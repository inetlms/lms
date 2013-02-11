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

$tmp = $DB->GetOne('SELECT value FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('monit','step_test'));
if (!$tmp) $tmp = 5;

$DB->addconfig('monit','active_monitoring','1');
$DB->addconfig('monit','netdev_clear','365');
$DB->addconfig('monit','netdev_test','1');
$DB->addconfig('monit','netdev_test_type','icmp');
$DB->addconfig('monit','nodes_clear','365');
$DB->addconfig('monit','nodes_test','1');
$DB->addconfig('monit','nodes_test_type','icmp');
$DB->addconfig('monit','packetsize','32');
$DB->addconfig('monit','test_script_dir','/usr/local/sbin/lms-monitoring.pl');

$DB->Execute('INSERT INTO uiconfig (section,var,value) VALUES (?,?,?), (?,?,?), (?,?,?), (?,?,?), (?,?,?), (?,?,?) ;',array(
    'monit','step_test_netdev',$tmp,
    'monit','step_test_nodes',$tmp,
    'monit','step_test_owner',$tmp,
    'monit','onwer_clear','365',
    'monit','owner_test','1',
    'monit','owner_test_type','icmp'
    ));

$DB->Execute('DELETE FROM uiconfig WHERE section=? AND var=?;',array('monit','step_test'));

if ($DB->GetOne('SELECT 1 FROM uiconfig WHERE section = ? AND var = ? LIMIT 1;',array('phpui','default_module')))
    $DB->Execute('UPDATE uiconfig SET value = ? WHERE section = ? AND var = ? ;',array('welcome_new','phpui','default_module'));
else
    $DB->Execute('INSERT INTO uiconfig (section,var,value) VALUES (?,?,?);',array('phpui','default_module','welcome_new'));

$DB->Execute('UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?', array('2012122100', 'dbvex'));

$DB->CommitTrans();

?>