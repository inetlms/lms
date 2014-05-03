<?php

function get_list_annex()
{
    global $LMS,$SMARTY,$annex_info;
    $layout['popup'] = true;
    $obj = new xajaxResponse();
    
    $filelist = $LMS->GetFilesList($annex_info['section'],$annex_info['ownerid']);
    $SMARTY->assign('filelist',$filelist);

    $obj->assign("id_annex_content","innerHTML",$SMARTY->fetch('annexlist.html'));
    return $obj;
}

function delete_file_annex($id)
{
    global $LMS,$SMARTY,$annex_info;
    $layout['popup'] = true;
    $obj = new xajaxResponse();
    $LMS->DeleteFile(intval($id));
    $obj->script("xajax_get_list_annex();");
    return $obj;
}


//$LMS->InitXajax();
//$LMS->RegisterXajaxFunction(array('getManagementUrls', 'addManagementUrl', 'delManagementUrl','update_location_interface','get_list_annex','delete_file_annex'));
//$SMARTY->assign('xajax', $LMS->RunXajax());
$SMARTY->assign('incannex',1);

?>