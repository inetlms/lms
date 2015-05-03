<?php

$DB->Execute("ALTER TABLE netdevicemodels ADD active SMALLINT NOT NULL DEFAULT '1';");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015021801', 'dbvex'));

?>