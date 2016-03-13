<?php

function SprawdzSiecTelefonu($telefon) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'http://download.t-mobile.pl/updir/updir.cgi?msisdn='.$telefon);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 15);
    $wynik = curl_exec($curl);
    curl_close($curl);
    if (strpos($wynik,'--- Brak danych ---')) {
	$operator['kod']='000 00';
	$operator['nazwa']='NIEZNANY';
    } else {
	$wynik = strip_tags($wynik);
	$wynik = substr($wynik,10+strpos($wynik,'Kod sieci'));
	$operator['kod']=substr($wynik,0,6);
	$operator['nazwa']=substr($wynik,15);
    }
    return $operator;
}

 
