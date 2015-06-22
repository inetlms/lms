<?php

/*

$__RIGHTS = array(
    
    'global'	=> array(
	'fullaccess'	=> 'pełny dostęp',
	'useredit'	=> 'zarządzanie użytkownikami',
	'reload'	=> 'przeładowanie systemu',
	'config'	=> 'konfiguracja systemu',
	'slownik'	=> 'zarządzanie słownikami',
    ),
    'customer'	=> array(
	'edit'	=> 'edycja danych',
	'view'	=> 'przegląd danych',
	'add'	=> 'dodawanie nowych klientów',
    ),
    'node'	=> array(
	'edit'	=> 'edycja danych',
	'view'	=> 'przegląd danych',
	'add'	=> 'dodawanie nowych klientów',
    ),

);
*/
$_tmp_ = $DB->GetOne('SELECT exrights FROM users WHERE id = ? LIMIT 1;',array($AUTH->id));

if (!empty($_tmp_)) {
    $_tmp_ = unserialize($_tmp_);
    
    foreach ($_tmp_ as $key => $row) {
	for ($i=0; $i<sizeof($row); $i++)
	    $RIGHTS_USER[] = $row[$i];
    }
}

if ($rights = $LMS->GetUserRights($AUTH->id))
foreach ($rights as $level)
{
    if ($level === 0) {
		$CONFIG['privileges']['superuser'] = true;
		if (!defined('SUPERUSER'))
		    define('SUPERUSER',true);
		continue;
    }
}


function get_rights($name) {
    
    global $RIGHTS_LIST,$RIGHTS_USER;
    
    if (defined('SUPERUSER') && SUPERUSER == true)
	return TRUE;
    
    list($section, $name) = explode('.', $name, 2);
    $_section = strtoupper($section);
    $_name = strtoupper($name);
    
    $md5 = md5($_section.$_name);
    
    if (!in_array($md5,$RIGHTS_LIST[$section]))
	return TRUE;
    
    if (in_array($md5,$RIGHTS_USER))
	return TRUE;
    else
	return FALSE;
}

?>