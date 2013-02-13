<?php

/**************************************\
| Wiadomości Administracyjne v 2.0     |
| (c)2012 by Sylwester Kondracki       |
| www.lmsdodatki.pl                    |
\**************************************/

function module_main()
{
    global $DB,$LMS,$SESSION,$SMARTY;
    
    if (isset($_GET['ar'])) $archiwum = true; else $archiwum = false;
    if(isset($_GET['p']))
    {
	$id = (int)$_GET['p'];
	$DB->Execute('UPDATE messageitems SET isread = ? WHERE id = ?;',array(1,$id));
    }
    
    
    $message = $DB->GetAll('SELECT mi.id, m.subject, m.body, m.cdate, mi.isread , mi.firstread 
		FROM messageitems mi, messages m
		WHERE m.id = mi.messageid AND m.type=? AND mi.customerid = ? AND mi.status = ? '
		.(!$archiwum ? ' AND mi.isread=\'0\'' : '')
		.' ORDER BY m.cdate DESC ;',array(MSG_USERPANEL,$SESSION->id,MSG_SENT));
    
    if ($message) {
	$czas = time();
	for ($i=0; $i<sizeof($message); $i++)
	    if (!$message[$i]['isread'])
	    {
	    $DB->Execute('UPDATE messageitems SET '
	    .(!$message[$i]['firstread'] ? ' firstread=\''.$czas.'\', ' : '') 
	    .' lastread=\''.$czas.'\' WHERE id = ?;',array($message[$i]['id']));
	}
    }
    
    if ($message) // formatowanie %costam
    {
    	$data = $LMS->DB->GetRow('SELECT c.id, email, pin, '
		.$LMS->DB->Concat('c.lastname', "' '", 'c.name').' AS customername, '
		.$LMS->DB->Concat('c.address', "' '", 'c.zip',"' '",'c.city').' AS address, '
		.$LMS->DB->Concat('c.post_address', "' '", 'c.post_zip',"' '",'c.post_city').' AS postaddress, '
		.'COALESCE(b.value, 0) AS balance
		FROM customers c 
		LEFT JOIN (
			SELECT SUM(value) AS value, customerid
			FROM cash GROUP BY customerid
		) b ON (b.customerid = c.id) 
		WHERE c.id = ? LIMIT 1',array($SESSION->id));
	for ($i=0;$i<sizeof($message);$i++)
	{
	    if (!empty($data['customername'])) 
		$message[$i]['body'] = str_replace('%customer', $data['customername'], $message[$i]['body']);
	    else
		$message[$i]['body'] = str_replace('%customer','', $message[$i]['body']);
	    
	    if (!empty($data['balance'])) 
		$message[$i]['body'] = str_replace('%balance', $data['balance'], $message[$i]['body']);
	    else
		$message[$i]['body'] = str_replace('%balance','', $message[$i]['body']);
	
	    if (!empty($data['id'])) 
		$message[$i]['body'] = str_replace('%cid', $data['id'], $message[$i]['body']);
	    else
		$message[$i]['body'] = str_replace('%cid','', $message[$i]['body']);
		
	    if (!empty($data['pin'])) 
		$message[$i]['body'] = str_replace('%pin', $data['pin'], $message[$i]['body']);
	    else
		$message[$i]['body'] = str_replace('%pin', '', $message[$i]['body']);
	    
	    if (!empty($data['address'])) 
		$message[$i]['body'] = str_replace('%address', $data['address'], $message[$i]['body']);
	    else
		$message[$i]['body'] = str_replace('%address','', $message[$i]['body']);
	    
	    if (!empty($data['postaddress'])) 
		$message[$i]['body'] = str_replace('%postaddress', $data['postaddress'], $message[$i]['body']);
	    else
		$message[$i]['body'] = str_replace('%postaddress','', $message[$i]['body']);

	    if (bankaccount($data['id'])!='') 
	    {
		if (strpos($message[$i]['body'], '%bankaccount') !== false)
		$message[$i]['body'] = str_replace('%bankaccount', format_bankaccount(bankaccount($data['id'])), $message[$i]['body']);
	    }
	    else
		$message[$i]['body'] = str_replace('%bankaccount','', $message[$i]['body']);
	
	if(!(strpos($message[$i]['body'], '%last_3_in_a_table') === FALSE))
	{
		$last3 = '';
		if($last3_array = $LMS->DB->GetAll('SELECT comment, time, value 
			FROM cash WHERE customerid = ?
			ORDER BY time DESC LIMIT 3', array($data['id'])))
		{
			foreach($last3_array as $r)
			{
				$last3 .= date("Y/m/d | ", $r['time']);
				$last3 .= sprintf("%20s | ", sprintf($LANGDEFS[$LMS->ui_lang]['money_format'], $r['value']));
				$last3 .= $r['comment']."\n";
			}
		}
		$message[$i]['body'] = str_replace('%last_3_in_a_table', $last3, $message[$i]['body']);
	}

	if(!(strpos($message[$i]['body'], '%last_10_in_a_table') === FALSE))
	{
		$last10 = '';
		if($last10_array = $LMS->DB->GetAll('SELECT comment, time, value 
			FROM cash WHERE customerid = ?
			ORDER BY time DESC LIMIT 10', array($data['id'])))
		{
			foreach($last10_array as $r)
			{
				$last10 .= date("Y/m/d | ", $r['time']);
				$last10 .= sprintf("%20s | ", sprintf($LANGDEFS[$LMS->ui_lang]['money_format'], $r['value']));
				$last10 .= $r['comment']."\n";
			}
		}
		$message[$i]['body'] = str_replace('%last_10_in_a_table', $last10, $message[$i]['body']);
	}
	
	}


    }
    
    $count = count($message);
//    if ($count!==0) for ($i=0;$i<$count;$i++) $message[$i]['body'] = base64_decode($message[$i]['body']);
    $SMARTY->assign('archiwum',$archiwum);
    $SMARTY->assign('message', $message);

    $SMARTY->display('module:wiadomosci.html');
}

?>
