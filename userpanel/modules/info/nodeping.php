<?php

global $LMS, $DB, $SMARTY;

function getinfofromping($ipek=NULL,$netdevid=NULL)
{
    global $DB;
	if (is_null($netdevid))
	    return $DB->GetRow('SELECT n.name nname, c.lastname, c.name FROM nodes n, customers c WHERE n.ipaddr=inet_aton(?) AND c.id=n.ownerid LIMIT 1',array($ipek));
	else 
	    return $DB->GetRow('SELECT n.name nname, c.name as lastname, c.model as name, c.location FROM nodes n, netdevices c WHERE n.ipaddr=inet_aton(?) AND c.id=n.netdev LIMIT 1',array($ipek));
}

$ipek = (isset($_GET['ip']) ? $_GET['ip'] : '127.0.0.1');

if (isset($_GET['refresh'])) $refresh = true; else $refresh = false;
if (!$refresh)
{
    $layout['popup'] = true;
    $packetsize = (isset($_GET['packetsize']) ? $_GET['packetsize'] : '64');
    $SMARTY->assign('packetsize',$packetsize);
    $SMARTY->assign('ipek',$ipek);
    $SMARTY->assign('typtest',(isset($_POST['typtest']) ? $_POST['typtest'] : 'icmp'));
    
    if ($ipek == '127.0.0.1') 
    {
	$klient='localhost';
	$komputer='localhost';
	$location='';
    } 
    else 
    {
	if (isset($_GET['netdev'])) $netdevid = $_GET['netdev']; else $netdevid=NULL;
	$wyn = GetInfoFromPING($ipek,$netdevid);
	$klient = $wyn['lastname'].' '.$wyn['name'];
	$komputer = $wyn['nname'];
	$location = $wyn['location'];
    }
    if (isset($_GET['netdev'])) 
    {
	$SMARTY->assign('typenode','netdev'); 
	$SMARTY->assign('location',$location);
    }
	else $SMARTY->assign('typenode','nodes');
    $SMARTY->assign('netdevid',$netdevid);
    $SMARTY->assign('klient',$klient);
    $SMARTY->assign('komputer',$komputer);
    $SMARTY->assign('popup',true);
    $SMARTY->display('module:nodeping.html');
}
else
{
    
    $czasy = array();
    $czasy = $_POST['czas'];
    $packetsize = (isset($_GET['packetsize']) ? $_GET['packetsize'] : '64');
    $typtest = (isset($_GET['typtest']) ? $_GET['typtest'] : (isset($_POST['typtest']) ? $_POST['typtest'] : 'icmp'));
    $stats = array();
    
    if (count($czasy)>21) 
    {
	unset($czasy[0]);
	$tmp = array();
	$tmp1=array();
	for ($i=1;$i<=count($czasy);$i++)
	{
	    $tmp[]=$czasy[$i];
	}
	$czasy=array();
	$czasy=$tmp;
    }

    $ping=NULL;
    $pinger = get_conf('monit.test_script_dir');
    $ping = exec("sudo $pinger --ip=".$ipek." --ps=".$packetsize." --t=".$typtest);
    $czasy[] = $czastmp = $ping;
    
    if (empty($_POST['stats']))
    {
	$stats['min'] = 0;
	$stats['max'] = 0;
	$stats['avg'] = 0;
	$stats['wyslano']=0;
	$stats['odebrano']=0;
    } else $stats = $_POST['stats'];
    
    $stats['wyslano']++;

    if ($czastmp != '-1')
    {
	$stats['odebrano']++;
	if ($czastmp < $stats['min']) $stats['min']=$czastmp;
	if ($czastmp > $stats['max']) $stats['max']=$czastmp;
	if ($stats['min'] <= 0) $stats['min'] = $czastmp;
	$tmpczas = 0;
	$count = count($czasy);
	if ($count!='0')
	{ 
	    for ($jk=0;$jk<$count;$jk++)
	    {
		if ($czasy[$jk]!='-1') $tmpczas = $tmpczas + $czasy[$jk];
	    }
	    $stats['avg'] = $tmpczas / count($czasy);
	} else $stats['avg'] = 'b.d';
    }
    
    $SMARTY->assign('packetsize',$packetsize);
    $SMARTY->assign('stats',$stats);
    $SMARTY->assign('ipek',$ipek);
    $SMARTY->assign('czasy',$czasy);
    $SMARTY->assign('typtest',$typtest);
    $SMARTY->assign('popup',true);
    $SMARTY->display('module:nodepingframe.html');
}


?>