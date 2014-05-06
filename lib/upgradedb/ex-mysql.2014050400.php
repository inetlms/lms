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

$DB->Execute("
CREATE TABLE IF NOT EXISTS billing_details (
  id int(10) unsigned NOT NULL auto_increment,
  documents_id int(10) unsigned NOT NULL,
  name varchar(40) NOT NULL,
  value decimal(9,2) unsigned NOT NULL,
  PRIMARY KEY (id),
  KEY documents_id (documents_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");

$DB->Execute("
CREATE TABLE IF NOT EXISTS v_fax (
  id int(10) unsigned NOT NULL auto_increment,
  nr_from char(10) NOT NULL,
  nr_to char(10) NOT NULL,
  data int(10) unsigned NOT NULL,
  customerid int(10) unsigned NOT NULL,
  uniqueid char(8) NOT NULL,
  filename varchar(30) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");

$DB->Execute("
CREATE TABLE IF NOT EXISTS v_netlist (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(30) NOT NULL,
  start char(10) NOT NULL,
  count int(10) unsigned NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");

$DB->Execute("
CREATE TABLE IF NOT EXISTS v_exportedusers (
  lmsid int(10) unsigned NOT NULL,
  PRIMARY KEY (lmsid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014050400', 'dbvex'));
$DB->CommitTrans();

?>