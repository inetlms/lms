<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("ALTER TABLE invprojects ADD number VARCHAR( 30 ) NOT NULL DEFAULT '' COMMENT 'numer projektu';");
$DB->Execute("ALTER TABLE invprojects ADD contract VARCHAR( 30 ) NOT NULL DEFAULT '' COMMENT 'numer umowy' ;");
$DB->Execute("ALTER TABLE invprojects ADD title VARCHAR( 255 ) NOT NULL DEFAULT '' COMMENT 'tytuł projektu';");
$DB->Execute("ALTER TABLE invprojects ADD program TINYINT(1) NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD action TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'działanie';");
$DB->Execute("ALTER TABLE invprojects ADD division VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'firma';");
$DB->Execute("ALTER TABLE invprojects ADD contractdate INT NOT NULL DEFAULT '0' COMMENT 'data podpisania umowy';");
$DB->Execute("ALTER TABLE invprojects ADD fromdate INT NOT NULL DEFAULT '0' COMMENT 'data rozpoczęcia projektu';");
$DB->Execute("ALTER TABLE invprojects ADD todate INT NOT NULL DEFAULT '0' COMMENT 'data zakończenia projektu';");
$DB->Execute("ALTER TABLE invprojects ADD states VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'województwo';");
$DB->Execute("ALTER TABLE invprojects ADD scope VARCHAR( 255 ) NOT NULL COMMENT 'zakres';");
$DB->Execute("ALTER TABLE invprojects ADD value DECIMAL( 20, 2 ) NOT NULL DEFAULT '0.00' COMMENT 'wartość projektu';");
$DB->Execute("ALTER TABLE invprojects ADD ownvalue DECIMAL( 20, 2 ) NOT NULL DEFAULT '0.00' COMMENT 'środki własne';");
$DB->Execute("ALTER TABLE invprojects ADD status TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'status projektu';");
$DB->Execute("ALTER TABLE invprojects ADD eu TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'czy projekt finansowany z funduszy europejskich';");
$DB->Execute("ALTER TABLE invprojects ADD description TEXT NULL DEFAULT NULL ;");
$DB->Execute("ALTER TABLE invprojects ADD siis TINYINT( 1 ) NOT NULL DEFAULT '0' COMMENT 'czy projekt uwzględnić w raporcie SIIS';");
$DB->Execute("ALTER TABLE invprojects ADD cdate INT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD mdate INT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD cuser INT NOT NULL DEFAULT '0';");
$DB->Execute("ALTER TABLE invprojects ADD muser INT NOT NULL DEFAULT '0';");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015022202', 'dbvex'));

$DB->CommitTrans();

?>
