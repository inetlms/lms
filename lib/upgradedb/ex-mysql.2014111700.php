<?php


$DB->BeginTrans();

$DB->Execute("
CREATE TABLE IF NOT EXISTS tv_billingevent (
  id int(11) NOT NULL AUTO_INCREMENT,
  customerid int(11) NOT NULL,
  account_id int(11) NOT NULL,
  be_selling_date date NOT NULL,
  be_desc text NOT NULL,
  be_vat float(5,2) NOT NULL,
  be_gross float(5,2) NOT NULL,
  group_id int(11) NOT NULL,
  cust_number varchar(10) NOT NULL,
  package_id int(11) NOT NULL,
  hash varchar(32) NOT NULL,
  beid int(11) NOT NULL,
  be_b2b_netto float(5,2) DEFAULT NULL,
  docid int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY hash (hash)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ; 
");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",array($DB->_dbname,'customers','tv_cust_number')))
{
    
    $DB->Execute("DROP VIEW IF EXISTS customersview;");
    
    $DB->Execute("DROP VIEW IF EXISTS contractorview;");
    
    $DB->Execute("alter table customers add column tv_cust_number varchar(9) COLLATE utf8_polish_ci DEFAULT NULL; ");
    
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

}

$DB->addconfig('jambox','enabled','0');
$DB->addconfig('jambox','login','');
$DB->addconfig('jambox','haslo','');
$DB->addconfig('jambox','serwer','https://sms.sgtsa.pl/test/xmlrpc');
$DB->addconfig('jambox','cache','1');
$DB->addconfig('jambox','cache_lifetime','472000');
$DB->addconfig('jambox','numberplanid','1');

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014111700', 'dbvex'));

$DB->CommitTrans();

?>
