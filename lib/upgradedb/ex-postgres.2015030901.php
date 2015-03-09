<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("ALTER TABLE netdevices ADD login VARCHAR( 128 ) DEFAULT NULL;");
$DB->Execute("ALTER TABLE netdevices ADD passwd VARCHAR( 128 ) DEFAULT NULL ;");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030901', 'dbvex'));

$DB->CommitTrans();

?>
