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
 *  $Id: 2013/02/07 22:01:35 Sylwester Kondracki Exp $
 */

$layout['pagetitle'] = 'Szablony nowych wiadomości';
$akcja = (isset($_POST['akcja']) ? $_POST['akcja'] : 'none');

if (isset($_POST['messtemp']))
    $messtemp = $_POST['messtemp'];
else
    $messtemp=array();
//echo "<pre>"; print_r($messtemp); echo "</pre>";
if (isset($_GET['addnew']))
{
    $LMS->AddMessageTemplate($messtemp);
    $messtemp=array();
    $akcja='none';
}
if (isset($_GET['save']))
{
    $LMS->UpdateMessagetemplate($messtemp);
    $messtemp=array();
    $akcja='none';
}

if (isset($_GET['d']) && isset($_GET['id']))
{
    $id = (int)$_GET['id'];
    $LMS->DeleteMessageTemplate($id);
}

if (isset($_GET['e']) && isset($_GET['id']))
{
    $id = (int)$_GET['id'];
    $akcja = 'edycja';
    if ($dane = $LMS->GetMessageTemplate($id))
    {
	$messtemp = $dane;
	unset($dane);
    }
}
    
$SMARTY->assign('messtemp',$messtemp);
$SMARTY->assign('akcja',$akcja);
$SMARTY->assign('messlist',$LMS->getlistmessagestemplate());
$SMARTY->display('messagetemplate.html');
?>