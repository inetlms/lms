<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012-2015 iNET LMS Developers
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
 *  Sylwester Kondracki
 *  sylwester.kondracki@gmail.com
 *  gadu-gadu : 6164816
 *
*/

$layout['pagetitle'] = 'Konfiguracja pól formularzy';

function select_form($name)
{
    global $CONFIGFROM, $DEFAULTFORM, $SMARTY;
    $obj = new xajaxResponse();
    
    if (empty($name)) {
	$obj->assign("formfield","innerHTML","");
	return $obj;
    }
    
    $lista = array();
    foreach ($DEFAULTFORM[$name] as $key => $val)
    {
	$lista[] = array(
	'item' => $key,
	'val' => $val[0],
	'desc' => $val[1],
	);
    }
    
    
    $SMARTY->assign('lista',$lista);
    $SMARTY->assign('section',$name);
    $html = $SMARTY->fetch('configformfield.html');
    $obj->assign("formfield","innerHTML",$html);
    return $obj;
}

function set_configform($section,$var,$value)
{
    global $CONFIGFORM,$DB;
    $obj = new xajaxResponse();
    
    $CONFIGFORM[$section][$var] = $value;
    
    if ($DB->getOne('SELECT 1 FROM formconfig WHERE section=? AND var=? LIMIT 1;',array($section,$var)))
	$DB->Execute('UPDATE formconfig SET value = ? WHERE section = ? AND var = ?;',array(($value ? $value : 0),$section,$var));
    else
	$DB->Execute('INSERT INTO formconfig (section,var,value) VALUES (?,?,?);',array($section,$var,($value ? $value : 0)));
    return $obj;
}

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('select_form','set_configform',));
$SMARTY->assign('xajax', $LMS->RunXajax());

$SMARTY->assign('_CONFIGFORM',$CONFIGFORM);
$SMARTY->display('configform.html');


?>