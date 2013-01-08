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
 *  $Id Sylwester Kondracki Exp $
 */
$changelog = array(
/*
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> '',
	),
*/
	array(
	    'data'	=> '2013/01/07',
	    'status'	=> 'add',
	    'opis'	=> 'dodano moduł: autobackup, moduł tworzy kopię bazy danych na lokalnym systemie wraz z możliwością wysyłki archiwum na zdalny serwer FTP. Możliwe jest również tworzenie kopii bezpieczeństwa wybranych katalkogów na FTP. Konfiguracja w UI sekcja autobackup. Plik lms-autobackup należy odpalać przez cron np. raz na 3 dni',
	),
	array(
	    'data'	=> '2013/01/05',
	    'status'	=> 'up',
	    'opis'	=> 'poprawka kliku drobych błędów nie wpływających bezspośrednio na stabilność LMS\'a',
	),
	array(
	    'data'	=> '2013/01/03',
	    'status'	=> 'add',
	    'opis'	=> 'dodano możliwość prowadzenia historii przyznawanych adresów IP lokalnych i publicznych, włączenie / wyłączenie logowania konfigurujemy w UI sekcja phpui , opcja iphistory , domyślne ustawienie: 1',
	),
	array(
	    'data'	=> '2013/01/02',
	    'status'	=> 'up',
	    'opis'	=> 'update tcpdf to version 5.9.202',
	),
	array(
	    'data'	=> '2013/01/01',
	    'status'	=> 'add',
	    'opis'	=> 'dodano zmienną konfiguracyjną installation_name w sekcji phpui. Nazwa instalacji wyświetlana będzie w oknie logowania, w stopce i w prawym górnym rogu',
	),
	array(
	    'data'	=> '2013/01/01',
	    'status'	=> 'up',
	    'opis'	=> 'aktualizacja syslog, zwiększono zakres logowanych zdarzeń',
	),
	array(
	    'data'	=> '2012/12/31',
	    'status'	=> 'add',
	    'opis'	=> 'Lista Klientów: optymalizacja zapytania SQL dla bazy MySQL.',
	),
	array(
	    'data'	=> '2012/12/31',
	    'status'	=> 'add',
	    'opis'	=> 'dodano możliwość wyłączenia starych taryf',
	),
	array(
	    'data'	=> '2012/12/30',
	    'status'	=> 'add',
	    'opis'	=> 'utworzenie nowej pozycji w menu Taryfy, przeniesienie taryf z finansów i promocji z konfiguracji',
	),
	array(
	    'data'	=> '2012/12/30',
	    'status'	=> 'add',
	    'opis'	=> 'Dodano możliwość włączenia / wyłączenia poszczególnych modułów/pozycji w menu. Konfiguracja indywidualna dla danego użytkownika dostępna w karcie użytkownika.',
	),
	array(
	    'data'	=> '2012/12/30',
	    'status'	=> 'add',
	    'opis'	=> 'dodano templatkę druczków opłat abonamentowych do generatora dokumentów, opartą na drukach FT-0100',
	),
	array(
	    'data'	=> '2012/12/30',
	    'status'	=> 'up',
	    'opis'	=> 'optymalizacja ustawień dla kompilatora Smarty',
	),
	array(
	    'data'	=> '2012/12/30',
	    'status'	=> 'add',
	    'opis'	=> 'dodano nowe typy taryf: dierżawa, serwis it, VIP, multi room, zawieszenie',
	),
	array(
	    'data'	=> '2012/12/30',
	    'status'	=> 'add',
	    'opis'	=> 'dodano moduł obsługujący telefonię Internetową ( VoIP ) dla serwisów Telekomunikacja Bliżej i TK24. Dokumentacja modułu jest w lms/doc/README-VoIP-Hiperus-C5.txt (chwilowo)',
	),
	array(
	    'data'	=> '2012/12/30',
	    'status'	=> 'add',
	    'opis'	=> 'Dodanie zmiennej konfiguracyjnej config_empty_value w sekcji phpui',
	),
	array(
	    'data'	=> '2012/12/29',
	    'status'	=> 'add',
	    'opis'	=> 'Przebudowa niektórych filtrów w listach, dodanie ikony <img src="img/cancel.gif"> która pozwala nam na szybki reset filtra, wyróżnienie kolorem aktywnych filtrów.',
	),
	array(
	    'data'	=> '2012/12/27',
	    'status'	=> 'add',
	    'opis'	=> 'Wyróżnienie kolorami obciążenia i wpływy klienta. Zielony - wpływ środków, Czerwony - obciążenie konta',
	),
	array(
	    'data'	=> '2012/12/27',
	    'status'	=> 'add',
	    'opis'	=> 'Call Center - moduł umożliwiający prowadzenie historii kontaktów z klientami',
	),
	array(
	    'data'	=> '2012/12/23',
	    'status'	=> 'add',
	    'opis'	=> 'Lista klientów - dodakowy filtr pozwalający nam na wyłapanie klientów u których wygasają zobowiązania lub mają zobowiązania bezterminowe.<br>
			    Informacja również jest widoczna na stronie startowej, o ile użytkownik nie ma włączonych ukrywanie zestawień.',
	),
	array(
	    'data'	=> '2012/12/23',
	    'status'	=> 'add',
	    'opis'	=> 'Lista klientów - lekka przebudowa filtrów',
	),
	array(
	    'data'	=> '2012/12/22',
	    'status'	=> 'add',
	    'opis'	=> 'widoczna na liście komputerów i w karcie klienta informacja do jakiego urządzenia jest podłączony dany komputer',
	),
	array(
	    'data'	=> '2012/12/21',
	    'status'	=> 'add',
	    'opis'	=> 'szybki podgląd danych firmy w karcie klienta, do której jest on przypisany',
	),
	array(
	    'data'	=> '2012/12/21',
	    'status'	=> 'add',
	    'opis'	=> 'szybki podgląd danych firmy w Konfiguracja -> Firmy (Oddziały), lekka przebudowa listy',
	),
	array(
	    'data'	=> '2012/12/20',
	    'status'	=> 'add',
	    'opis'	=> 'Monitoring - podstawowe monitorowanie aktywności urządzeń sieciowych, klientów i własnych. Dodane wykresy do kart komputerów i kart urządzeń sieciowych.<br>
			    Ikona&nbsp;&nbsp;<img src="img/radar.png">&nbsp;&nbsp;na liście urządzeń pozwala na szybkie wyświetlenie wykresu z monitoringu.<br>
			    Monitoring narazie bez systemu powiadomień o usterkach, będzie to potem rozbudowane. Dokumentcaja jest w lms/doc/README-monitoring.txt (chwilowo)',
	),
	array(
	    'data'	=> '2012/12/09',
	    'status'	=> 'add',
	    'opis'	=> 'syslog - logowanie zdarzeń systemowych. Konfiguracja w UI, sekcja phpui<br><b>przełączniki:</b><br>
			    <b>syslog_level</b> 0 - Wyłączone logowanie, 1 - Włączone Logowanie. Domyślnie: 1<br>
			    <b>syslog_pagelimit</b> Limit wyświetlanych zdarzeń na jednej stronie w logach systemowych. Domyślnie: 100',
	),
	array(
	    'data'	=> '2012/12/09',
	    'status'	=> 'add',
	    'opis'	=> 'Kontrahenci - firmy,klienci z którymi współpracujemy a nie są klientami maszej sieci.',
	),
	array(
	    'data'	=> '2012/12/07',
	    'status'	=> 'add',
	    'opis'	=> 'Lista klientów - dodakowy filtr na podstawie pierwszej litery nazwiska',
	),
	array(
	    'data'	=> '2012/12/06',
	    'status'	=> 'add',
	    'opis'	=> 'Dodatkowe pola odbiorcy faktury VAT lub proforma w karcie klienta, jeżeli odbiorcą ma być inny podmiot niż jest podane w podstawowych danych klienta',
	),
	array(
	    'data'	=> '2012/12/03',
	    'status'	=> 'add',
	    'opis'	=> 'Dodano obsługę faktur proforma wraz ze skryptami shell (perl) do ich wystawiania i  wysyłki na email. Warunkiem automatycznego wystawienia faktury pro jest zaznaczenie w zobowiązaniu klienta że ma być wystawiona tego typu faktura',
	),
);
//array_multisort($changelog, SORT_ASC, SORT_STRING);
$layout['pagetitle'] = 'Changelog';
$SMARTY->assign('changelog',$changelog);
$SMARTY->display('changelog.html');
?>