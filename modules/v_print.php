<?php
$s = $voip->wsdl->GetSettings();
$n = $voip->wsdl->GetNode($_GET['account']);
$u = $voip->GetUserToSettings($voip->wsdl->GetNodeOwner($_GET['account']), $s[3]);
$layout['pagetitle'] = 'Ustawienia konta ' . $n['name'];
$m = array();
$m[] = $u['lastname'] . ' ' . $u['name'] . ' - ustawienia konta ' . $n['name'];
$m[] = '';
$m[] = 'Adres serwera SIP: ' . $s[1];
$m[] = 'Login konta SIP: ' . $n['accountcode'];
$m[] = 'Hasło konta SIP: ' . $n['secret'];
$m[] = 'Numer poczty glosowej: ' . $s[4];
$m[] = 'Login poczty głosowej: ' . $n['mailbox'];
$m[] = 'Hasło poczty głosowej: ' . $n['mailboxpin'];
$m[] = 'Adres Internetowego Centrum Użytkownika: ' . $s[2];
$m[] = 'Login ICU: ' . $u['login'];
$m[] = 'Hasło ICU: ' . $u['pin'];
$out = implode("\n", $m);
$SMARTY->assign('body', $out);
$SMARTY->display('v_print.html');
?>
