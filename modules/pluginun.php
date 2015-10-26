<?php

if (isset($_POST['uninstall'])) {
    $form = $_POST['uninstall'];
    
    if (!$form['issure'])
	$SESSION->redirect('?m=plugin');
    
    $id = $form['id'];
    if ($form['undb'])
	$undb = true;
	else $undb = false;
    
    $name = $DB->GetOne('SELECT name FROM plug WHERE id = ? '.$DB->Limit(1).';',array($id));
    include(PLUG_DIR.'/'.$name.'/configuration.php');
    
    if ($__info['installdb'] && $undb && file_exists(PLUG_DIR.'/'.$name.'/install/'.$DB->_dbtype.'.uninstall.php')) 
    {
	set_time_limit(0);
	include(PLUG_DIR.'/'.$name.'/install/'.$DB->_dbtype.'.uninstall.php');
    }
    $DB->Execute('DELETE FROM plug WHERE id = ? ;',array($id));
    $SESSION->redirect('?m=plugin');
}



if (!isset($_GET['id']) || 
    empty($_GET['id']) || 
    !intval($_GET['id']) || 
    !$name = $DB->GetOne('SELECT name  FROM plug WHERE id = ? '.$DB->Limit('1').';',array(intval($_GET['id']))))
    $SESSION->redirect('?m=plugin');

include(PLUG_DIR.'/'.$name.'/configuration.php');

$info = $__info;
$info['id'] = intval($_GET['id']);

$layout['pagetitle'] = 'Odinstaluj wtyczkę : '.$info['display'];

$SMARTY->assign('info',$info);
$SMARTY->display('pluginun.html');
?>