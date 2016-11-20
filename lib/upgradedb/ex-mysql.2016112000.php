<?php

$DB->Execute("DROP VIEW IF EXISTS customersview;");
$DB->Execute("DROP VIEW IF EXISTS contractorview;");

$DB->AddConfig('phpui','pin_size','6');

$DB->Execute("ALTER TABLE customers CHANGE pin pin varchar(12) NOT NULL DEFAULT '0';");

$DB->Execute("
	CREATE VIEW customersview AS
	SELECT c.* FROM customers c
	WHERE NOT EXISTS (
	SELECT 1 FROM customerassignments a
	JOIN excludedgroups e ON (a.customergroupid = e.customergroupid)
	WHERE e.userid = lms_current_user() AND a.customerid = c.id) 
	AND c.type IN ('0','1');
");

$DB->Execute("
	CREATE VIEW contractorview AS
	SELECT c.* FROM customers c
	WHERE c.type = '2';
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2016112000', 'dbvex'));

?>