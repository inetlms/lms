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

$DB->addconfig('hiperus_c5','numberplanid','');
$DB->addconfig('hiperus_c5','taxrate','');
$DB->addconfig('hiperus_c5','prodid','');
$DB->addconfig('hiperus_c5','content','szt');
$DB->addconfig('hiperus_c5','wlr','0');
$DB->addconfig('hiperus_c5','leftmonth','1');
$DB->addconfig('hiperus_c5','accountlist_pagelimit','50');
$DB->addconfig('hiperus_c5','terminallist_pagelimit','50');
$DB->addconfig('hiperus_c5','force_relationship','1');
$DB->addconfig('hiperus_c5','number_manually','0');
$DB->addconfig('hiperus_c5','lms_login','');
$DB->addconfig('hiperus_c5','lms_pass','');
$DB->addconfig('hiperus_c5','lms_url','http://localhost/lms');
$DB->addconfig('phpui','delete_link_in_customerbalancebox','0');
$DB->addconfig('phpui','config_empty_value','0');

$DB->Execute("ALTER TABLE users ADD modules TEXT DEFAULT NULL ;");

$DB->Execute("ALTER TABLE tariffs ADD active TINYINT( 1 ) NOT NULL DEFAULT '1' ;");

$DB->Execute("ALTER TABLE tariffs ADD INDEX (active) ;");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2012122901', 'dbvex'));

$DB->CommitTrans();

?>
