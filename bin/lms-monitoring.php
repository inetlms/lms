#!/usr/bin/php
<?php

/*
 * LMS iNET
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
 */

ini_set('error_reporting', E_ALL&~E_NOTICE);

$parameters = array(
	'C:' => 'config-file:',
	'q' => 'quiet',
	'h' => 'help',
	't' => 'test',
	'a' => 'autotest',
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
if (array_key_exists('test', $options)) $test = true; else $test = false;
if (array_key_exists('autotest', $options)) $autotest = true; else $autotest = false;

if (array_key_exists('help', $options))
{
	print <<<EOF
lms-monitoring.php
version 1.0.3
(C) 2012-2013 LMS iNET

-C, --config-file=/etc/lms/lms.ini      alternate config file (default: /etc/lms/lms.ini);
-h, --help                              wyswietlenie pomocy i zakonczenie;
-t, --test                              wykonuje test bez zapisu informacji do bazy danych;
-q, --quiet                             nie wyswietla dodatkowych informacji;

EOF;
	exit(0);
}

if (!$quiet)
{
	print <<<EOF

lms-monitoring.php
version 1.0.0
(C) 2012-2013 LMS iNET

EOF;
}

if (array_key_exists('config-file', $options))
	$CONFIG_FILE = $options['config-file'];
else
	$CONFIG_FILE = '/etc/lms/lms.ini';

if (!$quiet) {
	echo "Using file ".$CONFIG_FILE." as config.\n\n";
}

if (!is_readable($CONFIG_FILE))
	die("Nie mozna odczytac pliku konfiguracyjnego file [".$CONFIG_FILE."]!\n");
	
$ch = curl_init();
if (!$ch)
	die("Blad krytyczny: Nie zainicjowano biblioteki curl !\n");

$CONFIG = (array) parse_ini_file($CONFIG_FILE, true);

$CONFIG['directories']['sys_dir'] = (!isset($CONFIG['directories']['sys_dir']) ? getcwd() : $CONFIG['directories']['sys_dir']);
$CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'/lib' : $CONFIG['directories']['lib_dir']);

define('SYS_DIR', $CONFIG['directories']['sys_dir']);
define('LIB_DIR', $CONFIG['directories']['lib_dir']);
define('USER_AGENT', "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
define('COOKIE_FILE', tempnam('/tmp', 'lms-monitoring-cookies-'));
require_once(LIB_DIR.'/config.php');

$_DBTYPE = $CONFIG['database']['type'];
$_DBHOST = $CONFIG['database']['host'];
$_DBUSER = $CONFIG['database']['user'];
$_DBPASS = $CONFIG['database']['password'];
$_DBNAME = $CONFIG['database']['database'];

require(LIB_DIR.'/LMSDB.php');

$DB = DBInit($_DBTYPE, $_DBHOST, $_DBUSER, $_DBPASS, $_DBNAME);

if(!$DB)
{
	die("Fatal error: cannot connect to database!\n");
}


if($cfg = $DB->GetAll('SELECT section, var, value FROM uiconfig WHERE disabled=0'))
	foreach($cfg as $row)
		$CONFIG[$row['section']][$row['var']] = $row['value'];

$test_netdev = $test_nodes = $test_owner = TRUE;

if ($autotest) 
{
//    if ( intval(date('i',time())) % intval(get_conf('monit.step_test')) ) die;
    if ( intval(date('i',time())) % intval(get_conf('monit.step_test_netdev')) ) $test_netdev = FALSE;
    if ( intval(date('i',time())) % intval(get_conf('monit.step_test_nodes')) ) $test_nodes = FALSE;
    if ( intval(date('i',time())) % intval(get_conf('monit.step_test_owner')) ) $test_owner = FALSE;
    if (!get_conf('monit.active_monitoring')) die("Monitoring jest wyłaczony\n\n");
}

require_once(LIB_DIR.'/language.php');
include_once(LIB_DIR.'/definitions.php');
require_once(LIB_DIR.'/unstrip.php');
require_once(LIB_DIR.'/common.php');
require_once(LIB_DIR.'/LMS.class.php');
//require_once(LIB_DIR.'/GaduGadu.class.php');

$lms_url = (!empty($CONFIG['monit']['lms_url']) ? $CONFIG['monit']['lms_url'] : 'http://localhost/lms/');
$lms_user = (!empty($CONFIG['monit']['lms_user']) ? $CONFIG['monit']['lms_user'] : '');
$lms_password = (!empty($CONFIG['monit']['lms_password']) ? $CONFIG['monit']['lms_password'] : '');

if (!empty($CONFIG['monit']['smtp_host'])) $CONFIG['mail']['smtp_host'] = $CONFIG['monit']['smtp_host'];
if (!empty($CONFIG['monit']['smtp_port'])) $CONFIG['mail']['smtp_port'] = $CONFIG['monit']['smtp_port'];
if (!empty($CONFIG['monit']['smtp_user'])) $CONFIG['mail']['smtp_username'] = $CONFIG['monit']['smtp_user'];
if (!empty($CONFIG['monit']['smtp_pass'])) $CONFIG['mail']['smtp_password'] = $CONFIG['monit']['smtp_pass'];
if (!empty($CONFIG['monit']['smtp_auth'])) $CONFIG['mail']['smtp_auth_type'] = $CONFIG['monit']['smtp_auth'];

$perlscript = get_conf('monit.test_script_dir','/usr/local/sbin/lms-monitoring.pl');

$AUTH = NULL;
$LMS = new LMS($DB, $AUTH, $CONFIG);
$LMS->ui_lang = $_ui_language;
$LMS->lang = $_language;
//$GG = new rfGG(GG_VER_77);

$currenttime = time(); // akualny czas
$lasttesttime = $DB->GetOne('SELECT MAX(cdate) FROM monittime LIMIT 1;'); // czas ostatniego testu
$mtnastype = $DB->getone('SELECT id FROM nastypes WHERE name = ? LIMIT 1;',array('mikrotik_api'));

// *********************************************************************************************************************************************************************************************************
// sprawdzamy które urządzenia mają już status padniętych
if ($tmp = $DB->GetCol('SELECT nodeid FROM monitwarn WHERE backtime=?',array(0)))
    $noping = implode(',',$tmp);
else $noping = NULL;

if ($tmp = $DB->GetCol('SELECT ownid FROM monitwarn WHERE backtime = ?',array(0)))
    $nopingown = implode(',',$tmp);
else $nopingown = NULL;



if (!$quiet) printf("\n\n---------- TEST URZADZEN ZE STATUSEM DZIALAJACYCH ----------\n\n");
// testujemy urządzenia sieciowe które działają

if ($test_netdev)
{
    $nodeslist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr, netdev FROM  monit_vnodes WHERE netdev=1 '.(!empty($noping) ? 'AND id NOT IN ('.$noping.') ' : '').' ORDER BY ipaddr ASC;');
    if ( get_conf('monit.netdev_test') && $nodeslist)
    {
	$count = sizeof($nodeslist);
	for ($i=0; $i<$count; $i++) 
	{
	    $nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
		    .( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
	    if ($nodeslist[$i]['ptime'] == '-1') // jak nie odpowiada to czekamy 3 sek i ponawiamy test
	    {
		$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
		    .( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
	    }
	    
	    if (!$quiet)
	    {
		if ($nodeslist[$i]['ptime'] == '-1') $strtmp = 'timeout'; else $strtmp = $nodeslist[$i]['ptime']." ms";
		
		$info = "";
		$info .= sprintf("%-16s","Urz. sieciowe");
		$info .= sprintf("%-16s",$nodeslist[$i]['ipaddr']);
		$info .= " czas: ";
		$info .= sprintf("%12s",$strtmp);
		$info .= " protokol: ";
		$info .= sprintf("%-10s",$nodeslist[$i]['test_type']);
		print($info."\n");
	    }
	}
	
	if (!$test)
	{
	    $DB->BeginTrans();
	    for ($i=0; $i<$count; $i++)
	    {
		$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array($nodeslist[$i]['id'],0,$currenttime,$nodeslist[$i]['ptime']));
		
		if ($nodeslist[$i]['ptime'] == '-1') // info że coś padło
		{
		    $mid = $DB->GetLastInsertID('monittime');
		    $DB->Execute('INSERT INTO monitwarn (nodeid, ownid, monitid, cdate, backtime, sendwarn, sendback) VALUES (?,?,?,?,?,?,?) ;',
			array($nodeslist[$i]['id'],(!empty($netdevlist[$i]['netdev']) ? 1 : 0),$mid,$currenttime,0,0,0));
		}
	    }
	    $DB->Committrans();
	}
    }
}
// testujemy urządzenia klientow które działają
if ($test_nodes)
{
    $nodeslist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr, netdev FROM  monit_vnodes WHERE netdev=0 '.(!empty($noping) ? 'AND id NOT IN ('.$noping.') ' : '').' ORDER BY ipaddr ASC;');
    if ( get_conf('monit.nodes_test') && $nodeslist)
    {
	$count = sizeof($nodeslist);
	for ($i=0; $i<$count; $i++) 
	{
	    $nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
		    .( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
	    if ($nodeslist[$i]['ptime'] == '-1') // jak nie odpowiada to czekamy 3 sek i ponawiamy test
	    {
		$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
		    .( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
	    }
	    
	    if (!$quiet)
	    {
		if ($nodeslist[$i]['ptime'] == '-1') $strtmp = 'timeout'; else $strtmp = $nodeslist[$i]['ptime']." ms";
		
		$info = "";
		$info .= sprintf("%-16s","Urz. klienta");
		$info .= sprintf("%-16s",$nodeslist[$i]['ipaddr']);
		$info .= " czas: ";
		$info .= sprintf("%12s",$strtmp);
		$info .= " protokol: ";
		$info .= sprintf("%-10s",$nodeslist[$i]['test_type']);
		print($info."\n");
	    }
	}
	
	if (!$test)
	{
	    $DB->BeginTrans();
	    for ($i=0; $i<$count; $i++)
	    {
		$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array($nodeslist[$i]['id'],0,$currenttime,$nodeslist[$i]['ptime']));
		
		if ($nodeslist[$i]['ptime'] == '-1') // info że coś padło
		{
		    $mid = $DB->GetLastInsertID('monittime');
		    $DB->Execute('INSERT INTO monitwarn (nodeid, ownid, monitid, cdate, backtime, sendwarn, sendback) VALUES (?,?,?,?,?,?,?) ;',
			array($nodeslist[$i]['id'],(!empty($netdevlist[$i]['netdev']) ? 1 : 0),$mid,$currenttime,0,0,0));
		}
	    }
	    $DB->Committrans();
	}
    }
}


// testujemy własne urządzenia które działają
if ($test_owner)
{
    if (get_conf('monit.owner_test') && $ownlist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr FROM  monitown WHERE active=1 '.(!empty($nopingown) ? 'AND id NOT IN ('.$nopingown.') ' : '').' ;'))
    {
	$count = sizeof($ownlist);
	for ($i=0; $i<$count; $i++)
	{
	    $ownlist[$i]['ptime'] = exec("$perlscript --ip=".$ownlist[$i]['ipaddr']." --type=".$ownlist[$i]['test_type']." "
		    .( !empty($ownlist[$i]['test_port']) ? " --port=".$ownlist[$i]['test_port']." " : " "));
	    if ($ownlist[$i]['ptime'] == '-1') // jak nie odpowiada to czekamy 3 sek i ponawiamy test
	    {
		$ownlist[$i]['ptime'] = exec("$perlscript --ip=".$ownlist[$i]['ipaddr']." --type=".$ownlist[$i]['test_type']." "
		    .( !empty($ownlist[$i]['test_port']) ? " --port=".$ownlist[$i]['test_port']." " : " "));
	    }
	    if (!$quiet)
	    {
		if ($ownlist[$i]['ptime'] == '-1') $strtmp = 'timeout'; else $strtmp = $ownlist[$i]['ptime']." ms";
		$info = "";
		$info .= sprintf("%-17s","Urz. własne");
		$info .= sprintf("%-16s",$ownlist[$i]['ipaddr']);
		$info .= " czas: ";
		$info .= sprintf("%12s",$strtmp);
		$info .= " protokol: ";
		$info .= sprintf("%-10s",$ownlist[$i]['test_type']);
		print($info."\n");
	    }
	}
	
	if (!$test)
	{
	    $DB->BeginTrans();
	    for ($i=0; $i<$count; $i++)
	    {
		$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array(0,$ownlist[$i]['id'],$currenttime,$ownlist[$i]['ptime']));
	        if ($ownlist[$i]['ptime'] == '-1') // info że coś padło
		{
		    $mid = $DB->GetLastInsertID('monittime');
		    $DB->Execute('INSERT INTO monitwarn (nodeid, ownid, monitid, cdate, backtime, sendwarn, sendback) VALUES (?,?,?,?,?,?,?) ;',
				array(0,$ownlist[$i]['id'],$mid,$currenttime,0,0,0));
		}
	    
	    }
	    $DB->Committrans();
	}
    }
}

if (!$quiet) printf("\n\n---------- TEST URZADZEN ZE STATUSEM TIMEOUT ----------\n\n");
// *********************************************************************************************************************************************************************************************************
// testujemy urządzenia które mają już status padniętych z poprzedniego testu

if ($tmp = $DB->GetCol('SELECT nodeid FROM monitwarn WHERE backtime = ?;',array(0)))
    $noping = implode(',',$tmp);
else $noping = NULL;

if ($tmp = $DB->GetCol('SELECT ownid FROM monitwarn WHERE backtime = ?;',array(0)))
    $nopingown = implode(',',$tmp);
else $nopingown = NULL;


if ($test_netdev)
{
    // testujemy urządzenia sieciowe 
    if ( get_conf('monit.netdev_test') && $nodeslist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr, netdev FROM  monit_vnodes WHERE netdev=1 '.(!empty($noping) ? 'AND id IN ('.$noping.') ' : ' AND id=0 ').' ;'))
    {
	$count = sizeof($nodeslist);
	for ($i=0; $i<$count; $i++)
	{
	    $nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
		    .( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
	    if ($nodeslist[$i]['ptime'] == '-1') // jak nie odpowiada to czekamy 1 sek i ponawiamy test
	    {
		sleep(1);
		$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
		    .( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
	    }
	    if (!$quiet)
	    {
		if ($nodeslist[$i]['ptime'] == '-1') $strtmp = 'timeout'; else $strtmp = $nodeslist[$i]['ptime']." ms";
		$info = "";
		$info .= sprintf("%-16s","Urz. sieciowe");
		$info .= sprintf("%-16s",$nodeslist[$i]['ipaddr']);
		$info .= " czas: ";
		$info .= sprintf("%12s",$strtmp);
		$info .= " protokol: ";
		$info .= sprintf("%-10s",$nodeslist[$i]['test_type']);
		print($info."\n");
	    }
	}
	if (!$test)
	{
	    $DB->BeginTrans();
	    for ($i=0; $i<$count; $i++)
	    {
		$tmp = $DB->GetRow('SELECT monitid, cdate FROM monitwarn WHERE backtime=0 AND nodeid=? LIMIT 1;',array($nodeslist[$i]['id']));
		
		
		if ($nodeslist[$i]['ptime'] > '0') // info że coś wstało
		{
		    if ($tmp['cdate'] == $currenttime)
		    {
			$DB->Execute('UPDATE monitwarn SET backtime=? WHERE nodeid = ? AND cdate=?',array($currenttime,$nodeslist[$i]['id'],$currenttime));
			$DB->Execute('UPDATE monittime SET ptime=?, warn_timeout=1 WHERE id=?',array($nodeslist[$i]['ptime'],$tmp['monitid']));
		    }
		    else 
		    {
			$DB->Execute('UPDATE monitwarn SET backtime=? WHERE monitid=?',array($currenttime,$tmp['monitid']));
			$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array($nodeslist[$i]['id'],0,$currenttime,$nodeslist[$i]['ptime']));
		    }
		}
		else
		{
		    if ($tmp['cdate'] != $currenttime)
		    $DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array($nodeslist[$i]['id'],0,$currenttime,$nodeslist[$i]['ptime']));
		}
	    }
	    $DB->Committrans();
	}
    }
}

if ($test_nodes)
{
    // testujemy urządzenia klientów
    if ( get_conf('monit.nodes_test') && $nodeslist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr, netdev FROM  monit_vnodes WHERE netdev=0 '.(!empty($noping) ? 'AND id IN ('.$noping.') ' : ' AND id=0 ').' ;'))
    {
	$count = sizeof($nodeslist);
	for ($i=0; $i<$count; $i++)
	{
	    $nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
		    .( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
	    if ($nodeslist[$i]['ptime'] == '-1') // jak nie odpowiada to czekamy 1 sek i ponawiamy test
	    {
		sleep(1);
		$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
		    .( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
	    }
	    if (!$quiet)
	    {
		if ($nodeslist[$i]['ptime'] == '-1') $strtmp = 'timeout'; else $strtmp = $nodeslist[$i]['ptime']." ms";
		$info = "";
		$info .= sprintf("%-16s","Urz. klienta");
		$info .= sprintf("%-16s",$nodeslist[$i]['ipaddr']);
		$info .= " czas: ";
		$info .= sprintf("%12s",$strtmp);
		$info .= " protokol: ";
		$info .= sprintf("%-10s",$nodeslist[$i]['test_type']);
		print($info."\n");
	    }
	}
	if (!$test)
	{
	    $DB->BeginTrans();
	    for ($i=0; $i<$count; $i++)
	    {
		$tmp = $DB->GetRow('SELECT monitid, cdate FROM monitwarn WHERE backtime=0 AND nodeid=? LIMIT 1;',array($nodeslist[$i]['id']));
		
		
		if ($nodeslist[$i]['ptime'] > '0') // info że coś wstało
		{
		    if ($tmp['cdate'] == $currenttime)
		    {
			$DB->Execute('UPDATE monitwarn SET backtime=? WHERE nodeid = ? AND cdate=?',array($currenttime,$nodeslist[$i]['id'],$currenttime));
			$DB->Execute('UPDATE monittime SET ptime=?, warn_timeout=1 WHERE id=?',array($nodeslist[$i]['ptime'],$tmp['monitid']));
		    }
		    else 
		    {
			$DB->Execute('UPDATE monitwarn SET backtime=? WHERE monitid=?',array($currenttime,$tmp['monitid']));
			$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array($nodeslist[$i]['id'],0,$currenttime,$nodeslist[$i]['ptime']));
		    }
		}
		else
		{
		    if ($tmp['cdate'] != $currenttime)
		    $DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array($nodeslist[$i]['id'],0,$currenttime,$nodeslist[$i]['ptime']));
		}
	    }
	    $DB->Committrans();
	}
    }
}


if ($test_owner)
{
    if (get_conf('monit.owner_test') && $ownlist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr FROM  monitown WHERE 1=1 '.(!empty($nopingown) ? 'AND id IN ('.$nopingown.') ' : ' AND id=0').' ;'))
    {
	$count = sizeof($ownlist);
	for ($i=0; $i<$count; $i++)
	{
	    $ownlist[$i]['ptime'] = exec("$perlscript --ip=".$ownlist[$i]['ipaddr']." --type=".$ownlist[$i]['test_type']." "
		    .( !empty($ownlist[$i]['test_port']) ? " --port=".$ownlist[$i]['test_port']." " : " "));
	    
	    
	    if ($ownlist[$i]['ptime'] == '-1') // jak nie odpowiada to czekamy 1 sek i ponawiamy test
	    {
		sleep(1);
		$ownlist[$i]['ptime'] = exec("$perlscript --ip=".$ownlist[$i]['ipaddr']." --type=".$ownlist[$i]['test_type']." "
		    .( !empty($ownlist[$i]['test_port']) ? " --port=".$ownlist[$i]['test_port']." " : " "));
	    }
	    if (!$quiet)
	    {
		if ($ownlist[$i]['ptime'] == '-1') $strtmp = 'timeout'; else $strtmp = $ownlist[$i]['ptime']." ms";
		$info = "";
		$info .= sprintf("%-17s","Urz. własne");
		$info .= sprintf("%-16s",$ownlist[$i]['ipaddr']);
		$info .= " czas: ";
		$info .= sprintf("%12s",$strtmp);
		$info .= " protokol: ";
		$info .= sprintf("%-10s",$ownlist[$i]['test_type']);
		print($info."\n");
	    }
	}
	
	if (!$test)
	{
	    $DB->BeginTrans();
	    for ($i=0; $i<$count; $i++)
	    {
		$tmp = $DB->GetRow('SELECT monitid, cdate FROM monitwarn WHERE backtime=0 AND ownid=? LIMIT 1;',array($ownlist[$i]['id']));
		if ($ownlist[$i]['ptime'] > '0') // info że coś wstało
		{
		    if ($tmp['cdate'] == $currenttime)
		    {
			$DB->Execute('UPDATE monitwarn SET backtime=? WHERE ownid = ? AND cdate=?',array($currenttime,$ownlist[$i]['id'],$currenttime));
		        $DB->Execute('UPDATE monittime SET ptime=?, warn_timeout=1 WHERE id=?',array($ownlist[$i]['ptime'],$tmp['monitid']));
		    }
		    else 
		    {
		        $DB->Execute('UPDATE monitwarn SET backtime=? WHERE monitid=?',array($currenttime,$tmp['monitid']));
		        $DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array(0,$ownlist[$i]['id'],$currenttime,$ownlist[$i]['ptime']));
		    }
		}
		else
		{
		    if ($tmp['cdate'] != $currenttime)
		    $DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array(0,$ownlist[$i]['id'],$currenttime,$ownlist[$i]['ptime']));
		}
	    }
	    $DB->Committrans();
	}
    }
}
if (!empty($DB->errors)) print_r($DB->errors);
if (!$quiet) printf("\n\nKONIEC TESTU\n\n");
?>