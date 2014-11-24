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

if (!$DB->GetOne('SELECT 1 FROM pg_tables WHERE tablename = ?;',array('tv_billingevent')))
{

    $DB->Execute("
	REATE TABLE tv_billingevent (
	id serial not null,
	customerid int NOT NULL,
	account_id int NOT NULL,
	be_selling_date date NOT NULL,
	be_desc text NOT NULL,
	be_vat numeric(5,2) NOT NULL,
	be_gross numeric(5,2) NOT NULL,
	group_id int NOT NULL,
	cust_number varchar(10) NOT NULL,
	package_id int NOT NULL,
	hash varchar(32) NOT NULL,
	beid int NOT NULL,
	be_b2b_netto numeric(5,2) DEFAULT NULL,
	docid int DEFAULT NULL,
	PRIMARY KEY (id)
    ); 
    ");

    $DB->Execute("CREATE UNIQUE INDEX hash ON tv_billingevent (hash);");
}

if (!$DB->GetOne("SELECT 1 FROM information_schema.columns WHERE table_name = ? AND column_name= ?;",array('customers','tv_cust_number')))
{
	$DB->Execute("DROP VIEW IF EXISTS customersview;");
	$DB->Execute("DROP VIEW IF EXISTS contractorview;");
	$DB->Execute("alter table customers add column tv_cust_number varchar(9) DEFAULT NULL; ");
//	$DB->Execute("grant select, insert, update, delete on tv_billingevent to lms;");
	
	$DB->Execute("
	    CREATE VIEW customersview AS
	    SELECT c.* FROM customers c
	    WHERE NOT EXISTS (
	    SELECT 1 FROM customerassignments a
	    JOIN excludedgroups e ON (a.customergroupid = e.customergroupid)
	    WHERE e.userid = lms_current_user() AND a.customerid = c.id) 
	    AND c.type IN ('0','1');
	");
	
	$DB->Execute("
	    CREATE VIEW contractorview AS
	    SELECT c.* FROM customers c
	    WHERE c.type = '2';
	");
}

$DB->addconfig('jambox','enabled','0');
$DB->addconfig('jambox','login','');
$DB->addconfig('jambox','haslo','');
$DB->addconfig('jambox','serwer','https://sms.sgtsa.pl/test/xmlrpc');
$DB->addconfig('jambox','cache','1');
$DB->addconfig('jambox','cache_lifetime','472000');
$DB->addconfig('jambox','numberplanid','');



$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014111700', 'dbvex'));
$DB->CommitTrans();

?>