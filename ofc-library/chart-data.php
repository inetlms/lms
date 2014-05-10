<?php

include_once( 'php-ofc-library/open-flash-chart.php' );
$x_labels=array();
$g=$_GET['g'];
$g=explode('*', $g);
$ilosc_stref=count($g);




//===========================================================
$bar_red = new bar( 50, '#D54C78' );
$bar_red->key( 'T1(8:00-16:00)', 10 );


$bar_blue = new bar( 50, '#3334AD' );
$bar_blue->key( 'T2(16:00-24:00)', 10 );

 
$bar_green= new bar( 50, '#00dd00' );
$bar_green->key( 'T3(0:00-8:00)', 10 );  
//===========================================================  


for($k=0; $k<$ilosc_stref; $k++){
$strefa[$k]=explode('|', $g[$k]);
$x_labels[]=$strefa[$k][0];

$t=explode('@', $strefa[$k][1]);
if($t[0]==null)$t[0]=0;
$bar_red->data[] = $t[0];
if($t[1]==null)$t[1]=0;
$bar_blue->data[] = $t[1];
if($t[2]==null)$t[2]=0;
$bar_green->data[] = $t[2];
if($t[0]>$tmax)
$tmax=$t[0];
if($t[1]>$tmax)
$tmax=$t[1];
if($t[2]>$tmax)
$tmax=$t[2];
}
 
  
  
  
  
  
  
  
// create the graph object:
$g = new graph();
$g->title( 'Statystyka rozmów', '{font-size:15px; color: #FFFFFF; margin: 5px; background-color: #505050; padding:5px; padding-left: 20px; padding-right: 20px;}' );

//$g->set_data( $data_1 );
//$g->bar_3D( 75, '#D54C78', '2006', 10 );

//$g->set_data( $data_2 );
//$g->bar_3D( 75, '#3334AD', '2007', 10 );

$g->data_sets[] = $bar_red;
$g->data_sets[] = $bar_blue;
$g->data_sets[] = $bar_green;

// $g->set_x_axis_3d( 3 );
$g->x_axis_colour( '#909090', '#ADB5C7' );
$g->y_axis_colour( '#909090', '#ADB5C7' );

$g->set_x_labels( $x_labels);
$g->set_x_label_style( 10, 'black', 1); 
$g->set_y_max($tmax );
$g->y_label_steps( 5 );
$g->set_y_legend( 'Ilosc sekund', 12, '#736AFF' );
echo $g->render();
?>