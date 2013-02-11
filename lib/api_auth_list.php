<?php

if (!defined('LMS_API_SRV')) die;
$API_AUTH = array();

/*
// PRZYKŁADY tworzenia kont dla klientów API
$API_AUTH['sylwek'] = array(
			'passwd'	=> sha1('tajnehaselko'), 	// hasło
			'domain'	=> 'PATI.NET',		// domena, trzeci człon zabezpieczający
			'active'	=> true,		// czy konto aktywne
			'secretkey'	=> 'misiek1234',	// klucz szyfrujący transmisję
			'remoteip'	=> NULL	// dozwolone host z jakiego klient może się logować, jeżeli nie ograniczami to warość = NULL
			);


$API_AUTH['sylwek2'] = array(
			'passwd'	=> sha1('tajnehaslo'),
			'domain'	=> 'PATI.NET',
			'active'	=> true,
			'secretkey'	=> 'misiek4321',
			'remoteip'	=> '127.0.0.1' // ograniczenie tylko do maszyny lokalnej
			);
*/
$API_AUTH['sylwek'] = array(
	'passwd'	=> sha1('misiek'), 	// hasło
	'domain'	=> 'PATI.NET',		// domena, trzeci człon zabezpieczający
	'active'	=> true,		// czy konto aktywne
	'secretkey'	=> 'misiek1234',	// klucz szyfrujący transmisję
	'remoteip'	=> NULL	// dozwolone host z jakiego klient może się logować, jeżeli nie ograniczami to warość = NULL
);



?>