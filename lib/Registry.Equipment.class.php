<?php

/*
 *  iNET LMS - ewidencja sprzętu
 *
 *  (C) Copyright 2001-2014 iNET LMS Developers
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
 *  2014 Kondracki Sylwester
 */



class RegistryEquipment
{

    private $_version = '1.0.0';


    public function GetVersion() {
	
	return $this->_version;
	
    } // end GetVersion


    public function GetDictionaryEventList($active = false) {
	
	global $DB;
	return $DB->GetAll('SELECT e.*, (SELECT COUNT(c.id) FROM re_event c WHERE c.eventid = e.id) AS eventused 
	    FROM re_dictionary_event e WHERE e.deleted=0 '.($active ? ' AND e.active=1 ' : '').' ORDER BY e.name;');
	
    }


    public function CheckIssetDictionaryEvent($nazwa,$id = NULL) {
	
	global $DB;
	
	if (!$id) {
	    if ($DB->GetOne('SELECT 1 FROM re_dictionary_event WHERE deleted=0 AND UPPER(name) = ? '.$DB->Limit(1).' ;',array(strtoupper($nazwa))))
		return TRUE;
	    else
		return FALSE;
	} else {
	    if ($DB->GetOne('SELECT 1 FROM re_dictionary_event WHERE deleted=0 AND UPPER(name) = ? AND id != ? '.$DB->Limit(1).' ;',array(strtoupper($nazwa),$id)))
		return TRUE;
	    else
		return FALSE;
	}
	
    }


    public function addDictionaryEvent($form) {
	
	global $DB;
	
	$DB->Execute('INSERT INTO re_dictionary_event (name,description,licznik,koszt,paliwo,active,deleted) VALUES (?,?,?,?,?,?,?) ;',
	    array(
		($form['name'] ? $form['name'] : NULL),
		($form['description'] ? $form['description'] : NULL),
		($form['licznik'] ? 1 : 0),
		($form['koszt'] ? 1 : 0),
		($form['paliwo'] ? 1 : 0),
		1,0,
	    )
	);
	
	return $DB->GetLastInsertId('re_dictionary_event');
    } // end addDictionaryCarType


    public function updateDictionaryEvent($form) {
	
	global $DB;
	
	if (
	    $DB->Execute('UPDATE re_dictionary_event SET name = ?, description = ?, licznik=?, koszt=?, paliwo=? WHERE id = ? ;',
		array(
		    ($form['name'] ? $form['name'] : NULL),
		    ($form['description'] ? $form['description'] : NULL),
		    ($form['licznik'] ? 1 : 0),
		    ($form['koszt'] ? 1 : 0),
		    ($form['paliwo'] ? 1 : 0),
		    $form['id']
		)
	    )
	) {
	    return TRUE;
	} else {
	    return FALSE;
	}
	
    } // end updateDictionaryCarType


    public function getDictionaryEvent($id) {
	
	global $DB;
	
	return $DB->GetRow('SELECT id, name, description, licznik, koszt, paliwo FROM re_dictionary_event WHERE id = ? '.$DB->Limit(1).' ;',array($id));
	
    }


    public function deletedDictionaryEvent($id = NULL) {
	
	global $DB;
	
	if (!$id)
	    return FALSE;
//	
//	if ($DB->GetOne('SELECT 1 FROM re_cars WHERE dr_cartype = ? LIMIT 1;',array($id)))
//	    $DB->Execute('UPDATE re_dictionary_event SET deleted = 1 WHERE id = ? ;',array($id));
//	else
	    $DB->Execute('DELETE FROM re_dictionary_event WHERE id = ? ;',array($id));
	return TRUE;
    }



    public function GetDictionaryCarTypeList($active = false) {
	
	global $DB;
	return $DB->GetAll('SELECT t.*, 
			    (SELECT COUNT(id) FROM re_cars WHERE dr_cartype = t.id) AS caruse 
			    FROM re_dictionary_cartype t 
			    WHERE t.deleted = 0 '.($active ? ' AND t.active=1 ' : '').' ORDER BY t.name;');
	
    }


    public function CheckIssetDictionaryCarType($nazwa,$id = NULL) {
	
	global $DB;
	
	if (!$id) {
	    if ($DB->GetOne('SELECT 1 FROM re_dictionary_cartype WHERE deleted=0 AND UPPER(name) = ? '.$DB->Limit(1).' ;',array(strtoupper($nazwa))))
		return TRUE;
	    else
		return FALSE;
	} else {
	    if ($DB->GetOne('SELECT 1 FROM re_dictionary_cartype WHERE deleted=0 AND UPPER(name) = ? AND id != ? '.$DB->Limit(1).' ;',array(strtoupper($nazwa),$id)))
		return TRUE;
	    else
		return FALSE;
	}
	
    }


    public function addDictionaryCarType($form) {
	
	global $DB;
	
	$DB->Execute('INSERT INTO re_dictionary_cartype (name,description,active,deleted) VALUES (?,?,?,?) ;',
	    array(
		($form['name'] ? $form['name'] : NULL),
		($form['description'] ? $form['description'] : NULL),
		1,0,
	    )
	);
	
	return $DB->GetLastInsertId('re_dictionary_cartype');
    } // end addDictionaryCarType


    public function updateDictionaryCarType($form) {
	
	global $DB;
	
	if (
	    $DB->Execute('UPDATE re_dictionary_cartype SET name = ?, description = ? WHERE id = ? ;',
		array(
		    ($form['name'] ? $form['name'] : NULL),
		    ($form['description'] ? $form['description'] : NULL),
		    $form['id']
		)
	    )
	) {
	    return TRUE;
	} else {
	    return FALSE;
	}
	
    } // end updateDictionaryCarType


    public function getDictionaryCarType($id) {
	
	global $DB;
	
	return $DB->GetRow('SELECT id, name, description FROM re_dictionary_cartype WHERE id = ? '.$DB->Limit(1).' ;',array($id));
	
    }


    public function deletedDictionaryCarType($id = NULL) {
	
	global $DB;
	
	if (!$id)
	    return FALSE;
	
//	if ($DB->GetOne('SELECT 1 FROM re_cars WHERE dr_cartype = ? LIMIT 1;',array($id)))
//	    $DB->Execute('UPDATE re_dictionary_cartype SET deleted=1 WHERE id = ?;',array($id));
//	else
	    $DB->Execute('DELETE FROM re_dictionary_cartype WHERE id = ? ;',array($id));
	return TRUE;
    }


    public function checkissetnrrej($rej,$id=null)
    {
	global $DB;
	
	$rej = strtoupper(str_replace(' ','',$rej));
	$result = $DB->GetOne('SELECT 1 FROM re_cars WHERE dr_a = ? '.($id ? ' AND id != '.$id : '').' LIMIT 1;',array($rej));
	if ($result == 1)
	    return TRUE;
	else
	    return FALSE;
    }


    public function checkissetshortnamecar($shortname,$id = NULL)
    {
	global $DB;
	
//	$rej = strtoupper(str_replace(' ','',$rej));
	$result = $DB->GetOne('SELECT 1 FROM re_cars WHERE UPPER(shortname) = ? '.($id ? ' AND id != '.$id : '').' LIMIT 1;',array(strtoupper($shortname)));
	if ($result == 1)
	    return TRUE;
	else
	    return FALSE;
    }


    public function addcars($dane)
    {
	global $DB,$AUTH;
	
	$dane['dr_b'] = str_replace('-','/',$dane['dr_b']);
	$dane['dr_i'] = str_replace('-','/',$dane['dr_i']);
	$dane['dr_h'] = str_replace('-','/',$dane['dr_h']);
	$dane['datazakupu'] = str_replace('-','/',$dane['datazakupu']);
	
	if (!empty($dane['dr_b']) && !is_int($dane['dr_b'])) 
	    $dane['dr_b'] = strtotime($dane['dr_b']);
	
	if (!empty($dane['dr_i']) && !is_int($dane['dr_i']))
	    $dane['dr_i'] = strtotime($dane['dr_i']);
	
	if (!empty($dane['dr_h']) && !is_int($dane['dr_h']))
	    $dane['dr_h'] = strtotime($dane['dr_h']);
	
	if (!empty($dane['datazakupu']) && !is_int($dane['datazakupu']))
	    $dane['datazakupu'] = strtotime($dane['datazakupu']);
	
	$DB->Execute('INSERT INTO re_cars (dr_a, dr_b, dr_c11, dr_c12, dr_c13, dr_c21, dr_c22, dr_c23, dr_d1, dr_d2, dr_d3, dr_e, dr_f1, dr_f2, dr_f3, dr_g, dr_h,
			    dr_i, dr_j, dr_k, dr_l, dr_o1, dr_o2, dr_p1, dr_p2, dr_p3, dr_q, dr_s1, dr_s2, dr_wydajacy, dr_seriadr, dr_cartype, dr_przeznaczenie,
			    dr_rokprodukcji, dr_ladownosc, dr_nacisk, dr_kartapojazdu, dr_notes, cdate, cuser, mdate, muser, description, forma_nabycia, datazakupu, stanlicznika, zbiornik, shortname, status) 
			    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?) ;',
			    array(
			    ($dane['dr_a'] ? $dane['dr_a'] : NULL),
			    ($dane['dr_b'] ? $dane['dr_b'] : NULL),
			    ($dane['dr_c11'] ? $dane['dr_c11'] : NULL),
			    ($dane['dr_c12'] ? $dane['dr_c12'] : NULL),
			    ($dane['dr_c13'] ? $dane['dr_c13'] : NULL),
			    ($dane['dr_c21'] ? $dane['dr_c21'] : NULL),
			    ($dane['dr_c22'] ? $dane['dr_c22'] : NULL),
			    ($dane['dr_c23'] ? $dane['dr_c23'] : NULL),
			    ($dane['dr_d1'] ? $dane['dr_d1'] : NULL),
			    ($dane['dr_d2'] ? $dane['dr_d2'] : NULL),
			    ($dane['dr_d3'] ? $dane['dr_d3'] : NULL),
			    ($dane['dr_e'] ? $dane['dr_e'] : NULL),
			    ($dane['dr_f1'] ? $dane['dr_f1'] : NULL),
			    ($dane['dr_f2'] ? $dane['dr_f2'] : NULL),
			    ($dane['dr_f3'] ? $dane['dr_f3'] : NULL),
			    ($dane['dr_g'] ? $dane['dr_g'] : NULL),
			    ($dane['dr_h'] ? $dane['dr_h'] : NULL),
			    ($dane['dr_i'] ? $dane['dr_i'] : NULL),
			    ($dane['dr_j'] ? $dane['dr_j'] : NULL),
			    ($dane['dr_k'] ? $dane['dr_k'] : NULL),
			    ($dane['dr_l'] ? $dane['dr_l'] : NULL),
			    ($dane['dr_o1'] ? $dane['dr_o1'] : NULL),
			    ($dane['dr_o2'] ? $dane['dr_o2'] : NULL),
			    ($dane['dr_p1'] ? $dane['dr_p1'] : NULL),
			    ($dane['dr_p2'] ? $dane['dr_p2'] : NULL),
			    ($dane['dr_p3'] ? $dane['dr_p3'] : NULL),
			    ($dane['dr_q'] ? $dane['dr_q'] : NULL),
			    ($dane['dr_s1'] ? $dane['dr_s1'] : NULL),
			    ($dane['dr_s2'] ? $dane['dr_s2'] : NULL),
			    ($dane['dr_wydajacy'] ? $dane['dr_wydajacy'] : NULL),
			    ($dane['dr_seriadr'] ? $dane['dr_seriadr'] : NULL),
			    ($dane['dr_cartype'] ? $dane['dr_cartype'] : NULL),
			    ($dane['dr_przeznaczenie'] ? $dane['dr_przeznaczenie'] : NULL),
			    ($dane['dr_rokprodukcji'] ? $dane['dr_rokprodukcji'] : NULL),
			    ($dane['dr_ladownosc'] ? $dane['dr_ladownosc'] : NULL),
			    ($dane['dr_nacisk'] ? $dane['dr_nacisk'] : NULL),
			    ($dane['dr_kartapojazdu'] ? $dane['dr_kartapojazdu'] : NULL),
			    ($dane['dr_notes'] ? $dane['dr_notes'] : NULL),
			    time(),$AUTH->id,0,0,
			    ($dane['description'] ? $dane['description'] : NULL),
			    ($dane['forma_nabycia'] ? $dane['forma_nabycia'] : NULL),
			    ($dane['datazakupu'] ? $dane['datazakupu'] : NULL),
			    ($dane['stanlicznika'] ? $dane['stanlicznika'] : NULL),
			    ($dane['zbiornik'] ? $dane['zbiornik'] : NULL),
			    ($dnae['shortname'] ? $dane['shortname'] : NULL),
			    ($dane['status'] ? $dane['status'] : 0),
			    )
	);
	
	return $DB->GetLastInsertId('re_cars');
    }
    
    
    public function updatecars($dane)
    {
	global $DB,$AUTH;
	
	$dane['dr_b'] = str_replace('-','/',$dane['dr_b']);
	$dane['dr_i'] = str_replace('-','/',$dane['dr_i']);
	$dane['dr_h'] = str_replace('-','/',$dane['dr_h']);
	$dane['datazakupu'] = str_replace('-','/',$dane['datazakupu']);
	
	if (!empty($dane['dr_b']) && !is_int($dane['dr_b'])) 
	    $dane['dr_b'] = strtotime($dane['dr_b']);
	
	if (!empty($dane['dr_i']) && !is_int($dane['dr_i']))
	    $dane['dr_i'] = strtotime($dane['dr_i']);
	
	if (!empty($dane['dr_h']) && !is_int($dane['dr_h']))
	    $dane['dr_h'] = strtotime($dane['dr_h']);
	
	if (!empty($dane['datazakupu']) && !is_int($dane['datazakupu']))
	    $dane['datazakupu'] = strtotime($dane['datazakupu']);
	
	$DB->Execute('UPDATE re_cars SET dr_a=?, dr_b=?, dr_c11=?, dr_c12=?, dr_c13=?, dr_c21=?, dr_c22=?, dr_c23=?, dr_d1=?, dr_d2=?, dr_d3=?, dr_e=?, dr_f1=?, dr_f2=?, 
			    dr_f3=?, dr_g=?, dr_h=?, dr_i=?, dr_j=?, dr_k=?, dr_l=?, dr_o1=?, dr_o2=?, dr_p1=?, dr_p2=?, dr_p3=?, dr_q=?, dr_s1=?, dr_s2=?, dr_wydajacy=?, 
			    dr_seriadr=?, dr_cartype=?, dr_przeznaczenie=?, dr_rokprodukcji=?, dr_ladownosc=?, dr_nacisk=?, dr_kartapojazdu=?, dr_notes=?, 
			    mdate=?, muser=?, description=?, forma_nabycia=?, datazakupu=?, stanlicznika=?, zbiornik=?, shortname=?, status=? WHERE id = ?;', 
			    array(
			    ($dane['dr_a'] ? $dane['dr_a'] : NULL),
			    ($dane['dr_b'] ? $dane['dr_b'] : NULL),
			    ($dane['dr_c11'] ? $dane['dr_c11'] : NULL),
			    ($dane['dr_c12'] ? $dane['dr_c12'] : NULL),
			    ($dane['dr_c13'] ? $dane['dr_c13'] : NULL),
			    ($dane['dr_c21'] ? $dane['dr_c21'] : NULL),
			    ($dane['dr_c22'] ? $dane['dr_c22'] : NULL),
			    ($dane['dr_c23'] ? $dane['dr_c23'] : NULL),
			    ($dane['dr_d1'] ? $dane['dr_d1'] : NULL),
			    ($dane['dr_d2'] ? $dane['dr_d2'] : NULL),
			    ($dane['dr_d3'] ? $dane['dr_d3'] : NULL),
			    ($dane['dr_e'] ? $dane['dr_e'] : NULL),
			    ($dane['dr_f1'] ? $dane['dr_f1'] : NULL),
			    ($dane['dr_f2'] ? $dane['dr_f2'] : NULL),
			    ($dane['dr_f3'] ? $dane['dr_f3'] : NULL),
			    ($dane['dr_g'] ? $dane['dr_g'] : NULL),
			    ($dane['dr_h'] ? $dane['dr_h'] : NULL),
			    ($dane['dr_i'] ? $dane['dr_i'] : NULL),
			    ($dane['dr_j'] ? $dane['dr_j'] : NULL),
			    ($dane['dr_k'] ? $dane['dr_k'] : NULL),
			    ($dane['dr_l'] ? $dane['dr_l'] : NULL),
			    ($dane['dr_o1'] ? $dane['dr_o1'] : NULL),
			    ($dane['dr_o2'] ? $dane['dr_o2'] : NULL),
			    ($dane['dr_p1'] ? $dane['dr_p1'] : NULL),
			    ($dane['dr_p2'] ? $dane['dr_p2'] : NULL),
			    ($dane['dr_p3'] ? $dane['dr_p3'] : NULL),
			    ($dane['dr_q'] ? $dane['dr_q'] : NULL),
			    ($dane['dr_s1'] ? $dane['dr_s1'] : NULL),
			    ($dane['dr_s2'] ? $dane['dr_s2'] : NULL),
			    ($dane['dr_wydajacy'] ? $dane['dr_wydajacy'] : NULL),
			    ($dane['dr_seriadr'] ? $dane['dr_seriadr'] : NULL),
			    ($dane['dr_cartype'] ? $dane['dr_cartype'] : NULL),
			    ($dane['dr_przeznaczenie'] ? $dane['dr_przeznaczenie'] : NULL),
			    ($dane['dr_rokprodukcji'] ? $dane['dr_rokprodukcji'] : NULL),
			    ($dane['dr_ladownosc'] ? $dane['dr_ladownosc'] : NULL),
			    ($dane['dr_nacisk'] ? $dane['dr_nacisk'] : NULL),
			    ($dane['dr_kartapojazdu'] ? $dane['dr_kartapojazdu'] : NULL),
			    ($dane['dr_notes'] ? $dane['dr_notes'] : NULL),
			    time(),$AUTH->id,
			    ($dane['description'] ? $dane['description'] : NULL),
			    ($dane['forma_nabycia'] ? $dane['forma_nabycia'] : NULL),
			    ($dane['datazakupu'] ? $dane['datazakupu'] : NULL),
			    ($dane['stanlicznika'] ? $dane['stanlicznika'] : NULL),
			    ($dane['zbiornik'] ? $dane['zbiornik'] : NULL),
			    ($dane['shortname'] ? $dane['shortname'] : NULL),
			    ($dane['status'] ? $dane['status'] : 0),
			    $dane['id'],
			    )
	);
	
    }


    public function getcarlist()
    {
	global $DB;
	
	$result = $DB->GetAll('SELECT c.id, c.dr_a, c.dr_d1, c.dr_d3, c.dr_p1, c.dr_p2, c.dr_p3, c.dr_cartype, 
				(SELECT d.name FROM re_dictionary_cartype d WHERE d.id = c.dr_cartype) AS cartype_name,
				stanlicznika FROM re_cars c ORDER BY c.dr_d1;');
	
	return $result;
    }
    
    
    function deletecar($id)
    {
	global $DB,$LMS;
	$DB->BeginTrans();
	    $DB->Execute('DELETE FROM re_assurance WHERE idcar = ? ;',array($id));
	    $DB->Execute('DELETE FROM re_event WHERE idcar = ? ;',array($id));
	    $DB->Execute('DELETE FROM re_users WHERE idcar = ? ;',array($id));
	    $LMS->DeleteFileByOwner($section='re_cars',$ownerid=$id);
	    $DB->Execute('DELETE FROM re_cars WHERE id = ? ;',array($id));
	$DB->CommitTrans();
    }
    
    public function CheckIssetCar($id) 
    {
	
	global $DB;
	
	return ($DB->GetOne('SELECT 1 FROM re_cars WHERE id = ? '.$DB->Limit(1).' ;',array($id)) ? TRUE : FALSE);
    }


    public function getCar($id)
    {
	
	global $DB;
	
	$result = $DB->GetRow('SELECT c.*, d.name AS cartypename,
                                ROUND ( (
                                    SELECT ( SUM(c.litrow) / SUM( IFNULL (n.stanlicznika - c.stanlicznika,0 ) ) *100 ) AS spalanie
                                    FROM `re_event` AS c
                                    LEFT JOIN `re_event` AS n
                                    ON n.id = ( SELECT MIN(id) FROM `re_event` WHERE stanlicznika  > c.stanlicznika  AND idcar=?)
                                    WHERE c.idcar = ? AND (n.stanlicznika - c.stanlicznika) > 0
                                    ORDER BY c.datazdarzenia
                                ),2 ) AS spalanie
				    FROM re_cars c 
				    LEFT JOIN re_dictionary_cartype d ON (d.id = c.dr_cartype) 
				    WHERE c.id = ? '.$DB->Limit(1).' ;',
				    array($id,$id,$id)
	);
	
	if (!$result)
	    return FALSE;
	else
	    return $result;
    }

    public function getCarAverageConsumption($id)
    {
	
	global $DB;
	
	$result = $DB->GetRow('
                                SELECT ROUND ( SUM(c.litrow) / SUM( IFNULL (n.stanlicznika - c.stanlicznika,0 ) ) *100 , 2 ) AS spalanie
                                FROM `re_event` AS c
                                LEFT JOIN `re_event` AS n
                                ON n.id = ( SELECT MIN(id) FROM `re_event` WHERE stanlicznika  > c.stanlicznika  AND idcar=?)
                                WHERE c.idcar = ? AND (n.stanlicznika - c.stanlicznika) > 0
                                ORDER BY c.datazdarzenia
				    
				;',
				array($id,$id)
	);
	
	if (!$result)
	    return FALSE;
	else
	    return $result;
    }
    
     public function getCarFuleConsumptionData($id) {
         
         global $DB;
	
	$result = $DB->GetAll('
                                SELECT c.litrow AS litrow,c.stanlicznika AS stanlicznika,
                                IFNULL (n.stanlicznika - c.stanlicznika,0) AS przejechane
                                FROM `re_event` AS c
                                LEFT JOIN `re_event` AS n
                                ON n.id = ( SELECT MIN(id) FROM `re_event` WHERE stanlicznika  > c.stanlicznika  AND idcar=?)
                                WHERE c.idcar = ? AND (n.stanlicznika - c.stanlicznika) > 0
                                ORDER BY c.datazdarzenia 
                                ;',
				array($id,$id)
	);
	
	if (!$result)
	    return FALSE;
	else
	    return $result;
         
     }

    public function getuserscar($idc)
    {
	
	global $DB;
	
	$result = $DB->GetAll('SELECT r.*, u.login, u.name 
				    FROM re_users r 
				    LEFT JOIN users u ON (u.id = r.iduser)
				    WHERE r.idcar = ? 
				    ORDER BY r.dfrom ASC;',
				    array($idc)
	);
	
	return $result;
    }
    
    
    public function getusercar($id)
    {
	
	global $DB;
	
	return $DB->GetRow('SELECT id, iduser, dfrom, dto FROM re_users WHERE id = ? LIMIT 1;',array($id));
    }
    
    
    public function adduserscar($dane)
    {
	
	global $DB;
	
	$dane['dfrom'] = str_replace('-','/',$dane['dfrom']);
	$dane['dto'] = str_replace('-','/',$dane['dto']);
	
	if (empty($dane['dfrom']))
	    $dane['dfrom'] = 0;
	elseif (!is_int($dane['dfrom'])) {
	    $dane['dfrom'] = strtotime($dane['dfrom']);
	}
	
	if (empty($dane['dto']))
	    $dane['dto'] = 0;
	elseif (!is_int($dane['dto'])) {
	    $dane['dto'] = strtotime($dane['dto']);
	}
	
	$result = $DB->Execute('INSERT INTO re_users (iduser,idcar,idother,dfrom,dto) VALUES (?,?,?,?,?);',
				array(
					($dane['iduser'] ? $dane['iduser'] : 0),
					($dane['idcar'] ? $dane['idcar'] : 0),
					0,
					($dane['dfrom'] ? $dane['dfrom'] : 0),
					($dane['dto'] ? $dane['dto'] : 0),
				)
	);
	
	return $DB->GetLastInsertID('re_users');
    }
    
    
    public function updateuserscar($dane)
    {
	global $DB;
	
	$dane['dfrom'] = str_replace('-','/',$dane['dfrom']);
	$dane['dto'] = str_replace('-','/',$dane['dto']);
	
	if (empty($dane['dfrom']))
	    $dane['dfrom'] = 0;
	elseif (!is_int($dane['dfrom']))
	    $dane['dfrom'] = strtotime($dane['dfrom']);
	
	if (empty($dane['dto']))
	    $dane['dto'] = 0;
	elseif (!is_int($dane['dto']))
	    $dane['dto'] = strtotime($dane['dto']);
	
	$result = $DB->Execute('UPDATE re_users SET iduser = ?, dfrom = ?, dto = ? WHERE id = ?;',
			array(
				($dane['iduser'] ? $dane['iduser'] : 0),
				($dane['dfrom'] ? $dane['dfrom'] : 0),
				($dane['dto'] ? $dane['dto'] : 0),
				$dane['id']
			)
	);
    }


    public function delassurance($id) 
    {
	global $DB;
	$DB->Execute('DELETE FROM re_assurance WHERE id = ? ;',array($id));
    }


    public function getlistassurancecar($idc)
    {
	global $DB;
	$result = $DB->GetAll('SELECT * FROM re_assurance WHERE idcar = ? ORDER BY dfrom DESC;',array($idc));
	return $result;
    
    }


    public function getAssurance($id)
    {
	global $DB;
	$result = $DB->GetRow('SELECT * FROM re_assurance WHERE id = ? '.$DB->Limit().';',array($id));
	return $result;
    }


    public function addassurance($form)
    {
	global $DB,$AUTH;
	
	$form['dfrom'] = str_replace('-','/',$form['dfrom']);
	if (empty($form['dfrom'])) 
		$form['dfrom'] = 0;
	elseif (!is_int($form['dfrom'])) 
		$form['dfrom'] = strtotime($form['dfrom']);
	
	$form['dto'] = str_replace('-','/',$form['dto']);
	if (empty($form['dto'])) 
		$form['dto'] = 0;
	elseif (!is_int($form['dto'])) 
		$form['dto'] = strtotime($form['dto']);
	
	$form['rata1to'] = str_replace('-','/',$form['rata1to']);
	if (empty($form['rata1to'])) 
		$form['rata1to'] = 0;
	elseif (!is_int($form['rata1to'])) 
		$form['rata1to'] = strtotime($form['rata1to']);
	
	if (empty($form['rata2'])) {
	    $form['rata2to'] = '';
	}
	
	$form['rata2to'] = str_replace('-','/',$form['rata2to']);
	if (empty($form['rata2to'])) {
		$form['rata2to'] = 0;
		$form['rata2cash'] = 0;
		$form['rata2cashdate'] = '';
	}
	elseif (!is_int($form['rata2to'])) 
		$form['rata2to'] = strtotime($form['rata2to']);
	
	if (empty($form['rata1cash'])) {
	    $form['rata1cashdate'] = '';
	}
	
	if (empty($form['rata2cash'])) {
	    $form['rata2cashdate'] = '';
	}
	
	$form['rata1cashdate'] = str_replace('-','/',$form['rata1cashdate']);
	if (empty($form['rata1cashdate'])) 
		$form['rata1cashdate'] = 0;
	elseif (!is_int($form['rata1cashdate'])) 
		$form['rata1cashdate'] = strtotime($form['rata1cashdate']);
	
	$form['rata2cashdate'] = str_replace('-','/',$form['rata2cashdate']);
	if (empty($form['rata2cashdate'])) 
		$form['rata2cashdate'] = 0;
	elseif (!is_int($form['rata2cashdate'])) 
		$form['rata2cashdate'] = strtotime($form['rata2cashdate']);
	
	$form['datazawarcia'] = str_replace('-','/',$form['datazawarcia']);
	if (empty($form['datazawarcia'])) 
		$form['datazawarcia'] = 0;
	elseif (!is_int($form['datazawarcia'])) 
		$form['datazawarcia'] = strtotime($form['datazawarcia']);
	
	
	$DB->Execute('INSERT INTO re_assurance (idcar, idother, cuser, cdate, muser, mdate, dfrom, dto, 
		oc, ac, nw, assistance, nrpolisy, nrumowy, rata1, rata2, rata1to, rata2to, rata1cash, rata1cashdate, rata2cash, 
		rata2cashdate, ubezpieczyciel, asekurant,ubezpieczajacy, datazawarcia) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);',
		array(
		    ($form['idc'] ? $form['idc'] : 0),
		    ($form['idother'] ? $form['idother'] : 0),
		    $AUTH->id, time(), 0, 0,
		    ($form['dfrom'] ? $form['dfrom'] : 0), //data
		    ($form['dto'] ? $form['dto'] : 0), // data
		    ($form['oc'] ? 1 : 0),
		    ($form['ac'] ? 1 : 0),
		    ($form['nw'] ? 1 : 0),
		    ($form['assistance'] ? 1 : 0),
		    ($form['nrpolisy'] ? $form['nrpolisy'] : NULL),
		    ($form['nrumowy'] ? $form['nrumowy'] : NULL),
		    ($form['rata1'] ? $form['rata1'] : '0.00'),
		    ($form['rata2'] ? $form['rata2'] : NULL),
		    ($form['rata1to'] ? $form['rata1to'] : 0), // data
		    ($form['rata2to'] ? $form['rata2to'] : 0), // data
		    ($form['rata1cash'] ? 1 : 0),
		    ($form['rata1cashdate'] ? $form['rata1cashdate'] : 0), //data
		    ($form['rata2cash'] ? 1 : 0),
		    ($form['rata2cashdate'] ? $form['rata2cashdate'] : 0), //data
		    ($form['ubezpieczyciel'] ? $form['ubezpieczyciel'] : NULL),
		    ($form['asekurant'] ? $form['asekurant'] : NULL),
		    ($form['ubezpieczajacy'] ? $form['ubezpieczajacy'] : NULL),
		    ($form['datazawarcia'] ? $form['datazawarcia'] : 0), // data
		)
	);
	
//	return $DB->getlastinsertdb('re_assurance');
    }


    public function updateAssurance($form)
    {
	global $DB,$AUTH;
	
	$form['dfrom'] = str_replace('-','/',$form['dfrom']);
	if (empty($form['dfrom'])) 
		$form['dfrom'] = 0;
	elseif (!is_int($form['dfrom'])) 
		$form['dfrom'] = strtotime($form['dfrom']);
	
	$form['dto'] = str_replace('-','/',$form['dto']);
	if (empty($form['dto'])) 
		$form['dto'] = 0;
	elseif (!is_int($form['dto'])) 
		$form['dto'] = strtotime($form['dto']);
	
	$form['rata1to'] = str_replace('-','/',$form['rata1to']);
	if (empty($form['rata1to'])) 
		$form['rata1to'] = 0;
	elseif (!is_int($form['rata1to'])) 
		$form['rata1to'] = strtotime($form['rata1to']);
	
	if (empty($form['rata2'])) {
	    $form['rata2to'] = '';
	}
	
	$form['rata2to'] = str_replace('-','/',$form['rata2to']);
	if (empty($form['rata2to'])) {
		$form['rata2to'] = 0;
		$form['rata2cash'] = 0;
		$form['rata2cashdate'] = '';
	}
	elseif (!is_int($form['rata2to'])) 
		$form['rata2to'] = strtotime($form['rata2to']);
	
	if (empty($form['rata1cash'])) {
	    $form['rata1cashdate'] = '';
	}
	
	if (empty($form['rata2cash'])) {
	    $form['rata2cashdate'] = '';
	}
	
	$form['rata1cashdate'] = str_replace('-','/',$form['rata1cashdate']);
	if (empty($form['rata1cashdate'])) 
		$form['rata1cashdate'] = 0;
	elseif (!is_int($form['rata1cashdate'])) 
		$form['rata1cashdate'] = strtotime($form['rata1cashdate']);
	
	$form['rata2cashdate'] = str_replace('-','/',$form['rata2cashdate']);
	if (empty($form['rata2cashdate'])) 
		$form['rata2cashdate'] = 0;
	elseif (!is_int($form['rata2cashdate'])) 
		$form['rata2cashdate'] = strtotime($form['rata2cashdate']);
	
	$form['datazawarcia'] = str_replace('-','/',$form['datazawarcia']);
	if (empty($form['datazawarcia'])) 
		$form['datazawarcia'] = 0;
	elseif (!is_int($form['datazawarcia'])) 
		$form['datazawarcia'] = strtotime($form['datazawarcia']);
	
	
	
	$DB->Execute('UPDATE re_assurance SET idcar=?, idother=?, muser=?, mdate=?, dfrom=?, dto=?, 
		oc=?, ac=?, nw=?, assistance=?, nrpolisy=?, nrumowy=?, rata1=?, rata2=?, rata1to=?, rata2to=?, 
		rata1cash=?, rata1cashdate=?, rata2cash=?, rata2cashdate=?, ubezpieczyciel=?, 
		asekurant=?, ubezpieczajacy=?, datazawarcia=? WHERE id = ?;',
		array(
		    ($form['idc'] ? $form['idc'] : 0),
		    ($form['idother'] ? $form['idother'] : 0),
		    $AUTH->id, time(), 
		    ($form['dfrom'] ? $form['dfrom'] : 0), //data
		    ($form['dto'] ? $form['dto'] : 0), // data
		    ($form['oc'] ? 1 : 0),
		    ($form['ac'] ? 1 : 0),
		    ($form['nw'] ? 1 : 0),
		    ($form['assistance'] ? 1 : 0),
		    ($form['nrpolisy'] ? $form['nrpolisy'] : NULL),
		    ($form['nrumowy'] ? $form['nrumowy'] : NULL),
		    ($form['rata1'] ? $form['rata1'] : '0'),
		    ($form['rata2'] ? $form['rata2'] : NULL),
		    ($form['rata1to'] ? $form['rata1to'] : 0), // data
		    ($form['rata2to'] ? $form['rata2to'] : 0), // data
		    ($form['rata1cash'] ? 1 : 0),
		    ($form['rata1cashdate'] ? $form['rata1cashdate'] : 0), //data
		    ($form['rata2cash'] ? 1 : 0),
		    ($form['rata2cashdate'] ? $form['rata2cashdate'] : 0), //data
		    ($form['ubezpieczyciel'] ? $form['ubezpieczyciel'] : NULL),
		    ($form['asekurant'] ? $form['asekurant'] : NULL),
		    ($form['ubezpieczajacy'] ? $form['ubezpieczajacy'] : NULL),
		    ($form['datazawarcia'] ? $form['datazawarcia'] : 0), // data
		    $form['id']
		)
	);
	
//	return $DB->getlastinsertdb('re_assurance');
    } // end updateAssurance


    function AddEvent($form)
    {
	global $DB,$AUTH;
	
	$form['datazdarzenia'] = str_replace('-','/',$form['datazdarzenia']);
	if (empty($form['datazdarzenia'])) 
		$form['datazdarzenia'] = 0;
	elseif (!is_int($form['datazdarzenia'])) 
		$form['datazdarzenia'] = strtotime($form['datazdarzenia']);
	
	if (isset($form['koszt'])) 
	    $form['koszt'] = str_replace(',','.',$form['koszt']);
	
	$DB->Execute('INSERT INTO re_event (idcar, idother, cdate, cuser, mdate, muser, stanlicznika, datazdarzenia, litrow, koszt, name, description, eventid) 
		    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ;', array(
			($form['idc'] ? $form['idc'] : 0),
			($form['idother'] ? $form['idother'] : 0),
			time(),$AUTH->id,0,0,
			($form['stanlicznika'] ? $form['stanlicznika'] : 0),
			$form['datazdarzenia'],
			($form['litrow'] ? $form['litrow'] : 0),
			($form['koszt'] ? $form['koszt'] : '0.00'),
			($form['name'] ? $form['name'] : ''),
			($form['description'] ? $form['description'] : NULL),
			($form['eventid'] ? $form['eventid'] : 0),
			)
	);
    }
    
    
    function UpdateEvent($form)
    {
	global $DB,$AUTH;
	
	$form['datazdarzenia'] = str_replace('-','/',$form['datazdarzenia']);
	if (empty($form['datazdarzenia'])) 
		$form['datazdarzenia'] = 0;
	elseif (!is_int($form['datazdarzenia'])) 
		$form['datazdarzenia'] = strtotime($form['datazdarzenia']);
	
	if (isset($form['koszt'])) 
	    $form['koszt'] = str_replace(',','.',$form['koszt']);
	
	$DB->Execute('UPDATE re_event SET mdate=?, muser=?, stanlicznika=?, datazdarzenia=?, litrow=?, koszt=?, name=?, description=?, eventid=? WHERE id = ?;', 
	
		    array(
			time(),$AUTH->id,
			($form['stanlicznika'] ? $form['stanlicznika'] : 0),
			$form['datazdarzenia'],
			($form['litrow'] ? $form['litrow'] : 0),
			($form['koszt'] ? $form['koszt'] : '0.00'),
			($form['name'] ? $form['name'] : ''),
			($form['description'] ? $form['description'] : NULL),
			($form['eventid'] ? $form['eventid'] : 0),
			$form['id'],
			)
	);
    }
    
    
    function GetEventList($idc = NULL, $idother = NULL)
    {
	global $DB;
	
	$result = $DB->GetAll('SELECT e.id, e.cdate, e.cuser, e.mdate, e.muser, e.stanlicznika, e.datazdarzenia, e.litrow, e.koszt, e.name, e.eventid, e.description 
				FROM re_event e WHERE '
				.($idc ? ' idcar = '.$idc : ' idother = '.$idother)
				.' ');
	
	return $result;
    }
    
    
    function getEvent($id)
    {
	global $DB;
	
	$result = $DB->GetRow('SELECT * FROM re_event WHERE id = ? LIMIT 1;',array($id));
	return $result;
    }
    
    
    function deleteEvent($id)
    {
	global $DB;
	$DB->Execute('DELETE FROM re_event WHERE id = ? ;',array($id));
    }




} // end class

define('FUEL_DISEL',1);
define('FUEL_GAS',2);
define('FUEL_GAS_LPG',3);
define('FUEL_LPG',4);
define('FUEL_ELECTRIC',5);


$FUEL = array(
    FUEL_DISEL => 'olej napędowy',
    FUEL_GAS => 'benzyna',
    FUEL_GAS_LPG => 'benzyna + lpg',
    FUEL_LPG => 'lpg',
    FUEL_ELECTRIC => 'napęd elektryczny',
);


define('NABYCIE_UMOWA',1);
define('NABYCIE_LEASING',2);
define('NABYCIE_FAKTURA',3);
define('NABYCIE_RATY',4);
define('NABYCIE_UZYCZENIE',5);
define('NABYCIE_NAJEM',6);
define('NABYCIE_OTHER',20);

$NABYCIE = array(
    NABYCIE_UMOWA => 'umowa kupna sprzedaży',
    NABYCIE_LEASING => 'leasing',
    NABYCIE_FAKTURA => 'faktura VAT',
    NABYCIE_RATY => 'kredyt bankowy',
    NABYCIE_UZYCZENIE => 'użyczenie',
    NABYCIE_NAJEM => 'najem',
    NABYCIE_OTHER => 'inne',
);


define('STATUSCAR_EFFICIENTUSE',1);
define('STATUSCAR_EFFICIENTUNUSED',2);
define('STATUSCAT_SERVICE',3);
define('STATUSCAR_REPAIR',4);
define('STATUSCAR_SOLD',5);
define('STATUSCAR_SCRAPPED',6);
define('STATUSCAR_UNKNOWN',7);

$STATUSCAR = array(
    
    STATUSCAR_EFFICIENTUSE => 'sprawny, używany',
    STATUSCAR_EFFICIENTUNUSED => 'sprawny, nieużywany',
    STATUSCAT_SERVICE => 'w serwisie',
    STATUSCAR_REPAIR => 'w naprawie',
    STATUSCAR_SOLD => 'sprzedany',
    STATUSCAR_SCRAPPED => 'pojazd złomowany',
    STATUSCAR_UNKNOWN => 'nieznany',
);


if(isset($SMARTY))
{
	$SMARTY->assign('_FUEL',$FUEL);
	$SMARTY->assign('_NABYCIE',$NABYCIE);
	$SMARTY->assign('_STATUSCAR',$STATUSCAR);
}

$RE = new RegistryEquipment();
?>
