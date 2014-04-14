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
 *  $Id: v 1.00 2013/02/26 22:01:35 Sylwester Kondracki Exp $
 */


$layout['pagetitle'] = 'Osobisty Notatnik';
$layout['popup'] = true;


function change_filter($typ=NULL)
{
    global $DB,$PROFILE;
    
    $n  = $PROFILE->get('notes_n');
    $w  = $PROFILE->get('notes_w');
    $bw = $PROFILE->get('notes_bw');
    
    $obj = new xajaxResponse();
    
    if ($typ === 'n') {
	if ($n) {
	    $obj->script("document.getElementById('id_filtr_n').src = 'img/circle_grey.png';");
	    $PROFILE->nowsave('notes_n',0);
	} else {
	    $obj->script("document.getElementById('id_filtr_n').src = 'img/circle_yellow.png';");
	    $PROFILE->nowsave('notes_n',1);
	}
    } elseif ($typ === 'w') {
	if ($w) {
	    $obj->script("document.getElementById('id_filtr_w').src = 'img/circle_grey.png';");
	    $PROFILE->nowsave('notes_w',0);
	} else {
	    $obj->script("document.getElementById('id_filtr_w').src = 'img/circle_green.png';");
	    $PROFILE->nowsave('notes_w',1);
	}
    } elseif ($typ === 'bw') {
	if ($bw) {
	    $obj->script("document.getElementById('id_filtr_bw').src = 'img/circle_grey.png';");
	    $PROFILE->nowsave('notes_bw',0);
	} else {
	    $obj->script("document.getElementById('id_filtr_bw').src = 'img/circle_red.png';");
	    $PROFILE->nowsave('notes_bw',1);
	}
    }
    
    $obj->script("xajax_show_list_theme();");
    return $obj;
}


function show_list_theme()
{
    global $DB,$AUTH,$PROFILE;

    $obj = new xajaxResponse();
    
    $prio_n = $PROFILE->get('notes_n');
    $prio_w = $PROFILE->get('notes_w');
    $prio_bw = $PROFILE->get('notes_bw');
    
    if (!$prio_n) $obj->script("document.getElementById('id_filtr_n').src = 'img/circle_grey.png';");
    if (!$prio_w) $obj->script("document.getElementById('id_filtr_w').src = 'img/circle_grey.png';");
    if (!$prio_bw) $obj->script("document.getElementById('id_filtr_bw').src = 'img/circle_grey.png';");

    $return = '';
    $return = '<div style="overflow:auto;width:206px;height:335px;border: solid 1px #CCCCCC;">';
    $return .= '<table width="100%" cellpadding="3" cellspacing="2">';

    if ($lista = $DB->GetAll('SELECT id, prio, opis FROM notatnik WHERE prio IN (0'.($prio_n ? ',1' : '').($prio_w ? ',2' : '').($prio_bw ? ',3' : '').') AND iduser = ? ORDER BY id DESC;',array($AUTH->id))) {
	$kolor[0] = '#EEEEEE';
	$kolor[1] = '#DDDDDD';
	for ($i=0; $i<sizeof($lista);$i++)
	{
	    $return .= '<tr';
	    if ($i % 2) $return .=' class=""'; else $return .= ' class="lucid"';
	    $return .= ' id="id'.$lista[$i]['id'].'" onmouseover="addClass(this, \'highlight\')" onmouseout="removeClass(this, \'highlight\')">';
	    $return .= '<td width="99%" onclick="xajax_show_note(\''.$lista[$i]['id'].'\');" style="cursor:pointer;" onmouseover="popup(\''.$lista[$i]['opis'].'\');" onmouseout="pophide();">';
	    $return .= substr($lista[$i]['opis'],0,23);
	    if (strlen($lista[$i]['opis']) > 23) $return .= '...';
	    $return .= '</td>';
	    $return .= '<td width="1%" nowrap>';
	    
	    
	    $prio = intval($lista[$i]['prio']);
	    if ($prio === 3) $return .= '<img src="img/circle_red.png" border="0" style="cursor:pointer;" title="[ ZMIEŃ PRIO ]"';
	    elseif ($prio === 2) $return .= '<img src="img/circle_green.png" border="0" style="cursor:pointer;" title="[ ZMIEŃ PRIO ]"';
	    else $return .= '<img src="img/circle_yellow.png" border="0" style="cursor:pointer;" title="[ ZMIEŃ PRIO ]"';
	    $return .= ' id="id_prio_img_'.$lista[$i]['id'].'" onclick="xajax_change_prio(\''.$lista[$i]['id'].'\');"> ';
	    $return .= '<img src="img/edit.gif" border="0" style="cursor:pointer;" title="[ EDYTUJ ]" onclick="xajax_edit_note('.intval($lista[$i]['id']).');"> ';
	    $return .= '<img src="img/delete.gif" border="0" style="cursor:pointer;" title="[ USUŃ ]" onclick="deletenote('.intval($lista[$i]['id']).');"> ';
	    $return .= '</td>';
	    $return .= '</tr>';
	}
    }

    $return .= '</table></div>';
    $obj->assign("id_list","innerHTML",$return);
    return $obj;
    die;
}

function show_note($id)
{
    global $DB,$AUTH;
    usleep(50000);
    $obj = new xajaxResponse();
    
    if ($idl = $DB->GetAll('SELECT id FROM notatnik WHERE iduser = ? ;',array($AUTH->id))) {
	for ($j=0; $j<count($idl); $j++)
	    $obj->script("removeClassId('id".$idl[$j]['id']."','active');");
    }
    
    $dane = $DB->GetRow('SELECT opis, tresc FROM notatnik WHERE id = ? LIMIT 1;',array(intval($id)));
    
    $obj->assign("id_theme","innerHTML",'<b>'.$dane['opis'].'</b>');
    $obj->assign("id_description","innerHTML",'<div style="overflow:auto;width:417px;height:328px;border:solid 1px #CCCCCC;padding:4px;background-color:#FFFFFF;" ondblclick="xajax_edit_note(\''.$id.'\');">'.nl2br(base64_decode($dane['tresc'])).'</div>');
    $obj->script("addClassId('id".$id."','active');");
    $obj->assign("noteid","value",$id);
    
    return $obj;
}


function change_prio($id)
{
    global $DB,$PROFILE;
    
    $id = intval($id);
    $obj = new xajaxResponse();
    $prio = intval($DB->GetOne('SELECT prio FROM notatnik WHERE id = ? LIMIT 1;',array($id)));
    
    if ($prio === 1) {
	$DB->Execute('UPDATE notatnik SET prio = ? WHERE id = ? ;',array(2,$id));
	$obj->script("document.getElementById('id_prio_img_".$id."').src = 'img/circle_green.png';");
    } elseif ($prio === 2) {
	$DB->Execute('UPDATE notatnik SET prio = ? WHERE id = ? ;',array(3,$id));
	$obj->script("document.getElementById('id_prio_img_".$id."').src = 'img/circle_red.png';");
    } elseif ($prio === 3) {
	$DB->Execute('UPDATE notatnik SET prio = ? WHERE id = ? ;',array(1,$id));
	$obj->script("document.getElementById('id_prio_img_".$id."').src = 'img/circle_yellow.png';");
    }
    
    $obj->script("xajax_show_list_theme();");
    return $obj;
}

function add_theme()
{
    global $DB,$AUTH;
    $obj = new xajaxResponse();
    $value = sprintf('%03d',(intval($DB->GetOne('SELECT COUNT(id) FROM notatnik WHERE iduser=?;',array($AUTH->id)))+1)).' z '.date('Y/m/d H:i');
    $obj->assign("id_theme","innerHTML",'<b>'.$value.'</b>');
    $str = '<p style="margin:0;margin-bottom:0px;"><input type="text" required style="width:395px;" name="theme" id="idtheme" value="'.$value.'">';
    $str .= '&nbsp;&nbsp;<img src="img/save.gif" style="border:0;cursor:pointer;" title="[ ZAPISZ ]" onclick="savenote();"></p>';
    $str .= '<textarea required style="width:425px;height:310px;margin-top:10px;" name="description" id="iddescription"></textarea>';
    $obj->assign("id_description","innerHTML",$str);
    $obj->script("document.getElementById('iddescription').focus();");
//    $obj->script("xajax_go_wysiwyg();");
    return $obj;
}


function save_note($title=NULL,$value=NULL)
{
    global $DB,$AUTH;
        
    $obj = new xajaxResponse();
    if (!empty($title) && !empty($value))
    {
	if (empty($title)) $title = sprintf('%04d',(intval($DB->GetOne('SELECT MAX(id) FROM notatnik WHERE iduser=?;',array($AUTH->id)))+1)).' z '.date('Y/m/d H:i');
	$DB->Execute('INSERT INTO notatnik (data,iduser,prio,opis,tresc) VALUES (?,?,?,?,?) ;',array(time(),$AUTH->id,1,$title,base64_encode($value)));
	$id = $DB->GetLastInsertId('notatnik');
	$obj->script("xajax_show_list_theme();");
	$obj->script("xajax_show_note('".$id."');");
	
    }
    elseif (empty($title)) $obj->script("document.getElementById('idtheme').focus();");
    elseif (empty($value)) $obj->script("document.getElementById('iddescription').focus();");
    return $obj;
}

function edit_note($id)
{
    global $DB,$AUTH;
    $obj = new xajaxResponse();
    $id = intval($id);
    $dane = $DB->GetRow('SELECT id,opis,tresc FROM notatnik WHERE id = ? AND iduser = ? LIMIT 1 ;',array($id,$AUTH->id));
    $obj->assign("id_theme","innerHTML",'<b>'.$dane['opis'].'</b>');
    $str = '<p style="margin:0;margin-bottom:0px;"><input type="text" required style="width:395px;" name="theme" id="idtheme" value="'.$dane['opis'].'" onkeyup="javascript:document.getElementById(\'id_theme\').innerHTML = document.getElementById(\'idtheme\').value;">';
    $str .= '&nbsp;&nbsp;<img src="img/save.gif" style="border:0;cursor:pointer;" title="[ ZAPISZ ]" onclick="updatenote();"></p>';
    $str .= '<textarea style="width:425px;height:310px;margin-top:10px;"  name="description" id="iddescription">'.base64_decode($dane['tresc']).'</textarea>';
    $obj->assign("id_description","innerHTML",$str);
    $obj->assign("noteid","value",$id);
//    $obj->script("xajax_go_wysiwyg();");
    
    return $obj;
}

function go_wysiwyg()
{
    usleep(50000);
    $obj = new xajaxResponse();
    $obj->script('$(document).ready(function(){$("#iddescription").cleditor({width:422,height:"100%",fonts:"Tahoma,Verdana,Arial,Helvetica",sizes: "1,2,3,4,5",useCSS: false,bodyStyle:"margin:4px;font:8pt Tahoma,Verdna,Arial;cursor:text;"})[0].focus();});');
    return $obj;
}

function update_note($idek=NULL,$title=NULL,$value=NULL)
{
    global $DB,$AUTH;
    $obj = new xajaxResponse();
    $idek = intval($idek);
    if (!empty($idek) && !empty($title) && !empty($value))
    {
	$DB->execute('UPDATE notatnik SET opis = ?, tresc = ? WHERE id = ? AND iduser = ? ',array($title,base64_encode($value),$idek,$AUTH->id));
	$obj->script("xajax_show_list_theme();");
	$obj->script("xajax_show_note('".$idek."');");
    }
    elseif (empty($title)) $obj->script("document.getElementById('idtheme').focus();");
    elseif (empty($value)) $obj->script("document.getElementById('iddescription').focus();");
    else {
	$obj->script("xajax_show_list_theme();");
	$obj->script("xajax_show_note('0');");
    }
    return $obj;
}

function delete_note($id)
{
    global $DB, $AUTH;
    
    $obj = new xajaxResponse();
    
    $id = intval($id);
    $DB->Execute('DELETE FROM notatnik WHERE id = ? AND iduser = ? ;',array($id,$AUTH->id));
    $obj->script("xajax_show_list_theme();");
    $obj->script("xajax_add_theme();");

    return $obj;
}

function start_note()
{
    global $DB,$AUTH,$PROFILE;
    $obj = new xajaxResponse();
    
    if (!$PROFILE->check_exists_key('notes_n'))
    {
	$prio_n = $prio_w = $prio_bw = 1;
	$PROFILE->save('notes_n',1);
	$PROFILE->save('notes_w',1);
	$PROFILE->save('notes_bw',1);
	$PROFILE->saveProfiles();
    }
    else
    {
	$prio_n = $PROFILE->get('notes_n');
	$prio_w = $PROFILE->get('notes_w');
	$prio_bw = $PROFILE->get('notes_bw');
    }
    
    $obj->script("xajax_show_list_theme();");
    if ($maxid = $DB->GetOne('SELECT MAX(id) FROM notatnik WHERE prio IN (0'.($prio_n ? ',1' : '').($prio_w ? ',2' : '').($prio_bw ? ',3' : '').') AND iduser = ? ;',array($AUTH->id)))
	$obj->script("xajax_show_note('".$maxid."');");
    else
	$obj->script("xajax_add_theme();");
    
    return $obj;
}

$LMS->InitXajax();
$LMS->RegisterXajaxFunction(array('show_list_theme','show_note','add_theme','save_note','change_prio','change_filter','delete_note','edit_note','update_note','start_note','go_wysiwyg'));
$SMARTY->assign('xajax',$LMS->RunXajax());
$SMARTY->display('notatnik.html');

?>