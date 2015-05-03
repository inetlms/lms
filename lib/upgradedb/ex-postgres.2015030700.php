<?php

// iNET LMS

$DB->BeginTrans();


$DB->Execute("CREATE SEQUENCE formconfig_id_seq;");
$DB->Execute("
CREATE TABLE formconfig (
    id 		integer 	DEFAULT nextval('formconfig_id_seq'::text) NOT NULL,
    section 	varchar(64) 	NOT NULL DEFAULT '',
    var 	varchar(64) 	NOT NULL DEFAULT '',
    value 	smallint 	NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    CONSTRAINT formconfig_section_key UNIQUE (section, var)
);
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
