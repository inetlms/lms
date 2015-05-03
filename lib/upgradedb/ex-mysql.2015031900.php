<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("ALTER TABLE uke ADD vercsv VARCHAR(10) NULL DEFAULT NULL COMMENT 'wersja generatora csv';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015031900', 'dbvex'));

$DB->CommitTrans();

?>
