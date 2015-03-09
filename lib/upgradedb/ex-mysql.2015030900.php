<?php

$DB->BeginTrans();

$DB->Execute("ALTER TABLE netdevicemodels ADD ean VARCHAR( 13 ) NOT NULL DEFAULT '';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030900', 'dbvex'));

$DB->CommitTrans();

?>
