<?php

/*
 *  iNET LMS version 1.0.1
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
 *  Sylwester Kondracki
 */

class Profile
{


	var $autosave = FALSE;
	var $DB;
	var $AUTH;
	private $update = FALSE;
	private $settings = array();


	public function __construct(&$DB,&$AUTH) 
	{
		$this->DB =& $DB;
		$this->AUTH =& $AUTH;
		if ($dane = $this->DB->GetOne('SELECT profiles FROM users WHERE id = ? '.$this->DB->Limit(1).' ;',array($this->AUTH->id))) 
		{
			$this->settings = unserialize($dane);
		}
	}


	public function __destruct() 
	{
		if (!$this->autosave) 
			$this->saveProfiles();
	}


	public function saveProfiles() 
	{
		if ($this->update) 
		{
			$dane = serialize($this->settings);
			$this->DB->Execute('UPDATE users SET profiles = ? WHERE id = ? ;',array($dane,$this->AUTH->id));
			$this->update = FALSE;
		}
	}


	public function save($variable,$content) 
	{
		$this->settings[$variable] = $content;
		$this->update = TRUE;
		
		if ($this->autosave)
			$this->saveProfiles();
	}


	public function nowsave($variable,$content)
	{
		if ($tmp = $this->DB->GetOne('SELECT profiles FROM users WHERE id = ? LIMIT 1;',array($this->AUTH->id)))
		{
		    $tmp = unserialize($tmp);
		    $tmp[$variable] = $content;
		    if ($content == '') unset($tmp[$variable]);
		    $tmp = serialize($tmp);
		    $this->DB->Execute('UPDATE users SET profiles = ? WHERE id = ? ;',array($tmp,$this->AUTH->id));
		}
		else
		{
		    $tmp[$variable] = $content;
		    if ($content == '') unset($tmp[$variable]);
		    $tmp = serialize($tmp);
		    $this->DB->Execute('UPDATE users SET profiles = ? WHERE id = ? ;',array($tmp,$this->AUTH->id));
		}
	}


	public function get($variable,$def = NULL) 
	{
		if (isset($this->settings[$variable]))
		{
			return $this->settings[$variable];
		}
		elseif ($tmp = $this->DB->GetOne('SELECT profiles FROM users WHERE id = ? '.$this->DB->Limit(1).' ;',array($this->AUTH->id)))
		{
			$tmp = unserialize($tmp);
			if (isset($tmp[$variable]))
			{
				$this->settings[$variable] = $tmp[$variable];
				
				return $tmp[$variable];
			}
			else
			    return $def;
		    
		}
		else
			return $def;
	}


	public function restore($variable, &$content, $def = NULL) 
	{
		if ($result = $this->get($variable))
		{
		    $content = $result;
		}
		else 
		{
			$this->settings[$variable] = $def;
			$this->update = TRUE;
			$content = $def;
			
			if ($this->autosave) 
				$this->saveProfiles();
		}
	}


	public function remove($variable) 
	{
		if (isset($this->settings[$variable])) 
		{
			unset($this->settings[$variable]);
			$this->update = TRUE;
			
			if ($this->autosave) 
				$this->saveProfiles();
			
			return TRUE;
		}
		else 
			return FALSE;
	}


	public function nowremove($variable) 
	{
		if (isset($this->settings[$variable])) 
		{
			unset($this->settings[$variable]);
			$this->update = TRUE;
			$this->saveProfiles();
			return TRUE;
		}
		else 
			return FALSE;
	}


	public function is_set($variable) 
	{
		if (isset($this->settings[$variable]))
			return TRUE;
		else
			return FALSE;
	}

}

function get_profile($variable,$def = NULL)
{
    global $PROFILE,$DB;
    return $PROFILE->get($variable,$def);
}

?>