<?php
$layout['pagetitle'] = 'Ewidencja pojazdów';

if (isset($_GET['delcar']) && !empty($_GET['delcar']) && intval($_GET['delcar']) && isset($_GET['is_sure']) && $_GET['is_sure'] == '1')
    $RE->deletecar($_GET['delcar']);


$carlist = $RE->getcarlist();

$SMARTY->assign('carlist',$carlist);
$SMARTY->display('re_carlist.html');
?>