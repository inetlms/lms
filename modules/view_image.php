<?php

$id = intval($_GET['id']);
$layout['popup'] = true;
$SMARTY->assign('id',$id);
$SMARTY->display('view_image.html');

?>