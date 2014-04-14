<?php

/*
 *  iNET LMS
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


if (!$DB->GetOne("SELECT 1 FROM pg_tables WHERE tablename = ?",array('radippool'))) 
{
    $DB->Execute("
    CREATE SEQUENCE radippool_id_seq;
    
    CREATE TABLE radippool (
	id			DEFAULT netval('radippool_id_seq'::text) NOT NULL,
	pool_name		varchar(64) NOT NULL,
	FramedIPAddress		INET NOT NULL,
	NASIPAddress		VARCHAR(16) NOT NULL default '',
	pool_key		VARCHAR(64) NOT NULL default 0,
	CalledStationId		VARCHAR(64),
	CallingStationId	text NOT NULL default ''::text,
	expiry_time		TIMESTAMP(0) without time zone NOT NULL default 'now'::timestamp(0),
	username		text DEFAULT ''::text,
	PRIMARY KEY (id)
    );

    CREATE INDEX radippool_poolname_expire_idx ON radippool USING btree (pool_name, expiry_time);
    CREATE INDEX radippool_framedipaddress_idx ON radippool USING btree (framedipaddress);
    CREATE INDEX radippool_nasip_poolkey_ipaddress_idx ON radippool USING btree (nasipaddress, pool_key, framedipaddress);
    ");

}


$DB->Execute("DROP VIEW nas");
$DB->Execute("ALTER TABLE netdevices ADD server varchar(64) DEFAULT ''");
$DB->Execute("CREATE VIEW nas AS 
		SELECT n.id, inet_ntoa(n.ipaddr) AS nasname, d.shortname, d.nastype AS type,
		d.clients AS ports, d.secret, d.server, d.community, d.description 
		FROM nodes n 
		JOIN netdevices d ON (n.netdev = d.id) 
		WHERE n.nas = 1");



if (!$DB->GetOne("SELECT 1 FROM pg_tables WHERE tablename = ?",array('radacct'))) 
{
    $DB->Execute("
	CREATE SEQUENCE radacct_id_seq;
	CREATE TABLE radacct (
		RadAcctId		DEFAULT nextval('radacct_id_seq'::text) NOT NULL,
		AcctSessionId		VARCHAR(64) NOT NULL,
		AcctUniqueId		VARCHAR(32) NOT NULL,
		UserName		VARCHAR(253),
		GroupName		VARCHAR(253),
		Realm			VARCHAR(64),
		NASIPAddress		INET NOT NULL,
		NASPortId		VARCHAR(15),
		NASPortType		VARCHAR(32),
		AcctStartTime		TIMESTAMP with time zone,
		AcctStopTime		TIMESTAMP with time zone,
		AcctSessionTime		BIGINT,
		AcctAuthentic		VARCHAR(32),
		ConnectInfo_start	VARCHAR(50),
		ConnectInfo_stop	VARCHAR(50),
		AcctInputOctets		BIGINT,
		AcctOutputOctets	BIGINT,
		CalledStationId		VARCHAR(50),
		CallingStationId	VARCHAR(50),
		AcctTerminateCause	VARCHAR(32),
		ServiceType		VARCHAR(32),
		XAscendSessionSvrKey	VARCHAR(10),
		FramedProtocol		VARCHAR(32),
		FramedIPAddress		INET,
		AcctStartDelay		INTEGER,
		AcctStopDelay		INTEGER,
		PRIMARY KEY(id)
	);
	CREATE INDEX radacct_active_user_idx ON radacct (UserName, NASIPAddress, AcctSessionId) WHERE AcctStopTime IS NULL;
	CREATE INDEX radacct_start_user_idx ON radacct (AcctStartTime, UserName);
    ");

}


if (!$DB->GetOne("SELECT 1 FROM pg_tables WHERE tablename = ?",array('radcheck'))) 
{
    $DB->Execute("
	CREATE SEQUENCE radcheck_id_seq;
	CREATE TABLE radcheck (
		id		DEFAULT nextval('radcheck_id_seq'::text) NOT NULL,
		UserName	VARCHAR(64) NOT NULL DEFAULT '',
		Attribute	VARCHAR(64) NOT NULL DEFAULT '',
		op		CHAR(2) NOT NULL DEFAULT '==',
		Value		VARCHAR(253) NOT NULL DEFAULT '',
		PRIMARY KEY(id)
	);
	create index radcheck_UserName_idx on radcheck (UserName,Attribute);
    ");
}


if (!$DB->GetOne("SELECT 1 FROM pg_tables WHERE tablename = ?",array('radgroupcheck'))) 
{
    $DB->Execute("
	CREATE SEQUENCE radgroupcheck_id_seq;
	CREATE TABLE radgroupcheck (
		id		DEFAULT nextval('radgroupcheck_id_seq'::text) NOT NULL,
		GroupName	VARCHAR(64) NOT NULL DEFAULT '',
		Attribute	VARCHAR(64) NOT NULL DEFAULT '',
		op		CHAR(2) NOT NULL DEFAULT '==',
		Value		VARCHAR(253) NOT NULL DEFAULT '',
		PRIMARY KEY(id)
	);
	create index radgroupcheck_GroupName on radgroupcheck (GroupName,Attribute);
    ");
}

if (!$DB->GetOne("SELECT 1 FROM pg_tables WHERE tablename = ?",array('radgroupreply'))) 
{
    $DB->Execute("
	CREATE SEQUENCE radgroupreply_id_seq;
	CREATE TABLE radgroupreply (
		id		DEFAULT nextval('radgroupreply_id_seq'::text) NOT NULL,
		GroupName	VARCHAR(64) NOT NULL DEFAULT '',
		Attribute	VARCHAR(64) NOT NULL DEFAULT '',
		op		CHAR(2) NOT NULL DEFAULT '=',
		Value		VARCHAR(253) NOT NULL DEFAULT '',
		PRIMARY KEY(id)
	);
	create index radgroupreply_GroupName_idx on radgroupreply (GroupName,Attribute);
");
}


if (!$DB->GetOne("SELECT 1 FROM pg_tables WHERE tablename = ?",array('radreply'))) 
{
    $DB->Execute("
	CREATE SEQUENCE radreply_id_seq;
	CREATE TABLE radreply (
		id		DEFAULT nextval('radreply_id_seq'::text) NOT NULL,
		UserName	VARCHAR(64) NOT NULL DEFAULT '',
		Attribute	VARCHAR(64) NOT NULL DEFAULT '',
		op		CHAR(2) NOT NULL DEFAULT '=',
		Value		VARCHAR(253) NOT NULL DEFAULT '',
		PRIMARY KEY(id)
	);
	create index radreply_UserName_idx on radreply (UserName,Attribute);
    ");
}


if (!$DB->GetOne("SELECT 1 FROM pg_tables WHERE tablename = ?",array('radusergroup'))) 
{
    $DB->Execute("
	CREATE TABLE radusergroup (
		UserName	VARCHAR(64) NOT NULL DEFAULT '',
		GroupName	VARCHAR(64) NOT NULL DEFAULT '',
		priority	INTEGER NOT NULL DEFAULT 0
	);
	create index radusergroup_UserName_idx on radusergroup (UserName);
    ");
}


if (!$DB->GetOne("SELECT 1 FROM pg_tables WHERE tablename = ?",array('radpostauth'))) 
{
    $DB->Execute("
	CREATE SEQUENCE radpostauth_id_seq;
	CREATE TABLE radpostauth (
		id			DEFAULT nextval('radpostauth_id_seq'::text) NOT NULL,
		username		VARCHAR(253) NOT NULL,
		pass			VARCHAR(128),
		reply			VARCHAR(32),
		CalledStationId		VARCHAR(50),
		CallingStationId	VARCHAR(50),
		authdate		TIMESTAMP with time zone NOT NULL default 'now()',
		PRIMARY KEY(id)
	);
    ");
}


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014040900', 'dbvex'));
$DB->CommitTrans();

?>