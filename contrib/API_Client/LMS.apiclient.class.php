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
 *  $Id: v 1.00 2013/01/11 22:01:35 Sylwester Kondracki Exp $
 */


class LMS_API_CLIENT
{
	var $version = '1.0.0';
	private $_login;
	private $_passwd;
	private $_domain;
	private $_secretkey;
	private $_url;
	private $_port;
	private $result = NULL;
	private $_error = false;
	private $_autosend = true;
	
	var $errors;
	private $_request = array();
	
	function LMS_API_CLIENT($login = NULL, $passwd = NULL, $domain = NULL, $key = NULL, $url = NULL, $port = NULL)
	{
	    $this->_login = $login;
	    $this->_passwd = $passwd;
	    $this->_domain = $domain;
	    $this->_secretkey = $key;
	    $this->_port = $port;
	    $this->_url = $url;
	}
	
	public function Request($req,$opt=NULL)
	{
	    if ($this->_autosend) $this->InitRequest();
	    
	    if (is_null($opt)) $opt = array();
	    $this->_request['request'][] = array(
					'name' =>$req,
					'opt' => $opt
				);
	    if ($this->_autosend) 
	    {
		$this->send();
		return $this->result[0];
	    }
	    else
		return (sizeof($this->_request['request'])-1);
	}
	
	public function GetResult()
	{
	    return $this->result;
	}
	
	public function SetAutoSend($auto = true)
	{
	    if (!is_bool($auto)) $auto = true;
	    $this->_autosend = $auto;
	}
	
	public function InitRequest()
	{
	    $this->_request = array();
	}
	
	public function Send()
	{
	    $return = NULL;
	    $users = array(
			    'passwd'	=> sha1($this->_passwd),
			    'domain'	=> $this->_domain
			);

	    $tablica['login'] = $this->_login;
	    $tablica['dane'] = @serialize(array_merge($users,$this->_request));
	    $tablica['apiinetlms'] = '1';
	    $c = curl_init();
	    curl_setopt($c,CURLOPT_URL,$this->_url);
	    if ($this->_port) curl_setopt($c,CURLOPT_PORT,$this->_port);
	    curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($c,CURLOPT_POST,1);
	    curl_setopt($c,CURLOPT_POSTFIELDS, $tablica);
	    curl_setopt($c,CURLOPT_USERAGENT,'MozillaXYZ/1.0');
	    curl_setopt($c,CURLOPT_TIMEOUT,60);
	    $info = curl_getinfo($c);
	    $result = curl_exec($c);
	    if (curl_error($c))
	    {
		$this->_error = true;
		$this->errors[] = curl_error($c);
		return false;
	    }
	    curl_close($c);
	    if (@unserialize($result))
	    {
		$return = unserialize($result); 
		$return['result'] = $this->decode($return['result']);
		if (@unserialize($return['result'])) $return['result'] = unserialize($return['result']);
		
	    }
	    else {
		$return = $this->decode($result);
	    }
	    $this->result = $return['result'];
	    return $return;
	}
	
	
	private function safe_b64encode($string) 
	{
	    $data = base64_encode($string);
	    $data = str_replace( array('+','/','='), array('-','_',''), $data);
	    return $data;
	}
    
	private function safe_b64decode($string)
	{
	    $data = str_replace( array('-','_'), array('+','/'), $string);
	    $mod4 = strlen($data) % 4;
	    if ( $mod4 ) 
	    {
		$data .= substr('====',$mod4);
	    }
	    return base64_decode($data);
	}
	
	public function encode($value=NULL)
	{
	    if (is_null($value) || strlen($value)===0) return false;
	    $text = $value;
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
	    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,$this->_secretkey,$text,MCRYPT_MODE_ECB,$iv);
	    return trim($this->safe_b64encode($crypttext)); 
	}
	
	public function decode($value=NULL)
	{
	    if (is_null($value) || strlen($value)===0) return false;
	    $crypttext = $this->safe_b64decode($value); 
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
	    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$this->_secretkey,$crypttext,MCRYPT_MODE_ECB,$iv);
	    return trim($decrypttext);
	}
	
}
?>