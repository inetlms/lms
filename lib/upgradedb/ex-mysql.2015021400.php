<?php

$DB->Execute("
create table if not exists cache (
id integer not null default 0,
md5 varchar(50) not null default '',
action char(50) not null default '',
value longtext not null default '',
primary key(id,action));
");
$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015021400', 'dbvex'));

?>