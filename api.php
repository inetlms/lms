<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2012 LMS iNET Developers
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
 *  $Id: v 1.00 2013/01/11 13:01:35 Sylwester Kondracki Exp $
 */

ini_set('error_reporting', E_ALL&~E_NOTICE);

include('/etc/lms/init_lms.php');

$currenttime = time(); // akualny czas

define('LMS_API_SRV',true);

define('REQ_DIR',LIB_DIR.'/api_request');


$errors = array(
		0	=> 'no error',
		1	=> 'no auth',
		2	=> 'no request',
		3	=> 'API Server no DataBase Connect',
		);

$result = array(
		'error' => 0,
		'errors' => NULL,
		'result' => NULL
		);
		

function _die($res=NULL,$err=0,$name = '',$key='tajnehaslo')
{
    global $errors,$result;
    $result['error'] = $err;
    $result['errors'] = $errors[$err].' '.$name;
    if (is_array($res)) $res = serialize($res);
    $result['result'] = encode($res,$key);
    die(serialize($result));
}

function safe_b64encode($string) 
{
	    $data = base64_encode($string);
	    $data = str_replace( array('+','/','='), array('-','_',''), $data);
	    return $data;
}
    
 function safe_b64decode($string)
{
	    $data = str_replace( array('-','_'), array('+','/'), $string);
	    $mod4 = strlen($data) % 4;
	    if ( $mod4 ) 
	    {
		$data .= substr('====',$mod4);
	    }
	    return base64_decode($data);
}
	
function encode($value=NULL,$key = 'tajnehaslo')
{
	    if (is_null($value) || strlen($value)===0) return false;
	    $text = $value;
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
	    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$key,$text,MCRYPT_MODE_ECB,$iv);
	    return trim(safe_b64encode($crypttext)); 
}
	
	
function decode($value=NULL,$key='tajnehaslo')
{
	    if (is_null($value) || strlen($value)===0) return false;
	    $crypttext = safe_b64decode($value); 
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
	    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$crypttext,MCRYPT_MODE_ECB,$iv);
	    return trim($decrypttext);
}

if (!$DB) _die(null,3);

$login = (isset($_POST['login']) ? htmlspecialchars($_POST['login']) : NULL);

if (!isset($_POST['apiinetlms']) || is_null($login) || !is_string($login) || strlen($login)===0) _die(null,1);


include(LIB_DIR.'/api_auth_list.php');

if (!isset($API_AUTH[$login])) 
{
    _die(null,1);
} 
else 
{
    if (@unserialize($_POST['dane'])) $dane = unserialize($_POST['dane']); else _die(null,2); 

    if ($dane['passwd'] != $API_AUTH[$login]['passwd'] || $API_AUTH[$login]['domain'] != $dane['domain'] || !$API_AUTH[$login]['active']) 
	_die(null,1);

    if (!empty($API_AUTH[$login]['remoteip']) && $API_AUTH[$login]['remoteip'] != $_SERVER['REMOTE_ADDR'] ) 
	_die(null,1);
    
    if (!isset($dane['request']) || !is_array($dane['request']) || empty($dane['request'])) 
	_die(null,2);
    
    $request = $dane['request'];
    $count = count($request);
    $res = array();
    for ($i = 0; $i<$count; $i++) // odczyt requestów
    {
	$name = strtolower($request[$i]['name']);
	$file = REQ_DIR.'/'.$name.'.php';
	$options = $request[$i]['opt'];
	if (!file_exists($file)) $res[$i] = array();
	else
	{
	    $_result = NULL;
	    include($file);
	    $res[$i] = $_result;
	}
    }

    _die($res,0,'',$API_AUTH[$login]['secretkey']);
}

?>
