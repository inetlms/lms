<?php

// iNET LMS 

$DB->BeginTrans();

$DB->Execute("ALTER TABLE invprojects ADD number VARCHAR( 30 ) NOT NULL DEFAULT '';");
$DB->Execute("ALTER TABLE invprojects ADD contract VARCHAR( 30 ) NOT NULL DEFAULT '';");
$DB->Execute("ALTER TABLE invprojects ADD title VARCHAR( 255 ) NOT NULL DEFAULT '';");
$DB->Execute("ALTER TABLE invprojects ADD program SMALLINT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD action SMALLINT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD division VARCHAR(255) NOT NULL DEFAULT '';");
$DB->Execute("ALTER TABLE invprojects ADD contractdate INTEGER NOT NULL DEFAULT '0' ;");
$DB->Execute("ALTER TABLE invprojects ADD fromdate INTEGER NOT NULL DEFAULT '0' ;");
$DB->Execute("ALTER TABLE invprojects ADD todate INT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD states VARCHAR(64) NOT NULL DEFAULT '' ;");
$DB->Execute("ALTER TABLE invprojects ADD scope VARCHAR( 255 ) NOT NULL DEFAULT '';");
$DB->Execute("ALTER TABLE invprojects ADD value NUMERIC( 20, 2 ) NOT NULL DEFAULT '0.00';");
$DB->Execute("ALTER TABLE invprojects ADD ownvalue NUMERIC( 20, 2 ) NOT NULL DEFAULT '0.00';");
$DB->Execute("ALTER TABLE invprojects ADD status SMALLINT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD eu SMALLINT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD description TEXT DEFAULT NULL ;");
$DB->Execute("ALTER TABLE invprojects ADD siis SMALLINT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD cdate INTEGER NOT NULL DEFAULT '0' ;");
$DB->Execute("ALTER TABLE invprojects ADD mdate INTEGER NOT NULL DEFAULT '0' ;");
$DB->Execute("ALTER TABLE invprojects ADD cuser INTEGER NOT NULL DEFAULT '0' ;");
$DB->Execute("ALTER TABLE invprojects ADD muser INTEGER NOT NULL DEFAULT '0' ;");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015022202', 'dbvex'));

$DB->CommitTrans();

?>
