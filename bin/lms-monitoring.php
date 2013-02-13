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
 *  $Id: monitnodelist.php,v 1.0.1 2013/01/20 22:01:35 Sylwester Kondracki Exp $
 */

ini_set('error_reporting', E_ALL&~E_NOTICE);

$parameters = array(
	'C:' => 'config-file:',
	'q' => 'quiet',
	'h' => 'help',
	't' => 'test',
	'a' => 'autotest',
);


foreach ($parameters as $key => $val) 
{
	$val = preg_replace('/:/', '', $val);
	$newkey = preg_replace('/:/', '', $key);
	$short_to_longs[$newkey] = $val;
}

$options = getopt(implode('', array_keys($parameters)), $parameters);

foreach ($short_to_longs as $short => $long)
	if (array_key_exists($short, $options))
	{
		$options[$long] = $options[$short];
		unset($options[$short]);
	}


if (array_key_exists('quiet', $options)) 
	$quiet = true; 
else 
	$quiet = false;

if (array_key_exists('test', $options)) 
	$test = true; 
else 
	$test = false;

if (array_key_exists('autotest', $options)) 
	$autotest = true; 
else 
	$autotest = false;

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
version 1.0.1
(C) 2012-2013 LMS iNET

EOF;
}

if (array_key_exists('config-file', $options) && is_readable($options['config-file']))
	$CONFIG_FILE = $options['config-file'];
elseif (is_readable('lms.ini'))
	$CONFIG_FILE = 'lms.ini';
elseif (is_readable('/etc/lms/lms.ini'))
	$CONFIG_FILE = '/etc/lms/lms.ini';

if (!$quiet) 
	echo "Using file ".$CONFIG_FILE." as config.\n\n";


if (!is_readable($CONFIG_FILE))
	die("Nie mozna odczytac pliku konfiguracyjnego file [".$CONFIG_FILE."]!\n");
	
$ch = curl_init();

if (!$ch)
	die("Blad krytyczny: Nie zainicjowano biblioteki curl !\n");

$CONFIG = (array) parse_ini_file($CONFIG_FILE, true);

$CONFIG['directories']['sys_dir'] = (!isset($CONFIG['directories']['sys_dir']) ? getcwd() : $CONFIG['directories']['sys_dir']);
$CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'/lib' : $CONFIG['directories']['lib_dir']);
$CONFIG['directories']['tmp_dir'] = (!isset($CONFIG['directories']['tmp_dir']) ? $CONFIG['directories']['sys_dir'].'/tmp' : $CONFIG['directories']['tmp_dir']);
$CONFIG['directories']['rrd_dir'] = (!isset($CONFIG['directories']['rrd_dir']) ? $CONFIG['directories']['sys_dir'].'/rrd' : $CONFIG['directories']['rrd_dir']);

define('SYS_DIR', $CONFIG['directories']['sys_dir']);
define('LIB_DIR', $CONFIG['directories']['lib_dir']);
define('TMP_DIR', $CONFIG['directories']['tmp_dir']);
define('RRD_DIR', $CONFIG['directories']['rrd_dir']);
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

$test_netdev = $test_nodes = $test_owner = $test_signal = TRUE;

$step_test_netdev = intval(get_conf('monit.step_test_netdev',5));
$step_test_nodes = intval(get_conf('monit.step_test_nodes',5));
$step_test_owner = intval(get_conf('monit.step_test_owner',10));
$step_test_signal = intval(get_conf('monit.step_test_signal',5));

if ($step_test_netdev < 5) 
	$step_test_netdev = 5;

if ($step_test_nodes < 5) 
	$step_test_nodes = 5;

if ($step_test_owner < 5) 
	$step_test_owner = 5;

if ($step_test_signal < 5) 
	$step_test_signal = 5;

define('STEP_NETDEV',$step_test_netdev);
define('STEP_NODES',$step_test_nodes);
define('STEP_OWNER',$step_test_owner);
define('STEP_SIGNAL',$step_test_signal);


if ($autotest) 
{
	if ( intval(date('i',time())) % STEP_NETDEV ) 
		$test_netdev = FALSE;
	
	if ( intval(date('i',time())) % STEP_NODES ) 
		$test_nodes = FALSE;
	
	if ( intval(date('i',time())) % STEP_OWNER ) 
		$test_owner = FALSE;
	
	if ( intval(date('i',time())) % STEP_SIGNAL ) 
		$test_signal = FALSE;
	
	if (!get_conf('monit.active_monitoring')) 
	{
		die("Monitoring jest wyłaczony\n\n");
	}
}

require_once(LIB_DIR.'/language.php');
include_once(LIB_DIR.'/definitions.php');
require_once(LIB_DIR.'/unstrip.php');
require_once(LIB_DIR.'/common.php');
require_once(LIB_DIR.'/LMS.class.php');
require_once(LIB_DIR.'/GaduGadu.class.php');
require_once(LIB_DIR.'/Routeros_api.class.php');

//$lms_url = (!empty($CONFIG['monit']['lms_url']) ? $CONFIG['monit']['lms_url'] : 'http://localhost/lms/');
//$lms_user = (!empty($CONFIG['monit']['lms_user']) ? $CONFIG['monit']['lms_user'] : '');
//$lms_password = (!empty($CONFIG['monit']['lms_password']) ? $CONFIG['monit']['lms_password'] : '');

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
$GG = new rfGG(GG_VER_77);
$MT = new routeros_api();

$currenttime = time(); 
$lasttesttime = $DB->GetOne('SELECT MAX(cdate) FROM monittime LIMIT 1;');

if ($tmp = $DB->GetCol('SELECT nodeid FROM monitwarn WHERE backtime=?',array(0)))
	$noping = implode(',',$tmp);
else 
	$noping = NULL;

if ($tmp = $DB->GetCol('SELECT ownid FROM monitwarn WHERE backtime = ?',array(0)))
	$nopingown = implode(',',$tmp);
else 
	$nopingown = NULL;


if (!$quiet) 
	printf("\n\n---------- TEST URZADZEN ZE STATUSEM DZIALAJACYCH ----------\n\n");

if ($test_netdev)
{
	$nodeslist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr, netdev 
				FROM  monit_vnodes 
				WHERE netdev = 1 AND pingtest = 1 '
				.(!empty($noping) ? 'AND id NOT IN ('.$noping.') ' : '')
				.' ORDER BY ipaddr ASC;'
	);
	
	if (get_conf('monit.netdev_test') && $nodeslist)
	{
		$count = sizeof($nodeslist);
		
		for ($i=0; $i<$count; $i++) 
		{
			$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
							.( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
			
			if ($nodeslist[$i]['ptime'] == '-1')
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
				$info .= " ".sprintf("%-10s",$nodeslist[$i]['test_type']);
				print($info."\n");
			}
		}
		
		if (!$test)
		{
			for ($i=0; $i<$count; $i++)
			{
				$ptime = str_replace(' ','',str_replace(',','.',sprintf("%.2f",$nodeslist[$i]['ptime'])));
				
				$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',
					    array($nodeslist[$i]['id'], 0, $currenttime, $ptime)
				);
				
				$LMS->RRD_UpdatePingFile('node.'.$nodeslist[$i]['id'],$ptime,$currenttime,STEP_NETDEV);
				$LMS->RRD_CreateSmallPingImage('node.'.$nodeslist[$i]['id'],'-1d','now');
				
				if ($nodeslist[$i]['ptime'] == '-1')
				{
					$mid = $DB->GetLastInsertID('monittime');
					$DB->Execute('INSERT INTO monitwarn (nodeid, ownid, monitid, cdate, backtime, sendwarn, sendback) VALUES (?,?,?,?,?,?,?) ;',
							array($nodeslist[$i]['id'],(!empty($netdevlist[$i]['netdev']) ? 1 : 0),$mid,$currenttime,0,0,0)
					);
				}
			}
		}
	}
}

if ($test_nodes)
{
	$nodeslist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr, netdev 
				FROM  monit_vnodes WHERE netdev=0 AND pingtest=1 '
				.(!empty($noping) ? 'AND id NOT IN ('.$noping.') ' : '')
				.' ORDER BY ipaddr ASC;'
	);
	
	if (get_conf('monit.nodes_test') && $nodeslist)
	{
		$count = sizeof($nodeslist);
		
		for ($i=0; $i<$count; $i++) 
		{
			$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
							.( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
			
			if ($nodeslist[$i]['ptime'] == '-1')
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
				$info .= " ".sprintf("%-10s",$nodeslist[$i]['test_type']);
				print($info."\n");
			}
		}
		
		if (!$test)
		{
			for ($i=0; $i<$count; $i++)
			{
				$ptime = str_replace(' ','',str_replace(',','.',sprintf("%.2f",$nodeslist[$i]['ptime'])));
				
				$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',
						array($nodeslist[$i]['id'], 0, $currenttime,$ptime)
				);
				
				$LMS->RRD_UpdatePingFile('node.'.$nodeslist[$i]['id'],$ptime,$currenttime, STEP_NODES);
				$LMS->RRD_CreateSmallPingImage('node.'.$nodeslist[$i]['id'],'-1d','now');
				
				if ($nodeslist[$i]['ptime'] == '-1') 
				{
					$mid = $DB->GetLastInsertID('monittime');
					$DB->Execute('INSERT INTO monitwarn (nodeid, ownid, monitid, cdate, backtime, sendwarn, sendback) VALUES (?,?,?,?,?,?,?) ;',
						array($nodeslist[$i]['id'],(!empty($netdevlist[$i]['netdev']) ? 1 : 0),$mid,$currenttime,0,0,0));
				}
			}
		}
	}
}


if ($test_owner)
{
	if (get_conf('monit.owner_test') && $ownlist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr FROM  monitown WHERE active=1 '.(!empty($nopingown) ? 'AND id NOT IN ('.$nopingown.') ' : '').' ;'))
	{
		$count = sizeof($ownlist);
		
		for ($i=0; $i<$count; $i++)
		{
			$ownlist[$i]['ptime'] = exec("$perlscript --ip=".$ownlist[$i]['ipaddr']." --type=".$ownlist[$i]['test_type']." "
						    .( !empty($ownlist[$i]['test_port']) ? " --port=".$ownlist[$i]['test_port']." " : " "));
			
			if ($ownlist[$i]['ptime'] == '-1')
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
				$info .= " ".sprintf("%-10s",$ownlist[$i]['test_type']);
				print($info."\n");
			}
		}
		
		if (!$test)
		{
			for ($i=0; $i<$count; $i++)
			{
				$ptime = str_replace(' ','',str_replace(',','.',sprintf("%.2f",$ownlist[$i]['ptime'])));
				
				$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',
					array(0, $ownlist[$i]['id'], $currenttime, $ptime));
				
				$LMS->RRD_UpdatePingFile('owner.'.$ownlist[$i]['id'],$ptime,$currenttime, STEP_OWNER);
				$LMS->RRD_CreateSmallPingImage('owner.'.$ownlist[$i]['id'],'-1d','now');
				
				if ($ownlist[$i]['ptime'] == '-1')
				{
					$mid = $DB->GetLastInsertID('monittime');
					$DB->Execute('INSERT INTO monitwarn (nodeid, ownid, monitid, cdate, backtime, sendwarn, sendback) VALUES (?,?,?,?,?,?,?) ;',
							array(0,$ownlist[$i]['id'],$mid,$currenttime,0,0,0));
				}
			}
		}
	}
}

if (!$quiet) printf("\n\n---------- TEST URZADZEN ZE STATUSEM TIMEOUT ----------\n\n");

if ($tmp = $DB->GetCol('SELECT nodeid FROM monitwarn WHERE backtime = ?;',array(0)))
	$noping = implode(',',$tmp);
else 
	$noping = NULL;

if ($tmp = $DB->GetCol('SELECT ownid FROM monitwarn WHERE backtime = ?;',array(0)))
	$nopingown = implode(',',$tmp);
else 
	$nopingown = NULL;


if ($test_netdev)
{
	if ( get_conf('monit.netdev_test') && $nodeslist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr, netdev FROM  monit_vnodes WHERE netdev=1 AND pingtest=1 '.(!empty($noping) ? 'AND id IN ('.$noping.') ' : ' AND id=0 ').' ;'))
	{
		$count = sizeof($nodeslist);
		
		for ($i=0; $i<$count; $i++)
		{
			$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
							.( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
			
			if ($nodeslist[$i]['ptime'] == '-1')
			{
				usleep(300000);
				$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
								.( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
			}
			
			if (!$quiet)
			{
				if ($nodeslist[$i]['ptime'] == '-1') 
					$strtmp = 'timeout'; 
				else 
					$strtmp = $nodeslist[$i]['ptime']." ms";
				
				$info = "";
				$info .= sprintf("%-16s","Urz. sieciowe");
				$info .= sprintf("%-16s",$nodeslist[$i]['ipaddr']);
				$info .= " czas: ";
				$info .= sprintf("%12s",$strtmp);
				$info .= " ".sprintf("%-10s",$nodeslist[$i]['test_type']);
				print($info."\n");
			}
		}
		
		if (!$test)
		{
			for ($i=0; $i<$count; $i++)
			{
				$tmp = $DB->GetRow('SELECT monitid, cdate FROM monitwarn WHERE backtime=0 AND nodeid=? LIMIT 1;',array($nodeslist[$i]['id']));
				
				if ($nodeslist[$i]['ptime'] > '0')
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
				
				$ptime = str_replace(' ','',str_replace(',','.',sprintf("%.2f",$nodeslist[$i]['ptime'])));
				$LMS->RRD_UpdatePingFile('node.'.$nodeslist[$i]['id'],$ptime,$currenttime, STEP_NODES);
				$LMS->RRD_CreateSmallPingImage('node.'.$nodeslist[$i]['id'],'-1d','now');
			}
		}
	}
}

if ($test_nodes)
{
	if (get_conf('monit.nodes_test') && $nodeslist = $DB->GetAll('SELECT id, test_type, test_port, ipaddr, netdev FROM  monit_vnodes WHERE netdev=0 AND pingtest=1 '.(!empty($noping) ? 'AND id IN ('.$noping.') ' : ' AND id=0 ').' ;'))
	{
		$count = sizeof($nodeslist);
		
		for ($i=0; $i<$count; $i++)
		{
			$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
							.( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
			
			if ($nodeslist[$i]['ptime'] == '-1')
			{
				usleep(300000);
				$nodeslist[$i]['ptime'] = exec("$perlscript --ip=".$nodeslist[$i]['ipaddr']." --type=".$nodeslist[$i]['test_type']." "
								.( !empty($nodeslist[$i]['test_port']) ? " --port=".$nodeslist[$i]['test_port']." " : " "));
			}
			
			if (!$quiet)
			{
				if ($nodeslist[$i]['ptime'] == '-1') 
					$strtmp = 'timeout'; 
				else 
					$strtmp = $nodeslist[$i]['ptime']." ms";
				
				$info = "";
				$info .= sprintf("%-16s","Urz. klienta");
				$info .= sprintf("%-16s",$nodeslist[$i]['ipaddr']);
				$info .= " czas: ";
				$info .= sprintf("%12s",$strtmp);
				$info .= " ".sprintf("%-10s",$nodeslist[$i]['test_type']);
				print($info."\n");
			}
		}
		
		if (!$test)
		{
			for ($i=0; $i<$count; $i++)
			{
				$tmp = $DB->GetRow('SELECT monitid, cdate FROM monitwarn WHERE backtime=0 AND nodeid=? LIMIT 1;',array($nodeslist[$i]['id']));
				
				if ($nodeslist[$i]['ptime'] > '0')
				{
					if ($tmp['cdate'] == $currenttime)
					{
						$DB->Execute('UPDATE monitwarn SET backtime=? WHERE nodeid = ? AND cdate=?',array($currenttime,$nodeslist[$i]['id'],$currenttime));
						$DB->Execute('UPDATE monittime SET ptime=?, warn_timeout=1 WHERE id=?',array($nodeslist[$i]['ptime'],$tmp['monitid']));
					}
					else 
					{
						$DB->Execute('UPDATE monitwarn SET backtime=? WHERE monitid=?',array($currenttime,$tmp['monitid']));
						$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',
							array($nodeslist[$i]['id'],0,$currenttime,$nodeslist[$i]['ptime']));
						
						$ptime = str_replace(' ','',str_replace(',','.',sprintf("%.2f",$nodeslist[$i]['ptime'])));
						$LMS->RRD_UpdatePingFile('node.'.$nodeslist[$i]['id'],$ptime,$currenttime, STEP_NODES);
					}
				}
				else
				{
					if ($tmp['cdate'] != $currenttime) 
					{
						$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',
							array($nodeslist[$i]['id'],0,$currenttime,$nodeslist[$i]['ptime']));
						
						$ptime = str_replace(' ','',str_replace(',','.',sprintf("%.2f",$nodeslist[$i]['ptime'])));
						$LMS->RRD_UpdatePingFile('node.'.$nodeslist[$i]['id'],$ptime,$currenttime, STEP_NODES);
					}
				}
				
				$LMS->RRD_CreateSmallPingImage('node.'.$nodeslist[$i]['id'],'-1d','now');
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
			
			if ($ownlist[$i]['ptime'] == '-1')
			{
				usleep(300000);
				$ownlist[$i]['ptime'] = exec("$perlscript --ip=".$ownlist[$i]['ipaddr']." --type=".$ownlist[$i]['test_type']." "
								.( !empty($ownlist[$i]['test_port']) ? " --port=".$ownlist[$i]['test_port']." " : " "));
			}
			
			if (!$quiet)
			{
				if ($ownlist[$i]['ptime'] == '-1') 
					$strtmp = 'timeout'; 
				else 
					$strtmp = $ownlist[$i]['ptime']." ms";
				
				$info = "";
				$info .= sprintf("%-17s","Urz. własne");
				$info .= sprintf("%-16s",$ownlist[$i]['ipaddr']);
				$info .= " czas: ";
				$info .= sprintf("%12s",$strtmp);
				$info .= " ".sprintf("%-10s",$ownlist[$i]['test_type']);
				print($info."\n");
			}
		}
		
		if (!$test)
		{
			for ($i=0; $i<$count; $i++)
			{
				$tmp = $DB->GetRow('SELECT monitid, cdate FROM monitwarn WHERE backtime=0 AND ownid=? LIMIT 1;',array($ownlist[$i]['id']));
				
				if ($ownlist[$i]['ptime'] > '0')
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
						$ptime = str_replace(' ','',str_replace(',','.',sprintf("%.2f",$ownlist[$i]['ptime'])));
						$LMS->RRD_UpdatePingFile('owner.'.$ownlist[$i]['id'],$ptime,$currenttime, STEP_OWNER);
					}
				}
				else
				{
					if ($tmp['cdate'] != $currenttime)
					{
						$DB->Execute('INSERT INTO monittime (nodeid, ownid, cdate, ptime) VALUES (?,?,?,?) ;',array(0,$ownlist[$i]['id'],$currenttime,$ownlist[$i]['ptime']));
						$ptime = str_replace(' ','',str_replace(',','.',sprintf("%.2f",$ownlist[$i]['ptime'])));
						$LMS->RRD_UpdatePingFile('owner.'.$ownlist[$i]['id'],$ptime,$currenttime, STEP_OWNER);
					}
				}
				$LMS->RRD_CreateSmallPingImage('owner.'.$ownlist[$i]['id'],'-1d','now');
			}
		}
	}
}


if (get_conf('monit.signal_test') && $test_signal)
{
	$no_signal_test = $DB->GetCol('SELECT UPPER(mac) AS mac FROM monit_vnodes WHERE signaltest = 0 AND netdev = 0 ;');
	if (!$no_signal_test) 
		$no_signal_test = array();
	
	$n_list = $DB->GetAll('SELECT id, UPPER(mac) AS mac, ipaddr FROM monit_vnodes WHERE signaltest = 1 ;');
	
	$n_list_ip = array();
	
	if ($n_list) 
	{
		$tmp = array();
		
		for ($i=0; $i<sizeof($n_list); $i++)
		{
			$tmp[$n_list[$i]['id']] = $n_list[$i]['mac'];
			$n_list_ip[$n_list[$i]['id']] = $n_list[$i]['ipaddr'];
		}
		
		$n_list = $tmp;
		unset($tmp);
	}
	else 
		$n_list = array();
	$nd_list = $DB->GetAll('SELECT mv.id AS nid, mv.ipaddr AS ipaddr, 
		    nd.monit_nastype AS nastype, nd.monit_login AS login, nd.monit_passwd AS passwd, nd.monit_port AS port, n.netdev AS netdevid 
		    FROM monit_vnodes mv 
		    JOIN nodes n ON (n.id = mv.id) 
		    JOIN netdevices nd ON (nd.id = n.netdev) 
		    WHERE mv.netdev = 1 AND mv.signaltest = 1 AND (nd.monit_nastype = 1 OR nd.monit_nastype = 14 OR nd.monit_nastype = 15) ;'
	);
	if ($nd_list) 
	{
		$n_count = sizeof($n_list);
		$no_count = sizeof($no_signal_test);
		$nd_count = sizeof($nd_list);
		
		for ($i = 0; $i < $nd_count; $i++)
		{
			$data = array();
			if (empty($nd_list[$i]['port'])) $nd_list[$i]['port'] = '8728';
			if (empty($nd_list[$i]['login'])) $nd_list[$i]['login'] = 'public';
			$nastype = intval($nd_list[$i]['nastype']);
			
			if ( ($nastype === 1) || ($nastype === 15)) 
			{
				$hostek = $nd_list[$i]['ipaddr'];
				
				if (!empty($nd_list['port'])) $hostek .= ':'.$nd_list[$i]['port'];
				
				$result = $LMS->WIFI_GetAllSignal($hostek,$nd_list[$i]['login']);
				
				if ($result)
					$data = $result;
				else
					$data = array();
			} 
			
			if ($nastype === 14 ) 
			{
				$MT->debug = false;
				$MT->port = $nd_list[$i]['port'];
				
				if ($connect = $MT->connect($nd_list[$i]['ipaddr'],$nd_list[$i]['login'],$nd_list[$i]['passwd'])) 
				{
					$MT->write('/interface/wireless/registration-table/print');
					$result = $MT->read();
					$MT->disconnect();
					
					if ($result)
					{
						for ($k = 0; $k < sizeof($result); $k++)
						{
							if (isset($result[$k]['signal-strength']) && !empty($result[$k]['signal-strength'])) 
								$tab[$k]['rx_signal'] = substr($result[$k]['signal-strength'],0,strpos($result[$k]['signal-strength'],"dBm"));
							else
								$tab[$k]['rx_signal'] = 0;
							
							if (isset($result[$k]['tx-signal-strength']) && !empty($result[$k]['tx-signal-strength'])) 
								$tab[$k]['tx_signal'] = $result[$k]['tx-signal-strength'];
							else
								$tab[$k]['tx_signal'] = 0;
							
							if (isset($result[$k]['rx-rate']) && !empty($result[$k]['rx-rate'])) 
								$tab[$k]['rx_rate'] = str_replace('Mbps','',$result[$k]['rx-rate']);
							else
								$tab[$k]['rx_rate'] = 0;
							
							if (isset($result[$k]['tx-rate']) && !empty($result[$k]['tx-rate'])) 
								$tab[$k]['tx_rate'] = str_replace('Mbps','',$result[$k]['tx-rate']);
							else
								$tab[$k]['tx_rate'] = 0;
							
							if (isset($result[$k]['packets']) && !empty($result[$k]['packets'])) 
							{
								$tmp = explode(',',$result[$k]['packets']);
								$tab[$k]['tx_packets'] = $tmp[0];
								if (isset($tmp[1])) 
									$tab[$k]['rx_packets'] = $tmp[1];
								else
									$tab[$k]['rx_packets'] = 0;
							}
							else
								$tab[$k]['tx_packets'] = $tab[$k]['rx_packets'] = 0;
							
							if (isset($result[$k]['bytes']) && !empty($result[$k]['bytes'])) 
							{
								$tmp = explode(',',$result[$k]['bytes']);
								$tab[$k]['tx_bytes'] = $tmp[0];
								if (isset($tmp[1])) 
									$tab[$k]['rx_bytes'] = $tmp[1];
								else
									$tab[$k]['rx_bytes'] = 0;
							}
							else
								$tab[$k]['tx_bytes'] = $tab[$k]['rx_bytes'] = 0;
							
							if (isset($result[$k]['signal-to-noise']) && !empty($result[$k]['signal-to-noise'])) 
								$tab[$k]['signal_noise'] = $result[$k]['signal-to-noise'];
							else
								$tab[$k]['signal_noise'] = 0;
							
							if (isset($result[$k]['tx-ccq']) && !empty($result[$k]['tx-ccq'])) 
								$tab[$k]['tx_ccq'] = $result[$k]['tx-ccq'];
							else
								$tab[$k]['tx_ccq'] = 0;
							
							if (isset($result[$k]['rx-ccq']) && !empty($result[$k]['rx-ccq'])) 
								$tab[$k]['rx_ccq'] = $result[$k]['rx-ccq'];
							else
								$tab[$k]['rx_ccq'] = 0;
							
							if (isset($result[$k]['ack-timeout']) && !empty($result[$k]['ack-timeout'])) 
								$tab[$k]['ack_timeout'] = $result[$k]['ack-timeout'];
							else
								$tab[$k]['ack_timeout'] = 0;
							
							if (isset($result[$k]['mac-address']) && !empty($result[$k]['mac-address'])) 
								$tab[$k]['mac'] = strtoupper($result[$k]['mac-address']);
							else
								$tab[$k]['mac'] = '0';
						}
						
						$data = $tab;
						unset($tab);
					}
					else
					{
						$data = array();
					}
				}
				else 
					$data = array();
			}
			if (!empty($data))
			{
				$d_count = sizeof($data);
				for ($j = 0; $j<$d_count; $j++)
				if (!in_array($data[$j]['mac'],$no_signal_test))
				{
					$idek = intval(array_search($data[$j]['mac'],$n_list));
					
					if (!$DB->GetOne('SELECT 1 FROM monitnodes WHERE id = ? '.$DB->Limit(1).' ;',array($idek)))
					{
						$idek = $DB->GetOne('SELECT nodeid FROM macs WHERE UPPER(mac) = ? LIMIT 1;',array(strtoupper($data[$j]['mac'])));
						if (!empty($idek))
						{
							$LMS->SetMonit($idek,1);
							$DB->Execute('UPDATE monitnodes SET pingtest = ? , signaltest = ? WHERE id = ? ;',array(0,1,$idek));
						}
						
					}
					if (!empty($idek))
					{
						$rx_signal = ceil(abs(intval(str_replace(' ','',str_replace(',','.',($data[$j]['rx_signal'] ? $data[$j]['rx_signal'] : 0))))));
						$tx_rate = ceil(intval(str_replace(' ','',str_replace(',','.',($data[$j]['tx_rate'] ? $data[$j]['tx_rate'] : 0)))));
						$rx_rate = ceil(abs(intval(str_replace(' ','',str_replace(',','.',($data[$j]['rx_rate'] ? $data[$j]['rx_rate'] : 0))))));
						$tx_packets = str_replace(' ','',$data[$j]['tx_packets']);
						$rx_packets = str_replace(' ','',$data[$j]['rx_packets']);
						$tx_bytes = str_replace(' ','',$data[$j]['tx_bytes']);
						$rx_bytes = str_replace(' ','',$data[$j]['rx_bytes']);
						
						$LMS->RRD_UpdateSignalFile('node.'.$idek,$rx_signal,$tx_rate,$rx_rate,$currenttime, STEP_SIGNAL);
						$LMS->RRD_UpdateTransferFile('node.'.$idek,$tx_packets,$rx_packets,$tx_bytes,$rx_bytes,$currenttime,STEP_SIGNAL);
						
						if ($nd_list[$i]['nastype'] == '14')
						{
							$tx_signal = ceil(abs(intval(str_replace(' ','',str_replace(',','.',($data[$j]['tx_signal'] ? $data[$j]['tx_signal'] : 0))))));
							$signal_noise = ceil(intval(str_replace(' ','',str_replace(',','.',($data[$j]['signal_noise'] ? $data[$j]['signal_noise'] : 0)))));
							$tx_ccq = ceil(intval(str_replace(' ','',str_replace(',','.',($data[$j]['tx_ccq'] ? $data[$j]['tx_ccq'] : 0)))));
							$rx_ccq = ceil(intval(str_replace(' ','',str_replace(',','.',($data[$j]['rx_ccq'] ? $data[$j]['rx_ccq'] : 0)))));
							$ack_timeout = ceil(intval(str_replace(' ','',str_replace(',','.',($data[$j]['ack_timeout'] ? $data[$j]['ack_timeout'] : 0)))));
							
							$DB->Execute('INSERT INTO monitsignal (cdate, nodeid, rx_signal, tx_signal, 
								    signal_noise, tx_rate, rx_rate, rx_ccq, tx_ccq, ack_timeout) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ',
								array($currenttime, $idek, $rx_signal, $tx_signal, $signal_noise, $tx_rate, $rx_rate, $rx_ccq, $tx_ccq, $ack_timeout ));
							
							$LMS->RRD_UpdateSignalExpandedFile('node.'.$idek, $tx_signal, $signal_noise, $tx_ccq, $rx_ccq, $ack_timeout, $currenttime, STEP_SIGNAL);
							$LMS->RRD_CreateSmallSignalImage('node.'.$idek,'-1d','now',NULL,true);
						}
						else
						{
							$LMS->RRD_CreateSmallSignalImage('node.'.$idek,'-1d','now', NULL, false);
							$DB->Execute('INSERT INTO monitsignal (cdate, nodeid, rx_signal, tx_rate, rx_rate) VALUES (?, ?, ?, ?, ?) ',
								array($currenttime, $idek, $rx_signal,$tx_rate,$rx_rate));
						}
						
						$DB->Execute('UPDATE monitnodes SET src_netdev = ? WHERE id = ? ;',array($nd_list[$i]['netdevid'],$idek));
					}
				}
			}
		}
	} 
} 

$data = strtotime(date('Y/m/d',time()));

if ( (time() >= ($data + 7200)) && (time() <= ($data + 10800)) )
{
	$leftday = time() - 691200;
	
	if ($ideki = $DB->GetCol('SELECT v.id AS id FROM monit_vnodes v JOIN monittime t ON (t.nodeid = v.id) WHERE ownid = 0 AND t.cdate < ? ',array($leftday)))
	{
		$DB->Execute('DELETE FROM monitsignal WHERE nodeid IN ('.implode(',',$ideki).')');
		$DB->Execute('DELETE FROM monitwarn WHERE nodeid IN ('.implode(',',$ideki).')');
		$DB->Execute('DELETE FROM monittime WHERE nodeid IN ('.implode(',',$ideki).')');
	}
	
	if ($ideki = $DB->GetCol('SELECT ownid FROM monittime WHERE ownid !=0 AND cdate < ?',array($leftday)))
	{
		$DB->Execute('DELETE FROM monitwarn WHERE ownid IN ('.implode(',',$ideki).')');
		$DB->Execute('DELETE FROM monittime WHERE ownid IN ('.implode(',',$ideki).')');
	}
}

if (get_conf('monit.beep','0') && ($test_netdev || $test_nodes || $test_owner || $test_signal)) exec("beep");

if (!$quiet) 
	printf("\n\nKONIEC TESTU\n\n");

?>