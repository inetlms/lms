<?

if (!defined('LMS_API_SRV')) die; // ważny nagłówek stosować wszędzie

$_result = NULL;

foreach($options as $item => $key)
{
    switch (strtolower($item))
    {
//	case 'aktywne' 		: $addsql .= ' AND aktywne=\''.$key.'\' ';		break;
//	case 'usuniete'		: $addsql .= ' AND usuniete=\''.$key.'\' ';		break;
	case 'id'		: $cid = $key; break;
    }
}


// przykład wykorzystania funkcji z LMS.class.php
$_result = $LMS->GetCustomer($cid); 

/* przykład
$_result = $DB->GetAll('SELECT costam FROM jakastabela WHERE 1=1 '.$addsql);

*/

?>