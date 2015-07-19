<?php

$DB->BeginTrans();

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'info_center','mp3'))) 
$DB->Execute("ALTER TABLE info_center ADD mp3 VARCHAR(50) NULL DEFAULT NULL COMMENT 'plik audio';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015040700', 'dbvex'));

$DB->CommitTrans();

?>
