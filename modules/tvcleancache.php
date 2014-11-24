<?php
/*
 * Aplikacja IPTV versja 1.2
 * 2011 ITMSOFT
 * 1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  
*/

$layout['pagetitle'] = '';

$LMSTV->tv_cache->clean(Zend_Cache::CLEANING_MODE_ALL);
//$SESSION->redirect($_SERVER['HTTP_REFERER']);

$SMARTY->assign('config', $config);
$SMARTY->display('tvcleancache.html');
?>
