<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2012 LMS Developers
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
 *  $Id$
 */

define('DBVERSION', '2012111100'); // here should be always the newest version of database!
//define('DBVEX','2013011000'); // wersja bazy LMS iNET
define('DBVEX','2013021000'); // wersja bazy LMS iNET
				 // it placed here to avoid read disk every time when we call this file.

/*
 * This file contains procedures for upgradeing automagicly database.
 */

if (!defined('NO_CHECK_UPGRADEDB'))
{

function getdir($pwd = './', $pattern = '^.*$')
{
	if ($handle = @opendir($pwd))
	{
		while (($file = readdir($handle)) !== FALSE)
			if(preg_match('/'.$pattern.'/',$file))
				$files[] = $file;
		closedir($handle);
	}
	return $files;
}

if($dbversion = $DB->GetOne('SELECT keyvalue FROM dbinfo WHERE keytype = ?',array('dbversion'))) {
	if(DBVERSION > $dbversion)
	{
		set_time_limit(0);
		$lastupgrade = $dbversion;
		$_dbtype = $CONFIG['database']['type'] == 'mysqli' ? 'mysql' : $CONFIG['database']['type'];

		$upgradelist = getdir(LIB_DIR.'/upgradedb/', '^'.$_dbtype.'.[0-9]{10}.php$');
		if(sizeof($upgradelist))
			foreach($upgradelist as $upgrade)
			{
				$upgradeversion = preg_replace('/^'.$_dbtype.'\.([0-9]{10})\.php$/','\1',$upgrade);

				if($upgradeversion > $dbversion && $upgradeversion <= DBVERSION)
					$pendingupgrades[] = $upgradeversion;
			}

		if(sizeof($pendingupgrades))
		{
			sort($pendingupgrades);
			foreach($pendingupgrades as $upgrade)
			{
				include(LIB_DIR.'/upgradedb/'.$_dbtype.'.'.$upgrade.'.php');
				if(!sizeof($DB->errors))
					$lastupgrade = $upgrade;
				else
					break;
			}
		}
	}
}

//$layout['dbschversion'] = isset($lastupgrade) ? $lastupgrade : DBVERSION;

$dbversion = $DB->GetOne('SELECT keyvalue FROM dbinfo WHERE keytype = ?',array('dbvex'));

if (!$dbversion) 
{
    $DB->Execute('INSERT INTO dbinfo (keytype, keyvalue) VALUES (?,?) ;',array('dbvex','2012120600'));
    $dbversion = '2012120600';
}

if($dbversion) 
{
	if(DBVEX > $dbversion)
	{
		set_time_limit(0);
		$lastupgrade = $dbversion;
		$_dbtype = $CONFIG['database']['type'] == 'mysqli' ? 'mysql' : $CONFIG['database']['type'];

		$upgradelist = getdir(LIB_DIR.'/upgradedb/', '^ex-'.$_dbtype.'.[0-9]{10}.php$');
		if(sizeof($upgradelist))
			foreach($upgradelist as $upgrade)
			{
				$upgradeversion = preg_replace('/^ex-'.$_dbtype.'\.([0-9]{10})\.php$/','\1',$upgrade);

				if($upgradeversion > $dbversion && $upgradeversion <= DBVEX)
					$pendingupgrades[] = $upgradeversion;
			}

		if(sizeof($pendingupgrades))
		{
			sort($pendingupgrades);
			foreach($pendingupgrades as $upgrade)
			{
				include(LIB_DIR.'/upgradedb/ex-'.$_dbtype.'.'.$upgrade.'.php');
				if(!sizeof($DB->errors))
					$lastupgrade = $upgrade;
				else
					break;
			}
		}
	}
}


$layout['dbschversion'] = isset($lastupgrade) ? $lastupgrade : DBVERSION;
$layout['dbschversionex'] = isset($lastupgrade) ? $lastupgrade : DBVEX;

} // end if defined no check
?>
