<?php

/*
 *  LMS Userpanel version 1.0rc1-Kai
 *
 *  (C) Copyright 2004-2006 Userpanel Developers
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
 *  $Id: functions.php,v 1.11.2.1 2006/01/16 09:49:58 lexx Exp $
 */

if (isset($_USERPANEL_SETUPMODE))
{
    function module_setup()
    {
	global $SMARTY,$LMS;
	$SMARTY->assign('disable_transferform', $LMS->CONFIG['userpanel']['disable_transferform']);
	$SMARTY->assign('disable_invoices', $LMS->CONFIG['userpanel']['disable_invoices']);
	$SMARTY->assign('invoice_duplicate', $LMS->CONFIG['userpanel']['invoice_duplicate']);
	$SMARTY->assign('show_tariffname', $LMS->CONFIG['userpanel']['show_tariffname']);
	$SMARTY->assign('show_speeds', $LMS->CONFIG['userpanel']['show_speeds']);
	$SMARTY->display('module:finances:setup.html');
    }

    function module_submit_setup()
    {
	global $SMARTY,$DB;
	if ($_POST['disable_transferform']) {
	    $DB->Execute('UPDATE uiconfig SET value = \'1\' WHERE section = \'userpanel\' AND var = \'disable_transferform\'');
	} else {
	    $DB->Execute('UPDATE uiconfig SET value = \'0\' WHERE section = \'userpanel\' AND var = \'disable_transferform\'');
	}
	if ($_POST['disable_invoices']) {
	    $DB->Execute('UPDATE uiconfig SET value = \'1\' WHERE section = \'userpanel\' AND var = \'disable_invoices\'');
	} else {
	    $DB->Execute('UPDATE uiconfig SET value = \'0\' WHERE section = \'userpanel\' AND var = \'disable_invoices\'');
	}
	if ($_POST['invoice_duplicate']) {
	    $DB->Execute('UPDATE uiconfig SET value = \'1\' WHERE section = \'userpanel\' AND var = \'invoice_duplicate\'');
	} else {
	    $DB->Execute('UPDATE uiconfig SET value = \'0\' WHERE section = \'userpanel\' AND var = \'invoice_duplicate\'');
	}
	if ($_POST['show_tariffname']) {
	    $DB->Execute('UPDATE uiconfig SET value = \'1\' WHERE section = \'userpanel\' AND var = \'show_tariffname\'');
	} else {
	    $DB->Execute('UPDATE uiconfig SET value = \'0\' WHERE section = \'userpanel\' AND var = \'show_tariffname\'');
	}
	if ($_POST['show_speeds']) {
	    $DB->Execute('UPDATE uiconfig SET value = \'1\' WHERE section = \'userpanel\' AND var = \'show_speeds\'');
	} else {
	    $DB->Execute('UPDATE uiconfig SET value = \'0\' WHERE section = \'userpanel\' AND var = \'show_speeds\'');
	}
	header('Location: ?m=userpanel&module=finances');
    }
}


function module_transferform()
{
    include 'transferform.php';
}

function module_invoice()
{
    include 'invoice.php';
}

function module_main()
{
    include 'main.php';
}

function module_ivr()
{
	include 'ivr.php';
}

function module_calendar()
{
	include 'callendar.php';
}

function module_fax()
{
global $SMARTY, $voip, $SESSION;
if(is_array($_POST['fin']))
	$voip->ui_deletefin($_POST['fin'], $SESSION->id);
if(is_array($_POST['fout']))
	$voip->ui_deletefout($_POST['fout'], $SESSION->id);
if($_GET['sa'])
	$SMARTY->assign('faxform', $voip->ui_faxsa($_GET['sa'], $SESSION->id));
$customernodes = array();
$tmp = $voip->wsdl->GetCustomerNodes($SESSION->id);
if(is_array($tmp))
	foreach($tmp as $key => $val)
		if(is_numeric($key))
			$customernodes[$val['id']] = $val['name'];
$adrbook = array();
foreach((array)$voip->wsdl->getaddressbook($SESSION->id) as $val)
	$adrbook[$val['number']] = $val['name'];
$SMARTY->assign('addressbook', $adrbook);
$adrbookgr = array();
$err = array();
foreach((array)$voip->wsdl->gr_list($SESSION->id) as $val)
	$adrbookgr[$val['id']] = $val['name'];
$SMARTY->assign('addressbookgr', $adrbookgr);
if(($file = $_FILES['faxfile']) && ($gr = $_POST['dogr']) && ($nr = $_POST['fromnr']))
{
	if(!array_key_exists($nr, $customernodes))
		$err[] = 'Błąd!!';
	if(strtolower(substr($file['name'], -3)) != 'pdf')
		$err[] = 'Błąd! Obsługiwane są wyłącznie pliki PDF.';
	if($file['error'])
		$err[] = 'Błąd wysyłania pliku. Spróbuj ponownie.';
	$numbers = $voip->wsdl->nr_list($gr);
	if(empty($numbers))
		$err[] = 'Brak wpisów';
	else
	{
		if(is_array($numbers))
			foreach($numbers as $val)
				if($voip->wsdl->checkcost($val['number'], $nr) === FALSE)
					$err[] = 'Błędny nr telefonu.';
		if(empty($err))
		{
			if(is_array($numbers))
				foreach($numbers as $val)
					$voip->preparetofax($file, $customernodes[$nr], $val['number'], $SESSION->id);
			$err[] = 'Fax został przekazany do wysłania.';
		}
	}
	$SMARTY->assign('err', $err);
}
elseif(($file = $_FILES['faxfile']) && ($nr = $_POST['fromnr']) && ($nrto = $_POST['tonr']))
{
	if(!array_key_exists($nr, $customernodes))
		$err[] = 'Błąd!!';
	if($voip->wsdl->checkcost($nrto, $nr) === FALSE)
		$err[] = 'Błędny nr telefonu.';
	if($file['error'])
		$err[] = 'Błąd wysyłania pliku. Spróbuj ponownie.';
	if(strtolower(substr($file['name'], -3)) != 'pdf')
		$err[] = 'Błąd! Obsługiwane są wyłącznie pliki PDF.';
	if(empty($err))
	{
		$voip->preparetofax($file, $customernodes[$nr], $nrto, $SESSION->id);
		$err[] = 'Fax został przekazany do wysłania.';
	}
	$SMARTY->assign('err', $err);
}
elseif(($uniq = $_POST['faxfile_uniqueid']) && ($nr = $_POST['fromnr']) && ($nrto = $_POST['tonr']))
{
	if(!array_key_exists($nr, $customernodes))
		$err[] = 'Błąd!!';
	if($voip->wsdl->checkcost($nrto, $nr) === FALSE)
		$err[] = 'Błędny nr telefonu.';
	if(empty($err))
	{
		if($voip->preparetofax_again($uniq, $customernodes[$nr], $nrto, $SESSION->id))
			$err[] = 'Fax został przekazany do wysłania.';
		else $err[] = 'Błąd!!';
	}
	$SMARTY->assign('err', $err);
}
$SMARTY->assign('fin', $voip->fax_inbox($SESSION->id));
$SMARTY->assign('fout', $voip->fax_outbox($SESSION->id));
$SMARTY->assign('nodes', $customernodes);
$SMARTY->display('module:fax.html');
}

function module_faxprint()
{
global $voip,$SESSION;
$file=$voip->faxprint($SESSION->id,$_GET['id'],$_GET['t']);
if(!$file) die('Błąd !');
$f=tempnam('/tmp','pdf');
header("Content-type: application/pdf");
execute_program('tiff2pdf', ' -o '.$f.' '.$file);
readfile($f);
unlink($f);
exit();
}

function module_settings()
{
	global $SMARTY, $voip, $SESSION, $LMS;
	if(!$voip->wsdl->uicheckowner($_GET['id'], $SESSION->id))
		$SESSION->redirect('?m=voip');
	if($sip = $_POST['sip'])
	{
		if(!$voip->wsdl->uicheckowner($sip['id'], $SESSION->id))
			$SESSION->redirect('?m=voip');
		$err = array();
		$sip['finlimit'] = str_replace(',', '.', $sip['finlimit']);
		if($sip['busy_action'] == 'forward' && !$sip['busy_forward_number'])
			$err[] = 'Brak numeru przekierowania';
		if($sip['unavail_action'] == 'forward' && !$sip['unavail_forward_number'])
			$err[] = 'Brak numeru przekierowania';
		if($sip['busy_forward_number'] && $voip->wsdl->checkcost($sip['busy_forward_number'], $sip['id']) === FALSE)
			$err[] = 'Błędny numer przekierowania';
		if($sip['unavail_forward_number'] && $voip->wsdl->checkcost($sip['unavail_forward_number'], $sip['id']) === FALSE)
			$err[] = 'Błędny numer przekierowania';
		if($sip['mailboxpin'] && !preg_match('/^[0-9]{4,8}$/', $sip['mailboxpin']))
			$err[] = 'Błędny pin poczty (4-8 znaków)';
		if(!preg_match('/^[0-9.]+$/', $sip['finlimit']))
			$err[] = 'Niedozwolone znaki w polu kwota !';
		if($sip['voicemailaddr'] && !check_email($sip['voicemailaddr']))
			$err[] = 'Błędny adres email!';
		if($sip['faxmailaddr'] && !check_email($sip['faxmailaddr']))
			$error[] = 'Błędny adres email!';

		if(empty($err))
		{
			if(strlen($sip['busy_forward_number']) == 9 and $sip['busy_forward_number'][0] != '0')
				$sip['busy_forward_number'] = '0' . $sip['busy_forward_number'];
			if(strlen($sip['unavail_forward_number']) == 9 and $sip['unavail_forward_number'][0] != '0')
				$sip['unavail_forward_number'] = '0' . $sip['unavail_forward_number'];
			$voip->wsdl->uiupdatesip($sip);
			$SESSION->redirect('?m=voip');
		}
		else
		{
			$SMARTY->assign('err', $err);
			$tmp = $voip->wsdl->ui_getsip($_GET['id']);
			$sip['id_subscriptions'] = $tmp['id_subscriptions'];
		}
	}
	else
		$sip = $voip->wsdl->ui_getsip($_GET['id']);
	if(strlen($sip['busy_forward_number']) == 10 and $sip['busy_forward_number'][0] == '0' and $sip['busy_forward_number'][1] != '0')
		$sip['busy_forward_number'] = substr($sip['busy_forward_number'], -9);
	if(strlen($sip['unavail_forward_number']) == 10 and $sip['unavail_forward_number'][0] == '0' and $sip['unavail_forward_number'][1] != '0')
		$sip['unavail_forward_number'] = substr($sip['unavail_forward_number'], -9);
	$subs = $voip->wsdl->GetTariff($sip['id_subscriptions']);
	$addserv = array();
	$taxes = $LMS->GetTaxes();
	$tax = 0;
	if(is_array($taxes))
		foreach($taxes as $val)
			if($val['id'] == $voip->config['taxid'])
				$tax = $val['value'];
	foreach($subs['addserv'] as $val)
		$addserv[$val['column_name']] = number_format(round($val['price'] + $val['price'] * ($tax / 100), 2),2, '.', '');
	$SMARTY->assign('addserv', $addserv);
	$user = array();
	$voip->wsdl->GetCustomer($user, $SESSION->id);
	$tmp = $voip->wsdl->GetSettings();
	$sip['sipserver'] = $tmp[1];
	$sip['mailboxnumber'] = $tmp[4];
	$SMARTY->assign('user', $user);
	$SMARTY->assign('sip', $sip);
	$SMARTY->assign('busy_action', array('busy' => 'Sygnał zajętości', 'voicemail' => 'Poczta głosowa', 'forward' => 'Przekieruj'));
	$SMARTY->assign('unavail_action', array('unavail' => 'Sygnał niedostępności', 'voicemail' => 'Poczta głosowa', 'forward' => 'Przekieruj'));
	$SMARTY->display('module:settings.html');
}

function module_allowedrates()
{
global $SMARTY, $SESSION, $voip;
if(!$voip->wsdl->uicheckowner($_GET['id'],$SESSION->id)) $SESSION->redirect('?m=voip');
if($alr=$_POST['sz'])
{
	$voip->wsdl->uiAddAllowedRates($alr,$_GET['id']);
	$SESSION->redirect('?m=voip');
}
$SMARTY->assign('alr',$voip->wsdl->uiGetAllowedRates($_GET['id']));

$SMARTY->display('module:alr.html');
}

function module_listen()
{
global $SESSION,$voip;
$file = $voip->uilisten($SESSION->id, $_GET['id']);
if (file_exists($file)) 
{
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.basename($file));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	ob_clean();
	flush();
	readfile($file);
	exit;
}
$SESSION->redirect('?m=voip');
}

function module_delcdr()
{
global $SESSION,$voip;
$file = $voip->uilisten($SESSION->id, $_GET['id']);
if (file_exists($file)) 
	@unlink($file);
$SESSION->redirect('?m=voip');
}

function module_addressbook()
{
global $SESSION, $voip, $SMARTY;
if($gr = $_POST['group'])
{
        if(!preg_match('/^[0-9a-zA-Z_ ]+$/', $gr['name']))
		$error = 'Błędna nazwa grupy';
        if($voip->wsdl->check_gr_exist($gr['name'], $SESSION->id, $gr['id']))
		$error = 'Podana nazwa już istnieje';
        if($error) $SMARTY->assign('error', $error);
        else
        {
                if($gr['id'])
                {
                        $voip->wsdl->edit_gr($gr['name'], $gr['block'], $SESSION->id, $gr['id']);
                }
                else $voip->wsdl->add_gr($gr['name'], $gr['block'], $SESSION->id);
        $SESSION->redirect('?m=voip&f=addressbook');
        }
}
elseif($_GET['edit'])
{
        $SMARTY->assign('group', $voip->wsdl->gr_gettoedit($_GET['edit'], $SESSION->id));
        $SMARTY->assign('groupaction', true);
}
elseif($_GET['del'])
        $voip->wsdl->del_gr($_GET['del'], $SESSION->id);
$SMARTY->assign('groups', $voip->wsdl->gr_list($SESSION->id));
$SMARTY->display('module:addressbook.html');
}

function module_addressbookd()
{
global $SESSION,$voip,$SMARTY;
if(!$voip->wsdl->check_grd($_GET['gr'],$SESSION->id)) $SESSION->redirect('?m=voip&f=addressbook');
if($nr=$_POST['nr'])
{
        if(!preg_match('/^[0-9a-zA-Z_ ]+$/',$nr['name']) || !$nr['name']) $error='Błędna nazwa kontaktu';
        if(!preg_match('/^\d+$/',$nr['number'])) $error='Błędny numer kontaktu';
        if($voip->wsdl->check_nr_exist($nr['number'],$SESSION->id,$nr['id'],$_GET['gr'])) $error='Podany numer już istnieje';
        if($voip->wsdl->check_nrname_exist($nr['name'],$SESSION->id,$nr['id'],$_GET['gr'])) $error='Podana nazwa już istnieje';
        if($error) $SMARTY->assign('error',$error);
        else
        {
                if($nr['id']) $voip->wsdl->edit_nr($nr['name'],$nr['number'],$nr['id']);
                else $voip->wsdl->add_nr($nr['name'],$nr['number'],$_GET['gr']);
        }

}
elseif($_GET['edit'])
{
        $SMARTY->assign('nr',$voip->wsdl->nr_gettoedit($_GET['edit']));
        $SMARTY->assign('groupaction',true);
}
elseif($_GET['del'])
        $voip->wsdl->del_nr($_GET['del']);
$SMARTY->assign('nrl',$voip->wsdl->nr_list($_GET['gr']));
$SMARTY->display('module:addressbookd.html');
}

function module_cost()
{
global $voip,$SMARTY,$LMS;
setlocale(LC_NUMERIC, 'C');
if(($to=$_POST['tonr']) && ($tar=$_GET['id']))
{
$cost=$voip->wsdl->checkcost($to,$tar);
if($cost===false) $SMARTY->assign('err','Brak w cenniku !');
else
{
$taxes=$LMS->GetTaxes();
$tax=0;
if(is_array($taxes)) foreach($taxes as $val) if($val['id'] == $voip->config['taxid']) $tax=$val['value'];

	$SMARTY->assign('err',$cost[1].'<br>Koszt: '.number_format(round($cost[0]*($tax/100)+$cost[0],2),2,'.','').' PLN za minutę połączenia');
}
$SMARTY->assign('tonr',$to);
}
$SMARTY->display('module:cost.html');
}

function module_tariff()
{
global $LMS, $voip;
$cnet = 'Cena za minutę połączenia (netto)';
$voip->toiso($cnet);
$cbrut = 'Cena za minutę połączenia (brutto)';
$voip->toiso($cbrut);
$taxes=$LMS->GetTaxes();
$tax=23;
if(is_array($taxes)) foreach($taxes as $val) if($val['id'] == $voip->config['taxid']) $tax=$val['value'];
setlocale (LC_TIME, "C");
$sip=$voip->GetNode($_GET['id']);
$exp=$voip->wsdl->CennExport($sip['id_tariffs']);
$data=array();
$i=1;$suma=0;$poz=0;
if(is_array($exp)) foreach($exp as $val)
{
$el=array();
$el['L.p.']=$i++;
$el['Kierunek']=$val['desc'];
if($val['days']==510 && $val['from']=='00:00' && $val['to']=='23:59') $el['Kiedy']='zawsze';
else
{
        $x=decbin($val['days']);
        $x=sprintf('%09s', $x);
        $x=$voip->str_split($x);
        $el['Kiedy']=$val['from'].'-'.$val['to']."\n".$voip->wsdl->days($x);
}
$koszt=$val['price']*60;
$el[$cnet]=sprintf("%.3f",round($koszt,3));
$koszt=$koszt*($tax/100)+$koszt;
$el[$cbrut]=sprintf("%.3f",round($koszt,3));
$data[]=$voip->toiso($el);
}
if($_GET['csv'])
{
	$fname = tempnam("/tmp", "CSV");
	$f=fopen($fname,'w');
	foreach((array)$data as $key=>$val)
	{
		$line='';$line1='';
		if($key==0) 
		{
			foreach((array)$val as $key1=>$val1)
			{
				$line1.=$key1.';';
				$line.=$val1.';';
			}
		$line=substr($line1,0,-1)."\n".$line;
		}
		else foreach((array)$val as $key1=>$val1) $line.=$val1.';';
		$line=substr($line,0,-1)."\n";
		fwrite($f,$line);
	}
	fclose($f);
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="cenn.csv"');
	readfile($fname);
	unlink($fname);
	exit();
}

require_once(LIB_DIR.'/ezpdf.php');

$pdf = init_pdf('A4', 'portrait', trans('Invoices'));

$pdf->ezTable($data,'','',array('fontSize' => 5));
$pdf->ezStream();
close_pdf($pdf);
exit();
}

?>
