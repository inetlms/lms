<?php

$_pluglist = array();
$_pluginc = array();	// lista plików do podłączenia 
$_pluglinks = array();	// lista linków do wstawienia w róznych kartach



class PLUG {

	var $plugins = array();
	
	// lista dostępnych sekcji gdzie można podpiąć boxy lub linki
	var $_section_box = array(
	    'customer',
	    'networknodeinfo_interface',
	);
	
	var $_section_links = array(
	    'customerinfo',
	    'customeredit',
	    'customeradd',
	    'nodeinfo', // komputer klienta
	    'nodeedit', // komputer klienta
	    'nodeadd',  // ---"---
	    'netdevipinfo', // netdevipinfobox.html
	);


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
	    global $DB,$_pluglist,$SESSION;
	
	    if ($tmp = $DB->GetAll('SELECT name FROM plug WHERE enabled = 1;')) {
	
		for ($i=0; $i<sizeof($tmp); $i++) {
		    if (file_exists(PLUG_DIR.'/'.$tmp[$i]['name'].'/configuration.php')) {
			include(PLUG_DIR.'/'.$tmp[$i]['name'].'/configuration.php');
			if (isset($__info['revision']) && $__info['revision'] != 'iNET LMS') {
			    $SESSION->addWarning('Wtyczka <b>'.$__info['display'].'</b> nie została uruchomiona, Wtyczka napisana tylko pod iLMS');
			} else {
			    if (version_compare($__info['minversion'],LMSV) != '1') 
				$_pluglist[] = $tmp[$i]['name'];
			    else 
				$SESSION->addWarning('Wtyczka <b>'.$__info['display'].'</b> nie została uruchomiona, iNET LMS nie spełnia wymaganej wersji');
			}
		    }
		}
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


    private function check(&$section, $section_array, $indexes = NULL)
    {
	global $AUTH;
	
	$section = strtolower($section);
	if (!in_array($section,$section_array))
	    return FALSE;
	
	if (!is_null($indexes) && !empty($indexes) && !empty($AUTH->nomodules) && in_array($indexes,$AUTH->nomodules)) 
	    return FALSE;
	
	return TRUE;
    }

	// zostaje żeby zachować zgodność ze starymi wtyczkami
	function addBoxCustomer($plugname,$modfile=NULL,$htmlfile=NULL)
	{
	    $this->addBox($plugname,'customer',$modfile,$htmlfile,NULL);
	}


	function addBox($plugname, $section, $modfile = NULL, $htmlfile = NULL, $indexes = NULL)
	{
		global $_pluginc;
		
		if (!is_null($modfile) && !empty($modfile) && file_exists(PLUG_DIR.'/'.$plugname.'/modules/'.$modfile))
			$modfile = PLUG_DIR.'/'.$plugname.'/modules/'.$modfile;
		else
			$modfile = NULL;
		
		if (!is_null($htmlfile) && !empty($htmlfile) && file_exists(PLUG_DIR.'/'.$plugname.'/templates/'.$htmlfile))
			$htmlfile = 'plug/'.$plugname.'/templates/'.$htmlfile;
		else
			$htmlfile = NULL;
		
		if (is_null($modfile) && is_null($htmlfile))
		    return FALSE;
		
		$sec = explode('|',$section);
		for ($i=0; $i<sizeof($sec); $i++)
		{
		    if ($this->check($sec[$i],$this->_section_box,$indexes)) 
			$_pluginc[$sec[$i]][] = array(
			    'modfile'	=> $modfile,
			    'htmlfile'	=> $htmlfile
			);
		}
	}


    public function addLinks($section, $links, $indexes = NULL)
    {
	global $_pluglinks;
	
	$sec = explode('|',$section);
	
	for ($i=0; $i<sizeof($sec); $i++) {
	
	    if ($this->check($sec[$i],$this->_section_links,$indexes)) 
		$_pluglinks[$sec[$i]][] = $links;
	}
    }


    public function includeJavaScript($script = NULL)
    {
	if (!$script)
	    return FALSE;
	
	global $layout;
	$layout['includesjs'][] = $script;
    }


	public function updateDBPlugins()
	{
	global $DB,$_pluglist;
	
	if (!defined('NO_CHECK_UPGRADEDB')){
	
	    if ($info = $DB->GetAll('SELECT name, dbver FROM plug WHERE enabled = 1;')) {
		for ($i=0; $i<sizeof($info); $i++) {
		
		    if (!in_array($info[$i]['name'],$_pluglist))
			continue;
		    
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
$SMARTY->assignByRef('pluglinks',$_pluglinks);
?>