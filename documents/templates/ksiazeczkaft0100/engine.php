<?php


$cid = $document['customerid'];
if (empty($document['fromdate'])) $document['fromdate'] = time();
if (empty($document['todate'])) $document['todate'] = $document['fromdate'];

$date['fromyear'] = date('Y',$document['fromdate']);
$date['frommonth'] = date('n',$document['fromdate']);
$date['toyear'] = date('Y',$document['todate']);
//$date['tomonth'] = date('m',$document['todate']);

$datefrom = new DateTime(date('Y-m-d H:i:s',$document['fromdate']));
$dateto = new DateTime(date('Y-m-d H:i:s',$document['todate']));
$diff = $datefrom->diff($dateto);
$date['countmc'] = $diff->format("%m")+1;
$pages = ($countmc / 3);

$date['msc'][1] = 'Styczeń';
$date['msc'][2] = 'Luty';
$date['msc'][3] = 'Marzec';
$date['msc'][4] = 'Kwiecień';
$date['msc'][5] = 'Maj';
$date['msc'][6] = 'Czerwiec';
$date['msc'][7] = 'Lipiec';
$date['msc'][8] = 'Sierpień';
$date['msc'][9] = 'Wrzesień';
$date['msc'][10] = 'Październik';
$date['msc'][11] = 'Listopad';
$date['msc'][12] = 'Grudzień';

$SMARTY->assign(
		array(
			'date'	=> $date,
			'cid'	=> $cid,
			'engine' => $engine,
			'document' => $document,
		     )
		);

$output = $SMARTY->fetch(DOC_DIR.'/templates/'.$engine['name'].'/'.$engine['template']);

?>
