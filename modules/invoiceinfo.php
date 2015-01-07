<?php

$id = $_GET['id'];

$info = $LMS->getinvoicecontent($id);
$layout['popup'] = true;
$SMARTY->assign('info',$info);
$SMARTY->display('invoiceinfo.html');

?>