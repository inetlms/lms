<?php


function savecars($forms)
{
	global $DB,$RE,$SMARTY,$AUTH;
	$blad = false;
	$inf = 'Pole wymagane';
	$form = $forms['caredit'];
	$id = ($form['id'] ? $form['id'] : NULL);
	unset($form['id']);
	
	$form['dr_a'] = strtoupper(str_replace(' ','',$form['dr_a']));
	$obj = new xajaxResponse();
	
	
	foreach ($form as $key => $val) {
		$obj->script("removeClassId('".$key."','alerts');");
		$obj->assign($key."_alerts","innerHTML","");
	}
	
	if (empty($form['shortname'])) {
	    $obj->script("addClassId('shortname','alerts');");
	    $obj->assign("shortname_alerts","innerHTML","Nazwa skrócona jest wymagana od 5 do 32 znaków");
	    $blad = true;
	} elseif (strlen($form['shortname']) < 5 || strlen($form['shortname']) > 32 ) {
	    $obj->script("addClassId('shortname','alerts');");
	    $obj->assign("shortname_alerts","innerHTML","Nazwa musi mieć od 5 do 32 znaków");
	    $blad = true;
	} elseif ($RE->checkissetshortnamecar($form['shortname'],$id)) {
	    $obj->script("addClassId('shortname','alerts');");
	    $obj->assign("shortname_alerts","innerHTML","Podana nazwa jest już używana !!!");
	    $blad = true;
	}
	
	if (empty($form['status'])) {
	    $obj->script("addClassId('status','alerts');");
	    $obj->assign("status_alerts","innerHTML","Proszę podać status pojazdu");
	    $blad = true;
	}
	
	if (empty($form['dr_a'])) {
		$obj->script("addClassId('dr_a','alerts');");
		$obj->assign("dr_a_alerts","innerHTML",$inf);
		$blad = true;
	} elseif (strlen($form['dr_a']) != 7) {
		$obj->script("addClassId('dr_a','alerts');");
		$obj->assign("dr_a_alerts","innerHTML","Błędnie podano nr. rejestracyjny");
		$blad = true;
	} elseif ($RE->checkissetnrrej($form['dr_a'],$id)) {
		$obj->script("addClassId('dr_a','alerts');");
		$obj->assign("dr_a_alerts","innerHTML","Podany nr. rejestracyjny jest już bazie !!!");
		$blad = true;
	} else 
		$obj->assign("dr_a","value",$form['dr_a']);
	
	
	if (empty($form['dr_d1'])) {
		$obj->script("addClassId('dr_d1','alerts');");
		$obj->assign("dr_d1_alerts","innerHTML",$inf);
		$blad = true;
	} else {
		$form['dr_d1'] = strtoupper($form['dr_d1']);
		$obj->assign("dr_d1","value",$form['dr_d1']);
	}
	
	
	if (!empty($form['dr_d2'])) {
		$form['dr_d2'] = strtoupper($form['dr_d2']);
		$obj->assign("dr_d2","value",$form['dr_d2']);
	}
	
	
	if (empty($form['dr_d3'])) {
		$obj->script("addClassId('dr_d3','alerts');");
		$obj->assign("dr_d3_alerts","innerHTML",$inf);
		$blad = true;
	} else {
		$form['dr_d3'] = strtoupper($form['dr_d3']);
		$obj->assign("dr_d3","value",$form['dr_d3']);
	}
	
	
	if (empty($form['dr_e'])) {
		$obj->script("addClassId('dr_e','alerts');");
		$obj->assign("dr_e_alerts","innerHTML",$inf);
		$blad = true;
	} else {
		$form['dr_e'] = strtoupper($form['dr_e']);
		$obj->assign("dr_e","value",$form['dr_e']);
	}
	
	
	if (empty($form['dr_rokprodukcji'])) {
		$obj->script("addClassId('dr_rokprodukcji','alerts');");
		$obj->assign("dr_rokprodukcji_alerts","innerHTML",$inf);
		$blad = true;
	} elseif (strlen($form['dr_rokprodukcji']) !=4) {
		$obj->script("addClassId('dr_rokprodukcji','alerts');");
		$obj->assign("dr_rokprodukcji_alerts","innerHTML","Błędna data");
		$blad = true;
	} elseif (!intval($form['dr_rokprodukcji'])) {
		$obj->script("addClassId('dr_rokprodukcji','alerts');");
		$obj->assign("dr_rokprodukcji_alerts","innerHTML","Błędna data 12");
		$blad = true;
	}
	
	
	if (empty($form['dr_b'])) {
		$obj->script("addClassId('dr_b','alerts');");
		$obj->assign("dr_b_alerts","innerHTML",$inf);
		$blad = true;
	} else {
		$form['dr_b'] = str_replace('-','/',$form['dr_b']);
		$form['dr_b'] = str_replace('.','/',$form['dr_b']);
		$form['dr_b'] = str_replace(',','/',$form['dr_b']);
		$form['dr_b'] = str_replace(' ','/',$form['dr_b']);
		
		if (!check_date($form['dr_b'])) {
			$obj->script("addClassId('dr_b','alerts');");
			$obj->assign("dr_b_alerts","innerHTML","Błędnie podana data");
			$blad = true;
		}
		
		$obj->assign("dr_b","value",$form['dr_b']);
	}
	
	
	if (empty($form['dr_i'])) {
		$obj->script("addClassId('dr_i','alerts');");
		$obj->assign("dr_i_alerts","innerHTML",$inf);
		$blad = true;
	} else {
		$form['dr_i'] = str_replace('-','/',$form['dr_i']);
		$form['dr_i'] = str_replace('.','/',$form['dr_i']);
		$form['dr_i'] = str_replace(',','/',$form['dr_i']);
		$form['dr_i'] = str_replace(' ','/',$form['dr_i']);
		
		if (!check_date($form['dr_i'])) {
			$obj->script("addClassId('dr_i','alerts');");
			$obj->assign("dr_i_alerts","innerHTML","Błędnie podana data");
			$blad = true;
		}
		
		$obj->assign("dr_i","value",$form['dr_i']);
	}
	
	
	if (!empty($form['dr_h'])) {
		$form['dr_h'] = str_replace('-','/',$form['dr_h']);
		$form['dr_h'] = str_replace('.','/',$form['dr_h']);
		$form['dr_h'] = str_replace(',','/',$form['dr_h']);
		$form['dr_h'] = str_replace(' ','/',$form['dr_h']);
		
		if (!check_date($form['dr_h'])) {
			$obj->script("addClassId('dr_h','alerts');");
			$obj->assign("dr_h_alerts","innerHTML","Błędnie podana data");
			$blad = true;
		}
		
		$obj->assign("dr_h","value",$form['dr_h']);
	}
	
	
	if (!empty($form['dr_ladownosc'])) {
		$form['dr_ladownosc'] = round(str_replace(',','.',$form['dr_ladownosc']),0);
		
		if (!intval($form['dr_ladownosc'])) {
			$obj->script("addClassId('id_ladownosc','alets');");
			$obj->assign("dr_ladownosc_alerts","innerHTML","Błędnie podano ładowność");
			$blad = true;
		} else
			$obj->assign("dr_ladownosc","value",$form['dr_ladownosc']);
	}
	
	
	if (!empty($form['dr_nacisk'])) {
		$form['dr_nacisk'] = str_replace(',','.',$form['dr_nacisk']);
		
		if (!check_natural($form['dr_nacisk'])) {
			$obj->script("addClassId('id_nacisk','alets');");
			$obj->assign("dr_nacisk_alerts","innerHTML","Błędnie podano nacisk na oś");
			$blad = true;
		} else {
			$form['dr_nacisk'] = sprintf("%0.2f",$form['dr_nacisk']);
			$form['dr_nacisk'] = str_replace(',','.',$form['dr_nacisk']);
			$obj->assign("dr_nacisk","value",$form['dr_nacisk']);
		}
	}
	
	
	if (!empty($form['dr_f1'])) {
		$form['dr_f1'] = round(str_replace(',','.',$form['dr_f1']),0);
		
		if (!intval($form['dr_f1'])) {
			$obj->script("addClassid('dr_f1','alerts');");
			$obj->assign("dr_f1_alerts","innerHTML","Błędnie podano masę");
			$blad = true;
		} else
			$obj->assign("dr_f1","value",$form['dr_f1']);
	}
	
	
	if (!empty($form['dr_f2'])) {
		$form['dr_f2'] = round(str_replace(',','.',$form['dr_f2']),0);
		
		if (!intval($form['dr_f2'])) {
			$obj->script("addClassid('dr_f2','alerts');");
			$obj->assign("dr_f2_alerts","innerHTML","Błędnie podano masę");
			$blad = true;
		} else
			$obj->assign("dr_f2","value",$form['dr_f2']);
	}
	
	
	if (!empty($form['dr_f3'])) {
		$form['dr_f3'] = round(str_replace(',','.',$form['dr_f3']),0);
		
		if (!intval($form['dr_f3'])) {
			$obj->script("addClassid('dr_f3','alerts');");
			$obj->assign("dr_f3_alerts","innerHTML","Błędnie podano masę");
			$blad = true;
		} else
			$obj->assign("dr_f3","value",$form['dr_f3']);
	}
	
	
	if (!empty($form['dr_g'])) {
		$form['dr_g'] = round(str_replace(',','.',$form['dr_g']),0);
		
		if (!intval($form['dr_g'])) {
			$obj->script("addClassid('dr_g','alerts');");
			$obj->assign("dr_g_alerts","innerHTML","Błędnie podano masę");
			$blad = true;
		} else
			$obj->assign("dr_g","value",$form['dr_g']);
	}
	
	
	if (!empty($form['dr_l'])) {
		$form['dr_l'] = round(str_replace(',','.',$form['dr_l']),0);
		
		if (!intval($form['dr_l'])) {
			$obj->script("addClassid('dr_l','alerts');");
			$obj->assign("dr_l_alerts","innerHTML","Błędnie podano liczbę osi");
			$blad = true;
		} else
			$obj->assign("dr_l","value",$form['dr_l']);
	}
	
	
	if (!empty($form['dr_o1'])) {
		$form['dr_o1'] = round(str_replace(',','.',$form['dr_o1']),0);
		
		if (!intval($form['dr_o1'])) {
			$obj->script("addClassid('dr_o1','alerts');");
			$obj->assign("dr_o1_alerts","innerHTML","Błędnie podano masę");
			$blad = true;
		} else
			$obj->assign("dr_o1","value",$form['dr_o1']);
	}
	
	
	if (!empty($form['dr_o2'])) {
		$form['dr_o2'] = round(str_replace(',','.',$form['dr_o2']),0);
		
		if (!intval($form['dr_o2'])) {
			$obj->script("addClassid('dr_o2','alerts');");
			$obj->assign("dr_o2_alerts","innerHTML","Błędnie podano masę");
			$blad = true;
		} else
			$obj->assign("dr_o2","value",$form['dr_o2']);
	}
	
	
	if (!empty($form['dr_p1'])) {
		$form['dr_p1'] = str_replace(',','.',$form['dr_p1']);
		
		if (!check_natural($form['dr_p1'])) {
			$obj->script("addClassId('id_p1','alets');");
			$obj->assign("dr_p1_alerts","innerHTML","Błędnie podano pojemność silnika");
			$blad = true;
		} else {
			$form['dr_p1'] = sprintf("%0.2f",$form['dr_p1']);
			$form['dr_p1'] = str_replace(',','.',$form['dr_p1']);
			$obj->assign("dr_p1","value",$form['dr_p1']);
		}
	}
	
	
	if (!empty($form['dr_p2'])) {
		$form['dr_p2'] = str_replace(',','.',$form['dr_p2']);
		
		if (!check_natural($form['dr_p2'])) {
			$obj->script("addClassId('id_p2','alets');");
			$obj->assign("dr_p2_alerts","innerHTML","Błędnie podano moc silnika");
			$blad = true;
		} else {
			$form['dr_p2'] = sprintf("%0.2f",$form['dr_p2']);
			$form['dr_p2'] = str_replace(',','.',$form['dr_p2']);
			$obj->assign("dr_p2","value",$form['dr_p2']);
		}
	}
	
	
	if (!empty($form['dr_p1']) && empty($form['dr_p3'])) {
		$blad = true;
		$obj->script("addClassId('dr_p3','alerts');");
		$obj->assign("dr_p3_alerts","innerHTML","Proszę wybrać rodzaj paliwa");
	}
	
	
	if (empty($form['dr_wydajacy'])) {
		$obj->script("addClassId('dr_wydajacy','alerts');");
		$obj->assign("dr_wydajacy_alerts","innerHTML",$inf);
		$blad = true;
	}
	
	
	if (empty($form['dr_c11'])) {
		$obj->script("addClassId('dr_c11','alerts');");
		$obj->assign("dr_c11_alerts","innerHTML",$inf);
		$blad = true;
	}
	
	
	if (empty($form['dr_c12'])) {
		$obj->script("addClassId('dr_c12','alerts');");
		$obj->assign("dr_c12_alerts","innerHTML",$inf);
		$blad - true;
	} elseif (!check_regon($form['dr_c12']) && !check_ssn($form['dr_c12'])) {
		$obj->script("addClassId('dr_c12','alerts');");
		$obj->assign("dr_c12_alerts","innerHTML","Błędnie wprowadzone REGON lub PESEL");
		$blad - true;
	}
	
	
	if (empty($form['dr_c13'])) {
		$obj->script("addClassId('dr_c13','alerts');");
		$obj->assign("dr_c13_alerts","innerHTML",$inf);
		$blad = true;
	}
	
	
	if (empty($form['dr_c21'])) {
		$obj->script("addClassId('dr_c21','alerts');");
		$obj->assign("dr_c21_alerts","innerHTML",$inf);
		$blad = true;
	}
	
	
	if (empty($form['dr_c22'])) {
		$obj->script("addClassId('dr_c22','alerts');");
		$obj->assign("dr_c22_alerts","innerHTML",$inf);
		$blad = true;
	} elseif (!check_regon($form['dr_c22']) && !check_ssn($form['dr_c22'])) {
		$obj->script("addClassId('dr_c22','alerts');");
		$obj->assign("dr_c22_alerts","innerHTML","Błędnie wprowadzono REGON lub PESEL");
		$blad = true;
	}
	
	
	if (empty($form['dr_c23'])) {
		$obj->script("addClassId('dr_c23','alerts');");
		$obj->assign("dr_c23_alerts","innerHTML",$inf);
		$blad = true;
	}
	
	
	if (!empty($form['dr_s1'])) {
		$form['dr_s1'] = round(str_replace(',','.',$form['dr_s1']),0);
		
		if (!intval($form['dr_s1']) || $form['dr_s1'] < 1) {
			$obj->script("addClassid('dr_s1','alerts');");
			$obj->assign("dr_s1_alerts","innerHTML","Błędnie podano liczbę miejsc siedzących");
			$blad = true;
		} else
			$obj->assign("dr_s1","value",$form['dr_s1']);
	}
	
	
	if (!empty($form['dr_s2'])) {
		$form['dr_s2'] = round(str_replace(',','.',$form['dr_s2']),0);
		
		if (!intval($form['dr_s2'])) {
			$obj->script("addClassid('dr_s2','alerts');");
			$obj->assign("dr_s2_alerts","innerHTML","Błędnie podano liczbę miejsc stojących");
			$blad = true;
		} else
			$obj->assign("dr_s2","value",$form['dr_s2']);
	}
	
	
	if (empty($form['dr_kartapojazdu'])) {
		$obj->script("addClassId('dr_kartapojazdu','alerts');");
		$obj->assign("dr_kartapojazdu_alerts","innerHTML",$inf);
		$blad = true;
	} else {
		$form['dr_kartapojazdu'] = strtoupper($form['dr_kartapojazdu']);
		$obj->assign("dr_kartapojazdu","value",$form['dr_kartapojazdu']);
	}
	
	
	if (empty($form['dr_seriadr'])) {
		$obj->script("addClassId('dr_seriadr','alerts');");
		$obj->assign("dr_seriadr_alerts","innerHTML",$inf);
		$blad = true;
	} else {
		$form['dr_seriadr'] = strtoupper($form['dr_seriadr']);
		$obj->assign("dr_seriadr","value",$form['dr_seriadr']);
	}
	
	
	if (empty($form['forma_nabycia'])) {
		$blad = true;
		$obj->script("addClassId('forma_nabycia','alerts');");
		$obj->assign("forma_nabycia_alerts","innerHTML",$inf);
	}
	
	
	if (!empty($form['datazakupu'])) {
		$form['datazakupu'] = str_replace('-','/',$form['datazakupu']);
		$form['datazakupu'] = str_replace('.','/',$form['datazakupu']);
		$form['datazakupu'] = str_replace(',','/',$form['datazakupu']);
		$form['datazakupu'] = str_replace(' ','/',$form['datazakupu']);
		
		if (!check_date($form['datazakupu'])) {
			$obj->script("addClassId('datazakupu','alerts');");
			$obj->assign("datazakupu_alerts","innerHTML","Błędnie podana data");
			$blad = true;
		}
		
		$obj->assign("datazakupu","value",$form['datazakupu']);
	}
	
	
	if (!empty($form['stanlicznika'])) {
		$form['stanlicznika'] = round(str_replace(',','.',$form['stanlicznika']),0);
		
		if (!intval($form['stanlicznika'])) {
			$obj->script("addClassid('stanlicznika','alerts');");
			$obj->assign("stanlicznika_alerts","innerHTML","Błędnie podano stan licznika");
			$blad = true;
		} else
			$obj->assign("stanlicznika","value",$form['stanlicznika']);
	}
	
	
	if (!empty($form['dr_p1']) && empty($form['zbiornik'])) {
		$blad = true;
		$obj->script("addClassId('zbiornik','alerts');");
		$obj->assign("zbiornik_alerts","innerHTML",$inf);
	} elseif (!empty($form['zbiornik'])) {
		$form['zbiornik'] = round(str_replace(',','.',$form['zbiornik']),0);
		
		if (!intval($form['zbiornik'])) {
			$obj->script("addClassid('zbiornik','alerts');");
			$obj->assign("zbiornik_alerts","innerHTML","Błędnie podano pojemność zbiornika");
			$blad = true;
		} else
			$obj->assign("zbiornik","value",$form['zbiornik']);
	}
	
	
	if (!$blad) {
		
		if ($id) {
			$form['id'] = $id;
			$RE->updatecars($form);
		} else {
			$id = $RE->addcars($form);
		}
		
		if ($form['backto'] == 're_carinfo')
			$obj->script("self.location.href='?m=re_carinfo&tuck=base&idc=".$id."';");
		else
			$obj->script("self.location.href='?m=re_carlist';");
	}
	
	return $obj;
}

function saveassurance($forms)
{
	global $DB,$RE,$LMS,$AUTH;
	$obj = new xajaxResponse();
	
	$obj->script("removeClassId('id_nrpolisy','alerts');");
	$obj->assign("nrpolisy_alerts","innerHTML","");
	
	$obj->assign("ubezpieczenie_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_datazawarcia','alerts');");
	$obj->assign("datazawarcia_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_dfrom','alerts');");
	$obj->assign("dfrom_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_dto','alerts');");
	$obj->assign("dto_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_rata1','alerts');");
	$obj->assign("rata1_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_rata1to','alerts');");
	$obj->assign("rata1to_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_rata1cashdate','alerts');");
	$obj->assign("rata1cashdate_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_nrpolisy','alerts');");
	$obj->assign("nrpolisy_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_rata2','alerts');");
	$obj->assign("rata2_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_rata2to','alerts');");
	$obj->assign("rata2to_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_rata2cashdate','alerts');");
	$obj->assign("rata2cashdate_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_ubezpieczyciel','alerts');");
	$obj->assign("ubezpieczyciel_alerts","innerHTML","");
	
	$obj->script("removeClassId('id_ubezpieczajacy','alerts');");
	$obj->assign("ubezpieczajacy_alerts","innerHTML","");
	
	$form = $forms['assuranceedit'];
	$blad = false;
	
	
	if (empty($form['nrpolisy'])) {
		$obj->script("addClassId('id_nrpolisy','alerts');");
		$obj->assign("nrpolisy_alerts","innerHTML","Numer polisy jest wymagany");
		$blad = true;
	}
	
	
	if (!$form['oc'] && !$form['ac'] && !$form['nw'] && !$form['assistance']) {
		$obj->assign("ubezpieczenie_alerts","innerHTML","Proszę wybrać min. jeden rodzaj ubezpieczenia");
		$blad = true;
	}
	
	
	if (empty($form['datazawarcia'])) {
		$obj->script("addClassId('id_datazawarcia','alerts');");
		$obj->assign("datazawarcia_alerts","innerHTML","Proszę podać datę");
		$blad = true;
	} else {
		$form['datazawarcia'] = str_replace('-','/',$form['datazawarcia']);
		$form['datazawarcia'] = str_replace('.','/',$form['datazawarcia']);
		$form['datazawarcia'] = str_replace(',','/',$form['datazawarcia']);
		$form['datazawarcia'] = str_replace(' ','/',$form['datazawarcia']);
		$obj->assign("id_datazawarcia","value",$form['datazawarcia']);
		
		if (!check_date($form['datazawarcia'])) {
			$obj->script("addClassId('id_datazawarcia','alerts');");
			$obj->assign("datazawarcia_alerts","innerHTML","Błędnie podano datę");
			$blad = true;
		}
	}
	
	
	if (empty($form['dfrom'])) {
		$obj->script("addClassId('id_dfrom','alerts');");
		$obj->assign("dfrom_alerts","innerHTML","Proszę podać datę");
		$blad = true;
	} else {
		$form['dfrom'] = str_replace('-','/',$form['dfrom']);
		$form['dfrom'] = str_replace('.','/',$form['dfrom']);
		$form['dfrom'] = str_replace(',','/',$form['dfrom']);
		$form['dfrom'] = str_replace(' ','/',$form['dfrom']);
		$obj->assign("id_dfrom","value",$form['dfrom']);
		
		if (!check_date($form['dfrom'])) {
			$obj->script("addClassId('id_dfrom','alerts');");
			$obj->assign("dfrom_alerts","innerHTML","Błędnie podano datę");
			$blad = true;
		}
	}
	
	
	if (empty($form['dto'])) {
		$obj->script("addClassId('id_dto','alerts');");
		$obj->assign("dto_alerts","innerHTML","Proszę podać datę");
		$blad = true;
	} else {
		$form['dto'] = str_replace('-','/',$form['dto']);
		$form['dto'] = str_replace('.','/',$form['dto']);
		$form['dto'] = str_replace(',','/',$form['dto']);
		$form['dto'] = str_replace(' ','/',$form['dto']);
		$obj->assign("id_dto","value",$form['dto']);
		
		if (!check_date($form['dto'])) {
			$obj->script("addClassId('id_dto','alerts');");
			$obj->assign("dto_alerts","innerHTML","Błędnie podano datę");
			$blad = true;
		}
	}
	
	
	if (empty($form['rata1'])) {
		$blad = true;
		$obj->script("addClassId('id_rata1','alerts');");
		$obj->assign("rata1_alerts","innerHTML","Proszę podać składkę");
	} else {
		$form['rata1'] = str_replace(',','.',$form['rata1']);
		$obj->assign("id_rata1","value",$form['rata1']);
		
		if (!check_natural($form['rata1'])) {
			$blad = true;
			$obj->script("addClassId('id_rata1','alerts');");
			$obj->assign("rata1_alerts","innerHTML","Błednie podano składkę");
		}
	}
	
	
	if (empty($form['rata1to'])) {
		$obj->script("addClassId('id_rata1to','alerts');");
		$obj->assign("rata1to_alerts","innerHTML","Proszę podać datę");
		$blad = true;
	} else {
		$form['rata1to'] = str_replace('-','/',$form['rata1to']);
		$form['rata1to'] = str_replace('.','/',$form['rata1to']);
		$form['rata1to'] = str_replace(',','/',$form['rata1to']);
		$form['rata1to'] = str_replace(' ','/',$form['rata1to']);
		$obj->assign("id_rata1to","value",$form['rata1to']);
		
		if (!check_date($form['rata1to'])) {
			$obj->script("addClassId('id_rata1to','alerts');");
			$obj->assign("rata1to_alerts","innerHTML","Błędnie podano datę");
			$blad = true;
		}
	}
	
	
	if ($form['rata1cash']) {
		
		if (empty($form['rata1cashdate'])) {
			$obj->script("addClassId('id_rata1cashdate','alerts');");
			$obj->assign("rata1cashdate_alerts","innerHTML","Proszę podać datę");
			$blad = true;
		} else {
			$form['rata1cashdate'] = str_replace('-','/',$form['rata1cashdate']);
			$form['rata1cashdate'] = str_replace('.','/',$form['rata1cashdate']);
			$form['rata1cashdate'] = str_replace(',','/',$form['rata1cashdate']);
			$form['rata1cashdate'] = str_replace(' ','/',$form['rata1cashdate']);
			$obj->assign("id_rata1cashdate","value",$form['rata1cashdate']);
			
			if (!check_date($form['rata1cashdate'])) {
				$obj->script("addClassId('id_rata1cashdate','alerts');");
				$obj->assign("rata1cashdate_alerts","innerHTML","Błędnie podano datę");
				$blad = true;
			}
		}
	}
	
	
	if (!empty($form['rata2'])) {
		$form['rata2'] = str_replace(',','.',$form['rata2']);
		
		if (!check_natural($form['rata2'])) {
			$blad = true;
			$obj->script("addClassId('id_rata2','alerts');");
			$obj->assign("rata2_alerts","innerHTML","Błędnie podano składkę");
		} 
		
		if (empty($form['rata2to'])) {
			$obj->script("addClassId('id_rata2to','alerts');");
			$obj->assign("rata2to_alerts","innerHTML","Proszę podać datę");
			$blad = true;
		} else {
			$form['rata2to'] = str_replace('-','/',$form['rata2to']);
			$form['rata2to'] = str_replace('.','/',$form['rata2to']);
			$form['rata2to'] = str_replace(',','/',$form['rata2to']);
			$form['rata2to'] = str_replace(' ','/',$form['rata2to']);
			$obj->assign("id_rata2to","value",$form['rata2to']);
			
			if (!check_date($form['rata2to'])) {
				$obj->script("addClassId('id_rata2to','alerts');");
				$obj->assign("rata2to_alerts","innerHTML","Błędnie podano datę");
				$blad = true;
			}
		}
		
		if ($form['rata2cash']) {
			
			if (empty($form['rata2cashdate'])) {
				$obj->script("addClassId('id_rata2cashdate','alerts');");
				$obj->assign("rata2cashdate_alerts","innerHTML","Proszę podać datę");
				$blad = true;
			} else {
				$form['rata2cashdate'] = str_replace('-','/',$form['rata2cashdate']);
				$form['rata2cashdate'] = str_replace('.','/',$form['rata2cashdate']);
				$form['rata2cashdate'] = str_replace(',','/',$form['rata2cashdate']);
				$form['rata2cashdate'] = str_replace(' ','/',$form['rata2cashdate']);
				$obj->assign("id_rata2cashdate","value",$form['rata2cashdate']);
				
				if (!check_date($form['rata2cashdate'])) {
					$obj->script("addClassId('id_rata2cashdate','alerts');");
					$obj->assign("rata2cashdate_alerts","innerHTML","Błędnie podano datę");
					$blad = true;
				}
			}
		}
	} // end rata2 if isset
	
	if (empty($form['ubezpieczyciel'])) {
	    $blad = true;
	    $obj->script("addClassId('id_ubezpieczyciel','alerts');");
	    $obj->assign("ubezpieczyciel_alerts","innerHTML","Dane wymagane");
	}
	
	if (empty($form['ubezpieczajacy'])) {
	    $blad = true;
	    $obj->script("addClassId('id_ubezpieczajacy','alerts');");
	    $obj->assign("ubezpieczajacy_alerts","innerHTML","Dane wymagane");
	}
	
	
	if (!$blad) {
	    
	    if (isset($form['id']) && !empty($form['id'])) {
		// aktualizacja
		$RE->updateAssurance($form);
	    } else {
		// dodajemy rekord
		$RE->addassurance($form);
		     
	    }
	    
	    $obj->script("self.location.href='?m=".$form['backto']."&tuck=".$form['tuck']."&idc=".$form['idc']."';");
	}
	
	
	return $obj;
}


function saveevent($forms)
{
	global $DB,$RE;
	$blad = false;
	$obj = new xajaxResponse();
	
	$form = $forms['eventedit'];
	
	$obj->script("removeClassId('id_datazdarzenia','alerts');");
	$obj->script("removeClassId('id_name','alerts');");
	$obj->script("removeClassId('id_koszt','alerts');");
	$obj->script("removeClassId('id_stanlicznika','alerts');");
	$obj->script("removeClassId('id_litrow','alerts');");
	$obj->assign("id_datazdarzenia_alerts","innerHTML","");
	$obj->assign("id_name_alerts","innerHTML","");
	$obj->assign("id_koszt_alerts","innerHTML","");
	$obj->assign("id_stanlicznika_alerts","innerHTML","");
	$obj->assign("id_litrow_alerts","innerHTML","");
	
	if (empty($form['datazdarzenia'])) {
		$obj->script("addClassId('id_datazdarzenia','alerts');");
		$obj->assign("id_datazdarzenia_alerts","innerHTML","Proszę podać datę");
		$blad = true;
	} else {
		$form['datazdarzenia'] = str_replace('-','/',$form['datazdarzenia']);
		$form['datazdarzenia'] = str_replace('.','/',$form['datazdarzenia']);
		$form['datazdarzenia'] = str_replace(',','/',$form['datazdarzenia']);
		$form['datazdarzenia'] = str_replace(' ','/',$form['datazdarzenia']);
		$obj->assign("id_datazdarzenia","value",$form['datazdarzenia']);
		
		if (!check_date($form['datazdarzenia'])) {
			$obj->script("addClassId('id_datazdarzenia','alerts');");
			$obj->assign("id_datazdarzenia_alerts","innerHTML","Błędnie podano datę");
			$blad = true;
		}
	}
	
	if (empty($form['name'])) {
	    $obj->script("addClassId('id_name','alerts');");
	    if (get_conf('registryequipment.car_eventdic',0))
		$obj->assign("id_name_alerts","innerHTML","Proszę wybrać zdarzenie z listy");
	    else
		$obj->assign("id_name_alerts","innerHTML","Proszę podać nazwę zdarzenia");
	    $blad = true;
	}
	
	if ($form['eventid']) {
	    $eid = $form['eventid'];
	    $mus = $DB->GetRow('SELECT licznik, koszt, paliwo FROM re_dictionary_event WHERE id = ? LIMIT 1;',array($eid));
	} else
	    $mus['licznik'] = $mus['koszt'] = $mus['paliwo'] = FALSE;
	
	$form['koszt'] = str_replace(',','.',$form['koszt']);
		
	if (($mus['koszt'] && (empty($form['koszt']) || !check_natural($form['koszt']))) || 
	    (!empty($form['koszt']) && !check_natural($form['koszt']))) {
		$blad = true;
		$obj->script("addClassId('id_koszt','alerts');");
		$obj->assign("id_koszt_alerts","innerHTML","Brak lub błędnie podano Koszt");
	} else
		$obj->assign("id_koszt","value",$form['koszt']);
	
	if (($mus['licznik'] && (empty($form['stanlicznika']) || !intval($form['stanlicznika']))) ||
	    (!empty($form['licznik']) && !intval($form['licznik']))) {
		$blad = true;
		$obj->script("addClassId('id_stanlicznika','alerts');");
		$obj->assign("id_stanlicznika_alerts","innerHTML","Brak lub błędnie podano stan licznika");
	}
	
	if (!empty($form['litrow'])) 
	    $form['litrow'] = str_replace(',','.',$form['litrow']);
	
	if (($mus['paliwo'] && (empty($form['litrow']) || !check_natural($form['litrow']))) ||
	    (!empty($form['litrow']) && !check_natural($form['litrow']))) {
		$blad = true;
		$obj->script("addClassId('id_litrow','alerts');");
		$obj->assign("id_litrow_alerts","innerHTML","Brak lub błędnie podano ilość paliwa");
	} else
		$obj->assign("id_litrow","value",$form['litrow']);
	
	if (!$blad) {
	    
	    if ($form['id']) {
		$RE->UpdateEvent($form);
	    } else {
		$RE->AddEvent($form);
	    }
		
	    $obj->script("self.location.href='?m=re_carinfo&tuck=event&idc=".$form['idc']."';");
	}
    
    return $obj;
}

$LMS->RegisterXajaxFunction(
    array(
	'savecars',
	'saveassurance',
	'saveevent',
    )
);

?>