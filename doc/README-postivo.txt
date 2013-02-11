Opis i konfiguracja


Skrypt: lms-sendinvoiceswithpostivo


1. Opis i konfiguracja

Skrypt służy do wysyłania faktur papierowych za pośrednictwem Postivo.pl.
Faktury generowane są na podstawie szablonu dostępnego w lms-ui, dlatego
wymagane jest podanie klienta i hasła do interfejsu www lms-ui. Wysyłać
można wyłącznie faktury generowane w formacie PDF. Przed rozpoczęciem
konfiguracji należy zarejestrować konto w Postivo.pl oraz aktywować dostęp
do konta przez API. Opis w jaki sposób to zrobić znajduje się na
http://postivo.pl/pomoc-instrukcja-samouczek

Skrypt ten wymaga dodatkowych modułów perla: 
          LWP::UserAgent
          SOAP::Lite
          MIME::Base64


Konfigurację należy umieścić w pliku lms.ini sekcja

[sendinvoiceswithpostivo]


lms_url - Adres do lms-ui. Domyślnie: http://localhost/lms/
Przykład: lms_url = http://lms.mynet.pl

lms_user - Login użytkownika. Domyślnie: pusty
Przykład:  lms_user = admin

lms_password - Hasło do lms-ui. Domyślnie: puste
Przykład: lms_password = moje_hasło


postivo_user - Login konta użytkownika w Postivo.pl. Domyślnie: nie zdefiniowany.
Przykład: postivo_user = user

postivo_api_password - Hasło dostępu do API Postivo.pl. Domyślnie: nie zdefiniowane.
Przykład: postivo_api_password = hasło

customergroups - Lista nazw grup (oddzielonych spacjami), które mają być uwzględnione
        podczas wysyłki. Faktury dla klientów należących do wskazanych grup
        wysyłane będą przez Postivo.pl. Domyślnie: nie ustawiona - wszystkie grupy.
Przykład: customergroups = grupa1 grupa2

sender_id - ID nadawcy przesyłek zdefiniowanego w Postivo.pl, który będzie wykorzystywany
        podczas wysyłania faktur. Domyślnie: pusty (pod uwagę brany będzie domyślny
        nadawca zdefiniowany w Postivo.pl)
Przykład: sender_id = 2

config_id - ID profilu konfiguracji zdefiniowanego w Postivo.pl, który będzie wykorzystywany
        podczas wysyłania faktur. Domyślnie: pusty (pod uwagę brany będzie domyślny profil
        konfiguracji zdefiniowany w Postivo.pl)
Przykład: config_id = 5

sandbox - Określa czy przesyłki mają być przesyłane do systemu testowego Postivo.pl celem
          sprawdzenia funkcjonowania usługi. Domyślnie: 0 (tryb testowy wyłączony)
Przykład: sandbox = 1



         Ponadto mamy do dyspozycji jeden przydatny parametr wiersza poleceń
--fakedate | -f. Przy jego użyciu można  sprawić, aby skrypt działał z podmienioną datą systemową (w formacie YYYY/MM/DD),
 na przykład --fakedate=2004/10/10





2. Instalacja


Konfigurację skryptu wprowadza się w sekcji [sendinvoiceswithpostivo] w pliku lms.ini
Skrypt przenieś z katalogu /bin  do katalogu /usr/sbin

Po przeniesieniu musisz go jeszcze dopisać do crontaba tak, aby był uruchamiany
automatycznie, właśnie wtedy kiedy tego chcesz.


!!! OSTRZEŻENIE !!!

Pamiętaj, że uruchomienie skryptu oznacza, że za pośrednictwem Postivo.pl zostaną wysłane wszystkie faktury wystawione
w ciągu ostatnich 24 godzin, zatem uruchamiaj skrypt wyłącznie jeden raz dziennie! Ponowne uruchomienie skryptu spowoduje ponowną wysyłkę faktur.

Przykładowo, wpis w crontabie dla skryptu lms-sendinvoiceswithpostivo (wykonywanego codziennie o godzinie 00:01) powinien wyglądać następująco:
1 0 * * *       /usr/sbin/lms-sendinvoiceswithpostivo 1 > /dev/null

Po więcej informacji możesz sięgnąć do man crontab

Skrypt posiada dodatkowe opcje uruchomieniowe:
-C plik     położenie i nazwa alternatywnego pliku lms.ini, domyślnie /etc/lms/lms.ini
-q          wykonanie skryptu bez wyświetlania komunikatów
-h          pomoc (a w zasadzie to tylko listing opcji)
-v          informacja o wersji skryptu
