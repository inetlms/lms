<?php
/*
 * HiperusLIB - Open Source telephony lib
 *
 * Copyright (C) 2010 - 2011 Telekomunikacja Blizej
 *
 * See devel.hiperus.pl for more information about
 * the HiperusLIB and HiperusAPI project. 
 *
 * Version: 1.0
 */

/******** Configuration section - sekcja konfiguracji *************************/

// plik z danymi do autoryzacji
if(!defined('H_PWD_FILE')) define('H_PWD_FILE','/var/lib/hiperus/hiperus1.pwd');

// plik w którym będzie przechowywana sesja
if(!defined('H_SESSION_FILE')) define('H_SESSION_FILE','/var/lib/hiperus/hiperus1.session');
$shellsession = '/var/lib/hiperus/hiperus2.session';

/******** End of configuration section ****************************************/

define('H_URI','https://backend.hiperus.pl:8080/hiperusapi.php');

/**
 * 
 */
class HiperusLib {

    private $_h_username;
    private $_h_password;
    private $_h_domain;
    
    private $_h_realm;
    private $_h_sessid;
    private $_h_id_reseller;
    private $_h_debug = false;

    /**
     * __construct()
     */
    public function __construct($realm='PLATFORM_MNGM',$username=null,$password=null,$domain=null) {
        $this->_h_realm = $realm;
        $this->_h_username = $username;
        $this->_h_password = $password;
        $this->_h_domain = $domain;
        
        if(!file_exists(H_SESSION_FILE)) {
            $this->hStartSession();
        } elseif(filesize(H_SESSION_FILE) == 0) {
            $this->hStartSession();
        } else {
            $this->hContinueSession();
        }
    }
    
    
    /**
     * hStartSession()
     */
    private function hStartSession() {
    
        if($this->_h_username && $this->_h_password) {
            $username = $this->_h_username;
            $password = $this->_h_password;
            $domain = $this->_h_domain;
        } else {    
            $pwd_f_content = file_get_contents(H_PWD_FILE);
            if($pwd_f_content===false)
                throw new Exception("Unable to get login information from pwd file");

            $pwd_f_content = explode("\n",$pwd_f_content);            
            $username = trim($pwd_f_content[0]);
            $password = trim($pwd_f_content[1]);
            $domain = trim($pwd_f_content[2]);
        }
        
        
        $req = new stdClass();
        $req->username = $username;
        $req->password = $password;
        $req->domain = $domain;

        $this->_h_session = null; // generate new sessid

        $response = $this->sendRequest("Login",$req);
        
        if(!$response->success) {
            throw new Exception("HiperusLIB login failed: ".$response->error_message);
        } 
        
        $this->_h_session = $response->sessid;
        //$this->_h_id_reseller
        if(!file_put_contents(H_SESSION_FILE,$this->_h_session)) {
            throw new Exception("HiperusLIB unable to save session");
        }
        chmod(H_SESSION_FILE,0600);

    }


    /**
     * hContinueSession()
     */
    private function hContinueSession() {
        $session_f_content = file_get_contents(H_SESSION_FILE);
        if($session_f_content===false)
            throw new Exception("Unable to get session information from file");
        
        $this->_h_session = trim($session_f_content);
        
        $req = new stdClass();

        $response = $this->sendRequest("CheckLogin",$req);

        if(!$response->success) {
            unlink(H_SESSION_FILE);
            throw new Exception("HiperusLIB reopen session failed: ".$response->error_message);
        }

        if($this->_h_session != $response->sessid)
            throw new Exception("HiperusLIB session matching fatal error");
        
        if(!$response->result_set[0]['logged']) {
            $this->hStartSession();
        }
                    
    }


    /**
     * sendRequest()
     */
    public function sendRequest($action,$req) {
        if($this->_h_debug) _h_debug("SEND Action: $action");
        $sessid = $this->_h_session;
        $realm = $this->_h_realm;
        
        $soapClient = new SoapClient(null,array(
            'uri'=>H_URI,
            'location'=>H_URI
        ));
        
        $ret = $soapClient->request($realm,$action,$req,$sessid);

        if($this->_h_debug) {
            _h_debug("REQUEST ====>\n");
            _h_debug("REALM: $realm ACTION: $action SESSID: $sessid\n");
            print_r($req);
            _h_debug("RESPONSE <=======\n");
            print_r($ret);
        }
        
        return $ret;
    }
}


/*==============================================================
    Helper function - obj2xml - Hiperus
===============================================================*/
function obj2xml(&$parent,$o) {
    $v = get_object_vars($o);
    foreach($v as $key=>$val) {
        if(is_object($val)) {
            $el = new DOMElement($key);
            $parent->appendChild($el);
            obj2xml($el,$val);
        } elseif(is_array($val)) {
            $el = new DOMElement($key);
            $parent->appendChild($el);
            array2xml($el,$val);
        } else {
            $parent->appendChild(new DOMElement($key,$val));
        }
    }
}

/*==============================================================
    Helper function - array2xml - Hiperus
===============================================================*/
function array2xml(&$parent,$a) {
    foreach($a as $key=>$val) {
        if(is_integer($key))
            $keystr = "record";    
        else
            $keystr = $key;
            
        if(is_object($val)) {
            $el = new DOMElement($keystr);
            $parent->appendChild($el);
            obj2xml($el,$val);
        } elseif(is_array($val)) {
            $el = new DOMElement($keystr);
            $parent->appendChild($el);
            array2xml($el,$val);
        } else {
            $parent->appendChild(new DOMElement($keystr,$val));
        }
    }
}


/*==============================================================
    Helper function - xml2obj - Hiperus
===============================================================*/
function xml2obj($domElement,$ident="-") {
    $ret_obj = new stdClass();
    if($domElement->childNodes) {
        $ridx = 0;
        foreach($domElement->childNodes as $c_elem) {
            $_t = $c_elem->tagName;
            if($c_elem->nodeType == XML_ELEMENT_NODE && $c_elem->childNodes->length == 0) {
                $ret_obj->$_t = null;
            } elseif($c_elem->nodeType == XML_ELEMENT_NODE && $c_elem->childNodes->length == 1 && $c_elem->childNodes->item(0)->nodeType == XML_TEXT_NODE) {
                $ret_obj->$_t = $c_elem->childNodes->item(0)->wholeText;
            } elseif($c_elem->nodeType == XML_ELEMENT_NODE && $c_elem->childNodes->length >= 1) {
            
                if($_t == 'result_set') {
                    $n_c_elem = $c_elem;
                    foreach($n_c_elem->childNodes as $n) {
                        $ret_obj->result_set[] = xml2obj($n,$ident."-");
                    }
                
                } elseif($_t == 'fields') {
                    $n_c_elem = $c_elem;
                    foreach($n_c_elem->childNodes as $n) {
                        $ret_obj->fields[] = xml2obj($n,$ident."-");
                    }                
                } else {
                    $ret_obj->$_t = xml2obj($c_elem,$ident."-");                
                }                
            }
        }
    }
    
    
    return $ret_obj;
}

function _h_debug($str) {
    print "[".date("Y-m-d H:i:s")."] DEBUG: ".$str."\n";
}

?>
