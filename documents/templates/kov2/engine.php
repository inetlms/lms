<?php

if(!function_exists('calcMonth'))
{
    function calcMonth($fyear, $fmonth, $tyear, $tmonth)
    {
	$_start = mktime(0,0,0,$fmonth,1,$fyear);
	$_end = mktime(23,59,59,$tmonth,date("t",mktime(0,0,0,$tmonth,1,$tyear)),$tyear);
    
	if ($_start <= $_end)
	{
	    $yearF = date('Y',$_start);
	    $monthF = date('m',$_start);
	    $yearT = date('Y',$_end);
	    $monthT = date('m',$_end);
	    
	    if ($yearF == $yearT)
		$months = ($monthT - $monthF);
	    else {
		$months = ((12*($yearT-$yearF)-$monthF)+$monthT);
	    }
	    return $months;
	}
	else
	    return false;
    }
}



$cid = $document['customerid'];

if (empty($document['fromdate'])) $document['fromdate'] = time();
if (empty($document['todate'])) $document['todate'] = $document['fromdate'];

$date['fromyear'] = date('Y',$document['fromdate']);
$date['frommonth'] = date('m',$document['fromdate']);
$date['toyear'] = date('Y',$document['todate']);
$date['tomonth'] = date('m',$document['todate']);

$ilmc = $date['countmc'] = calcMonth($date['fromyear'],$date['frommonth'],$date['toyear'],$date['tomonth']) + 1;
$pages = ceil($date['countmc'] / 3);


$ks = $gil = array();

for ($i=0; $i<$ilmc;$i++)
{
    $tmp = strtotime($date['fromyear'].'/'.$date['frommonth'].'/1 +'.$i.' month');
    $ks[] = array(
	'y'	=> date('Y',$tmp),
	'm'	=> date('m',$tmp),
    );
}

if ($document['gilotyna'] && $pages > 1) {
    $tab = array_chunk($ks,$pages);
    $tmp = array();

    for ($j=0; $j<$pages; $j++)
	for ($i=0; $i<sizeof($tab); $i++) {
	    if (isset($tab[$i][$j])) $gil[] = $tab[$i][$j];
	    else
		$gil[] = array('y'=>NULL,'m'=>NULL);
	}
    
    $ks = $gil;
}

$SMARTY->assign(
		array(
			'date'	=> $date,
			'cid'	=> $cid,
			'engine' => $engine,
			'document' => $document,
			'ks'		=> $ks,
		     )
		);

$output = $SMARTY->fetch(DOC_DIR.'/templates/'.$engine['name'].'/'.$engine['template']);

?>
