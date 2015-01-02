<?php


$DB->BeginTrans();

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015010100', 'dbvex'));
$DB->Execute("INSERT INTO dbinfo (keyvalue,keytype) VALUES (?,?);",array('2015010100', 'dbvexp'));

$DB->CommitTrans();

?>
