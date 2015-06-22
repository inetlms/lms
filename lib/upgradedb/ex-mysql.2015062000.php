<?php

$DB->BeginTrans();


if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
    array($DB->_dbname,'users','exrights'))) 
    $DB->execute("ALTER TABLE users ADD exrights LONGTEXT NULL DEFAULT NULL COMMENT 'info o rozszerzonych prawach dostępu';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015062000', 'dbvex'));

$DB->CommitTrans();

?>
