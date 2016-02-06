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
 *  Sylwester Kondracki
 *  sylwester.kondracki@gmail.com
 *  gadu-gadu : 6164816
 *
*/

$changelog = array(
/*
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> '',
	),
	
*/	
	
//	array(
//	    'data'	=> '',
//	    'status'	=> '',
//	    'opis'	=> 'dodano w słownikach podmioty obce korzystające lub współdzielące infrastrukturę telekomunikacyjną',
//	),
	array(
	    'data'	=> '2016-02-06',
	    'status'	=> '',
	    'opis'	=> 'dodano pola iBGP oraz eBGP w urządzeniach sieciowych, konfig w : Konfiguracja->formularze',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano edytory wizualne w karcie klienta i komputera, konfig w : Konfiguracja->formularze',
	),
	array(
	    'data'	=> '2015-12-01',
	    'status'	=> '',
	    'opis'	=> 'zmiana klasy do wysyłki sms przez serwis SerwerSMS',
	),
	array(
	    'data'	=> '2015-10-26',
	    'status'	=> '',
	    'opis'	=> 'aktualizacja obsługi wtyczek',
	),
	array(
	    'data'	=> '2015-10-24',
	    'status'	=> '',
	    'opis'	=> 'poprawka dla Hiperus C5',
	),
	array(
	    'data'	=> '2015-10-23',
	    'status'	=> '',
	    'opis'	=> 'poprawka dla importu bilingów dla Hiperus C5',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka faktury dla pola "Odbiorca faktury"',
	),
	array(
	    'data'	=> '2015-10-01',
	    'status'	=> '',
	    'opis'	=> 'update obsługi wtyczek',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka generowania faktur pdf w wersji 2',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka ścieżek w ZEND',
	),
    	array(
	    'data'	=> '2015-09-14',
	    'status'	=> '',
	    'opis'	=> 'Zmieniono zasadę działania promocji.',
	),
    
	array(
	    'data'	=> '2015-07-09',
	    'status'	=> '',
	    'opis'	=> 'kilka drobnych poprawek, rozbudowa systemu do obsługi wtyczek',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka DB dla Jambox\'a',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'rozbudowa systemu obsługi wtyczek',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka dla preg_replace ( PHP 5.5 )',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'w karcie komputera doszło pole NAS, można powiązać komputer z rzeczywistym NAS który autoryzuje urządzenie klienta',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'kolejnych kilka drobnych poprawek',
	),
	array(
	    'data'	=> '2015-06-22',
	    'status'	=> '',
	    'opis'	=> 'kilka drobnych zmian i poprawek :-)',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka błędu przy nadawaniu publicznych adresów IP',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano skróty klawiszowe przy edycji / dodawaniu, F2 -> zapisz , ESC -> anuluj',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Dodano skrót klawiszowy : F9 -> ukrycie / pokazanie menu ',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano możliwość wyłączenia informacji o stanie bilansu klienta na fakturze przed wystawieniem faktury, domyślnie włączone, konf: UI->Invoices->print_balance_info<br>Zmienna dla faktur w formacie pdf (tcpdf) wersji 1 i 2, informacja o wyświetlaniu bilansu jest zapamiętana w dokumencie',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'kilka drobnych poprawek',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano sortowanie w liście urządzeń sieciowych',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Poprawka przeładowania głównej strony po dodaniu nowej wiadomości w tablicy informacyjnej',
	),
	array(
	    'data'	=> '2015-03-17',
	    'status'	=> '',
	    'opis'	=> 'Generator SIIS v5',
	),
	array(
	    'data'	=> '2015-03-12',
	    'status'	=> '',
	    'opis'	=> 'dodano możliwość które pola szybkiego wyszukiwania mają być widoczne : Konfigracja -> Formularze -> Szybkie wyszukiwanie',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano szybkie wyszukiwanie interfejsów sieciowych',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przebudowa mechanizmu wyszukiwania klientów w "szybkim wyszukiwaniu"',
	),
	
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano grupy dla interfejsów sieciowych, konfiguracja w słownikach',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano grupy dla węzłów, konfiguracja w słownikach',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'korekta prędkości w połączeniach interfejs <-> komputer',
	),
	array(
	    'data'	=> '2015-03-09',
	    'status'	=> '',
	    'opis'	=> 'dodano możliwość wystawienia korekt w karcie klienta',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano kody ean w modelach urządzeń',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'rozbudowano filtry w Historii importów',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano uprawnienia dla załączników dla klientów i kontrahentów',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano możliwość zdefiniowania które pola mają być widoczne w formularzu edycja/nowy interfejs sieciowy',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano pola login i hasło w karcie interfejsu sieciowego + uprawnienia w karcie użytkownika iNET LMS',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'rozbudowa filtru w interfejsach sieciowych',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano pokazywanie położenia klienta na mapie google na podstawie jego adresu, w karcie klienta trzeba kliknąć na adres siedziby / zameldowania.',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano możliwość zdefiniowania które pola mają być widoczne w formularzu edycja/nowy klient',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przeniesiono zmienną konfiguracyjna node_autoname z sekcji interfejsy sieciowe do konfiguracji formularze -> komputery',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przeniesiono zmienną konfiguracyjną pppoe_login z sekcji interfejsy sieciowe do konfiguracji formularze -> komputery',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przeniesiono zmienną phpui->public_ip do konfiguracji formularza "komputery"',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano możliwość zdefiniowania które pola mają być widoczne w formularzu edycja/nowy komputer',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano nową strefę konfiguracyjną : Konfiguracja -> formularze',
	),
	array(
	    'data'	=> '2015-03-04',
	    'status'	=> '',
	    'opis'	=> 'wprowadzono informację o liniach telekomunikacyjncyh',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'modyfikacja słownika rodzaje budynków do wymogów SIIS v5',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'modyfikacja zestawiania połączenia sieciowego : interfejs <-> interfejs, dodano dodatkowe pola : Warstwa sieci, Trakt, Linia telekomunikacyjna',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'modyfikacja zestawiania połączenia sieciowego : interfejs <-> komputer, dodano dodatkowe pola : Warstwa sieci, Rodzaj przyłącza',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przebudowa listy interfejsów sieciowych, dodano filtry',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przebudowa listy węzłów, dodano filtry',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'wprowadzono informację o projektach dla węzłów, interfesjów sieciowych oraz w karcie komputera',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano projekty inwestycyjne, konfiguracja : Słownik -> projekty',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano możliwość załączenia dokumentów / plikow w karcie kontrahenta',
	),
	array(
	    'data'	=> '2015-02-21',
	    'status'	=> '',
	    'opis'	=> 'poprawa czytelności niektórych tabel / list',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'UI -> dodano zmienną node_autoname w sekcji osprzęt sieciowy, włączenie tej zmienne spowoduje automatyczne nadawanie nazwy komputera w formacie C_{id klienta}_N_{id komputera} w przypadku gdy pole nazwa w formularzu pozostawimy puste.<br><b>Domyślnie wyłączone</b>',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Dodano słownik producentów i modeli sprzętu, do wykorzystania w interfejsach sieciowych oraz w karcie urządzenia klienta (komputer)',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przebudowa listy sieci IP, dodano zmienną w netlist_pagelimit w sekcji phpui',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano zmienną gethostbyaddr w UI, sekcja phpui, wartość 0 wyłącza rozwiązywanie adresów IP na nazwę hosta w liście użytkowników. <br><b>DEF.: 1</b>',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'optymalizacja wydajności przy przeglądaniu listy klientów',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'optymalizacja wydajności przy przeglądaniu listy komputerów',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'modyfikacja paska do przewijania stron',
	),

	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano zmienną konfiguracyjną (UI) w osprzęcie sieciowym <b>pppoe_login</b>, która włącza nam dodtakowe pole na login dla sesji PPPoE, do wykorzystania jeżeli obecne pola nie zdają nam egzaminu',
	),
	array(
	    'data'	=> '2015-02-05',
	    'status'	=> '',
	    'opis'	=> 'aktualizacja userpanel',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'lekka modyfikacja książeczki opłat v2',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka konwersji faktury proforma na fakturę VAT',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodanie lms-payments-proforma, skrypt wystawia tylko faktury proforma',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka skryptu lms-payments, skrypt wystawia faktury VAT i tylko naliczanie',
	),
	array(
	    'data'	=> '2015-02-04',
	    'status'	=> '',
	    'opis'	=> 'poprawka książeczki opłat w wersji 2',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka lms-payments dot. barku zmienych w UI. sekcja invoices, zmienne : template_file, template_version, type, sdateview',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka generatora dokumentów',
	),
	array(
	    'data'	=> '2015-02-01',
	    'status'	=> '',
	    'opis'	=> 'dodano nową książeczkę płat',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> '<b>!!!</b> - poprawka dla generowanych dok. dot. contenttype, to czy dokument ma być wygenerowany w html czy w pdf decyduje od tej pory wpis w pliku info.php dla danej templatki a nie zmienna w UI',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano skrypt do importu płatności z Alior Bank w formacie xml, skrypt w contrib/bin/cashimport-alior-xml.php<br>W pliku należy wpisać namiary na skrzynkę pocztową itp',
	),
	array(
	    'data'	=> '2015-01-22',
	    'status'	=> '',
	    'opis'	=> 'poprawka edycji faktury, błędnie podstawiał pola dla odbiorcy faktury i adresu wysyłki ',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka choosecustomersearch.php , nie można było wybrać klienta jak w nazwie był użyty znak "',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka skryptu lms-payments, nie naliczał abonamentu jak w zobowiązaniu klienta było tylko naliczanie',
	),
	array(
	    'data'	=> '2015-01-19',
	    'status'	=> '',
	    'opis'	=> 'Szybkie wyszukiwanie klientów - rozbudowano o pola : NIP, PESEL, REGON, EDG/KRS i Dow. os.',
	),
	array(
	    'data'	=> '2015-01-15',
	    'status'	=> '',
	    'opis'	=> 'poprawka invoicelist dla postgresa',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'rozbudowano filtr STATUS na liście klientów o pola : bilans zerowy , z nadpłątą',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'rozbudowano filtr ZOBOWIĄZANIA na liście klientów o pola : tylko naliczanie, naliczanie z fakturą, naliczanie z proformą',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka update bazy',
	),
	array(
	    'data'	=> '2015-01-10',
	    'status'	=> '',
	    'opis'	=> 'dodano wyszukiwanie klienta po : nr telefonu, nr. gadugau, skype i yahoo , (dane z karty klienta)',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano katalog templates/custom w którym możemy trzymać własne pliki html zastępując oryginalne szablony',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'rozbudowa tabeli documents o pola post_*, celem jest zapamiętanie adresu wysyłki faktury',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'w konfiguracji firmy/oddziały dodano pole URL Logo File, logo będzie na fakturach, fakturach korygujących i proforma, dotyczy tylko faktur w wersji 2 i ma wyższy priorytet od zmiennej w UI invoices.urllogofile',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'przebudowa pliku bin/lms-payments który jest jedynym słusznym skryptem do naliczania faktur / opłat abonamentów w LMS',
	),
	array(
	    'data'	=> '',
	    'status'	=> 'del',
	    'opis'	=> 'usunięto plik bin/lms-payments.php',
	),
	array(
	    'data'	=> '',
	    'status'	=> 'del',
	    'opis'	=> 'usunięto plik bin/lms-payments-proforma',
	),
	array(
	    'data'	=> '',
	    'status'	=> 'del',
	    'opis'	=> 'usunięto starą wersję USERPANEL',
	),
	array(
	    'data'	=> '2015-01-07',
	    'status'	=> '',
	    'opis'	=> 'dostosowano wygląd faktur korygujących do obecnych przepisów',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'doszedł słownik powodów korekty faktury',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dla faktur pdf w formacie FT-0100 na małym blankiecie w nazwie odbiorcy została zastosowana nazwa skrócona firmy / oddziału',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano skrypt bin/lms-invoice2pdf.php.<br>
			    Za pomocą tego skryptu możemy wygenerować pliki pdf dla faktur, korekt i faktur proforma. Jeżeli będziemy chcieli trzymać faktury w formie pdf to skrypt należy odpalić po skrypcie lms-payments.<br>
			    zalecane pierwsze uruchomienie z przełącznikiem --help',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'UI -> sekcja invoices , doszły nowe przełączniki<br>
			    <b>create_pdf_file</b> - tworzenie w locie plików pdf na serwerze dla faktur i korekt <b>Def.: 0</b><br>
			    <b>create_pdf_file_proforma</b> - tworzenie w locie plików pdf na serwerze dla faktur proforma <b>Def.: 0</b><br>
			    <b>deleted_closed</b> - umożliwia kasowanie zamkniętych faktur <b>Def.: 0</b><br>
			    <b>edit_closed</b> - umożliwia edycję zamkniętych faktur <b>Def.: 0</b><br><br>
			    Domyślnym katalogiem zapisu plików jest documents/invoice_pdf , ściężkę można zmienic dodając w lms.ini w sekcji [directories] wpis np.:<br>
			    invoice_dir = /mojasciezka/faktury',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'USERPANEL -> Konfiguracja -> VoIP Hiperus C5 -> dodano możliwość ukrycia hasła dla terminala w UP, ',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'USERPANEL - naniesiono kilka poprawek',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka kilku plików php z katalogu bin, poprawka dotyczy includowania nagłówka.<br>
			    <B>WAŻNE</b><br>
			    należy skopiować lub zrobić dowiązanie symboliczne pliku z contrib/init_lms.php do /etc/lms/',
	),
	array(
	    'data'	=> '2015/01/04',
	    'status'	=> '',
	    'opis'	=> 'Dodano templatkę dla faktur pdf zgodną z nowymi przepisami zachowując możliwość drukowania i wystawiania faktur w starej wersji. Dodana jest osbługa różnych wersji templatek.<br>
			    Po aktualizacji należy skonfigurować nowe zmienne w UI sekcja invoices<br>
			    <b>urllogofile</b> -> ścieżka do loga firmy które możemy umieścić na fakturach<br>
			    <b>template_version</b> -> wersja templatki, dla nowej należy ustawić wartość 2, DEF.: 1<br>
			    <b>template_file_proforma</b> -> szablon dla faktur PROFORMA<br>
			    <b>set_protection</b> -> czy wygenerowany dokument pdf ma być zabezpiecony przed modyfikacją<br>
			    <b>sdateview</b> -> czy na fakturze ma być widoczna data dostawy / wykonania usługi, jeżeli wystawiamy faktury z automatu a w pozycjach zawarty jest okres za jaki to dotyczy to wartość ustawiamy na 0, w innym przypadku na 1. Przy ręcznym wystawianiu faktury możemy zdefiniować czy ów data ma być widoczna.<br><br>
			    Przebudowano również skrypty lms-payments i lms-payments-proforma, i tylko tych dwóch skryptów należy używać do wystawiania faktur !!!',
	),
	array(
	    'data'	=> '2015/01/01',
	    'status'	=> '',
	    'opis'	=> 'poprawka wystawiania faktur oraz rozliczeń konta kontrahenta',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'aktualizacja bazy ethercodes',
	),
	array(
	    'data'	=> '2014/12/31',
	    'status'	=> '',
	    'opis'	=> 'Dodano przycisk wyłączający blokadę w "Finanse/Historia importów"',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Aktualizacja TCPDF do wersji 5.9.209',
	),
	array(
	    'data'	=> '2014/12/10',
	    'status'	=> '',
	    'opis'	=> 'Dodano obsługę wysyłek sms za pomocą Mikrotika,<br>konfiguracja w UI sekcja sms',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawa ścieżek include w ZEND na potrzeby Jambox',
	),
	array(
	    'data'	=> '2014/11/24',
	    'status'	=> '',
	    'opis'	=> 'poprawka błędów przy nadawaniu adresów IP',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'Dodano obsługę <b>SGT Jambox</b> :)<br><b>UWAGA</b> - należy uzupełnić zmienne konfiguracyjne w UI sekcja Jambox, moduł nie korzysta już z lmstv.ini !!!',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka konwersji faktury PRO na VAT oraz poprawka odczytu danych firmy na fakturze jeżli pola div_* są puste w tabeli documents',
	),
	array(
	    'data'	=> '2014/11/05',
	    'status'	=> '',
	    'opis'	=> 'dodano obsługę serwisu SerwerSMS',
	),
        array(
            'data'      => '2014/10/31',
            'status'    => '',
            'opis'      => 'dodano filtr w liście klientów "blokady/ zawieszone blokowanie" - dla klientów z zawieszonym blokowaniem oraz "zobowiązania / zawieszone naliczanie" - dla klientów z zawieszonym naliczaniem dodanych taryf',
        ),
    
        array(
            'data'      => '2014/10/26',
            'status'    => '',
            'opis'      => 'w liście komputerów dodano status "z blokadą" i "z powiadomieniem"',
        ),


	array(
	    'data'	=> '2014/10/25',
	    'status'	=> '',
	    'opis'	=> 'Dodano prostą ewidencję pojazdów',
	),
	array(
	    'data'	=> '2014/10/22',
	    'status'	=> '',
	    'opis'	=> 'zmiany dot. uzupełnienia pól w documents div_*, dokumenty KP, KW które są wystawione dla klientów spoza naszej sieci dane są brane na podstawie default_division',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano zmienną w UI phpui.default_division jest to domyślne ID firmy dla dokumentów wystawianych dla klientów nie będącymi klientami sieci / brak ich na liście',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano szybkie wyszukiwanie klienta na podstawie numeru dokumentu',
	),
	array(
	    'data'	=> '2014/10/21',
	    'status'	=> '',
	    'opis'	=> 'dodano dodatkowe pole blokada dla komputerów, do wykorzystania np. na przekierowanie o całkowitej blokadzie usług',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano pole IP NAT w sieciach IP',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'dodano pole UPUST w taryfach, do wykorzystania w umowach',
	),
	array(
            'data'      => '2014/10/07',
            'status'    => '',
            'opis'      => 'w liście komputerów dodano status "bez zobowiązań" wyświetlający komputery nie powiązane z żadną taryfą',
	),
	 array(
            'data'      => '2014/09/15',
            'status'    => '',
            'opis'      => 'w /contrib/bin dodano parser dla formatu 123elixir banku BZWBK',
        ),
       array(
            'data'      => '2014/09/15',
            'status'    => '',
            'opis'      => 'dodano sortowanie w Sieci IP',
	),
	array(
	    'data'	=> '2014/06/14',
	    'status'	=> '',
	    'opis'	=> 'poprawka błędu przy dodawaniu/edycji komputera jeżeli włączone jest wymuszone połączenie z interfejsem sieciowym, a w systemie nie zdefiniowano żadnego intefejsu sieciowego',
	),
	array(
	    'data'	=> '',
	    'status'	=> '',
	    'opis'	=> 'poprawka dla ethercodes',
	),
	array(
	    'data'	=> '',
	    'status'	=> 'update',
	    'opis'	=> 'Naniesiono kilka poprawek odnośnie kontahentów',
	),
	array(
	    'data'	=> '2014/05/21',
	    'status'	=> '',
	    'opis'	=> 'poprawka dla monitoringu',
	),
	array(
	    'data'	=> '2014/05/15',
	    'status'	=> '',
	    'opis'	=> 'Poprawka modułu radius',
	),
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