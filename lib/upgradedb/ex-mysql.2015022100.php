<?php

$DB->BeginTrans();

$DB->Execute('UPDATE netlinks SET speed = ? WHERE speed = ? ;',array(20000,25000));
$DB->Execute('UPDATE netlinks SET speed = ? WHERE speed = ? ;',array(30000,54000));
$DB->Execute('UPDATE netlinks SET speed = ? WHERE speed = ? ;',array(150000,200000));
$DB->Execute('UPDATE netlinks SET speed = ? WHERE speed = ? ;',array(250000,300000));
$DB->Execute('UPDATE netlinks SET speed = ? WHERE speed = ? ;',array(1000000,1250000));

$DB->CommitTrans();

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015022100', 'dbvex'));

?>