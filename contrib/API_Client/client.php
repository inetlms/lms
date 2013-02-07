<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2012 LMS iNET Developers
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
 *  $Id: v 1.00 2013/01/11 13:01:35 Sylwester Kondracki Exp $
 */




// -------------------------------- PLIK PRZYKŁADOWY ----------------------------------------------





$apifile = dirname(__FILE__).'/LMS.apiclient.class.php';  // ścieżka do klasy

if (file_exists($apifile)) require_once($apifile); else die('brak pliku '.$apifile);

// login, hasło, domena, klucz szyfrujacy dane, url, port - jeżeli np. mam to na virtualce
$API = new LMS_API_CLIENT('login','haslo','DOMENA.NET','klucz_szyfrujacy','http://mojlms.pl/api.php',NULL);


// PIERWSZY SPOSÓB POBIERANIA DANYCH, wolniej działa przy dużej ilości zapytań, dobre rozwiązanie jak potrzebujemy wysłać pojedyncze zapytanie
// każde zapytanie jest odrazu wysyłane , a wynikiem jest odpowiedź z serwera w postaci tablicy
// Domyślne ustawienie dla AutoSend = TRUE

//$API->SetAutoSend(true); // nie musimy ustawiać dla true, chyba że po drodze przestawiny tylko na false 

$wynik = array();
$wynik[] = $API->Request('getremote');
$wynik[] = $API->request('getcustomerbalance',array('id'=>1,'totime'=>time()-86400)); // id klienta
$wynik[] = $API->request('getcustomerbalancelist',array('id'=>1,'totime'=>time()-86400)); // id klienta
$wynik[] = $API->request('getcustomername',array('id'=>1));  // id klienta
$wynik[] = $API->request('getcustomer',array('id'=>1));  // id klienta
$wynik[] = $API->request('getcustomernodes',array('id'=>1)); // id klienta
$wynik[] = $API->request('getcustomernames',null);
$wynik[] = $API->request('getnode',array('id'=>2));


echo "<pre>";
print_r($wynik);
echo "</pre>";




// ****************************************************************************************************************************
// DRUGI SPOSÓB - HURTOWE WYSŁANIE ZAPYTAŃ , szybciej działa w takich przypadkach

$API->InitRequest(); // inicjujemy

$API->SetAutoSend(false);

$wynik = array();
$balance = $API->request('getcustomerbalance',array('id'=>1,'totime'=>time()-86400)); // id klienta
$balancelist = $API->request('getcustomerbalancelist',array('id'=>1,'totime'=>time()-86400)); // id klienta
$customername = $API->request('getcustomername',array('id'=>1));  // id klienta
$customerinfo = $API->request('getcustomer',array('id'=>1));  // id klienta
$customernodes = $API->request('getcustomernodes',array('id'=>1)); // id klienta
$customernode = $API->request('getnode',array('id'=>2)); // id komputera

$API->Send(); // wysyłamy żądanie do serwera
$result = $API->GetResult(); // pobieramy dane do tablicy

$API->SetAutoSend(true); // włączamy ponownie automatyczne wysyłanie zapytań

echo 'Klient: '.$result[$customername].'<br>';
echo 'Bilans: '.$result[$balance].'<br>';
echo "Komputery: <pre>"; print_r($result[$customernodes]); echo "</pre> <br>";
echo "Info o kompie: <pre>"; print_r($result[$customernode]); echo "</pre> <br>";

// a dalej już robimy z wynikiem co chcemy

?>