<?php

$DB->BeginTrans();

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'documents','print_balance_info'))) 
$DB->Execute("ALTER TABLE documents ADD print_balance_info TINYINT(1) NOT NULL DEFAULT '1';");
$DB->AddConfig('invoices','print_balance_info','1');

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015042900', 'dbvex'));

$DB->CommitTrans();

?>
