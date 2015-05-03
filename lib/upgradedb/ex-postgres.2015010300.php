<?php


$DB->BeginTrans();

$DB->Execute("ALTER TABLE documents ADD version VARCHAR( 10 ) DEFAULT NULL;");
$DB->Execute("ALTER TABLE documents ADD templatetype VARCHAR( 10 ) DEFAULT NULL;");
$DB->Execute("ALTER TABLE documents ADD templatefile VARCHAR( 255 ) DEFAULT NULL");
$DB->Execute("ALTER TABLE documents ADD sdateview SMALLINT DEFAULT '0'");
$DB->Execute("ALTER TABLE documents ADD urllogofile VARCHAR( 255 ) DEFAULT NULL;");



$type = get_conf('invoices.type');
$template = get_conf('invoices.template_file');
$cnote_template = get_conf('invoices.cnote_template_file');

$DB->addconfig('invoices','template_version','1');
$DB->addconfig('invoices','set_protection','1');
$DB->addconfig('invoices','sdateview','0');
$DB->addconfig('invoices','template_file_proforma',$template);
$DB->addconfig('invoices','urllogofile','');


$DB->Execute('UPDATE documents SET version = ?, sdateview=? WHERE type IN (?,?,?);',array('1','1','1','3','6'));
$DB->Execute('UPDATE documents SET templatetype=?, templatefile=? WHERE type IN (1,6);',array($type,$template,'1','6'));
$DB->Execute('UPDATE documents SET templatetype=?, templatefile=? WHERE type=?;',array($type,$cnote_template,'3'));


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015010300', 'dbvex'));

$DB->CommitTrans();

?>
