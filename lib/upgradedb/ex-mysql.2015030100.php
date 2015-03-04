<?php

// iNET LMS

$DB->BeginTrans();

$DB->Execute("
    CREATE TABLE networknodegroups (
	id INT not null auto_increment,
	name varchar(128) default null,
	description text default null,
	primary key(id),
	UNIQUE (name)
) ENGINE=InnoDB;
");

$DB->Execute("
    CREATE TABLE networknodeassignments (
    id int not null auto_increment,
    networknodeid int NOT NULL,
    networknodegroupid int NOT NULL,
    primary key (id),
    UNIQUE (networknodeid, networknodegroupid),
    FOREIGN KEY (networknodeid) REFERENCES networknode(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (networknodegroupid) REFERENCES networknodegroups(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
");

$DB->Execute("
    CREATE TABLE netdevicesgroups (
	id INT not null auto_increment,
	name varchar(128) default null,
	description text default null,
	PRIMARY KEY(id),
	UNIQUE (name)
) ENGINE=InnoDB;
");

$DB->Execute("
    CREATE TABLE netdevicesassignments (
    id int not null auto_increment,
    netdevicesid int NOT NULL,
    netdevicesgroupid int NOT NULL,
    primary key (id),
    UNIQUE (netdevicesid, netdevicesgroupid),
    FOREIGN KEY (netdevicesid) REFERENCES netdevices(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (netdevicesgroupid) REFERENCES netdevicesgroups(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015030100', 'dbvex'));

$DB->CommitTrans();

?>
