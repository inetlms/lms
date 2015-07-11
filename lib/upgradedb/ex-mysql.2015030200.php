<?php

// iNET LMS

$DB->BeginTrans();

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'uke','version'))) 
$DB->Execute("ALTER TABLE uke ADD version VARCHAR(8) NULL DEFAULT NULL COMMENT 'wersja specyfikacji';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'uke','revision'))) 
$DB->Execute("ALTER TABLE uke ADD revision VARCHAR(8) NULL DEFAULT NULL;");

$DB->Execute("ALTER TABLE uke_data CHANGE data data LONGTEXT CHARACTER SET utf8 COLLATE utf8_polish_ci NULL DEFAULT NULL;");
$DB->Execute("UPDATE uke SET version='4';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030200', 'dbvex'));

$DB->CommitTrans();

?>
