<?php

/*
 *  FTP.class.php v.1.0.0
 *
 *  (C) Copyright 2012/10/11 by Sylwester Kondracki
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
 */


class FTP {
	private $_host;
	private $_port = 21;
	private $_pass;
	private $_connectid;
	private $_timeout = 90;
	private $_user;
	var $error = NULL;
	var $passive = false;
	var $ssl = false;
	var $system_type;

	function  __construct($host = NULL, $user = NULL, $pass = NULL, $port = 21, $timeout = 90) {
		$this->_host = $host;
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_port = (int)$port;
		$this->_timeout = (int)$timeout;
	}

	function  __destruct() {
		$this->close();
	}

	function connect($ssl = false) 
	{
		if (!is_bool($ssl)) $ssl = false;
		$this->ssl = $ssl;
		
		if(!$this->ssl) 
		{
			if(!$this->_connectid = ftp_connect($this->_host, $this->_port, $this->_timeout)) 
			{
				$this->error[] = "Błąd połączenia z serwerem FTP ".$this->_host;
				return false;
			}
		} 
		elseif(function_exists("ftp_ssl_connect")) 
		{
			if(!$this->_connectid = ftp_ssl_connect($this->_host, $this->_port, $this->_timeout)) 
			{
				$this->error = "Błąd połączenia z serwerem FTP ".$this->_host." (SSL)";
				return false;
			}
		} else {
			$this->error = "Błąd połączenia z serwerem FTP ".$this->_host." (nieznany typ połączenia)";
			return false;
		}
		if(ftp_login($this->_connectid, $this->_user, $this->_pass)) 
		{
			ftp_pasv($this->_connectid, (bool)$this->passive);
			$this->system_type = ftp_systype($this->_connectid);
			return true;
		} else {
			$this->error = "Błąd połączenia z serwerem FTP ".$this->_host." - błędny login";
			return false;
		}
	}

	function close() 
	{
		if($this->_connectid) 
		{
			ftp_close($this->_connectid);
			$this->_connectid = false;
		}
	}

	// zmieniamy katalog
	function chdir($dir = '', $dircreate = false) 
	{
	    if (empty($dir)) return false;
	    $result = @ftp_chdir($this->_connectid, $dir);
	    if ($result === false && $dircreate)
	    {
		$this->mkdir($dir);
		$result = @ftp_chdir($this->_connectid, $dir);
	    }
	    if ($sefult === false) return false; else return true;
	}

	// tworzenie katalogu
	function mkdir($dir = '') 
	{
	    if (empty($dir)) return false;
	    $result = @ftp_mkdir($this->_connectid, $dir);
	    if ($result === false) return false; else return true;
	}

	// kasowanie katalogu na ftp
	function rmdir($dir = '') 
	{
	    if (empty($dir)) return false;
	    $result = @ftp_rmdir($this->_connectid, $dir);
	    if ($result === true) return true; else return false;
	}

	// zmiana uprawnień
	function chmod($permissions = 0, $remote_file = NULL) 
	{
	    $result = @ftp_chmod($this->_connectid, $permissions, $remote_file);
	    if ($result === false)  return false;  else return true;
	}

	// kasowanie pliku na ftp
	function delete($remote_file = NULL) 
	{
		if(ftp_delete($this->_connectid, $remote_file)) return true; else return false;
	}

	// kasowanie katalogu i całej jego zawartosci razem z podkatalogami
	function delete_dir($dir = '')
	{
	    if (empty($dir)) return false;
	    
	    $list = $this->ls($dir);
	    if ($list && count($list) > 0)
	    {
		foreach ($list as $item)
		{
		    if (!$this->delete($item))
		    {
			$this->delete_dir($item);
		    }
		}
	    }
	    return $this->rmdir($dir);
	}

	//wysyłka pliku na serwer
	function upload($local_file = NULL, $remote_file = NULL, $mode = 'auto', $action = 'update') 
	{
	    if ($mode == 'auto') $mode = $this->_getmode($local_file);
	    $rem_size = $this->filesize($remote_file);
	    $loc_size = @filesize($local_file);
	
	    if (!$rem_size)  // jak pliku niema to wpisz
	    {
		    if (@ftp_put($this->_connectid, $remote_file, $local_file, $mode)) return true; return false;
	    }
	    elseif ( ($rem_size == $loc_size) && ($action == 'replace') )
	    {
		    $this->delete($remote_file);
		    if (@ftp_put($this->_connectid, $remote_file, $local_file, $mode)) return true; return false;
	    }
	    elseif ( ($rem_size != $loc_size) && ($action == 'replace' || $action == 'update') )
	    {
		    $this->delete($remote_file);
		    if (@ftp_put($this->_connectid, $remote_file, $local_file, $mode)) return true; return false;
	    }
	    return false;
	}

	// pobranie pliku z ftp na lokalny dysk
	function download($remote_file = NULL, $local_file = NULL, $mode = 'auto') 
	{
	    if ($mode == 'auto') $mode = $this->_getmode($remotel_file);
	    if (ftp_get($this->_connectid, $local_file, $remote_file, $mode)) return true; else return false;
	}

	// kopia lustrzana katalogu lokalnego
	// co ma zrobic jak plik juz jest na ftp
	// action -> skip - pomin, replace-zawsze zastap, update-aktualizuj jak inny rozmiar
	function mirror($locdir,$remdir,$action = 'update')
	{
	    if ($fp = @opendir($locdir))
	    {
		$this->chdir($remdir,true);
		
		while (FALSE != ($file = readdir($fp)))
		{
		    if (@is_dir($locdir.$file) && substr($file,0,1) != '.')
		    {
			$this->mirror($locdir.$file."/",$remdir.$file."/");
		    }
		    elseif (substr($file,0,1) != '.')
		    {
			$rem_size = $this->filesize($remdir."/".$file);
			$loc_size = filesize($locdir."/".$file);
			
			if (!$rem_size) // jak pliku niema to wpisz
			{
			    $this->upload($locdir.$file,$remdir.$file);
			}
			
			elseif ( ($rem_size == $loc_size) && ($action == 'replace') )
			{
				    $this->delete($remdir.$file);
				    $this->upload($locdir.$file,$remdir.$file);
			}
			
			elseif ( ($rem_size != $loc_size) && ($action == 'replace' || $action == 'update') )
			{
				    $this->delete($remdir.$file);
				    $this->upload($locdir.$file,$remdir.$file);
			}
		    
		    }
		}
		return true;
	    }
	    return false;
	}

	// sprawdza jakiego rodzaju ma być transfer ASCII czy BIN
	// zwraca stałą: FTP_ASCII lub FTP_BINARY
	function _getmode($filename = '')
	{
	    if (empty($filename)) return false;
	    
	    $ext = 'txt';
	    if ( strpos($filename,'.') === FALSE) $ext = 'txt';
	    else
	    {
		$x = explode('.',$filename);
		$ext = end($x);
	    }
	    $ext = strtolower($ext);
	    if ( in_array($ext,array('txt','text','php','php3','php4','php5','js','css','htm','html','phtml','shtml','xhtml','log','xml')))
		return FTP_ASCII;
	    else
		return FTP_BINARY;
	}

	 // lista plików
	function ls($directory = '.') 
	{
		$list = array();
		if ($list = ftp_nlist($this->_connectid, $directory)) return $list; else return array();
	}

	// zwraca aktualny katalog na serwerz FTP
	function pwd() 
	{
		return ftp_pwd($this->_connectid);
	}

	// zmiana nazwy pliku na FTP
	function rename($old_name = NULL, $new_name = NULL) 
	{
		if(ftp_rename($this->_connectid, $old_name, $new_name)) return true; else return false;
	}

	// wykonuje polecenie na ftp
	function exec($command = NULL)
	{
	    if (ftp_exec($this->_connectid, $command)) return true; else return false;
	}

	// zwraca rozmiar pliku
	function filesize($filename)
	{
	    $size = @ftp_size($this->_connectid,$filename);
	    if ($size && $size != -1 ) return $size; else return false;
	}
}

?>