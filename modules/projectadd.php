<?php

/*
 *  iNET LMS 
 *
 *  (C) Copyright 2012-2015 iNET LMS Developers
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
 *  $Id$ projectadd.php 20150220 
 *
 *  Sylwester Kondracki
*/


include('project.xajax.php');
$layout['pagetitle'] = trans('Nowy projekt inwestycyjny');


$SMARTY->assign('divlist',$DB->getall('SELECT id, name,shortname FROM divisions WHERE status = 0;'));
$SMARTY->assign('states',$DB->getall('SELECT id,name FROM states;'));
$SMARTY->display('projectedit.html');

?>