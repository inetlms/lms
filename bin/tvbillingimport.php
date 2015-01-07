<?php
/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2013 LMS Developers
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
 *  $Id$
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2011 ITMSOFT
 *  1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  
 
 */
include('/etc/lms/init_lms.php');
if (get_conf('jambox.enabled')) {
    require_once(LIB_DIR.'/LMS.tv.class.php');
    $LMSTV = new LMSTV($DB,$AUTH,$CONFIG);
}

$end = cal_days_in_month(CAL_GREGORIAN, Date("m"), Date("Y"));

$start_date = Date("Y-m-02", strtotime("last month")); 
$end_date = Date("Y-m-".$end); 
//$end_date = Date("Y-m-d"); 
echo $start_date;
echo $end_date;

$res = $LMSTV->GetBillingEvents($start_date, $end_date);

print_r($res);

if (count($res)) {

	foreach ($res as $key => $r){
		try{
		
				/*$sql = "INSERT INTO tv_billingevent (customerid, account_id, be_selling_date, be_desc, be_vat, be_gross, be_b2b_netto, group_id, cust_number, package_id, hash, beid)
				values (".(empty($r['cust_external_id']) ? 0 : $r['cust_external_id']).",
				".$r['account_id'].",
				'".$r['be_selling_date']."',
				'".$r['be_desc']."',
				".$r['be_vat'].",
				".$r['be_gross'].",
				".$r['be_b2b_netto'].",
				".(empty($r['group_id']) ? 0 : $r['group_id']).",
				'".$r['cust_number']."',
				".$r['package_id'].",
				'".md5($r['id'])."',
				".$r['id']."); ";
		
				print_r($r);
				
				 $DB->Execute($sql); */
		
				 $DB->Execute('INSERT INTO tv_billingevent (customerid, account_id, be_selling_date, be_desc, be_vat, be_gross, be_b2b_netto, group_id, cust_number, package_id, hash, beid)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
				array(
				empty($r['cust_external_id']) ? 0 : $r['cust_external_id'],
				$r['account_id'],
				$r['be_selling_date'],
				$r['be_desc'],
				$r['be_vat'],
				$r['be_gross'],
				$r['be_b2b_netto'],
				empty($r['group_id']) ? 0 : $r['group_id'],
				$r['cust_number'],
				$r['package_id'],
				md5($r['id']),
				$r['id'], ));
				
		} catch (Exception $e){
			print_r($e);
		}
	}
}


?>
