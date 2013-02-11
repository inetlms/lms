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

$DB->Execute("ALTER TABLE documents ADD INDEX ( reference ); ");
$DB->addconfig('invoices','default_type_of_documents','');
$DB->addconfig('monit','live_ping','1');

$DB->Execute("DROP VIEW monit_vnodes;");

$DB->Execute("
CREATE VIEW monit_vnodes AS SELECT  m.id AS id, m.test_type, m.test_port, m.send_timeout, m.send_ptime, inet_ntoa(n.ipaddr) AS ipaddr, m.maxptime, 
COALESCE((SELECT 1 FROM nodes WHERE nodes.id = m.id AND nodes.netdev != 0 AND nodes.ownerid = 0),0) AS netdev, n.name 
FROM monitnodes m 
JOIN nodes n ON (n.id = m.id) 
WHERE m.active = 1 AND n.ipaddr !=0 ;
");
      

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2013011000', 'dbvex'));

$DB->CommitTrans();
?>