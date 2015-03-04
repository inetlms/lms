<?php

// iNET LMS

$DB->BeginTrans();


if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('networknode','invprojectid'))
    $DB->Execute("ALTER TABLE networknode ADD COLUMN invprojectid integer DEFAULT NULL");

if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('networknode','status'))
    $DB->Execute("ALTER TABLE networknode ADD COLUMN status smallint DEFAULT 0");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015022203', 'dbvex'));

$DB->CommitTrans();

?>
