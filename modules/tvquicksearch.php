<?php

/*
 * LMS version 1.11.9 Moloc
 *
 *  (C) Copyright 2001-2009 LMS Developers
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
 *  $Id: quicksearch.php,v 1.64 2009/06/18 08:58:41 alec Exp $
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  
 */

function macformat($mac)
{
	$res = str_replace('-', ':', $mac);
	// allow eg. format "::ab:3::12", only whole addresses
	if(preg_match('/^([0-9a-f]{0,2}):([0-9a-f]{0,2}):([0-9a-f]{0,2}):([0-9a-f]{0,2}):([0-9a-f]{0,2}):([0-9a-f]{0,2})$/i', $mac, $arr))
	{
		$res = '';
		for($i=1; $i<=6; $i++)
		{
			if($i > 1) $res .= ':';
			if(strlen($arr[$i]) == 1) $res .= '0';
			if(strlen($arr[$i]) == 0) $res .= '00';

			$res .= $arr[$i];
		}
	}
	else // other formats eg. cisco xxxx.xxxx.xxxx or parts of addresses
	{
		$tmp = preg_replace('/[^0-9a-f]/i', '', $mac);

		if(strlen($tmp) == 12) // we've the whole address
		if(check_mac($tmp))
		$res = $tmp;
	}
	return $res;
}

$search = urldecode(trim($_GET['what']));
$model = explode(",", urldecode(trim($_GET['model'])));

if(isset($_GET['ajax'])) // support for AutoSuggest
{
	//	if (!isset($_SESSION['stbsearch'])){
	//		$list = $LMS->stbGetRegistered('stock');
	//		$_SESSION['stbsearch'] = $list;
	//	} else 	$list = $_SESSION['stbsearch'];
	// print_r($_SESSION);

	session_start();

	if (!$_SESSION['stbsearch']) {
		//print_r("pobiera");
		$_SESSION['stbsearch'] = $LMSTV->stbGetRegistered('stock');
	}
	$list = $_SESSION['stbsearch'];
	
	$search = trim(strtolower(str_replace(":", "", $search)));

	$eglible=array(); $actions=array(); $descriptions=array();
	$i = 10;
	if ($list)
	foreach($list as $idx => $row) {

		if (
			(
				strpos(strtolower(str_replace(":", "", $row['stb_mac'])), $search) 
				|| strpos(strtolower($row['stb_serial']), strtolower($search))
			) && (
				empty($model[0]) || in_array($row['stb_model'], $model) 
			) 
		) {
			$actions[$row['stb_id']] = '';//?m=customerinfo&id='.$row['stb_id'];
			$eglible[$row['stb_id']] = $row['stb_mac'];
			$descriptions[$row['stb_id']] = $row['stb_serial']. ", ". $row['stb_model'];
			$i --;
		}
		if ($i < 0) break;
	}

	header('Content-type: text/plain');
	if ($eglible) {
		print preg_replace('/$/',"\");\n","this.eligible = new Array(\"".implode('","',$eglible));
		print preg_replace('/$/',"\");\n","this.descriptions = new Array(\"".implode('","',$descriptions));
		print preg_replace('/$/',"\");\n","this.actions = new Array(\"".implode('","',$actions));
	} else {
		print "false;\n";
	}
	exit;
}

//$SESSION->save('customersearch', $s);
//$SESSION->save('cslk', 'OR');

//$SESSION->remove('cslp');
//$SESSION->remove('csln');
//$SESSION->remove('cslg');
//$SESSION->remove('csls');

$target = '?m=customersearch&search=1';

$SESSION->redirect(!empty($target) ? $target : '?'.$SESSION->get('backto'));

?>
