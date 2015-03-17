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
 *  Sylwester Kondracki Exp $
*/



class UKE {
    var $DB;
    var $LMS;


    function UKE(&$DB,&$LMS)
    {
	$this->DB = &$DB;
	$this->LMS = &$LMS;
    }


    function getSIISlist()
    {
	$result = $this->DB->GetAll('SELECT * FROM uke WHERE report_type=? ORDER BY reportyear DESC;',array('SIIS'));
	return $result;
    }


    function getRaportInfo($id) 
    {
	$result = $this->DB->GetRow('SELECT * FROM uke WHERE id = ? LIMIT 1;',array($id));
	return $result;
    }


    function add_siis($dane)
    {
	
	$this->DB->Execute('INSERT INTO uke (report_type, divisionid, reportyear, divname, ten, regon, rpt, rjst, krs, 
			    states, districts, boroughs, city, zip, street, 
			    location_city, location_street, location_house, location_flat, kod_terc, kod_simc, kod_ulic,
			    url, email, accept1, accept2, accept3, accept4, accept5, accept6,
			    contact_name, contact_lastname, contact_phone, contact_fax, contact_email, closed, passwd, description, version, revision)
			    VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);',
			    array(
				'SIIS',
				($dane['divisionid'] ? $dane['divisionid'] : 0),
				($dane['reportyear'] ? $dane['reportyear'] : 0),
				($dane['divname'] ? $dane['divname'] : NULL),
				($dane['ten'] ? $dane['ten'] : NULL),
				($dane['regon'] ? $dane['regon'] : NULL),
				($dane['rpt'] ? $dane['rpt'] : NULL),
				($dane['rjst'] ? $dane['rjst'] : NULL),
				($dane['krs'] ? $dane['krs'] : NULL),
				($dane['states'] ? $dane['states'] : NULL),
				($dane['districts'] ? $dane['districts'] : NULL),
				($dane['boroughs'] ? $dane['boroughs'] : NULL),
				($dane['city'] ? $dane['city'] : NULL),
				($dane['zip'] ? $dane['zip'] : NULL),
				($dane['street'] ? $dane['street'] : NULL),
				($dane['location_city'] ? $dane['location_city'] : NULL),
				($dane['location_street'] ? $dane['location_street'] : NULL),
				($dane['location_house'] ? $dane['location_house'] : NULL),
				($dane['location_flat'] ? $dane['location_flat'] : NULL),
				($dane['kod_terc'] ? $dane['kod_terc'] : 0),
				($dane['kod_simc'] ? $dane['kod_simc'] : 0),
				($dane['kod_ulic'] ? $dane['kod_ulic'] : 0),
				($dane['url'] ? $dane['url'] : NULL),
				($dane['email'] ? $dane['email'] : NULL),
				($dane['accept1'] ? $dane['accept1'] : 0),
				($dane['accept2'] ? $dane['accept2'] : 0),
				($dane['accept3'] ? $dane['accept3'] : 0),
				($dane['accept4'] ? $dane['accept4'] : 0),
				($dane['accept5'] ? $dane['accept5'] : 0),
				($dane['accept6'] ? $dane['accept6'] : 0),
				($dane['contact_name'] ? $dane['contact_name'] : NULL),
				($dane['contact_lastname'] ? $dane['contact_lastname'] : NULL),
				($dane['contact_phone'] ? $dane['contact_phone'] : NULL),
				($dane['contact_fax'] ? $dane['contact_fax'] : NULL),
				($dane['contact_email'] ? $dane['contact_email'] : NULL),
				0,NULL,
				($dane['description'] ? $dane['description'] : NULL),
				SIIS_VERSION,SIIS_REVISION
			    )
	);
	
	return $this->DB->GetLastInsertId('uke');
	
    }


    function update_siis($dane)
    {
	
	$this->DB->Execute('UPDATE uke SET divisionid=?, divname=?, reportyear=?, ten=?, regon=?, rpt=?, rjst=?, krs=?, 
			    states=?, districts=?, boroughs=?, city=?, zip=?, street=?, 
			    location_city=?, location_street=?, location_house=?, location_flat=?, kod_terc=?, kod_simc=?, kod_ulic=?,
			    url=?, email=?, accept1=?, accept2=?, accept3=?, accept4=?, accept5=?, accept6=?,
			    contact_name=?, contact_lastname=?, contact_phone=?, contact_fax=?, contact_email=?, description=? 
			    WHERE id = ?;',
			    array(
				($dane['divisionid'] ? $dane['divisionid'] : 0),
				($dane['divname'] ? $dane['divname'] : NULL),
				($dane['reportyear'] ? $dane['reportyear'] : 0),
				($dane['ten'] ? $dane['ten'] : NULL),
				($dane['regon'] ? $dane['regon'] : NULL),
				($dane['rpt'] ? $dane['rpt'] : NULL),
				($dane['rjst'] ? $dane['rjst'] : NULL),
				($dane['krs'] ? $dane['krs'] : NULL),
				($dane['states'] ? $dane['states'] : NULL),
				($dane['districts'] ? $dane['districts'] : NULL),
				($dane['boroughs'] ? $dane['boroughs'] : NULL),
				($dane['city'] ? $dane['city'] : NULL),
				($dane['zip'] ? $dane['zip'] : NULL),
				($dane['street'] ? $dane['street'] : NULL),
				($dane['location_city'] ? $dane['location_city'] : NULL),
				($dane['location_street'] ? $dane['location_street'] : NULL),
				($dane['location_house'] ? $dane['location_house'] : NULL),
				($dane['location_flat'] ? $dane['location_flat'] : NULL),
				($dane['kod_terc'] ? $dane['kod_terc'] : 0),
				($dane['kod_simc'] ? $dane['kod_simc'] : 0),
				($dane['kod_ulic'] ? $dane['kod_ulic'] : 0),
				($dane['url'] ? $dane['url'] : NULL),
				($dane['email'] ? $dane['email'] : NULL),
				($dane['accept1'] ? $dane['accept1'] : 0),
				($dane['accept2'] ? $dane['accept2'] : 0),
				($dane['accept3'] ? $dane['accept3'] : 0),
				($dane['accept4'] ? $dane['accept4'] : 0),
				($dane['accept5'] ? $dane['accept5'] : 0),
				($dane['accept6'] ? $dane['accept6'] : 0),
				($dane['contact_name'] ? $dane['contact_name'] : NULL),
				($dane['contact_lastname'] ? $dane['contact_lastname'] : NULL),
				($dane['contact_phone'] ? $dane['contact_phone'] : NULL),
				($dane['contact_fax'] ? $dane['contact_fax'] : NULL),
				($dane['contact_email'] ? $dane['contact_email'] : NULL),
				($dane['description'] ? $dane['description'] : NULL),
				$dane['id'],
			    )
	);
	
    }


    function add_siis_data_po($dane)
    {
	$this->DB->Execute('INSERT INTO uke_data (rapid, mark, markid, useraport, data) VALUE (?,?,?,?,?) ;',
	    array(
		$dane['rapid'],
		$dane['mark'],
		$dane['markid'],
		1,
		$dane['data'],
	    )
	);
	
	return $this->DB->GetLastInsertId('uke_data');
    }
    
    function add_siis_data($dane) {
	$this->add_siis_data_po($dane);
    }
    
    
    function update_siis_data_po($dane)
    {
	$this->DB->Execute('UPDATE uke_data SET markid=?, data=? WHERE id=?;',array($dane['markid'],$dane['data'],$dane['id']));
    }


    function getPOList($idr)
    {
	$result = array();
	
	if ($tmp = $this->DB->GetAll('SELECT id,useraport,data FROM uke_data WHERE rapid = ? AND mark = ? ;',array($idr,'PO')))
	{
	    $count = sizeof($tmp);
	    for ($i=0;$i<$count;$i++) {
		$result[$i] = unserialize($tmp[$i]['data']);
		$result[$i]['useraport'] = $tmp[$i]['useraport'];
		$result[$i]['id'] = $tmp[$i]['id'];
	    }
	}
	
	return $result;
    }
    
    
    function getPOinfo($id)
    {
	$result = array();
	
	if ($tmp = $this->DB->GetRow('SELECT * FROM uke_data WHERE id = ? ;',array($id)))
	{
		$result = unserialize($tmp['data']);
		$result['useraport'] = $tmp['useraport'];
		$result['id'] = $tmp['id'];
		$result['rapid'] = $tmp['rapid'];
		$result['mark'] = $tmp['mark'];
		$result['markid'] = $tmp['markid'];
	}
	
	return $result;
    }
    
    function getPROJlist($idr)
    {
	$result = array();
	$tmp = $this->DB->getAll('SELECT id,markid,useraport,data FROM uke_data WHERE rapid = ? AND mark = ?;',array($idr,'PROJ'));
	
	if ($tmp) {
	    $count = sizeof($tmp);
	    for ($i=0; $i<$count; $i++) {
		$result[$i] = unserialize($tmp[$i]['data']);
		$result[$i]['idr'] = $tmp[$i]['rapid'];
		$result[$i]['useraport'] = $tmp[$i]['useraport'];
		$result[$i]['id'] = $tmp[$i]['id'];
		$result[$i]['markid'] = $tmp[$i]['markid'];
	    }
	}
	
	return $result;
    }
    
    
    function getWWList($idr)
    {
	$result = array();
	
	$tmp = $this->DB->GetAll('SELECT id,markid,useraport,data FROM uke_data WHERE rapid = ? AND mark = ? ;',array($idr,'WW'));
	
	if ($tmp)
	{
	    $count = sizeof($tmp);
	    for ($i=0;$i<$count;$i++) {
		$result[$i] = unserialize($tmp[$i]['data']);
		$result[$i]['idw'] = $result[$i]['id'];
		$result[$i]['useraport'] = $tmp[$i]['useraport'];
		$result[$i]['id'] = $tmp[$i]['id'];
		$result[$i]['markid'] = $tmp[$i]['markid'];
	    }
	}
	
	return $result;
    }
    
    
    function getWOList($idr)
    {
	$result = array();
	
	$tmp = $this->DB->GetAll('SELECT id,markid,useraport,data FROM uke_data WHERE rapid = ? AND mark = ? ;',array($idr,'WO'));
	
	if ($tmp)
	{
	    $count = sizeof($tmp);
	    for ($i=0;$i<$count;$i++) {
		$result[$i] = unserialize($tmp[$i]['data']);
		$result[$i]['idw'] = $result[$i]['id'];
		$result[$i]['useraport'] = $tmp[$i]['useraport'];
		$result[$i]['id'] = $tmp[$i]['id'];
		$result[$i]['markid'] = $tmp[$i]['markid'];
	    }
	}
	
	return $result;
    }
    
    
    
    function getINTList($idr)
    {
	$result = array();
	
	$tmp = $this->DB->GetAll('SELECT id,markid,useraport,data FROM uke_data WHERE rapid = ? AND mark = ? ;',array($idr,'INT'));
	
	if ($tmp)
	{
	    $count = sizeof($tmp);
	    for ($i=0;$i<$count;$i++) {
		$result[$i] = unserialize($tmp[$i]['data']);
		$result[$i]['idw'] = $result[$i]['id'];
		$result[$i]['useraport'] = $tmp[$i]['useraport'];
		$result[$i]['id'] = $tmp[$i]['id'];
		$result[$i]['markid'] = $tmp[$i]['markid'];
	    }
	}
	
	return $result;
    }
    
    
    function getLKList($idr)
    {
	$result = array();
	
	$tmp = $this->DB->GetAll('SELECT id,markid,useraport,data FROM uke_data WHERE rapid = ? AND mark = ? ;',array($idr,'LK'));
	
	if ($tmp)
	{
	    $count = sizeof($tmp);
	    for ($i=0;$i<$count;$i++) {
		$result[$i] = unserialize($tmp[$i]['data']);
		$result[$i]['idw'] = $result[$i]['id'];
		$result[$i]['useraport'] = $tmp[$i]['useraport'];
		$result[$i]['id'] = $tmp[$i]['id'];
		$result[$i]['markid'] = $tmp[$i]['markid'];
	    }
	}
	
	return $result;
    }
    
    
    function getLBList($idr)
    {
	$result = array();
	
	$tmp = $this->DB->GetAll('SELECT id,markid,useraport,data FROM uke_data WHERE rapid = ? AND mark = ? ;',array($idr,'LB'));
	
	if ($tmp)
	{
	    $count = sizeof($tmp);
	    for ($i=0;$i<$count;$i++) {
		$result[$i] = unserialize($tmp[$i]['data']);
		$result[$i]['idw'] = $result[$i]['id'];
		$result[$i]['useraport'] = $tmp[$i]['useraport'];
		$result[$i]['id'] = $tmp[$i]['id'];
		$result[$i]['markid'] = $tmp[$i]['markid'];
	    }
	}
	
	return $result;
    }
    
    
    function getPOLList($idr)
    {
	$result = array();
	
	$tmp = $this->DB->GetAll('SELECT id,markid,useraport,data FROM uke_data WHERE rapid = ? AND mark = ? ;',array($idr,'POL'));
	
	if ($tmp)
	{
	    $count = sizeof($tmp);
	    for ($i=0;$i<$count;$i++) {
		$result[$i] = unserialize($tmp[$i]['data']);
		$result[$i]['idw'] = $result[$i]['id'];
		$result[$i]['useraport'] = $tmp[$i]['useraport'];
		$result[$i]['id'] = $tmp[$i]['id'];
		$result[$i]['markid'] = $tmp[$i]['markid'];
	    }
	}
	
	return $result;
    }
    
    
    function getZASList($idr)
    {
	$result = array();
	
	$tmp = $this->DB->GetAll('SELECT id,markid,useraport,data FROM uke_data WHERE rapid = ? AND mark = ? ;',array($idr,'ZAS'));
	
	if ($tmp)
	{
	    $count = sizeof($tmp);
	    for ($i=0;$i<$count;$i++) {
		$result[$i] = unserialize($tmp[$i]['data']);
		$result[$i]['idw'] = $result[$i]['id'];
		$result[$i]['useraport'] = $tmp[$i]['useraport'];
		$result[$i]['id'] = $tmp[$i]['id'];
		$result[$i]['markid'] = $tmp[$i]['markid'];
	    }
	}
	
	return $result;
    }
    
    

} // end class

define('REPORT_YEAR','2014'); // domyślny rok za który jest raporcik
define('REPORT_DATE_RANGE',strtotime(REPORT_YEAR.'/12/31 23:59:59'));
define('SIIS_VERSION','5');
define('SIIS_REVISION','2.5');

$UKE = new UKE($DB,$LMS);

?>