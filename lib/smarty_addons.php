<?php

/*
 *  iNET LMS version 1.0.3
 *
 *  (C) Copyright 2001-2012 LMS Developers
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
 *  $Id:  2013/02/18 11:01:28 Sylwester Kondracki Exp $
 *
 */
 
 
function _smarty_function_title($params, &$template)
{
		if (isset($params['value']) && !empty($params['value'])) $value = trans($params['value']); else $value = NULL;
		if (isset($params['link']) && !empty($params['link'])) $link = $params['link']; else $link = NULL;
		if (isset($params['help']) && !empty($params['help'])) $help = $params['help']; else $help = NULL;
		if (isset($params['color']) && !empty($params['color'])) $color = $params['color']; else $color = NULL;
		$small = (isset($params['small']) ? true : false);
		$width = 100;
		$return = '';
		
		if ($link) $width -= 1;
		if ($help) $widh -= 1;
		if ($value) {
			if ($small) $return .= '<div class="title title_small" style="margin-top:10px;">';
			else $return .= '<div class="title" style="margin-top:12px;">';
			$return .= '<table width="100%" cellpadding="0" cellspacing="0">';
			$return .= '<tr>';
			
			if (!is_null($color))
			    $value = '<font style="color:'.$color.';">'.$value.'</font>';
			
			if ($small) $return .= '<td width="'.$width.'%" class="title_sm title_sm_small" style="vertical-align:middle;" align="left">'.$value.'</td>';
			else $return .= '<td width="'.$width.'%" class="title_sm" style="vertical-align:middle;" align="left">'.$value.'</td>';
			
			if ($link)
				$return .= '<td width="1%" nowrap style="font-weight:normal;">'.$link.'</td>';
//			if ($help)
//					$return .= '<td width="1%" nowrap style="padding-left:6px;"><img src="img/help.png" width="20" height="20" onclick="help_popup(\''.$help.'\');" title="[ POMOC ]" style="cursor:pointer;border:0;"></td>';
			$return .= '</tr></table>';
			$return .= '</div>';
		}
		
		return $return;
}


function _smarty_function_help($params, &$template)
{
		if (isset($params['key']) && !empty($params['key'])) $key = $params['key']; else $val = NULL;
		$return = '';
		if ($key) {
		    
		    $return = '<img src="img/help.png" width="16" height="16" onclick="help_popup(\''.$key.'\');" title="[ POMOC ]" style="cursor:pointer;border:0;">';
		    
		}
		return $return;
}


function _smarty_block_box($params, $content, &$template, &$repeat)
{
	if (empty($content)) return NULL;
	
	$boxtitleclass = $boxcontentclass = '';
	$title 	= (isset($params['title']) ? $params['title'] : '');
	$boxwidth 	= (isset($params['width']) ? $params['width'] : '185px');
	$boxheight 	= (isset($params['height']) ? $params['height'] : '');
	$boxalign 	= (isset($params['align']) ? $params['align'] : '');
	$boxvalign 	= (isset($params['valign']) ? $params['valign'] : '');
	$boxshadow 	= (isset($params['shadow']) ? true : false);
	$boxid 		= (isset($params['id']) ? $params['id'] : NULL);
	$boxradius 	= (isset($params['radius']) ? true : false);
	$boxclass 	= (isset($params['class']) ? $params['class'] : '');
	$boxstyle 	= (isset($params['style']) ? $params['style'] : '');
	$boxlink 	= (isset($params['link']) ? $params['link'] : '');
	$contentstyle 	= (isset($params['contentstyle']) ? $params['contentstyle'] : '');
	
	$template->assignGlobal('boxtitle',$title);
	$template->assignGlobal('boxlink',$boxlink);
	$template->assignGlobal('boxcontent',$content);
	$template->assignGlobal('boxwidth',$boxwidth);
	$template->assignGlobal('boxheight',$boxheight);
	$template->assignGlobal('boxid',$boxid);
	$template->assignGlobal('boxalign',$boxalign);
	$template->assignGlobal('boxvalign',$boxvalign);
	$template->assignGlobal('boxradius',$boxradius);
	$template->assignGlobal('boxshadow',$boxshadow);
	$template->assignGlobal('boxclass',$boxclass);
	$template->assignGlobal('boxstyle',$boxstyle);
	$template->assignGlobal('contentstyle',$contentstyle);
	
	return $template->fetch('box.html');
}


function plug_get_template($tpl_name, &$tpl_source, $template)
{
	$plug = $_GET['plug'];
	$template_path = PLUG_DIR.'/'.$plug.'/templates/'.$tpl_name;
	
	if (file_exists($template_path)) {
		$tpl_source = file_get_contents($template_path);
		return true;
	} 
	else 
		return false;
}


function plug_get_timestamp($tpl_name, &$tpl_timestamp, $template)
{
	$plug = $_GET['plug'];
	$template_path = PLUG_DIR.'/'.$plug.'/templates/'.$tpl_name;
	
	if (file_exists($template_path)) {
		$tpl_timestamp = filectime($template_path);
		return true;
	} 
	else 
		return false;
}


function plug_get_secure($tpl_name, $template){return true;}

function plug_get_trusted($tpl_name, $template){}


function _smarty_function_img($params, $template)
{
    global $_GET, $img;
    $file = 'img/Abort.png';
    
    if (isset($_GET['plug']) && !empty($_GET['plug']) && file_exists(SYS_DIR.'/plug/'.(isset($_GET['plug']) ? $_GET['plug'] : 'noneplugin').'/img/'.$params['src'])) 
	$file = 'plug/'.$_GET['plug'].'/img/'.$params['src'];
    else
	$file = 'img/'.$params['src'];
	
    $result  = '<span style="vertical-align:middle;"><img ';
    $result .= 'src="'.$file.'" ';
    $repeat = FALSE;
    
    if(isset($params['alt'])) $result .= 'alt="'.$params['alt'].'" ';
    else $result .= 'alt="" ';
    if(isset($params['width'])) $result .= 'width="'.$params['width'].'" ';
    if(isset($params['height'])) $result .= 'height="'.$params['height'].'" ';
    if(isset($params['style'])) $result .= 'style="'.$params['style'].'" ';
    if(isset($params['border'])) $result .= 'border="'.$params['border'].'" ';
    if(isset($params['onclick'])) $result .= 'onclick="'.$params['onclick'].'" ';
    if(isset($params['id'])) $result .= 'id="'.$params['id'].'" ';
    if(isset($params['tip']) && !empty($params['tip']))
    {
	$tip = $params['tip'];
	$tip = str_replace('\'', '\\\'', $tip);
	$tip = str_replace('"', '&quot;', $tip);
	$tip = str_replace("\r", '', $tip);
	$tip = str_replace("\n", '<BR>', $tip);
	if (get_conf('phpui.viewtip')) 
	    $result .= 'onmouseover="popup(\''.trans($tip).'\');" onmouseout="return nd();"';
	
    }
    $result .= '/></span>';
    return $result;
}

function _smarty_function_links($params, $template)
{
    global $_GET, $img;
    $result = '';
    $value 		= (isset($params['value']) 		? trans($params['value']) 	: NULL);
    $href 		= (isset($params['href']) 		? $params['href'] 		: NULL);
    $target 		= (isset($params['target']) 		? $params['target'] 		: NULL);
    $confirm		= (isset($params['confirm']) 		? $params['confirm'] 		: NULL);
    $id			= (isset($params['id']) 		? $params['id'] 		: NULL);
    $onclick 		= NULL;
    $image 		= (isset($params['img']) 		? $params['img'] 		: NULL);
    $imageid		= (isset($params['imgid']) 		? $params['imgid'] 		: NULL);
    $rights 		= (isset($params['rights']) 		? $params['rights'] 		: NULL);
    $tip 		= (isset($params['tip']) 		? $params['tip'] 		: NULL);
    $hreflang 		= (isset($params['hreflang']) 		? $params['hreflang'] 		: NULL);
    $media 		= (isset($params['media']) 		? $params['media'] 		: NULL);
    $rel 		= (isset($params['rel']) 		? $params['rel'] 		: NULL);
    $rev 		= (isset($params['rev']) 		? $params['rev'] 		: NULL);
    $type 		= (isset($params['type']) 		? $params['type'] 		: NULL);
    $class 		= (isset($params['class']) 		? $params['class'] 		: NULL);
    $lang 		= (isset($params['lang']) 		? $params['lang'] 		: NULL);
    $style 		= (isset($params['style']) 		? $params['style'] 		: NULL);
    $title 		= (isset($params['title']) 		? $params['title'] 		: NULL);
    $tabindex 		= (isset($params['tabindex']) 		? $params['tabindex'] 		: NULL);
    $onfocus 		= (isset($params['onfocus']) 		? $params['onfocus'] 		: NULL);
    $onblur 		= (isset($params['onblur']) 		? $params['onblur'] 		: NULL);
    $ondblclick 	= (isset($params['ondblclick'])		? $params['ondblclick'] 	: NULL);
    $onmousedown 	= (isset($params['onmousedown']) 	? $params['onmousedown'] 	: NULL);
    $onmouseup 		= (isset($params['onmouseup']) 		? $params['onmouseup'] 		: NULL);
    $onmouseover 	= (isset($params['onmouseover']) 	? $params['onmouseover'] 	: NULL);
    $onmousemove 	= (isset($params['onmousemove']) 	? $params['onmousemove'] 	: NULL);
    $onmouseout 	= (isset($params['onmouseout']) 	? $params['onmouseout'] 	: NULL);
    $onkeypress 	= (isset($params['onkeypress']) 	? $params['onkeypress'] 	: NULL);
    $onkeydown 		= (isset($params['onkeydown']) 		? $params['onkeydown'] 		: NULL);
    $onkeyup 		= (isset($params['onkeyup']) 		? $params['onkeyup'] 		: NULL);
    $id			= (isset($params['id'])			? $params['id']			: NULL);
    
    if (!is_null($rights)) 
	$rights = check_rights($rights); 
    else 
	$rights = true;
    
    if (!$rights) $tip .= '<br><font color="red">BRAK UPRAWNIEŃ</font>';
    
    if ($confirm) $confirm = "return confirmLinks(this,'".$confirm."');";
    
    $onclick = (isset($params['onclick']) ? $params['onclick'] : $confirm);
    
    if(!is_null($tip) && !empty($tip) && strlen($tip)!==0 && get_conf('phpui.viewtips'))
    {
	$tip = str_replace('\'', '\\\'', $tip);
	$tip = str_replace('"', '&quot;', $tip);
	$tip = str_replace("\r", '', $tip);
	$tip = str_replace("\n", '<BR>', $tip);
	$tip = 'onmouseover="popup(\''.$tip.'\'); onmouseout="return nd();" ';
    } 
	else $tip = NULL;
    if ($image)
    {
	
	if( isset($_GET['plug']) && !empty($_GET['plug']) && file_exists(SYS_DIR.'/plug/'.(isset($_GET['plug']) ? $_GET['plug'] : 'noneplugin').'/img/'.$image)) 
	    $file = 'plug/'.$_GET['plug'].'/img/'.$image;
	elseif(file_exists(SYS_DIR.'/img/'.$image)) 
	    $file = 'img/'.$image;
	else $image = NULL;
    }
    if ($image) 
	$image = '&nbsp;<img src="'.$file.'" alt="">';
    if ($rights)
    {
	$result .='<a '
	.($id 		? 'id="'.$id.'" ' 			: '')
	.($href 	? 'href="'.$href.'" ' 			: '')
	.($target 	? 'target="'.$target.'" ' 		: '')
	.($onclick 	? 'onclick="'.$onclick.'" ' 		: '')
	.($hreflang 	? 'hreflang="'.$hreflang.'" ' 		: '')
	.($media 	? 'media="'.$media.'" ' 		: '')
	.($type 	? 'type="'.$type.'" ' 			: '')
	.($rel 		? 'rel="'.$rel.'" ' 			: '')
	.($rev 		? 'rev="'.$rev.'" ' 			: '')
	.($class 	? 'class="'.$class.'" ' 		: '')
	.($lang 	? 'lang="'.$lang.'" ' 			: '')
	.($style 	? 'style="cursor:pointer;'.$style.'" ' 	: ' style="cursor:pointer;"')
	.($tabindex 	? 'tabindex="'.$tabindex.'" ' 		: '')
	.($onfocus 	? 'onfocus="'.$onfocus.'" ' 		: '')
	.($onblur 	? 'onblur="'.$onblur.'" ' 		: '')
	.($ondblclick 	? 'ondblclick="'.$ondblclick.'" ' 	: '')
	.($onmousedown 	? 'onmousedown="'.$onmosuedown.'" ' 	: '')
	.($onmouseup 	? 'onmouseup="'.$onmouseup.'" ' 	: '')
	.($onmouseover 	? 'onmouseover="'.$onmouseover.'" ' 	: '')
	.($onmousemove 	? 'onmousemove="'.$onmousemove.'" ' 	: '')
	.($onmouseout 	? 'onmouseout="'.$onmouseout.'" ' 	: '')
	.($onkeypress 	? 'onkeypress="'.$onkeypress.'" ' 	: '')
	.($onkeydown 	? 'onkeydown="'.$onkeydown.'" ' 	: '')
	.($onkeyup 	? 'onkeyup="'.$onkeyup.'" ' 		: '')
	.($title 	? 'titile="'.$title.'" ' 		: '')
	.($tip 		? $tip : '')
	.'';
	$result .= '>'
	.($value ? $value : '')
	.($image ? $image : '')
	.'</a>';
     }
     else
     {
	$result .='<a style="cursor:pointer;"'
	.($target ? ' target="'.$target.'" ' : '')
	.($tip ? $tip : '')
	.'';
	$result .= '>'
	.($value ? $value : '')
	.($image ? $image : '')
	.'</a>';
     }
    return $result;
}





$SMARTY->registerResource("plug", array(
					"plug_get_template",
					"plug_get_timestamp",
					"plug_get_secure",
					"plug_get_trusted",
				)
			);
$SMARTY->registerPlugin('function', 'title', '_smarty_function_title');
$SMARTY->registerPlugin('function', 'help', '_smarty_function_help');
$SMARTY->registerPlugin('block','box','_smarty_block_box');
$SMARTY->registerPlugin('function', 'img','_smarty_function_img');
$SMARTY->registerPlugin('function', 'links','_smarty_function_links');

?>
