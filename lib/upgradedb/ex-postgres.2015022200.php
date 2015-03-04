<?php

$DB->BeginTrans();

$DB->Execute('UPDATE networknode SET buildingtype = ? WHERE buildingtype = ? ;',array(9,13));
$DB->Execute('UPDATE networknode SET buildingtype = ? WHERE buildingtype = ? ;',array(9,14));
$DB->Execute('UPDATE networknode SET buildingtype = ? WHERE buildingtype = ? ;',array(9,15));
$DB->Execute('UPDATE networknode SET buildingtype = ? WHERE buildingtype = ? ;',array(9,16));
$DB->Execute('UPDATE networknode SET buildingtype = ? WHERE buildingtype = ? ;',array(2,19));
$DB->Execute('UPDATE networknode SET buildingtype = ? WHERE buildingtype = ? ;',array(23,18));

$DB->CommitTrans();

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015022200', 'dbvex'));

?>