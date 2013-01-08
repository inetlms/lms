<?php

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