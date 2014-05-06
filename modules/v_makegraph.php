<?php
ini_set('error_reporting', '0');
include $CONFIG['voip']['jpgraph'].'/jpgraph.php';
include $CONFIG['voip']['jpgraph'].'/jpgraph_line.php';
$from=str_replace('%20',' ',$_GET['from']);
$to=str_replace('%20',' ',$_GET['to']);
$res=$voip->GetBalance($from, $to, $_GET['user']);
$d1=array();$d2=array();$names=array();$d3=array();
foreach($res as $val)
{
$d1[]=$val['cost'];
$d2[]=$val['trunk'];
$names[]=$val['data'];
$d3[]=$val['tmp_cost'];
}
		$graph = new Graph(600,400,"auto");
		$graph->SetScale("textlin");
		$graph->yaxis->SetColor("black","red");
		$graph->title->Set("Bilans kosztów");
		$graph->subtitle->Set("Od: $from  do: $to");
		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->img->SetMargin(40,125,40,80);    
		$graph->legend->Pos(0.01,0.5,"right","center");
		$graph->xaxis->SetTextTickInterval((int)(count($d1)/30)+1);
		$graph->xaxis->SetTextLabelInterval(2);
		$graph->xaxis->SetTickLabels($names);
		$graph->xaxis->SetLabelAngle(90);
		$graph->yaxis->title->Set("PLN");
$p1 = new LinePlot($d1);
$p1->SetColor("green");
$p1->SetLegend("Przychody\n z rozmów\n(z darmowymi\nminutami)");
$p2 = new LinePlot($d2);
$p2->SetColor("blue");
$p2->SetLegend("Wydatki\n na rozmowy");
$p3 = new LinePlot($d3);
$p3->SetColor("red");
$p3->SetLegend("Przychody\n z rozmów\n(bez darmowych\nminut)");
		$graph->Add($p1);
		$graph->Add($p3);
		$graph->Add($p2);
		$graph->Stroke();
?>
