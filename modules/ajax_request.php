<?php

$action 	= (isset($_GET['action']) ? strtolower($_GET['action']) : (isset($_POST['action']) ? strtolower($_POST['action']) : NULL));
$mod 		= (isset($_GET['mod']) ? strtolower($_GET['mod']) : (isset($_POST['mod']) ? strtolower($_POST['mod']) : NULL));
$field 		= (isset($_GET['field']) ? $_GET['field'] : (isset($_POST['field']) ? $_POST['field'] : NULL));
$id 		= (isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : NULL));
$idc 		= (isset($_GET['idc']) ? intval($_GET['idc']) : (isset($_POST['idc']) ? intval($_POST['idc']) : NULL));
$access 	= (isset($_GET['access']) ? intval($_GET['access']) : (isset($_POST['access']) ? intval($_POST['access']) : NULL));
$active 	= (isset($_GET['active']) ? intval($_GET['active']) : (isset($_POST['active']) ? intval($_POST['active']) : NULL));

if ($action) $action = htmlspecialchars($action);
if ($field) $field = htmlspecialchars($field);
if ($mod) $mod = htmlspecialchars($mod);
if ($id) $id = intval($id);

if (is_null($action) || empty($action))  die('null');
$result = NULL;

switch ($action) 
{

	case 'uploadfile' :
				$result = $LMS->UploadFile('myfile',$_POST['annex_section'],intval($_POST['annex_ownerid']),$_POST['opis']);
	break;

	case 'downloadfile' :
				$LMS->downloadfile($id);
	break;

	case 'check_networknode_name' :
				if (is_null($field) || empty($field)) $filed = 'name';
				$request = trim(strtoupper($_REQUEST[$field]));
				$ignoreid = (isset($_GET['ignoreid']) ? $_GET['ignoreid'] : NULL);
				
				if ($ignoreid)
				{
				    if ($DB->GetOne('SELECT 1 FROM networknode WHERE UPPER('.$field.') = ? AND id != ? '.$DB->Limit(1).' ;',array($request,$ignoreid)))
					$result = false;
				    else
					$result = true;
				}
				else
				{
				    if ($DB->GetOne('SELECT 1 FROM networknode WHERE UPPER('.$field.') = ? '.$DB->Limit(1).' ;',array($request)))
					$result = false;
				    else
					$result = true;
				}
	break;

	case 'check_netdev_name' :
				if (is_null($field) || empty($field)) $filed = 'name';
				$request = trim(strtoupper($_REQUEST[$field]));
				$ignoreid = (isset($_GET['ignoreid']) ? $_GET['ignoreid'] : NULL);
				
				if ($ignoreid)
				{
				    if ($DB->GetOne('SELECT 1 FROM netdevices WHERE UPPER('.$field.') = ? AND id != ? '.$DB->Limit(1).' ;',array($request,$ignoreid)))
					$result = false;
				    else
					$result = true;
				}
				else
				{
				    if ($DB->GetOne('SELECT 1 FROM netdevices WHERE UPPER('.$field.') = ? '.$DB->Limit(1).' ;',array($request)))
					$result = false;
				    else
					$result = true;
				}
	break;

	case 'node_access' :
				if ($idc)
				{
				    if ($DB->Execute('UPDATE nodes SET access = ? WHERE ownerid = ? ;',array(intval($_POST['access']),$idc)))
					$result = TRUE;
				    else
					$result = FALSE;
				}
				elseif ($id) {
				    if ($DB->Execute('UPDATE nodes SET access = ? WHERE id = ? ;',array(intval($_POST['access']),$id)))
					$result = TRUE;
				    else
					$result = FALSE;
				}
	break;

	case 'node_warning' :
				if ($idc)
				{
				    if ($DB->Execute('UPDATE nodes SET warning = ? WHERE ownerid = ? ;',array(intval($_POST['warning']),$idc)))
					$result = TRUE;
				    else
					$result = FALSE;
				}
				elseif ($id) {
				    if ($DB->Execute('UPDATE nodes SET warning = ? WHERE id = ? ;',array(intval($_POST['warning']),$id)))
					$result = TRUE;
				    else
					$result = FALSE;
				}
	break;
    
    case 'balance_ok' :
			if ($DB->GetOne('SELECT 1 FROM customers WHERE deleted = 0 AND id = ? LIMIT 1;',array($idc)))
			{
			    $balance = $LMS->GetCustomerBalance($idc);
			    
			    if($balance<0)
			    {
				$DB->BeginTrans();
				
				$DB->Execute('INSERT INTO cash (time, type, userid, value, customerid, comment) VALUES (?NOW?, 1, ?, ?, ?, ?)', 
					    array($AUTH->id, str_replace(',','.', $balance*-1), $idc, trans('Accounted')));
				
				$DB->Execute('UPDATE documents SET closed = 1 WHERE customerid = ? AND type IN (?, ?) AND closed = 0', array($idc, DOC_INVOICE, DOC_CNOTE));
				
				if (SYSLOG)
				    addlogs('rozliczono zobowiązania klienta '.$LMS->getcustomername($idc),'e=up;m=fin;c='.$idc.';');
				
				$DB->CommitTrans();
			    }
			    $result = true;
			}
			else $result = false;
    break;
    

}

if (is_null($result)) die('null');
elseif (is_string($result)) die($result);
elseif ($result == true) die('true');
elseif ($result == false) die('false');
else die('null');

?>