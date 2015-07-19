<?php

$DB->BeginTrans();

if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('users','exrights')))
$DB->execute("ALTER TABLE users ADD exrights TEXT DEFAULT NULL COMMENT;");

$DB->Execute('UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?', array('2015062000', 'dbvex'));

$DB->CommitTrans();

?>
