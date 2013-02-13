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
 *  $Id:2013/02/10 22:01:35 Sylwester Kondracki Exp $
 */
 

function pobierzdane()
{
    $url = 'http://inetlms.pl/wsparcie.php';
    $tab['from'] = 'tolms';
    $c = curl_init();
    curl_setopt($c,CURLOPT_URL,$url);
    curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($c,CURLOPT_POST,1);
    curl_setopt($c,CURLOPT_POSTFIELDS, $tab);
    curl_setopt($c,CURLOPT_USERAGENT,'MozillaXYZ/1.0');
    curl_setopt($c,CURLOPT_TIMEOUT,30);
    $info = curl_getinfo($c);
    $result = curl_exec($c);
    if (curl_error($c))
    {
	return false;
    }
    curl_close($c);
    return $result;
}

function aktualizacja($dane)
{
    global $DB;
    if ($DB->GetOne('SELECT 1 FROM uiconfig WHERE section=? AND var=? LIMIT 1;',array('inetlms','sponsorzy')))
	    $DB->Execute('UPDATE uiconfig SET value = ? WHERE section = ? AND var = ?;',array($dane,'inetlms','sponsorzy'));
	else
	    $DB->Execute('INSERT INTO uiconfig (section, var, value) VALUES (?, ?, ?) ;',array('inetlms','sponsorzy',$dane));
}

if (isset($_GET['update']) && $_GET['update'] == '1')
{
    $tmp = pobierzdane();
    if ($tmp) aktualizacja($tmp);
}

$info = get_conf('inetlms.sponsorzy',NULL);

if (!$info)
{
    $tmp = pobierzdane();
    if ($tmp)
    {
	aktualizacja($tmp);
	$info = $tmp;
	unset($tmp);
    }
}

function viewsponsorzy()
{
    $obj = new xajaxResponse();
    $info = get_conf('inetlms.sponsorzy',NULL);
    if (!$info) 
    {
	$result = "<p style=\"margin-top:10px;\"><h1>Brak informacji</h1><h3>Pobranie danych o sponsorach nie jest możliwe.</h3></p>";
    }
    else
    {
	$info = unserialize(base64_decode($info));
	$cdate = $info['data'];
	if ($cdate < (time() - 604800))
	{
	    $update = pobierzdane();
	    if ($update) 
	    {
		aktualizacja($update);
		$info = unserialize(base64_decode($update));
	    }
	}
	
	$cdate = $info['data'];
	$dane = $info['dane'];
	$tab = $dane;
	$count = sizeof($tab);
	
	$j=0;
	$width=700;
	$result =  "<p style=\"margin:4px 0 4px 0;\"><strong>Lista firm które wsparły finansowo projekt iNET LMS</strong></p>";
	$result .= "<table style=\"width:".$width."px;border:0;\" cellpadding=\"3\" cellspacing=\"0\">";
	
	for ($i = 0; $i<$count; $i++)
	if (!empty($tab[$i][4]))
	{
	    $j++;
	    $result .=  "<tr>
	    <td width=\"1%\" nowrap align=\"center\" style=\"border: solid 1px #999999;border-bottom:0;\"><b>&nbsp;&nbsp;".$j."&nbsp;&nbsp;</b></td>
	    <td width=\"1%\" nowrap align=\"center\" style=\"border-top:solid 1px #999999;border-right:solid 1px #999999;\"><b>&nbsp;".$tab[$i][3]."%&nbsp;</b></td>
	    <td width=\"98%\" style=\"border-top:solid 1px #999999;border-right:solid 1px #999999;\">
	    <b>".$tab[$i][1]."</b>";
	
	    if (!empty($tab[$i][2]))
	    $result .= "<br><font style=\"font-size:11px;\">".$tab[$i][2]."</font>";
	
	    if (!empty($tab[$i][4]))
	    $result .= "<p style=\"margin:3px 0 3px 1px;\"><img src=\"img/bluepx.gif\" style=\"height:16px;width:".$tab[$i][4]."px;\"></p>";
	
	    $result .= "</td></tr>";
	}
	
	$result .= "<tr><td colspan=\"3\" style=\"border-top:solid 1px #999999;\"></td></tr></table>";

	$j = 0;
	$result .= "<p style=\"margin:10px 0 4px 0;\"><strong>Lista firm które wsparły projekt iNET LMS udostępniając kody źródłowe swoich rozwiązań</strong></p>";
	$result .= "<table style=\"width:".$width."px;\" cellpadding=\"3\" cellspacing=\"0\">";
	
	for ($i = 0; $i<$count; $i++)
	if (empty($tab[$i][4]))
	{
	    $j++;
	    $result .= "<tr>
	    <td width=\"1%\" nowrap align=\"center\" style=\"border: solid 1px #999999;border-bottom:0;\"><b>&nbsp;&nbsp;".$j."&nbsp;&nbsp;</b></td>
	    <td width=\"99%\" style=\"border-top:solid 1px #999999;border-right:solid 1px #999999;\">
	    <b>".$tab[$i][1]."</b>";
	    
	    if (!empty($tab[$i][2]))
	    $result .= "<br><font style=\"font-size:12px;\">".$tab[$i][2]."</font>";
	    $result .= "</td></tr>";
	}
	$result .= "<tr><td colspan=\"2\" style=\"border-top:solid 1px #999999;\"></td></tr>";
	$result .= "</table>";
	$result .= "<p style=\"color:green;font-size:10px;\">Stan na dzień ".date('Y/m/d',$cdate)."</p>";
    }
    
    $obj -> assign('spons','innerHTML',$result);
    return $obj;
}

$LMS->InitXajax();
$LMS->RegisterXajaxFunction('viewsponsorzy');
$SMARTY->assign('xajax',$LMS->RunXajax());
$SMARTY->display('sponsorzy.html');
?>