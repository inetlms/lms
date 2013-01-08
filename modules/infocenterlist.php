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
 *  $Id: v 1.00 2012/12/22 01:53:35 Sylwester Kondracki Exp $
 */

if (!get_conf('privileges.hide_callcenter'))
{
    if (!isset($_GET['cid'])) $SESSION->restore('icl_cid',$_GET['cid']);
//    else $listdata['cid'] = $cid;
    $SESSION->save('icl_cid',$_GET['cid']);
    include(MODULES_DIR.'/infocenter.inc.php');
    
    $listdata['cid'] = $_GET['cid'];
    
    if (!isset($_GET['d'])) $SESSION->restore('icl_d',$listdata['dircetion']); 
    else $listdata['direction'] = $_GET['d'];
    if (empty($listdata['direction'])) $listdata['direction'] = 'desc';
    $SESSION->save('icl_d',$listdata['direction']);
    
    if (!isset($_GET['o'])) $SESSION->restore('icl_o',$listdata['order']); 
    else $listdata['order'] = $_GET['o'];
    if (empty($listdata['order'])) $listdata['order'] = 'cdate';
    $SESSION->save('icl_o',$listdata['order']);
    
    
    if (!isset($_GET['s'])) $SESSION->restore('icl_s',$listdata['status']); 
    else $listdata['status'] = $_GET['s'];
    $SESSION->save('icl_s',$listdata['status']);
    
    if (!isset($_GET['st'])) {
	if (!is_null($SESSION->get('icl_st')))
	    $SESSION->restore('icl_st',$listdata['status2']); 
	else 
	    $listdata['status2'] = '1';
    }
    else $listdata['status2'] = $_GET['st'];
    $SESSION->save('icl_st',$listdata['status2']);

    if (!isset($_GET['datefrom'])) $SESSION->restore('icl_datefrom',$listdata['datefrom']); 
    else $listdata['datefrom'] = $_GET['datefrom'];

    if (!isset($_GET['dateto'])) $SESSION->restore('icl_dateto',$listdata['dateto']); 
    else $listdata['dateto'] = $_GET['dateto'];
    
    

    $dfrom = $dto = NULL;
    $nowstart = strtotime(date('Y/m/d',time()));
    $nowend = strtotime(date('Y/m/d',time()))+86399;

    if (!empty($listdata['datefrom'])) {
	    if (strtotime($listdata['datefrom']))
		$dfrom = strtotime($listdata['datefrom']);
	    else
		$dfrom = strtotime(date('Y/m/d',$listdata['datefrom']));
		
	    if ($dfrom > $nowstart) $dfrom = $nowstart;
    }

    if (!empty($listdata['dateto'])) {
	    if (strtotime($listdata['dateto']))
		$dto = strtotime($listdata['dateto'])+86399;
	    else
		$dto = strtotime(date('Y/m/d',$listdata['dateto']))+86399;
	
	    if ($dto > $nowend) $dto = $nowend;
    }

    if (!empty($dfrom) && !empty($dto)) {
	    if ($dfrom > $dto) $dfrom = ($dto - 86399);
    }

    if (!empty($dfrom)) $listdata['datefrom'] = date('Y-m-d',$dfrom);
    if (!empty($dto)) $listdata['dateto'] = date('Y-m-d',$dto);

    $SESSION->save('icl_datefrom',$listdata['datefrom']);
    $SESSION->save('icl_dateto',$listdata['dateto']);
    

    $type = $_GET['type'];
    $SMARTY->assign('type',$type);
    $layout['pagetitle'] = 'Call Center';
    if (isset($_GET['cid']) && !empty($_GET['cid'])) $layout['pagetitle'] .= ' - klient: <a href="?m=customerinfo&id='.$cid.'">'.$cusname.'</a>';
    $SMARTY->assign('customername',$cusname);

    $topiclist = $LMS->GetCustomerListInfoCenter($cid,$listdata['status'],$listdata['status2'],$dfrom,$dto,$listdata['order'].','.$listdata['direction']);

    if (!isset($_GET['page'])) $SESSION->restore('icl_page',$_GET['page']);
    $page = (!isset($_GET['page']) ? 1 : $_GET['page']);
    $pagelimit = (! $LMS->CONFIG['phpui']['callcenter_pagelimit'] ? 50 : $LMS->CONFIG['phpui']['callcenter_pagelimit']);
    $start = ($page -1) * $pagelimit;
    $listdata['total'] = sizeof($topiclist);
    
    $SESSION->save('icl_page',$page);
    $SESSION->save('backto',$_SERVER['QUERY_STRING']);
    
    $SMARTY->assign('listdata',$listdata);
    $SMARTY->assign('page',$page);
    $SMARTY->assign('pagelimit',$pagelimit);
    $SMARTY->assign('start',$start);
    $SMARTY->assign('topiclist',$topiclist);
    $SMARTY->display('infocenterlist.html');
}
else
    $SMARTY->display('noaccess.html');
?>