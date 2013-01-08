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
 *  $Id: v 1.00 2012/12/22 01:53:35 Sylwester Kondracki Exp $
 */

include(MODULES_DIR.'/infocenter.inc.php');

$layout['pagetitle'] = 'Call Center: ';

if (isset($_GET['callback'])) $callback='&callback'; else $callback='';
$SMARTY->assign('callback',$callback);

if ($action === 'newtopic') $layout['pagetitle'] .= 'nowe zdarzenie ';

if (isset($_GET['cid'])) {
    $layout['pagetitle'] .= 'klient: <a href="?m=customerinfo&id='.$cid.'">'.$cusname.'</a>';
}

if ($action==='delpost' && $_GET['is_sure']=='1') {
    $LMS->DelPost($_GET['postid']);
    $SESSION->redirect('?m=infocenter&tid='.$tid.'&backto='.$backto.($cid ? '&cid='.$cid : '').($nid ? '&nid='.$nid : '').($netdevid ? '&netdevid='.$netdevid : ''));
}

if ($action==='deltopic' && $_GET['is_sure']=='1') {
    $LMS->DelTopic($tid);
    $SESSION->redirect('?m='.$backto.($cid ? '&id='.$cid : '').($nid ? '&id='.$nid : '').($netdevid ? '&id='.$netdevid : ''));
}

if ($action==='editpost'){
    $SMARTY->assign('post',$DB->GetRow('SELECT * FROM info_center_post WHERE id=?',array($_GET['postid'])));
}

if ($action==='reopen') {
    $DB->Execute('UPDATE info_center SET closed = ? WHERE id = ? ;',array(0,$tid));
    if (SYSLOG) {
	$tmp = $DB->getrow('SELECT cid, topic FROM info_center WHERE id=? '.$DB->limit('1').' ;',array($tid));
	$cusname=$LMS->getcustomername($tmp['cid']);
	addlogs('Otwarto ponownie zgłoszenie '.$tmp['topic'].' dla klienta '.$cusname,'e=up;m=cc;c='.$tmp['cid'].';id='.$tid.';');
    }
    $SESSION->redirect('?m='.$backto.($tid ? '&tid='.$tid : '').($cid ? '&cid='.$cid : '').($nid ? '&nid='.$nid : '').($netdevid ? '&netdevid='.$netdevid : ''));
}

if (isset($_POST['infocenteraddpost']))
{
    $dane['cid'] = $cid;
    $dane['tid'] = $tid;
    $dane['post'] = $_POST['post'];
    $LMS->AddNewPostToTopic($dane);

    if (!$callback)
	$SESSION->redirect('?m=infocenter&tid='.$tid.'&backto='.$backto.($cid ? '&cid='.$cid : '').($nid ? '&nid='.$nid : '').($netdevid ? '&netdevid='.$netdevid : ''));
    else
	$SESSION->redirect('?m='.$backto.($cid ? '&id='.$cid : '').($nid ? '&id='.$nid : '').($netdevid ? '&id='.$netdevid : ''));
}

if (isset($_POST['infocentereditpost']))
{
    $dane['cid'] = $cid;
    $dane['tid'] = $tid;
    $dane['post'] = $_POST['post'];
    $dane['postid'] = $_POST['postid'];
    $LMS->UpdatePost($dane);
    $SESSION->redirect('?m=infocenter&tid='.$tid.'&backto='.$backto.($cid ? '&cid='.$cid : '').($nid ? '&nid='.$nid : '').($netdevid ? '&netdevid='.$netdevid : ''));
}

if (isset($_POST['infocenternew']))
{
    $dane['cid'] = $cid;
    $dane['topic'] = $_POST['topic'];
    $dane['description'] = $_POST['description'];
    $dane['post'] = $_POST['post'];
    $dane['prio'] = $_POST['prio'];
    $LMS->addNewTopic($dane);
    $SESSION->redirect('?m='.$backto.($cid ? '&cid='.$cid.'&id='.$cid : '').($nid ? '&id='.$nid : '').($netdevid ? '&id='.$netdevid : ''));
}

if (isset($_POST['infocenteredit']))
{
    $dane['cid'] = $cid;
    $dane['topic'] = $_POST['topic'];
    $dane['description'] = $_POST['description'];
    $dane['post'] = $_POST['post'];
    $dane['prio'] = $_POST['prio'];
    $dane['closed'] = $_POST['closed'];
    $dane['closedinfo'] = $_POST['closedinfo'];
    $dane['tid'] = $tid;
    $LMS->UpdateTopic($dane);
    if (!$callback)
	$SESSION->redirect('?m=infocenter&tid='.$tid.'&backto='.$backto.($cid ? '&cid='.$cid : '').($nid ? '&nid='.$nid : '').($netdevid ? '&netdevid='.$netdevid : ''));
    else
	$SESSION->redirect('?m='.$backto.($cid ? '&id='.$cid : '').($nid ? '&id='.$nid : '').($netdevid ? '&id='.$netdevid : ''));
}

$SMARTY->display('infocenter.html');

?>