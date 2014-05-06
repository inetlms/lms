<?php


$error = '';
$layout['pagetitle'] = 'Sprawozdanie UKE';
$dir = dirname($voip->mondir);
$file = $dir . '/tmp/UKE';



if(!is_dir($voip->mondir))
	$error = 'Katalog <B>' . $dir . '</B> musi być zamontowany.';
else
{
	if($_POST['rok'] and preg_match('/^\d{4}$/', $_POST['rok']))
	{
		if(FALSE === file_put_contents($file, $_POST['rok']))
			$error = 'Nie udało się zapisać pliku';
	}
	elseif($_GET['del'] == 1)
		unlink($file);
	if(is_file($file))
	{
		$ent = file($file, FILE_SKIP_EMPTY_LINES);
		if(!$ent)
			$error = 'Problem z uprawnieniami dla pliku ' . $file;
		else
		{
			if(count($ent) == 1)
				$SMARTY->assign('req', $ent[0]);
			else
				$SMARTY->assign('res', array($ent[0], unserialize($ent[1])));
		}
	}
}

$SMARTY->assign('error', $error);
$SMARTY->display('v_uke.html');
?>
