<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2012 LMS-EX Developers
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
 *  $Id$
 */

// REPLACE THIS WITH PATH TO YOUR CONFIG FILE

$CONFIG_FILE = '/etc/lms/lms.ini';

// PLEASE DO NOT MODIFY ANYTHING BELOW THIS LINE UNLESS YOU KNOW
// *EXACTLY* WHAT ARE YOU DOING!!!
// *******************************************************************

define('START_TIME', microtime(true));
define('LMS-UI', true);
define('LMSV','15.02.08');
ini_set('error_reporting', E_ALL&~E_NOTICE);

// find alternative config files:
if (is_readable('lms.ini')) {

    $CONFIG = (array) parse_ini_file('lms.ini', true);

} elseif (is_readable($CONFIG_FILE)) {

    $CONFIG = (array) parse_ini_file($CONFIG_FILE, true);

} elseif (is_readable('/etc/lms/lms-'.$_SERVER['HTTP_HOST'].'.ini')) {

    $CONFIG = (array) parse_ini_file('/etc/lms/lms-'.$_SERVER['HTTP_HOST'].'.ini', true);

} elseif (is_readable('/etc/lms/lms.ini')) {

    $CONFIG = (array) parse_ini_file('/etc/lms/lms.ini', true);
}
elseif (!is_readable($CONFIG_FILE)) die('Unable to read configuration file ['.$CONFIG_FILE.'] !'); 

// Check for configuration vars and set default values
$CONFIG['directories']['sys_dir'] = (!isset($CONFIG['directories']['sys_dir']) ? getcwd() : $CONFIG['directories']['sys_dir']);
$CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'/lib' : $CONFIG['directories']['lib_dir']);
$CONFIG['directories']['tmp_dir'] = (!isset($CONFIG['directories']['tmp_dir']) ? $CONFIG['directories']['sys_dir'].'/tmp' : $CONFIG['directories']['tmp_dir']);
$CONFIG['directories']['rrd_dir'] = (!isset($CONFIG['directories']['rrd_dir']) ? $CONFIG['directories']['sys_dir'].'/rrd' : $CONFIG['directories']['rrd_dir']);
$CONFIG['directories']['doc_dir'] = (!isset($CONFIG['directories']['doc_dir']) ? $CONFIG['directories']['sys_dir'].'/documents' : $CONFIG['directories']['doc_dir']);
$CONFIG['directories']['uploadfiles_dir'] = (!isset($CONFIG['directories']['uploadfiles_dir']) ? $CONFIG['directories']['sys_dir'].'/uploadfiles' : $CONFIG['directories']['uploadfiles_dir']);
$CONFIG['directories']['modules_dir'] = (!isset($CONFIG['directories']['modules_dir']) ? $CONFIG['directories']['sys_dir'].'/modules' : $CONFIG['directories']['modules_dir']);
$CONFIG['directories']['backup_dir'] = (!isset($CONFIG['directories']['backup_dir']) ? $CONFIG['directories']['sys_dir'].'/backups' : $CONFIG['directories']['backup_dir']);
$CONFIG['directories']['config_templates_dir'] = (!isset($CONFIG['directories']['config_templates_dir']) ? $CONFIG['directories']['sys_dir'].'/config_templates' : $CONFIG['directories']['config_templates_dir']);
$CONFIG['directories']['smarty_compile_dir'] = (!isset($CONFIG['directories']['smarty_compile_dir']) ? $CONFIG['directories']['sys_dir'].'/templates_c' : $CONFIG['directories']['smarty_compile_dir']);
$CONFIG['directories']['smarty_templates_dir'] = (!isset($CONFIG['directories']['smarty_templates_dir']) ? $CONFIG['directories']['sys_dir'].'/templates' : $CONFIG['directories']['smarty_templates_dir']);
$CONFIG['directories']['plug_dir'] = (!isset($CONFIG['directories']['plug_dir']) ? $CONFIG['directories']['sys_dir'].'/plug' : $CONFIG['directories']['plug_dir']);
$CONFIG['directories']['invoice_dir'] = (!isset($CONFIG['directories']['invoice_dir']) ? $CONFIG['directories']['doc_dir'].'/invoice_pdf' : $CONFIG['directories']['invoice_dir']);

define('SYS_DIR', $CONFIG['directories']['sys_dir']);
define('TMP_DIR', $CONFIG['directories']['tmp_dir']);
define('RRD_DIR', $CONFIG['directories']['rrd_dir']);
define('LIB_DIR', $CONFIG['directories']['lib_dir']);
define('DOC_DIR', $CONFIG['directories']['doc_dir']);
define('BACKUP_DIR', $CONFIG['directories']['backup_dir']);
define('UPLOADFILES_DIR', $CONFIG['directories']['uploadfiles_dir']);
define('MODULES_DIR', $CONFIG['directories']['modules_dir']);
define('SMARTY_COMPILE_DIR', $CONFIG['directories']['smarty_compile_dir']);
define('SMARTY_TEMPLATES_DIR', $CONFIG['directories']['smarty_templates_dir']);
define('PLUG_DIR',$CONFIG['directories']['plug_dir']);
define('INVOICE_DIR',$CONFIG['directories']['invoice_dir']);

// Do some checks and load config defaults

require_once(LIB_DIR.'/checkdirs.php');
require_once(LIB_DIR.'/config.php');

// Init database

$_DBTYPE = $CONFIG['database']['type'];
$_DBHOST = $CONFIG['database']['host'];
$_DBUSER = $CONFIG['database']['user'];
$_DBPASS = $CONFIG['database']['password'];
$_DBNAME = $CONFIG['database']['database'];
$_DBDEBUG = (isset($CONFIG['database']['debug']) ? chkconfig($CONFIG['database']['debug']) : FALSE);

require(LIB_DIR.'/LMSDB.php');

$DB = DBInit($_DBTYPE, $_DBHOST, $_DBUSER, $_DBPASS, $_DBNAME, $_DBDEBUG);

if(!$DB)
{
	// can't working without database
	die();
}


// Initialize templates engine (must be before locale settings)

require_once(LIB_DIR.'/Smarty/Smarty.class.php');

$SMARTY = new Smarty;

// test for proper version of Smarty

if (defined('Smarty::SMARTY_VERSION'))
	$ver_chunks = preg_split('/[- ]/', Smarty::SMARTY_VERSION);
else
	$ver_chunks = NULL;
if (count($ver_chunks) < 2 || version_compare('3.1', $ver_chunks[1]) > 0)
	die('<B>Wrong version of Smarty engine! We support only Smarty-3.x greater than 3.1.</B> - '.Smarty::SMARTY_VERSION);

define('SMARTY_VERSION', $ver_chunks[1]);

// uncomment this line if you're not gonna change template files no more
//$SMARTY->compile_check = false;

// Read configuration of LMS-UI from database

if($cfg = $DB->GetAll('SELECT section, var, value FROM uiconfig WHERE disabled=0'))
	foreach($cfg as $row)
		$CONFIG[$row['section']][$row['var']] = $row['value'];


// SYSLOG
if (empty($CONFIG['phpui']['syslog_level']))
    define('SYSLOG',FALSE);
else
    define('SYSLOG',TRUE);

// Call any of upgrade process before anything else
require_once(LIB_DIR.'/functions.php');

require_once(LIB_DIR.'/upgradedb.php');


// Redirect to SSL
$_FORCE_SSL = (isset($CONFIG['phpui']['force_ssl']) ? chkconfig($CONFIG['phpui']['force_ssl']) : FALSE);

if($_FORCE_SSL && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on'))
{
	header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	exit(0);
}

// Include required files (including sequence is important)

require_once(LIB_DIR.'/language.php');
require_once(LIB_DIR.'/unstrip.php');
require_once(LIB_DIR.'/definitions.php');
require_once(LIB_DIR.'/common.php');
require_once(LIB_DIR.'/checkip.php');
require_once(LIB_DIR.'/LMS.class.php');
require_once(LIB_DIR.'/Auth.class.php');
require_once(LIB_DIR.'/Profiles.class.php');
require_once(LIB_DIR.'/accesstable.php');
require_once(LIB_DIR.'/Session.class.php');
require_once(LIB_DIR.'/GaduGadu.class.php');
require_once(LIB_DIR.'/LMS.Hiperus.class.php');
require_once(LIB_DIR.'/RADIUS.class.php');
require_once(LIB_DIR.'/Routeros_api.class.php');
require_once(LIB_DIR.'/LMS.PLUG.class.php');


// Initialize Session, Auth and LMS classes

$SESSION = new Session($DB, $CONFIG['phpui']['timeout']);
$AUTH = new Auth($DB, $SESSION);
$PROFILE = new Profile($DB,$AUTH);
$LMS = new LMS($DB, $AUTH, $CONFIG);
$LMS->ui_lang = $_ui_language;
$LMS->lang = $_language;
$GG = new rfGG(GG_VER_77);
$RAD = new radius($DB,$LMS);
$MT = new routeros_api();

require_once(LIB_DIR.'/smarty_addons.php');

$layout['plugin'] = NULL;
$layout['logname'] = $AUTH->logname;
$layout['logid'] = $AUTH->id;
$layout['lmsdbv'] = $DB->_version;
$layout['smarty_version'] = SMARTY_VERSION;
$layout['hostname'] = hostname();
$layout['lmsv'] = 'iNET LMS';
$layout['lmsvr'] = $LMS->_revision.'/'.$AUTH->_revision;
$layout['lmsvr'] = LMSV;
$layout['dberrors'] =& $DB->errors;
$layout['dbdebug'] = $_DBDEBUG;
$layout['popup'] = isset($_GET['popup']) ? true : false;
$menu = NULL;

if (get_conf('registryequipment.enabled')) {
	require_once(LIB_DIR.'/Registry.Equipment.class.php');
}

if(get_conf('voip.enabled','0') )
{
	if (fetch_url(get_conf('voip.wsdlurl')))
	{
	    require_once(LIB_DIR.'/LMSVOIP.class.php');
	    require_once(LIB_DIR.'/floAPI.php');
	    $voip = new LMSVOIP($DB, $CONFIG['voip']);
	    $layout['v_errors'] =& $voip->errors;
	} else {
	    $voip = NULL;
	    $layout['v_errors'] = 'Moduł VoIP Nettelekom został wyłączony automatycznie<br>Brak połączenia z '.get_conf('voip.wsdlurl').' !!!';
	    $layout['v_errors_connect'] = TRUE;
	    $CONFIG['voip']['enabled'] = 0;
	}
}
    else $voip = NULL;

if (get_conf('sms.service') == 'serwersms') {
    require_once(LIB_DIR.'/SerwerSMS_api.php');
}

if (get_conf('jambox.enabled',0)) {
    require_once(LIB_DIR.'/LMS.tv.class.php');
    $LMSTV = new LMSTV($DB,$AUTH,$CONFIG);
}
// Set some template and layout variables

$SMARTY->setTemplateDir(NULL);
$SMARTY->addTemplateDir(
	array(
	    SMARTY_TEMPLATES_DIR.'/custom',
	    SMARTY_TEMPLATES_DIR,
	)
);
$SMARTY->compile_dir = SMARTY_COMPILE_DIR;
$SMARTY->debugging = (isset($CONFIG['phpui']['smarty_debug']) ? chkconfig($CONFIG['phpui']['smarty_debug']) : FALSE);
$SMARTY->use_sub_dirs = TRUE;




$SMARTY->assignByRef('layout', $layout);
$SMARTY->assignByRef('LANGDEFS', $LANGDEFS);
$SMARTY->assignByRef('_ui_language', $LMS->ui_lang);
$SMARTY->assignByRef('_language', $LMS->lang);


$error = NULL; // initialize error variable needed for (almost) all modules


header('X-Powered-By: LMS/'.$layout['lmsv']);

$PLUG->updateDBPlugins();

// Check privileges and execute modules
if ($AUTH->islogged) {
	
	// Load plugin files and register hook callbacks
	$plugins = preg_split('/[;,\s\t\n]+/', $CONFIG['phpui']['plugins'], -1, PREG_SPLIT_NO_EMPTY);
	if (!empty($plugins))
		foreach ($plugins as $plugin_name)
			require LIB_DIR . '/plugins/' . $plugin_name . '.php';
	
	$res = $LMS->ExecHook('access_table_init', array('accesstable' => $access['table']));
	if (isset($res['accesstable']))
		$access['table'] = $res['accesstable'];
	
	$module = isset($_GET['m']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['m']) : '';
	$plug = isset($_GET['plug']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['plug']) : '';
	
	if (!$layout['popup'])
	    require_once(LIB_DIR.'/menu.php');
	
	
	$deny = $allow = FALSE;
	
	$res = $LMS->ExecHook('module_load_before', array('module' => $module));
	if ($res['abort']) {
		$SESSION->close();
		$DB->Destroy();
		die;
	}
	$module = $res['module'];
	
	if ($AUTH->passwdrequiredchange)
		$module = 'chpasswd';
	
	$PLUG->initPlugins();
	$_plugcount = sizeof($_pluglist);
	
	if ($module == '')
	{
		$module = $CONFIG['phpui']['default_module'];
		if (!file_exists(MODULES_DIR.'/'.$module.'.php'))
		    $module = 'welcome_new';
		    $plug = '';
	}
	
	if (!$layout['popup']) {
	    
	    if ($_pluglist) {
		for ($i=0; $i<($_plugcount); $i++) {
		    if (file_exists(PLUG_DIR.'/'.$_pluglist[$i].'/menu.php') && is_readable(PLUG_DIR.'/'.$_pluglist[$i].'/menu.php'))
			include(PLUG_DIR.'/'.$_pluglist[$i].'/menu.php');
		}
	    }
	    
	    foreach($menu as $idx => $item) if(isset($item['submenu'])) uasort($menu[$idx]['submenu'],'menu_cmp');
	    uasort($menu,'menu_cmp');
	    $SMARTY->assign('newmenu',$menu);
	}
	
	if ($_plugcount > 0) {
	    
	    for ($i=0; $i<$_plugcount; $i++) {
		
		if (file_exists(PLUG_DIR.'/'.$_pluglist[$i].'/lang/'.$LMS->lang.'.php'))
			require_once(PLUG_DIR.'/'.$_pluglist[$i].'/lang/'.$LMS->lang.'.php');
		
		if (file_exists(PLUG_DIR.'/'.$_pluglist[$i].'/inc.php'))
			require_once(PLUG_DIR.'/'.$_pluglist[$i].'/inc.php');
		
		if (is_dir(PLUG_DIR.'/'.$_pluglist[$i].'/includes')) {
		    $phpfile = $PLUG->list_dir(PLUG_DIR.'/'.$_pluglist[$i].'/includes','php');
		    $jsfile = $PLUG->list_dir(PLUG_DIR.'/'.$_pluglist[$i].'/includes','js');
		    $cssfile = $PLUG->list_dir(PLUG_DIR.'/'.$_pluglist[$i].'/includes','css');
		    for ($j=0; $j<sizeof($phpfile); $j++) require_once(PLUG_DIR.'/'.$_pluglist[$i].'/includes/'.$phpfile[$j]);
		    for ($j=0; $j<sizeof($jsfile); $j++) $layout['includesjs'][] = 'plug/'.$_pluglist[$i].'/includes/'.$jsfile[$j];
		    for ($j=0; $j<sizeof($cssfile); $j++) $layout['includescss'][] = 'plug/'.$_pluglist[$i].'/includes/'.$cssfile[$j];
		}
		
	    }
	    
	    $SMARTY->assignByRef('_pluginc',$_pluginc);
	}
	
	if ($plug) {
	    
	    if (file_exists(PLUG_DIR.'/'.$plug.'/lang/'.$LMS->lang.'.php'))
		require_once(PLUG_DIR.'/'.$plug.'/lang/'.$LMS->lang.'.php');
	    
	    if (is_dir(PLUG_DIR.'/'.$plug.'/includes_call')) {
		$phpfile = $PLUG->list_dir(PLUG_DIR.'/'.$plug.'/includes_call','php');
		$jsfile = $PLUG->list_dir(PLUG_DIR.'/'.$plug.'/includes_call','js');
		$cssfile = $PLUG->list_dir(PLUG_DIR.'/'.$plug.'/includes_call','css');
		for ($j=0; $j<sizeof($phpfile); $j++) require_once(PLUG_DIR.'/'.$plug.'/includes_call/'.$phpfile[$j]);
		for ($j=0; $j<sizeof($jsfile); $j++) $layout['includesjs'][] = 'plug/'.$plug.'/includes_call/'.$jsfile[$j];
		for ($j=0; $j<sizeof($cssfile); $j++) $layout['includescss'][] = 'plug/'.$plug.'/includes_call/'.$cssfile[$j];
	    }

	    if (file_exists(PLUG_DIR.'/'.$plug.'/modules/'.$module.'.php')) {
		$layout['plug'] = $plug;
		$layout['module'] = $module;
		include(PLUG_DIR.'/'.$plug.'/modules/'.$module.'.php');
	    } else {
		$layout['module'] = 'notfound';
		$layout['pagetitle'] = trans('Error!');
		$SMARTY->assign('layout', $layout);
		$SMARTY->assign('server', $_SERVER);
		$SMARTY->display('notfound.html');
	    }
	    
//	    $SMARTY->assign('plug',$plug);
	}
	elseif (file_exists(MODULES_DIR.'/custom/'.$module.'.php'))
	{
		$global_allow = !$AUTH->id || (!empty($access['allow']) && preg_match('/'.$access['allow'].'/i', $module));

		if ($AUTH->id && ($rights = $LMS->GetUserRights($AUTH->id)))
			foreach ($rights as $level)
			{
				if ($level === 0) {
					$CONFIG['privileges']['superuser'] = true;
				}

				if (!$global_allow && !$deny && isset($access['table'][$level]['deny_reg']))
					$deny = (bool) preg_match('/'.$access['table'][$level]['deny_reg'].'/i', $module);
				elseif (!$allow && isset($access['table'][$level]['allow_reg']))
					$allow = (bool) preg_match('/'.$access['table'][$level]['allow_reg'].'/i', $module);

				if (isset($access['table'][$level]['privilege']))
					$CONFIG['privileges'][$access['table'][$level]['privilege']] = TRUE;
			}

		if ($global_allow || ($allow && !$deny))
		{
			$layout['module'] = $module;
			$LMS->InitUI();
			include(MODULES_DIR.'/custom/'.$module.'.php');
		}
		else
			$SMARTY->display('noaccess.html');
	}
	elseif (file_exists(MODULES_DIR.'/'.$module.'.php'))
	{
		$global_allow = !$AUTH->id || (!empty($access['allow']) && preg_match('/'.$access['allow'].'/i', $module));

		if ($AUTH->id && ($rights = $LMS->GetUserRights($AUTH->id)))
			foreach ($rights as $level)
			{
				if ($level === 0) {
					$CONFIG['privileges']['superuser'] = true;
				}

				if (!$global_allow && !$deny && isset($access['table'][$level]['deny_reg']))
					$deny = (bool) preg_match('/'.$access['table'][$level]['deny_reg'].'/i', $module);
				elseif (!$allow && isset($access['table'][$level]['allow_reg']))
					$allow = (bool) preg_match('/'.$access['table'][$level]['allow_reg'].'/i', $module);

				if (isset($access['table'][$level]['privilege']))
					$CONFIG['privileges'][$access['table'][$level]['privilege']] = TRUE;
			}

		if ($global_allow || ($allow && !$deny))
		{
			$layout['module'] = $module;
			$LMS->InitUI();
			include(MODULES_DIR.'/'.$module.'.php');
		}
		else
			$SMARTY->display('noaccess.html');
	}
	else
	{
		$layout['module'] = 'notfound';
		$layout['pagetitle'] = trans('Error!');
		$SMARTY->assign('layout', $layout);
		$SMARTY->assign('server', $_SERVER);
		$SMARTY->display('notfound.html');
	}
	
	if (!$PROFILE->autosave) 
		$PROFILE->saveProfiles();
	
	if($SESSION->get('lastmodule') != $module)
		$SESSION->save('lastmodule', $module);
}
else
{
    
	$SMARTY->assign('error', $AUTH->error);
	$SMARTY->assign('target','?'.$_SERVER['QUERY_STRING']);
	function login_adbox()
	{
	    $obj = new xajaxResponse();
	    if ($result = fetch_url(INETLMS_ADV_URL))
		$obj->assign("id_login_adbox","innerHTML",base64_decode($result));
	    else
		$obj->assign("id_login_adbox","innerHTML","<img src='img/logo.gif'>");
	    return $obj;
	}
	
	$ver = @file_get_contents('https://raw.githubusercontent.com/inetlms/lms/master/README.md');
	$_newversion = NULL;
	
	if ($ver) {
		$ver = str_replace("\n","",$ver);
		$ver = str_replace("iNET LMS ","",$ver);
		
		if ($ver != LMSV && version_compare($ver,LMSV) == '1') 
			$_newversion = $ver;
	}
	
	$LMS->InitXajax();
	$LMS->RegisterXajaxFunction('login_adbox');
	$SMARTY->assign('xajax',$LMS->RunXajax());
	$SMARTY->assign('_newversion',$_newversion);
	$SMARTY->display('login.html');
}


$SESSION->close();
$DB->Destroy();

?>
