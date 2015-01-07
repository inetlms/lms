#!/usr/bin/php
<?php

require_once('/etc/lms/init_lms.php');

$voip=new LMSVOIP($DB,$CONFIG['voip']);
setlocale(LC_NUMERIC, 'C');
$voip->ImportInvoice($argv[1]);
?>
