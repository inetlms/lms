<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("
CREATE TABLE formconfig (
    id int(11) NOT NULL auto_increment,
    section varchar(64) NOT NULL DEFAULT '',
    var varchar(64) NOT NULL DEFAULT '',
    value tinyint(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY var (section, var)
) ENGINE=InnoDB;
");


$pubip = get_conf('phpui.public_ip');
$pppoe = get_conf('netdevices.pppoe_login');
$autoname = get_conf('netdevices.node_autoname');

$DB->Execute('INSERT INTO formconfig (section,var,value) VALUES (?,?,?);',array('nodes','public_ip',($pubip ? 1 : 0)));
$DB->Execute('INSERT INTO formconfig (section,var,value) VALUES (?,?,?);',array('nodes','pppoe_login',($pppoe ? 1 : 0)));
$DB->Execute('INSERT INTO formconfig (section,var,value) VALUES (?,?,?);',array('nodes','node_autoname',($autoname ? 1 : 0)));

$DB->Execute('DELETE FROM uiconfig WHERE section=? AND var=?;',array('phpui','public_ip'));
$DB->Execute('DELETE FROM uiconfig WHERE section=? AND var=?;',array('netdevices','pppoe_login'));
$DB->Execute('DELETE FROM uiconfig WHERE section=? AND var=?;',array('netdevices','node_autoname'));

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030700', 'dbvex'));

$DB->CommitTrans();

?>
