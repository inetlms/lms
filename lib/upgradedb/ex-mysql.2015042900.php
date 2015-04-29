<?php

$DB->BeginTrans();

$DB->Execute("ALTER TABLE documents ADD print_balance_info TINYINT(1) NOT NULL DEFAULT '1';");
$DB->AddConfig('invoices','print_balance_info','1');

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015042900', 'dbvex'));

$DB->CommitTrans();

?>
