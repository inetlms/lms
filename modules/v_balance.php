<?php

include_once 'ofc-library/open_flash_chart_object.php';

$fp = fopen('/tmp/file.csv', 'w');


   
function czysc ( $sText )
{
$sText = html_entity_decode($sText);
          $aSzukaj = array('ć','Ć','ś','Ś','ą','Ą','ż','Ż','ó','Ó','ł','Ł','ś','Ś','ź','Ź','ń','Ń','ę','Ę');
          $aZamien = array('c','C','s','S','a','A','z','Z','o','O','l','L','s','S','z','Z','n','N','e','E');
          $sOK = "abcdefghijklmnopqrstuvwxyz";
          $sOK .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

          $sText = str_replace($aSzukaj, $aZamien, $sText);
          $sTextN = "";

          for ( $i = 0; $i < strlen($sText); $i++ )
          {
               if ( strpos($sOK,$sText[$i]) === false )
                    $sTextN .= "_";
               else
                    $sTextN .= $sText[$i];
          }

          return $sTextN;
     }




$layout['pagetitle'] = 'Bilans kosztów';
$m=0;
$balance=$_POST['balance'];
if(isset($balance))
{
list($from,$none)=explode(' ',$balance['from']);
if(!$none) $none='00:00:00';
else $none.=':00';
$from=str_replace('/','-',$from).' '.$none;
list($to,$none)=explode(' ',$balance['to']);
if(!$none) $none='23:59:59';
else $none.=':59';
$to=str_replace('/','-',$to).' '.$none;
$balancedet=$voip->GetBalanceDet($from, $to, $balance['customerid']);
$SMARTY->assign('bd',$balancedet);
if($_POST['wykr']==1)
$SMARTY->assign('img','?m=v_makegraph&from='.str_replace(' ','%20',$from).'&to='.str_replace(' ','%20',$to).'&user='.$balance['customerid'].'&dummy='.time());
$balance['wykr']=$_POST['wykr'];

if(!empty($balance['customerid'])){
$kli[0]=$voip->GetAstId($balance['customerid']);
if(!isset($_POST['podzial_strefy'])){
$polaczenia=$voip->bal_pol1($kli[0], $from, $to);

}
else
 $polaczenia=$voip->bal_pol2($kli[0], $from, $to);
}
elseif(isset($_POST['podzial_klient'])){
$SMARTY->assign('kli',1);

 $kli=$voip->get_crd_users();
 if(!isset($_POST['podzial_strefy']))
 $polaczenia=$voip->bal_pol3($from, $to);
 else
 $polaczenia=$voip->bal_pol4($from, $to);

}elseif(!isset($_POST['podzial_strefy'])) {$kli[0]=-1; $polaczenia=$voip->bal_pol5($from, $to);}
elseif(isset($_POST['podzial_strefy'])){$kli[0]=-1; $polaczenia=$voip->bal_pol6($from, $to);}

for($m=0; $m<count($kli); $m++)
{
$g='';
unset($tab);


if($polaczenia && !isset($_POST['podzial_strefy']) && $kli[0]!=-1)
	foreach($polaczenia as $key=>$val){
	if($val['id_users']==$kli[$m]){
	$username=$val['sysname'];
	$tab[$voip->toutf8($val['desc'])]['sekundy']=$val['sum'];
	$tab[$voip->toutf8($val['desc'])]['koszt']=$val['cost'];
	}
	}

	if($polaczenia && !isset($_POST['podzial_strefy']) && $kli[0]==-1)  // dla wszystkich razem
	{
		foreach($polaczenia as $key=>$val){
				
		$tab[$voip->toutf8($val['desc'])]['sekundy']=$val['sum'];
		$tab[$voip->toutf8($val['desc'])]['koszt']=$val['cost'];
		
		}
	
	}
	
	if($polaczenia && isset($_POST['podzial_strefy']) && $kli[0]==-1)  // dla wszystkich razem
	{
	$SMARTY->assign('str',1); 		
		foreach($polaczenia['t1'] as $key=>$val){
				
		$tab[$voip->toutf8($val['desc'])]['sekundy']['t1']=$val['sum'];
		$tab[$voip->toutf8($val['desc'])]['koszt']['t1']=$val['cost'];
		
		}
		
		foreach($polaczenia['t2'] as $key=>$val){
				
		$tab[$voip->toutf8($val['desc'])]['sekundy']['t2']=$val['sum'];
		$tab[$voip->toutf8($val['desc'])]['koszt']['t2']=$val['cost'];
		
		}
		
		foreach($polaczenia['t3'] as $key=>$val){
				
		$tab[$voip->toutf8($val['desc'])]['sekundy']['t3']=$val['sum'];
		$tab[$voip->toutf8($val['desc'])]['koszt']['t3']=$val['cost'];
		
		}
	}
	
	
	
if($polaczenia && isset($_POST['podzial_strefy'])){      // jezeli mamy podzial na strefy to wykonujemy 3 osobne zapytania do kazdej
	$SMARTY->assign('str',1); 							//wiec musimu to jakos poskladac w calosc		

	foreach((array)$polaczenia['t1'] as $key=>$val){
	if($val['id_users']==$kli[$m]){
	$username=$val['sysname'];
	$tab[$voip->toutf8($val['desc'])]['sekundy']['t1']=$val['sum'];
	$tab[$voip->toutf8($val['desc'])]['koszt']['t1']=$val['cost'];
	}
	}
	
	foreach((array)$polaczenia['t2'] as $key=>$val){
	if($val['id_users']==$kli[$m]){
	$username=$val['sysname'];
	$tab[$voip->toutf8($val['desc'])]['sekundy']['t2']=$val['sum'];
	$tab[$voip->toutf8($val['desc'])]['koszt']['t2']=$val['cost'];
	}
	}
	
	foreach((array)$polaczenia['t3'] as $key=>$val){
	if($val['id_users']==$kli[$m]){
	$username=$val['sysname'];
	$tab[$voip->toutf8($val['desc'])]['sekundy']['t3']=$val['sum'];
	$tab[$voip->toutf8($val['desc'])]['koszt']['t3']=$val['cost'];
	}
	}


}


if($tab){
if(!$username){
fputcsv($fp, array('wszyscy'));
if(!isset($_POST['podzial_strefy']))
fputcsv($fp, array('strefa', 'sekundy', 'koszta'));
else
fputcsv($fp, array('strefa', 'sekundy t1', 'koszta t1', 'sekundy t2', 'koszta t2', 'sekundy t3', 'koszta t3'));
}
else{
fputcsv($fp, array($username));
if(!isset($_POST['podzial_strefy']))
fputcsv($fp, array('strefa', 'sekundy', 'koszta'));
else
fputcsv($fp, array('strefa', 'sekundy t1', 'koszta t1', 'sekundy t2', 'koszta t2', 'sekundy t3', 'koszta t3'));
}
foreach($tab as $key=>$val){

 // var_dump($val);
$key_czyste=czysc($key);
 if(!isset($_POST['podzial_strefy'])){
 $t[0]=$key_czyste;
 $t[1]=$val['sekundy'];
 $t[2]=$val['koszt'];
 fputcsv($fp, $t);}
 else
 {
 $t[0]=$key_czyste;
 $t[1]=$val['sekundy']['t1'];
 $t[2]=$val['koszt']['t1'];
 $t[3]=$val['sekundy']['t2'];
 $t[4]=$val['koszt']['t2'];
 $t[5]=$val['sekundy']['t3'];
 $t[6]=$val['koszt']['t3'];
 fputcsv($fp, $t);
 
 }

	if($_POST['wykr']==1){
	if(isset($_POST['podzial_strefy'])){
	$g.=$key_czyste."|".$tab[$key]['sekundy']['t1']."@".$tab[$key]['sekundy']['t2']."@".$tab[$key]['sekundy']['t3']."*";
	}
	else
	$g.=$key_czyste."|".$tab[$key]['sekundy']."|";
	}
}
}

if($_POST['wykr']==1){
$g=substr($g,0,-1);



}
if($tab)
$calosc[$voip->toutf8($username)]['reszta']=$tab;

if(isset($_POST['podzial_strefy'])){

if($tab && $_POST['wykr']==1)
$calosc[$voip->toutf8($username)]['wykres']=open_flash_chart_object( 1005, 700, 'http://'. $_SERVER['SERVER_NAME'] .'/ofc-library/chart-data.php?g='.$g, false );
}
else
if($tab && $_POST['wykr']==1)
$calosc[$voip->toutf8($username)]['wykres']=open_flash_chart_object( 1005, 700, 'http://'. $_SERVER['SERVER_NAME'] .'/ofc-library/chart-data2.php?g='.$g, false );
}
 fclose($fp);

$SMARTY->assign('lst',$calosc); 



}


else
{
$balance['from']=date('Y/m/d H:i',time()-2678400);
$balance['to']=date('Y/m/d H:i');
$balance['wykr']="1";
}

$SMARTY->assign('customers', $voip->GetCustomerNames());
$SMARTY->assign('listdata',$balance);

$SMARTY->display('v_balance.html');

?>
