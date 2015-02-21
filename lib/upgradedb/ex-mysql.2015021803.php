<?php

$DB->BeginTrans();

$DB->Execute("ALTER TABLE netdevices CHANGE model model VARCHAR(128) NOT NULL DEFAULT '';");
$DB->Execute("ALTER TABLE nodes CHANGE model model VARCHAR (128) NULL DEFAULT NULL;");
$DB->Execute("ALTER TABLE netdeviceproducers CHANGE name name VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ;");
$DB->Execute("ALTER TABLE netdevicemodels CHANGE name name VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ;");
$DB->Execute("UPDATE netdeviceproducers SET name=UPPER(name);");

$DB->addconfig('phpui','syslog_maxrecord','150000','');
$DB->addconfig('phpui','gethostbyaddr','1','');
$DB->addconfig('phpui','netlist_pagelimit','50','');
$DB->addconfig('netdevices','node_autoname','0','');
$DB->CommitTrans();

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015021803', 'dbvex'));

?>