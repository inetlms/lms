<?php

$DB->BeginTrans();

$DB->Execute("ALTER TABLE info_center ADD mp3 VARCHAR(50) NULL DEFAULT NULL COMMENT 'plik audio';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015040700', 'dbvex'));

$DB->CommitTrans();

?>
