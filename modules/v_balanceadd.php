<?php

/*
 *  $Id: $
 */

$addbalance = $_POST['addbalance'];
$SESSION->save('addbc', $addbalance['comment']);
$SESSION->save('addbt', $addbalance['time']);
$SESSION->save('addbv', $addbalance['value']);
$SESSION->save('addbtax', $addbalance['taxid']);

$addbalance['value'] = str_replace(',', '.', $addbalance['value']);

if($addbalance['time'])
{
	list($date,$time) = split(' ',$addbalance['time']);
	$date = explode('/',$date);
	$time = explode(':',$time);
	if(checkdate($date[1],$date[2],$date[0])) //if date is wrong, set today's date
		$addbalance['time'] = "$date[0]-$date[1]-$date[2] $time[0]:$time[1]:00";
	else
		unset($addbalance['time']);
}
if(isset($addbalance['customerid']))
{
	if($voip->CustomerExists($addbalance['customerid']))
	{
		if($addbalance['type']) 
			$addbalance['taxid'] = 0;
			
		if($addbalance['value'] != 0)
			$voip->AddBalance($addbalance);
	}
}
else
{
	$addbalance['customerid'] = '0';
	$addbalance['taxid'] = '0';
	$addbalance['type'] = '1';
	
	if($addbalance['value'] != 0)
		$voip->AddBalance($addbalance);
}

header('Location: ?'.$SESSION->get('backto'));

?>
