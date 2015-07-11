<?php

$DB->BeginTrans();

$DB->Execute('ALTER TABLE plug ALTER COLUMN indexes TYPE VARCHAR(40);');

$DB->CommitTrans();

$DB->Execute('UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?;', array('2015070500', 'dbvex'));

?>