<?php
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