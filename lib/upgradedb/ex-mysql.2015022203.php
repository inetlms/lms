<?php

// iNET LMS

$DB->BeginTrans();


if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'networknode','invprojectid'))) 
    $DB->Execute("ALTER TABLE networknode ADD COLUMN invprojectid int(11) DEFAULT NULL");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'networknode','status'))) 
    $DB->Execute("ALTER TABLE networknode ADD COLUMN status tinyint(1) DEFAULT '0'");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015022203', 'dbvex'));

$DB->CommitTrans();

?>
