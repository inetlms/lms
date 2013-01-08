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
 *  $Id: v 1.00 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */


if (!isset($_GET['account'])) $SESSION->redirect('?m=hv_accountlist');

$cusid = intval($_GET['account']);

if (!isset($_GET['pstnid']) || !isset($_GET['ext'])) $SESSION->redirect('?m=hv_accountinfo&id='.$cusid);

$pstnid = $_GET['pstnid'];
$ext = $_GET['ext'];
$ok = ($DB->GetOne('SELECT id FROM hv_pstn WHERE id=? AND customerid=? AND extension=? LIMIT 1;',array($pstnid,$cusid,$ext)) ? TRUE : FALSE);

if ($ok) $HIPERUS->DeletePSTNCustomer($pstnid);

unset($pstnid);
unset($ext);

$SESSION->redirect('?m=hv_accountinfo&id='.$cusid);

?>