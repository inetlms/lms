<?php

include_once( 'php-ofc-library/open-flash-chart.php' );
$x_labels=array();
$g=$_GET['g'];
$g=explode('|', $g);





//===========================================================
$bar_red = new bar( 50, '#D54C78' );
 
//===========================================================  


$i=0;
while($i<count($g)){
$x_labels[]=$g[$i];


if($g[$i+1]==null)$g[$i+1]=0;
$bar_red->data[] = $g[$i+1];

if($g[$i+1]>$tmax)
$tmax=$g[$i+1];
$i++;
$i++;

}
 
  
  
  
  
  
  
  
// create the graph object:
$g = new graph();
$g->title( 'Statystyka rozmów', '{font-size:15px; color: #FFFFFF; margin: 5px; background-color: #505050; padding:5px; padding-left: 20px; padding-right: 20px;}' );


$g->data_sets[] = $bar_red;

$g->x_axis_colour( '#909090', '#ADB5C7' );
$g->y_axis_colour( '#909090', '#ADB5C7' );

$g->set_x_labels( $x_labels);
$g->set_x_label_style( 10, 'black', 1); 
$g->set_y_max($tmax );
$g->y_label_steps( 5 );
$g->set_y_legend( 'Ilosc sekund', 12, '#736AFF' );
echo $g->render();
?>