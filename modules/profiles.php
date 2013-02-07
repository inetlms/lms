<?php

/*
 *  iNET LMS
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
 *  $Id: v 1.0.0 2013/01/17 22:01:35 Sylwester Kondracki Exp $
 */


$variable = ($_POST['variable'] ? $_POST['variable'] : NULL);
$content = ($_POST['content'] ? $_POST['content'] : NULL);
$action = ($_POST['action'] ? $_POST['action'] : NULL);

if (is_null($action) || is_null($variable) || empty($action) || empty($variable)) die;

switch ($action)
{

    case 'setprofile' : 
			$PROFILE->nowsave($variable,$content);
    break;

}

die;
?>