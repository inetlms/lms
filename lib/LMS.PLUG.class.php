<?php

$_pluglist = array();
$_pluginc = array();

class PLUG {

	var $plugins = array();


	function __construct() { }


	public function list_dir($dir,$maska=NULL) {
		
		$lista = array();
		
		if (!is_null($maska)) $maska = '.'.$maska;
		
		if (is_dir($dir)) {
			
			foreach(array_diff(scandir($dir),array('.','..')) as $file) {
				
				if (!is_null($maska)) {
					
					if ($myfile = stristr($file,$maska)) $lista[] = $file;
					
				} else $lista[] = $file;
			}
		}
		
		return $lista;
	}


	public function initPlugins() 
	{
		
		global $DB,$_pluglist;
		
		if ($tmp = $DB->GetAll('SELECT name FROM plug WHERE enabled = 1;')) {
			for ($i=0; $i<sizeof($tmp); $i++)
				$_pluglist[] = $tmp[$i]['name'];
		}
		
	}


	public function IncludeRegisterHook()
	{
	    global $DB,$LMS,$AUTH,$SESSION,$CONFIG;
	    
	    if ($info = $DB->GetAll('SELECT name FROM plug;')) {
		for ($i=0; $i<sizeof($info); $i++) {
		    if (file_exists(PLUG_DIR.'/'.$info[$i]['name'].'/registerhook.php')) 
			include(PLUG_DIR.'/'.$info[$i]['name'].'/registerhook.php');
		}
		
	    }
	}


	function addBoxCustomer($plugname,$modfile=NULL,$htmlfile=NULL)
	{
		global $_pluginc;
		
		if (is_null($modfile) && is_null($htmlfile)) 
		    return FALSE;
		
		if (!is_null($modfile) && !empty($modfile) && file_exists(PLUG_DIR.'/'.$plugname.'/modules/'.$modfile))
			$modfile = PLUG_DIR.'/'.$plugname.'/modules/'.$modfile;
		else
			$modfile = NULL;
		
		if (!is_null($htmlfile) && !empty($htmlfile) && file_exists(PLUG_DIR.'/'.$plugname.'/templates/'.$htmlfile))
			$htmlfile = 'plug/'.$plugname.'/templates/'.$htmlfile;
		else
			$htmlfile = NULL;
		
		if (!is_null($modfile) || !is_null($htmlfile)) {
			
			$_pluginc['customer'][] = array(
			    'modfile'	=> $modfile,
			    'htmlfile'	=> $htmlfile
			);
		}
	}


	public function addBox($plugname, $section, $modfile = NULL, $htmlfile = NULL)
	{
		global $_pluginc;
		
		if (is_null($modfile) && is_null($htmlfile))
		    return FALSE;
		
		if (!is_null($modfile) && !empty($modfile) && file_exists(PLUG_DIR.'/'.$plugname.'/modules/'.$modfile))
			$modfile = PLUG_DIR.'/'.$plugname.'/modules/'.$modfile;
		else
			$modfile = NULL;
		
		if (!is_null($htmlfile) && !empty($htmlfile) && file_exists(PLUG_DIR.'/'.$plugname.'/templates/'.$htmlfile))
			$htmlfile = 'plug/'.$plugname.'/templates/'.$htmlfile;
		else
			$htmlfile = NULL;
		
		if (!is_null($modfile) || !is_null($htmlfile)) {
			
			$_pluginc[$section][] = array(
			    'modfile'	=> $modfile,
			    'htmlfile'	=> $htmlfile
			);
		}
	}


	function includeJavaScript($script = NULL)
	{
	    if (!$script)
		return FALSE;
	    
	    global $layout;
	    $layout['includesjs'][] = $script;
	}


	public function updateDBPlugins()
	{
	global $DB;
	
	if (!defined('NO_CHECK_UPGRADEDB')){
	
	    if ($info = $DB->GetAll('SELECT name, dbver FROM plug WHERE enabled = 1;')) {
		for ($i=0; $i<sizeof($info); $i++) {
		
		    if (file_exists(PLUG_DIR.'/'.$info[$i]['name'].'/configuration.php')) {
			include(PLUG_DIR.'/'.$info[$i]['name'].'/configuration.php');
			if ($__info['installdb'] && !empty($__info['dbversion']) && $__info[$DB->_dbtype] == true && $__info['dbversion'] > $info[$i]['dbver']) {
			    
			    set_time_limit(0);
			    
			    $lastupgrade = $info[$i]['dbver'];
			    $dbversion = $__info['dbversion'];
			    $_dbtype = $DB->_dbtype;
			    $upgradelist = getdir(PLUG_DIR.'/'.$info[$i]['name'].'/install/', '^'.$_dbtype.'.[0-9]{10}.php$');
			    
			    if(sizeof($upgradelist))
				foreach($upgradelist as $upgrade)
				{
					$upgradeversion = preg_replace('/^'.$_dbtype.'\.([0-9]{10})\.php$/','\1',$upgrade);
					if($upgradeversion > $lastupgrade && $upgradeversion <= $dbversion)
					$pendingupgrades[] = $upgradeversion;
				}
				
				if(sizeof($pendingupgrades))
				{
				    sort($pendingupgrades);
				    foreach($pendingupgrades as $upgrade)
				    {
					include(PLUG_DIR.'/'.$info[$i]['name'].'/install/'.$_dbtype.'.'.$upgrade.'.php');
					if(!sizeof($DB->errors))
					    $lastupgrade = $upgrade;
					else
					    break;
				    }
				}
			}
		    }
		}
	    }
	    unset($info);
	    unset($__info);
	    }
	}


} // end class

$PLUG = new PLUG();
?>