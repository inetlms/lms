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
 *  $Id: v 1.00 2012/12/21 19:04:25 Sylwester Kondracki Exp $
 */

$akcja = NULL;
$dane = array();
$layout['pagetitle'] = 'Lista własnych urządzeń monitorowanych';

if (isset($_POST['monitownadd']) || isset($_POST['monitownedit']))
{
    if (isset($_POST['monitownadd'])) $dane = $_POST['monitownadd']; else $dane = $_POST['monitownedit'];
    if (empty($dane['nazwa'])) $error['nazwa'] = 'Nazwa hosta jest wymagana';
    if (empty($dane['ipaddr'])) $error['ipaddr'] = 'Adres hosta jest wymagany';
    
    if (!$error)
    {
	if (isset($_POST['monitownadd'])) 
	    $LMS->addOwnerMonit($dane);
	else
	    $LMS->UpdateOwnerMonit($dane);
	
	$SESSION->redirect('?m=monitownlist');
    }
}

if (isset($_GET['action']))
{
    switch ($_GET['action'])
    {
	case 'clear'		: $LMS->ClearStatMonit($_GET['id'],'owner'); break;
	case 'add'		: $akcja = 'add'; break;
	case 'setaccess'	: $LMS->SetMonitOwner($_GET['id'],$_GET['active']); break;
	case 'edit'		: $akcja='edit'; $SMARTY->assign('dane',$LMS->getownermonit($_GET['id'])); break;
	case 'deldev'		: $LMS->DeleteOwnerMonit($_GET['id']); break;
	case 'settesttype'	: $LMS->settesttypeowner($_GET['id'],$_GET['testtype']); break;
    }
}

$LMSdevlist = $LMS->GetOwnerMonitList();
$SMARTY->assign('error',$error);
$SMARTY->assign('akcja',$akcja);
$SMARTY->assign('devlist',$devlist);
$SMARTY->assign('monitdevlist',$LMSdevlist);
$SMARTY->display('monitownlist.html');

?>