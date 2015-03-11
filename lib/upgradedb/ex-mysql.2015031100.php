<?php

$DB->BeginTrans();

$DB->Execute('UPDATE nodes SET linkspeed = ? WHERE linkspeed = ? ;',array(20000,25000));
$DB->Execute('UPDATE nodes SET linkspeed = ? WHERE linkspeed = ? ;',array(30000,54000));
$DB->Execute('UPDATE nodes SET linkspeed = ? WHERE linkspeed = ? ;',array(150000,200000));
$DB->Execute('UPDATE nodes SET linkspeed = ? WHERE linkspeed = ? ;',array(250000,300000));
$DB->Execute('UPDATE nodes SET linkspeed = ? WHERE linkspeed = ? ;',array(1000000,1250000));

$DB->CommitTrans();

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015031100', 'dbvex'));

?>