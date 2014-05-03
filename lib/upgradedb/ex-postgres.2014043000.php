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


$DB->Execute('ALTER TABLE netdevices ADD devtype SMALLINT DEFAULT 1 ;'); // 0-pasywne 1-aktywne
$DB->Execute('ALTER TABLE netdevices ADD managed SMALLINT DEFAULT 1 ;'); // czy urządzenie jest zarządzalne
$DB->Execute('ALTER TABLE netdevices ADD sharing SMALLINT DEFAULT 0 ;'); // czy są udostępniane porty (interfejsy) dla innych ISP
$DB->Execute('ALTER TABLE netdevices ADD modular SMALLINT DEFAULT 0 ;'); // czy urz. ma budowę modułową, 
$DB->Execute('ALTER TABLE netdevices ADD backbone_layer SMALLINT DEFAULT 0 ;');	// warstwa szkieletowa
$DB->Execute('ALTER TABLE netdevices ADD distribution_layer SMALLINT DEFAULT 1 ;');	// warstwa dystrybucyjna
$DB->Execute('ALTER TABLE netdevices ADD access_layer SMALLINT DEFAULT 1 ;');	// warstwa dostępowa
$DB->Execute('ALTER TABLE netdevices ADD typeofdevice INTEGER DEFAULT 0;');	// rodzaj urządzenia


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014043000', 'dbvex'));
$DB->CommitTrans();

?>