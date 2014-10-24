<?php

/*
 *  iNET LMS
 *
 *  (C) Copyright 2001-2012 LMS Developers
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




$DB->BeginTrans();


$docs = $DB->GetAll('SELECT d.id, d.customerid, c.divisionid, v.id AS div_id, 
			    v.shortname AS div_shortname, v.name AS div_name, v.address AS div_address, v.city AS div_city, v.zip AS div_zip,
			    v.account AS div_account, v.inv_header AS div_inv_header, v.inv_footer AS div_inv_footer, v.inv_author AS div_inv_author,
			    v.inv_cplace AS div_inv_cplace, v.ten AS div_ten, v.regon AS div_regon, v.countryid AS div_countryid 
			    FROM documents d 
			    JOIN customers c ON (c.id = d.customerid) 
			    JOIN divisions v ON (v.id = c.divisionid) 
			    WHERE d.customerid !=0 AND d.divisionid = 0;');
	
	if (!empty($docs)) {
	    foreach ($docs as $doc) {
		$DB->Execute('UPDATE documents SET divisionid=?, div_name=?, div_address=?, div_city=?, div_zip=?, div_countryid=?, div_ten=?, div_regon=?,
			    div_account=?, div_inv_header=?, div_inv_footer=?, div_inv_author=?, div_inv_cplace=?, div_shortname=? 
			    WHERE id = ?;', 
			    array(
				($doc['div_id'] ? $doc['div_id'] : 0),
				($doc['div_name'] ? $doc['div_name'] : ''),
				($doc['div_address'] ? $doc['div_address'] : ''),
				($doc['div_city'] ? $doc['div_city'] : ''),
				($doc['div_zip'] ? $doc['div_zip'] : ''),
				($doc['div_countryid'] ? $doc['div_countryid'] : 0),
				($doc['div_ten'] ? $doc['div_ten'] : ''),
				($doc['div_regon'] ? $doc['div_regon'] : ''),
				($doc['div_account'] ? $doc['div_account'] : ''),
				($doc['div_inv_header'] ? $doc['div_inv_header'] : ''),
				($doc['div_inv_footer'] ? $doc['div_inv_footer'] : ''),
				($doc['div_inv_author'] ? $doc['div_inv_author'] : ''),
				($doc['div_inv_cplace'] ? $doc['div_inv_cplace'] : ''),
				($doc['div_shortname'] ? $doc['div_shortname'] : ''),
				$doc['div_id']
			    )
		);
	    }
	}

$divid = $DB->GetOne('SELECT MIN(id) AS id FROM divisions WHERE status = ? LIMIT 1;',array(0));

if ($divid)
{
	$division = $DB->GetRow('SELECT id, shortname, name, address, city, zip, account, inv_header, inv_footer, inv_author, inv_cplace, ten, regon, countryid 
			FROM divisions WHERE id = ? LIMIT 1;',array($divid));
	
	if ($division)
	$DB->Execute('UPDATE documents SET divisionid=?, div_name=?, div_address=?, div_city=?, div_zip=?, div_countryid=?, div_ten=?, div_regon=?,
			    div_account=?, div_inv_header=?, div_inv_footer=?, div_inv_author=?, div_inv_cplace=?, div_shortname=? 
			    WHERE divisionid = 0;', 
			    array(
				($division['id'] ? $division['id'] : 0),
				($division['name'] ? $division['name'] : ''),
				($division['address'] ? $division['address'] : ''),
				($division['city'] ? $division['city'] : ''),
				($division['zip'] ? $division['zip'] : ''),
				($division['countryid'] ? $division['countryid'] : 0),
				($division['ten'] ? $division['ten'] : ''),
				($division['regon'] ? $division['regon'] : ''),
				($division['account'] ? $division['account'] : ''),
				($division['inv_header'] ? $division['inv_header'] : ''),
				($division['inv_footer'] ? $division['inv_footer'] : ''),
				($division['inv_author'] ? $division['inv_author'] : ''),
				($division['inv_cplace'] ? $division['inv_cplace'] : ''),
				($division['shortname'] ? $division['shortname'] : ''),
			    )
		);
	
}

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2014102202', 'dbvex'));
$DB->CommitTrans();

?>