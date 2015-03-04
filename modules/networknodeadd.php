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

$layout['pagetitle'] = 'Nowy węzeł';

$networknodeinfo = array(
	
	    'type'			=> NODE_OWN,
	    'backbone_layer'		=> NIE,
	    'distribution_layer'	=> TAK,
	    'access_layer'		=> TAK,
	    'dc24' 			=> 1,
	    'ac230' 			=> 1,
	    'service_broadband' 	=> 1,
	    'teryt'			=> true,
	    
);

if (isset($_GET['create']) && isset($_GET['int']) && !empty($_GET['int']))
{
	
	
	$networknodeinfo = array(
	
	    'type'			=> NODE_OWN,
	    'backbone_layer'		=> NIE,
	    'distribution_layer'	=> TAK,
	    'access_layer'		=> TAK,
	    'int'			=> $_GET['int'],
	    
	);
	
	if ($int = $DB->GetROw('SELECT * FROM netdevices WHERE id = ? LIMIT 1;',array($_GET['int'])))
	{
	    $teryt = $LMS->getterytcode($int['location_city'],$int['location_street']);
//	    $networknodeinfo[''] = 
	    $networknodeinfo['int'] = $int['id'];
	    $networknodeinfo['name'] = 'WEZEL_'.strtoupper($int['name']);
	    $networknodeinfo['teryt'] = (!empty($int['location_city']) ? 1 : 0);
	    $networknodeinfo['states'] = $teryt['name_states'];
	    $networknodeinfo['districts'] = $teryt['name_districts'];
	    $networknodeinfo['boroughs'] = $teryt['name_boroughs'];
	    $networknodeinfo['city'] = $teryt['name_city'];
	    $networknodeinfo['street'] = $teryt['name_street'];
	    $networknodeinfo['location_city'] = $int['location_city'];
	    $networknodeinfo['location_street'] = $int['location_street'];
	    $networknodeinfo['location_house'] = $int['location_house'];
	    $networknodeinfo['location_flat'] = $int['location_flat'];
	    $networknodeinfo['location'] = $teryt['name_city'].' '.$teryt['name_street'].' '.$int['location_house'].($int['location_flat'] ? '/'.$int['location_flat'] : '');
	    $networknodeinfo['longitude'] = $int['longitude'];
	    $networknodeinfo['latitude'] = $int['latitude'];
	    $networknodeinfo['dc24'] = 1;
	    $networknodeinfo['service_broadband'] = 1;
	}
	
	
	
}

if (isset($_POST['name']))
{
    $networknode = $_POST['networknode'];
    $networknode['name'] = $_POST['name'];
    $networknode['teryt'] = $_POST['teryt'] ? true : false;
    if (!$networknode['teryt'] && empty($networknode['collocationid'])) $networknode['location_city'] = $networknode['location_street'] = NULL;
    
    $idn = $LMS->Addnetworknode($networknode);
    if (isset($networknode['int']) && !empty($networknode['int'])) {
	$LMS->add_interface_for_networknode($idn,$networknode['int']);
    }
    $SESSION->redirect('?m=networknodeinfo&idn='.$idn);
}

$SMARTY->assign('networknodeinfo',$networknodeinfo);
$SMARTY->assign('actions','add');
$SMARTY->assign('projectlist',$DB->getAll('SELECT id,name FROM invprojects WHERE type = 0 ORDER BY name ASC;'));
$SMARTY->display('networknodeedit.html');
?>
