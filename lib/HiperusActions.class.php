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

define("CDR_FILE_PATH","/tmp");
require_once(LIB_DIR.'/HiperusLib.class.php');
class HiperusActions {

    public static function CreateCustomer($c_data) {
        $hlib = new HiperusLib();
        $req = new stdClass();
        foreach($c_data as $key=>$val) {
            $req->$key = $val;
        }
        $ret = $hlib->sendRequest("AddCustomer",$req);
        if(!$ret) {
            throw new Exception("Nie można utworzyć klienta: ".$c_name);
        }
        if(!$ret->success) {
            throw new Exception("Nie można utworzyć klienta.\n".$ret->error_message);
        }
        if(!$ret->result_set[0]->id)
            throw new Exception("Nie można ustalić identyfikatora klienta: ".$c_name);
        return $ret->result_set[0]->id;
    }

    public static function CreateSIPTerminal($username,$password,$customer_name=null,$customer_id=null,$pricelist_name=null,$pricelist_id=null) {
        if(!$customer_name && !$customer_id)
            throw new Exception("Identyfikator klienta lub nazwa klienta jest wymagana");
        if(!$pricelist_name && !$pricelist_id)
            throw new Exception("Identyfikator cennika lub nazwa cennika jest wymagana");
        $hlib = new HiperusLib();
        $req = new stdClass();
        if($customer_name) {
            $creq = new stdClass();
            $creq->name = $customer_name;
            $ret = $hlib->sendRequest("SearchCustomer",$creq);
            if(!$ret->success === false)
                throw new Exception("Błąd podczas wyszukiwania klienta: $customer_name\n".$response->error_message);
            $customers = array();
            foreach($ret->result_set as $s_result) {
                if($s_result['search_type'] == 1)
                    $customers[] = $s_result;
            }
            if(count($customers) > 1)
                throw new Exception("Znaleziono więcej niż jednego klienta spełniającego podane krytera: $customer_name");
            if(count($customers) === 0)
                throw new Exception("Nie znaleziono klienta spełniającego podane kryteria: $customer_name");
            $req->id_customer = $customers[0]['customer_id'];
        } 
        if($customer_id) {
            $req->id_customer = $customer_id;
        }
        if($pricelist_name) {
            $ret = $hlib->sendRequest("GetCustomerPricelistList",new stdClass());
            $pricelists = array();
            foreach($ret->result_set as $p_result) {
                if(strpos(strtolower($p_result['name']),strtolower($pricelist_name))===0) {
                    $pricelists[] = $p_result;
                }
            }
            if(count($pricelists) > 1)
                throw new Exception("Znaleziono więcej niż jednen cennik spełniający podane kryteriach: $pricelist_name");
            if(count($pricelists) === 0)
                throw new Exception("Nie znaleziono cennika spełniającego podane kryteria: $pricelist_name");
            $req->id_pricelist = $pricelists[0]['id'];    
        } 
        if($pricelist_id) {
            $req->id_pricelist = $pricelist_id;
        }
        $req->username = $username;
        $req->password = $password;
        $req->screen_numbers = true;
        $req->t38_fax = false;
        $ret = $hlib->sendRequest("AddTerminal",$req);
        if(!$ret) throw new Exception('Nie można utworzyć terminala SIP');
        if(!$ret->success) throw new Exception("Nie można utworzyć terminala SIP.\n".$ret->error_message);
        if(!$ret->result_set[0]->id_terminal) throw new Exception("Nie można ustalić identyfikatora terminala SIP: ".$c_name);
        return $ret->result_set[0]->id_terminal;
    }
    public static function CreatePSTNNumber($id_customer,$number_data=null,$terminal_data=null,$user_data=null,$subscription_data=null) {
        if(is_array($number_data)) {
            $number = $number_data['number'];
            $country_code = $number_data['country_code'] ? $number_data['country_code'] : '48';
            $sn = $number_data['sn'];
            if(!isset($number_data['is_main'])) $is_main = true; else $is_main = $number_data['is_main'];
            if(!isset($number_data['clir'])) $clir = false; else $clir = $number_data['clir'];
            if(!isset($number_data['virtual_fax'])) $virtual_fax = false; else $virtual_fax = $number_data['virtual_fax'];
            if(!isset($number_data['voicemail_enabled'])) $voicemail_enabled = false; else $voicemail_enabled = $number_data['voicemail_enabled'];
        } else {
            $country_code = '48';
            $is_main = true;
            $clir = false;
            $virtual_fax = false;
        }
        if(is_array($terminal_data)) {
            $id_terminal = $terminal_data['id_terminal'];
            $id_pricelist = $terminal_data['id_pricelist'];
            $pricelist_name = $terminal_data['pricelist_name'];
        }
        if(is_array($user_data)) {
            $id_auth = $user_data['id_auth'];
            $useremail = $user_data['user_email'];
            $userpassword = $user_data['user_password'];
        }
        if(is_array($subscription_data)) {
            $id_subscription = $subscription_data['id_subscription'];
            $subscription_name = $subscription_data['subscription_name'];
        }
        $hlib = new HiperusLib();

        if(!$number) {
            $r = new stdClass();
            $r->sn=$sn;
            $ret = $hlib->sendRequest("GetFirstFreePlatformNumber",$r);
            if($ret->success===false)
                throw new Exception("Nie można ustalić wolnego numeru PSTN.\n".$response->error_message);
            $number = $ret->result_set[0]['free_number'];
            $country_code = $ret->result_set[0]['country_code'];
        }
        if(!$number || !$country_code)
            throw new Exception("Brak wolnych numerów PSTN w Twoim planie numeracyjnym. Proszę skontaktować się z działem Hotline.");

        if(!$id_terminal) {
            if(!$id_pricelist) {
                $r = new stdClass();
                $ret = $hlib->sendRequest("GetCustomerPricelistList",$r);
                if($ret->success===false)
                    throw new Exception("Nie można pobrać listy cenników klienckich");
                if(!$pricelist_name) {
                    if($ret->result_set[0])
                        $id_pricelist = $ret->result_set[0]['id'];
                } else {
                    foreach($ret->result_set as $rs) {
                        if(strtolower($pricelist_name) == strtolower($rs['name'])) { 
                            $id_pricelist = $rs['id'];
                            break;
                        }
                    }
                }
            }
            if(!$id_pricelist)
                throw new Exception("Nie można ustalić cennika dla tworzonego terminala SIP");
            $create_terminal = true;
        }
        if(!$id_subscription) {
            if($subscription_name) {
                $r = new stdClass();
                $ret = $hlib->sendRequest("GetSubscriptionList",$r);
                if($ret->success===false)
                    throw new Exception("Nie można pobrać listy abonamentów");
                foreach($ret->result_set as $rs) {
                    if(strtolower($subscription_name) == strtolower($rs['name'])) { 
                        $id_subscription = $rs['id'];
                        break;
                    }
                }
            }
        }
        if($useremail) {
            $r = new stdClass();
            $r->id_customer = $id_customer;
            $ret = $hlib->sendRequest("GetEndUserAuthList",$r);
            if($ret->success===false)
                throw new Exception("Nie można pobrać listy użytkowników końcowych");
            foreach($ret->result_set as $rs) {
                if(strtolower($useremail) == strtolower($rs['email'])) { 
                    $id_auth = $rs['id'];
                    $useremail = null;
                    $userpass = null;
                    break;
                }
            }
        }
        $r = new stdClass();
        $r->number = $number;
        $r->country_code = $country_code;
        $r->id_customer = $id_customer;
        $r->is_main = $is_main;
        $r->clir = $clir;
        $r->virtual_fax = $virtual_fax;
        $r->voicemail_enabled = $voicemail_enabled;
        if($create_terminal) {
            $r->create_terminal = $create_terminal;
            $r->id_pricelist = $id_pricelist;
        } else {
            $r->id_terminal = $id_terminal;
        }
        $r->id_auth = $id_auth;
        $r->useremail = $useremail;
        $r->userpassword = $userpassword;
        $r->id_subscription = $id_subscription;
        $ret = $hlib->sendRequest("AddExtension",$r);
        if(!$ret) throw new Exception("Nie można utworzyć numeru PSTN");
        if($ret->success === false)throw new Exception("Nie można utworzyć numeru PSTN.\n".$ret->error_message);
        $r = new stdClass();
        $r->id_extension = $ret->result_set[0]['id_extension'];
        $ret = $hlib->sendRequest("GetExtensionData",$r);
        if(!$ret) throw new Exception("Nie moge pobrać danych utworzonego numeru PSTN");
        if($ret->success === false) throw new Exception("Nie moge pobrać danych utworzonego numeru PSTN.\n".$ret->error_message);
        return $ret->result_set[0];
    }
    public static function GetCustomerData($id_customer) {
        $hlib = new HiperusLib();
        $r = new stdClass();        
        $r->id_customer = $id_customer;
        $response = $hlib->sendRequest("GetCustomerData",$r);
        return $response->result_set[0];
    }
    public static function GetCustomerDataExtID($ext_billing_id) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->ext_billing_id = $ext_billing_id;
        $response = $hlib->sendRequest("GetCustomerIDByExtBillingID",$r);
        if(!$response->success) {
            throw new Exception("Nie można pobrać danych klienta bazując na identyfikatorze z systemu zewnętrznego.\n".$response->error_message);
        }
        $id_customer = $response->result_set[0]['id'];
        return HiperusActions::GetCustomerData($id_customer);
    }
    public static function ChangeCustomerData($c_data) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        foreach($c_data as $key=>$val) {
            $r->$key = $val;
        }
        $r->id_customer = $r->id;
        $response = $hlib->sendRequest("SaveCustomerData",$r);
        if($response->success===false) {
            throw new Exception("Nie można zapisać danych klienta.\n".$response->error_message);
        }
        return true;
    }
    public static function ChangeTerminalData($t_data) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        foreach($t_data as $key=>$val) $r->$key = $val;
        if($r->id) $r->id_terminal = $r->id;
        $response = $hlib->sendRequest("SaveTerminalData",$r);
        if($response->success===false) throw new Exception("Nie można zapisać danych terminala SIP.\n".$response->error_message);
        return true;
    }
    public static function GetCustomerList($offset=null,$limit=null,$query=null) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->offset = $offset;
        $r->limit = $limit;
        $r->query = $query;
        $response = $hlib->sendRequest("GetCustomerList",$r);
        if(!$response->success) throw new Exception("Nie można pobrać listy klientów.\n".$response->error_message);
        return $response->result_set;    
    }
    public static function GetTerminalList($id_customer,$offset=null,$limit=null) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->id_customer = $id_customer;
        $response = $hlib->sendRequest("GetTerminalList",$r);
        if(!$response) throw new Exception("Nie można pobrać listy terminali SIP");
        if(!$response->success) throw new Exception("Nie można pobrać listy terminali SIP.\n".$response->error_message);
        return $response->result_set;
    }
    public static function GetPSTNNumberList($id_customer,$offset=null,$limit=null) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->id_customer = $id_customer;
        $response = $hlib->sendRequest("GetExtensionList",$r);
        if(!$response) throw new Exception("Nie można pobrać listy numerów PSTN");
        if(!$response->success) throw new Exception("Nie można pobrać listy numerów PSTN.\n".$response->error_message);
        return $response->result_set;
    }
    public static function GetPricelistList($offset=null,$limit=null) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $response = $hlib->sendRequest("GetCustomerPricelistList",$r);
        if(!$response) throw new Exception("Nie można pobrać listy cenników klienckich");
        if(!$response->success)throw new Exception("Nie można pobrać listy cenników klienckich.\n".$response->error_message);
        return $response->result_set;
    }
    public static function GetSubscriptionList() {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $response = $hlib->sendRequest("GetSubscriptionList",$r);
        if(!$response) throw new Exception("Nie można pobrać listy abonamentów");
        if(!$response->success) throw new Exception("Nie można pobrać listy abonamentów.\n".$response->error_message);
        return $response->result_set;
    }
    public static function GetEndUserList($id_customer,$offset=null,$limit=null) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->id_customer = $id_customer;
        $response = $hlib->sendRequest("GetEndUserAuthList",$r);
        if(!$response) throw new Exception("Nie można pobrać listy końcowych użytkowników");
        if(!$response->success) throw new Exception("Nie można pobrać listy końcowych użytkowników.\n".$response->error_message);
        return $response->result_set;
    }
    public static function GetBillingFile($from,$to,$offset=null,$limit=null,$success_calls=true,$id_customer=null,$calltype=null) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->from = $from;
        $r->to = $to;
        $r->compress = true;
        $r->offset = $offset;
        $r->limit = $limit;
        $r->success_calls = $success_calls;
        $r->id_customer = $id_customer;
        $r->calltype = $calltype;
        $response = $hlib->sendRequest("GetBilling",$r);
        if(!$response) throw new Exception("Nie można pobrać danych billingowych");
        if(!$response->success) throw new Exception("Nie można pobrać danych billingowych.\n".$response->error_message);
        $uniqid = uniqid("reseller_cdrs_");
        $zip_filename = CDR_FILE_PATH."/".$uniqid.".zip";
        file_put_contents($zip_filename,base64_decode($response->result_set[0]));
        return $zip_filename;
    }
    public static function GetBilling($from,$to,$offset=null,$limit=null,$success_calls=true,$id_customer=null,$calltype=null) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->from = $from;
        $r->to = $to;
        $r->offset = $offset;
        $r->limit = $limit;
        $r->compress = false;
        $r->success_calls = $success_calls;
        $r->calltype = $calltype;
        $r->id_customer = $id_customer;
        $response = $hlib->sendRequest("GetBilling",$r);
        if(!$response) throw new Exception("Nie można pobrać danych billingowych");
        if(!$response->success) throw new Exception("Nie można pobrać danych billingowych.\n".$response->error_message);
        return $response->result_set;
    }
    public static function DelTerminal($id_terminal) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->id_terminal = $id_terminal;
        $response = $hlib->sendRequest("DelTerminal",$r);
        if(!$response) throw new Exception("Nie można usunąć terminala SIP");
        if(!$response->success) throw new Exception("Nie można usunąć terminala SIP.\n".$response->error_message);
        return true;
    }
    public static function DelPSTNNumber($id_extension) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->id_extension = $id_extension;
        $response = $hlib->sendRequest("DelExtension",$r);
        if(!$response) throw new Exception("Nie można usunąć numeru PSTN");
        if(!$response->success) throw new Exception("Nie można usunąć numery PSTN.\n".$response->error_message);
        return true;
    }
    public static function DelCustomer($id_customer) {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $r->id_customer = $id_customer;
        $response = $hlib->sendRequest("DelCustomer",$r);
        if(!$response) throw new Exception("Nie można usunąć klienta");
        if(!$response->success) throw new Exception("Nie można usunąć klienta.\n".$response->error_message);
	if (!$response || !$response->success) return false; else return true;
    }
    public static function ReloadSettings() {
        $hlib = new HiperusLib();
        $r = new stdClass();
        $response = $hlib->sendRequest("Logout",$r);
        if(!$response)throw new Exception("Błąd wylogowywania");
        if(!$response->success) throw new Exception("Błąd wylogowywania.\n".$response->error_message);
        $hlib = new HiperusLib();
        $r = new stdClass();
        $response = $hlib->sendRequest("CheckLogin",$r);
        if(!$response) throw new Exception("Błąd.");
        if(!$response->success) throw new Exception("Błąd.\n".$response->error_message);
        return true;
    }
}
?>