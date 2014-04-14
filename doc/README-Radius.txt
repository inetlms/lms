.1
 nalezy do /etc/sudoers dodać następujące wpisy
www-data ALL = NOPASSWD: /usr/bin/radtest
www-data ALL = NOPASSWD: /usr/bin/radclient

.2 Jeżeli do tej pory tabele radiusa były poza bazą LMS'a to musimy niestety przenieść dane do DB LMS'a i przebudować konfig radiusa


