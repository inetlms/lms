#!/usr/bin/php
<?php
chdir(dirname ( __FILE__ ).'/..'); // go to LMS root directory

require 'contrib/initLMS.php';

$voip=new LMSVOIP($DB,$CONFIG['voip']);
setlocale(LC_NUMERIC, 'C');
$voip->ImportInvoice($argv[1]);
?>
