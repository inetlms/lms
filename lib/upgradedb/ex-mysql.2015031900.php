<?php

// iNET LMS

$DB->BeginTrans();

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'uke','vercsv'))) 
$DB->Execute("ALTER TABLE uke ADD vercsv VARCHAR(10) NULL DEFAULT NULL COMMENT 'wersja generatora csv';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015031900', 'dbvex'));

$DB->CommitTrans();

?>
