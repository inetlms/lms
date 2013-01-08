<?php

/*
 * LMS version Expanded
 *
 *  (C) Copyright 2012 LMS-EX Developers
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
CREATE TABLE contractorgroups (
  id int(11) NOT NULL auto_increment, 
  name varchar(255) NOT NULL DEFAULT '', 
  description text NOT NULL, 
  PRIMARY KEY (id), 
  UNIQUE KEY name (name)
) ENGINE=InnoDB;
");

$DB->Execute("
CREATE TABLE contractorassignments (
  id int(11) NOT NULL auto_increment, 
  contractorgroupid int(11) NOT NULL REFERENCES contractorgroups (id) ON DELETE CASCADE ON UPDATE CASCADE,
  customerid int(11) NOT NULL REFERENCES customers (id) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (id),
  UNIQUE KEY contractorassignment (contractorgroupid, customerid),
  INDEX customerid (customerid)
) ENGINE=InnoDB;
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2012120701', 'dbvex'));

$DB->CommitTrans();

?>