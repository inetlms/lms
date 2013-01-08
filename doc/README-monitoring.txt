Monitoring How-To
Wymagania:

Dla prawidłowego działania monitoringu wymagane są pakiety :

PERL - Perl::Ping , Perl::Net , Bundle::LWP , Lwp , Lwp::UserAgent , Net::SNMP , Net::Tetlen::Cisco , Net::SSH::Perl

Przykładowa instalacja (sprawdzone pod debianem)

perl -MCPAN -e 'install force install Perl::Ping'
perl -MCPAN -e 'install force install Perl::Net'
perl -MCPAN -e 'install force install Bundle::LWP'
perl -MCPAN -e 'install force install Lwp'
perl -MCPAN -e 'install force install Lwp::UserAgent'
perl -MCPAN -e 'install force install Net::SNMP'
perl -MCPAN -e 'install force install Net::Tetlen::Cisco'
perl -MCPAN -e 'install force install Net::SSH::Perl'

PEAR
apt-get install php-pear
pear install mail
pear install Net_SMTP
pear install Auth_SASL
pear install mail_mime

W crontab dodajemy wpis:
*/1 * * * * root /var/www/lms-ex/bin/lms-monitoring.php --config-file=/etc/lms/lms.ini --autotest --q
lub
*/1 * * * * root php /var/www/lms-ex/bin/lms-monitoring.php --config-file=/etc/lms/lms.ini --autotest --q

zmienną --config-file musimy użyć jeżeli plik lms.ini jest w innej lokalizacji niż /etc/lms , domyślną wartością jest /etc/lms/lms.ini
Podstawowa konfiguracja

W konfiguracji monitoringu ( sekcja monitoring, opcja step_test_* ) podajemy czas w minutach określający interwał testu dla danych urządzeń.
Zmieniając wartość dla step_test należy wyczyścić statystyki !!!

Jeżeli będziemy chcieli ręcznie przeprowadzić test z shell, to skrypt odpalamy z przełącznikiem -t (test), przełącznik ten
zabroni skryptowi dodania informacji o czasach odpowiedzi do bazy danych.
Pominięcie tego przełącznika podczas ręcznego testu spowoduje błędy w statystykach.

Ważne jest również prawidłowe podanie scieżki do lms-monitoring.pl , opcja test_script_dir

Pozostałe wartości dla monitoringu ustawiamy już z poziomu LMS. 
