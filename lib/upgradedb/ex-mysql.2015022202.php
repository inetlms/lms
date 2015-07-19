<?php

// iNET LMS

$DB->BeginTrans();



if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','number'))) 
$DB->Execute("ALTER TABLE invprojects ADD number VARCHAR( 30 ) NOT NULL DEFAULT '' COMMENT 'numer projektu';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','contract'))) 
$DB->Execute("ALTER TABLE invprojects ADD contract VARCHAR( 30 ) NOT NULL DEFAULT '' COMMENT 'numer umowy' ;");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','title'))) 
$DB->Execute("ALTER TABLE invprojects ADD title VARCHAR( 255 ) NOT NULL DEFAULT '' COMMENT 'tytuł projektu';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','program'))) 
$DB->Execute("ALTER TABLE invprojects ADD program TINYINT(1) NOT NULL DEFAULT '0';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','action'))) 
$DB->Execute("ALTER TABLE invprojects ADD action TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'działanie';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','division'))) 
$DB->Execute("ALTER TABLE invprojects ADD division VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'firma';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','contractdate'))) 
$DB->Execute("ALTER TABLE invprojects ADD contractdate INT NOT NULL DEFAULT '0' COMMENT 'data podpisania umowy';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','fromdate'))) 
$DB->Execute("ALTER TABLE invprojects ADD fromdate INT NOT NULL DEFAULT '0' COMMENT 'data rozpoczęcia projektu';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','todate'))) 
$DB->Execute("ALTER TABLE invprojects ADD todate INT NOT NULL DEFAULT '0' COMMENT 'data zakończenia projektu';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','states'))) 
$DB->Execute("ALTER TABLE invprojects ADD states VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'województwo';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','scope'))) 
$DB->Execute("ALTER TABLE invprojects ADD scope VARCHAR( 255 ) NOT NULL COMMENT 'zakres';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','value'))) 
$DB->Execute("ALTER TABLE invprojects ADD value DECIMAL( 20, 2 ) NOT NULL DEFAULT '0.00' COMMENT 'wartość projektu';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','ownvalue'))) 
$DB->Execute("ALTER TABLE invprojects ADD ownvalue DECIMAL( 20, 2 ) NOT NULL DEFAULT '0.00' COMMENT 'środki własne';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','status'))) 
$DB->Execute("ALTER TABLE invprojects ADD status TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'status projektu';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','eu'))) 
$DB->Execute("ALTER TABLE invprojects ADD eu TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'czy projekt finansowany z funduszy europejskich';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','description'))) 
$DB->Execute("ALTER TABLE invprojects ADD description TEXT NULL DEFAULT NULL ;");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','siis'))) 
$DB->Execute("ALTER TABLE invprojects ADD siis TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'czy projekt uwzględnić w raporcie SIIS';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','cdate'))) 
$DB->Execute("ALTER TABLE invprojects ADD cdate INT NOT NULL DEFAULT '0';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','mdate'))) 
$DB->Execute("ALTER TABLE invprojects ADD mdate INT NOT NULL DEFAULT '0';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','cuser'))) 
$DB->Execute("ALTER TABLE invprojects ADD cuser INT NOT NULL DEFAULT '0';");

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'invprojects','muser'))) 
$DB->Execute("ALTER TABLE invprojects ADD muser INT NOT NULL DEFAULT '0';");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015022202', 'dbvex'));

$DB->CommitTrans();

?>
