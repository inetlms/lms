<?

if (!defined('LMS_API_SRV')) die; // ważny nagłówek stosować wszędzie

$_result = NULL;
foreach($options as $item => $key)
{
    switch (strtolower($item))
    {
	case 'id'		: $id = $key; break;
	case 'count'		: $count = $key; break;
    }
}
$_result = $LMS->GetCustomerNodes($id,$count); 


?>