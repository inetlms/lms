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
 *  Sylwester Kondracki
 *  sylwester.kondracki@gmail.com
 *  gadu-gadu : 6164816
 *
*/

$layout['pagetitle'] = trans('Select customer');

$p = isset($_GET['p']) ? $_GET['p'] : '';

if(!$p || $p == 'main')
	$SMARTY->assign('js', 'var targetfield1 = window.parent.targetfield1; var targetfield2 = window.parent.targetfield2;');
//	$SMARTY->assign('js', 'var targetfield = window.parent.targetfield;');

if(isset($_POST['searchcustomer']) && $_POST['searchcustomer'])
{
	$search = $_POST['searchcustomer'];
	$sql_search = $DB->Escape("%$search%");
	$where_cust = 'AND ('.(intval($search) ? 'c.id = '.intval($search).' OR' : '')
			."    ten ?LIKE? $sql_search"
			." OR ssn ?LIKE? $sql_search"
			." OR icn ?LIKE? $sql_search"
			." OR rbe ?LIKE? $sql_search"
			." OR regon ?LIKE? $sql_search"
			." OR UPPER(email) ?LIKE? UPPER($sql_search)"
			." OR UPPER(".$DB->Concat('lastname',"' '",'c.name').") ?LIKE? UPPER($sql_search)"
			." OR UPPER(address) ?LIKE? UPPER($sql_search)"
			." OR UPPER(post_name) ?LIKE? UPPER($sql_search)"
			." OR UPPER(post_address) ?LIKE? UPPER($sql_search)) ";

	$SMARTY->assign('searchcustomer', $search);
}

if(isset($where_cust))
{
	$customerlist = $DB->GetAll('SELECT c.* FROM (
					    SELECT DISTINCT c.id AS id, address, zip, city, email, ssn, ten, regon, 
					    '.$DB->Concat('UPPER(c.lastname)',"' '",'c.name').' AS customername 
					    FROM customersview c ' 
					    .' WHERE deleted = 0 '
					    .(isset($where_cust) ? $where_cust : '')
					    .'ORDER BY customername LIMIT 20
					) c');
	
	for ($i=0; $i<sizeof($customerlist); $i++)
	    $customerlist[$i]['customername'] = str_replace('"','',str_replace("'","",$customerlist[$i]['customername']));
	
	$SMARTY->assign('customerlist', $customerlist);
}

$SMARTY->assign('part', $p);
$SMARTY->display('choosecustomersearch.html');

?>
