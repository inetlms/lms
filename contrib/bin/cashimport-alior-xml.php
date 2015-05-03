#!/usr/bin/php
<?php

/* iNET LMS 15.01.26
 * import płatności Alior Bank w formacie XML
 *
 * skrypt powstał na zlecenie firmy OPTIMA Dawid Foszcz
 *
*/

empty($_SERVER['SHELL']) && die('<br><Br>Sorry Winnetou, tylko powloka shell ;-)');
ini_set('error_reporting', E_ALL&~E_NOTICE);


$cashimport = array(
    'server'		=> '',					// host smtp
    '7zpasswd'		=> '',					// hasło do archiwum
    'username'		=> '',					// nazwa użytkownika email
    'password'		=> '',					// hasło do skrzynki pocztowej
    'use_seen_flag'	=> true,
//    'copy_dir'		=> '/serwer/wyciagi/',		// ścieżka gdzie zapisywać kopię wyciągów
    'date_regexp'	=> '/([0-9]{4})([0-9]{2})([0-9]{2})/', //rrmmdd
);

$parameters = array(
	'config-file:',
	'quiet',
	'help',
);

foreach ($parameters as $key => $val) 
{
    $val = preg_replace('/:/', '', $val);
    $newkey = preg_replace('/:/', '', $key);
    $short_to_longs[$newkey] = $val;
}

$options = getopt(implode('', array_keys($parameters)), $parameters);

foreach ($short_to_longs as $short => $long) if(array_key_exists($short, $options))
{
    $options[$long] = $options[$short];
    unset($options[$short]);
}

function chk_opt($key)
{
    global $options;
    if (array_key_exists($key,$options))
	return true;
    else
    {
	echo"\nNieznany przelacznik : ".$key."\nOperacja przerwana !!!\nprosze uzyc przelacznika --help\n\n";die;
    }
}

function get_opt($key,$def=NULL)
{
    global $options;
    if (chk_opt($key))
    {
	if(!is_null($options[$key]))
	    return $options[$key];
	else 
	    return $def;
    }
    else 
	return $def;
}

function xml2array($xmlContent, $out = array()) 
{
    $xmlObject = is_object($xmlContent) ? $xmlContent : simplexml_load_string($xmlContent);
    foreach ((array) $xmlObject as $index => $node)
	$out[$index] = (is_object($node) || is_array($node)) ? xml2array($node) : $node;
    return $out;
}


function cp1250_to_utf8($str)
{
    $str = str_replace("=B9","ą",$str);
    $str = str_replace("=E6","ć",$str);
    $str = str_replace("=EA","ę",$str);
    $str = str_replace("=B3","ł",$str);
    $str = str_replace("=F1","ń",$str);
    $str = str_replace("=F3","ó",$str);
    $str = str_replace("=9C","ś",$str);
    $str = str_replace("=9F","ź",$str);
    $str = str_replace("=BF","ż",$str);
    $str = str_replace("=A5","Ą",$str);
    $str = str_replace("=C6","Ć",$str);
    $str = str_replace("=CA","Ę",$str);
    $str = str_replace("=A3","Ł",$str);
    $str = str_replace("=D1","Ń",$str);
    $str = str_replace("=D3","Ó",$str);
    $str = str_replace("=8C","Ś",$str);
    $str = str_replace("=8F","Ź",$str);
    $str = str_replace("=AF","Ż",$str);
    $str = str_replace('ţ',' ',$str);
    return $str;
}


if (array_key_exists('quiet', $options)) $quiet = true; else $quiet = false;
if (array_key_exists('test', $options)) $test = true; else $test = false;
if (array_key_exists('notmail', $options)) $notmail = true; else $notmail = false;

if (array_key_exists('help', $options))
{
	print <<<EOF


--config-file=/etc/lms/lms.ini      alternatywny plik konfiguracyjny (default: /etc/lms/lms.ini);
--help                              wyswietlenie pomocy i zakonczenie;
--quiet                             nie wyswietla dodatkowych informacji;

EOF;
	exit(0);
}


if (array_key_exists('input', $options) && is_readable($options['input'])) $INPUT_FILE = $options['input']; else $INPUT_FILE = NULL;

if (array_key_exists('config-file', $options) && is_readable($options['config-file'])) $CONFIG_FILE = $options['config-file'];
elseif (is_readable('lms.ini')) $CONFIG_FILE = 'lms.ini';
elseif (is_readable('/etc/lms/lms.ini')) $CONFIG_FILE = '/etc/lms/lms.ini';
else die('Nie mozna odczytac pliku lms.ini');

if (!$quiet) {echo "Uzywam pliku konfiguracyjnego ".$CONFIG_FILE."\n\n";}

if (!is_readable($CONFIG_FILE)) die("Nie mozna odczytac pliku konfiguracyjnego file [".$CONFIG_FILE."]!\n");

$CONFIG = (array) parse_ini_file($CONFIG_FILE, true);
$CONFIG['directories']['sys_dir'] = (!isset($CONFIG['directories']['sys_dir']) ? getcwd() : $CONFIG['directories']['sys_dir']);
$CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'/lib' : $CONFIG['directories']['lib_dir']);
$CONFIG['directories']['tmp_dir'] = (!isset($CONFIG['directories']['tmp_dir']) ? $CONFIG['directories']['sys_dir'].'/tmp' : $CONFIG['directories']['tmp_dir']);

define('SYS_DIR', $CONFIG['directories']['sys_dir']);
define('TMP_DIR', $CONFIG['directories']['tmp_dir']);
define('LIB_DIR', $CONFIG['directories']['lib_dir']);
define('WYC_DIR','/serwer/wyciagi/');

require_once(LIB_DIR.'/config.php');

$_DBTYPE = $CONFIG['database']['type'];
$_DBHOST = $CONFIG['database']['host'];
$_DBUSER = $CONFIG['database']['user'];
$_DBPASS = $CONFIG['database']['password'];
$_DBNAME = $CONFIG['database']['database'];

require(LIB_DIR.'/LMSDB.php');

$DB = DBInit($_DBTYPE, $_DBHOST, $_DBUSER, $_DBPASS, $_DBNAME);

if (!$DB)
{
    die("Fatal error: cannot connect to database!\n");
}

if ($cfg = $DB->GetAll('SELECT section, var, value FROM uiconfig WHERE disabled=0')) 
    foreach($cfg as $row) $CONFIG[$row['section']][$row['var']] = $row['value'];

require_once(LIB_DIR.'/language.php');
include_once(LIB_DIR.'/definitions.php');
require_once(LIB_DIR.'/unstrip.php');
require_once(LIB_DIR.'/common.php');
require_once(LIB_DIR.'/LMS.class.php');

$AUTH = NULL;
$LMS = new LMS($DB, $AUTH, $CONFIG);
$LMS->ui_lang = $_ui_language;
$LMS->lang = $_language;

if (!$quite) echo "\n-- START --\n\n";

$filelist = array();


if (!$notmail)
{
    $ih = @imap_open("{" . $cashimport['server'] . "}INBOX", $cashimport['username'], $cashimport['password']);
    
    if (!$ih)
	die("Cannot connect to mail server!\n");
    
    $posts = imap_search($ih, $cashimport['use_seen_flag'] ? 'UNSEEN' : 'ALL');
    
    if (!empty($posts))
    foreach ($posts as $postid) 
    {
	    $imap_structure = imap_fetchstructure($ih, $postid);
	    if ($imap_structure->type == 1)
	    {
		$parts = $imap_structure->parts;
		foreach ($parts as $partid => $part )
		{
		    if ($part->type == 1 && count($part->parts))
		    {
			foreach ($part->parts as $multipartid => $multipart) 
			{
			    if ($multipart->type ==3 && strpos($multipart->dparameters[0]->value, '.7z'))
			    {
				$attachments[$partid][filename] = $multipart->dparameters[0]->value;
				$attachments[$partid][bytes] = $multipart->bytes;
				$attachments[$partid][pos] = ($partid+1).".".($multipartid+1);
				$attachments[$partid][id] = $postid;
			    }
			}
		    } 
		    else 
		    {
			if ($part->type == 3 && strpos($part->dparameters[0]->value, '.7z'))
			{
			    $attachments[$partid][filename] = $part->dparameters[0]->value;
			    $attachments[$partid][bytes] = $part->bytes;
			    $attachments[$partid][pos] = $partid+1;
			    $attachments[$partid][id] = $postid;
			}
		    }
		}
	    }
	    
	    if ($attachments)
	    {
		foreach ($attachments as $attachment)
		{
		    $filename = '/tmp/'.$attachment[filename];
		    
		    $filelist[] = str_replace('7z','xml',$filename);
		    
		    $file = fopen($filename,'w');
		    stream_filter_append($file,'convert.base64-decode',STREAM_FILTER_WRITE);
		    if (chkconfig($cashimport['use_seen_flag']))
			imap_savebody ($ih, $file, $attachment[id], $attachment[pos] );
		    else
			imap_savebody ($ih, $file, $attachment[id], $attachment[pos], FT_PEEK);
		    exec('7za e '. $filename .' -p'. $cashimport['7zpasswd'] .' -o/tmp -y', $debug);
		    fclose($file);
		    
		}
	    }
	
    }
    
    imap_close($ih);
}


if (!empty($filelist)) {
    
    $count = sizeof($filelist);
    $cash = array();
    $time = time();
    
    if (isset($cashimport['copy_dir']) && !empty($cashimport['copy_dir']))  // tworzymy kopię wyciągów
    {
	for ($i=0; $i<$count;$i++) 
	{
	    $fi = fopen($filelist[$i],"r");
	    $fo = fopen($cashimport['copy_dir'].'/'.str_replace('/tmp/','',$filelist[$i]),"w");
	    while (!feof($fi)) {
		fwrite($fo,fgets($fi));
	    }
	    fclose($fo);
	    fclose($fi);
	}
    } // end backup files
    
    for ($i=0; $i<$count; $i++) // pobierani info z plików
    {
	$cash = array();
	
	if ($tmp = simplexml_load_file($filelist[$i])) 
	{
	    
	    $DB->Execute('INSERT INTO sourcefiles (name,idate,userid) VALUES (?,?,?);',array(str_replace('/tmp/','',$filelist[$i]),$time,NULL));
	    $sourcefileid = $DB->getLastInsertId('sourcefiles');
	    
	    $wplat = $tmp->{'FOOTER'}->{'TRNCRT'};
	    
	    if ( $wplat == 1) 
	    {
		$xml = xml2array($tmp->{'TRANLIST'}->{'TRANSACTION'});
		
		if (preg_match($cashimport['date_regexp'],$xml['TRANDT'],$date)) 
		    {
			$unixtime = mktime(0,0,0,$date[2],$date[3],$date[1]);
		    }
			else $unixtime = time();
		
		$cash[] = array(
			'cid'		=> intval(substr($xml['MPTID'],'-8')),
			'date' 		=> $xml['TRANDT'],
			'unixtime'	=> $unixtime,
			'account' 	=> $xml['MPTID'],
			'value' 	=> str_replace(',','.',trim($xml['AMT']/100)),
			'sendname' 	=> cp1250_to_utf8($xml['SNDNAME']),
			'title'		=> cp1250_to_utf8($xml['TITLE']),
			'sourcefileid'	=> $sourcefileid,
		);
	    
	    } else {
	    
		for ($i=0; $i<($wplat); $i++) {
		    
		    $xml = xml2array($tmp->{'TRANLIST'}->{'TRANSACTION'}->{$i});
		    
		    if (preg_match($cashimport['date_regexp'],$xml['TRANDT'],$date)) 
		    {
			$unixtime = mktime(0,0,0,$date[2],$date[3],$date[1]);
		    }
			else $unixtime = time();
		    
		    $cash[] = array(
			'cid'		=> intval(substr($xml['MPTID'],'-8')),
			'date' 		=> $xml['TRANDT'],
			'unixtime'	=> $unixtime,
			'account' 	=> $xml['MPTID'],
			'value' 	=> str_replace(',','.',trim($xml['AMT']/100)),
			'sendname' 	=> cp1250_to_utf8($xml['SNDNAME']),
			'title'		=> cp1250_to_utf8($xml['TITLE']),
			'sourcefileid'	=> $sourcefileid,
		    );
		}
	    }
	}
	
	
	if (!empty($cash)) {
		
		for ($j=0; $j<sizeof($cash); $j++) 
		{
			$hash = md5($cash[$j]['cid'].$cash[$j]['unixtime'].$cash[$j]['value'].$cash[$j]['sendname'].$cash[$j]['title']);
			
			$customerid = $DB->getOne('SELECT id FROM customers WHERE id = ? LIMIT 1;',array($cash[$j]['cid']));
			
			$DB->Execute('INSERT INTO cashimport (date, value, customer, customerid, description, hash, sourceid, sourcefileid) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ;',
				array($cash[$j]['unixtime'],$cash[$j]['value'],$cash[$j]['sendname'],$customerid,$cash[$j]['title'],$hash,NULL,$cash[$j]['sourcefileid'],));
			$cashid = $DB->GetLastInsertId('cashimport');
			
			if ($customerid) // rozliczamy konto klienta
			{
				$balance = array(
					'time' 			=> $cash[$j]['unixtime'],
					'userid' 		=> 0,
					'value'			=> $cash[$j]['value'],
					'type'			=> 1,
					'taxid'			=> 0,
					'customerid'		=> $customerid,
					'comment'		=> $cash[$j]['sendname'].'<br>'.$cash[$j]['title'],
					'docid'			=> 0,
					'itemid'		=> 0,
					'importid'		=> $cashid,
					'sourceid'		=> $cash[$j]['sourcefileid'],
				);
				
				if ($LMS->addBalance($balance))
				    $DB->Execute('UPDATE cashimport SET closed = 1  WHERE id = ? ;',array($cashid));
				    
				    $balance = $LMS->getcustomerbalance($customerid);
				    
				    if ($balance >=0) 
					$DB->Execute('UPDATE nodes SET warning = 0, blockade = 0 WHERE ownerid = ? ;',array($customerid));
			}
		}
	}
    }
}


?>
