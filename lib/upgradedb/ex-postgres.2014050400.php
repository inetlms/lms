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
 * VoIP nettelekom
 */



$DB->BeginTrans();

$DB->Execute("CREATE SEQUENCE billing_details_id_seq;");
$DB->Execute("
CREATE TABLE billing_details (
  id INTEGER DEFAULT nextval('billing_details_id_seq'::text) NOT NULL,
  documents_id integer NOT NULL,
  name varchar(40) NOT NULL,
  value numeric(9,2) NOT NULL,
  PRIMARY KEY  (id));
");
$DB->Execute("CREATE INDEX billing_details_documents_id ON billing_details (documents_id);");


$DB->Execute("CREATE SEQUENCE v_fax_id_seq;");
$DB->Execute("
CREATE TABLE v_fax (
  id integer default nextval('v_fax_id_seq'::text) NOT NULL,
  nr_from varchar(10) NOT NULL,
  nr_to varchar(10) NOT NULL,
  data integer NOT NULL,
  customerid integer NOT NULL,
  uniqueid varchar(8) NOT NULL,
  filename varchar(30) NOT NULL,
  PRIMARY KEY  (id));
");


$DB->Execute("CREATE SEQUENCE v_netlist_id_seq;");
$DB->Execute("
CREATE TABLE v_netlist (
  id integer default nextval('v_netlist_id_seq'::text) not null,
  name varchar(30) NOT NULL,
  start varchar(10) NOT NULL,
  count integer NOT NULL,
  PRIMARY KEY  (id));
");


$DB->Execute("
CREATE TABLE v_exportedusers (
  lmsid integer NOT NULL,
  PRIMARY KEY (lmsid));
");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014050400', 'dbvex'));
$DB->CommitTrans();

?>