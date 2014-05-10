<?php
global $SMARTY,$voip,$SESSION;
$actions=array(0=>'BRAK',1=>'Odtwórz nagranie',2=>'Inny IVR',3=>'Poczta głosowa',4=>'Przekieruj połączenie');

switch($_GET['ff'])
{
case 'add':
	$_POST['group']['name'] = trim($_POST['group']['name']);
	$ivrid=$voip->wsdl->ivr_exists($_POST['group']['name']);
	if((!$ivrid or $ivrid==$_POST['group']['id']) and preg_match('/^[0-9a-zA-Z_ ]+$/',$_POST['group']['name']))
	{
		$voip->wsdl->ivr_add($_POST['group'],$SESSION->id);
		$SESSION->redirect('?m=voip&f=ivr');
	}
	else $SMARTY->assign('err','Błędna nazwa IVR');
	break;
case 'edit':
	$ed=$voip->wsdl->ivr_gettoedit($_GET['id'],$SESSION->id);
	if($ed)
	{
		$SMARTY->assign('group',$ed);
		$SMARTY->assign('groupaction',true);
	}
	else $SMARTY->assign('err','Błąd!');
	break;
case 'del':
	$f=$voip->wsdl->ivr_del($_GET['id'],$SESSION->id);
	if(is_array($f)) foreach($f as $val) $voip->ivr_deletefile(strtok($val, "_"),$SESSION->id);
	$SESSION->redirect('?m=voip&f=ivr');
	break;
case 'det':
	if($ivr=$_POST['ivr'])
	{
		$voip->wsdl->ivr_save($ivr,$_GET['id'],$SESSION->id);
		$SESSION->redirect('?m=voip&f=ivr&ff=det&id='.$_GET['id']);
	}
	$list=$voip->wsdl->ivr_getdet($_GET['id'],$SESSION->id);
	$tmp=array();
	if(is_array($list)) foreach($list as $val)
	{
		if($val['action'])
		{
			$act=$val['action'][0];
			$tmp['actiont_'.$val['digit']]=$actions[$act];
			$tmp['action_'.$val['digit']]=$val['action'];
			if($act==2) $tmp['actiont_'.$val['digit']].=': '.$voip->wsdl->ivr_getone(substr($val['action'],2),$SESSION->id);
			elseif($act==4) $tmp['actiont_'.$val['digit']].=': '.substr($val['action'],2);
		}
		if($val['message'])
		{
			$pos=strpos($val['message'],'_');
			if($pos===false) continue;
			$file=substr($val['message'],0,$pos);
			$file_orig=substr($val['message'],$pos+1);
			$tmp['filet_'.$val['digit']]=$file_orig;
			$tmp['file_'.$val['digit']]=$val['message'];
		}
	}
	$SMARTY->assign('list',$tmp);
	$SMARTY->assign('title','IVR: '.$voip->wsdl->ivr_getone($_GET['id'],$SESSION->id));
	$det=true;
	break;
case 'delm':
	$file=$voip->wsdl->ivr_getfiletodel($_GET['id'],$_GET['mess'],$SESSION->id);
	if($file) $voip->ivr_deletefile(strtok($file, "_"),$SESSION->id);
	$SESSION->redirect('?m=voip&f=ivr&ff=det&id='.$_GET['id']);
case 'uploadfile':
	if(($f=$_FILES['f']) and is_array($f) and $f['error']==UPLOAD_ERR_OK and substr(strtolower($f['name']),-4) == '.wav')
	{
		$file=$voip->ivr_uploadfile($f,$SESSION->id);
		$SMARTY->assign('filename',$file.'_'.$f['name']);
		$SMARTY->assign('filename_orig',$f['name']);
		$SMARTY->assign('uplok',true);
	}
	elseif($f) $SMARTY->assign('err','Błąd! Obsługiwane są pliki wav 3MB max');
	$SMARTY->display('module:ivr_upload.html');
	exit;
case 'action':
	switch($_POST['act'])
	{
		case '0':
			$SMARTY->assign('uplok',true);
			$SMARTY->assign('action','');
			$SMARTY->assign('action_name',$actions[0]);
			break;
		case '1':
			$SMARTY->assign('uplok',true);
			$SMARTY->assign('action',1);
			$SMARTY->assign('action_name',$actions[1]);
			break;
		case '2':
			if($_POST['ivr'])
			{
				$SMARTY->assign('uplok',true);
				$SMARTY->assign('action','2_'.$_POST['ivr']);
				$SMARTY->assign('action_name',$actions[2].': '.$voip->wsdl->ivr_getone($_POST['ivr'],$SESSION->id));
				break;
			}
			$ivr=$voip->wsdl->ivr_getall($SESSION->id);
			$ilist=array();
			foreach($ivr as $val) if($val['id']!=$_GET['ivrid']) $ilist[$val['id']]=$val['name'];
			if(empty($ilist)) break;
			$SMARTY->assign('ivrlist',$ilist);
			break;
		case '3':
			$SMARTY->assign('uplok',true);
			$SMARTY->assign('action',3);
			$SMARTY->assign('action_name',$actions[3]);
			break;
		case '4':
			if($_POST['nr'] and preg_match('/^[0-9]+$/',$_POST['nr']))
			{
				$SMARTY->assign('uplok',true);
				$SMARTY->assign('action','4_'.$_POST['nr']);
				$SMARTY->assign('action_name',$actions[4].': '.$_POST['nr']);
				break;
			}
	}
	$SMARTY->assign('act',$actions);
	$SMARTY->display('module:ivr_action.html');
	exit;

}

if($det)
{
	$SMARTY->display('module:ivrd.html');
}
else
{
	$nodes=$voip->wsdl->GetCustomerNodes($SESSION->id);
	$nod=array();
	if($_GET['ff']=='edit') $ed=$_GET['id']; else $ed=null;
	$active=$voip->wsdl->ivr_getassignedacc($SESSION->id,$ed);
	if($active==null) $active=array();
	foreach($nodes as $val) if(is_array($val) and !in_array($val['id'],$active)) $nod[$val['id']]=$val['name'];
	$SMARTY->assign('num',$nod);
	$ivrlist=$voip->wsdl->ivr_get($SESSION->id);
	$SMARTY->assign('groups',$ivrlist);
	$SMARTY->display('module:ivr.html');
}
?>

