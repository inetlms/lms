<?php

/*
 *  LMS version 1.11-git
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
 *  $Id$
 */

function module_setup()
{
    global $SMARTY, $DB, $USERPANEL, $layout, $LMS;
    $layout['pagetitle'] = trans('Userpanel Configuration');
    $SMARTY->assign('logourl', get_conf('userpanel.logourl'));
    $SMARTY->assign('boxinfo1',get_conf('userpanel.boxinfo1'));
    $SMARTY->assign('boxinfo2',get_conf('userpanel.boxinfo2'));
    $SMARTY->assign('boxinfo3',get_conf('userpanel.boxinfo3'));
    $SMARTY->assign('boxinfo4',get_conf('userpanel.boxinfo4'));
    $SMARTY->assign('total', sizeof($USERPANEL->MODULES));
    $SMARTY->assign('disable_modules',unserialize(get_conf('userpanel.disable_modules','a:0:{}')));
    $SMARTY->display(USERPANEL_DIR.'/templates/setup.html');
}

function module_submit_setup()
{
    global $DB, $LMS;

    if ($DB->GetOne('SELECT 1 FROM uiconfig WHERE section = ? AND var = ? LIMIT 1;',array('userpanel','logourl')))
	$DB->Execute('UPDATE uiconfig SET value = ? WHERE section = ? AND var = ? ;',array($_POST['logourl'],'userpanel','logourl'));
    else
	$DB->Execute('INSERT INTO uiconfig (section, var, value) VALUES (?, ?, ?) ;',array('userpanel','logourl',$_POST['logourl']));
    $LMS->CONFIG['userpanel']['logourl'] = $_POST['logourl'];

    if ($DB->GetOne('SELECT 1 FROM uiconfig WHERE section = ? AND var = ? LIMIT 1;',array('userpanel','boxinfo1')))
	$DB->Execute('UPDATE uiconfig SET value = ? WHERE section = ? AND var = ? ;',array($_POST['boxinfo1'],'userpanel','boxinfo1'));
    else
	$DB->Execute('INSERT INTO uiconfig (section, var, value) VALUES (?, ?, ?) ;',array('userpanel','boxinfo1',$_POST['boxinfo1']));
    $LMS->CONFIG['userpanel']['boxinfo1'] = $_POST['boxinfo1'];

    if ($DB->GetOne('SELECT 1 FROM uiconfig WHERE section = ? AND var = ? LIMIT 1;',array('userpanel','boxinfo2')))
	$DB->Execute('UPDATE uiconfig SET value = ? WHERE section = ? AND var = ? ;',array($_POST['boxinfo2'],'userpanel','boxinfo2'));
    else
	$DB->Execute('INSERT INTO uiconfig (section, var, value) VALUES (?, ?, ?) ;',array('userpanel','boxinfo2',$_POST['boxinfo2']));
    $LMS->CONFIG['userpanel']['boxinfo2'] = $_POST['boxinfo2'];
    
    if ($DB->GetOne('SELECT 1 FROM uiconfig WHERE section = ? AND var = ? LIMIT 1;',array('userpanel','boxinfo3')))
	$DB->Execute('UPDATE uiconfig SET value = ? WHERE section = ? AND var = ? ;',array($_POST['boxinfo3'],'userpanel','boxinfo3'));
    else
	$DB->Execute('INSERT INTO uiconfig (section, var, value) VALUES (?, ?, ?) ;',array('userpanel','boxinfo3',$_POST['boxinfo3']));
    $LMS->CONFIG['userpanel']['boxinfo3'] = $_POST['boxinfo3'];
    
    if ($DB->GetOne('SELECT 1 FROM uiconfig WHERE section = ? AND var = ? LIMIT 1;',array('userpanel','boxinfo4')))
	$DB->Execute('UPDATE uiconfig SET value = ? WHERE section = ? AND var = ? ;',array($_POST['boxinfo4'],'userpanel','boxinfo4'));
    else
	$DB->Execute('INSERT INTO uiconfig (section, var, value) VALUES (?, ?, ?) ;',array('userpanel','boxinfo4',$_POST['boxinfo4']));
    $LMS->CONFIG['userpanel']['boxinfo4'] = $_POST['boxinfo4'];

    module_setup();
}

function module_rights()
{
    global $SMARTY, $DB, $LMS, $layout;
    
    $layout['pagetitle'] = trans('Customers\' rights');
    
    $customerlist = $LMS->GetCustomerNames();
    $userpanelrights = $DB->GetAll('SELECT id, module, name, description, setdefault FROM up_rights');

    $SMARTY->assign('customerlist',$customerlist);
    $SMARTY->assign('userpanelrights', $userpanelrights);
    $SMARTY->display(USERPANEL_DIR.'/templates/setup_rights.html');
}

function module_submit_rights()
{
    global $DB;
    $setrights=$_POST['setrights'];
    if(isset($setrights) && isset($setrights['mcustomerid'])) {
        $newrights=$setrights['rights'];
        foreach($setrights['mcustomerid'] as $customer) {
            $oldrights=$DB->GetAll('SELECT id, rightid FROM up_rights_assignments WHERE customerid=?',
                array($customer));
            if($oldrights != null)
                foreach($oldrights as $right)
                    if(isset($newrights[$right['rightid']]))
                        unset($newrights[$right['rightid']]);
                    else
                        $DB->Execute('DELETE FROM up_rights_assignments WHERE id=?',
                            array($right['id']));
            if($newrights != null)
                foreach($newrights as $right)
                    $DB->Execute('INSERT INTO up_rights_assignments(customerid, rightid) VALUES(?, ?)',
                        array($customer, $right));
        }
    }
    module_rights();
}

function module_submit_rights_default()
{
    global $DB;
    $rights = isset($_POST['setdefaultrights']) ? $_POST['setdefaultrights'] : array();
    foreach($DB->GetCol('SELECT id FROM up_rights') as $right)
        $DB->Execute('UPDATE up_rights SET setdefault = ? WHERE id = ?',
	        array(isset($rights[$right]) ? 1 : 0, $right));
    module_rights();
}

function module_setactive()
{
    global $LMS,$DB;
    if (isset($_GET['actmod']) && !empty($_GET['actmod']) && isset($_GET['act']))
    {
	$actmod = $_GET['actmod'];
	$act = ($_GET['act'] ? true : false);
	
	if ($tmp = $DB->GetOne('SELECT value FROM uiconfig WHERE section = ? AND var = ? '.$DB->Limit(1).' ;',array('userpanel','disable_modules')))
	{
	    $tmp = unserialize($tmp);
	    if (!is_array($tmp)) $tmp = array();
	}
	else
	    $tmp = array();
	if ($act)
	{
	    $tmp2 = intval(array_search($actmod,$tmp));
	    if (isset($tmp[$tmp2]))
		unset($tmp[$tmp2]);
	}
	else
	    array_push($tmp,$actmod);
	
	$tmp = serialize($tmp);
	
	if ($DB->Execute('UPDATE uiconfig SET value = ? WHERE section = ? AND var = ? ;',array($tmp,'userpanel','disable_modules')))
		$LMS->CONFIG['userpanel']['disable_modules'] = $tmp;
	
    }
    module_setup();
}

?>
