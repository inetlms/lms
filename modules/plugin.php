<?php

$layout['pagetitle'] = 'Configure Plugins';

if (isset($_GET['action']) && !empty($_GET['action']))
{

    switch ($_GET['action'])
    {
    
	case 'enabled' :
		$id = intval($_GET['id']);
		$set = ($_GET['set'] ? 1 : 0);
		$DB->Execute('UPDATE plug SET enabled=? WHERE id = ?;',array($set,$id));
		$SESSION->redirect('?m=plugin');
	break;
	
	case 'install' :
		$ind = $_GET['ind'];
		$name = $_GET['name'];
		
		$pluglist = array();
		
		$plugindir = opendir(PLUG_DIR);
		while ( false != ($dirname = readdir($plugindir))) {
			
			if ((preg_match('/^[a-zA-Z0-9]/',$dirname)) && (is_dir(PLUG_DIR.'/'.$dirname)) && file_exists(PLUG_DIR.'/'.$dirname.'/configuration.php')) {
				
				require_once(PLUG_DIR.'/'.$dirname.'/configuration.php');
				
				if ($__info['name'] == $name && $__info['indexes'] == $ind) {
				
				    if (!$DB->GetOne('SELECT 1 FROM plug WHERE name=? AND indexes=? LIMIT 1;',array($name,$ind)))
					$DB->Execute('INSERT INTO plug (name,indexes,enabled,dbver) VALUES (?, ?, ?, ?);',
					    array($__info['name'],$__info['indexes'],0,($__info['dbversion'] ? $__info['dbversion'] : '')));
				
				}
			
			}
		}
		$SESSION->redirect('?m=plugin');
	break;
    
    }

}

$pluglist = array();

$plugindir = opendir(PLUG_DIR);
while ( false != ($dirname = readdir($plugindir))) {
    
    if ((preg_match('/^[a-zA-Z0-9]/',$dirname)) && (is_dir(PLUG_DIR.'/'.$dirname)) && file_exists(PLUG_DIR.'/'.$dirname.'/configuration.php')) {
	
	require_once(PLUG_DIR.'/'.$dirname.'/configuration.php');
//	
//	if (isset($__info['disabled']) && $__info['disabled'] == true)
//	    break;
	
	$pluglist[$__info['indexes']] = $__info;
    }
}

$installplug = $DB->GetAllByKey('SELECT id,indexes,enabled,dbver, 1 AS install FROM plug ORDER BY name ASC;','indexes');

foreach ($pluglist as $key => $item) {
    if (isset($installplug[$key])) {
    $pluglist[$key]['install'] = $installplug[$key]['install'];
    $pluglist[$key]['id'] = $installplug[$key]['id'];
    $pluglist[$key]['enabled'] = $installplug[$key]['enabled'];
    $pluglist[$key]['dbver'] = $installplug[$key]['dbver'];
    }
}
//echo "<pre>"; print_r($pluglist); echo "</pre>"; die;
$SMARTY->assign('pluglist',$pluglist);
$SMARTY->display('plugin.html');
?>