<?php

$layout['pagetitle'] = 'Konfiguracja wtyczek';

$pluglist = array();

$plugindir = opendir(PLUG_DIR);
while ( false != ($dirname = readdir($plugindir)))
{
    if ((preg_match('/^[a-zA-Z0-9]/',$dirname)) && (is_dir(PLUG_DIR.'/'.$dirname)) && file_exists(PLUG_DIR.'/'.$dirname.'/configuration.php'))
    {
	require_once(PLUG_DIR.'/'.$dirname.'/configuration.php');
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

$SMARTY->assign('pluglist',$pluglist);
$SMARTY->display('plugin.html');
?>