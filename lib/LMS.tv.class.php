<?php
/*
 * Aplikacja IPTV versja 1.2
 * 2011 ITMSOFT
 * 1.2.1 23/08/2011 19:00:00
 
 *  Modyfikacja: Aplikacja IPTV versja 1.2
 *  2014 SGT
 *  1.2.1 23/08/2011 19:00:00  
*/

//set_include_path('.' . PATH_SEPARATOR . 'lib' . PATH_SEPARATOR . get_include_path());

require_once LIB_DIR."/Zend/Loader.php";
require_once LIB_DIR."/Zend/XmlRpc/Client.php";
require_once LIB_DIR."/Zend/Cache.php";
require_once LIB_DIR."/Zend/Paginator.php";

function __autoload($class)   {
	Zend_Loader::loadClass($class);
}

class send {

	private $h = null;
	private $auth_data = null;
	
	static function getInstance() {

		static $instance;
		if (!isset($instance)) {
			$c = __CLASS__;
			$instance = new $c;
			
			try {
				//$ini_array = parse_ini_file('lmstv.ini', true);
				//$instance->h = new Zend_XmlRpc_Client($ini_array['logowanie']['serwer']);
				$url = get_conf('jambox.serwer');
				$instance->h = new Zend_XmlRpc_Client($url);
				$instance->h->getHttpClient()->setHeaders(array('User-Agent: LMS SGT')); 
				//$instance->auth_data = array("user_name" => $ini_array['logowanie']['login'], "user_pass" => $ini_array['logowanie']['haslo']);
				$login = get_conf('jambox.login');
				$haslo = md5(get_conf('jambox.haslo'));
				$instance->auth_data = array("user_name" => "$login", "user_pass" => "$haslo");
				
			} catch (Exception $e) {;}
		}
		return $instance;
	}
	
	function get($name, $params = array()){
		
		array_unshift($params, $this->auth_data);
		try {
			return $this->h->call($name, $params);
		} catch (Exception $e) {throw new Exception($e->getMessage(), $e->getCode());}
	}	
	
	function dump_backtrace() {
		$t = debug_backtrace();
		$tmp = '';
		unset ($t[0]);
		$t = array_reverse($t);
		foreach ($t as $k => $v ) {
			$tmp .= $v['function']  .'(' .(@implode(',', $v["args"])). ') >> ';
		}
		return $tmp;
	}
	
	function getHandle(){
		return $this->h;
	}
}


//class LMSTV extends LMS {
class LMSTV {
	var $s = null;
	var $tv_cache = null;
	var $ini_array = null;
	public $smsurl = null;
	var $DB;
		
	function LMSTV($DB, $AUTH, $CONFIG) 
	{
		session_start();
//		parent::LMS($DB, $AUTH, $CONFIG);
		$this->DB =& $DB;
		$this->s = send::getInstance();
		
		//$this->ini_array = parse_ini_file('lmstv.ini', true);
		//$this->smsurl =substr($this->ini_array['logowanie']['serwer'], 0, '-6' );
		$url = get_conf('jambox.serwer');
		$this->smsurl =substr($url, 0, '-6' );
		
		$cache = get_conf('jambox.cache',1);
		$cache_lifetime = get_conf('jambox.cache_lifetime','472000');
		
		$frontendOptions = array(
				'lifetime' => $cache_lifetime,
				'debug_header' => true, // for debugging
				'regexps' => array(
				'^/$' => array('cache' => true),
		       	'^/index/' => array('cache' => true),
		       	'^/article/' => array('cache' => false),
		       	'^/article/view/' => array(
		        'cache' => true,
		        'cache_with_post_variables' => true,
		        'make_id_with_post_variables' => true)));

		$backendOptions = array('cache_dir' => TMP_DIR);
		
		$this->tv_cache = Zend_Cache::factory('Page', 'File', $frontendOptions, $backendOptions);
	
		$_SESSION['tv_cache'] = $cache;
	}
	
	function get($name, $params = array()){
		return $this->s->get($name, $params);	
	}

	function GetBillingEvents($start_date = '', $end_date = '', $id = '') {
		$start_date = str_replace("/", "-", $start_date);
		$end_date = str_replace("/", "-", $end_date);
		return $this->s->get('billingEventsGet', array($start_date, $end_date, $id));
	}
	
	function GetBillingEventsDB($start_date = '', $end_date = '', $id = '', $docid = null, $orderby = 'beid', $direction = 'asc') {
		$start_date = addslashes(str_replace("/", "-", $start_date));
		$end_date = addslashes(str_replace("/", "-", $end_date));	
		$docid = (int)$docid;	
		$sql = 'SELECT * from tv_billingevent where id > 0 '.($id ? " and group_id = $id " : '')
		.($start_date ? " and be_selling_date >= '$start_date' " : '')
		.($end_date ? " and be_selling_date <= '$end_date' " : '')
		.($docid ? " and docid = $docid " : '')
		. " order by $orderby $direction";
		return $res = $this->DB->GetAll($sql);
	}	

	function CustomerExport($customerid) {
		$customeradd = $this->DB->GetRow('SELECT * FROM customers WHERE id = ?', array($customerid));
		if ($customeradd['tv_cust_number']) {
			$mode = 'edit';
			$cust_number = $customeradd['tv_cust_number'];
		} else {
			$cust_list = $this->CustomerList();
			foreach ($cust_list as $cust) {
				if ($cust['cust_external_id'] == $customerid) {
					$mode = 'edit';
					$cust_number = $cust['cust_number'];
					break;
				} else {
					$mode = 'add';
				}
			}
		}		

			$cust_data = array(
				'cust_name' 		=> lms_ucwords($customeradd['name']),
				'cust_surname' 		=> lms_ucwords($customeradd['lastname']),
				'cust_pesel' 		=> $customeradd['ssn'],
				'cust_m_city' 		=> $customeradd['city'],
				'cust_m_postal_code'	=> $customeradd['zip'],
				'cust_m_street' 	=> $customeradd['address'],
				'cust_m_home_nr' 	=> '.',
				'cust_m_flat' 		=> '.',
				'cust_c_city' 		=> $customeradd['city'],
				'cust_c_postal_code'	=> $customeradd['zip'],
				'cust_c_street' 	=> $customeradd['address'],
				'cust_c_home_nr' 	=> '.',
				'cust_c_flat' 		=> '.',
				'cust_email' 		=> $customeradd['email'],
				'cust_external_id' => $customeradd['id'],
			);
			
		if ($mode == 'edit') {		
			//$this->DB->Execute('UPDATE customers SET cust_number=? WHERE id=?', array($cust_number, $customerid));
			$this->s->get('custEdit', array($cust_number, $cust_data));
			
		} else {
			$cust_data['cust_vod_pin'] 		= rand(1000, 9999);
			$cust_data['cust_master_pin'] 	= rand(1000, 9999);
			$cust_number = $this->s->get('custAdd', array($cust_data));
		
			$this->DB->Execute('UPDATE customers SET tv_cust_number=? WHERE id=?', array($cust_number, $customerid));
		}
		
		$this->_cleanCustomerCache($customerid);
		if ($_SESSION['tv_cache']) $this->tv_cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('customer'));
		return $cust_number;
	}

	public function CustomerGetNumber($id){
		$cust_number = $this->DB->GetOne('SELECT tv_cust_number FROM customers WHERE id = ?', array($id));
		if ($cust_number){
			return $cust_number;
		} else {
			return false;	
		}
	}	
	
	public function ExistsInLMS($customerid){
		$cust_number = $this->DB->GetOne('SELECT tv_cust_number FROM customers WHERE id = ?', array($customerid));
		if ($cust_number){
			return $cust_number;
			
		} else {
			return false;
			
		}
	}
	
	public function GetCustomer($id){
		$data['tv_cust_number'] = $this->DB->GetOne('SELECT tv_cust_number FROM customers WHERE id = ?', array($id));
		if ((int)$data['tv_cust_number']){
			$data = $this->CustomerList($data['tv_cust_number']);
		}
		return $data;
	}
	
	public function PackageGetAll(){
		$cache_name = __FUNCTION__;
		if (!$_SESSION['tv_cache'] || !$res = $this->tv_cache->load($cache_name)){
			$res = $this->s->get('packageGetAll');
			if ($_SESSION['tv_cache']) $this->tv_cache->save($res, $cache_name, array('packagelist'));
		}
		return $res;
	}

	public function PackageGetAvail($customerid, $account_id){
		$cache_name = __FUNCTION__.$account_id;
		if (!$_SESSION['tv_cache'] || !$res = $this->tv_cache->load($cache_name)){
			$res = $this->s->get('packageGetAvail', array($account_id));
			if ($_SESSION['tv_cache']) $this->tv_cache->save($res, $cache_name, array('customer'.$customerid));
		}
		return $res;
	}

	public function AccountLock($customerid, $account_id){
		$this->_cleanCustomerCache($customerid);
		return $this->s->get('accountLock', array((string)$account_id));
	}

	public function AccountUnlock($customerid, $account_id){
		$this->_cleanCustomerCache($customerid);
		return $this->s->get('accountUnlock', array((string)$account_id));
	}

	public function AccountAdd($cust_number, $customerid, $acc_data){
		if (!(int)$cust_number) {
			$cust_number = $this->CustomerExport($customerid);
		}
		$this->_cleanCustomerCache($customerid);
		return $this->s->get('accountAdd', array($cust_number, $acc_data));
	}

	public function AccountDel($customerid, $account_id){
		$this->_cleanCustomerCache($customerid);
		return $this->s->get('accountDel', array($account_id));
	}

	public function AccountEdit($customerid, $account_id, $acc_data){
		$this->_cleanCustomerCache($customerid);
		return $this->s->get('accountEdit', array($account_id, $acc_data));
	}

	public function stbGetRegistered($show_mode = ''){
		$cache_name = __FUNCTION__.$show_mode;
		if (!$_SESSION['tv_cache'] || !$res = $this->tv_cache->load($cache_name)){
			$res = $this->s->get('stbGetRegistered', array($show_mode));
			if ($_SESSION['tv_cache']) $this->tv_cache->save($res, $cache_name, array('stblist'));
		}
		return $res;
	}

	//public function CustomerList($cust_number = null){
	public function CustomerList($cust_number = ''){
		$cache_name = __FUNCTION__.$cust_number;
		$id = null;
		if ((int)$cust_number) $id = $this->DB->GetOne('SELECT id FROM customers WHERE tv_cust_number = ?', array($cust_number));
		
		if (!$_SESSION['tv_cache'] || !$res = $this->tv_cache->load($cache_name)){
			try{
				$res = $this->s->get('custList', array($cust_number));
			}catch (Exception $e){
			var_dump($e);
				$res = null;
			}
			if ($_SESSION['tv_cache']) $this->tv_cache->save($res, $cache_name, array('customer'.$id));
		}
		return $res;
	}
	
	public function GetCustomerByCustNumber($cust_number){
		 return $this->DB->GetOne('SELECT id FROM customers WHERE tv_cust_number = ?', array($cust_number));
	}

	//public function MessagesList($customerid = null, $cust_number = null){
	public function MessagesList($customerid = null, $cust_number = ''){
		//if ($customerid) $cust_number = (string)$this->DB->GetOne('SELECT cust_number FROM customers WHERE id = ?', array($customerid));
		$cache_name = __FUNCTION__.$customerid;
		if (!$_SESSION['tv_cache'] || !$res = $this->tv_cache->load($cache_name)){
			$res = $this->s->get('meldingerList', array($cust_number));
			if ($_SESSION['tv_cache']) $this->tv_cache->save($res, $cache_name, array('customermsgs', 'customermsgs'.$customerid));
		}
		return $res;
	}

	public function MeldingerDel($ids = null, $customerid = null){
		if ($customerid){
			$cust_number = (string)$this->DB->GetOne('SELECT tv_cust_number FROM customers WHERE id = ?', array((int)$customerid));
			$res = $this->s->get('meldingerDel', array(null, $cust_number));
			if ($_SESSION['tv_cache']) $this->tv_cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('customermsgs', 'customermsgs'.$customerid));
		}
		if (is_array($ids)){
			$res = $this->s->get('meldingerDel', array($ids));
		}
		if ($_SESSION['tv_cache']) $this->tv_cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('customermsgs'));
		return $res;
	}

	public function MeldingerSend($customerid = null, $msg_body, $msg_teaser_valid_from, $msg_teaser_valid_to, $msg_body_extended, $msg_valid_from, $msg_valid_to, $msg_prio, $msg_show_priority){
		$cust_number = null;
		if ($customerid){
			$cust_number = (string)$this->DB->GetOne('SELECT tv_cust_number FROM customers WHERE id = ?', array((int)$customerid));
		}

		$res = $this->s->get('meldingerSend', array($cust_number, $msg_body, $msg_teaser_valid_from, $msg_teaser_valid_to, $msg_body_extended, $msg_valid_from, $msg_valid_to, $msg_prio, $msg_show_priority));
		if ($_SESSION['tv_cache']) $this->tv_cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('customermsgs', 'customermsgs'.$customerid));
		return $res;
	}

	public function StbUnlink($customerid, $account_id, $stb_mac){
		$_SESSION['stbsearch'] = null;
		$this->_cleanCustomerCache($customerid);
		$this->_cleanSTBListCache();
		return $this->s->get('stbUnlink', array($account_id, $stb_mac));
	}

	public function StbLink($customerid, $account_id, $stb_mac, $subnet_id, $cust_order_id){
		$_SESSION['stbsearch'] = null;
		$this->_cleanCustomerCache($customerid);
		$this->_cleanSTBListCache();
		return $this->s->get('stbLink', array($account_id, $stb_mac, $subnet_id, $cust_order_id));
	}

	public function StbRemove($stb_mac){
		$_SESSION['stbsearch'] = null;
		$this->_cleanSTBListCache();		
		return $this->s->get('stbRemove', array($stb_mac));
	}

	public function StbRegister($stb_mac, $stb_serial, $stb_model){
		$_SESSION['stbsearch'] = null;
		$this->_cleanSTBListCache();
		return $this->s->get('stbRegister', array($stb_mac, $stb_serial, $stb_model));
	}

	public function CustomerGetSubscriptions($customerid, $cust_number){
		//$cust_number = $this->DB->GetOne('SELECT cust_number FROM customers WHERE id = ?', array($customerid));
		$cache_name = __FUNCTION__.$customerid;
		
		if (!$_SESSION['tv_cache'] || !$res = $this->tv_cache->load($cache_name)){
			try {
				$res = $this->s->get('custGetSubscriptions', array($cust_number));
			}catch (Exception $e){
				$res = array();
			}
			if (!$res) $res = array();
			
			if ($_SESSION['tv_cache']) $this->tv_cache->save($res, $cache_name, array('customer'.$customerid));
		}
		return $res;
	}

	public function AccountGetSTB($customerid, $account_id){
		$cache_name = __FUNCTION__.$account_id;
		if (!$_SESSION['tv_cache'] || !$res = $this->tv_cache->load($cache_name)){
			$res = $this->s->get('accountGetSTB', array((string)$account_id));
			if ($_SESSION['tv_cache']) $this->tv_cache->save($res, $cache_name,array('customer'.$customerid));
		}
		return $res;

	}

	public function SubnetList(){
		$cache_name = __FUNCTION__;
		if (!$_SESSION['tv_cache'] || !$res = $this->tv_cache->load($cache_name)){
			$res = $this->s->get('subnetList');
			if ($_SESSION['tv_cache']) $this->tv_cache->save($res, $cache_name, array('subnetlist'));
		}
		return $res;
	}

	public function SubnetSplit($subnet_id, $subnet_name1 = "", $subnet_name2 = ""){
		if ($_SESSION['tv_cache']) $this->tv_cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('subnetlist'));
		$this->_cleanCustomerCache();
		$this->_cleanSTBListCache();
		return $this->s->get('subnetSplit', array($subnet_id, $subnet_name1, $subnet_name2));
	}

	public function PackagesAdd($customerid, $account_id, $packages_id, $start_date = ''){
		$start_date = str_replace("/", "-", $start_date);
		$res = $this->s->get('packagesAdd', array($account_id, $packages_id, $start_date));
		$this->_cleanCustomerCache($customerid);
		return $res;
	}

	public function PackagesEntitle($customerid, $account_id, $packages_id, $start_date = ''){
		$start_date = str_replace("/", "-", $start_date);
		$res = $this->s->get('packagesEntitle', array($account_id, $packages_id, $start_date));
		$this->_cleanCustomerCache($customerid);
		return $res;
	}

	public function SubscriptionActivate($customerid, $account_id, $subscription_id){
		$start_date = str_replace("/", "-", $start_date);
		$this->_cleanCustomerCache($customerid);
		return $this->s->get('subscriptionActivate', array($account_id, $subscription_id));
	}

	public function SubscriptionTerminate($customerid, $account_id, $subscription_id, $term_date, $term_fee = '', $term_desc = ''){
		$this->_cleanCustomerCache($customerid);
		return $this->s->get('subscriptionTerminate', array($account_id, $subscription_id, $term_date, $term_fee, $term_desc));
	}

	public function UpdatePin($customerid, $cust_master_pin, $cust_vod_pin){
		$data = $this->GetCustomer($customerid);
		if ($data['cust_number']) {
			$cust_number = $data['cust_number'];
			$cust_data = array(
				'cust_master_pin' 	=> $cust_master_pin,
				'cust_vod_pin' 		=> $cust_vod_pin,
			);
			$this->s->get('custEdit', array($cust_number, $cust_data));
			$this->_cleanCustomerCache($customerid);
			if ($_SESSION['tv_cache']) $this->tv_cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('customer'));
		}
		return $cust_number;
	} 

	private function _cleanCustomerCache($customerid = null){
		if ($_SESSION['tv_cache']) $this->tv_cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('customer'.$customerid));
	}
	
	private function _cleanSTBListCache(){
		if ($_SESSION['tv_cache']) $this->tv_cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('stblist'));
	}
	
	
	/* Funkcja ustawia bilans operatora w tvPanelu */
	public function setCustomerPaymentInfo(){
		$upload_date = Date("Y-m-d");
		$tv_cust_list = $this->CustomerList();
		
		$payment_info_array = array();
			foreach($tv_cust_list as $list){
				if(empty($list['cust_external_id']) || empty($list['cust_number']))continue;
				$payment_info_array[$list['cust_number']] = array(
											'cust_total_balance'	=>	$this->GetCustomerBalance($list['cust_external_id']),															
											);				
			}
		

		
		return $this->s->get('setCustomerPaymentInfo', array($upload_date, $payment_info_array));
	}
}
?>
