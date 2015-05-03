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

$DB->Execute("ALTER TABLE documents CHANGE version version VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL DEFAULT '' COMMENT 'wersja dokumentu';");
$DB->Execute("ALTER TABLE documents CHANGE templatetype templatetype VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL DEFAULT '' COMMENT 'html lub pdf';");
$DB->Execute("ALTER TABLE documents CHANGE templatefile templatefile VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL DEFAULT '' COMMENT 'nazwa templatki lub pliku templates dla dokumentu';");
$DB->Execute("ALTER TABLE documents CHANGE urllogofile urllogofile VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL DEFAULT '' COMMENT 'adres do loga umieszczonego na fakturze';");


$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2015011000', 'dbvex'));

$DB->CommitTrans();

?>