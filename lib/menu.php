<?php

/*
 * iNET LMS 
 *
 * (C) Copyright 2001-2012 LMS Developers
 *
 * Please, see the doc/AUTHORS for more information about authors!
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License Version 2 as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 * USA.
 *
 * $Id$
 */

$menu = array(
'admin' => array(
			'name' => trans('Administration'),
			'img' =>'users.gif',
			'link' =>'?m=welcome',
			'tip' => trans('System information and management'),
			'accesskey' =>'i',
			'prio' => 10,
			'index' => MODULES_ADMIN,
			'submenu' => array(
				array(
					'name' => trans('Info'),
					'link' =>'?m='.$CONFIG['phpui']['default_module'],
					'tip' => trans('Basic system information'),
					'prio' => 10,
				),
				array(
					'name' => trans('Users'),
					'link' =>'?m=userlist',
					'tip' => trans('User list'),
					'prio' => 20,
				),
				array(
					'name' => trans('New User'),
					'link' =>'?m=useradd',
					'tip' => trans('New User'),
					'prio' => 30,
				),
				array(
					'name' => trans('Backups'),
					'link' =>'?m=dblist',
					'tip' => trans('Allows you to manage database backups'),
					'prio' => 40,
				),
				
				array(
					'name' => trans('Syslog'),
					'link' => '?m=syslog&sl_df='.date('Y/m/d',strtotime("-2 week",time())),
					'prio' => 50,
				),
				array(
					'name' => 'Changelog',
					'link' =>'?m=changelog',
					'tip' => '',
					'prio' => 60,
				),
				array(
					'name' => 'Sponsorzy',
					'link' =>'?m=sponsorzy',
					'tip' => 'Lista firm które przyczyniły się do rozwoju iNET LMS',
					'prio' => 70,
				),
				array(
					'name' => trans('Copyrights'),
					'link' =>'?m=copyrights',
					'tip' => trans('Copyrights, authors, etc.'),
					'prio' => 200,
				),
				array(
					'name' => 'Rejestracja',
					'link' => '?m=register',
					'tip' => 'Informacje o rejestracji instalacji iNET LMS',
					'prio' => 201,
				),
			),
		),

		'customers' => array(
			'name' => trans('Customers'),
			'img' =>'customer.gif',
			'link' =>'?m=customerlist',
			'tip' => trans('Customers Management'),
			'accesskey' =>'u',
			'prio' => 20,
			'index' => MODULES_CUSTOMERS,
			'submenu' => array(
				array(
					'name' => trans('List'),
					'link' =>'?m=customerlist',
					'tip' => trans('List of Customers'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Customer'),
					'link' =>'?m=customeradd',
					'tip' => trans('Allows you to add new customer'),
					'prio' => 20,
				),
				array(
					'name' => trans('Search'),
					'link' =>'?m=customersearch',
					'tip' => trans('Allows you to find customer'),
					'prio' => 30,
				),

				array(
					'name' => 'Call Center',
					'link' =>'?m=infocenterlist&cid=',
					'tip' => '',
					'prio' => 52,
				),
				array(
					'name' => trans('Notices'),
					'link' =>'?m=customerwarn',
					'tip' => trans('Allows you to send notices to customers'),
					'prio' => 60,
				),
				array(
					'name' => trans('Reports'),
					'link' =>'?m=customerprint',
					'tip' => trans('Lists and reports printing'),
					'prio' => 70,
				),
			),		 
		),

		'nodes' => array(
			'name' => trans('Nodes'),
			'img' =>'node.gif',
			'link' =>'?m=nodelist',
			'tip' => trans('Nodes Management'),
			'accesskey' =>'k',
			'prio' => 30,
			'index' => MODULES_NODES,
			'submenu' => array(
				array(
					'name' => trans('List'),
					'link' => '?m=nodelist',
					'tip' => trans('List of nodes'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Node'),
					'link' => '?m=nodeadd',
					'tip' => trans('Allows you to add new node'),
					'prio' => 20,
				),
				array(
					'name' => trans('Search'),
					'link' => '?m=nodesearch',
					'tip' => trans('Allows you to search node'),
					'prio' => 30,
				),

				array(
					'name' => trans('Notices'),
					'link' => '?m=nodewarn',
					'tip' => trans('Allows you to send notices to customers'),
					'prio' => 60,
				),
				array(
					'name' => 'Historia zmian IP',
					'link' => '?m=iphistory',
					'tip' => '',
					'prio' => 65,
				),
				array(
					'name' => trans('Reports'),
					'link' => '?m=nodeprint',
					'tip' => trans('Lists and reports printing'),
					'prio' => 70,
				),
			),
		),

		'VoIP' => array(
			'name' => trans('VoIP'),
			'img' =>'voip.gif',
			'tip' => trans('VoIP Management'),
			'accesskey' =>'v',
			'prio' => 40,
			'index' => MODULES_VOIP,
			'submenu' => array(
				array(
					'name' => trans('List'),
					'link' => '?m=voipaccountlist',
					'tip' => trans('List of Accounts'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Account'),
					'link' => '?m=voipaccountadd',
					'tip' => trans('Allows you to add the new VoIP account'),
					'prio' => 20,
				),
				array(
					'name' => trans('Search'),
					'link' => '?m=voipaccountsearch',
					'tip' => trans('Allows you to search VoIP account'),
					'prio' => 30,
				),
			),
		),

		'VoIPC5' => array(
			'name' => 'VoIP Hiperus C5',
			'img' =>'voip.gif',
			'link' =>'',
			'tip' => 'Telefonia Internetowa Hiperus',
			'accesskey' => '',
			'prio' => 50,
			'index'	=> MODULES_VOIPC5,
			'submenu' => array(
				array(
					'name' => 'Lista kont',
					'link' => '?m=hv_accountlist',
					'tip' => 'Lista Klientów',
					'prio' => 10,
				),
				array(
					'name' => 'Nowe konto',
					'link' => '?m=hv_accountadd',
					'tip' => 'Tworzenie nowego konta VoIP w  Hiperus C5',
					'prio' => 30,
				),
				array(
					'name' => 'Numery PSTN',
					'link' => '?m=hv_pstnrangelist',
					'tip' => 'Lista pul numerów PSTN',
					'prio' => 50,
				),
				array(
					'name' => 'Lista Terminali',
					'link' => '?m=hv_terminallist',
					'tip' => 'Lista Terminali',
					'prio' => 60,
				),
				array(
					'name' => 'Konfiguracja',
					'link' => '?m=configlist&page=1&s=hiperus_c5&n=',
					'tip' => '',
					'prio' => 70,
				),
			),
		),

		'netdevices' => array(
			'name' => 'Osprzęt sieciowy',
			'img' =>'netdev.gif',
			'link' =>'?m=netdevlist',
			'tip' => trans('Network Devices Management'),
			'accesskey' =>'o',
			'prio' => 80,
			'index' => MODULES_NETDEVICES,
			'submenu' => array(
				array(
					'name' => 'Interfejsy sieciowe',
					'link' => '?m=netdevlist',
					'tip' => trans('Network devices list'),
					'prio' => 10,
				),
				array(
					'name' => 'Nowy interfejs',
					'link' => '?m=netdevadd',
					'tip' => trans('Add new device'),
					'prio' => 20,
				),
				array(
					'name' => 'Węzły',
					'link' => '?m=networknodelist',
					'tip' => 'Węzły sieciowe',
					'prio' => 30,
				),
				array(
					'name' => 'Nowy Węzeł',
					'link' => '?m=networknodeadd',
					'tip' => 'Dodaj nowy węzeł sieciowy',
					'prio' => 40,
				),
				array(
					'name' => trans('Hosts'),
					'link' => '?m=hostlist',
					'tip' => trans('List of Hosts'),
					'prio' => 50,
				),
				array(
					'name' => 'Linie telekomunikacyjne',
					'link' => '?m=teleline',
					'tip' => 'Lista linii telekomunikacyjnych',
					'prio' => 60,
				),
				
				array(
					'name' => trans('Map'),
					'link' => '?m=netdevmap',
					'tip' => trans('Network map display'),
					'prio' => 80,
				),
				array(
					'name' => trans('Search'),
					'link' => '?m=netdevsearch',
					'tip' => trans('Allows you to search device'),
					'prio' => 90,
				),
				
			
// ------------------- STARY RAPORT -----------------------------
//				array(
//					'name' => trans('UKE report'),
//					'link' => '?m=uke',
//					'tip' => 'Raport SIIS v3, rozwiązanie z LMS 1.11-git',
//					'prio' => 50,
//				),
			),
		),
		
		'monitoring' => array(
			'name' => 'Monitoring',
			'img' =>'Radar.icon.gif',
			'link' =>'',
			'tip' => '',
			'accesskey' =>'',
			'prio' =>100,
			'index' => MODULES_MONITORING,
			'submenu' => array(
				array(
					'name' => 'Urządzenia sieciowe',
					'link' => '?m=monitnodelist&td=netdev',
					'tip' => 'Lista monitorowanych urządzeń sieciowych',
					'prio'=>'10'
				),
				array(
					'name' => 'Urządzenia klientów',
					'link' => '?m=monitnodelist&td=nodes',
					'tip' => 'Lista monitorowanych komputerów klientów',
					'prio'=>'20'
				),
				array(
					'name' => 'Urządzenia własne',
					'link' => '?m=monitownlist',
					'tip' => 'Lista monitorowanych własnych urządzeń',
					'prio'=>'30'
				),
				array(
					'name' => 'Konfiguracja',
					'link' => '?m=configlist&page=1&s=monit&n=',
					'tip' => 'Podstawowa konfiguracja monitoringu',
					'prio'=>'50'
				),
			)
		),
		

		'networks' => array(
			'name' => trans('IP Networks'),
			'img' =>'network.gif',
			'link' =>'?m=netlist',
			'tip' => trans('IP Address Pools Management'),
			'accesskey' =>'t',
			'prio' => 110,
			'index' => MODULES_NETWORKS,
			'submenu' => array(
				array(
					'name' => trans('List'),
					'link' => '?m=netlist',
					'tip' => trans('List of IP pools'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Network'),
					'link' => '?m=netadd',
					'tip' => trans('Add new address pool'),
					'prio' => 20,
				),
			),
		),
		'contractors' => array(
			'name' => trans('Contractors'),
			'img' =>'customer.gif',
			'link' =>'?m=customerlist',
			'tip' => trans('Contractors Management'),
			'accesskey' =>'u',
			'prio' => 120,
			'index' => MODULES_CONTRACTORS,
			'submenu' => array(
				array(
					'name' => trans('List'),
					'link' =>'?m=contractorlist',
					'tip' => trans('List of Contractors'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Contractor'),
					'link' =>'?m=contractoradd',
					'tip' => trans('Allows you to add new contractor'),
					'prio' => 20,
				),
				
				
//				array(
//					'name' => trans('New Group'),
//					'link' =>'?m=contractorgroupadd',
//					'tip' => trans('Allows you to add new group'),
//					'prio' => 50,
//				),
/*
				array(
					'name' => trans('Reports'),
					'link' =>'?m=customerprint',
					'tip' => trans('Lists and reports printing'),
					'prio' => 70,
				),
*/
			),
		),
		
		'tariffs' => array(
			'name' => 'Taryfy',
			'img' =>'tariffs.png',
			'link' =>'?m=tarifflist',
			'tip' => 'Zarządzanie taryfami',
			'accesskey' =>'f',
			'prio' => 130,
			'index' => MODULES_TARIFFS,
			'submenu' => array(
				array(
					'name' => trans('Subscriptions List'),
					'link' => '?m=tarifflist',
					'tip' => trans('List of subscription fees'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Subscription'),
					'link' => '?m=tariffadd',
					'tip' => trans('Add new subscription fee'),
					'prio' => 20,
				),
				array(
					'name' => trans('Promotions'),
					'link' => '?m=promotionlist',
					'tip' => trans('List of promotions'),
					'prio' => 90,
				),
			),
		),

		'finances' => array(
			'name' => trans('Finances'),
			'img' =>'money.gif',
			'link' =>'?m=tarifflist',
			'tip' => 'Zarządzanie finansami sieci',
			'accesskey' =>'f',
			'prio' => 140,
			'index' => MODULES_FINANCES,
			'submenu' => array(
				array(
					'name' => trans('Payments List'),
					'link' => '?m=paymentlist',
					'tip' => trans('List of standing payments'),
					'prio' => 30,
				),
				array(
					'name' => trans('New Payment'),
					'link' => '?m=paymentadd',
					'tip' => trans('Add new standing payment'),
					'prio' => 40,
				),
				array(
					'name' => trans('Balance Sheet'),
					'link' => '?m=balancelist',
					'tip' => trans('Table of financial operations'),
					'prio' => 50,
				),
				array(
					'name' => 'Historia importów', 
					'link' => '?m=cashimportlist',
					'tip' => 'Hisoria zaimportowanych płatności',
					'prio' => 55,
				),
				array(
					'name' => trans('New Balance'),
					'link' => '?m=balancenew',
					'tip' => trans('Add new financial operation'),
					'prio' => 60,
				),
				array(
					'name' => trans('Invoices List'),
					'link' => '?m=invoicelist',
					'tip' => trans('List of invoices'),
					'prio' => 70,
				),
				array(
					'name' => trans('New Invoice'),
					'link' => '?m=invoicenew&action=init',
					'tip' => trans('Generate invoice'),
					'prio' => 75,
				),
				array(
					'name' => trans('New Pro Forma Invoice'),
					'link' => '?m=invoicenew&action=init&proforma',
					'tip' => trans('Generate invoice'),
					'prio' => 76,
				),
				array(
					'name' => trans('Debit Notes List'),
					'link' => '?m=notelist',
					'tip' => trans('List of debit notes'),
					'prio' => 80,
				),
				array(
					'name' => trans('New Debit Note'),
					'link' => '?m=noteadd&action=init',
					'tip' => trans('Generate debit note'),
					'prio' => 85,
				), 
				array(
					'name' => trans('Cash Registry'),
					'link' => '?m=cashreglist',
					'tip' => trans('List of cash registries'),
					'prio' => 90,
				),
				array(
					'name' => trans('New Cash Receipt'),
					'link' => '?m=receiptadd&action=init',
					'tip' => trans('Generate cash receipt'),
					'prio' => 100,
				),
				array(
					'name' => trans('Import'),
					'link' => '?m=cashimport',
					'tip' => trans('Import cash operations'),
					'prio' => 110,
				),
				array(
					'name' => trans('Export'),
					'link' => '?m=export',
					'tip' => trans('Financial data export to external systems'),
					'prio' => 120,
				),
				array(
					'name' => trans('Reports'),
					'link' => '?m=print',
					'tip' => trans('Lists and reports printing'),
					'prio' => 130,
				),
			),
		),

		'documents' => array(
			'name' => trans('Documents'),
			'img' =>'docum.gif',
			'link' =>'?m=documentlist',
			'tip' => trans('Documents Management'),
			'accesskey' => '',
			'prio' => 150,
			'index' => MODULES_DOCUMENTS,
			'submenu' => array(
				array(
					'name' => trans('List'),
					'link' => '?m=documentlist&init=1',
					'tip' => trans('List of documents'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Document'),
					'link' => '?m=documentadd',
					'tip' => trans('Allows you to add new document'),
					'prio' => 20,
				),
//				array(
//					'name' => trans('Search'),
//					'link' => '?m=documentsearch',
//					'tip' => trans('Allows you to search documents'),
//					'prio' => 30,
//				),
				array(
					'name' => trans('Generator'),
					'link' =>'?m=documentgen',
					'tip' => trans('Documents mass creation'),
					'prio' => 40,
				),
				array(
					'name' => trans('Access rights'),
					'link' => '?m=documenttypes',
					'tip' => trans('Users access rights to documents by type'),
					'prio' => 50,
				),
			),
		),

		'hosting' => array(
			'name' => trans('Hosting'),
			'img' =>'account.gif',
			'link' =>'?m=accountlist',
			'tip' => trans('Hosting Services Management'),
			'accesskey' =>'a',
			'prio' => 160,
			'index' => MODULES_HOSTING,
			'submenu' => array(
				array(
					'name' => trans('Accounts'),
					'link' => '?m=accountlist',
					'tip' => trans('List of accounts'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Account'),
					'link' => '?m=accountadd',
					'tip' => trans('Add new account'),
					'prio' => 20,
				),
				array(
					'name' => trans('Aliases'),
					'link' => '?m=aliaslist',
					'tip' => trans('List of aliases'),
					'prio' => 30,
				),
				array(
					'name' => trans('New Alias'),
					'link' => '?m=aliasadd',
					'tip' => trans('Add new alias'),
					'prio' => 40,
				),
				array(
					'name' => trans('Domains'),
					'link' => '?m=domainlist',
					'tip' => trans('List of domains'),
					'prio' => 50,
				),
				array(
					'name' => trans('New Domain'),
					'link' => '?m=domainadd',
					'tip' => trans('Add new domain'),
					'prio' => 60,
				),
				array(
					'name' => trans('Search'),
					'link' => '?m=accountsearch',
					'tip' => trans('Allows you to search for account, alias, domain'),
					'prio' => 70,
				),
			),
		),

		'messages' => array(
			'name' => trans('Messages'),
			'img' =>'mailsms.gif',
			'link' =>'?m=messageadd',
			'tip' => trans('Customers Messaging'),
			'accesskey' =>'m',
			'prio' => 180,
			'index' => MODULES_MESSAGES,
			'submenu' => array(
				array(
					'name' => trans('List'),
					'link' => '?m=messagelist',
					'tip' => trans('List of sent messages'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Message'),
					'link' => '?m=messageadd',
					'tip' => trans('Allows you to send messages to customers'),
					'prio' => 20,
				),

				array(
					'name' => trans('Templates'),
					'link' => '?m=messagetemplate',
					'tip' => trans('Messages managing templates'),
					'prio' => 21,
				),

			),
		),

		'reload' => array(
			'name' => trans('Reload'),
			'img' =>'reload.gif',
			'link' =>'?m=reload',
			'tip' => trans(''),
			'accesskey' =>'r',
			'prio' => 190,
			'index' => MODULES_RELOAD,
		),

		'stats' => array(
			'name' => trans('Stats'),
			'img' =>'traffic.gif',
			'link' =>'?m=traffic',
			'tip' => trans('Statistics of Internet Link Usage'),
			'accesskey' =>'x',
			'prio' => 200,
			'index' => MODULES_STATS,
			'submenu' => array(
				array(
					'name' => trans('Filter'),
					'link' => '?m=traffic',
					'tip' => trans('User-defined stats'),
					'prio' => 10,
				),
				array(
					'name' => trans('Last Hour'),
					'link' => '?m=traffic&bar=hour',
					'tip' => trans('Last hour stats for all networks'),
					'prio' => 20,
				),
				array(
					'name' => trans('Last Day'),
					'link' => '?m=traffic&bar=day',
					'tip' => trans('Last day stats for all networks'),
					'prio' => 30,
				),
				array(
					'name' => trans('Last 30 Days'),
					'link' => '?m=traffic&bar=month',
					'tip' => trans('Last month stats for all networks'),
					'prio' => 40,
				),
				array(
					'name' => trans('Last Year'),
					'link' => '?m=traffic&bar=year',
					'tip' => trans('Last year stats for all networks'),
					'prio' => 50,
				),
				array(
					'name' => trans('Compacting'),
					'link' => '?m=trafficdbcompact',
					'tip' => trans('Compacting Database'),
					'prio' => 60,
				),
				array(
					'name' => trans('Reports'),
					'link' => '?m=trafficprint',
					'tip' => trans('Lists and reports printing'),
					'prio' => 70,
				),
			),
		),

		'helpdesk' => array(
			'name' => trans('Helpdesk'),
			'img' =>'ticket.gif',
			'link' =>'?m=rtqueuelist',
			'tip' => trans('Requests Tracking'),
			'accesskey' =>'h',
			'prio' => 210,
			'index' => MODULES_HELPDESK,
			'submenu' => array(
				array(
					'name' => trans('Queues List'),
					'link' => '?m=rtqueuelist',
					'tip' => trans('List of queues'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Queue'),
					'link' => '?m=rtqueueadd',
					'tip' => trans('Add new queue'),
					'prio' => 20,
				),
				array(
					'name' => trans('Categories List'),
					'link' => '?m=rtcategorylist',
					'tip' => trans('List of categories'),
					'prio' => 30,
				),
				array(
					'name' => trans('New Category'),
					'link' => '?m=rtcategoryadd',
					'tip' => trans('Add new category'),
					'prio' => 40,
				),
				array(
					'name' => trans('Search'),
					'link' => '?m=rtsearch',
					'tip' => trans('Tickets searching'),
					'prio' => 50,
				),
				array(
					'name' => trans('New Ticket'),
					'link' => '?m=rtticketadd',
					'tip' => trans('Add new ticket'),
					'prio' => 60,
				),
				array(
					'name' => trans('Reports'),
					'link' => '?m=rtprint',
					'tip' => trans('Lists and reports printing'),
					'prio' => 70,
				),
			),
		),

		'timetable' => array(
			'name' => trans('Timetable'),
			'img' =>'calendar.gif',
			'link' =>'?m=eventlist',
			'tip' => trans('Events Tracking'),
			'accesskey' =>'v',
			'prio' => 220,
			'index' => MODULES_TIMETABLE,
			'submenu' => array(
				array(
					'name' => trans('Timetable'),
					'link' => '?m=eventlist',
					'tip' => trans('Timetable'),
					'prio' => 10,
				),
				array(
					'name' => trans('New Event'),
					'link' => '?m=eventadd',
					'tip' => trans('New Event Addition'),
					'prio' => 20,
				),
				array(
					'name' => trans('Search'),
					'link' => '?m=eventsearch',
					'tip' => trans('Searching of Events in Timetable'),
					'prio' => 30,
				),
			),
		),

		'raporty'	=> array(
			'name' => 'Raporty',
			'img' => 'reports.png',
			'link' => '',
			'tip' => '',
			'prio' => 230,
			'index' => MODULES_RAPORTY,
			'submenu' => array(
				array(
				    'name' => 'Raporty SIIS',
				    'link' => '?m=uke_siis',
				    'tip' => '',
				    'prio' => 10,
				),
				array(
				    'name' => 'Nowy raport SIIS',
				    'link' => '?m=uke_siis_add',
				    'tip' => 'Utwórz nowy raport SIIS',
				    'prio' => 20,
				),
			),
		),
		
		'slownik'	=> array(
			'name'		=> 'Słowniki',
			'img'		=> 'dictionary.png',
			'link'		=> '',
			'prio'		=> 240,
			'index'		=> MODULES_SLOWNIK,
			'submenu'	=> array(
				array(
					'name' => 'Grupy klientów',
					'link' =>'?m=customergrouplist',
					'tip' => trans('List of Customers Groups'),
					'prio' => 10,
				),
				array(
					'name' => 'Grupy kontrahentów',
					'link' =>'?m=contractorgrouplist',
					'tip' => trans('List of Contractors Groups'),
					'prio' => 15,
				),
				array(
					'name' => 'Grupy komputerów',
					'link' =>'?m=nodegrouplist',
					'tip' => trans('List of Nodes Groups'),
					'prio' => 20,
				),
				array(
					'name' => 'Grupy interfejsów',
					'link' =>'?m=netdevgrouplist',
					'tip' => 'Lista grup interfejsów sieciowych',
					'prio' => 21,
				),
				array(
					'name' => 'Grupy węzłów',
					'link' =>'?m=networknodegrouplist',
					'tip' => 'Lista grup węzłów',
					'prio' => 22,
				),
				array(
					'name' => 'Pochodzenie klientów',
					'link' => '?m=customeroriginlist',
					'tip'	=> 'Źródła pochodzenia klientów',
					'prio' => 30,
				),
				
				array(
					'name' => 'Rodzaje urządzeń',
					'link' => '?m=dictionarydevices',
					'tip'	=> 'Rodzaje urządzeń sieciowych oraz instalowanych u klienta',
					'prio' => 40,
				),
				
				array(
					'name' => 'Producenci, modele',
					'link' => '?m=netdevicemodels',
					'tip'	=> 'Producenci i modele urządzeń sieciowych',
					'prio' => 50,
				),
				
				array(
					'name' => 'Powody korekt faktur',
					'link'	=> '?m=dictionarycnote',
					'tip'	=> 'Rodzaje powodów dla których jest dokonan korekta faktury',
					'prio'	=> 60,
				),
				array(
					'name' => trans('Projekty'),
					'link' => '?m=projectlist',
					'tip' => trans('Lista projektów inwestycyjnych'),
					'prio' => 70,
				),
				
			),
			
		),
		
		'config' => array(
			'name' => trans('Configuration'),
			'img' =>'settings.gif',
			'link' =>'?m=configlist',
			'tip' => trans('System Configuration'),
			'accesskey' =>'o',
			'prio' => 250,
			'index' => MODULES_CONFIG,
			'submenu' => array(
				array(
					'name' => trans('User Interface'),
					'link' =>'?m=configlist',
					'tip' => trans('Allows you to configure UI'),
					'prio' => 10,
				),
				array(
					'name' 	=> 'Formularze',
					'link'	=> '?m=configform',
					'tip'	=> 'Konfigurowanie wyświetlanych i wymaganych pól w formularzach',
					'prio'	=> 15,
				),
				array(
					'name' => trans('Tax Rates'),
					'link' => '?m=taxratelist',
					'tip' => trans('Tax Rates Definitions'),
					'prio' => 20,
				),
				array(
					'name' => trans('Numbering Plans'),
					'link' => '?m=numberplanlist',
					'tip' => trans('Numbering Plans Definitions'),
					'prio' => 30,
				),
				array(
					'name' => trans('States'),
					'link' => '?m=statelist',
					'tip' => trans('Country States Definitions'),
					'prio' => 40,
				),
				array(
					'name' => trans('Divisions'),
					'link' => '?m=divisionlist',
					'tip' => trans('Company Divisions Definitions'),
					'prio' => 50,
				),
				array(
					'name' => trans('Daemon'),
					'link' => '?m=daemoninstancelist',
					'tip' => trans('Daemon(s) Configuration'),
					'prio' => 70,
				),
				array(
					'name' => trans('Import Sources'),
					'link' => '?m=cashsourcelist',
					'tip' => trans('List of Cash Import Sources'),
					'prio' => 80,
				),
				array(
					'name'	=> 'Wtyczki',
					'link'	=> '?m=plugin',
					'tip'	=> 'Konfiguracja wtyczek',
					'prio'	=> 90,
				),
				
			),
		),
		
		
		
		'password' => array(
			'name' => trans('Password'),
			'img' => 'pass.gif',
			'link' => '?m=chpasswd',
			'tip' => trans('Allows you to change your password'),
			'accesskey' => 'p',
			'prio' => 260,
			'index' => MODULES_PASSWORD,
		),
/*
		'documentation' => array(
			'name' => trans('Documentation'),
			'img' => 'doc.gif',
			'link' => (is_dir('doc/html/'.$LMS->ui_lang) ? 'doc/html/'.$LMS->ui_lang.'/' : 'doc/html/en/'),
			'tip' => trans('Documentation'),
			'accesskey' => 'h',
			'prio' => 70,
			'index' => 360,
			'windowopen' => TRUE,
		),
*/
	);

if (get_conf('phpui.radius')) {
	
	$menu['radius'] = array(
		'name'		=> 'Radius',
		'img'		=> 'radius.gif',
		'link'		=> '',
		'tip'		=> '',
		'accesskey'	=> '',
		'prio'		=> 90,
		'index'		=> MODULES_RADIUS,
		'submenu'	=> array(
			array(
				'name'		=> 'sesje otwarte',
				'link'		=> '?m=rad_radacct&status=open&page=1',
				'tip'		=> '',
				'prio'		=> 10,
			),
			array(
				'name'		=> 'sesje zakończone',
				'link'		=> '?m=rad_radacct&status=completed&page=1&startdatefrom='.date('Y/m/d',strtotime("-3 day",time())),
				'tip'		=> '',
				'prio'		=> 10,
			),
			array(
				'name'		=> 'konfiguracja',
				'link'		=> '?m=configlist&page=1&s=radius&n=',
				'tip'		=> '',
				'prio'		=> 100,
			),
		),
	);
	
}


if (get_conf('voip.enabled','0')) {
	$menu['telefonia'] = array(
			'name' => 'VoIP Nettelekom',
			'img' =>'voip.gif',
			'link' =>'?m=configlist',
			'tip' => 'Telefonia internetowa',
			'accesskey' =>'v',
			'prio' => 60,
			'index' => MODULES_VOIPNT,
			'submenu' => array(
				array(
					'name' => 'Lista abonamentów',
					'link' =>'?m=v_tarifflist',
					'prio' => '10'),
				array(
					'name' => 'Nowy abonament',
					'link' => '?m=v_tariffadd',
					'tip' => 'Nowy abonament',
					'prio' => '20'),
				array(
					'name' => 'Lista cenników minut',
					'link' =>'?m=v_cennlist',
					'prio' => '30'),
				array(
					'name' => 'Nowy cennik minut',
					'link' => '?m=v_cennadd',
					'tip' => 'Nowy cennik minut',
					'prio' => '40'),
				array(
					'name' => 'Lista grup cennikowych',
					'link' =>'?m=v_trunkgrplist',
					'prio' => '50'),
				array(
					'name' => 'Nowa grupa cennikowa',
					'link' => '?m=v_trunkgrpadd',
					'tip' => 'Nowa grupa cennikowa',
					'prio' => '60'),
				array(
					'name' => 'Stan centrali',
					'link' => '?m=v_state',
					'tip' => 'Stan centrali',
					'prio' => '110'),
				array(
					'name' => 'CDR',
					'link' => '?m=v_cdr',
					'tip' => 'Lista połączeń wychodzących',
					'prio' => '120'),
/*				array(
TEMPORARY DISABLED			'name' => 'Bilans kosztów',
					'link' => '?m=v_balance',
					'tip' => 'Bilans kosztów',
					'prio' => '130'),*/
				array(
					'name' => 'Lista stref numeracyjnych',
					'link' => '?m=v_netlist',
					'tip' => 'Numery',
					'prio' => '140'),
				array(
					'name' => 'Nowa strefa numeracyjna',
					'link' => '?m=v_netadd',
					'tip' => 'Numery',
					'prio' => '150'),
				array(
					'name' => 'Wzorce numerów',
					'link' => '?m=v_numbers',
					'tip' => 'Numery',
					'prio' => '160'),
				array(
					'name' => 'Przelicz salda klientów',
					'link' => '?m=v_checkbalance',
					'tip' => 'Salda',
					'prio' => '190'),
				array(
					'name' => 'Użycie dysku',
					'link' => '?m=v_diskusage',
					'tip' => '',
					'prio' => '200'),
				array(
					'name' => 'Sprawozdanie UKE',
					'link' => '?m=v_uke',
					'tip' => '',
					'prio' => '210')	
				)
		);
}

if (get_conf('registryequipment.enabled')) {
	$menu['registryequipment'] = array(
		'name'		=> 'Ew. Pojazdów',
		'img'		=> 'car.png',
		'link'		=> '',
		'tip'		=> '',
		'prio'		=> 170,
		'index'		=> MODULES_REGISTRYEQUIPMENT,
		'submenu'	=> array(
			array(
				'name'	=> 'Lista pojazdów',
				'link'  => '?m=re_carlist',
				'prio' => 1,
			),
			array(
				'name' => 'Nowy pojazd',
				'link' => '?m=re_caradd',
				'prio' => 2,
			),
//			array(
//				'name' => 'Ubezpieczenia',
//				'link' => '?m=re_insurance',
//				'prio' => 10,
//			),
			array(
				'name' => 'Rodzaje pojazdów',
				'link' => '?m=re_dictionarycartype',
				'prio' => 50,
			),
			array(
				'name' => 'Rodzaje zdarzeń',
				'link' => '?m=re_dictionaryevent',
				'prio' => 60,
			),
		),
	);
}

if (get_conf('jambox.enabled',0)) {
	$menu['TV'] = array( 
			'name' => 'TV Jambox',
			'img' =>'tv_icon.png',
			'tip' => 'TV Management',
			'accesskey' =>'t',
			'prio' => 70,
			'index' => MODULES_JAMBOX,
			'submenu' => array(
				array(
					'name' => trans('Lista klientów'),
					'link' => '?m=tvcustomers',
					'tip' => trans('Lista klientów'),
					'prio' => 10,
				),
				array(
					'name' => trans('Lista pakietów'),
					'link' => '?m=tvpackageslist',
					'tip' => trans('Lista dostępnych pakietów'),
					'prio' => 20,
				),
				array(
					'name' => trans('Lista STB'),
					'link' => '?m=tvstblist',
					'tip' => trans('Lista STB'),
					'prio' => 30,
				),				
				array(
					'name' => trans('Podziel podsieć'),
					'link' => '?m=tvsubnetlist',
					'tip' => trans('Podziel podsieć'),
					'prio' => 40,
				),				
//				array(
//					'name' => trans('Bilingi'),
//					'link' => '?m=tvbillingevents',
//					'tip' => trans('Lista zdarzeń bilingowych'),
//					'prio' => 50,
//				),
				array(
					'name' => trans('Lista wiadomości'),
					'link' => '?m=tvmessages',
					'tip' => trans('Lista wiadomości'),
					'prio' => 60,
				),
				array(
					'name' => trans('Nowa wiadomość'),
					'link' => '?m=tvmessagessend',
					'tip' => trans('Nowa wiadomość'),
					'prio' => 61,
				),			
				array(
					'name' => trans('Odśwież dane'),
					'link' => '?m=tvcleancache',
					'tip' => trans('Odśwież dane'),
					'prio' => 71,
				),												
			),
		);								
		
}


// menu item for EtherWerX STM channels management
if (chkconfig($CONFIG['phpui']['ewx_support'])) {
	$menu['netdevices']['submenu'][] = array(
		'name' => trans('Channels List'),
		'link' => '?m=ewxchlist',
		'tip' => trans('List of STM channels'),
		'prio' => 50,
	);
	$menu['netdevices']['submenu'][] = array(
		'name' => trans('New Channel'),
		'link' => '?m=ewxchadd',
		'tip' => trans('Add new STM channel'),
		'prio' => 51,
	);
}

// Adding Userpanel menu items
if(!empty($CONFIG['directories']['userpanel_dir']))
        // be sure that Userpanel exists
	if(file_exists($CONFIG['directories']['userpanel_dir'].'/lib/LMS.menu.php'))
	        require_once($CONFIG['directories']['userpanel_dir'].'/lib/LMS.menu.php');

// Adding user-defined menu items
if(!empty($CONFIG['phpui']['custom_menu']))
        // be sure that file exists
	if(file_exists($CONFIG['phpui']['custom_menu']))
	        require_once($CONFIG['phpui']['custom_menu']);

/* Example for custom_menu file
<?php
	$menu['config']['submenu'][] = array(
		'name' => 'My config',
		'link' => '?m=myfile',
		'tip' => 'My Configuration',
		'prio' => 35,
	)
?>
*/

if(!function_exists('menu_cmp'))
{
    function menu_cmp($a, $b)
	{
		if(!isset($a['prio'])) $a['prio'] = 0;
		if(!isset($b['prio'])) $b['prio'] = 9999;

	    if($a['prio'] == $b['prio'])
	        return 0;
	    return ($a['prio'] < $b['prio']) ? -1 : 1;
	}
}
/*
foreach($menu as $idx => $item)
	if(isset($item['submenu']))
		uasort($menu[$idx]['submenu'],'menu_cmp');

uasort($menu,'menu_cmp');
*/
?>
