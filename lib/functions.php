<?php

/*
 *  iNET LMS
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
 *  $Id: functions.php,v 1.0 2015/01/03 11:01:28 Sylwester Kondracki Exp $
 */


function set_conf($name, $default = null)
{
    /*
	 dodajemy/ustawiamy zmienną konfiguracyjną tylko w tablicy $CONFIG
	 $name -> 'sekcja.zmienna' np. 'phpui.style'
	 $default -> wartość zmiennej, dla liczb podejemy w apostrofach np. '0'
    */
    global $CONFIG;
    list($section,$name) = explode('.',$name,2);
    $section = strtolower($section);
    $name = strtolower($name);
    $CONFIG[$section][$name] = $default;
}

function add_conf($name, $default = NULL, $description = '', $disabled = 0)
{
    // dodajemy/ustawiamy zmienną w bazie danych oraz aktualizujemy w tablicy $CONFIG
    global $CONFIG,$DB;
    list($section,$name) = explode('.',$name,2);
    $section = strtolower($section);
    $name = strtolower($name);
    
    if ($DB->GetOne('SELECT 1 FROM uiconfig WHERE UPPER(section)=? AND UPPER(var)=? '.$DB->Limit(1).';',array(strtoupper($section),strtoupper($name)))) {
	$DB->Execute("UPDATE uiconfig SET value=? "
	    .($description ? ",description='".$description."'" : "")
	    .", disabled=? WHERE section=? AND var=? ;",
	    array(
		($default ? $default : ''),
		($disabled ? 1 : 0),
		$section,
		$name,
	    )
	);
    } else {
	$DB->Execute('INSERT INTO uiconfig (section, var, value, description, disabled) VALUES (?, ?, ?, ?, ?);',
	    array(
		$section,
		$name,
		($default ? $default : ''),
		($description ? $description : ''),
		($disabled ? 1 : 0),
	    )
	);
    }
    
    $CONFIG[$section][$name] = $default;
}

?>
