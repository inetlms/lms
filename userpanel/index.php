<?php

/*
 *  LMS version 1.11-git
 *
 *  (C) Copyright 2001-2012 LMS Developers
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

$CONFIG_FILE = (is_readable('lms.ini')) ? 'lms.ini' : '/etc/lms/lms.ini';

// PLEASE DO NOT MODIFY ANYTHING BELOW THIS LINE UNLESS YOU KNOW
// *EXACTLY* WHAT ARE YOU DOING!!!
// *******************************************************************

ini_set('session.name','LMSSESSIONID');
ini_set('error_reporting', E_ALL&~E_NOTICE);

// find alternative config files:
if(is_readable('lms.ini'))
        $CONFIG_FILE = 'lms.ini';
elseif(is_readable('/etc/lms/lms-'.$_SERVER['HTTP_HOST'].'.ini'))
        $CONFIG_FILE = '/etc/lms/lms-'.$_SERVER['HTTP_HOST'].'.ini';
elseif(!is_readable($CONFIG_FILE))
        die('Unable to read configuration file ['.$CONFIG_FILE.']!');

// Parse configuration file
$CONFIG = (array) parse_ini_file($CONFIG_FILE, true);

// Check for configuration vars and set default values
if(empty($CONFIG['directories']['sys_dir']) || !file_exists($CONFIG['directories']['sys_dir']))
	die('System directory is not set or not exists!');
else
	$CONFIG['directories']['sys_dir'] = $CONFIG['directories']['sys_dir'];

$CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'lib' : $CONFIG['directories']['lib_dir']);
$CONFIG['directories']['modules_dir'] = (!isset($CONFIG['directories']['modules_dir']) ? $CONFIG['directories']['sys_dir'].'/modules' : $CONFIG['directories']['modules_dir']);
$CONFIG['directories']['userpanel_dir'] = (!isset($CONFIG['directories']['userpanel_dir']) ? getcwd() : $CONFIG['directories']['userpanel_dir']);
$CONFIG['directories']['smarty_compile_dir'] = $CONFIG['directories']['userpanel_dir'].'/templates_c';
$CONFIG['directories']['tmp_dir'] = (!isset($CONFIG['directories']['tmp_dir']) ? $CONFIG['directories']['sys_dir'].'/tmp' : $CONFIG['directories']['tmp_dir']);
$CONFIG['directories']['rrd_dir'] = (!isset($CONFIG['directories']['rrd_dir']) ? $CONFIG['directories']['sys_dir'].'/rrd' : $CONFIG['directories']['rrd_dir']);
$CONFIG['directories']['doc_dir'] = (!isset($CONFIG['directories']['doc_dir']) ? $CONFIG['directories']['sys_dir'].'/documents' : $CONFIG['directories']['doc_dir']);
$CONFIG['directories']['backup_dir'] = (!isset($CONFIG['directories']['backup_dir']) ? $CONFIG['directories']['sys_dir'].'/backups' : $CONFIG['directories']['backup_dir']);
$CONFIG['directories']['config_templates_dir'] = (!isset($CONFIG['directories']['config_templates_dir']) ? $CONFIG['directories']['sys_dir'].'/config_templates' : $CONFIG['directories']['config_templates_dir']);
$CONFIG['directories']['smarty_templates_dir'] = (!isset($CONFIG['directories']['smarty_templates_dir']) ? $CONFIG['directories']['sys_dir'].'/templates' : $CONFIG['directories']['smarty_templates_dir']);

define('USERPANEL_DIR', $CONFIG['directories']['userpanel_dir']);
define('USERPANEL_LIB_DIR', USERPANEL_DIR.'/lib/');
define('USERPANEL_MODULES_DIR', USERPANEL_DIR.'/modules/');

define('SYS_DIR', $CONFIG['directories']['sys_dir']);
define('LIB_DIR', $CONFIG['directories']['lib_dir']);
define('DOC_DIR', $CONFIG['directories']['doc_dir']);
define('MODULES_DIR', $CONFIG['directories']['modules_dir']);
define('SMARTY_COMPILE_DIR', $CONFIG['directories']['smarty_compile_dir']);

// include required files

require_once(USERPANEL_LIB_DIR.'/checkdirs.php');
require_once(LIB_DIR.'/config.php');
// Initialize database
$_DBTYPE = $CONFIG['database']['type'];
$_DBHOST = $CONFIG['database']['host'];
$_DBUSER = $CONFIG['database']['user'];
$_DBPASS = $CONFIG['database']['password'];
$_DBNAME = $CONFIG['database']['database'];

require_once(LIB_DIR.'/LMSDB.php');

$DB = DBInit($_DBTYPE, $_DBHOST, $_DBUSER, $_DBPASS, $_DBNAME);

if (!$DB) die;

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

// Read configuration of LMS-UI from database

if($cfg = $DB->GetAll('SELECT section, var, value FROM uiconfig WHERE disabled=0'))
        foreach($cfg as $row)
                $CONFIG[$row['section']][$row['var']] = $row['value'];

// Redirect to SSL

$_FORCE_SSL = check_conf('phpui.force_ssl');

if($_FORCE_SSL && $_SERVER['HTTPS'] != 'on')
{
     header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
     exit(0);
}

$_TIMEOUT = $CONFIG['phpui']['timeout'];

// Include required files (including sequence is important)

require_once(LIB_DIR.'/language.php');
include_once(LIB_DIR.'/definitions.php');
require_once(LIB_DIR.'/unstrip.php');
require_once(LIB_DIR.'/common.php');
require_once(LIB_DIR.'/LMS.class.php');



$AUTH = NULL;
$LMS = new LMS($DB, $AUTH, $CONFIG);

require_once(USERPANEL_LIB_DIR.'/Session.class.php');
require_once(USERPANEL_LIB_DIR.'/Userpanel.class.php');
require_once(USERPANEL_LIB_DIR.'/ULMS.class.php');
@include(USERPANEL_DIR.'/lib/locale/'.$_ui_language.'/strings.php');

unset($LMS); // reset LMS class to enable wrappers for LMS older versions

$LMS = new ULMS($DB, $AUTH, $CONFIG);
$SESSION = new Session($DB, $_TIMEOUT);
$USERPANEL = new USERPANEL($DB, $SESSION, $CONFIG);
$LMS->ui_lang = $_ui_language;
$LMS->lang = $_language;

// Initialize modules

$dh  = opendir(USERPANEL_MODULES_DIR);

$nomod = unserialize(get_conf('userpanel.disable_modules','a:0:{}'));

if($SESSION->islogged) {

	$noloadmodule = array();
	
	if($CONFIG['voip']['enabled'] == 1) {
	    
	    require_once(LIB_DIR.'/LMSVOIP.class.php');
	    $voip=new LMSVOIP($DB,$CONFIG['voip']);
	
	     if (!$DB->GetOne('SELECT 1 FROM v_exportedusers WHERE lmsid = ?', array($SESSION->id)))
	     $noloadmodule[] = 'voip';
	} else {
	     $noloadmodule[] = 'voip';
	}
	
	while (false !== ($filename = readdir($dh))) 
	{
	    if (!in_array($filename,$nomod) && !in_array($filename,$noloadmodule) && (preg_match('/^[a-zA-Z0-9]/',$filename)) && (is_dir(USERPANEL_MODULES_DIR.$filename)) && file_exists(USERPANEL_MODULES_DIR.$filename.'/configuration.php'))
	    {
		@include(USERPANEL_MODULES_DIR.$filename.'/locale/'.$_ui_language.'/strings.php');
		include(USERPANEL_MODULES_DIR.$filename.'/configuration.php');
	    }
	};

} else {

	while (false !== ($filename = readdir($dh))) 
	{
	    if (!in_array($filename,$nomod) && (preg_match('/^[a-zA-Z0-9]/',$filename)) && (is_dir(USERPANEL_MODULES_DIR.$filename)) && file_exists(USERPANEL_MODULES_DIR.$filename.'/configuration.php'))
	    {
		@include(USERPANEL_MODULES_DIR.$filename.'/locale/'.$_ui_language.'/strings.php');
		include(USERPANEL_MODULES_DIR.$filename.'/configuration.php');
	    }
	};

}

$SMARTY->assignByRef('LANGDEFS', $LANGDEFS);
$SMARTY->assignByRef('_ui_language', $LMS->ui_lang);
$SMARTY->assignByRef('_language', $LMS->lang);
$SMARTY->template_dir = USERPANEL_DIR.'/templates/';
$SMARTY->compile_dir = SMARTY_COMPILE_DIR;
$SMARTY->debugging = check_conf('phpui.smarty_debug');
require_once(USERPANEL_LIB_DIR.'/smarty_addons.php');

$layout['upv'] = $USERPANEL->_version.' ('.$USERPANEL->_revision.'/'.$SESSION->_revision.')';
$layout['lmsdbv'] = $DB->_version;
$layout['lmsv'] = $LMS->_version;
$layout['smarty_version'] = SMARTY_VERSION;
$layout['hostname'] = hostname();
$layout['dberrors'] =& $DB->errors;

$SMARTY->assignByRef('modules', $USERPANEL->MODULES);
$SMARTY->assignByRef('layout', $layout);

header('X-Powered-By: iNET LMS/'.$layout['lmsv']);



if($SESSION->islogged)
{
	$module = isset($_GET['m']) ? $_GET['m'] : '';
	
	if (isset($USERPANEL->MODULES[$module])) $USERPANEL->MODULES[$module]['selected'] = true;

	// Userpanel rights module
	$rights = $USERPANEL->GetCustomerRights($SESSION->id);
	$SMARTY->assign('rights', $rights);

	if(check_conf('userpanel.hide_nodes_modules'))
	{
		if(!$DB->GetOne('SELECT COUNT(*) FROM nodes WHERE ownerid = ? LIMIT 1', array($SESSION->id)))
		{
			unset($USERPANEL->MODULES['messages']);
			unset($USERPANEL->MODULES['stats']);
		}
	}

	if( file_exists(USERPANEL_MODULES_DIR.$module.'/functions.php')
	    && isset($USERPANEL->MODULES[$module]) )
        {
	    
    		include(USERPANEL_MODULES_DIR.$module.'/functions.php');

		$function = isset($_GET['f']) && $_GET['f']!='' ? $_GET['f'] : 'main';
		if (function_exists('module_'.$function)) 
		{
		    $to_execute = 'module_'.$function;
		    $to_execute();
		} else {
    		    $layout['error'] = trans('Function <b>$a</b> in module <b>$b</b> not found!', $function, $module);
    		    $SMARTY->display('error.html');
		}
	    
        }
        // if no module selected, redirect on module with lowest prio
	elseif ($module=='')
        {
		$redirectmodule = 'nomodulesfound';
		$redirectprio = 999;
		foreach ($USERPANEL->MODULES as $menupos)
		    if ($redirectprio > $menupos['prio']) 
		    {
			$redirectmodule = $menupos['module'];
			$redirectprio = $menupos['prio'];
		    }
		if ($redirectmodule == 'nomodulesfound') 
		{
    		    $layout['error'] = trans('No modules found!');
    		    $SMARTY->display('error.html');
		} 
		else
		{
		    header('Location: ?m='.$redirectmodule);
		}
        }
        else
        {
    		$layout['error'] = trans('Module <b>$a</b> not found!', $module);
    		$SMARTY->display('error.html');
    	}

        if(!isset($_SESSION['lastmodule']) || $_SESSION['lastmodule'] != $module)
    		$_SESSION['lastmodule'] = $module;
}
else
{
	if (isset($_POST['recovery']))
	{
	    $recovery['error'] = 'OK';

	    $dane = $_POST['recovery'];
	    $recovery['email'] = ($dane['email'] ? $dane['email'] : '');
	    $recovery['pesel'] = ($dane['pesel'] ? $dane['pesel'] : '');

	    if (empty($recovery['email']) || empty($recovery['pesel'])) $recovery['error'] = 'NODATA';
	    
	    if ($recovery['error'] === 'OK') { // czeszemy bazę
		$referer = str_replace('/?m=','',$_SERVER['HTTP_REFERER']);
		if ($tmp = $DB->GetRow('SELECT id, pin FROM customers WHERE UPPER(email) = ? AND (ten = ? OR ssn = ?) '.$DB->Limit(1).' ;',array(strtoupper($recovery['email']),$recovery['pesel'],$recovery['pesel'])))
		{
		    $customer = $LMS->GetCustomer($tmp['id']);
		    $division = $DB->GetOne('SELECT name FROM divisions WHERE id = ? '.$DB->Limit(1).' ;',array($customer['divisionid']));
		    $body = "\n";
		    $body .="Witaj ".$customer['customername']."\n\n";
		    $body .="Drogi Abonencie firmy ".$division."\n";
		    $body .="Twoje dane do Inernetowego Biura Obsługi Klienta to:\n\n";
		    $body .="ID : ".$tmp['id']."\n";
		    $body .="PIN: ".$tmp['pin']."\n\n";
		    $body .="Otrzymałeś(aś) ten e-mail pnieważ ktoś skorzystał z formularza przypomnienia loginu i hasła\n";
		    $body .=" na stronie ".$referer."\n\n";
		    $body .="Wiadomość została wygenerowana automatycznie, prosimy nie odpowiadać.\n";
		    $head = array(
			'From'	=> '"Automatyczny System Powiadomień" <'.$CONFIG['mail']['smtp_username'].'>',
			'Subject' => $division." - przypomnienie ID i PIN",
			'Reply-To' => 'No-Reply',
			'To' => ''
		    );
		    if ($LMS->SendMail($recovery['email'],$head,$body))
			$recovery['error'] = 'OK';
		    else 
			$recovery['error'] = 'ERROR';
		}
		else $recovery['error'] = 'ERROR';
	    }
	    
	    if ($recovery['error'] === 'OK') 
		$recovery['email'] = $recovery['pesel'] = NULL;
	
	    $SMARTY->assign('recovery',$recovery);
	}
	else
	    $SMARTY->assign('recovery',array());

        $SMARTY->assign('error', $SESSION->error);
        $SMARTY->assign('target','?'.$_SERVER['QUERY_STRING']);
        $SMARTY->display('login.html');
}

$DB->Destroy();

?>
