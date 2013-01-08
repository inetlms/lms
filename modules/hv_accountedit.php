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


$dane = NULL;

if ( isset($_POST['dane']) ) $dane = $_POST['dane'];

$id = (int)( isset($_GET['id']) ? $_GET['id'] : $dane['id'] );
$out = (isset($_GET['out']) ? $_GET['out'] : 'lista');

if ( !isset($_GET['id']) && !isset($_POST['dane']) ) header("Location: ?m=hv_accountlist");

if ( isset($_GET['save']) && !is_null($dane) && is_array($dane) ) { 
    if ($dane['invoice']=='0' || $dane['invoice']=='2') $dane['issue_invoice'] = 'f'; else $dane['issue_invoice'] = 't';
    if ($HIPERUS->UpdateCustomer($dane)) {
        if ($out=='lista') header("Location: ?m=hv_accountlist");
        if ($out=='panel') header("Location: ?m=hv_accountinfo&id=".$dane['id']);
        $SMARTY->assign('blad',false);
    } else $SMARTY->assign('blad',true);
}

$dane = $HIPERUS->GetCustomer($id);
$layout['pagetitle'] = 'Edycja konta VoIP - '.$dane['name'];

$SMARTY->assign('wlr',get_conf('hiperus_c5.wlr'));
$SMARTY->assign('voip_to_lms',get_conf('hiperus_c5.force_relationship'));
$SMARTY->assign('out',$out);
$SMARTY->assign('dane',$dane);
$SMARTY->assign('cuslms',$HIPERUS->GetCustomerLMSMinList());
$SMARTY->assign('price',$HIPERUS->GetPriceList());
$SMARTY->display('hv_accountedit.html');

?>