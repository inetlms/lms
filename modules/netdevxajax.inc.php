<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2012 LMS Developers
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
 *  $Id: netdevxajax.inc.php,v 1.1 2012/04/11 23:12:01 chilek Exp $
 */

function getManagementUrls($netdevid) {
	global $SMARTY, $DB;

	$result = new xajaxResponse();
	$mgmurls = NULL;
	$mgmurls = $DB->GetAll('SELECT id, url, comment FROM managementurls WHERE netdevid = ? ORDER BY id', array($netdevid));
	$SMARTY->assign('mgmurls', $mgmurls);
	$mgmurllist = $SMARTY->fetch('managementurllist.html');

	$result->assign('managementurltable', 'innerHTML', $mgmurllist);

	return $result;
}

function addManagementUrl($netdevid, $params) {
	global $DB;

	$result = new xajaxResponse();

	if (empty($params['url']))
		return $result;

	if (!preg_match('/^[[:alnum:]]+:\/\/.+/i', $params['url']))
		$params['url'] = 'http://' . $params['url'];

	$DB->Execute('INSERT INTO managementurls (netdevid, url, comment) VALUES (?, ?, ?)', array($netdevid, $params['url'], $params['comment']));
	$result->call('xajax_getManagementUrls', $netdevid);
	$result->assign('managementurladdlink', 'disabled', false);

	return $result;
}

function delManagementUrl($netdevid, $id) {
	global $DB;

	$result = new xajaxResponse();
	$DB->Execute('DELETE FROM managementurls WHERE id = ?', array($id));
	$result->call('xajax_getManagementUrls', $netdevid);
	$result->assign('managementurltable', 'disabled', false);

	return $result;
}

function update_location_interface($networknodeid) {
	global $DB;
	$obj = new xajaxResponse();
	
	if ($networknodeid && ($dane=$DB->GetRow('SELECT city,street,zip,location_city,location_street,location_house,location_flat,longitude,latitude FROM networknode WHERE id = ?;',array($networknodeid)))) {
	
		$adres = '';
		$adres .= $dane['zip'].' '.$dane['city'].', ';
		$adres .= $dane['street'].' '.$dane['location_house'];
		if ($dane['location_flat'])
		    $adres .= '/'.$dane['location_flat'];
		
		if ($dane['location_city'] && $dane['location_street']) $teryt = 'true'; else $teryt = 'false';
		
		$obj->assign('location','value',$adres);
		$obj->assign('location_city','value',$dane['location_city']);
		$obj->assign('location_street','value',$dane['location_street']);
		$obj->assign('location_house','value',$dane['location_house']);
		$obj->assign('location_flat','value',$dane['location_flat']);
		$obj->script("document.getElementById('teryt').checked = ".$teryt."");
		$obj->assign('longitude','value',$dane['longitude']);
		$obj->assign('latitude','value',$dane['latitude']);
		$obj->script("document.getElementById('location').readOnly = true;");
		$obj->script("document.getElementById('searchteryt').style.display='none';");
		$obj->script("document.getElementById('checkteryt').style.display = 'none';");
		$obj->script("document.getElementById('longitude').readOnly = true;");
		$obj->script("document.getElementById('latitude').readOnly = true;");
		$obj->script("document.getElementById('searchlongitude').style.display='none';");
		$obj->script("document.getElementById('searchlatitude').style.display='none';");
		
		
	} else {
		
		$obj->script("document.getElementById('location').readOnly = false;");
		$obj->script("document.getElementById('searchteryt').style.display='';");
		$obj->script("document.getElementById('checkteryt').style.display = '';");
		$obj->script("document.getElementById('longitude').readOnly = false;");
		$obj->script("document.getElementById('latitude').readOnly = false;");
		$obj->script("document.getElementById('searchlongitude').style.display='';");
		$obj->script("document.getElementById('searchlatitude').style.display='';");
		$obj->script("document.getElementById('location').value = document.getElementById('old_location').value;");
		$obj->script("document.getElementById('location_city').value = document.getElementById('old_location_city').value;");
		$obj->script("document.getElementById('location_street').value = document.getElementById('old_location_street').value;");
		$obj->script("document.getElementById('location_house').value = document.getElementById('old_location_house').value;");
		$obj->script("document.getElementById('location_flat').value = document.getElementById('old_location_flat').value;");
		$obj->script("document.getElementById('longitude').value = document.getElementById('old_longitude').value;");
		$obj->script("document.getElementById('latitude').value = document.getElementById('old_latitude').value;");
		$obj->script("if (document.getElementById('old_teryt').value == '1') document.getElementById('teryt').checked = true; else document.getElementById('teryt').checked = false;");
		
	}
	
	return $obj;

}

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('getManagementUrls', 'addManagementUrl', 'delManagementUrl','update_location_interface'));
$SMARTY->assign('xajax', $LMS->RunXajax());
?>
