<?php


$DB->BeginTrans();

$DB->addconfig('sms','mt_debug','0');
$DB->addconfig('sms','mt_host','');
$DB->addconfig('sms','mt_password','');
$DB->addconfig('sms','mt_port','8728');
$DB->addconfig('sms','mt_usb','usb1');
$DB->addconfig('sms','mt_username','');

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014121000', 'dbvex'));

$DB->CommitTrans();

?>
