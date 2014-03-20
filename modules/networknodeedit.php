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
 *  $Id: v 1.00 Sylwester Kondracki Exp $
 */

include('networknode.inc.php');

$layout['pagetitle'] = 'Edycja węzła: '.$networknodeinfo['name'];

if (isset($_GET['t'])) {
    $target = $_GET['t'];
    if ($_GET['t'] == 'collocationinfo') 
	$target .= '&idc='.$_GET['idc'];
    elseif ($_GET['t'] == 'networknodeinfo') 
	$target .= '&idn='.$_GET['idn'];
    else $target = NULL;
}
else $target = NULL;


$SMARTY->assign('target',$target);


if (isset($_POST['name']))
{
    $networknode = $_POST['networknode'];
    $networknode['name'] = $_POST['name'];
    $networknode['teryt'] = $_POST['teryt'] ? true : false;
    $networknode['networknodeid'] = $_POST['networknodeid'];
    if (!$networknode['teryt'] && empty($networknode['collocationid'])) $networknode['location_city'] = $networknode['location_street'] = NULL;
    $target = $_POST['target'];
    $id = intval($networknode['networknodeid']);
    $LMS->UpdateNetworkNode($networknode);
    
	$loc = $DB->GetRow('SELECT states, districts, boroughs, city, street, zip, location_city, location_street, location_house, location_flat, cadastral_parcel, longitude, latitude,
			    backbone_layer, distribution_layer, access_layer, sharing 
			    FROM networknode WHERE id = ? '.$DB->Limit(1).' ;',array($id));
	
	$location = $loc['city'];
	
	if (!empty($loc['location_street']) && $loc['location_street'] != '' && $loc['location_street'] != '99999' && $loc['location_street'] != '99998')
	    $location .= ', '.$loc['street'];
	
	$location .= ' '.$loc['location_house'];
	
	if (!empty($loc['location_flat']) && $loc['location_flat'] != '')
	    $location .= '/'.$loc['location_flat'];
	
	$DB->Execute('UPDATE netdevices SET 
		    location = ?, location_city = ?, location_street = ?, location_house = ?, location_flat = ?, longitude = ?, latitude = ? ,
		    backbone_layer = ?, distribution_layer = ?, access_layer = ?, sharing = ?
		    WHERE networknodeid = ? ;',
		    array($location, $loc['location_city'], $loc['location_street'], $loc['location_house'], $loc['location_flat'], $loc['longitude'], $loc['latitude'],
			$loc['backbone_layer'], $loc['distribution_layer'], $loc['access_layer'], $loc['sharing'], $id));
    
    if ($target)
	$SESSION->redirect('?m='.$target);
    else
	$SESSION->redirect('?m=networknodelist');
}

$SMARTY->assign('actions','edit');
$SMARTY->display('networknodeedit.html');
?>