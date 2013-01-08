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

$DB->Execute("CREATE SEQUENCE info_center_id_seq;");
$DB->Execute("
    CREATE TABLE info_center ( 
	id integer default nextval('info_center_id_seq'::text) NOT NULL,
	cid integer default null,
	nid integer default null,
	netdevid integer default null,
	topic varchar(255) default '',
	description varchar(255) default null,
	cdate integer default 0,
	mdate integer default 0,
	cuser integer default 0,
	muser integer default 0,
	closed smallint default 0,
	closeddate integer default 0,
	closedinfo text default null,
	closeduser integer default 0,
	deleted smallint default 0,
	prio smallint default 1,
	primary key(id)
	);
");

$DB->Execute("CREATE INDEX info_center_cid_idx ON info_center (cid) ;");
$DB->Execute("CREATE INDEX info_center_cdate_idx ON info_center (cdate) ;");
$DB->Execute("CREATE INDEX info_center_deleted_idx ON info_center (deleted) ;");

$DB->Execute("CREATE SEQUENCE info_center_post_id_seq;");
$DB->Execute("
    create table info_center_post (
	id bigint default nextval('info_center_post_id_seq'::text) not null,
	infoid integer not null references info_center (id) on delete cascade on update cascade,
	post text default null,
	cdate integer default 0,
	mdate integer default 0,
	cuser integer default 0,
	muser integer default 0,
	primary key(id)
	);
");

$DB->Execute("CREATE INDEX info_center_post_infoid_idx ON info_center_post (infoid) ;");

$DB->Execute("INSERT INTO uiconfig (section,var,value) VALUES (?,?,?) ;",array('phpui','callcenter_pagelimit',50));

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2012122200', 'dbvex'));

$DB->CommitTrans();

?>