<?

if (!defined('LMS_API_SRV')) die; // ważny nagłówek stosować wszędzie

$_result = NULL;
$totime = null;
foreach($options as $item => $key)
{
    switch (strtolower($item))
    {
	case 'id'		: $id = $key; break;
	case 'totime'		: $totime = $key; break;
    }
}
$_result = $LMS->GetCustomerBalance($id,$totime); 


?>