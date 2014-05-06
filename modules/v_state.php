<?php
$query = $voip->GetState();
$SMARTY->display('header.html');
echo '<H1>Połączenia</H1>';
echo '<TABLE WIDTH="100%" CLASS="superlight" CELLPADDING="5"><TR><TD CLASS="FALL">';
echo '<PRE>' . $query['channels'] . '</PRE>';
echo '</TD></TR></TABLE><BR><BR>';
echo '<H1>Klienci</H1>';
echo '<TABLE WIDTH="100%" CLASS="superlight" CELLPADDING="5"><TR><TD CLASS="FALL">';
$sip = $voip->wsdl->GetAccountsForState();
$cl = explode("\n", $query['clients']);
echo '<PRE>';
echo "$cl[0]\n$cl[1]\n$cl[2]\n";
unset($cl[0]);
unset($cl[1]);
unset($cl[2]);
foreach($sip as $val)
{
	$out = '';
	foreach($cl as $key => $val1)
	if(is_array($val['accountcode']) && in_array(substr($val1, 0, 10), $val['accountcode']))
	{
		$out .= $val1 . "\n";
		unset($cl[$key]);
	}
	if($out) echo "\n<b>" . $val['surname'] . ' ' . $val['forename'] . "</b>\n$out";
	reset($cl);
}
echo "\n\n" . implode("\n", $cl);

echo '</PRE>';
echo '</TD></TR></TABLE>';
echo '<table><tr><td><a href="?m=v_reload" onclick="return confirm(\'Uwaga! Wszystkie bieżące połączenia zostaną zakończone. Kontynuować ?\');">Przeładuj centralę</a></td></tr></table>';
$SMARTY->display('footer.html');
?>
