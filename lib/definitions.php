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


// na potrzeby menu
define('MODULES_ADMIN',20);
define('MODULES_CUSTOMERS',40);
define('MODULES_NODES',60);
define('MODULES_VOIP',80);
define('MODULES_VOIPC5',81); // hiperus C5
define('MODULES_VOIPNT',82); // nettelekom
define('MODULES_NETDEVICES',100);
define('MODULES_MONITORING',110);
define('MODULES_RADIUS',115);
define('MODULES_NETWORKS',120);
define('MODULES_CONTRACTORS',140);
define('MODULES_TARIFFS',150);
define('MODULES_FINANCES',160);
define('MODULES_DOCUMENTS',180);
define('MODULES_HOSTING',200);
define('MODULES_MESSAGES',220);
define('MODULES_RELOAD',240);
define('MODULES_STATS',260);
define('MODULES_HELPDESK',280);
define('MODULES_TIMETABLE',300);
define('MODULES_RAPORTY',330);
define('MODULES_SLOWNIK',331);
define('MODULES_CONFIG',340);
define('MODULES_PASSWORD',320);
define('MODULES_REGISTRYEQUIPMENT',350); // środki trwałe
define('MODULES_JAMBOX',360);
define('MODULES_USERPANEL',400);
define('MODULES_MAGAZYN',410);

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


//define('CSTATUS_ZAWIESZONY',8);
//define('CSTATUS_NIEPLACI',6);
//define('CSTATUS_REZYGNACJA',7);

define('CSTATUS_PODLACZONY',3);
define('CSTATUS_ZAINTERESOWANY',1);
define('CSTATUS_OCZEKUJACY',2);
define('CSTATUS_ODLACZONY',5);

$CSTATUS = array(
	CSTATUS_PODLACZONY	=> 'podłączony',
	CSTATUS_ZAINTERESOWANY	=> 'zainteresowany',
	CSTATUS_OCZEKUJACY	=> 'oczekujący',
//	CSTATUS_ZAWIESZONY	=> 'zawieszony',
//	CSTATUS_NIEPLACI	=> 'nie płacący',
//	CSTATUS_REZYGNACJA	=> 'rezygnacja',
	CSTATUS_ODLACZONY	=> 'odłączony',
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
define('DOC_COUNTING',0);		// tylko naliczanie
define('DOC_INVOICE', 1);
define('DOC_RECEIPT', 2);
define('DOC_CNOTE', 3);
//define('DOC_CMEMO', 4);
define('DOC_DNOTE', 5);
define('DOC_INVOICE_PRO',6);		// faktura proforma
define('DOC_INVOICE_PURCHASE',7);	// faktura zakupu
define('DOC_WARRANTY',8);		// gwarancja

define('DOC_CONTRACT', -1);
define('DOC_ANNEX', -2);
define('DOC_PROTOCOL', -3);
define('DOC_ORDER', -4);
define('DOC_SHEET', -5);
define('DOC_CONTRACT_END',-6);		// rozwiązanie umowy
define('DOC_PAYMENT_BOOK',-7);		// książeczka opłat
define('DOC_SUMMONS',-8);		// wezwanie do zapłaty
define('DOC_PRE_SUMMONS',-9);		// przedsądowe wezwanie do zapłaty
define('DOC_OTHER', -128);		// inne
define('DOC_BILLING',-10);		// billing

$DOCTYPES = array(
    DOC_COUNTING		=> trans('Only counting'),		// tylko naliczanie
    DOC_BILLING			=> 'billing',				// billing
    DOC_INVOICE 		=> trans('Invoice VAT'),				// faktura vat
    DOC_INVOICE_PRO		=> trans('Invoice Proforma'),			// faktura proforma
    DOC_INVOICE_PURCHASE 	=> trans('purchase invoice'),		// faktura zakupu
    DOC_WARRANTY		=> trans('warranty card'),		// karta gwarancyjna
    DOC_RECEIPT 		=> trans('cash receipt'),
    DOC_CNOTE			=> 'Faktura Korygująca', 		// faktura korygujaca
//    DOC_CMEMO	    		=> trans('credit memo'), // nota korygujaca
    DOC_DNOTE			=> trans('debit note'), 		// nota obciazeniowa/debetowa/odsetkowa
    DOC_CONTRACT		=> trans('contract'),			// umowa
    DOC_ANNEX			=> trans('annex'),			// aneks
    DOC_PROTOCOL		=> trans('protocol'),			// protokół
    DOC_ORDER			=> trans('order'),			// zamówienie
    DOC_SHEET			=> trans('customer sheet'), 		// karta klienta 
    DOC_CONTRACT_END		=> trans('contract termination'),	// rozwiązanie umowy
    DOC_PAYMENT_BOOK		=> trans('payments book'), 		// ksiazeczka oplat
    DOC_SUMMONS			=> trans('payment summons'), 		// wezwanie do zapłaty
    DOC_PRE_SUMMONS		=> trans('payment pre-summons'), 	// przedsądowe wezw. do zapłaty
    DOC_OTHER			=> trans('other'),			// ine
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
define('TARIFF_PHONE_ISDN',12);
define('TARIFF_PHONE_MOBILE',13);
define('TARIFF_INTERNET_MOBILE',14);
define('TARIFF_OTHER', -1);

$TARIFFTYPES = array(
	TARIFF_INTERNET		=> 'Stacjonarny dostęp do Internetu',										// 
	TARIFF_HOSTING		=> isset($CONFIG['tarifftypes']['hosting']) ? $CONFIG['tarifftypes']['config'] : trans('hosting'),
	TARIFF_SERVICE		=> isset($CONFIG['tarifftypes']['service']) ? $CONFIG['tarifftypes']['service'] : trans('service'),
	TARIFF_PHONE		=> 'Telefonia stacjonarna VoIP',										//
	TARIFF_TV		=> 'IPTV lub DTV',												//
//	TARIFF_DEPOSIT		=> isset($CONFIG['tarifftypes']['deposit']) ? $CONFIG['tarifftypes']['deposit'] : trans('deposit'),
	TARIFF_LEASE		=> isset($CONFIG['tarifftypes']['lease']) ? $CONFIG['tarifftypes']['lease'] : trans('lease'),
	TARIFF_ITSERVICE	=> isset($CONFIG['tarifftypes']['itservice']) ? $CONFIG['tarifftypes']['itservice'] : trans('it service'),
	TARIFF_VIP		=> isset($CONFIG['tarifftypes']['vip']) ? $CONFIG['tarifftypes']['vip'] : trans('VIP'),
	TARIFF_MULTIROOM	=> isset($CONFIG['tarifftypes']['multiroom']) ? $CONFIG['tarifftypes']['multiroom'] : trans('multi room'),
	TARIFF_SUSPENSION	=> isset($CONFIG['tarifftypes']['suspension']) ? $CONFIG['tarifftypes']['suspension'] : trans('suspension'),
	TARIFF_OTHER		=> 'Inne usługi',												//
	TARIFF_PHONE_ISDN	=> 'Telefonia stacjonarna POTS i ISDN',										//
	TARIFF_PHONE_MOBILE	=> 'Telefonia mobilna',												//
	TARIFF_INTERNET_MOBILE	=> 'Mobilny dostęp do Internetu',										//
);
asort($TARIFFTYPES);

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


// medium transmisyjne
define('LINKTYPES_CABLE',0);
define('LINKTYPES_RADIO',1);
define('LINKTYPES_FIBER',2);
define('LINKTYPES_CABLE_COAXIAL',3);

$LINKTYPES = array(
	LINKTYPES_FIBER		=> 'światłowodowe',
	LINKTYPES_RADIO		=> 'radiowe',
	LINKTYPES_CABLE		=> 'kablowe parowe miedziane',
	LINKTYPES_CABLE_COAXIAL => 'kablowe współosiowe miedziane',
);

// technologie

$LINKTECHNOLOGIES = array(
	0 => array( // medium miedziane parowe
		6 => '10 Mb/s Ethernet',
		7 => '100 Mb/s Fast Ethernet',
		8 => '1 Gigabit Ethernet',
		9 => '10 Gigabit Ethernet',
		1 => 'ADSL',
		2 => 'ADSL2',
		3 => 'ADSL2+',
		4 => 'VDSL',
		5 => 'VDSL2',
		10 => 'HDSL',
		11 => 'PDH',
		12 => 'POTS/ISDN',
	),
	1 => array( // medium radiowe
		100 => 'WiFi - 2,4 GHz',
		101 => 'WiFi - 5 GHz',
		113 => 'WiFi 2,4 i 5 GHz',
		104 => 'radiolinia',
		102 => 'WiMAX',
		103 => 'LMDS',
		105 => 'CDMA',
		106 => 'GPRS',
		107 => 'EDGE',
		108 => 'HSPA',
		109 => 'HSPA+',
		110 => 'DC-HSPA+',
		111 => 'MC-HSPA+',
		112 => 'LTE',
		114 => 'UMTS',
		115 => 'DMS',
	),
	2 => array( // medium światłowodowe
		208 => 'EPON',
		209 => 'GPON',
		203 => '10 Mb/s Ethernet',
		204 => '100 Mb/s Fast Ethernet',
		205 => '1 Gigabit Ethernet',
		206 => '10 Gigabit Ethernet',
		210 => '40 Gigabit Ethernet',
		207 => '100 Gigabit Ethernet',
		200 => 'CWDM',
		201 => 'DWDM',
		202 => 'SDH',
		211 => 'ATM',
		212 => 'PDH',
		213 => '(EURO)DOCSIS 1.x',
		214 => '(EURO)DOCSIS 2.x',
		215 => '(EURO)DOCSIS 3.x',
	),
	3 => array( // kablowe współosiowe miedziane
		50 => '(EURO)DOCSIS 1.x',
		51 => '(EURO)DOCSIS 2.x',
		52 => '(EURO)DOCSIS 3.x',
		53 => '10 Mb/s Ethernet',
	),
);
//asort($LINKTECHNOLOGIES[0]);
//asort($LINKTECHNOLOGIES[1]);
//asort($LINKTECHNOLOGIES[2]);
//asort($LINKTECHNOLOGIES[3]);

// dostosowanie prędkości do wymagań SIIS v5 2015r
$LINKSPEEDS = array(
	0		=> '0 Mb/s',	// + v5
	1000		=> '1 Mb/s',	// + v5
	2000		=> '2 Mb/s',	// + v5
	4000		=> '4 Mb/s',	// + v5
	8000		=> '8 Mb/s',	// + v5
	10000		=> '10 Mb/s',
	20000		=> '20 Mb/s',	// + v5
	30000		=> '30 Mb/s',	// + v5
//	25000		=> '25Mbit/s', 	// - v5 -> 20000
//	54000		=> '54Mbit/s', 	// - v5 -> 30000
	100000		=> '100 Mb/s',
	150000		=> '150 Mb/s',
//	200000		=> '200Mbit/s', // - v5 -> 150000
	250000		=> '250 Mb/s',	// + v5
//	300000		=> '300Mbit/s', // - v5 -> 250000
	500000		=> '500 Mb/s',	// + v5
	1000000		=> '1 Gb/s',
//	1250000		=> '1.2 Gb/s', // - v5 -> 1 Gb/s
	2500000		=> '2.5Gbit/s',
	10000000	=> '10 Gb/s',
	40000000	=> '40 Gb/s',	// + v5
	100000000	=> '100 Gb/s',
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

define('NIE',0);
define('TAK',1);
define('CZESCIOWO',2);


$TN = array(
    TAK		=> 'Tak',
    NIE		=> 'Nie'
);

$TNC = array(
    TAK		=> 'Tak',
    NIE		=> 'Nie',
    CZESCIOWO	=> 'Częściowo'
);

// zgodnie ze słownikiem UKE
define('TYP_BUDYNEK_BIUROWY',1);	// SIIS v5 [WW] [WO] [PL]
define('TYP_BUDYNEK_PRZEMYSLOWY',2);	// SIIS v5 [WW] [WO] [PL]
define('TYP_BUDYNEK_MIESZKALNY',3);	// SIIS v5 [WW] [WO] [PL]
define('TYP_OBIEKT_SAKRALNY',4);	// SIIS v5 [WW] [WO] [PL]
define('TYP_MASZT',5);			// SIIS v5 [WW] [WO] [PL]
define('TYP_WIEZA',6);			// SIIS v5 [WW] [WO] [PL]
define('TYP_KONTENER',7);		// SIIS v5 [WW] [WO] [PL]
define('TYP_SZAFA_ULICZNA',8);		// SIIS v5 [WW] [WO] [PL]
define('TYP_SKRZYNKA',9);		// SIIS v5 [WW] [WO] [PL]
define('TYP_STUDNIA_KABLOWA',10);	// SIIS v5 [WW] [WO] [PL]
define('TYP_KOMIN',11);			// SIIS v5 [WW] [WO] [PL]
define('TYP_SLUP',12);			// SIIS v5 [WW] [WO] [PL]
//define('TYP_SZAFKA_NASCIENNA',13); -> 9
//define('TYP_SZAFKA_NASCIENNA_RACK',14); -> 9
//define('TYP_SZAFKA_STOJACA',15); -> 9
//define('TYP_SZAFKA_STOJACA_RACK',16); -> 9
define('TYP_SLUPEK_TK',17);		// SIIS v5           [PL] (łączenie kabli)
//define('TYP_MUFA',18);  -> 23
//define('TYP_ELEWATOR',19); -> 2
define('TYP_BUDYNEK_USLUGOWY',20);	// SIIS v5 [WW] [WO] [PL]
define('TYP_BUDYNEK_PUBLICZNY',21);	// SIIS v5 [WW] [WO] [PL]
define('TYP_OBIEKT_SIECI_ELEKTRO',22);	// SIIS v5 [WW] [WO] [PL]
define('TYP_ZASOBNIK',23);		// SIIS v5           [PL] ( łączenie kabli )


$BUILDINGS = array(
    TYP_BUDYNEK_BIUROWY		=> 'budynek biurowy',
    TYP_BUDYNEK_PRZEMYSLOWY	=> 'budynek przemysłowy',
    TYP_BUDYNEK_MIESZKALNY	=> 'budynek mieszkalny',
    TYP_BUDYNEK_USLUGOWY	=> 'budynek usługowy',
    TYP_BUDYNEK_PUBLICZNY	=> 'budynek użyteczności publicznej',
    TYP_OBIEKT_SAKRALNY		=> 'obiekt sakralny',
    TYP_OBIEKT_SIECI_ELEKTRO	=> 'obiekt sieci elektroenergetycznej',
    TYP_MASZT			=> 'maszt',
    TYP_WIEZA			=> 'wieża',
    TYP_KONTENER		=> 'kontener',
    TYP_SZAFA_ULICZNA		=> 'szafa uliczna',
    TYP_SKRZYNKA		=> 'skrzynka',
    TYP_STUDNIA_KABLOWA		=> 'studnia kablowa',
    TYP_KOMIN			=> 'komin',
    TYP_SLUP			=> 'słup',
//    TYP_SZAFKA_NASCIENNA	=> 'szafka naścienna',
//    TYP_SZAFKA_NASCIENNA_RACK	=> 'szafka naścienna RACK',
//    TYP_SZAFKA_STOJACA		=> 'szafka stojąca',
//    TYP_SZAFKA_STOJACA_RACK	=> 'szafka stojąca RACK',
//    TYP_SLUPEK_TK		=> 'słupek telekomunikacyjny',
//    TYP_MUFA			=> 'mufa',
//    TYP_ELEWATOR		=> 'elewator',
//    TYP_ZASOBNIK		=> 'zasobnik kablowy',
);
asort($BUILDINGS);

// status węzła
define('NSTATUS_CLOSED',0); // zakończony
define('NSTATUS_IMPLEMENTATION',1); // w realizacji
define('NSTATUS_PLANNED',2); // planowany

$NSTATUS = array(
    NSTATUS_CLOSED		=> 'zakończone',
    NSTATUS_IMPLEMENTATION	=> 'w realizacji',
    NSTATUS_PLANNED		=> 'planowane',
);
asort($NSTATUS);

define('NODE_OWN',1);
define('NODE_FOREIGN',2);
define('NODE_ALIEN',3);
$TNODE = array(
    NODE_OWN		=> 'Węzeł własny',
    NODE_FOREIGN	=> 'Węzeł współdzielony z innym podmiotem',
    NODE_ALIEN		=> 'Węzeł obcy',
);

define('DEV_PASSIVE',0);
define('DEV_ACTIVE',1);
$DEVTYPE = array(
    DEV_ACTIVE		=> 'aktywne',
    DEV_PASSIVE		=> 'pasywne'
);

define('LAYER_ACCESS',1);		// warstwa dostępowa
define('LAYER_DISTRIBUTION',2);	// warstwa dystrybucyjna
define('LAYER_BACKBONE',3);		// warstwa szkieletowa
$LAYERTYPE = array(
    LAYER_ACCESS	=> 'dostępowa',
    LAYER_DISTRIBUTION	=> 'dystrybucyjna',
    LAYER_BACKBONE	=> 'szkieletowa'
);

// rodzaj traktu
define('TRACT_ABOVEGROUND',1);
define('TRACT_UNDERGROUND',2);
define('TRACT_UNDERGROUND_SEWER',3);
define('TRACT_UNDERGROUND_PIPELINE',4);
define('TRACT_RIGGING',5);
define('TRACT_CANALROAD',6);
define('TRACT_ABOVEGROUND_DEDICATE',7);


$TRACTTYPE = array(
    TRACT_UNDERGROUND		=> 'podziemny',
    TRACT_UNDERGROUND_SEWER	=> 'podziemny w kanalizacji',
    TRACT_UNDERGROUND_PIPELINE	=> 'podziemny w rurciągu',
    TRACT_CANALROAD		=> 'podziemny w kanale technicznym drogi',
    TRACT_ABOVEGROUND		=> 'napowietrzny',
    TRACT_RIGGING		=> 'napowietrzny na lini energetycznej',
    TRACT_ABOVEGROUND_DEDICATE	=> 'napowietrzny na dedykowanej podbudowie słupowej',
);

$TRACTNODE = array(
    1	=> 'bezprzewodowe',
    2	=> 'infrastruktura budynku',
    3	=> 'przyłącze napowietrzne',
    4	=> 'przyłącze podziemne w kanalizacji',
    5	=> 'przyłącze ziemne',
);

/* ************* START Światłowody i ich świat ********************** */
/*
    FIBRE -> włókno
    FIBER -> światłowód
    TUBE -> tuba
*/
// rodzaje włókien
define('FIBRE_G651',1);		// SIIS v5
define('FIBRE_G652',2);		// SIIS v5
define('FIBRE_G653',3);		// SIIS v5
define('FIBRE_G654',4);		// SIIS v5
define('FIBRE_G655',5);		// SIIS v5
define('FIBRE_G656',6);		// SIIS v5
define('FIBRE_G657',7);		// SIIS v5

$TYPEFIBRE = array(
    FIBRE_G651	=> 'G.651',	// SIIS v5
    FIBRE_G652	=> 'G.652',	// SIIS v5
    FIBRE_G653	=> 'G.653',	// SIIS v5
    FIBRE_G654	=> 'G.654',	// SIIS v5
    FIBRE_G655	=> 'G.655',	// SIIS v5
    FIBRE_G656	=> 'G.656',	// SIIS v5
    FIBRE_G657	=> 'G.657',	// SIIS v5
);


/* ************* END Światłowody i ich świat ********************** */


// podstawy prawne współdzielenia węzłów
define('POD_UMNET',1);
define('POD_UMBSA',2);
define('POD_UMLLU',3);
define('POD_ODSP',4);
define('POD_USLUGA',5);
define('POD_USLUGA2',6);
define('POD_IP',7);
define('POD_TRANSMISJA',8);
define('POD_PSTN',9);
define('POD_KOLOKACJA',10);
define('POD_DZIERZAWA',11);
define('POD_OTHER',100);

$PODSTAWA = array(
    POD_UMNET		=> 'Umowa o dostęp do sieci telekomunikacyjnej',
    POD_UMBSA		=> 'Umowa BSA na sieci innego podmiotu',
    POD_UMLLU		=> 'Umowa LLU na sieci innego podmiotu',
    POD_ODSP		=> 'Prosta odsprzedaż usług na sieci innego podmiotu',
    POD_USLUGA		=> 'Usługa dostępu szerokopasmowego w modelu VNO',
    POD_USLUGA2		=> 'Inne usługi w modelu VNO',
    POD_IP		=> 'Świadczenie lub zakup usług IP Tranzyt i IP Peering',
    POD_TRANSMISJA	=> 'Świadczenie lub zakup usługi transmisji danych',
    POD_PSTN		=> 'Umowa o połączeniu sieci PSTN',
    POD_KOLOKACJA	=> 'Kolokacja i najem',
    POD_DZIERZAWA	=> 'Dzierżawa',
    POD_OTHER		=> 'Inna',
);

// Investment project types
define('INV_PROJECT_REGULAR', 0);
define('INV_PROJECT_SYSTEM', 1);

define('PROJECT_PROGRAM_PO_IG',1);	// SIIS v5
define('PROJECT_PROGRAM_PO_RPW',2);	// SIIS v5

$PROJECTPROGRAM = array(
    PROJECT_PROGRAM_PO_IG	=> 'PO-IG',
    PROJECT_PROGRAM_PO_RPW	=> 'PO-RPW',
);


$PROJECTACTION = array(
    PROJECT_PROGRAM_PO_IG 	=> array(
				1 => '8.3',
				2 => '8.4'
				),
    PROJECT_PROGRAM_PO_RPW	=> array(
				1 => 'II.1'
				),
);

// Forma korzystania z infrastruktury obcej 
define('WAYOFUSING_01',1);
define('WAYOFUSING_02',2);
define('WAYOFUSING_03',3);
define('WAYOFUSING_04',4);
define('WAYOFUSING_05',5);
define('WAYOFUSING_06',6);
define('WAYOFUSING_07',7);
define('WAYOFUSING_08',8);
define('WAYOFUSING_09',9);
define('WAYOFUSING_10',10);
define('WAYOFUSING_11',11);

$WAYOFUSING = array(
    WAYOFUSING_01	=> 'Współwłasność z innym podmiotem',
    WAYOFUSING_02	=> 'Usługa LLU na sieci innego podmiotu',
    WAYOFUSING_03	=> 'Usługa BSA na sieci innego podmiotu',
    WAYOFUSING_04	=> 'Usługa WLR na sieci innego podmiotu',
    WAYOFUSING_05	=> 'Prosta odsprzedaż usług na sieci innego podmiotu',
    WAYOFUSING_06	=> 'Usługa dostępu szerokopasmowego w modelu MVNO',
    WAYOFUSING_07	=> 'Inne usługi w modelu MVNO',
    WAYOFUSING_08	=> 'Dzierżawa włókna światłowodowego',
    WAYOFUSING_09	=> 'Usługi transmisji danych na sieci innego podmiotu',
    WAYOFUSING_10	=> 'Usługi IP Transit na sieci innego podmiotu',
    WAYOFUSING_11	=> 'Numer dostępu do sieci',
);

$RAD_TERMINATE_CAUSE = array(
    'User-Request'			=> 'User Request',
    'Lost-Carrier'			=> 'Lost Carrier',
    'Lost-Service'			=> 'Lost Service',
    'Idle-Timeout'			=> 'Idle Timeout',
    'Session-Timeout'			=> 'Session Timeout',
    'Admin-Reset'			=> 'Admin Reset',
    'Admin-Reboot'			=> 'Admin Reboot',
    'Port-Error'			=> 'Port Error',
    'NAS-Error'				=> 'NAS Error',
    'NAS-Request'			=> 'NAS Request',
    'NAS-Reboot'			=> 'NAS Reboot',
    'Port-Unneeded'			=> 'Port Unneeded',
    'Port-Preempted'			=> 'Port Preempted',
    'Port-Suspended'			=> 'Port Suspended',
    'Service-Unavailable'		=> 'Service Unavailable',
    'Callback'				=> 'Callback',
    'User-Error'			=> 'User Error',
    'Host-Request'			=> 'Host Request',
    'Supplicant-Restart'		=> 'Supplicant Restart',
    'Reauthentication-Failure'		=> 'Reauthentication Failure',
    'Port-Reinitialized'		=> 'Port Reinitialized',
    'Port-Administratively-Disabled'	=> 'Port Administratively Disabled',
    'Lost-Power'			=> 'Lost Power',
);

$RAD_SERVICE_TYPE = array(
    'Login'				=> 'Login',
    'Framed'				=> 'Framed',
    'Framed-User'			=> 'Framed User',
    'Callback-Login'			=> 'Callback Login',
    'Callback-Framed'			=> 'Callback Framed',
    'Outbound'				=> 'Outbound',
    'Administrative'			=> 'Administrative',
    'NAS-Prompt'			=> 'NAS Prompt',
    'Authenticate-Only'			=> 'Authenticate Only',
    'Callback-NAS-Prompt'		=> 'Callback NAS Prompt',
    'Call-Check'			=> 'Call Check',
    'Callback-Administrative'		=> 'Callback Administrative',
    'Voice'				=> 'Voice',
    'Fax'				=> 'Fax',
    'Modem-Relay'			=> 'Modem Relay',
    'IAPP-Register'			=> 'IAPP-Register',
    'IAPP-AP-Check'			=> 'IAPP-AP-Check',
    'Authorize-Only'			=> 'Authorize Only',
    'Framed-Management'			=> 'Framed-Management',
);

// https://www.iana.org/assignments/radius-types/radius-types.xhtml#radius-types-4


if(isset($SMARTY))
{
	$SMARTY->assign('_CTYPES',$CTYPES);
	$SMARTY->assign('_CSTATUS',$CSTATUS);
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
	$SMARTY->assign('_LINKTECHNOLOGIES', $LINKTECHNOLOGIES);
	$SMARTY->assign('_LINKSPEEDS', $LINKSPEEDS);
	$SMARTY->assign('_BOROUGHTYPES', $BOROUGHTYPES);
	$SMARTY->assign('_PASSWDEXPIRATIONS', $PASSWDEXPIRATIONS);
	$SMARTY->assign('_MSG_LOG',$MSG_LOG);
	$SMARTY->assign('_MOD',$MOD);
	$SMARTY->assign('_TN',$TN);
	$SMARTY->assign('_TNC',$TNC);
	$SMARTY->assign('_BUILDINGS',$BUILDINGS);
	$SMARTY->assign('_TNODE',$TNODE);
	$SMARTY->assign('_DEVTYPE',$DEVTYPE);
	$SMARTY->assign('_LAYERTYPE',$LAYERTYPE);
	$SMARTY->assign('_PODSTAWA',$PODSTAWA);
	$SMARTY->assign('_RAD_TERMINATE_CAUSE',$RAD_TERMINATE_CAUSE);
	$SMARTY->assign('_RAD_SERVICE_TYPE',$RAD_SERVICE_TYPE);
	$SMARTY->assign('_TRACTTYPE',$TRACTTYPE);
	$SMARTY->assign('_TRACTNODE',$TRACTNODE);
	$SMARTY->assign('_NSTATUS',$NSTATUS);
	$SMARTY->assign('_TYPEFIBRE',$TYPEFIBRE);
	$SMARTY->assign('_WAYOFUSING',$WAYOFUSING);
	$SMARTY->assign('_PROJECTPROGRAM',$PROJECTPROGRAM);
	$SMARTY->assign('_PROJECTACTION',$PROJECTACTION);
}

define('DEFAULT_NUMBER_TEMPLATE', '%N/LMS/%Y');
define('INETLMS_ADV_URL','http://www.inetlms.pl/adv/getinfo.php');
define('INETLMS_REGISTER_URL','http://www.inetlms.pl/adv/registers.php');

?>
