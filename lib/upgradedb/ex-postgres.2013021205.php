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
 * ex-postgres.2013021205.php
 */

$DB->BeginTrans();

$DB->addconfig('sms','smsapi_eco',1);
$DB->addconfig('sms','smsapi_fast',0,0);
$DB->addconfig('sms','smsapi_nounicode',1);
$DB->addconfig('sms','smsapi_normalize',1);
$DB->addconfig('sms','smsapi_max_parts',3);
$DB->addconfig('sms','smsapi_skip_foreign',1);

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013021205', 'dbvex'));
$DB->CommitTrans();
?>
