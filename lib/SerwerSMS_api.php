<?php


class SerwerSMS {


    private static $url = "https://api2.serwersms.pl/messages/send_sms.json";


    function __construct() {

    }


    public function __desctruct() {
    }


    private static function call($params=array()) {
	
	if (empty($params) || !is_array($params)) {
	    throw new Exception('Brak wymaganych parametrów');
	}
	
	$params['username'] = get_conf('sms.username','demo');
	$params['password'] = get_conf('sms.password','demo');
	$params['details'] = get_conf('sms.details',true);
	$params['sender'] = get_conf('sms.from',NULL);
	if (get_conf('sms.smsapi_eco')) $params['sender'] = NULL;
	if (get_conf('sms.smsapi_fast')) $params['speed'] = 1; else $params['speed'] = 0;
	if (get_conf('sms.test',0)) $params['test'] = 1; else $params['test'] = 0;
	
	$requestURL = SerwerSMS::$url;
	$c = curl_init($requestURL);
	
	curl_setopt($c, CURLOPT_POST,1);
	curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($c, CURLOPT_TIMEOUT, 30);
	$answer = curl_exec($c);
	
	if (curl_errno($c)) {
		throw new Exception('Failed call: ' . curl_error($c) . ' ' . curl_errno($c));
	}
	
	curl_close($c);
	
	$result = json_decode($answer);
	
	if (isset($result->error)) {
	    throw new Exception($result->error->message, (int) $result->error->code);
	}
	
	return json_decode($answer,true);
    }


    public static function sendSMS($phone,$text,$params=array(),$detail = false) {
	
	$params = array_merge(array(
	    'phone' => $phone,
	    'text' => $text,
	    'sender' => $sender),
	    $params);
	
	$result = SerwerSMS::call($params);
	
	if ($detail) {
	    return $result;
	} else {
	    return ($result['success'] == '1' ? true : false);
	}
    }


} // end class

?>
