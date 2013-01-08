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


$layout['popup'] = true;
$province = ($_POST['province'] ? $_POST['province'] : NULL);
$county = ($_POST['county'] ? $_POST['county'] : NULL);
$borough =($_POST['borough'] ? $_POST['borough'] : NULL);
$list['borough'] = NULL;
$list['county'] = NULL;
$idlocal = NULL;
$nazwa = array();
if ( !is_null($province) ) {
    $list['county'] = $HIPERUS->GetListCountyByProvince($province);
    $nazwa['province'] = $HIPERUS->GetNameProvince($province);
}

if ( !is_null($province) && !is_null($county)) {
    $list['borough'] = $HIPERUS->GetListBoroughByCounty($county);
    $nazwa['county'] = $HIPERUS->GetNameCounty($county);
}

if ( !is_null($province) && !is_null($county) && !is_null($borough) ) {
    $idlocal = $HIPERUS->GetIDLocationTerminal($province,$county,$borough);
    $nazwa['borough'] = $HIPERUS->GetNameBorough($borough);
}

$list['province'] = $HIPERUS->GetListProvince();
$SMARTY->assign('nazwa',$nazwa);
$SMARTY->assign('list',$list);
$SMARTY->assign('idlocal',$idlocal);
$SMARTY->assign('province',$province);
$SMARTY->assign('county',$county);
$SMARTY->assign('borough',$borough);

$SMARTY->display('hv_searchterminallocation.html');

?>