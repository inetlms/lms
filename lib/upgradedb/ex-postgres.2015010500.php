<?php


$DB->BeginTrans();

$DB->addconfig('invoices','create_pdf_file','0');
$DB->addconfig('invoices','edit_closed','0');
$DB->addconfig('invoices','deleted_closed','0');

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015010500', 'dbvex'));

$DB->CommitTrans();

?>
