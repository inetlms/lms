<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2001-2012 LMS Developers
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 */


$DB->BeginTrans();

$DB->Execute("
    CREATE TABLE info_center ( 
	id int not null auto_increment,
	cid int default null,
	nid int default null,
	netdevid int default null,
	topic varchar(255) default '',
	description varchar(255) default null,
	cdate int default 0,
	mdate int default 0,
	cuser int default 0,
	muser int default 0,
	closed tinyint(1) default 0,
	closeddate int(11) default 0,
	closeduser int(11) default 0,
	closedinfo text default null,
	deleted tinyint(1) default 0,
	prio tinyint (1) default 1,
	primary key(id),
	index cid (cid),
	index cdate (cdate),
	index deleted (deleted)
) ENGINE=InnoDB;
");

$DB->Execute("
    create table info_center_post (
	id bigint not null auto_increment,
	infoid int not null default 0,
	post text default null,
	cdate int default 0,
	mdate int default 0,
	cuser int default 0,
	muser int default 0,
	primary key(id),
	index infoid (infoid)
) ENGINE=InnoDB;
");

$DB->Execute("INSERT INTO uiconfig (section,var,value) VALUES (?,?,?) ;",array('phpui','callcenter_pagelimit',50));

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2012122200', 'dbvex'));

$DB->CommitTrans();

?>