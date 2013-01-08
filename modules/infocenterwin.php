<?php


/*
 * LMS iNET
 *
 *  (C) Copyright 2012 LMS iNET Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
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
 *  $Id: v 1.00 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */


$id = (int)$_GET['id'];
$layout['popup'] = true;

$topic = $DB->GetRow('SELECT i.*, 
	(SELECT login FROM users WHERE id=i.cuser) AS cname, 
	(SELECT login FROM users WHERE id=i.muser) AS mname,
	(SELECT login FROM users WHERE users.id = i.closeduser) AS closedname 
	FROM info_center i WHERE id =?',array($id));

$postlist = $DB->GetAll('SELECT p.*, (SELECT login FROM users WHERE id=p.cuser) AS cname, (SELECT login FROM users WHERE id=p.muser) AS mname FROM info_center_post p WHERE p.infoid = ? ORDER BY p.cdate DESC;',array($id));

$SMARTY->assign('topic',$topic);
$SMARTY->assign('userlist',$DB->GetAll('SELECT id, login FROM users'));
$SMARTY->assign('postlist',$postlist);
$SMARTY->display('infocenterwin.html');

?>