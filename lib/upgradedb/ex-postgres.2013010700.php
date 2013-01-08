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

$DB->addconfig('autobackup','ftphost','');
$DB->addconfig('autobackup','ftpuser','');
$DB->addconfig('autobackup','ftppass','');
$DB->addconfig('autobackup','ftpssl','0');
$DB->addconfig('autobackup','db_backup','1');
$DB->addconfig('autobackup','db_gz','1');
$DB->addconfig('autobackup','db_stats','0');
$DB->addconfig('autobackup','db_ftpsend','0');
$DB->addconfig('autobackup','db_ftppath','/iNET_LMS_DB_DUMP');
$DB->addconfig('autobackup','dir_ftpsend','0');
$DB->addconfig('autobackup','dir_ftpaction','update');
$DB->addconfig('autobackup','dir_local','');
$DB->addconfig('autobackup','dir_ftp','');

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013010700', 'dbvex'));

$DB->CommitTrans();
?>