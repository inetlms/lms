<?php

$DB->BeginTrans();

$DB->Execute('UPDATE uke SET report_type = ?;',array('SIIS'));

$DB->CommitTrans();

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015031500', 'dbvex'));

?>