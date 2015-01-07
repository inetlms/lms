#!/usr/bin/php
<?php

/*
 *  iNET LMS 
 *
 *  (C) Copyright 2012-2015 iNET LMS Developers
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
 *  $Id: lms-invoice2pdf.php,v 1.0 2015/01/05 07:43:16 Sylwester Kondracki Exp $
 *
 *  Skrypt generuje pliki z fakturami pdf na serwer
 */


ini_set('error_reporting', E_ALL&~E_NOTICE);

$parameters = array(
	'config-file:', // /etc/lms/lms.ini
	'docid:',
	'quiet',
	'fakedate:',
	'help',
	'proforma',
	'fromdate:',
	'todate:',
	'leftmonth',
	'leftday',
	'replace',
);

$argv = $_SERVER['argv'];
array_shift($argv);
$param = $args = array();

if (sizeof($argv) != 0)
{
    $txt = "\n";
    $blad = false;
    for ($i=0;$i<sizeof($parameters);$i++)
	$param[] = str_replace(':','',$parameters[$i]);
    
    for ($i=0;$i<sizeof($argv);$i++)
	$args[] = str_replace('--','',$argv[$i]);
    
    for ($i=0; $i<sizeof($args); $i++) {
	list($par,$val) = sscanf($args[$i], '%[^=]=%s');
	if (!in_array($par,$param)) {
	    $txt .= "Nieznany parametr : ".$par."\n";
	    $blad = true;
	}
    }
    
    if ($blad) {
	$txt .= "\nPrzerywam wykonywanie skryptu !!!\n\n";
	print($txt);
	die;
    }
}
unset($argv);
unset($args);
unset($param);

$options = getopt(implode('',$parameters),$parameters);

if (array_key_exists('config-file', $options) && is_readable($options['config-file']))
    $CONFIG_FILE = $options['config-file'];

include('/etc/lms/init_lms.php');


if (array_key_exists('help', $options))
{
print <<<EOF


lms-invoice2pdf.php
(C) 2012-2015 iNET LMS Developers

--config-file=/etc/lms/lms.ini		alternatywny plik konfiguracyjny file (default: /etc/lms/lms.ini);
--quiet					tryb cichy
--proforma				genreuje pliki pdf dla faktur proforma, def.: false
--replace				nadpisuje istniejące pliki pdf, Def.: false
--docid=				wygeneruj plik dla konkretnego dokumentu, podajemy ID dokumentu
--leftday				wygenerowanie faktur z dnia poprzedniego
--leftmonth				generuje pliki pdf dla faktur wystwationych w poprzednim miesiącu
--fromdate=				data początkowa (YYYY/MM/DD)
--todate=				data końcowa (YYYY/MM/DD)
--fakedate=				wygeneruj wszystkie pliki z danego dnia (YYYY/MM/DD)


brak paramerów powoduje wygenerowanie plików z dnia dzisiejszego
fakedate i docid nie stosować razem !!!


EOF;
exit(0);

}

if (!array_key_exists('quiet',$options))
    $quiet = false;
else
    $quiet = true;

if (!$quiet) print("\n");

if (array_key_exists('proforma',$options)) $proforma = true;
else $proforma = false;

if (array_key_exists('replace',$options)) $replace = true;
else $replace = false;


$invoice = array();

function localtime2()
{
	global $fakedate;
	if (!empty($fakedate))
	{
		$date = explode("/", $fakedate);
		return mktime(0, 0, 0, $date[1], $date[2], $date[0]);
	}
	else
		return time();
}


function invoice_body() 
{
	global $invoice, $pdf, $CONFIG;
	
	if (isset($invoice['invoice'])) 	$template = $CONFIG['invoices']['cnote_template_file'];
	else $template = $CONFIG['invoices']['template_file'];
	
	switch (strtoupper($template)) 
	{
		case "STANDARD": invoice_body_standard(); break;
		case "FT-0100": invoice_body_ft0100(); break;
		default: require($template); break;
	}
	
	if (!isset($invoice['last'])) $pdf->AddPage();
}


function invoice_body_v2() 
{
	global $invoice, $pdf, $CONFIG;
	
	switch (strtoupper($invoice['templatefile'])) 
	{
		case "STANDARD": invoice_body_standard_v2(); break;
		case "FT-0100": invoice_body_ft0100_v2(); break;
		default: require($invoice['templatefile']); break;
	}
	
	if (!isset($invoice['last'])) $pdf->AddPage();
}


function invoice_create_pdf_file($docid, $output) 
{
	global $pdf,$LMS, $invoice;
	$pdf = init_pdf('A4', 'portrait', trans('Invoices'));
	$invoice = $LMS->GetInvoiceContent($docid);
	$which = array();
	if (!empty($_GET['original'])) $which[] = trans('ORIGINAL');
	if (!empty($_GET['copy'])) $which[] = trans('COPY');
	if (!empty($_GET['duplicate'])) $which[] = trans('DUPLICATE');
	if (!sizeof($which)) {
		$tmp = explode(',', $CONFIG['invoices']['default_printpage']);
		foreach ($tmp as $t) {
			    if (trim($t) == 'original') $which[] = trans('ORIGINAL');
			    elseif (trim($t) == 'copy') $which[] = trans('COPY');
			    elseif (trim($t) == 'duplicate') $which[] = trans('DUPLICATE');
		}
		if (!sizeof($which)) $which[] = '';
	}
	$count = sizeof($which);
	$i=0;
	foreach($which as $type) {
		$i++;
		if ($i == $count) $invoice['last'] = TRUE;
		
		if ($invoice['version'] == '2') invoice_body_v2();
		else invoice_body();
	}
	$pdf->output($output,'F');
}


require_once(LIB_DIR.'/tcpdf.php');
require_once(MODULES_DIR.'/invoice_tcpdf.inc.php');
require_once(MODULES_DIR.'/invoice_tcpdf_v2.inc.php');
//$DB->debug = true;

$fakedate = (array_key_exists('fakedate', $options) ? $options['fakedate'] : NULL);

$currtime = strftime("%s", localtime2());
$month = intval(strftime("%m", localtime2()));
$dom = intval(strftime("%d", localtime2()));
$year = strftime("%Y", localtime2());
$month = sprintf('%02.d',$month);
$dom = sprintf('%02.d',$dom);


if (array_key_exists('docid',$options)) 
{
    // generowanie pliku na podstawie id faktury
	$docid = intval($options['docid']);
	$doc = $DB->GetRow('SELECT d.type, d.customerid, d.number, d.numberplanid, d.cdate, d.version, d.templatetype, d.fullnumber, n.template 
			    FROM documents d 
			    JOIN numberplans n ON (n.id = d.numberplanid) 
			    WHERE d.id = ? AND d.templatetype = ? AND (d.version = 1 OR d.version = 2) 
			    AND (d.type = ? OR d.type = ?'
			     .($proforma ? ' OR d.type='.DOC_INVOICE_PRO.' ' : '')
			     .') 
			    LIMIT 1;',array($docid,'pdf',DOC_CNOTE,DOC_INVOICE));
	
	
	if (!$doc) {
	    if (!$quiet) print("Nie odnaleziono faktury o ID : ".$docid.", lub dokument nie spełnia kryteriów\n");
	    die;
	}
	
	if (!$doc['fullnumber']) {
	    $fullnumber = docnumber($doc['number'], $doc['template'], $doc['cdate']);
	} else {
	    $fullnumber = $doc['fullnumber'];
	}
	
	$filename = str_replace('/','_',$fullnumber);
	$filename = str_replace("\\","-",$filename);
	$filename .= '.pdf';
	
	$year = date('Y',$doc['cdate']);
	$month = sprintf('%02.d',date('m',$doc['cdate'])); 
	$dom = sprintf('%02.d',date('d',$doc['cdate']));
	$cid = 'CID'.sprintf('%06.d',$doc['customerid']);
	$did = 'DID'.sprintf('%08.d',$docid);
	
	
	$filename = $cid.'_'.$did.'_'.$filename;
	
	$PREFIX = $year.'/'.$month.'/'.$dom;
	$fullfilename = INVOICE_DIR.'/'.$PREFIX.'/'.$filename;
	
	$DIR = INVOICE_DIR.'/'.$PREFIX;
	`mkdir -p $DIR`;
	`chmod 755 $DIR`;
	`chown 33:33 $DIR`;
	if ($tmp = $DB->GetOne('SELECT 1 FROM documentcontents WHERE docid = ? LIMIT 1;',array($docid))) {
	    if (file_exists($fullfilename) && !$replace) {
		if (!$quiet) print("plik pdf (".$fullfilename.") dla podanej faktury już istnieje !!!\n");
		die;
	    } else {
		invoice_create_pdf_file($docid,$fullfilename);
		`chmod 644 $fullfilename`;
		`chown 33:33 $fullfilename`;
		$md5 = md5_file($fullfilename);
		$DB->Execute('UPDATE documentcontents SET md5sum = ? WHERE docid = ? ;',array($md5,$docid));
	    }
	} else {
	    
	    if ($doc['type'] == DOC_CNOTE)
		$title = 'Faktura Korygująca';
	    elseif ($doc['type'] == DOC_INVOICE_PRO)
		$title = 'Faktura Proforma';
	    else
		$title = 'Faktura';
	    
	    invoice_create_pdf_file($docid,$fullfilename);
	    `chmod 644 $fullfilename`;
	    `chown 33:33 $fullfilename`;
	    $md5 = md5_file($fullfilename);
	    
	    $DB->Execute('INSERT INTO documentcontents (docid, title, fromdate, todate, filename, contenttype, md5sum, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ;',
		array($docid, $title, 0, 0, $filename, 'pdf', $md5, ''));
	}
} else {
	// generowanie plików z całego dnia
	
	
	if (array_key_exists('fromdate',$options) && array_key_exists('todate',$options)) {
		
		if (empty($options['todate']) || !check_date($options['todate'])) {
			print("brak lub błędnie podano datę końcową, proszę podać w formacie YYYY/MM/DD\n");
			die;
		}
		
		if (empty($options['fromdate']) || !check_date($options['fromdate'])) {
			print("brak lub błędnie podano datę pocztątkową, proszę podać w formacie YYYY/MM/DD\n");
			die;
		}
		
		$zakres = true;
		$daystart = strtotime($options['fromdate']);
		$dayend = strtotime($options['todate']) + 86399;
		
	} elseif (array_key_exists('leftmonth',$options)) {
		
		$data = date('Y/m',strtotime("-1 MONTH"));
		$ildni = date('t',strtotime("-1 MONTH"));
		
		$daystart = strtotime($data.'/01');
		$dayend = strtotime("$data/$ildni") + 86399;
		
		$zakres = true;
		$options['fromdate'] = date('Y/m/d',$daystart);
		$options['todate'] = date('Y/m/d',$dayend);
		
	} elseif (array_key_exists('leftday',$options)) {
		$data = date('Y/m/d',strtotime("-1 DAY"));
		
		$daystart = strtotime($data);
		$dayend = strtotime($data) + 86399;
		
		$options['fromdate'] = date('Y/m/d',$daystart);
		$options['todate'] = date('Y/m/d',$dayend);
		
	} else {
		
		$date = $year.'/'.$month.'/'.$dom;
		$daystart = strtotime($date);
		$dayend = strtotime($date) + 86399;
		$zakres = false;
		$options['fromdate'] = date('Y/m/d',$daystart);
		$options['todate'] = date('Y/m/d',$dayend);
		
	}
	
	$docid = intval($options['docid']);
	$doc = $DB->GetAll('SELECT d.id, d.type, d.customerid, d.number, d.numberplanid, d.cdate, d.version, d.templatetype, d.fullnumber, n.template 
			    FROM documents d 
			    JOIN numberplans n ON (n.id = d.numberplanid) 
			    WHERE d.templatetype = ? AND cdate >= ? AND cdate <= ? AND (d.version = 1 OR d.version = 2) 
			    AND (d.type=? OR d.type=?'
			     .($proforma ? ' OR d.type='.DOC_INVOICE_PRO.' ' : '')
			     .') ;',
			    array('pdf',$daystart, $dayend, DOC_CNOTE, DOC_INVOICE));
	
	$count = sizeof($doc);
	
	if (!$doc) {
	    if (!$quiet) {
		if ($zakres)
		    print("Brak faktur z okresu : ".$$options['fromdate']." - ".$options['todate']."\n");
		else
		    print("Brak faktur z dnia : ".$date."\n");
		}
	    die;
	} else {
	    if (!$quiet) {
		if ($zakres)
		    print("Generuję ".$count." pliki(ów) z zakresu : ".$options['fromdate']." - ".$options['todate']."\n");
		else
		    print("Generuję ".$count." pliki(ów) z dnia : ".$date."\n");
	    }
	}
	
	
	
	$PREFIX = date('Y/m/d',$doc[0]['cdate']);
	$DIR = INVOICE_DIR.'/'.$PREFIX;
	`mkdir -p $DIR`;
	`chmod 755 $DIR`;
	`chown 33:33 $DIR`;
	
	for ($i=0; $i<$count; $i++) 
	{
		$_PREFIX = date('Y/m/d',$doc[$i]['cdate']);
		
		if ($_PREFIX != $PREFIX) {
			$PREFIX = $_PREFIX;
			$DIR = INVOICE_DIR.'/'.$PREFIX;
			`mkdir -p $DIR`;
			`chmod 755 $DIR`;
			`chown 33:33 $DIR`;
		}
		
		if (!$doc[$i]['fullnumber']) {
			$fullnumber = docnumber($doc[$i]['number'], $doc[$i]['template'], $doc[$i]['cdate']);
		} else {
			$fullnumber = $doc[$i]['fullnumber'];
		}
		
		$filename = str_replace('/','_',$fullnumber);
		$filename = str_replace("\\","-",$filename);
		$filename .= '.pdf';
		$cid = 'CID'.sprintf('%06.d',$doc[$i]['customerid']);
		$did = 'DID'.sprintf('%08.d',$doc[$i]['id']);
		
		$filename = $cid.'_'.$did.'_'.$filename;
		
		$fullfilename = INVOICE_DIR.'/'.$PREFIX.'/'.$filename;
		
		if ($tmp = $DB->GetOne('SELECT 1 FROM documentcontents WHERE docid = ? LIMIT 1;',array($doc[$i]['id']))) 
		{
			if (file_exists($fullfilename) && !$replace) {
				if (!$quiet) print("plik pdf (".$fullfilename.") dla podanej faktury już istnieje !!!\n");
			} else {
				invoice_create_pdf_file($doc[$i]['id'],$fullfilename);
				$md5 = md5_file($fullfilename);
				$DB->Execute('UPDATE documentcontents SET md5sum = ? WHERE docid = ? ;',array($md5,$doc[$i]['id']));
			}
		} else {
			if ($doc[$i]['type'] == DOC_CNOTE)
				$title = 'Faktura Korygująca';
			elseif ($doc[$i]['type'] == DOC_INVOICE_PRO)
				$title = 'Faktura Proforma';
			else
				$title = 'Faktura';
			
			invoice_create_pdf_file($doc[$i]['id'],$fullfilename);
			$md5 = md5_file($fullfilename);
			
			$DB->Execute('INSERT INTO documentcontents (docid, title, fromdate, todate, filename, contenttype, md5sum, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ;',
				array($doc[$i]['id'], $title, 0, 0, $filename, 'pdf', $md5, ''));
		}
		
		`chmod 644 $fullfilename`;
		`chown 33:33 $fullfilename`;
	}
	

}

?>