<?php
$layout['pagetitle'] = 'Usunięcie konta ' . $voip->wsdl->GetNodeName($_GET['id']);
$SMARTY->assign('nodeid', $_GET['id']);

if (!$voip->wsdl->NodeExists($_GET['id']))
{
	$body = '<H1>' . $layout['pagetitle'] . '</H1><P>' . trans('Incorrect ID number') . '</P>';
}
else
{

	if($_GET['is_sure'] != 1)
	{
		$body = '<H1>' . $layout['pagetitle'] . '</H1>';
		$body .= '<P>Napewno usunąć konto ' . $voip->wsdl->GetNodeName($_GET['id']) . ' ?</P>'; 
		$body .= '<P><A HREF="?m=v_nodedel&id=' . $_GET['id'] . '&is_sure=1">Tak, jestem pewien</A></P>';
	}
	else
	{
		$owner = $voip->wsdl->GetNodeOwner($_GET['id']);
		$voip->wsdl->DeleteNode($_GET['id']);
		if($SESSION->is_set('backto'))
			header('Location: ?' . $SESSION->get('backto'));
		else
			header('Location: ?m=customerinfo&id=' . $owner);
		$body = '<H1>' . $layout['pagetitle'] . '</H1>';
		$body .= '<P>Konto ' . $voip->wsdl->GetNodeName($_GET['id']) . ' zostało usunięte.</P>';
	}
}

$SMARTY->display('header.html');
$SMARTY->assign('body', $body);
$SMARTY->display('dialog.html');
$SMARTY->display('footer.html');
?>
