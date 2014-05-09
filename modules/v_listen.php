<?php
$file = $voip->uilisten($_GET['cid'], $_GET['id']);
if(file_exists($file)) 
{
	if($_GET['del'] == 1)
		@unlink($file);
	else
	{
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
		exit;
	}
}
$SESSION->redirect('?m=v_cdr');
?>
