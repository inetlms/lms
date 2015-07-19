<?php

if (!$DB->GetOne("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? ;",
array($DB->_dbname,'netdevicemodels','active'))) 
$DB->Execute("ALTER TABLE netdevicemodels ADD active TINYINT(1) NOT NULL DEFAULT '1';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015021801', 'dbvex'));

?>