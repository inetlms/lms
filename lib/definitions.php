<?php

/*
 * LMS version 1.11-git (EXPANDED)
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
 
// tablica z nazwami tabel które nie posiadają auto_increment / sequence
// tablica wykorzystywana przy dumpie bazy oraz przy tworzeniu dodatkowego pliku naprawy indexów dla pgsql
// cel: ułatwić życie jak robimy import danych dla pgsql z shell
$TABLENAME_NOINDEX = array(
			'rtattachments',
			'dbinfo',
			'invoicecontents',
			'receiptcontents',
			'documentcontents',
			'stats',
			'eventassignments',
			'monitnodes',
			'monituser',
			'hv_county',
			'hv_province',
			'hv_borough',
			'hv_pcb',
			'hv_pricelist',
			'hv_pstn',
			'hv_pstnrange',
			'hv_pstnusage',
			'hv_subscriptionlist',
			'hv_terminal',
			'hv_customers',
			'hv_enduserlist',
			'sessions');

define('MONIT_NONE',0);
define('MONIT_NODES',1);
define('MONIT_NETDEV',2);
define('MONIT_OWN',4);
define('MONIT_TIME',8);

$MONIT_DEF = array(
    MONIT_NONE		=>	'brak powiadomień',
    MONIT_NODES		=>	'urządzenia klientów',
    MONIT_NETDEV	=>	'urządzenia sieciowe',
    MONIT_OWN		=>	'urządzenia własne',
    MONIT_TIME		=>	'przekroczone czasy odpowiedzi'
);

$MONIT_DEF_SHORT = array(
    MONIT_NONE		=>	'brak',
    MONIT_NODES		=>	'klientów',
    MONIT_NETDEV	=>	'sieciowe',
    MONIT_OWN		=>	'własne',
    MONIT_TIME		=>	'czasy'
);


define('MOD_OTHER',1);
define('MOD_CUSTOMER',2);
define('MOD_NODE',3);
define('MOD_NETDEV',4);
define('MOD_FINANCES',5);
define('MOD_DOCUMENTS',6);
define('MOD_HELPDESK',7);
define('MOD_TIMETABLE',8);
define('MOD_CONFIG',9);
define('MOD_ADMIN',10);
define('MOD_VOIP',11);
define('MOD_HOSTING',12);
define('MOD_DAEMON',13);
define('MOD_MAG',14);
define('MOD_CONTRACTOR',15);
define('MOD_MONITORING',16);
define('MOD_TARIFF',17);
define('MOD_CALLCENTER',18);
define('MOD_SYSLOG',100);


$MOD = array(
    MOD_OTHER		=> 'inne',
    MOD_CUSTOMER	=> 'klienci',
    MOD_NODE		=> 'komputery',
    MOD_NETDEV		=> 'urz. sieciowe',
    MOD_FINANCES	=> 'finanse',
    MOD_DOCUMENTS	=> 'dokumenty',
    MOD_HELPDESK	=> 'helpdesk',
    MOD_TIMETABLE	=> 'terminarz',
    MOD_CONFIG		=> 'konfiguracja',
    MOD_ADMIN		=> 'administracja',
    MOD_VOIP		=> 'VoIP',
    MOD_HOSTING		=> 'hosting',
    MOD_DAEMON		=> 'daemon',
//    MOD_MAG		=> trans('magazyn'),
    MOD_CONTRACTOR	=> 'kontrahenci',
    MOD_MONITORING	=> 'monitoring',
    MOD_TARIFF		=> 'taryfy',
    MOD_CALLCENTER	=> 'callcenter',
    MOD_SYSLOG		=> 'syslog',
);

define('_ADD_',1);		// dodanie
define('_RM_',2);		// skasowanie
define('_UP_',3);		// aktualizacja / update /
define('_MOV_',4);		// przeniesienie
define('_WARN_',5);		// powiadomienia
define('_ACL_',6);		// access dla kompów
define('_INF_',7);		// informacja 
define('_ERR_',8);		// błąd

$MSG_LOG = array(
    _ADD_	=> 'added',
    _RM_	=> 'delete',
    _UP_	=> 'update',
    _MOV_	=> 'move',
    _WARN_	=> 'warn',
    _ACL_	=> 'access',
    _INF_	=> 'inf.',
    _ERR_	=> 'error',
);

// ----------------------- end system logs --------------------------


// customers and contractor type
define('CTYPES_PRIVATE',0);
define('CTYPES_COMPANY',1);
define('CTYPES_CONTRACTOR',2);
//define('CTYPES_RESELLER',3);

$CTYPES = array(
    CTYPES_PRIVATE	=> trans('private person'),
    CTYPES_COMPANY	=> trans('legal entity'),
    CTYPES_CONTRACTOR	=> trans('contractor'),
//    CTYPES_RESELLER	=> 'reseller'
);

// Helpdesk ticket status
define('RT_NEW', 0);
define('RT_OPEN', 1);
define('RT_RESOLVED', 2);
define('RT_DEAD', 3);

$RT_STATES = array(
    RT_NEW      => trans('new'),
    RT_OPEN     => trans('opened'),
    RT_RESOLVED => trans('resolved'),
    RT_DEAD     => trans('dead')
);

// Messages status and type
define('MSG_NEW', 1);
define('MSG_SENT', 2);
define('MSG_ERROR', 3);
define('MSG_DRAFT', 4);

define('MSG_MAIL', 1);
define('MSG_SMS', 2);
define('MSG_ANYSMS', 3);
define('MSG_GADUGADU',10);
define('MSG_USERPANEL',11);

// Account types
define('ACCOUNT_SHELL', 1);
define('ACCOUNT_MAIL', 2);
define('ACCOUNT_WWW', 4);
define('ACCOUNT_FTP', 8);
define('ACCOUNT_SQL', 16);

// Document types
define('DOC_INVOICE', 1);
define('DOC_RECEIPT', 2);
define('DOC_CNOTE', 3);
//define('DOC_CMEMO', 4);
define('DOC_DNOTE', 5);
define('DOC_INVOICE_PRO',6);		// faktura proforma
define('DOC_INVOICE_PURCHASE',7);	// faktura zakupu

define('DOC_CONTRACT', -1);
define('DOC_ANNEX', -2);
define('DOC_PROTOCOL', -3);
define('DOC_ORDER', -4);
define('DOC_SHEET', -5);
define('DOC_OTHER', -10);

$DOCTYPES = array(
    DOC_INVOICE 	=>	trans('Invoice'),
    DOC_INVOICE_PRO	=>	trans('Pro Forma Invoice'),
    DOC_INVOICE_PURCHASE =>	trans('purchase invoice'),
    DOC_RECEIPT 	=>	trans('cash receipt'),
    DOC_CNOTE	    =>	trans('credit note'), // faktura korygujaca
//    DOC_CMEMO	    =>	trans('credit memo'), // nota korygujaca
    DOC_DNOTE	    =>	trans('debit note'), // nota obciazeniowa/debetowa/odsetkowa
    DOC_CONTRACT	=>	trans('contract'),
    DOC_ANNEX	    =>	trans('annex'),
    DOC_PROTOCOL	=>	trans('protocol'),
    DOC_ORDER       =>	trans('order'),
    DOC_SHEET       =>	trans('customer sheet'), // karta klienta 
    -6  =>	trans('contract termination'),
    -7  =>	trans('payments book'), // ksiazeczka oplat
    -8  =>	trans('payment summons'), // wezwanie do zapłaty
    -9	=>	trans('payment pre-summons'), // przedsądowe wezw. do zapłaty
    DOC_OTHER       =>	trans('other'),
);

// Guarantee periods
$GUARANTEEPERIODS = array(
    -1 => trans('lifetime'),
    0  => trans('none'),
    12 => trans('$a months', 12),
    24 => trans('24 months', 24),
    36 => trans('$a months', 36),
    48 => trans('$a months', 48),
    60 => trans('$a months', 60)
);

// Internet Messengers
define('IM_GG', 0);
define('IM_YAHOO', 1);
define('IM_SKYPE', 2);

$MESSENGERS = array(
    IM_GG    => trans('Gadu-Gadu'),
    IM_YAHOO => trans('Yahoo'),
    IM_SKYPE => trans('Skype'),
);

define('DISPOSABLE', 0);
define('DAILY', 1);
define('WEEKLY', 2);
define('MONTHLY', 3);
define('QUARTERLY', 4);
define('YEARLY', 5);
define('CONTINUOUS', 6);
define('HALFYEARLY', 7);

// Accounting periods
$PERIODS = array(
    YEARLY	=>	trans('yearly'),
    HALFYEARLY  =>      trans('half-yearly'),
    QUARTERLY	=>	trans('quarterly'),
    MONTHLY	=>	trans('monthly'),
//    WEEKLY	=>	trans('weekly'),
//    DAILY	=>	trans('daily'),
    DISPOSABLE	=>	trans('disposable')
);

// Numbering periods
$NUM_PERIODS = array(
    CONTINUOUS	=>	trans('continuously'),
    YEARLY	=>	trans('yearly'),
    HALFYEARLY	=>	trans('half-yearly'),
    QUARTERLY	=>	trans('quarterly'),
    MONTHLY	=>	trans('monthly'),
//    WEEKLY	=>	trans('weekly'),
    DAILY	=>	trans('daily'),
);

// Tariff types
define('TARIFF_INTERNET', 1);
define('TARIFF_HOSTING', 2);
define('TARIFF_SERVICE', 3);
define('TARIFF_PHONE', 4);
define('TARIFF_TV', 5);
//define('TARIFF_DEPOSIT', 6); //kaucja
define('TARIFF_LEASE', 7); // dzierżawa
define('TARIFF_ITSERVICE', 8); // serwis IT
define('TARIFF_VIP', 9); // klient V.I.P , czybkie reagowanie
define('TARIFF_MULTIROOM',10); // podział sygnału
define('TARIFF_SUSPENSION',11); // zawieszenie usługi
define('TARIFF_OTHER', -1);

$TARIFFTYPES = array(
	TARIFF_INTERNET		=> isset($CONFIG['tarifftypes']['internet']) ? $CONFIG['tarifftypes']['internet'] : trans('internet'),
	TARIFF_HOSTING		=> isset($CONFIG['tarifftypes']['hosting']) ? $CONFIG['tarifftypes']['config'] : trans('hosting'),
	TARIFF_SERVICE		=> isset($CONFIG['tarifftypes']['service']) ? $CONFIG['tarifftypes']['service'] : trans('service'),
	TARIFF_PHONE		=> isset($CONFIG['tarifftypes']['phone']) ? $CONFIG['tarifftypes']['phone'] : trans('phone'),
	TARIFF_TV		=> isset($CONFIG['tarifftypes']['tv']) ? $CONFIG['tarifftypes']['tv'] : trans('tv'),
//	TARIFF_DEPOSIT		=> isset($CONFIG['tarifftypes']['deposit']) ? $CONFIG['tarifftypes']['deposit'] : trans('deposit'),
	TARIFF_LEASE		=> isset($CONFIG['tarifftypes']['lease']) ? $CONFIG['tarifftypes']['lease'] : trans('lease'),
	TARIFF_ITSERVICE	=> isset($CONFIG['tarifftypes']['itservice']) ? $CONFIG['tarifftypes']['itservice'] : trans('it service'),
	TARIFF_VIP		=> isset($CONFIG['tarifftypes']['vip']) ? $CONFIG['tarifftypes']['vip'] : trans('VIP'),
	TARIFF_MULTIROOM	=> isset($CONFIG['tarifftypes']['multiroom']) ? $CONFIG['tarifftypes']['multiroom'] : trans('multi room'),
	TARIFF_SUSPENSION	=> isset($CONFIG['tarifftypes']['suspension']) ? $CONFIG['tarifftypes']['suspension'] : trans('suspension'),
	TARIFF_OTHER		=> isset($CONFIG['tarifftypes']['other']) ? $CONFIG['tarifftypes']['other'] : trans('other'),
);

$PAYTYPES = array(
    1   => trans('cash'),
    2   => trans('transfer'),
    3   => trans('transfer/cash'),
    4   => trans('card'),
    5   => trans('compensation'),
    6   => trans('barter'),
    7   => trans('contract'),
    8   => trans('paid'),
);

// Contact types
define('CONTACT_MOBILE', 1);
define('CONTACT_FAX', 2);

$CONTACTTYPES = array(
    CONTACT_MOBILE 	=>	trans('mobile'),
    CONTACT_FAX 	=>	trans('fax'),
);

define('DISCOUNT_PERCENTAGE', 1);
define('DISCOUNT_AMOUNT', 2);

$DISCOUNTTYPES = array(
	DISCOUNT_PERCENTAGE	=> '%',
	DISCOUNT_AMOUNT		=> trans('amount'),
);

define('DAY_MONDAY', 0);
define('DAY_TUESDAY', 1);
define('DAY_THURSDAY', 2);
define('DAY_WEDNESDAY', 3);
define('DAY_FRIDAY', 4);
define('DAY_SATURDAY', 5);
define('DAY_SUNDAY', 6);

$DAYS = array(
	DAY_MONDAY	=> trans('Mon'),
	DAY_TUESDAY	=> trans('Tue'),
	DAY_THURSDAY	=> trans('Thu'),
	DAY_WEDNESDAY	=> trans('Wed'),
	DAY_FRIDAY	=> trans('Fri'),
	DAY_SATURDAY	=> trans('Sat'),
	DAY_SUNDAY	=> trans('Sun'),
);

$LINKTYPES = array(
	0		=> trans('wire'),
	1		=> trans('wireless'),
	2		=> trans('fiber'),
);

$LINKSPEEDS = array(
	10000		=> trans('10Mbit/s'),
	25000		=> trans('25Mbit/s'),
	54000		=> trans('54Mbit/s'),
	100000		=> trans('100Mbit/s'),
	200000		=> trans('200Mbit/s'),
	300000		=> trans('300Mbit/s'),
	1000000		=> trans('1Gbit/s'),
	10000000	=> trans('10Gbit/s'),
);

$BOROUGHTYPES = array(
	1 => trans('municipal commune'),
	2 => trans('rural commune'),
	3 => trans('municipal-rural commune'),
	4 => trans('city in the municipal-rural commune'),
	5 => trans('rural area to municipal-rural commune'),
	8 => trans('estate in Warsaw-Centre commune'),
	9 => trans('estate'),
);

$PASSWDEXPIRATIONS = array(
	0	=> trans('never expires'),
	7	=> trans('week'),
	14	=> trans('2 weeks'),
	21	=> trans('21 days'),
	31	=> trans('month'),
	62	=> trans('2 months'),
	93	=> trans('quarter'),
	183	=> trans('half year'),
	365	=> trans('year'),
);

if(isset($SMARTY))
{
	$SMARTY->assign('_CTYPES',$CTYPES);
	$SMARTY->assign('_DOCTYPES', $DOCTYPES);
	$SMARTY->assign('_PERIODS', $PERIODS);
	$SMARTY->assign('_GUARANTEEPERIODS', $GUARANTEEPERIODS);
	$SMARTY->assign('_NUM_PERIODS', $NUM_PERIODS);
	$SMARTY->assign('_RT_STATES', $RT_STATES);
	$SMARTY->assign('_MESSENGERS', $MESSENGERS);
	$SMARTY->assign('_TARIFFTYPES', $TARIFFTYPES);
	$SMARTY->assign('_PAYTYPES', $PAYTYPES);
	$SMARTY->assign('_CONTACTTYPES', $CONTACTTYPES);
	$SMARTY->assign('_DISCOUNTTYPES', $DISCOUNTTYPES);
	$SMARTY->assign('_DAYS', $DAYS);
	$SMARTY->assign('_LINKTYPES', $LINKTYPES);
	$SMARTY->assign('_LINKSPEEDS', $LINKSPEEDS);
	$SMARTY->assign('_BOROUGHTYPES', $BOROUGHTYPES);
	$SMARTY->assign('_PASSWDEXPIRATIONS', $PASSWDEXPIRATIONS);
	$SMARTY->assign('_MSG_LOG',$MSG_LOG);
	$SMARTY->assign('_MOD',$MOD);
}

define('DEFAULT_NUMBER_TEMPLATE', '%N/LMS/%Y');

?>
