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
	    'data'	=> '2014/05/14',
	    'status'	=> '',
	    'opis'	=> 'Dodano rejestrację kopii iNET LMS',
	),
	array(
	    'data'	=> '2014/05/05',
	    'status'	=> '',
	    'opis'	=> 'Dodana obsługa VoIP Nettelekomu. Włączenie modułu w konfiguracji UI -> Nettelekom zmienna enabled.',
	),
	array(
	    'data'	=> '2014/05/03',
	    'status'	=> '',
	    'opis'	=> 'poprawka błędu przy dodawaniu nowego komputera',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Dodano box załączniki w karcie klienta',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Zmiana wartości w .htaccess dla php_value post_max_size i php_value upload_max_filesize na 256M. Pozwoli to nam pobrać załączniki o max. wielkości 256MB.',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Doszedł box Załączniki w karcie interfejsu sieciowego.',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Dodano nowe pola w karcie interfejsu sieciowego',
	),
	array(
	    'data'	=> '2014/04/29',
	    'status'	=> '',
	    'opis'	=> 'Kilka drobnych zmian kosmetycznych',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano nową zmienną dla radius.auth_login -> <b>passwd</b> identyfikującą urządzenie klienckie na podstawie hasła jako loginu dla sesji PPPoE',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Dodano box reklamowy na stronie logowania. Reklamy są pobierane dynamicznie z adv.inetlms.pl. Informacje będą zawierać różne treści nie łamiąc prawa ani etyki :D',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Małe poprawki odnośnie modułu radius',
	),
	array(
	    'data'	=> '2014/04/23',
	    'status'	=> '',
	    'opis'	=> 'przebudowa obsługi wyboru adresu IP dla urządzeń klienckich i interfejsów sieciowych',
	),
	array(
	    'data'	=> '2014/04/22',
	    'status'	=> '',
	    'opis'	=> 'Dodano box z syslog w karcie klienta',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Aktualizacja Smarty do 3.1.18',
	),
	array(
	    'data'	=> '2014/04/14',
	    'status'	=> '',
	    'opis'	=> 'syslog - dodano filtr na podstawie klienta',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Dodano moduł Radius, który pozwala nam na przeglądanie tabeli radacct, wyzerowanie błędnych sesji oraz zerwanie bieżącej sesji.<br>Dokumentacja konfiguracji modułu znajduje się w DOC/README-Radius.txt',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dołączono tabele Radiusa do bazy LMS\'a',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano nowe pole w interfejsach sieciowych CoA Port, sekcja radius',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Przebudowa listy klientów',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'wprowadzono konfigurację technologii połączeń między interfejsami oraz urządzeniami klientów zgodnie z wytycznymi SIIS',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'doszła zmienna monit.autocreate_chart która odpowiada za automatyczne tworzenie plików png z wykresami podczas testu, 1-Tak, 0-Nie, wartość 0 powoduje tworzenie obrazka dopiero na żądanie. DEFAULT: 0',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'rozbudowa taryf o dodatkowe pola dla BURST',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano zmienną w UI -> osprzęt sieciowy -> force_connection, wymuszającą podłączenie urządzenia klienta do interfejsu sieciowego, podczas edycji / dodawania urz. klienta ',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano zmienną w UI -> osprzęt sieciowy -> force_network_dns, wymuszającą podanie adresu serwera DNS w konfiguracji sieci',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano zmienną w UI -> osprzęt sieciowy -> force_network_gateway, wymuszającą podanie bramki sieciowej w konfiguracji klasy adresowej IP (sieci)',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'doszła możliwość powiązania klasy adresowej IP z konkretnym hostem, dodano również zmienną w UI -> osprzęt sieciowy -> force_network_to_host która wymusza powiązanie sieci IP z hostem.',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano tablicę informacyjnę na stronie startowej, za pomocą tablicy możemy zostawiać krótkie informacje dla wybranych użytkowników systemu.',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> "przebudowano stronę startową, zmiana dotyczy dla welcome_new, w UI sekcja strona startowa możemy zdefiniować które box'y mają być wyświetlane, zachowano uprawnienia dla poszczególnych użytkowników.",
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Dodano Historię importów płatności masowych, Finanse -> Historia importów. Zastosowane zakładki i filtry pozwalają nam wyłapać nieścisłości jakie powstały podczas importu płatności.',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przybył nam osobisty notatnik, ikona na pasku narzędzi',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'rozszerzono filtr raportu sprzedaży, obecnie możliwy jest wydruk rejestru z podziałem na firmy, osoby fizyczne lub wszyscy. Podesłane przez MS-NET Miłosz Szewczak',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano dodatkowe pola w konfiguracji firmy / oddziału.',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przebudowano sposób przechowywania informacji o dokumentach, od teraz w tabeli documents zapisana jest informacja io firmie która wystawiła dany dok. Zmiana danych firmy nie będzie wpływać np. na wystawce faktur które zostały wcześniej wystawione',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Zmiana walidacji dla węzłów sieciowych',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Przeniesienie konfiguracji hostów z konfiguracja do osprzęt sieciowy',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'aktualizacja Smarty',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'doszła możliwość dodania źródła pochodzenia klientów, np. ulotka, konkurencja, łapanka :D',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'mała zmiana css ;)',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka wysyłki faktur dla postivo',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Generowanie raportu SIISv4',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Dodana obsługa węzłów sieciowych',
	),
	array(
	    'data'	=> '2013/02/12',
	    'status'	=> '',
	    'opis'	=> '<b><font color="blue">******************** iNET LMS 1.0.2 ********************</font></b>',
	),
	array(
	    'data'	=> '2013/02/12',
	    'status'	=> 'fix',
	    'opis'	=> 'mała poprawka w messageadd',
	),
	array(
	    'data'	=> '2013/02/12',
	    'status'	=> 'fix',
	    'opis'	=> 'poprawka monitoringu',
	),
	array(
	    'data'	=> '2013/02/12',
	    'status'	=> 'add',
	    'opis'	=> 'dodano nowy moduł do UP Wiadomości oparty o skrypt udostępniony przez CZEMPIN.NET<br>
			    Wiadomości "do" UP wysłamy poprzez Wiadomości -> nowa wiadomość, Klient ma swobodny dostęp do poprzednich wiadomości wystałnych w ten sposób,<br>
			    my natomiast w karcie klienta (box wiadomości) mamy informację kiedy klient przeczytał infrmację pierwszy i ostatni raz oraz czy potwierdził przeczytanie wiadomości.',
	),
	array(
	    'data'	=> '2013/02/12',
	    'status'	=> 'add',
	    'opis'	=> 'doszła możliwość wysłania do klienta wiadomości na Gadu-Gadu, dostępne w Wiadomości -> Nowa wiadomość. Konfiguracja klienta GG dostępna w UI sekcja Gadu-Gadu',
	),
	array(
	    'data'	=> '2013/02/12',
	    'status'	=> 'up',
	    'opis'	=> 'Zmieniono nazwę modułu dla userpanel z <b>Wiadomości</b> na <b>Powiadomienia</b>.<br>Moduł ten wyświetlał nam ostatnie powiadomienie wysłane poprzez Klienci / Komputery powiadomienia a nie rzeczywiste wiadomości wysłane za pomocą Wiadomości -> Nowa wiadomość.',
	),
	array(
	    'data'	=> '2013/02/12',
	    'status'	=> 'add',
	    'opis'	=> 'dodano dodatkowe zmienne <b>%address , %postaddress i %last_3_in_a_table</b> dla wysyłanych wiadomości. Obecne zmienne które zwracają nam dane:<br>
			    %customer -> nazwisko i imię klienta<br>
			    %balance -> saldo<br>
			    %cid -> id klienta<br>
			    %pin -> numer PIN klienta<br>
			    %address -> adres zameldowania / siedziby klienta<br>
			    %postaddress -> adres do korespondencji<br>
			    %bankaccount -> numer konta bankowego właściwego dla klienta<br>
			    %last_3_in_a_table -> ostatnie 3 operacje finansowe klienta<br>
			    %last_10_in_a_table -> ostatnich 10 operacji finansowych klienta',
	),
	array(
	    'data'	=> '2013/02/12',
	    'status'	=> 'up',
	    'opis'	=> '<font color="red"><b>UWAGA !!!</b></font><br>aktualizacja lms-sendinvoices i lms-sendinvocies-proforma, dla prawidłowego działania skryptu wymagany jest moduł perl <b>Socket::SSL</b>',
	),
	array(
	    'data'	=> '2013/01/11',
	    'status'	=> 'add',
	    'opis'	=> 'dodano listę firm które wsparły projekt iNET LMS, lista dostępna w Administracja -> sponsorzy<br>Dane są automatycznie aktualizowane co 7 dni.',
	),
	array(
	    'data'	=> '2013/01/11',
	    'status'	=> 'up',
	    'opis'	=> 'poprawka invoice_tcpdf.inc.php, jeżeli klient nie ma podanego numeru NIP wtedy na fakturze umieszczany jest numer PESEL',
	),
	array(
	    'data'	=> '2013/01/10',
	    'status'	=> 'add',
	    'opis'	=> 'wprowadzono możliwość tworzenia szablonów wiadomości, które możemy potem wykorzystać przy tworzeniu nowej wiadomości do klienta(ów) dostępne w Wiadomości -> Szablony.',
	),
	array(
	    'data'	=> '2013/02/10',
	    'status'	=> 'up',
	    'opis'	=> 'Aktualizacja tcpdf i html2pdf',
	),
	array(
	    'data'	=> '2013/02/10',
	    'status'	=> 'up',
	    'opis'	=> 'Poprawiono błąd w skrypcie lms-monitoring.php',
	),
	array(
	    'data'	=> '2013/02/08',
	    'status'	=> '',
	    'opis'	=> '<b><font color="blue">******************** iNET LMS 1.0.1 ********************</font></b>',
	),
	array(
	    'data'	=> '2013/02/06',
	    'status'	=> 'add',
	    'opis'	=> '
	    Dodano nowy Userpanel w oparciu o rozwiązanie firmy ALFA-SYSTEM M.Piwowarski, A. Widera spółka jawna z siedzibą w Knurowie,<br>
	    który będzie rozwijany wraz z iNET LMS, w UP znalazły się między innymi takie zmiany:<br>
	    1 - poprawiona grafika w niektórych modułach,<br>
	    2 - utworzono możliwość edytowania treści 3 box\'ów widocznych z prawej strony na stronie logowania,<br>
	    3 - doszedł nowy edytowalny box z wiadomością widoczną pod menu w module informacje,<br>
	    4 - doszła możliwość pingowania komputerów przez klienta w zależności od uprawnień,<br>
	    5 - doszła możliwość wydrukowania statystyk obciążenia łącza przez klienta w zależności od nadanych uprwnień,<br>
	    6 - dopisano skrypt wysyłający przypomnienie o ID i PIN, wcześniej był tylko formularz. Klient musi podać adres e-mail i PESEL lub NIP<br>
	    7 - doszły nowe uprawnienia dla klientów w konfiguracji Userpanlu,<br>
	    &nbsp;&nbsp;&nbsp;a) PING - klient może przeprowadzić test ping swoich komputerów<br>
	    &nbsp;&nbsp;&nbsp;b) Wydruk statystyk obciążenia łącza',
	),
	array(
	    'data'	=> '2013/01/28',
	    'status'	=> 'add',
	    'opis'	=> 'modyfikacja funkcji odpowiedzialnej za tworzenie kopii bazy danych, tworzony jest dodatkowy plik z zapytaniami naprawiającymi index\'y po ręcznym przywróceniu bazy danych z shell. Dodatkowy plik ma zastosowanie tylko dla bazy Postgres i ma za zadanie ułatwić życie. Listę tabel które nie posiadają auto_increment / sequence przeniesiono do tablicy $TABLENAME_NOINDEX w lib/definitions.php',
	),
	array(
	    'data'	=> '2013/01/28',
	    'status'	=> 'fix',
	    'opis'	=> 'poprawiono bład przy tworzeniu kopii bazy danych, błąd dotyczył nieuwzględnienia kluczy obcych dla tabel związanych z terytem, co powodowało błedy przy imporcie danych do LMS, jeżeli mieliśmy zaimportowaną bazę lokalizacji TERYT. ',
	),
	array(
	    'data'	=> '2013/01/28',
	    'status'	=> 'add',
	    'opis'	=> '
	    Dołączono plugin lms-sendinvoiceswithpostivo firmy POSTIVO.PL który umożliwia automatyczną wysyłkę wystawionych w systemie LMS faktur VAT w formie papierowej do klientów.<br>
	    Zanika potrzeba samodzielnego drukowania dokumentów, które maja zostać wysłane, adresowania kopert, a także tracenia czasu podczas wizyt w urzędach pocztowych.<br>
	    Opis konfiguracji i instalacji znajduje sie w doc/README-postivo.txt, więcej informacji można znaleźć na <a href="http://postivo.pl/ceny-wysylania-dokumentow-listem" target="_blank">http://postivo.pl/ceny-wysylania-dokumentow-listem</a>',
	),
	array(
	    'data'	=> '2013/01/28',
	    'status'	=> 'up',
	    'opis'	=> 'Przebudowa modułu monitoringu. Obecna wersja pozwala nam na zbieranie informacji o połączeniach radiowych z urządzeń Ubiquiti po SNMP i MikroTik po SNMP i API.<br>
			    Informacje o połączeniu radiowym zbierane z MT po API są rozszerzone o TX/RX CCQ, ACK i Signal Noise. Skrypt łączy się tylko z urządzeniami sieciowymi z których pobiera info, nie łączy się bezpośrednio z żadnym urządzeniem klienta.<br>
			    Włączenie monitorowania sygnałów Wi-Fi wymaga jedynie odpowiedniej konfiguracji w urządzeniach sieciowych, nie ma potrzeby ręcznego dodawania komputerów do listy monitorowanych urządzeń,
			    jeżeli będziemy chcieli również pingować urz. klienta to musimy już to ręcznie włączyć w Monitoringu -> urządzenia klientów.',
	),
	array(
	    'data'	=> '2013/01/27',
	    'status'	=> 'add',
	    'opis'	=> 'Rozpoczęto wprowadzanie indywidualnych ustawień "widoków" w LMS, ustawienia są zapamiętywane automatycznie i przywracane po ponownym zalogowaniu się do systemu.',
	),
	array(
	    'data'	=> '2013/01/14',
	    'status'	=> 'up',
	    'opis'	=> 'Poprawka systemu wykrywania wersji językowej',
	),
	array(
	    'data'	=> '2013/01/11',
	    'status'	=> 'add',
	    'opis'	=> 'API - dodano API dla LMS z klikoma przykładowymi requestami. Requesty w bardzo prosty sposób można samemu stworzyć zgodnie z własnymi potrzebami. Więcej info w contrib/API_Client',
	),
	array(
	    'data'	=> '2012/01/11',
	    'status'	=> 'fix',
	    'opis'	=> 'fix - poprawiono iphistory - nie aktualizował danych przy kasowaniu całego konta klienta',
	),
	array(
	    'data'	=> '2013/01/10',
	    'status'	=> 'add',
	    'opis'	=> 'dodano nowy rodzaj LIVE PING, dostępny na liście komputerów , w karcie klienta: box komputery i w karcie urządzenia sieciowego.<br>Należy do /etc/sudoers dodać wpis : www-data ALL = NOPASSWD: /var/www/lms/bin/lms-monitoring.pl (wpis przykładowy dla debian/ubuntu) podając prawidłową ścieżkę dla tego pliku, należy również sprawdzić i ew. poprawić ścieżkę w konfiguracji monitoringu opcja <b>test_script_dir</b><br>LIVE PING można wyłączyć zmieniając wartość z 1 na 0 w konfiguracji monitoringu opcja live_ping.',
	),
	array(
	    'data'	=> '2013/01/10',
	    'status'	=> 'add',
	    'opis'	=> 'dodano zmienną <b>default_type_of_documents</b> w sekcji <b>invoices</b><br>Zmienna określa nam domyślny typ dokumentu przy dodawaniu nowego zobowiązania/taryfy dla klienta, DEFAULT: <br>dozwolone wartości: <Br>invoice - faktura<br>proforma - faktura proforma<br>pusta wartość - tylko naliczenie opłat',
	),
	array(
	    'data'	=> '2013/01/10',
	    'status'	=> 'fix',
	    'opis'	=> 'poprawka linków na głównej stronie, box klienci<br>poprawka uprawnień do syslog',
	),
	array(
	    'data'	=> '2013/01/08',
	    'status'	=> '',
	    'opis'	=> '<b><font color="blue">******************** iNET LMS 1.0.0 ********************</font></b>',
	),
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
	    'opis'	=> 'doszła możliwość prowadzenia historii przyznawanych adresów IP lokalnych i publicznych, włączenie / wyłączenie logowania konfigurujemy w UI sekcja phpui , opcja iphistory , domyślne ustawienie: 1',
	),
	array(
	    'data'	=> '2013/01/02',
	    'status'	=> 'up',
	    'opis'	=> 'update tcpdf to version 5.9.202',
	),
	array(
	    'data'	=> '2013/01/01',
	    'status'	=> 'add',
	    'opis'	=> 'dodano nową zmienną konfiguracyjną installation_name w sekcji phpui. Nazwa instalacji wyświetlana będzie w oknie logowania, w stopce i w prawym górnym rogu',
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
	    'opis'	=> 'doszła możliwość wyłączenia starych taryf',
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