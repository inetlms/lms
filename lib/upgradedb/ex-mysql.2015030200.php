<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("ALTER TABLE uke ADD version VARCHAR(8) NULL DEFAULT NULL COMMENT 'wersja specyfikacji';");
$DB->Execute("ALTER TABLE uke ADD revision VARCHAR(8) NULL DEFAULT NULL;");
$DB->Execute("ALTER TABLE uke_data CHANGE data data LONGTEXT CHARACTER SET utf8 COLLATE utf8_polish_ci NULL DEFAULT NULL;");
$DB->Execute("UPDATE uke SET version='4';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030200', 'dbvex'));

$DB->CommitTrans();

?>
