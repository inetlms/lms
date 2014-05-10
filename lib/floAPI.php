<?php
/***************************************************************************
*  Original floAPI copyright (C) 2005 by Joshua Hatfield.                 *
*                                                                         *
*  In order to use any part of this floAPI Class, you must comply with    *
*  the license in 'license.doc'.  In particular, you may not remove this  *
*  copyright notice.                                                      *
*                                                                         *
*  Much time and thought has gone into this software and you are          *
*  benefitting.  We hope that you share your changes too.  What goes      *
*  around, comes around.                                                  *
***************************************************************************

Origional Release 0.0.1: 2005-08-16
Documentation unavailable at this time.
*/
class floAPI {
    var $socket;
    var $hostname;
    var $port;
    var $username;
    var $password;
    var $events = false;
    var $connected = true;
    var $event_buffer = array();
    function floAPI($username = "mark", $password = "mysecret", $hostname = "127.0.0.1", $port = "5040", $autologin = true) {
        if ($autologin) $this->open($username, $password, $hostname, $port);
    }
    function open($username = null, $password = null, $hostname = null, $port = null) {
        $this->username = $username?$username:$this->username;
        $this->password = $password?$password:$this->password;
        $this->hostname = $hostname?$hostname:$this->hostname;
        $this->port = $port?$port:$this->port;
        if ($this->socket) $this->close();
        if ($this->socket = fsockopen($this->hostname, $this->port, $errno, $errstr, 10)) {
            stream_set_timeout($this->socket, 2);
            $response = $this->request(
                "LOGIN",
                array(
                    "USERNAME" => $this->username,
                    "SECRET" => $this->password,
                    "EVENTS" => ($this->events?"OFF":"ON")
                )
            );
		$this->connected = true;
        } else $this->connected = false;
    }
    function close() {
        $this->request("LOGOFF");
        fclose($this->socket);
        $this->socket = null;
    }
    function request($action, $params = null, $wait = true) {
	if(!$this->connected) return false;
        $this->post($action, $params);
if($wait) return $this->wait_for_response();
                else return false;
        
    }
    function post($action, $params = null) {
        if ($this->socket !== false) {
            $request_params = "";
            if (is_array($params)) {
                foreach ($params as $key => $value) {
                    $request_params .= "$key: $value\r\n";
                }
            } else {
                $request_params = $params;
            }
            $request = "ACTION: $action\r\n$request_params\r\n";
//echo "222 $request<br>";
            fputs($this->socket, $request);
            return $request;
        } else {
            return false;
        }
    }
    function wait_for_response() {
        $response = false;
        while(!$response && $meta["timed_out"] == false){
            $response = $this->read_anything();
//echo "$response<br>";
            if (preg_match("/^Event\\:/", $response)) {
                $this->event_buffer[] = $response;
                $response = false;
            }
            $meta = stream_get_meta_data($this->socket);
        } // while
//print_r($this->event_buffer);        
//$out='';
//foreach($this->event_buffer as $val) $out.=$val;
//echo $response;

return $response;
    }
    function read_anything() {
        $line = "";
        while($line != "\r\n" && $meta["timed_out"] == false){
            $line = fgets($this->socket, 4096);
            $meta = stream_get_meta_data($this->socket);
            $buffer .= $line;
        } // while
        return $buffer;
    }
    function events_toggle($status) {
        $this->events = isset($status)?$status:!$this->events;
        if ($this->status === true) {
            $this->post("EVENTS", array("EVENTMASK" => "ON"));
        } elseif ($this->status === false) {
            $this->request("EVENTS", array("EVENTMASK" => "OFF"));
        } else {
            $this->request("EVENTS", array("EVENTMASK" => $status));
        }
    }
    function events_check() {
        if ($this->socket) {
            while($this->checkbuffer()) {
                $this->event_buffer[] = $this->read_anything();
            }
        }
        return count($this->event_buffer);
    }
    function checkbuffer() {
        $pArr = array($this->socket);
        if (false === ($num_changed_streams = stream_select($pArr, $write = NULL, $except = NULL, 0))) {
            return FALSE;
        } elseif ($num_changed_streams > 0) {
            return true;
        } else {
            return false;
        }
    }
    function events_shift() {
        return array_shift($this->event_buffer);
    }
    function events_pop() {
        return array_pop($this->event_buffer);
    }
}
?>
