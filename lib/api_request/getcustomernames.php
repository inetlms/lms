<?

if (!defined('LMS_API_SRV')) die; // ważny nagłówek stosować wszędzie

$_result = $DB->GetAllByKey('SELECT id, ' . $DB->Concat('lastname', "' '", 'name') . ' AS customername 
		FROM customers WHERE status > 1 AND deleted = 0 ORDER BY lastname, name', 'id');



?>