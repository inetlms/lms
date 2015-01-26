<?php

$_pluglist = array();
$_pluginc = array();

class PLUG {


    var $plugins = array();


    function __construct() { }


    public function initPlugins() {
	
	global $DB,$_pluglist;
	
	if ($tmp = $DB->GetAll('SELECT name FROM plug WHERE enabled = 1;')) {
	    for ($i=0; $i<sizeof($tmp); $i++)
		$_pluglist[] = $tmp[$i]['name'];
	}
	
    }


    function addIncludeCustomer($plugname,$modfile=NULL,$htmlfile=NULL)
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
	
	if (!is_null($modfile) || !is_null($htmlfile)) {
	    
	    $_pluginc['customer'][] = array(
		'modfile'	=> $modfile,
		'htmlfile'	=> $htmlfile
	    );
	}
    }


    function list_dir($dir,$maska=NULL) {
	
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




} // end class

$PLUG = new PLUG();
?>