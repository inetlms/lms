<?php

$DB->Execute('DELETE FROM uiconfig WHERE section=? AND var=?;',array('phpui','short_pagescroller'));
$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015021200', 'dbvex'));

?>