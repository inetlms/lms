#!/usr/bin/php
<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2012 LMS iNET Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id: v 1.00 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */

?>
<?php
empty($_SERVER['SHELL']) && die('<br><Br>Sorry Winnetou, tylko powloka shell ;-)');
?>
<?php
    $option = array();
    function parseArgs()
    {
	$argv = $_SERVER['argv'];
	array_shift($argv);
	$out = array();
	foreach($argv as $arg) 
	{
	    if( substr($arg,0,2)=='--' )
	    {
		$eqPos = strpos($arg,'=');
		if($eqPos === false)
		{
		    $key = substr($arg,2);
		    $out[$key] = isset($out[$key]) ? $out[$key] : true;
		} 
		else 
		{
		    $key = substr($arg,2,$eqPos-2);
		    $out[$key] = substr($arg,$eqPos+1);
		}
	    }
	    else 
	    if (substr($arg,0,1)=='-')
	    {
		if (substr($arg,2,1)=='=')
		{
		    $key = substr($arg,1,1);
		    $out[$key] = substr($arg,3);
		}
		else
		{
		    $chars = str_split(substr($arg,1));
		    foreach($chars as $char)
		    {
			$key = $char;
			$out[$key] = isset($out[$key]) ? $out[$key] : true;
		    }
		}
	    }
	    else
	    {
		$out[]=$arg;
	    }
	}
	return $out;
    }
    function dberr()
    {
	global $DB;
	if ($DB->_error)
	{
	    fwrite(STDERR,"\n\nBLAD BAZY !!!\n\n");
	    foreach($DB->errors as $item)
	    {
		fwrite(STDERR,"\nQUERY: ".$item['query']."\n\nERROR:".$item['error']."\n\n\n");
	    }
	    die;
	}
    }
    function getBoolean($key,$default=false)
    {
	if (empty($key) || is_null($key) || !isset($key)) return $default;
	if (is_bool($key)) return $key;
	$value = strtoupper($key);
	if (in_array($value,array('T','TAK','Y','YES','1','ON','TRUE'))) return true;
	elseif(in_array($value,array('N','NIE','F','NO','0','OFF','FALSE')))return false;
	else return $default;
    }
    $option = parseArgs();
    $count_op = count($option);
    $err = false;
    if ($count_op==0) $err = true;
    $opcje = array('config-file', 'help', 'import', 'quiet', 'customers', 'terminal', 'pstn', 'enduser', 'price', 'config', 'subscription', 'billing', 'all', 'b_date', 'b_from', 'b_to', 'b_type', 'b_success',
		    'v', 'h', 'i', 'q', 'c', 't', 'p', 'e', 'pr', 'cg', 's', 'b');
    $blad = array();
    foreach ($option as $nazwa => $klucz) if (!in_array($nazwa,$opcje)) $err = true;
    if ( isset($option['config-file']) ) $option['config-file'] = $option['config-file']; 
    else $option['config-file'] = '/etc/lms/lms.ini';
    if (!is_readable($option['config-file']) )
    {
	fwrite(STDERR,"\n\nNie mozna odczytac pliku ".$option['config-file']." !!! \n\n\n");
	$err=true;
    }
    if (isset($option['help']) || isset($option['h']) || $err)
    {
	fwrite(STDOUT,"\n
iNET LMS Hiperus C5 v.1.0.0

POMOC

--config-file=     -> pelna sciezka do pliku lms.ini,
                      DEF.: --config-file=/etc/lms/lms.ini
--help, --h        -> wyswietlenie pomocy
--quiet, --q       -> cisza, bez informacji na ekranie
--import, --i      -> najpierw kasuje dane z bazy lms dotyczace VoIPa, 
                      a nastepnie pobiera dane z zew. serwisu


PRZELACZNIKI DO POBRANIA PODSTAWOWYCH DANYCH,

--customers, --c   -> pobiera dane o kontach VoIP , jezeli nie pobralismy 
                      danych o kontach to uzywanie nastepnych 
                      przelacznikow mija sie z celem.
--terminal, --t    -> informacje o terminalach
--pstn, p          -> informacje o przydzielonych pulach PSTN, wielkosci puli,
                      wykorzystanych numerach
--enduser, --e     -> informacje o uzytkownikach koncowych panelu 
                      administracyjnego
--price, --pr      -> informacje o cennikach
--subscription,--s -> informacje o abonamentach
--config, --cf     -> informacje o WLR
--all              -> pobiera wsztstkie powyzsze informacje

BILINGI - droga cierniowa :)
    zew serwer dosc dlugo zwraca informacje o bilingach, dlatego prosze uzbroic
    sie w cierpliwosc podczas pobierania.
    Przelacznik --import nie dziala dla bilingow, jezeli chcemy wyczyscic 
    sobie baze musimy to zrobic recznie.

--billing, --b     -> pobranie danych bilingowych
--b_type=          -> pobranie danych o określonym typie polaczen, DEF.:outgoing
                      dozwolone wartosci przelacznika:
                         all, incoming, outgoing, disa, forwarded, internal, vpbx
--b_success=       -> pobranie danych o statusie polaczenia, DEF.: yes
                      all(wszystkie), yes(zrealizowane), no(nie zrealizowane)

--b_from=          -> data poczatkowa pobieranych bilingow, RRRR-MM-DD
--b_to=            -> data koncowa dla pobieranych bulingow, RRRR-MM-DD
--b_date=          -> lekkie ulatwienie ;-), uzycie tego przelacznika spowoduje 
                      zignorowanie --b_from i --b_to,
                      dozwolone wartosci przelacznika :
                         nowday    : pobiera dane z dnia biezacego
                         leftday   : pobiera dane z dnia poprzedniego
                         nowmonth  : pobiera dane z biezacego miesiaca
                         leftmonth : pobiera dane z poprzedniego miesiaca
                         nowweek   : pobiera dane z biezacego tygodnia
\n\n");
    die;
    }
    
    $CONFIG = (array) parse_ini_file($option['config-file'],true);
    
    $CONFIG['directories']['sys_dir'] = (!isset($CONFIG['directories']['sys_dir']) ? getcwd() : $CONFIG['directories']['sys_dir']);
    $CONFIG['directories']['lib_dir'] = (!isset($CONFIG['directories']['lib_dir']) ? $CONFIG['directories']['sys_dir'].'/lib' : $CONFIG['directories']['lib_dir']);

    
    define('SYS_DIR',$CONFIG['directories']['sys_dir']);
    define('LIB_DIR',$CONFIG['directories']['lib_dir']);
    $_DBTYPE = $CONFIG['database']['type'];$_DBHOST=$CONFIG['database']['host'];$_DBUSER=$CONFIG['database']['user'];$_DBPASS=$CONFIG['database']['password'];$_DBNAME=$CONFIG['database']['database'];
    require(LIB_DIR.'/LMSDB.php');
    $DB=DBInit($_DBTYPE,$_DBHOST,$_DBUSER,$_DBPASS,$_DBNAME);
    if(!$DB){fwrite(STDERR,"\n\nBlad polaczenia z baza dancyh !!!\n\n");exit();}
    define('H_SESSION_FILE','/tmp/hiperus2.session');
    include(LIB_DIR.'/LMS.Hiperus.class.php');
    $HIPERUS = new LMSHiperus($DB);
    $now = date('Y-m-d',time());
    if (isset($option['quiet']) || isset($option['q'])) 
	$quiet = true; 
	else 
	$quiet=false;
    if (isset($option['import']) || isset($option['i'])) 
	$import = true; 
	else 
	$import=false;
    if (isset($option['customers']) || isset($option['c'])) 
	$option['customers'] = true; 
	else 
	$option['customers'] = false;
    if (isset($option['terminal']) || isset($option['t'])) 
	$option['terminal'] = true; 
	else 
	$option['terminal'] = false;
    if (isset($option['pstn']) || isset($option['p'])) 
	$option['pstn'] = true; 
	else 
	$option['pstn'] = false;
    if (isset($option['enduser']) || isset($option['e'])) 
	$option['enduser'] = true; 
	else 
	$option['enduser'] = false;
    if (isset($option['price']) || isset($option['pr'])) 
	$option['price'] = true; 
	else 
	$option['price'] = false;
    if (isset($option['config']) || isset($option['cg'])) 
	$option['config'] = true; 
	else 
	$option['config'] = false;
    if (isset($option['subscription']) || isset($option['s'])) 
	$option['subscription'] = true; 
	else 
	$option['subscription'] = false;
    if (isset($option['billing']) || isset($option['b'])) 
	$option['billing'] = true; 
	else 
	$option['billing'] = false;
    if (isset($option['all'])) 
	$option['all'] = true; 
	else 
	$option['all'] = false;
    if ($option['all'])
    {
	$option['customers']=true;
	$option['terminal']=true;
	$option['pstn']=true;
	$option['enduser']=true;
	$option['price']=true;
	$option['subscription']=true;
	$option['billing']=false;
    }
    if( $option['all'] || $option['customers'] )
    {
	
	if ($import) 
	{
	    if ( !$quiet )fwrite(STDOUT,"Czyszcze liste klientow w bazie LMS\n");
	    $DB->Execute('DELETE FROM hv_customers ;');
	}
	if ( !$quiet ) fwrite(STDOUT,"Pobieram liste klientow\n");
	$HIPERUS->ImportCustomersList();
	dberr();
    }
    // pobranie listy zaimportowanych kont VoIP
    $cus=$DB->GetAll('SELECT id, create_date FROM hv_customers ORDER BY id ASC ');
    $cus_count=count($cus);
    dberr();
    // Pobranie listy terminali
    if( ( $option['all'] || $option['terminal'] ) && $cus_count !==0 )
    {
	if ($import) 
	{
	    if ( !$quiet ) fwrite(STDOUT,"Czyszcze liste terminali w bazie LMS\n");
	    $DB->Execute('DELETE FROM hv_terminal ;');
	}
	if ( !$quiet ) fwrite(STDOUT,"Pobieram liste terminali\n");
	$HIPERUS->ImportTerminalList();
    }
    // pobranie informacji o numerach PSTN
    if ( ($option['all'] || $option['pstn']) && $cus_count!==0 )
    {
	if ($import)
	{
	    if(!$quiet) fwrite(STDOUT,"Czyszcze informacje numerach PSTN w bazie LMS\n");
	    $DB->Execute('DELETE FROM hv_pstn ;');
	    $DB->Execute('DELETE FROM hv_pstnrange ;');
	    $DB->Execute('DELETE FROM hv_pstnusage ;');
	}
	if(!$quiet) fwrite(STDOUT,"Pobieram informacje o numerach PSTN.\n");
	$HIPERUS->ImportPSTNList();
	$HIPERUS->ImportPSTNRangeList();
	$HIPERUS->ImportPSTNUsageList();
    }
    // informacje o użytkownikach koncowych panelu klienta
    if( ($option['all'] || $option['enduser'] ) && $cus_count!==0 )
    {
	if ($import)
	{
	    if (!$quiet) fwrite(STDOUT,"Czyszcze liste uzytkownikow panelu koncowego klienta w bazie LMS\n");
	    $DB->Execute('DELETE FROM hv_enduserlist ;');
	}
	if(!$quiet)fwrite(STDOUT,"Pobieram liste uzytkownikow panelu koncowego klienta\n");
	$HIPERUS->ImportEndUserList();
    }
    // informacje o cennikach
    if ( $option['all'] || $option['price'] )
    {
	if ($import)
	{
	    if (!$quiet) fwrite(STDOUT,"Czyszcze liste cennikow dla klienta koncowego w bazie LMS\n");
	    $DB->execute('DELETE FROM hv_pricelist ;');
	}
	if (!$quiet) fwrite(STDOUT,"Pobieram liste cennikow dla klienta koncowego\n");
	$HIPERUS->ImportPriceList();
    }
    // info o abonamentach
    if ( $option['all'] || $option['subscription'] )
    {
	if ($import)
	{
	    if (!$quiet) fwrite(STDOUT,"Czyszcze liste abonamentow zdefiniowanych przez resllera w bazie LMS\n");
	    $DB->Execute('DELETE FROM hv_subscriptionlist ;');
	}
	if (!$quiet) fwrite(STDOUT,"Pobieram liste abonamentow zdefiniowanych przez resllera\n");
	$HIPERUS->ImportSubscriptionList();
    }
    //if ($option['billing'] || $option['billing-file'])
    if ($option['billing'])
    {
	if(!$quiet)fwrite(STDOUT,"Pobieram liste bilingow\nProsze o cierplowosc ale zew. serwis dosc dlugo odpowiada przy bilingach.\n\n");
	if (isset($option['b_date']))
	{
	    $option['b_date'] = strtolower($option['b_date']);
	    if (!in_array($option['b_date'],array('nowday','leftday','nowmonth','leftmonth','nowweek'))) $option['b_date'] = 'nowday';
	    if (isset($option['b_from'])) unset($option['b_from']);
	    if (isset($option['b_to'])) unset($option['b_to']);
	    unset($option['b_from']);
	    unset($option['b_to']);
	}
	if (isset($option['b_type']))
	{
	    $option['b_type'] = strtolower($option['b_type']);
	    if (!in_array($option['b_type'],array('all','incoming','outgoing','disa','forwarded','internal','vpbx'))) $option['b_type'] = 'outgoing';
	} else $option['b_type'] = 'outgoing';
	if (isset($option['b_success']))
	{
	    $option['b_success'] = strtolower($option['b_success']);
	    if (!in_array($option['b_success'],array('all','yes','no'))) $option['b_success'] = 'yes';
	} 
	    else $option['b_success'] = 'yes';
	if(!isset($option['b_from'])||!isset($option['b_to'])||isset($option['b_date']))
	{   
	    unset($option['b_from']);
	    unset($option['b_to']);
	}
	if(!isset($option['b_from'])&&!isset($option['b_to'])&&!isset($option['b_date'])) $option['b_date'] = 'nowday';
	if(isset($option['b_from']) && isset($option['b_to']) && !isset($option['b_date']))
	{
	    $from = $option['b_from'];
	    $to = $option['b_to'];
	}
	if (isset($option['b_date']))
	{
	    switch ($option['b_date'])
	    {
		case 'nowday' : $from = $now; $to = $now; break;
		case 'leftday' : $from = date('Y-m-d',time()-86400); $to = $from; break;
		case 'nowmonth' : $from = date('Y-m')."-01"; $to = $now; break;
		case 'nowweek' : $from = date('Y-m-d',time()-604800); $to = date('Y-m-d',time()); break;
		case 'leftmonth' : $from = date('Y-m-d',mktime(0,0,0,date('m')-1,1,date('Y'))); $to = date('Y-m-t',mktime(0,0,0,date('m')-1,1,date('Y'))); break;
	    }
	}
	if (!$quiet) fwrite(STDOUT,"Pobieram biling od dnia : ".$from." do ".$to."\n");
	$HIPERUS->ImportBilling($from,$to,$option['b_success'],$option['b_type'],NULL,$quiet);
    }
    if ($option['config']) $HIPERUS->GetConfigHiperus();
?>