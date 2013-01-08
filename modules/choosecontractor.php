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

$layout['pagetitle'] = trans('Select contractor');

$p = isset($_GET['p']) ? $_GET['p'] : '';

if(!$p || $p == 'main')
	$SMARTY->assign('js', 'var targetfield1 = window.parent.targetfield1; var targetfield2 = window.parent.targetfield2;');

	$search = $_POST['searchcustomer'];

	$where_cust = 'AND ('.(intval($search) ? 'c.id = '.intval($search).' OR' : '')
			.'    ten LIKE '.$DB->Escape('%'.$search.'%')
			.' OR ssn LIKE '.$DB->Escape('%'.$search.'%')
			.' OR icn LIKE '.$DB->Escape('%'.$search.'%')
			.' OR rbe LIKE '.$DB->Escape('%'.$search.'%')
			.' OR regon LIKE '.$DB->Escape('%'.$search.'%')
			.' OR UPPER(email) LIKE UPPER('.$DB->Escape('%'.$search.'%').')'
			.' OR UPPER('.$DB->Concat('lastname',"' '",'c.name').') LIKE UPPER('.$DB->Escape('%'.$search.'%').')'
			.' OR UPPER(address) LIKE UPPER('.$DB->Escape('%'.$search.'%').')) ';

	$SMARTY->assign('searchcustomer', $search);

	$customerlist = $DB->GetAll('SELECT c.*, (SELECT SUM(value) FROM cash WHERE customerid = c.id) AS balance 
				FROM (SELECT DISTINCT c.id AS id, address, zip, city, email, ssn, 
				'.$DB->Concat('UPPER(c.lastname)',"' '",'c.name').' AS customername
				FROM contractorview c ' 

				.'WHERE deleted = 0 '
				.(isset($where_cust) ? $where_cust : '')

				.'ORDER BY customername LIMIT 15) c');

	$SMARTY->assign('customerlist', $customerlist);


$SMARTY->assign('contractor',TRUE);
$SMARTY->assign('part', $p);
$SMARTY->display('choosecontractor.html');

?>