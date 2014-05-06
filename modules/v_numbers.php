<?php
$layout['pagetitle'] = 'Wzorce numerów';
if($nr = $_POST['numer'])
{
	$res = $voip->wsdl->numbers_search($nr, $_GET['id_tariff'], $_GET['trunk']);
	$SMARTY->assign('n', $res);
	$SMARTY->assign('numer', $nr);
}
elseif($_GET['id'])
	$SMARTY->assign('n', $voip->wsdl->getratebyid($_GET['id']));
$SMARTY->display('v_numbers.html');
?>
