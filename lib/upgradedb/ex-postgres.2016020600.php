<?php

$DB->BeginTrans();

if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('netdevices','ibgp')))
$DB->execute("ALTER TABLE netdevices ADD ibgp bigint not null default 0;");

if (!$DB->GetOne('SELECT 1 FROM information_schema.columns WHERE table_name = ?  AND column_name=? ;',array('netdevices','ebgp')))
$DB->execute("ALTER TABLE netdevices ADD ebgp bigint not null default 0;");

$DB->Execute('UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?', array('2016020600', 'dbvex'));

$DB->CommitTrans();

?>
