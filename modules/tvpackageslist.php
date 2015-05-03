<?php
/*
 * Aplikacja IPTV versja 1.2
 * 2011 ITMSOFT
 * 1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  
*/

$layout['pagetitle'] = trans('Lista pakietów aktualnie dostępnych w sprzedaży');

$list = $LMSTV->PackageGetAll();

$SMARTY->assign('list', $list);
$SMARTY->display('tvpackageslist.html');
$SMARTY->assignByRef('smsurl', $LMSTV->smsurl);
?>
