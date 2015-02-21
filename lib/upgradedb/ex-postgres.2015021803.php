<?php

$DB->BeginTrans();

$DB->Execute('ALTER TABLE netdevices ALTER COLUMN model TYPE VARCHAR(128);');
$DB->Execute('ALTER TABLE nodes ALTER COLUMN model TYPE VARCHAR (128);');
$DB->Execute('ALTER TABLE netdeviceproducers ALTER COLUMN name TYPE VARCHAR(64);');
$DB->Execute('ALTER TABLE netdevicemodels ALTER COLUMN name TYPE VARCHAR(128);');
$DB->Execute('UPDATE netdeviceproducers SET name=UPPER(name);');

$DB->addconfig('phpui','syslog_maxrecord','150000','');
$DB->addconfig('phpui','gethostbyaddr','1','');
$DB->addconfig('phpui','netlist_pagelimit','50','');
$DB->addconfig('netdevices','node_autoname','0','');

$DB->CommitTrans();

$DB->Execute('UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?;', array('2015021803', 'dbvex'));

?>