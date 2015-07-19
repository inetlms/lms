<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012-2015 LMS Developers
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
 *  Sylwester Kondracki
 *  sylwester.kondracki@gmail.com
 *  gadu-gadu : 6164816
*/

$cid = $nid = $netdevid = $action = NULL;

if (isset($_GET['cid'])) {
    $cid = intval($_GET['cid']);
    $SMARTY->assign('cid',$cid);
    $cusname  = $LMS->getcustomername($cid);
    $SMARTY->assign('customername',$cusname);

}
elseif (isset($_GET['nid'])) {
    $nid = intval($_GET['nid']);
    $SMARTY->assign('nid',$nid);
}
elseif (isset($_GET['netdevid'])) {
    $netdevid = intval($_GET['netdevid']);
    $SMARTY->assign('netdevid',$netdevid);
}

if (isset($_GET['action'])) $action = $_GET['action'];
if (isset($_GET['tid'])) $tid = intval($_GET['tid']); else $tid = NULL; // topic td
if (isset($_GET['pid'])) $pid = intval($_GET['pid']); else $pid = NULL; // post id
$SMARTY->assign('action',$action);
$SMARTY->assign('tid',$tid);
$SMARTY->assign('pid',$pid);

$audiopath = (! $LMS->CONFIG['phpui']['callcenter_audiopath'] ? '/' : $LMS->CONFIG['phpui']['callcenter_audiopath']);
$SMARTY->assign('audiopath',$audiopath);

if (!is_null($tid) && !empty($tid)) {
    $topic = $DB->GetRow('SELECT i.*, 
	(SELECT login FROM users WHERE id=i.cuser) AS cname, 
	(SELECT login FROM users WHERE id=i.muser) AS mname, 
	(SELECT login FROM users WHERE id=i.closeduser) AS closedname 
	FROM info_center i WHERE id =?',array($tid));
    $order = (isset($_GET['asc']) ? 'ASC' : 'DESC');
    $postlist = $DB->GetAll('SELECT p.*, 
		(SELECT login FROM users WHERE id=p.cuser) AS cname, 
		(SELECT login FROM users WHERE id=p.muser) AS mname 
		FROM info_center_post p 
		WHERE p.infoid = ? 
		ORDER BY p.cdate '.$order.';',
		array($tid));

    $SMARTY->assign('topic',$topic);
    $SMARTY->assign('postlist',$postlist);
}

$backto = $_GET['backto'];
$SMARTY->assign('backto',$backto);

?>
