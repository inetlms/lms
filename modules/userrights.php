<?php

if (isset($_POST['prawa'])) {
    $form = $_POST['prawa'];
    $_old = $DB->GetOne('SELECT exrights FROM users WHERE id = ? LIMIT 1;',array($form['id']));

    if (empty($_old)) {
	$_old = array();
    } else {
	$_old = unserialize($_old);
	unset($_old[$form['sec']]);
    }
    
    $_new[$form['sec']] = $form['md5'];
    $new = array_merge($_old,$_new);
    $new = serialize($new);
    $DB->Execute('UPDATE users SET exrights=? WHERE id = ?;',array($new,$form['id']));
    $SESSION->redirect('?m=userrights&id='.$form['id'].'&sec='.$form['sec']);
}


$user = $DB->GetRow('SELECT id, login, name FROM users WHERE id = ? LIMIT 1;',array(intval($_GET['id'])));

if (!$user)
    $SESSION->redirect('?m=userlist');

$layout['pagetitle'] = 'Uprawnienia dla : '.$user['login'];
$userright = array();

$sec = $right = array();

$listdata['sec'] = (isset($_GET['sec']) && !empty($_GET['sec']) ? $_GET['sec'] : NULL);
$listdata['cid'] = $_GET['id'];

foreach ($RIGHTS_LIST as $key => $row) {
    $sec[] = $key;
}

if ($listdata['sec']) {
	foreach ($_RL_[$listdata['sec']] as $key => $row)
	{
	    $right[] = array(
		'name1'		=> $key,
		'md5'		=> $RIGHTS_LIST[$listdata['sec']][$key],
		'description'	=> $_RL_[$listdata['sec']][$key],
	    );
	}
	$tmp = $DB->GetOne('SELECT exrights FROM users WHERE id = ? LIMIT 1;',array($_GET['id']));
	if (empty($tmp)) {
	    $tmp = array();
	} else {
	    $tmp = unserialize($tmp);
	}
	
	foreach ($tmp as $key => $row) {
	    for ($i=0; $i<sizeof($row); $i++)
	    $userright[] = $row[$i];
	}
}

$SMARTY->assign('userright',$userright);
$SMARTY->assign('listdata',$listdata);
$SMARTY->assign('right',$right);
$SMARTY->assign('sec',$sec);
$SMARTY->display('userrights.html');
?>