<?php
class LMSVOIP
{
var $lmsdb;
var $config;
var $mondir;
var $fax_outgoingdir;
var $fax_incomingdir;
var $fax_statusdir;
var $rategroups;
var $mailboxdir;
var $dialplan_file;
var $dialplan = array();
var $incvoipdir;
var $ivrdir;
var $wsdl;
var $errors = null;

function LMSVOIP(&$lmsdb, &$config)
{
$this->lmsdb = $lmsdb;
$this->config = $config;
$this->mondir = $config['mondir'];
$this->fax_outgoingdir = $config['fax_outgoingdir'];
$this->fax_incomingdir = $config['fax_incomingdir'];
$this->fax_statusdir = $config['fax_statusdir'];
$this->mailboxdir = $config['mailboxdir'];
$this->dialplan_file = $config['dialplan_file'];
$this->incvoipdir = $config['incvoipdir'];
$this->ivrdir = $config['ivrdir'];
$this->wsdl = new SoapClient($config['wsdlurl'], array('login' => $config['wsdllogin'], 'password' => $config['wsdlpassword'], 'cache_wsdl' => WSDL_CACHE_NONE));
$this->rategroups = $this->wsdl->makerategroups();
if(!is_dir($this->mondir))
	$this->errors = 'Katalog <B>'.dirname($this->mondir).'</B> nie jest zamontowany. Niektóre funkcjonalności mogą nie działać prawidłowo.';
}

function toutf8(&$d)
{
	if(is_array($d))
	foreach($d as $key => $val)
	{
		$d[$key] = iconv('ISO-8859-2', 'UTF-8//IGNORE', $val);
	}
	else $d = iconv('ISO-8859-2', 'UTF-8//IGNORE', $d);
	return $d;
}

function toiso(&$d)
{
	if(is_array($d))
	foreach($d as $key => $val)
	{
		$d[$key] = iconv('UTF-8', 'ISO-8859-2//IGNORE', $val);
	}
	else $d = iconv('UTF-8', 'ISO-8859-2//IGNORE', $d);
	return $d;
}

function GetNetworkList()
{
if($networks = $this->lmsdb->GetAll('SELECT id, name, start, count AS size FROM v_netlist ORDER BY name'))
{
	$size = 0; $assigned = 0;

	foreach($networks as $idx => $row)
	{
		$row['assigned'] = $this->wsdl->_GetNetworkList($row['start'], $row['start'] + $row['size']);
		$row['end']=sprintf('%010s',(int)$row['start'] + $row['size'] - 1);
		$networks[$idx] = $row;
		$size += $row['size'];
		$assigned += $row['assigned'];
	}
	$networks['size'] = $size;
	$networks['assigned'] = $assigned;
}
return $networks;
}

function NetworkExists($id)
{
return $this->lmsdb->GetOne('SELECT COUNT(id) FROM v_netlist WHERE id = ?', array($id));
}

function GetNetworkRecord($id)
{
$network = $this->lmsdb->GetRow('SELECT id, name, start, count AS size FROM v_netlist WHERE id = ?', array($id));

$network['assigned'] = $this->wsdl->_GetNetworkRecord($network);
$network['end'] = sprintf('%010s', (int)$network['start'] + $network['size'] - 1);
$network['page'] = 1;
$network['pages'] = 1;
$nodes = $this->wsdl->_getnodes((int)$network['start'], (int)$network['end']);
for($i = 0; $i < $network['size']; $i++)
{
$j = sprintf('%010d', (int)$network['start'] + $i);
$network['nodes']['id'][$i] = ($nodes[$j] ? $nodes[$j]['id'] : 0 );
$network['nodes']['name'][$i] = ($nodes[$j] ? $nodes[$j]['name'] : 0 );
$network['nodes']['address'][$i] = $j;

}
$network['rows'] = ceil(sizeof($network['nodes']['address']) / 4);
$network['pageassigned'] = $network['assigned'];
$network['free'] = $network['size'] - $network['assigned'];
return $network;
}

function NetworkUpdate($d)
{
$count = (int)$d['end'] - (int)$d['start'] + 1;
$this->lmsdb->Execute('UPDATE v_netlist SET name = ?, start = ?, count = ? WHERE id = ?', array($d['name'], $d['start'], $count, $d['id']));
}

function NetworkAdd($d)
{
$this->lmsdb->Execute('INSERT INTO v_netlist (name, start, count) VALUES (?, ?, ?)', array($d['name'], $d['start'], $d['count']));
return $this->lmsdb->GetLastInsertID('v_netlist');
}

function NetworkDelete($id)
{
$this->lmsdb->Execute('DELETE FROM v_netlist WHERE id = ?', array($id));
}

function GetNetworks()
{
if($netlist = $this->lmsdb->GetAll('SELECT id, name, start AS address, count AS prefix FROM v_netlist ORDER BY name'))
return $netlist;
}

function get_billing_details($tslist)
{
if(is_array($tslist['id'])) foreach($tslist['id'] as $key => $val)
{
	if($this->lmsdb->GetOne('SELECT COUNT(id) FROM billing_details WHERE documents_id = ?', array($tslist['docid'][$key])) > 0)
		$tslist['details'][$key] = 1;
	else 
		$tslist['details'][$key] = 0;
}
}

function get_billing_details2($tslist)
{
if(is_array($tslist)) foreach($tslist as $key => $val) if(is_array($val))
{
	if($this->lmsdb->GetOne('SELECT COUNT(id) FROM billing_details WHERE documents_id = ?', array($val['id'])) > 0)
		$tslist[$key]['details'] = 1;
	else 
		$tslist[$key]['details'] = 0;
}
return $tslist;
}

function update_user($d)
{
$u = $this->lmsdb->GetRow('SELECT lastname, name, email, address, zip, city, ten, pin FROM customers WHERE id = ?', array($d['id']));
$u['password'] = md5($u['pin']);
$d['type'] = 'postpaid';
$this->wsdl->_update_user($d, $u);
}

function fax_outbox($u, $limit = 0)
{
$res = $this->lmsdb->GetAll('SELECT * FROM v_fax WHERE customerid = ? ORDER BY id DESC', array($u));
$user = $this->wsdl->GetAstId($u);
$status = '';
if(is_array($res)) foreach($res as $key => $val)
{
	$statusfile = $this->fax_statusdir . $user . '_' . $val['uniqueid'];
	if(is_file($statusfile))
	{
		$fp = fopen($statusfile, 'r');
		while(!feof($fp))
		{
			$line = fgets($fp);
			if(substr($line, 0, 7) == 'Status:') $status = substr($line, 8);
		}
		fclose($fp);
	}
	if(!$status)
		if($val['data'] + 600 < time()) $status = 'Błąd';
			else $status = 'Przekazany do wysłania';
	$res[$key]['status'] = $status;
	if(file_exists($this->fax_outgoingdir . $user . '/' . $val['uniqueid'] . '.tif'))
		$res[$key]['allowprint'] = true;
}
return $res;
}

function ui_deletefin($d,$user)
{
$uid = $this->wsdl->GetAstId($user);
foreach($d as $val)
{
	$fname = $this->fax_incomingdir . $uid . '/' . $this->wsdl->_faxprint($user, $val, '') . '.tif';
	if(is_file($fname))
		@unlink($fname);
}
}

function ui_deletefout($d, $user)
{
$uid = $this->wsdl->GetAstId($user);
foreach($d as $val)
{
	$uniq = $this->lmsdb->GetOne('SELECT uniqueid FROM v_fax WHERE id = ? AND customerid = ?', array($val, $user));
	if($uniq)
	{
		$fname = $this->fax_outgoingdir . $uid . '/' . $uniq . '.tif';
		$this->lmsdb->Execute('DELETE FROM v_fax WHERE id = ?', array($val));
		@unlink($fname);
	}
}
}

function ui_faxsa($id, $user)
{
$out = $this->lmsdb->GetRow('SELECT nr_from, nr_to, uniqueid, filename FROM v_fax WHERE id = ? AND customerid = ?', array($id, $user));
if(!$out) return;
$out['id_ast_sip'] = $this->wsdl->_ui_faxsa($out['nr_from']);
return $out;
}

function preparetofax($f, $nrfrom, $nrto, $user = 0)
{
$subdir = $this->wsdl->GetAstId($user);
do
	$filename = substr(md5(uniqid(rand(), true)), -10, 8);
while(file_exists($this->fax_outgoingdir . $subdir . '/' . $filename . '.tif'));
if(strlen($nrto) == 9 and $nrto[0] != '0')
	$nrto = '0' . $nrto;
$fname = $nrfrom . '-' . $nrto . '-' . $filename . '.tif';
execute_program('gs', '-q -dNOPAUSE -dBATCH -r204x98 -dSAFER -sDEVICE=tiffg3 -sOutputFile=' . $this->fax_outgoingdir . $fname . ' -f ' . $f['tmp_name']);
$this->lmsdb->Execute('INSERT INTO v_fax (nr_from, nr_to, data, customerid, uniqueid, filename) VALUES (?, ?, ?NOW?, ?, ?, ?)', array($nrfrom, $nrto, $user, $filename, $f['name']));
}

function preparetofax_again($f, $nrfrom, $nrto, $user = 0)
{
$subdir = $this->wsdl->GetAstId($user);
if(!file_exists($this->fax_outgoingdir . $subdir . '/' . $f . '.tif')) return false;
do
	$filename = substr(md5(uniqid(rand(), true)), -10, 8);
while(file_exists($this->fax_outgoingdir . $subdir . '/' . $filename . '.tif'));
if(strlen($nrto) == 9 and $nrto[0] != '0')
	$nrto = '0' . $nrto;
$fname = $nrfrom . '-' . $nrto . '-' . $filename . '.tif';
copy($this->fax_outgoingdir . $subdir . '/' . $f . '.tif', $this->fax_outgoingdir . $fname);
$forig = $this->lmsdb->GetOne('SELECT filename FROM v_fax WHERE uniqueid = ?', array($f));
$this->lmsdb->Execute('INSERT INTO v_fax (nr_from, nr_to, data, customerid, uniqueid, filename) VALUES (?, ?, ?NOW?, ?, ?, ?)',array($nrfrom, $nrto, $user, $filename, $forig));
return true;
}

function GetTaxId()
{
return $this->lmsdb->GetOne('SELECT id FROM taxes WHERE value = 23');
}

function ImportInvoice($date)
{
global $LMS;

if(!$date)
	$date = date('Y/m/d');
list($year, $month, $day) = explode('/',$date);	

$alltaxes = $LMS->GetTaxes();
foreach($alltaxes as $val) if($val['id'] == $this->config['taxid'])
{
	$tax = $val['value'];
	$taxid = $val['id'];
	$this->wsdl->UpdateTax($val['value']);
	break;
}
if(!$tax)
{
	if($year >= 2011) $tax = 23;
	else $tax = 22;
}
if(!$taxid) $taxid = 1;

$tax = $tax / 100 + 1;

$customers = $this->wsdl->_ImportInvoice_customers($day);
if(is_array($customers)) foreach($customers as $val)
{
	$ab = $this->wsdl->_ImportInvoice_ab($val['id']);
	$now = mktime(1, 0, 0, $month, $day, $year);
	$last = strtotime('-1 month', $now);
	$from = date('Y-m-d H:i:s', $last);
	$to = str_replace('/','-', $date) . ' 01:00:00';
	
	$imp = $this->wsdl->_ImportInvoice_imp($val['id'], $from, $to);
	$netto = $imp + $ab['amount'];
	
	$this->wsdl->_ImportInvoice_updatefreesec($ab['free'] * 60, $val['id']);
	
	$addserv = $this->wsdl->billaddserv($val['id']);
	$netto += $addserv['sum'];
	if($netto == 0) continue;

	$daybegin = mktime(0, 0, 0, $month, $day, $year);
	$dayend = mktime(23, 59, 59, $month, $day, $year);
	$docid = $this->lmsdb->GetOne('SELECT id FROM documents WHERE cdate >= ? AND cdate <= ? AND customerid = ? AND type = ?',array($daybegin, $dayend, $val['lmsid'], DOC_INVOICE));
	if($docid)
	{
		$itemid = $this->lmsdb->getone('SELECT MAX(itemid) FROM invoicecontents WHERE docid = ?',array($docid));
		$itemid++;
	}
	else
	{
		$numberplan = $this->lmsdb->GetOne('SELECT id FROM numberplans WHERE doctype = ? AND isdefault = 1', array(DOC_INVOICE));
		if(!$numberplan) $numberplan = 0;
		$number = $LMS->GetNewDocumentNumber(DOC_INVOICE, $numberplan, $now);
		$urow = $this->lmsdb->GetRow('SELECT lastname, name, address, city, zip, ssn, ten, divisionid FROM customers WHERE id = ?', array($val['lmsid']));
		$division = $this->lmsdb->GetRow('SELECT name, address, city, zip, countryid, ten, regon,
				account, inv_header, inv_footer, inv_author, inv_cplace 
				FROM divisions WHERE id = ? ;',array($urow['divisionid']));
		$this->lmsdb->Execute('INSERT INTO documents (number, numberplanid, type, customerid, name, address, zip, city, ten, ssn, cdate, sdate, paytime, paytype, divisionid, div_name, div_address, div_city, div_zip, div_countryid, div_ten, div_regon, div_account, div_inv_header, div_inv_footer, div_inv_author, div_inv_cplace) VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 2, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($number, $numberplan, $val['lmsid'], $urow['lastname'].' '.$urow['name'], $urow['address'], $urow['zip'], $urow['city'], $urow['ten'], $urow['ssn'], $now, $now, 7, $urow['divisionid'],
			($division['name'] ? $division['name'] : ''),
			($division['address'] ? $division['address'] : ''), 
			($division['city'] ? $division['city'] : ''), 
			($division['zip'] ? $division['zip'] : ''),
			($division['countryid'] ? $division['countryid'] : 0),
			($division['ten'] ? $division['ten'] : ''), 
			($division['regon'] ? $division['regon'] : ''), 
			($division['account'] ? $division['account'] : ''),
			($division['inv_header'] ? $division['inv_header'] : ''), 
			($division['inv_footer'] ? $division['inv_footer'] : ''), 
			($division['inv_author'] ? $division['inv_author'] : ''), 
			($division['inv_cplace'] ? $division['inv_cplace'] : ''),
			));
		$docid = $this->lmsdb->GetLastInsertID("documents");
		$itemid = 1;
	}
	$this->lmsdb->Execute('INSERT INTO invoicecontents (docid, value, taxid, prodid, content, count, description, tariffid, itemid, pdiscount, vdiscount) VALUES (?, ?, ?, \'\', \'szt\', 1, ?, 0, ?, 0, 0)', array($docid, round($tax*$netto,2), $taxid, 'Usługi telekomunikacyjne', $itemid));
	
	$this->lmsdb->Execute('INSERT INTO cash (time, value, taxid, customerid, comment, docid, itemid) VALUES (?, ?, ?, ?, ?, ?, ?)', array($now, round($tax * $netto, 2) * -1, $taxid, $val['lmsid'], 'Usługi telekomunikacyjne', $docid, $itemid));
	
	echo "CID: {$val['lmsid']} VAL: " . round($tax * $netto, 2) . " DESC: Usługi telekomunikacyjne\n";
	
	$cachedrates = array();
	$konta = $this->wsdl->_ImportInvoice_konta($val['id']);
	foreach($konta as $konto)
	{
		$ab = $this->wsdl->_ImportInvoice_abbd($konto['id_subscriptions']);
		if($ab['amount']>0)
			$this->lmsdb->Execute('INSERT INTO billing_details (documents_id, name, value) VALUES (?, ?, ?)', array($docid, $ab['name'], $ab['amount']));
		$pol = $this->wsdl->_ImportInvoice_pol($val['id'], $from, $to, $konto['accountcode']);
		$price = array();
		if(is_array($pol)) foreach($pol as $po)
		{
			if($cachedrates[$po['id_rates']])
				$rategr = $cachedrates[$po['id_rates']];
			else
			{
				$rategr = $this->wsdl->_ImportInvoice_rategr($po['dst'], $po['id_rates']);
				$cachedrates[$po['id_rates']] = $rategr;
			}
			$price[$rategr] += $po['cost'];
		}
		foreach($price as $rtg => $cost) if($cost > 0)
		{
			$name = $this->rategroups[$rtg] . ' - konto ' . $konto['accountcode'];
			$this->lmsdb->Execute('INSERT INTO billing_details (documents_id, name, value) VALUES (?, ?, ?)', array($docid, $name, $cost));
		}
	}
	
	if(is_array($addserv['data'])) foreach($addserv['data'] as $adds) if($adds['price'] > 0)
	{
		$name = $adds['dname'].' - '.$adds['name'];
		$this->lmsdb->Execute('INSERT INTO billing_details (documents_id, name, value) VALUES (?, ?, ?)', array($docid, $name, $adds['price']));
	}

}

$users = $this->wsdl->GetCustomerNames();
foreach($users as $us)
{
	$this->wsdl->UpdateCustomerBalance($us['id'], -$LMS->GetCustomerBalance($us['id']));
}
if(isset($this->config['voip_timeswitch']) and $this->config['voip_timeswitch'] == 1) $this->wsdl->EnableTimeAccounts($date);
}

function export_user($lmsid, $type = 'postpaid')
{
$u = $this->lmsdb->GetRow('SELECT lastname, name, email, address, zip, city, ten, pin FROM customers WHERE id = ?',array($lmsid));
$u['password'] = md5($u['pin']);
$this->wsdl->_export_user($lmsid, $type, $u);
$this->lmsdb->Execute('INSERT INTO v_exportedusers VALUES (?)', array($lmsid));
}

function CustomerExists($id)
{
if($this->lmsdb->GetOne('SELECT COUNT(*) FROM v_exportedusers WHERE lmsid = ?',array($id)) > 0)
	return true;
else
	return false;
}

function GetState()
{
        $api = new floAPI($this->config['voip_as_login'], $this->config['voip_as_pass'], $this->config['voip_as_host']);
        $out = array();
        $out['clients'] = $api->request('COMMAND', array('COMMAND' => 'sip show peers'));
        $out['channels'] = $api->request('COMMAND', array('COMMAND' => 'core show channels'));
        $api->close();
return $out;
}

function reload_dialplan()
{
        $api = new floAPI($this->config['voip_as_login'], $this->config['voip_as_pass'], $this->config['voip_as_host']);
        $api->request('COMMAND', array('COMMAND' => 'dialplan reload'));
        $api->close();
}

function DeleteCustomer($lmsid)
{
$this->wsdl->_DeleteCustomer($lmsid);
$this->lmsdb->Execute('DELETE FROM v_exportedusers WHERE lmsid = ?', array($lmsid));
}

function faxprint($u, $id, $type)
{
$user = $this->wsdl->GetAstId($u);
switch($type)
{
        case 'incoming':
		$file = $this->fax_incomingdir . $user . '/' . $this->wsdl->_faxprint($u, $id, $type) . '.tif';
        break;

        case 'outgoing':
        	$uniqid = $this->lmsdb->GetOne('SELECT uniqueid FROM v_fax WHERE customerid = ? AND id = ?', array($u, $id));
        	if(!$uniqid) return null;
        	$file = $this->fax_outgoingdir . $user . '/' . $uniqid . '.tif';
        break;

        default:
        return null;
}
if(file_exists($file)) return $file;
return null;
}

function GetUserToSettings($id, $field)
{
return $this->lmsdb->GetRow('SELECT lastname, name, ' . $field . ' AS login, pin FROM customers WHERE id = ?', array($id));
}

function GetTariff($id)
{
$tariff = $this->wsdl->GetTariff($id);
foreach((array)$tariff['idlms'] as $val)
{
	$temp = $this->lmsdb->GetRow('SELECT id, ' . $this->lmsdb->Concat('UPPER(lastname)', "' '", 'name') . ' AS customername FROM customers WHERE id = ? AND deleted = 0', array($val['lmsid']));
	$temp['customername'] .= ' ('.$val['name'].')';
	$tariff['customers'][] = $temp;
}
return $tariff;
}

function GetCustomersWithT($id)
{
$cust = $this->wsdl->GetCustomersWithT($id);
foreach((array)$cust['idlms'] as $val)
{
	$temp = $this->lmsdb->GetRow('SELECT id, ' . $this->lmsdb->Concat('UPPER(lastname)', "' '", 'name').' AS customername FROM customers WHERE id = ? AND deleted = 0', array($val['lmsid']));
	$temp['customername'] .= ' ('.$val['name'].')';
	$cust['customers'][] = $temp;
}
return $cust;
}

function ivr_uploadfile($file, $user)
{
$us = $this->wsdl->GetAstId($user);
if(!is_dir($this->ivrdir . $us)) mkdir($this->ivrdir . $us);
$roz = substr($file['name'], -3);
do
	$filename = substr(md5(uniqid(time())), 8, 8) . '.' . $roz;
while(file_exists($this->ivrdir . $us . '/' . $filename));
execute_program('sox', $file['tmp_name'].' -r 8000 -c 1 -s ' . $this->ivrdir . $us . '/' . $filename);
return $filename;
}

function ivr_deletefile($file, $user)
{
$us = $this->wsdl->GetAstId($user);
@unlink($this->ivrdir . $us . '/' . $file);
}

function CustomerStats(&$customerdata)
{
$customerdata['voip'] = $this->lmsdb->GetOne('SELECT COUNT(lmsid) FROM v_exportedusers;');
}

function str_split($string, $split_length = 1)
{
$array = array();
$i = 0;
$len = strlen($string);
do
{
        $part = '';
        for ($j = 0; $j < $split_length; $j++)
        {
                $part .= $string{$i};
                $i++;
        }
        $array[] = $part;
}
while ($i < $len);
return $array;
}

function parse_dialplan()
{
$tmp = @file($this->dialplan_file);
if($tmp and is_array($tmp))
foreach($tmp as $val)
{
	$val = trim($val);
	if(!$val) continue;
	if(!preg_match('/^exten => (\d{2})\/(0\d{9}),1,Dial\(SIP\/(0\d{9})\)$/', $val, $match)) continue;
	$this->dialplan[] = array('exten' => $match[1], 'clid' => $match[2], 'dst' => $match[3]);
}
}

function add_to_dialplan($nr, $id)
{
$this->parse_dialplan();
$numbers = $this->wsdl->get_numbers_by_id($id);
$this->delete_old_dialplan($id, $numbers);
foreach($nr as $key => $val)
if(preg_match('/^\d{2}$/', $val))
	foreach($numbers as $number) $this->dialplan[] = array('exten' => $val, 'clid' => $number, 'dst' => $key);
$this->write_dialplan();
}

function write_dialplan()
{
$fp = fopen($this->dialplan_file, 'w');
foreach($this->dialplan as $val)
if($val['dst'] != $val['clid'])
{
	$line = 'exten => ' . $val['exten'] . '/' . $val['clid'] . ',1,Dial(SIP/' . $val['dst'] . ")\n";
	fputs($fp, $line);
	$line = 'exten => ' . $val['exten'] . '/' . $val['clid'] . ",2,Hangup\n";
	fputs($fp, $line);
}
fclose($fp);
}

function delete_old_dialplan($id, $numbers)
{
foreach($this->dialplan as $key => $val)
	if(in_array($val['dst'], $numbers)) unset($this->dialplan[$key]);
}

function NodeUpdate($d)
{
$accountcode = $this->wsdl->NodeUpdate($d);
if(is_file($this->incvoipdir . $d['id'] . '.conf'))
{
	@unlink($this->incvoipdir . $d['id'] . '.conf');
	$conf = file($this->incvoipdir . '0.conf');
	$fp = fopen($this->incvoipdir . '0.conf', 'w');
	foreach((array)$conf as $val)
	{
		$tmp = explode('/', $val);
		if(trim($tmp[count($tmp) - 1]) != $accountcode and trim($val) != '')
			fputs($fp, $val . "\n");
	}
	fflush($fp);
	fclose($fp);
}
if(($user = $d['incuser']) && ($pass = $d['incpass']) && ($host = $d['inchost'])) $this->write_incvoip($user, $pass, $host, $d['id'], $accountcode);
}

function write_incvoip($user, $pass, $host, $num, $number)
{
$out = array();
$fp = fopen($this->incvoipdir . '0.conf', 'a');
fputs($fp, "register => $user:$pass@$host/$number\n");
fclose($fp);
$dns = dns_get_record($host, DNS_A);
if(count($dns) == 0) return;
$i = 0;
foreach($dns as $val)
{
	$i++;
	$out[] = "[${num}_$i]";
	$out[] = "name=${num}_$i";
	$out[] = 'type=peer';
	$out[] = 'allow=alaw,ulaw';
	$out[] = "host={$val['ip']}";
	$out[] = 'qualify=no';
	$out[] = 'insecure=no';
	$out[] = 'context=incoming';
}
$fp = fopen($this->incvoipdir . $num . '.conf', 'w');
foreach($out as $val) fputs($fp, "$val\n");
fclose($fp);
}

function GetNode($id)
{
$res = $this->wsdl->GetNode($id);
if(is_file($this->incvoipdir . $id . '.conf'))
{
	$tmp = file($this->incvoipdir . '0.conf');
	foreach((array)$tmp as $val)
		if(preg_match('/^register => ([^:]+):([^@]+)@([a-z0-9.]+)\/(\d+)$/i', $val, $m) and $m[4] == $res['name'])
		{
			$res['incuser'] = $m[1];
			$res['incpass'] = $m[2];
			$res['inchost'] = $m[3];
		}
}
return $res;
}

function check_monitor(&$d)
{
if(!($login = $d['login'])) return;
$wh = array('src','dst');
$jest = false;
foreach($wh as $w)
{
	if(is_dir($this->mondir . $login . '/' . $d[$w]) && is_file($this->mondir . $login . '/' . $d[$w] . '/' . $d['id'] . '.wav')) $jest = true;
}
$d['monitor'] = $jest;
}

public function GetCdrList($from, $to, $c, $order, $fnr = '', $tnr = '', $dir = null, $rategroup = null, $stat = null, $start=null, $limit=null)
{
	$res = $this->wsdl->GetCdrList($from, $to, $c, $order, $fnr, $tnr, $dir, $rategroup, $stat, $start, $limit);
	foreach($res as $k => $v) $this->check_monitor($res[$k]);
	return $res;
}

function uilisten($cid, $id)
{
$login = $this->wsdl->GetCustomerLogin($cid);
$path = $this->mondir . $login;
$fname = null;
$d = dir($path);
while (false !== ($entry = $d->read()))
	if(is_dir($path . '/' . $entry) && $entry != '.' && $entry != '..')
	{
		$d2 = dir($path . '/' . $entry);
		while (false !== ($entry2 = $d2->read()))
			if(is_file($path . '/' . $entry . '/' . $entry2) && $entry2 == $id . '.wav')
				$fname = $path . '/' . $entry . '/' . $entry2;
		$d2->close();
	}
$d->close();
return $fname;
}

function fax_inbox($user, $limit = 0)
{
$subdir = $this->wsdl->GetAstId($user);
if(!is_dir($this->fax_incomingdir . $subdir)) return null;
$out = array();
foreach(glob($this->fax_incomingdir . $subdir . '/*.tif') as $filename)
{
	$file = basename($filename);
	$uniqid = substr($file, 0, -4);
	$res = $this->wsdl->GetCallByUid($uniqid, $subdir);
	if(!$res) continue;
	$out[] = $res;
}
return $this->msort($out, 'id', false);
}

private function msort($array, $id = 'id', $sort_ascending = true) {
	$temp_array = array();
	while(count($array) > 0)
	{
		$lowest_id = 0;
		$index = 0;
		foreach ((array)$array as $item)
		{
			if(isset($item[$id]))
			{
				if($array[$lowest_id][$id])
				{
					if(strtolower($item[$id]) < strtolower($array[$lowest_id][$id]))
						$lowest_id = $index;
				}
			}
			$index++;
		}
		$temp_array[] = $array[$lowest_id];
		$array = array_merge(array_slice($array, 0, $lowest_id), array_slice($array, $lowest_id + 1));
	}
	if ($sort_ascending) {
		return $temp_array;
	} else {
		return array_reverse($temp_array);
	}
}

}
?>
