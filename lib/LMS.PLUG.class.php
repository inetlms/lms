<?php

class PLUG {
    
    var $plugins = array();
    
    function __construct()
    {
    }
    
    public function addPlugin($info) {
	
	if (!is_array($info) || empty($info))
	    return FALSE;
	
	$plugins[$info['index']] = $info;
    }
    
}

$PLUG = new PLUG();
?>