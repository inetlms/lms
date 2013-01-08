#!/usr/bin/php
<?php


/*
 * LMS iNET
 *
 *  (C) Copyright 2012 LMS iNET Developers
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
 *  $Id: v 1.00 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */


/*
 skrypt wystawia faktury VAT, domyślnie za poprzedni miesiąć
 przełącznik --leftmonth=M , M to ilość miesięcy wstecz za które mają być wystawione
 np. dzisiaj mamy sierpień
 --leftmonth=1 -> faktury będą za lipiec
 --leftmonth=3 -> faktury będą za maj
*/
?>
<?php
empty($_SERVER['SHELL']) && die('<br><Br>Sorry Winnetou, tylko powloka shell ;-)');
?>
<?php

ini_set('error_reporting', E_ALL&~E_NOTICE);

$parameters = array(
    'C:'	=> 'config-file:',
    'q'		=> 'quiet',
    'h'		=> 'help',
    'l:'	=> 'leftmonth:'
);

foreach ($parameters as $key => $val) {
	$val = preg_replace('/:/', '', $val);
	$newkey = preg_replace('/:/', '', $key);
	$short_to_longs[$newkey] = $val;
}
$options = array();
$options = getopt(implode('', array_keys($parameters)), $parameters);
foreach($short_to_longs as $short => $long)
	if (array_key_exists($short, $options))
	{
		$options[$long] = $options[$short];
		unset($options[$short]);
	}

if (array_key_exists('quiet', $options)) $quiet = true; else $quiet = false;

if (array_key_exists('help',$options))
{
print <<<EOF
iNET LMS Hiperus C5 Invoice
version 1.0.0

-C,                    alternatywny plik konfiguracyjny, -C /etc/lms/lms.ini
-h, --help             pomoc
-l,                    parametr M to numer miesiaca wstecz za ktory ma byc wystawiona faktura,
                       np. mamy maj a M=1 to f.vat będą za kwiecien, M=2 f.vat za marzec itd
                       Domyslnie wystawia za pełny poprzedni miesiac
                       użycie : -l 1
EOF;
    exit(0);
}

if (!$quiet)
{
	print <<<EOF

lms_hiperus_c5+invoice.php
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
define('COOKIE_FILE', tempnam('/tmp', 'lms-hiperus_invoice-cookies-'));

require_once(LIB_DIR.'/config.php');
require_once(LIB_DIR.'/language.php');
require_once(LIB_DIR.'/common.php');
require_once(LIB_DIR.'/definitions.php');

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

require(LIB_DIR.'/LMS.class.php');
$AUTH = NULL;
$LMS = new LMS($DB, $AUTH, $CONFIG);
$LMS->ui_lang = $_ui_language;
$LMS->lang = $_language;

require(LIB_DIR.'/LMS.Hiperus.class.php');

if (!array_key_exists('leftmonth',$options)) 
{
    $leftmonth = get_conf('hiperus_c5.leftmonth',1);
}
else
    $leftmonth = $options['leftmonth'];

if (empty($leftmonth)) $leftmonth = 1;

$userlist = $DB->GetAll('SELECT hc.id AS id, hc.ext_billing_id AS id_ext, '
			.$DB->Year('hc.create_date').' AS create_year, '
			.$DB->Month('hc.create_date').' AS create_month, '
			.$DB->Day('hc.create_date').' AS create_day '
			.' FROM hv_customers hc 
			JOIN hv_assign ha ON (ha.customerid = hc.id) 
			WHERE ha.keytype = ? AND ha.keyvalue = ? ',
			array('issue_invoice','2')
	    );
$count_u = sizeof($userlist);
$rok = intval(date('Y',strtotime('-'.$leftmonth.' month')));
$msc = intval(date('m',strtotime('-'.$leftmonth.' month')));
$taxid = $DB->GetOne('SELECT id FROM taxes WHERE value=? '.$DB->Limit('1').' ;',array(intval(get_conf('hiperus_c5.taxrate',get_conf('phpui.default_taxrate',23)))));

$mscstr = array('','Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień');
$vat = ((intval(get_conf('hiperus_c5.taxrate',get_conf('phpui.default_taxrate',23)))/100)+1);

for ($i=0; $i<$count_u; $i++)
{
	$userlist[$i]['rok'] = $rok;
	$userlist[$i]['miesiac'] = $msc;
	$userlist[$i]['sum_cost'] = 0;
	$userlist[$i]['terminal'] = array();
	$userlist[$i]['numberplanid'] = get_conf('hiperus_c5.numberplanid',$LMS->GetDefaultNumberPlanIDByCustomer($userlist[$i]['id_ext'],DOC_INVOICE));
	$terminalinfo = $HIPERUS->GetTerminalOneOrList(NULL,$userlist[$i]['id']);
	$count_t = sizeof($terminalinfo);
	for ($j=0; $j<$count_t; $j++)
	{
	    $userlist[$i]['terminal'][$j]['pricelist_name'] = $terminalinfo[$j]['pricelist_name'];
	    $userlist[$i]['terminal'][$j]['name'] = $terminalinfo[$j]['username'];
	    $userlist[$i]['terminal'][$j]['invoice_value'] = $DB->GetOne('SELECT invoice_value FROM hv_subscriptionlist WHERE id=? LIMIT 1 ;',array(intval($terminalinfo[$j]['id_subscription'])));
	    $cost = $HIPERUS->GetListBillingByCustomer2($userlist[$i]['id'],$rok,$msc,$terminalinfo[$j]['username']);
	    $userlist[$i]['terminal'][$j]['cost'] = ($cost[0]['cost'] ? $cost[0]['cost'] : 0);
	    $userlist[$i]['sum_cost'] += (($userlist[$i]['terminal'][$j]['invoice_value'] + $userlist[$i]['terminal'][$j]['cost'])*$vat);
	    $userlist[$i]['content'][] = array(
		'valuebrutto'		=> $userlist[$i]['terminal'][$j]['invoice_value'] * $vat,
		'taxid'			=> $taxid,
		'prodid'		=> get_conf('hiperus_c5.prodid',''),
		'jm'			=> get_conf('hiperus_c5.content','szt'),
		'count'			=> '1',
		'discount'		=> '0',
		'pdiscount'		=> '0',
		'vdiscount'		=> '0',
		'name'			=> 'Abonament VoIP : '.$userlist[$i]['terminal'][$j]['pricelist_name'].' za okres '.$mscstr[$msc].' '.$rok,
		'tariffid'		=> 0
	    );
	    $userlist[$i]['content'][] = array(
		'valuebrutto'		=> $userlist[$i]['terminal'][$j]['cost'] * $vat,
		'taxid'			=> $taxid,
		'prodid'		=> get_conf('hiperus_c5.prodid',''),
		'jm'			=> get_conf('hiperus_c5.content','szt'),
		'count'			=> '1',
		'discount'		=> '0',
		'pdiscount'		=> '0',
		'vdiscount'		=> '0',
		'name'			=> 'Koszt rozmów poza abonamentem '.$userlist[$i]['terminal'][$j]['pricelist_name'],
		'tariffid'		=> 0
	    );
	}
	unset($userlist[$i]['terminal']);
} //end for
    unset($vat);
    $tabtmp = array();
    for ($i=0; $i<$count_u; $i++) if ($userlist[$i]['sum_cost'] != 0) $tabtmp[] = $userlist[$i];
    unset($userlist);
    $userlist = $tabtmp;
    unset($tabtmp);
    $count_u = sizeof($userlist);
    $result = array(
	'loginform[login]' 	=> get_conf('hiperus_c5.lms_login'),
	'loginform[pwd]'	=> get_conf('hiperus_c5.lms_pass'),
	'users_data'		=> serialize($userlist)
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, get_conf('hiperus_c5.lms_url').'/?m=hv_newinvoice');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $result);
    curl_setopt($curl, CURLOPT_USERAGENT, "Private certification of software iNET LMS 1.0.0"); 
    curl_setopt($curl, CURLOPT_TIMEOUT,10);
    $info = curl_getinfo($curl);
    $page = curl_exec($curl);
    if (curl_error($curl)) echo "<br>Wysąpił błąd : ".curl_error($curl)."\n".get_conf('hiperus_c5.lms_url')."\n\n";
    curl_close($curl);
    unset($userlist);
    unset($curl);
    unset($result);
?>