LMS Hiperus C5 v.1.0.0

potrzebny soap i curl

WDROŻENIE

W  Logujemy się do LMS'a dodajemy nowego użytkownika o nazwie np. lms_hiperus, tworzymy mu hasło itd,
nadajemy mu prawa tylko : Obsługa VoIP HIPERUS C5 - Pełne uprawnienia
użytkownik ten potrzebny jest nam do wystawiania faktur VAT za VoIP'a

Dane rejestracyjne do zew. serisu
    tworzymy katalog /var/lib/hiperus i ustanawiamy prawa zapisu i odczytu dla apache

    tworzymy plik /var/lib/hiperus1.pwd z zwartością , prawa tylko do odczytu:
	login
	hasło
	domena
	
	chown 33:33 /var/lib/hiperus
	chown 33:33 /var/lib/hiperus/hiperus1.pwd
	chmod 644 /var/lib/hiperus/hiperus1.pwd
	chmod 755 /var/lib/hipeurs
	

    Jeżeli zachodzi potrzeba umieszczenia pliku w innej lokalizacji to należy przeedytować plik : HiperusLib.class.php
    na samym początku klasy podajemy ścieżkę


Pobranie danych z zew. serwisu do LMSa
    
    Pobieramy najpierw niezbędne info bez bilingów :
    ./lms_hiperus_c5_import.php --config-file=/etc/lms/lms.ini --import --customers --terminal --pstn --enduser --price --config --subscription
    lub
    ./lms_hiperus_c5_import.php --config-file=/etc/lms/lms.ini --import --all
    
    na końcu imporujemy listę bilingów, niestety może to trochę potrwać, długi czas pobierania danych jest uzależniony od zew. serwisu
    zalecane jest pobieranie danych z okresu max 6 miesięcy, 
    w przykładzie poniżej zakładam że bilingi istnieją od 2010-01-01 do dzisiaj (np. 2012-12-31),
    z testów wyszło że zew. seriws pozwala na pobranie bilingów z okresu max 9 miesięcy.
    
    Przełączniki :

	--b_date=okres
		okres -> nowday(dzisiaj), leftday(dzień poprzedni), nowmonth(miesiąc bieżący), leftmonth(miesiąc poprzedni)

	 LUB

	--b_from=RRRR-MM-DD -> data początkowa 
	--b_to=RRRR-MM-DD -> data końcowa 
	
	!!! Użycie przełącznika --b_date powoduje zignorowanie przełączników --b_from i --b_to !!!
	
	pozostałe przełączniki
	--b_type = all,incoming,outgoing,disa,forwarded,internal,vpbx -> typ dokonanych połączeń, domyślna wartość : outgoing

	--b_success=all,yes,no -> pobieranie bilingów o konkretnym statusie zrealizowanego połączenia lub wszystkich, domyślnie : yes
	
	
	PRZYKLAD, pobieramy bilingi z 3 lat, jeżeli mamy dość sporo klientów warto by było nieco bardziej
	rozdrobnić ramy czasowe pobieranych bilingów
	
	./lms_hiperus_c5_import.php --config-file --billing --b_type=all --b_success=all --b_from=2010-01-01 --b_to=2010-06-30
	./lms_hiperus_c5_import.php --config-file --billing --b_type=all --b_success=all --b_from=2010-07-01 --b_to=2010-12-31
	./lms_hiperus_c5_import.php --config-file --billing --b_type=all --b_success=all --b_from=2011-01-01 --b_to=2011-06-30
	./lms_hiperus_c5_import.php --config-file --billing --b_type=all --b_success=all --b_from=2011-07-01 --b_to=2011-12-31
	./lms_hiperus_c5_import.php --config-file --billing --b_type=all --b_success=all --b_from=2012-01-01 --b_to=2012-06-30
	./lms_hiperus_c5_import.php --config-file --billing --b_type=all --b_success=all --b_from=2012-07-01 --b_to=2012-12-30
	./lms_hiperus_c5_import.php --config-file --billing --b_type=all --b_success=all --b_date=nowday
	
	
	Istnieje również przełącznik --import, który powoduje że najpierw zostaną usunięte wszystkie dane z bazy LMS.
	dotyczące tylko VoIP'a , dla konkretnego przełącznika.
	Przełącznik --import działa tak samo ze wszystkimi pozostałymi przełącznikami.


	do cron'a dopisujemy pobieranie bilingów z dnia poprzedniego, ważne jest aby pobieranie bilingów w cron było wcześniej niż 
	wystawianie faktur za VoIP i wysyłką faktur VAT do klientów (jeżeli ktoś ma tak fajnie ustawione)
	
	PRZYKŁAD - pobieranie bilingów, zakładam że binarki LMSa są w /var/www/lms/bin
	
	01 01 * * *	root	/usr/bin/php /var/www/lms/bin/lms_hiperus_c5_import.php --config-file=/etc/lms/lms.ini --quiet --billing --b_type=all --b_success=all --b_date=leftday > /dev/null
	

Wystawianie faktur 
    
	Data wystawiania faktury VAT za VoIP, nie jest zależna od ustawień w karcie klienta.
	Abonament i koszt rozmów poza abonamentem jest wystawiany za pełny miesiąć od dnia 1 do ostatniego.
	Ważne jest, aby skrypt był odpalany po pobraniu bilingów a przed wysłaniem faktur do klientów
	do cron'a dodajemy kolejny wpis :
	
	15 3 1 * *	root	/usr/bin/php /var/www/lms/bin/lms_hiperus_c5_invoice.php --config-file=/etc/lms/lms.ini --quiet --leftmonth=1 > /dev/null
	
	Faktury będą wystawione każdego pierwszego danego miesiąca, za miesiąc poprzedni o godzinie 3:15
	Na fakturze będą znajwować się pozycje :
	    1 - Abonament XYZ
	    2 - koszt połączeń poza abonamentem XYZ
	dla każdego terminala osobno !!!
	
	Warunki które muszą być spełnione aby była wystawiona faktura:
	a) - konto VoIP musi być powiązane z klientem w LMSie
	b) - wartość faktury nie może być zerowa, jeżeli wartość = 0 zł to faktura nie jest wystawiana
	

Ogólny opis jak to działa.

    Wszystkie odczyty danych są dokonywane bezpośrednio z bazy danych LMSa,
    dodawanie, aktualizacja czy kasowanie kont,terminali,informacji itd są robione w trybie LIVE,
    np.
	jeżeli dodajemy nowe konto VoIP, to najpierw konto jest dodawane w zew. serwisie a następnie w LMS, jeżeli operacja przebiegła bez błędów.
	w przypadku gdy serwer zwróci info o błędzie to dane w LMS nie będą zmienione, lub zostaną automatycznie zaktualizowane do stanu faktycznego.
	
	w przypadku aktualizacji danych czy ich kasowania, schemat postępowania jest dokładnie taki sam.
	
    Nie zaleca się używania w kliku LMS'ach obsługi VoIP dotyczącej tego samego konta resellera,
    Jeżeli kiedyś może Telekomunikacja Bliżej wprowadzi callback informujący jakie zmiany zostały przeprowadzone itd,
    gdzie będzie można wpisać kilka adresów z namiarami do LMSa gdzie jest ten moduł to taki myk będzie można zrobić.
    
    Co zrobić jeżeli ktoś dokonał zmian w kontach VoIP przez panel zew. a w LMS nie widać zmian ?
    należy z poziomu shell zaktualizować dane, np. dane zostały zmienione w kilku kontach, informacje o konice VoIP, np. adres użytkownika
    
    root@debian:/#/usr/bin/php /var/www/lms/bin/lms_hiperus_c5_import.php --config-file=/etc/lms/lms.ini --customer
    
    zostaną dane zaktualizowane, !!! nie używamy przełącznika --import !!!
    
    podobnie postępujemy w przypadku innych zmian.

