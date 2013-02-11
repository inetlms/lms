#!/usr/bin/php
<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2012-2013 iNET LMS Developers
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
 *  $Id: v 1.00 2013/01/07 02:35:35 Sylwester Kondracki Exp $
 */

empty($_SERVER['SHELL']) && die('<br><Br>Sorry Winnetou, tylko powloka shell ;-)');
ini_set('error_reporting', E_ALL&~E_NOTICE);

$parameters = array(
	'C:' => 'config-file:',
	'q' => 'quiet',
	'h' => 'help',
);

foreach ($parameters as $key => $val) {
	$val = preg_replace('/:/', '', $val);
	$newkey = preg_replace('/:/', '', $key);
	$short_to_longs[$newkey] = $val;
}
$options = getopt(implode('', array_keys($parameters)), $parameters);
foreach($short_to_longs as $short => $long)
	if (array_key_exists($short, $options))
	{
		$options[$long] = $options[$short];
		unset($options[$short]);
	}

if (array_key_exists('quiet', $options)) $quiet = true; else $quiet = false;

if (array_key_exists('help', $options))
{
	print <<<EOF
	
lms-autobackup.php
version 1.0.0
(C) 2012-2013 iNET LMS 

-C, --config-file=/etc/lms/lms.ini      alternatywny plik konfiguracyjny (default: /etc/lms/lms.ini);
-h, --help                              wyswietlenie pomocy i zakonczenie;
-q, --quiet                             nie wyswietla dodatkowych informacji;

Wszystkie opcje konfiguracyjne należy ustawiać w UI !!!

EOF;
	exit(0);
}

if (!$quiet)
{
	print <<<EOF

(c) 2012-2013 iNET LMS <lms-autobackup.php>

EOF;
}

if (array_key_exists('config-file', $options) && is_readable($options['config-file']))
	$CONFIG_FILE = $options['config-file'];
elseif (is_readable('lms.ini'))
	$CONFIG_FILE = 'lms.ini';
elseif (is_readable('/etc/lms/lms.ini'))
	$CONFIG_FILE = '/etc/lms/lms.ini';

if (!$quiet) 
	echo "Uzywam pliku konfiguracyjnego ".$CONFIG_FILE."\n\n";


if (!is_readable($CONFIG_FILE))
	die("Nie mozna odczytac pliku konfiguracyjnego file [".$CONFIG_FILE."]!\n");

$CONFIG = (array) parse_ini_file($CONFIG_FILE, true);

$CONFIG['directories']['sys_dir'] = (!isset($CONFIG['directories']['sys_dir']) ? getcwd() : $CONFIG['directories']['sys_dir']);
$CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'/lib' : $CONFIG['directories']['lib_dir']);
$CONFIG['directories']['doc_dir'] = (!isset($CONFIG['directories']['doc_dir']) ? $CONFIG['directories']['sys_dir'].'/documents' : $CONFIG['directories']['doc_dir']);
$CONFIG['directories']['modules_dir'] = (!isset($CONFIG['directories']['modules_dir']) ? $CONFIG['directories']['sys_dir'].'/modules' : $CONFIG['directories']['modules_dir']);
$CONFIG['directories']['backup_dir'] = (!isset($CONFIG['directories']['backup_dir']) ? $CONFIG['directories']['sys_dir'].'/backups' : $CONFIG['directories']['backup_dir']);
$CONFIG['directories']['config_templates_dir'] = (!isset($CONFIG['directories']['config_templates_dir']) ? $CONFIG['directories']['sys_dir'].'/config_templates' : $CONFIG['directories']['config_templates_dir']);
$CONFIG['directories']['smarty_compile_dir'] = (!isset($CONFIG['directories']['smarty_compile_dir']) ? $CONFIG['directories']['sys_dir'].'/templates_c' : $CONFIG['directories']['smarty_compile_dir']);
$CONFIG['directories']['smarty_templates_dir'] = (!isset($CONFIG['directories']['smarty_templates_dir']) ? $CONFIG['directories']['sys_dir'].'/templates' : $CONFIG['directories']['smarty_templates_dir']);

define('SYS_DIR', $CONFIG['directories']['sys_dir']);
define('LIB_DIR', $CONFIG['directories']['lib_dir']);
define('DOC_DIR', $CONFIG['directories']['doc_dir']);
define('BACKUP_DIR', $CONFIG['directories']['backup_dir']);
define('MODULES_DIR', $CONFIG['directories']['modules_dir']);
define('SMARTY_COMPILE_DIR', $CONFIG['directories']['smarty_compile_dir']);
define('SMARTY_TEMPLATES_DIR', $CONFIG['directories']['smarty_templates_dir']);

define('USER_AGENT', "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");

define('COOKIE_FILE', tempnam('/tmp', 'iNET-lms-xxx-cookies-'));

require_once(LIB_DIR.'/config.php');

require(LIB_DIR.'/LMSDB.php');

$DB = DBInit($CONFIG['database']['type'], $CONFIG['database']['host'], $CONFIG['database']['user'],  $CONFIG['database']['password'], $CONFIG['database']['database']);

if(!$DB)
{
	die("Fatal error: cannot connect to database!\n");
}

if($cfg = $DB->GetAll('SELECT section, var, value FROM uiconfig WHERE disabled=0'))
	foreach($cfg as $row)
		$CONFIG[$row['section']][$row['var']] = $row['value'];

$AUTH->id = 0;

//ma dołączyć plik ale nie podności bazy 
define('NO_CHECK_UPGRADEDB',true);
include_once(LIB_DIR.'/upgradedb.php');

include_once(LIB_DIR.'/language.php');
include_once(LIB_DIR.'/definitions.php');
include_once(LIB_DIR.'/unstrip.php');
include_once(LIB_DIR.'/common.php');
include_once(LIB_DIR.'/LMS.class.php');
include_once(LIB_DIR.'/FTP.class.php');

if (get_conf('phpui.syslog_level')) define('SYSLOG',TRUE); else define('SYSLOG',FALSE);

$LMS = new LMS($DB, $AUTH, $CONFIG);
$LMS->ui_lang = $_ui_language;
$LMS->lang = $_language;

$currenttime = time(); // akualny czas

$FTP = new FTP(get_conf('autobackup.ftphost'),get_conf('autobackup.ftpuser'),get_conf('autobackup.ftppass'),21,120);

$akcja = get_conf('autobackup.dir_ftpaction','update');
$akcja = strtolower($akcja);
if (!in_array($akcja,array('skip','replace','update'))) $akcja = 'update';

$ssl = (get_conf('autobackup.ftpssl') ? true : false);

/************************************************************
*                BAZA DANYCH                                *
************************************************************/

if (get_conf('autobackup.db_backup')) 
{
    if (!$quiet) print("Tworzę kopię bazy danych \n");

    if (!$filename_sql = $LMS->DataBaseCreate(get_conf('autobackup.db_gz',true),get_conf('autobackup.db_stats',false)))
	$filename_sql = false;

    if (!$quiet) print("Utworzono kopię bazy danych ".$filename_sql."\n");
    
    if (get_conf('autobackup.db_ftpsend') && $filename_sql && $FTP->connect($ssl)) // wysyłamy baze na serwer
    {
	$filename = str_replace(get_conf('directories.backup_dir').'/','',$filename_sql);
	$tmp = str_replace('.sql','',$filename_sql);
//	$tmp = .'
	
	$FTP->chdir(get_conf('autobackup.db_ftppath'),true);
	
	if (!$quiet) echo "Tworze kopie bazy na FTP: ".$filename."\n";
	$result = $FTP->upload($filename_sql,$filename,'auto',$akcja);
	
	$FTP->close();
	
	if (SYSLOG) {
	    if ($result) addlogs('lms-autobackup -> Utworzono kopię bazy '.$filename.' na serwerze FTP: '.get_conf('autobackup.ftphost'),'e=add;m=admin;');
	    else addlogs('lms-autobackup -> Nie utworzono kopii bazy '.$filename.' na serwerze FTP: '.get_conf('autobackup.ftphost'),'e=err;m=admin;');
	}
    }
} // end if db backup


/************************************************
*             KATALOGI                          *
************************************************/

if (get_conf('autobackup.dir_ftpsend') && get_conf('autobackup.dir_local','') != ''  && $FTP->connect($ssl))
{

    $ftp_dirs = explode(',',get_conf('autobackup.dir_ftp'));
    $local_dirs = explode(',',get_conf('autobackup.dir_local'));
    
    for ($j=0;$j<sizeof($ftp_dirs);$j++)
    {
	$FTP->chdir("/");
	$tmpdir = explode("/",$ftp_dirs[$j]);
	if (sizeof($tmpdir) > 1) 
	{
	    $tmp = '/';
	    for ($i=1;$i<sizeof($tmpdir);$i++) {
		$tmp .= $tmpdir[$i].'/';
		$FTP->chdir($tmp,true);
	    }
	}
	
	$result = $FTP->mirror($local_dirs[$j].'/',$ftp_dirs[$j].'/',$akcja);
	if (SYSLOG) {
	    if ($result) addlogs('lms-autobackup -> Utworzono kopię katalogu '.$local_dirs[$j].' na serwerze FTP: '.get_conf('autobackup.ftphost'),'e=add;m=admin;');
	    else addlogs('lms-autobackup -> Nie utworzono kopii katalogu '.$local_dirs[$j].' na serwerze FTP: '.get_conf('autobackup.ftphost'),'e=err;m=admin;');
	}
    }
    
    $FTP->close();
    
    print_r($DB->errors);
}
?>