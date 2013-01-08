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

$DB->Execute("DROP VIEW IF EXISTS customersview;");
$DB->Execute("DROP VIEW IF EXISTS contractorview;");

$DB->Execute("ALTER TABLE `customers` ADD `invoice_name` VARCHAR( 255 ) NULL DEFAULT NULL ;");
$DB->Execute("ALTER TABLE `customers` ADD `invoice_address` VARCHAR( 255 ) NULL DEFAULT NULL;");
$DB->Execute("ALTER TABLE `customers` ADD `invoice_zip` VARCHAR( 10 ) NULL DEFAULT NULL;");
$DB->Execute("ALTER TABLE `customers` ADD `invoice_city` VARCHAR( 32 ) NULL DEFAULT NULL;");
$DB->Execute("ALTER TABLE `customers` ADD `invoice_countryid` INT ( 11 ) NULL DEFAULT NULL;");
$DB->Execute("ALTER TABLE `customers` ADD `invoice_ten` VARCHAR( 16 ) NULL DEFAULT NULL;");

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

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2012120601', 'dbvex'));

$DB->CommitTrans();

?>
