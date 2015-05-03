<?php

$DB->Execute("
create table cache (
id integer not null default 0,
md5 varchar(50) not null default '',
action varchar(50) not null default '',
value text not null default '',
primary key(id,action));
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015021400', 'dbvex'));

?>