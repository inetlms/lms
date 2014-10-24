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
define('DOC_OTHER', -128);
define('DOC_BILLING',-10);

$DOCTYPES = array(
    DOC_BILLING		=> 'billing',
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
define('TARIFF_PHONE_ISDN',12);
define('TARIFF_PHONE_MOBILE',13);
define('TARIFF_INTERNET_MOBILE',14);
define('TARIFF_OTHER', -1);

$TARIFFTYPES = array(
	TARIFF_INTERNET		=> 'Stacjonarny dostęp do Internetu',
	TARIFF_HOSTING		=> isset($CONFIG['tarifftypes']['hosting']) ? $CONFIG['tarifftypes']['config'] : trans('hosting'),
	TARIFF_SERVICE		=> isset($CONFIG['tarifftypes']['service']) ? $CONFIG['tarifftypes']['service'] : trans('service'),
	TARIFF_PHONE		=> 'Telefonia stacjonarna VoIP',
	TARIFF_TV		=> 'IPTV lub DTV',
//	TARIFF_DEPOSIT		=> isset($CONFIG['tarifftypes']['deposit']) ? $CONFIG['tarifftypes']['deposit'] : trans('deposit'),
	TARIFF_LEASE		=> isset($CONFIG['tarifftypes']['lease']) ? $CONFIG['tarifftypes']['lease'] : trans('lease'),
	TARIFF_ITSERVICE	=> isset($CONFIG['tarifftypes']['itservice']) ? $CONFIG['tarifftypes']['itservice'] : trans('it service'),
	TARIFF_VIP		=> isset($CONFIG['tarifftypes']['vip']) ? $CONFIG['tarifftypes']['vip'] : trans('VIP'),
	TARIFF_MULTIROOM	=> isset($CONFIG['tarifftypes']['multiroom']) ? $CONFIG['tarifftypes']['multiroom'] : trans('multi room'),
	TARIFF_SUSPENSION	=> isset($CONFIG['tarifftypes']['suspension']) ? $CONFIG['tarifftypes']['suspension'] : trans('suspension'),
	TARIFF_OTHER		=> 'Inne usługi',
	TARIFF_PHONE_ISDN	=> 'Telefonia stacjonarna POTS i ISDN',
	TARIFF_PHONE_MOBILE	=> 'Telefonia mobilna',
	TARIFF_INTERNET_MOBILE	=> 'Mobilny dostęp do Internetu',
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
	LINKTYPES_CABLE		=> 'kablowe parowe miedziane',
	LINKTYPES_RADIO		=> 'radiowe',
	LINKTYPES_FIBER		=> 'światłowodowe',
	LINKTYPES_CABLE_COAXIAL => 'kablowe współosiowe miedziane',
);
asort($LINKTYPES);

// technologie

$LINKTECHNOLOGIES = array(
	0 => array(
		1 => 'ADSL',
		2 => 'ADSL2',
		3 => 'ADSL2+',
		4 => 'VDSL',
		5 => 'VDSL2',
		6 => '10 Mb/s Ethernet',
		7 => '100 Mb/s Fast Ethernet',
		8 => '1 Gigabit Ethernet',
		9 => '10 Gigabit Ethernet',
//		50 => '(EURO)DOCSIS 1.x',
//		51 => '(EURO)DOCSIS 2.x',
//		52 => '(EURO)DOCSIS 3.x',
	),
	1 => array(
		100 => 'WiFi - 2,4 GHz',
		101 => 'WiFi - 5 GHz',
		102 => 'WiMAX',
		103 => 'LMDS',
		104 => 'radiolinia',
		105 => 'CDMA',
		106 => 'GPRS',
		107 => 'EDGE',
		108 => 'HSPA',
		109 => 'HSPA+',
		110 => 'DC-HSPA+',
		111 => 'MC-HSPA+',
		112 => 'LTE',
	),
	2 => array(
		200 => 'CWDM',
		201 => 'DWDM',
		202 => 'SDH',
		203 => '10 Mb/s Ethernet',
		204 => '100 Mb/s Fast Ethernet',
		205 => '1 Gigabit Ethernet',
		206 => '10 Gigabit Ethernet',
		207 => '100 Gigabit Ethernet',
		208 => 'EPON',
		209 => 'GPON',
	),
	3 => array(
		50 => '(EURO)DOCSIS 1.x',
		51 => '(EURO)DOCSIS 2.x',
		52 => '(EURO)DOCSIS 3.x',
	),
);


$LINKSPEEDS = array(
	10000		=> '10Mbit/s',
	25000		=> '25Mbit/s',
	54000		=> '54Mbit/s',
	100000		=> '100Mbit/s',
	150000		=> '150Mbit/s',
	200000		=> '200Mbit/s',
	300000		=> '300Mbit/s',
	1000000		=> '1Gbit/s',
	1250000		=> '1.2Gbit/s',
	2500000		=> '2.5Gbit/s',
	10000000	=> '10Gbit/s',
	100000000	=> '100Gbit/s',
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
define('TYP_BUDYNEK_BIUROWY',1);
define('TYP_BUDYNEK_PRZEMYSLOWY',2);
define('TYP_BUDYNEK_MIESZKALNY',3);
define('TYP_OBIEKT_SAKRALNY',4);
define('TYP_MASZT',5);
define('TYP_WIEZA',6);
define('TYP_KONTENER',7);
define('TYP_SZAFA_ULICZNA',8);
define('TYP_SKRZYNKA',9);
define('TYP_STUDNIA_KABLOWA',10);
define('TYP_KOMIN',11);
// na własne potrzeby
define('TYP_SLUP',12); // słup
define('TYP_SZAFKA_NASCIENNA',13);
define('TYP_SZAFKA_NASCIENNA_RACK',14);
define('TYP_SZAFKA_STOJACA',15);
define('TYP_SZAFKA_STOJACA_RACK',16);
define('TYP_SLUPEK_TK',17); // słupek telekomunikacyjny
define('TYP_MUFA',18); 
define('TYP_ELEWATOR',19);

$BUILDINGS = array(
    TYP_BUDYNEK_BIUROWY		=> 'budynek biurowy',
    TYP_BUDYNEK_PRZEMYSLOWY	=> 'budynek przemysłowy',
    TYP_BUDYNEK_MIESZKALNY	=> 'budynek mieszkalny',
    TYP_OBIEKT_SAKRALNY		=> 'obiekt sakralny',
    TYP_MASZT			=> 'maszt',
    TYP_WIEZA			=> 'wieża',
    TYP_KONTENER		=> 'kontener',
    TYP_SZAFA_ULICZNA		=> 'szafa uliczna',
    TYP_SKRZYNKA		=> 'skrzynka',
    TYP_STUDNIA_KABLOWA		=> 'studnia kablowa',
    TYP_KOMIN			=> 'komin',
    TYP_SLUP			=> 'słup',
    TYP_SZAFKA_NASCIENNA	=> 'szafka naścienna',
    TYP_SZAFKA_NASCIENNA_RACK	=> 'szafka naścienna RACK',
    TYP_SZAFKA_STOJACA		=> 'szafka stojąca',
    TYP_SZAFKA_STOJACA_RACK	=> 'szafka stojąca RACK',
    TYP_SLUPEK_TK		=> 'słupek telekomunikacyjny',
    TYP_MUFA			=> 'mufa',
    TYP_ELEWATOR		=> 'elewator',
);
asort($BUILDINGS);

define('NODE_OWN',1);
define('NODE_FOREIGN',2);
$TNODE = array(
    NODE_OWN		=> 'Węzeł własny',
    NODE_FOREIGN	=> 'Węzeł współdzielony z innym podmiotem',
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

$TYPETRACT = array(
    TRACT_ABOVEGROUND		=> 'nadziemny',
    TRACT_UNDERGROUND		=> 'podziemny',
    TRACT_UNDERGROUND_SEWER	=> 'podziemny w kanalizacji',
    TRACT_UNDERGROUND_PIPELINE	=> 'podziemny w rurciągu',
    TRACT_RIGGING		=> 'podwieszany na lini energetycznej',
    TRACT_CANALROAD		=> 'w kanale technicznym drogi',
    
);
asort($TYPETRACT);

// podstawy prawne współdzielenia węzłów
define('POD_UMNET',1);
define('POD_UMBSA',2);
define('POD_UMLLU',3);
define('POD_ODSP',4);
define('POD_USLUGA',5);
define('POD_OTHER',100);

$PODSTAWA = array(
    POD_NET	=> 'Umowa o dostęp do sieci telekomunikacyjnej',
    POD_UMBSA	=> 'Umowa BSA na sieci innego podmiotu',
    POD_UMLLU	=> 'Umowa LLU na sieci innego podmiotu',
    POD_ODSP	=> 'Prosta odsprzedaż usług na sieci innego podmiotu',
    POD_USLUGA	=> 'Usługa dostępu szerokopasmowego w modelu VNO',
    POD_OTHER	=> 'Inna',
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
	$SMARTY->assign('_TYPETRACT',$TYPETRACT);
}

define('DEFAULT_NUMBER_TEMPLATE', '%N/LMS/%Y');
define('INETLMS_ADV_URL','http://www.inetlms.pl/adv/getinfo.php');
define('INETLMS_REGISTER_URL','http://www.inetlms.pl/adv/registers.php');

?>