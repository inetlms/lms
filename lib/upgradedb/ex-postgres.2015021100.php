<?php

$DB->Execute("DROP VIEW IF EXISTS vnodes ;");
$DB->Execute("DROP VIEW IF EXISTS vmacs;");
$DB->Execute("ALTER TABLE nodes ADD pppoelogin VARCHAR( 128 ) DEFAULT '';");

$DB->Execute("
    CREATE VIEW vnodes AS
    SELECT n.*, m.mac
    FROM nodes n
    LEFT JOIN (SELECT nodeid, array_to_string(array_agg(mac), ',') AS mac
        FROM macs GROUP BY nodeid) m ON (n.id = m.nodeid);
");

$DB->Execute("
CREATE VIEW vmacs AS 
	SELECT n.*, m.mac, m.id AS macid 
	FROM nodes n 
	JOIN macs m ON (n.id = m.nodeid);
");

$DB->addconfig('netdevices','pppoe_login','0','');

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015021100', 'dbvex'));

?>