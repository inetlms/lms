<?php

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


if (isset($_GET['id']) && !empty($_GET['id']) && intval($_GET['id']))
    $iid = $_GET['id'];

$docid = $iid;

$doc = $DB->GetRow('SELECT d.type, d.customerid, d.number, d.numberplanid, d.cdate, d.version, d.templatetype, d.fullnumber, n.template 
		    FROM documents d 
		    JOIN numberplans n ON (n.id = d.numberplanid) 
		    WHERE d.id = ? AND d.templatetype = ? AND (d.version = 1 OR d.version = 2) 
		    AND (d.type = ? OR d.type = ? OR d.type=?) 
		    LIMIT 1;',array($docid,'pdf',DOC_CNOTE,DOC_INVOICE,DOC_INVOICE_PRO));
	
if ( ($doc['type'] == DOC_CNOTE || $doc['type'] == DOC_INVOICE) && !get_conf('invoices.create_pdf_file'))
    $doc = NULL;

if ( $doc['type'] == DOC_INVOICE_PRO && !get_conf('invoices.create_pdf_file_proforma'))
    $doc = NULL;

if ($doc) {
	
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
		
		if (file_exists($fullfilename)) 
		    @unlink($fullfilename);
		
		invoice_create_pdf_file($docid,$fullfilename);
		`chmod 644 $fullfilename`;
		`chown 33:33 $fullfilename`;
		$md5 = md5_file($fullfilename);
		$DB->Execute('UPDATE documentcontents SET md5sum = ? WHERE docid = ? ;',array($md5,$docid));
		
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
}

?>