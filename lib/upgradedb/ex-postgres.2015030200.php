<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("ALTER TABLE uke ADD version VARCHAR(8) DEFAULT NULL;");
$DB->Execute("ALTER TABLE uke ADD revision VARCHAR(8) DEFAULT NULL;");
$DB->Execute("UPDATE uke SET version='4';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030200', 'dbvex'));

$DB->CommitTrans();

?>
