<?php
	define('GG_PONG',0x0007); //Pong
	define('GG_PING',0x0008); //Ping
	define('GG_DCC7_INFO',0x001f);
	define('GG_DCC7_NEW',0x0020); //Informacje o chęci nawiązania połączenia DCC
	define('GG_DCC7_ACCEPT',0x0021); //Zaakceptowanie połączenia DCC
	define('GG_DCC7_REJECT',0x0022); //Odrzucenie połączenia DCC
	define('GG_DCC7_ID_REPLY',0x0023);
	define('GG_DCC7_ID_REQUEST',0x0023);
	define('GG_DCC7_DUNNO1',0x0024);
	define('GG_DCC7_ABORTED',0x0025);
	define('GG_DCC7_ABORT',0x0025);
	/**
	 * Pakiety wysylane
	*/
	define('GG_LOGIN',0x000c); //Logowanie przed GG 6.0
	define('GG_LOGIN_EXT',0x0013); //Logowanie przed GG 6.0
	define('GG_LOGIN60',0x0015); //Logowanie przed GG 7.7
	define('GG_LOGIN70',0x0019); //Logowanie przed GG 8.0
	define('GG_LOGIN80',0x0031); //Logowanie
	define('GG_NEW_STATUS',0x0002); //Zmiana stanu przed GG 8.0
	define('GG_NEW_STATUS80BETA',0x0028); //Zmiana stanu przed Nowym Gadu-Gadu
	define('GG_NEW_STATUS80',0x0038); //Zmiana stanu
	define('GG_SEND_MSG',0x000b); //Wysłanie wiadomości przed GG 8.0
	define('GG_SEND_MSG80',0x002d); //Wysłanie wiadomości
	define('GG_ADD_NOTIFY',0x000d); //Dodanie do listy kontaktów
	define('GG_REMOVE_NOTIFY',0x000e); //Usunięcie z listy kontaktów
	define('GG_NOTIFY_FIRST',0x000f); //Początkowy fragment listy kontaktów większej niż 400 wpisów
	define('GG_NOTIFY_LAST',0x0010); //Ostatni fragment listy kontaktów
	define('GG_LIST_EMPTY',0x0012); //Lista kontaktów jest pusta
	define('GG_PUBDIR50_REQUEST',0x0014); //Zapytanie katalogu publicznego
	define('GG_USERLIST_REQUEST',0x0016); //Zapytanie listy kontaktów na serwerze przed Nowym Gadu-Gadu
	define('GG_USERLIST_REQUEST80',0x002f); //Zapytanie listy kontaktów na serwerze
	/**	 
	    * Pakiety odbierane
	*/
	define('GG_WELCOME',0x0001); //Liczba do wyznaczenie hashu hasła
	define('GG_LOGIN_OK',0x0003); //Logowanie powiodło się przed Nowym Gadu-Gadu
	define('GG_LOGIN_FAILED',0x0009); //Logowanie nie powiodło się
	define('GG_LOGIN_OK80',0x0035); //Logowanie powiodło się
	define('GG_LOGIN_HASH_TYPE_INVALID',0x0016); //Dany rodzaj hashowania hasła jest nieobsługiwany przez serwer
	define('GG_NEED_EMAIL',0x0014); //Logowanie powiodło się, ale powinniśmy uzupełnić adres e-mail w katalogu publicznym
	define('GG_STATUS',0x0002); //Zmiana stanu przed GG 6.0
	define('GG_STATUS60',0x000f); //Zmiana stanu przed GG 7.7
	define('GG_STATUS77',0x0017); //Zmiana stanu przed GG 8.0
	define('GG_STATUS80BETA',0x002a); //Zmiana stanu przed Nowym Gadu-Gadu
	define('GG_STATUS80',0x0036); //Zmiana stanu
	define('GG_SEND_MSG_ACK',0x0005); //Potwierdzenie wiadomości
	define('GG_RECV_MSG',0x000a); //Przychodząca wiadomość przed GG 8.0
	define('GG_RECV_MSG80',0x002e); //Przychodząca wiadomość
	define('GG_XML_EVENT',0x0027); //Odebrano wiadomość systemową
	define('GG_DISCONNECTING',0x000b); //Zerwanie połączenia
	define('GG_DISCONNECT_ACK',0x000d); //Zerwanie połączenia po zmianie stanu na niedostępny
	define('GG_NOTIFY_REPLY',0x000c); //Stan listy kontaktów przed GG 6.0
	define('GG_NOTIFY_REPLY60',0x0011); //Stan listy kontaktów przed GG 7.7
	define('GG_NOTIFY_REPLY77',0x0018); //Stan listy kontaktów przed GG 8.0
	define('GG_NOTIFY_REPLY80BETA',0x002b); //Stan listy kontaktów przed Nowym Gadu-Gadu
	define('GG_NOTIFY_REPLY80',0x0037); //Stan listy kontaktów
	define('GG_PUBDIR50_REPLY',0x000e); //Odpowiedź katalogu publicznego
	define('GG_USERLIST_REPLY',0x0010); //Odpowiedź listy kontaktów na serwerze przed nowym Gadu-Gadu
	define('GG_USERLIST_REPLY80',0x0030); //Odpowiedź listy kontaktów na serwerze
	define('GG_XML_ACTION',0x002c);
	/**
		 * Dostepne statusy
	*/
	define('GG_STATUS_NOT_AVAIL',0x0001);
	define('GG_STATUS_NOT_AVIAL_DESCR',0x0015);
	define('GG_STATUS_AVAILABLE',0x0002);
	define('GG_STATUS_AVAILABLE_DESCR',0x0004);
	define('GG_STATUS_BUSY',0x0003);
	define('GG_STATUS_BUSY_DESCR',0x0005);
	define('GG_STATUS_INVISIBLE',0x0014);
	define('GG_STATUS_INVISIBLE_DESCR',0x0016);
	define('GG_CLASS_MSG',0x0004); // wiadomosc ma sie pojawic w
	define('GG_CLASS_CHAT',0x0008);
	define('GG_ACK_BLOCKED',0x0001);
	define('GG_ACK_DELIVERED',0x0002);
	define('GG_ACK_QUEUED',0x0003);
	define('GG_ACK_MBOXFULL',0x0004);
	define('GG_ACK_NOT_DELIVERED',0x0004);
	define('GG_FONT_BOLD',0x01);
	define('GG_FONT_ITALIC',0x02);
	define('GG_FONT_UNDERLINE',0x04);
	define('GG_FONT_COLOR',0x08);
	define('GG_FONT_IMAGE',0x80);
	define('GG_PROTOCOL_STAN77',0x00); // Rodzaj pakietu informującego o zmianie stanu kontaktów GG_STATUS77, GG_NOTIFY_REPLY77
	define('GG_PROTOCOL_STAN80BETA',0x01); // GG_STATUS80BETA, GG_NOTIFY_REPLY80BETA
	define('GG_PROTOCOL_STAN80',0x05); // GG_STATUS80, GG_NOTIFY_REPLY80
	define('GG_PROTOCOL_RECV80',0x02); // Rodzaj pakietu z otrzymają wiadomością wył: GG_RECV_MSG, wł: GG_RECV_MSG80
	define('GG_CONN_TIMEOUT',5); // Dla findServer
	define('GG_READ_TIMEOUT',4);
	define('GG_VER_80',0xff);
	define('GG_VER_77',0x2a); // build 3315//
class rfGG
{
	
	public static $verTxt = array(
			GG_VER_80 => '8.0.0.7669', 
			GG_VER_77 => '7.7.0.3315', 
			);
	protected static $ggServer = array('217.17.41.88','217.17.41.83','217.17.41.84','217.17.41.85','91.214.237.2','91.214.237.3','91.214.237.4','91.214.237.5','91.214.237.6','91.214.237.7',
					    '91.214.237.8','91.214.237.9','91.214.237.10','91.214.237.11','91.214.237.12','91.214.237.13','91.214.237.14','91.214.237.15','91.214.237.16','91.214.237.17',
					    '91.214.237.18','91.214.237.19','91.214.237.20','91.214.237.21','91.214.237.22','91.214.237.23','91.214.237.24','91.214.237.25','91.214.237.26','91.214.237.27',
					    '91.214.237.40','91.214.237.41','91.214.237.42','91.214.237.43','91.214.237.44','91.214.237.45','91.214.237.46','91.214.237.47','91.214.237.48','91.214.237.49',
					    '91.214.237.50','91.214.237.51','91.214.237.52','91.214.237.53','91.214.237.54','91.214.237.55','91.214.237.56','91.214.237.57','91.214.237.58','91.214.237.59',
					    '91.214.237.62','91.214.237.63','91.214.237.64','91.214.237.66','91.214.237.67','91.214.237.69','91.214.237.70','91.214.237.72');
	protected $hSocket = null;
	protected $ver = null;
	protected $error_msg = '';
	protected $error_connecting = null;
	protected $messages  = array();
	protected $msg_handler = null;
	
	public function __construct($ver = null)
	{
	    $this -> ver = $ver ? $ver:GG_VER_80;
	}
	
	/**
		* Ustawia handler dla odebranych wiadomosc
		* @param $functionName string Istniejaca funkcja
		* @return string Poprzednia funkcja
	*/
	public function set_message_handler($functionName)
	{
	    $result = $this->msg_handler;
	    $this->msg_handler = $functionName;
	    return $result;
	}
	
	/**
		* Zwraca nazwe bledu
		* @return string
	*/
	public function getError()
	{
		return $this->error_connecting ? $this->error_connecting : $this->error_msg;
	}
	
	/**
		 * Dodaje wiadomosc do listy
		 * @param $message
		 * @return unknown_type 
	*/
	protected function _addMessage($message)
	{
		if (isset($message['offset_plain'])) unset($message['offset_plain']);
		if (isset($message['offset_attr'])) unset($message['offset_attr']);
		if (isset($message['class'])) unset($message['class']);
		if (isset($message['size'])) unset($message['size']);
		if (isset($message['type'])) unset($message['type']);
		if (isset($message['msg_text'])) 
		{
			$message['msg_text'] = iconv('cp1250','utf-8',$message['msg_text']);
		}
		if ($this->msg_handler) 
		{
			$message = call_user_func_array($this->msg_handler,array($message));
			if (!$message) 
			{
				return ;
			}
		}
		array_push($this->messages, $message);
	}
	
	/**
		 * Pobiera jedna wiadomosc (w kolejnosci) i usuwa ja ze stosu
		 * Jesli nie ma wiadomosci zwraca null
		 * @return array
	*/
	public function getMessageOne()
	{
		return array_shift($this->messages);
	}
	
	/*
	    * Wszystkie wiadomosci i czysci liste
	    * @return array
	*/
	public function getMessages()
	{
	    $result = $this->messages;
	    $this->messages = array();
	    return $result;
	}
	
	/**
		 * Funkcja znajduje serwer do polaczen
		 * @param $uid
		 * @return array
	*/
	protected function _findServer($uid)
	{
		if ($hSocket = fsockopen('appmsg.gadu-gadu.pl', 80, $errorNumber, $errorString, GG_CONN_TIMEOUT)) 
		{
			fputs($hSocket,"GET /appsvc/appmsg4.asp?fmnumber=".$uid."&fmt=2&lastmsg=0&version=".self::$verTxt[$this->ver]." HTTP/1.1\r\nHost: appmsg.gadu-gadu.pl\r\n"."User-Agent: Mozilla/4.7 [en] (Win98; I)\r\nPragma: no-cache\r\n\r\n");
			$sData = '';
			while (($tmpData = fgets($hSocket, 128)) !== false) 
			{
				$sData .= $tmpData;
			}
			fclose($hSocket);
			if (strstr('notoperating', $sData))
			{
				return false;
			}
			if (!preg_match('/(([0-9]{1,3}\.){3}[0-9]{1,3})\:([0-9]{1,5})/', $sData, $aRegs) || !ip2long($aRegs[1]))
			{
				return false;
			}
			return array($aRegs[1], $aRegs[3]);
		}
		return false;
	}
	
	public function connect($uid, $password, $description = null, $status = GG_STATUS_AVAILABLE)
	{
		if ($description)
		{
			$statusDescription = iconv('utf-8','cp1250',$statusDescription);
		}
		if (false === ($aServer = $this->_findServer($uid))) 
		{
			$host = $this->ggServer[array_rand($ggServer)];
			$port = 8074;
		} 
		else 
		{
			$host = $aServer[0];
			$port = $aServer[1];
		}
		if ($this->hSocket = fsockopen($host, $port, $errorNumber, $errorString, GG_CONN_TIMEOUT))
		{
		    if (!stream_set_timeout($this->hSocket, GG_READ_TIMEOUT, 0))
		    {
		    	$this->error_msg = "Can't set socket";
		    	return false;
		    }
		    if (!$data = $this->_readPacket())
		    {
		    	$this->error_msg = "Can't open socket";
		    	return false;
		    }
		    if ($data['type'] != GG_WELCOME)
		    {
		    	$this->error_msg = "Not welcome message";
		    	return false;
		    }
		    $seed = unpack('Vseed',$data['value']);
		    return ($this->ver > GG_VER_77) ? $this->_connect80($uid, $password, $seed['seed'], $status, $description) : $this->_connect77($uid, $password, $seed['seed'], $status, $description);
		}
		return false;
	}
	
	/*
		* Polaczenie dla gg 80
		* @param $uid
		* @param $password
		* @param $seed
		* @param $status
		* @param $description
		* @return boolean
	*/
	protected function _connect80($uid, $password, $seed, $status, $description)
	{
		if (! $this->_writePacket(GG_LOGIN80, $this->_packLogin80($uid, $password, $seed, $status, $description)) || !$data = $this->_read(GG_LOGIN_OK80, true)) 
		{
			$this->error_msg = "Login failure";
			return false;
		}
		
		if (!$this->_writePacket(GG_LIST_EMPTY) || !$data = $this->_read(GG_NOTIFY_REPLY80, true)) 
		{
			$this->error_msg = "List failure";
			return false;
		}
		return true;
	}
	
	/**
		 * Polaczenie dla gg 77 i starszych
		 * @param $uid
		 * @param $password
		 * @param $seed
		 * @param $status
		 * @param $description
		 * @return boolean
	*/
	protected function _connect77($uid, $password, $seed, $status, $description)
	{
		if (!$this->_writePacket(GG_LOGIN70, $this->_packLogin70($uid, $password, $seed, $status, $description)) 
		    || !$data = $this->_read(GG_LOGIN_OK, true)) 
		{
			$this->error_msg = "Login failure";
			return false;
		}
		if (!$this->_writePacket(GG_LIST_EMPTY) || !$data = $this->_read(GG_NOTIFY_REPLY77,true))
		{
			$this->error_msg = "List failure";
			return false;
		}
		return true;
	}

	/**
		 * Zmiana statusu
		 * @param $status integer
		 * @param $statusDescription string
		 * @return boolean
	*/
	public function changeStatus($status, $statusDescription = null)
	{
		if (!$this->hSocket)
		{
			$this->error_connecting = 'Not connecting';
			return false;
		}
		if ($statusDescription !== null)
		{
			switch ($status)
			{
				case GG_STATUS_AVAILABLE: $status = GG_STATUS_AVAILABLE_DESCR; break;
				case GG_STATUS_BUSY: $status = GG_STATUS_BUSY_DESCR; break;
				case GG_STATUS_INVISIBLE: $status = GG_STATUS_INVISIBLE_DESCR; break;
				case GG_STATUS_NOT_AVAIL: $status = GG_STATUS_NOT_AVIAL_DESCR; break;
			}
			$statusDescription = iconv('utf-8','cp1250',$statusDescription);
		}
		$result = ($this->ver > GG_VER_77) ? $this->_changeStatus80($status, $statusDescription): $this->_changeStatus77($status, $statusDescription);
		if (!$result)
		{
			$this->error_msg = "Can't send change status";
		}
		return $result;
	}
	
	/**
		* Zmiana statusu wersja starsza niz 80
		* @param $status integer
		* @param $statusDescription string
		* @return boolean
	*/
	protected function _changeStatus77($status, $statusDescription = null)
	{
		if ($statusDescription !== null)
		{
			$packStatus = pack('Va'.strlen($statusDescription).'C',$status, $statusDescription, 0);
		} 
		else
		{
			$packStatus = pack('VC',$status, 0);
		}
		return $this->_writePacket(GG_NEW_STATUS,$packStatus);
	}
	
	/**
		* Zmiana statusu wersja 80 i nowsza
		* @param $status integer
		* @param $statusDescription string
		* @return boolean
	*/
	protected function _changeStatus80($status, $statusDescription = null)
	{
		if ($statusDescription !== null)
		{
			$packStatus = pack('VVVa'.strlen($statusDescription),$status, 0, strlen($statusDescription),$statusDescription);
		}
		else
		{
			$packStatus = pack('VVV',$status, 0, 0);
		}
		return $this->_writePacket(GG_NEW_STATUS80,$packStatus);
	}
	
	/**
		* Odczytuje pakiet danych
		* @return array
	*/
	protected function _readPacket()
	{
		$packet = fread($this->hSocket, 8);
		if (!strlen($packet)) { return false; }
		$packetData = unpack('Vtype/Vsize', $packet);
		if ($packetData['size'] > 0)
		{
			$packetData['value'] = fread($this->hSocket, $packetData['size']);
		}
		return $packetData;
	}
	
	protected function _read($expect, $exactly = true)
	{
		$result = false;
		while ($data = $this->_readPacket())
		{
			$data = $this->_handlePacket($data);
			if ($data['type'] == $expect)
			{
				return $data;
			}
			if ($data['type'] == GG_DISCONNECTING)
			{
				fclose($this->hSocket);
				$this->hSocket = null;
				$this->error_connecting = 'Disconnecting';
				return null;
			}
		}
		return (!$exactly && $data);
	}
	
	/*
		* Wysyla pakiet danych
		* @param $type integer
		* @param $packetData mixed
		* @return boolean
	*/
	protected function _writePacket($type, $data = null)
	{
		$packetData = $data ? pack('VV',$type, strlen($data)).$data : pack('VV',$type, 0);
		return (fwrite($this->hSocket,$packetData) == strlen($packetData));
	}
	
	/*
		 * Wyslanie wiadmosci
		 * @param $recipient
		 * @param $message
		 * @param $html
		 * @return boolean
	*/
	public function sendMessage($recipient, $message, $html = true)
	{
		if (!$this->hSocket)
		{
			$this->error_connecting = 'Not connecting';
			return false;
		}
		$message = preg_replace('#<br\s*/{0,1}>#i',"\r\n",$message);
		$message = strip_tags($message, '<i><b><u><c>');
		//$message = strtr($message, "\xA1\xA6\xAC\xB1\xB6\xBC", "\xA5\x8C\x8F\xB9\x9C\x9F");
		$mSeq = time() + rand(1,999);
		$result = ($this->ver <= GG_VER_77) ? $this->_sendMessage77($recipient, $message, $mSeq) : $this->_sendMessage80($recipient, $message, $mSeq);
		if (!$result || !$data = $this->_read(GG_SEND_MSG_ACK))
		{
			$this->error_msg = "Can't send message";
			return false;
		}
		if ($data['recipient'] != $recipient || $data['seq'] != $mSeq)
		{
			$this->error_msg = "This is not answer (".$data['seq'].") on my message $mSeq";
			return false;
		}
		return true;
	}
	
	/**
		 * Wyslanie wiadmosci
		 * @param $recipient integer numer gg odbiorcy
		 * @param $message string tresc max 2000 znakow
		 * @param $mSeq integer unikalny numer
		 * @param $html boolean jesli chcemy wyslac w formacie plaintext (
		 * @return boolean
	*/
	protected function _sendMessage77($recipient, $message, $mSeq, $html = true)
	{
		$message = iconv('utf-8','cp1250',$message);
		if ($html && $fontFormat = $this->_fontFormat($message))
		{
			$message = strip_tags($message);
			$msgPacket = pack('VVVa'.strlen($message).'CCv', $recipient, $mSeq, GG_CLASS_CHAT, $message, 0, 0x02, strlen($fontFormat)).$fontFormat;
		}
		else
		{
			$msgPacket = pack('VVVa'.strlen($message).'C', $recipient, $mSeq, GG_CLASS_CHAT, $message, 0);
		}
		return $this->_writePacket(GG_SEND_MSG, $msgPacket);
	}
	
	/**
		 * Wyslanie wiadmosci w wersji 80 i nowszej
		 * @param $recipient integer numer gg odbiorcy
		 * @param $message string tresc max 2000 znakow
		 * @param $mSeq integer unikalny numer
		 * @return boolean 
	*/
	protected function _sendMessage80($recipient, $message, $mSeq)
	{
		$plainText = iconv('utf-8','cp1250',$message);
		if (!$fontFormat = $this->_fontFormat($plainText))
		{
			$fontFormat = pack('vCCCC',0,GG_FONT_COLOR,0,0,0);
		}
		$plainText = strip_tags($plainText);
		$msgPacket = pack('VVVVVa'.strlen($message).'Ca'.strlen($plainText).'CCv', $recipient, $mSeq, GG_CLASS_CHAT, 21 + strlen($message), 22 + strlen($message) + strlen($plainText), $message, 0, $plainText, 0, 0x02, strlen($fontFormat)).$fontFormat;
		return $this->_writePacket(GG_SEND_MSG80, $msgPacket);
	}
	
	/**
		 * Obsluga odebranych wiadomosci
		 * @param $packet array Odebrany pakiet
		 * @return array Pakiet po przetworzeniu
	*/
	protected function _handlePacket($packet)
	{
		if ($packet['size'] == 0)
		{
			return $packet;
		}
		$result = array();
		switch ($packet['type'])
		{
			case GG_SEND_MSG_ACK:$result = unpack('Vstatus/Vrecipient/Vseq', $packet['value']); break;
			case GG_RECV_MSG:$result = unpack('Vsender/Vseq/Vtime/Vclass/a'.(strlen($packet['value'])-16).'msg_text', $packet['value']);
					$result['msg_attr'] = preg_replace('#^[^\x0]+#','',$result['msg_text']);
					$result['msg_text'] = preg_replace('#\x0.+$#','',$result['msg_text']);
					$this->_addMessage($result);
					break;
			case GG_RECV_MSG80:$result = unpack('Vsender/Vseq/Vtime/Vclass/Voffset_plain/Voffset_attr/a'.(strlen($packet['value'])-24).'tmp', $packet['value']);
					if ($result['offset_plain'] > 24)
					{
						$tmp = unpack('a'.($result['offset_plain'] - 24).'html/a'.($result['offset_attr'] - $result['offset_plain']).'text/a'.(strlen($packet['value']) - $result['offset_attr']).'attr',$result['tmp']);
						$result['msg_html'] = $tmp['html'];
					}
					else
					{
						$tmp = unpack('a'.($result['offset_attr'] - $result['offset_plain']).'text/a'.(strlen($packet['value']) - $result['offset_attr'] - 3).'attr',$result['tmp']);
					}
					$result['msg_text'] = $tmp['text'];
					$result['msg_attr'] = $tmp['attr'];
					unset($result['tmp']);
					$this->_addMessage($result);
					break;
			case GG_NOTIFY_REPLY:
			case GG_NOTIFY_REPLY60:$result = unpack('Vuin/Cstatus/Vremote_ip/vremote_port/Cversion/Cimage_size', $packet['value']); break;
			case GG_NOTIFY_REPLY77:$result = unpack('Vuin/Cstatus/Vremote_ip/vremote_port/Cversion/Cimage_size', $packet['value']); break;
			case GG_NOTIFY_REPLY80BETA:
			case GG_NOTIFY_REPLY80:$result = unpack('Vuin/Vstatus/Vflags/Vremote_ip/vremote_port/Cversion/Cimage_size', $packet['value']); break;
			case GG_PUBDIR50_REPLY:$tmp = explode("\x00", substr($packet['value'], 5));
					$result = array();
					for ($nr = 0, $cnt = sizeOf($tmp)-5; $nr < $cnt; $nr += 2) $result[$tmp[$nr]] = $tmp[$nr+1];
					break;
			default:$result['value'] = $packet['value'];
		}
		return array_merge(array('type' => $packet['type'], 'size' => $packet['size']),$result);
	}
	
	/*
		 * Wysyla ping (poniwaz serwer nie zawsze odpowiada daltego funkcja zawsze zwroci TRUE)
		 * Jesli serwer nie odpoie funkcja bedzie czekac przez chwile (5 sek jesli nie zostalo to zmienione)
		 * Funkce mozna wykorzystac do pobrania wiadomosci lub sprawdzenia czy nie ma jakies wiadomosci do odbioru
		 * @return boolean
	*/
	public function ping()
	{
		if (!$this->hSocket)
		{
			$this->error_connecting = 'Not connecting';
			return false;
		}
		$this->_writePacket(GG_PING);
		$this->_read(GG_PING);
		return true;
	}
	
	/*
		 * Wysyla pong (poniwaz serwer nie zawsze odpowiada daltego funkcja zawsze zwroci TRUE)
		 * Jesli serwer nie odpoie funkcja bedzie czekac przez chwile (5 sek jesli nie zostalo to zmienione)
		 * @return boolean
	*/
	public function pong()
	{
		if (!$this->hSocket)
		{
			$this->error_connecting = 'Not connecting';
			return false;
		}
		$this->_writePacket(GG_PONG);
		$this->_read(GG_PONG);
		return true;
	}
	
	/*
		* Zamyka polaczenie
		* Przed zamknieciem zmienia status na niedostepny (ewentualnie z opisem)
		* i sprawdza czy nie czekaja jeszcze jakies wiadomosci w sokecie
		* @param $statusDescription string
	*/
	function disconnect($statusDescription = null)
	{
		$this->changeStatus(GG_STATUS_NOT_AVAIL, $statusDescription);
		$this->_read(GG_DISCONNECT_ACK);
		fclose($this->hSocket);
		$this->hSocket = null;
	}
	
	/*
		* Analiza czcionki
		* @param $string
		* @return string
	*/
	protected function _fontFormat($string)
	{
		$string = strtoupper($string);
		$fontFormatData = array(
			'B' => GG_FONT_BOLD, 
			 'I' => GG_FONT_ITALIC, 
			 'U' => GG_FONT_UNDERLINE, 
			 'C' => GG_FONT_COLOR 
			// 8
		);
		if (!preg_match_all("'\<(.*?)\>'", $string, $aRegs, PREG_OFFSET_CAPTURE))
		{
			return false;
		}
		$fontData = $aRegs[0];
		$fontFormat = array();
		$a1 = 0;
		$a2 = array();
		$a3 = array();
		$b = 0;
		$d = 0;
		$cColor = pack('CCC',0,0,0);
		$lastColor = array();
		$indexColor = 0;
		for ($nr = 0, $len = sizeOf($fontData); $nr < $len; $nr++)
		{
			$cFontData = $fontData[$nr];
			$currentPos = $cFontData[1]-$d;
			if ($cFontData[0][1] != '/')
			{
				$lastColor[$indexColor++] = $cColor;
				if (strlen($cFontData[0]) > 3)
				{
					$cColor = pack('CCC', hexdec(substr($cFontData[0], 17, 2)), hexdec(substr($cFontData[0], 19, 2)),hexdec(substr($cFontData[0], 21, 2)));
				}
				$a1 |= $fontFormatData[$cFontData[0][1]];
				if (strlen($cColor))
				{
					$a1 |= GG_FONT_COLOR;
					$a3[$currentPos] = $cColor;
				}
				if (!isset($fontFormat[$currentPos]))
				{
					$fontFormat[$currentPos] = $a1;
				}
				else
				{
					$fontFormat[$currentPos] = $fontFormat[$currentPos] | $a1;
				}
				$d += strlen($cFontData[0]);
			}
			else
			{
				$c = $fontFormatData[$cFontData[0][2]];
				if ($c != GG_FONT_COLOR)
				{
					$a1 ^= $c;
				}
				$cColor = $lastColor[--$indexColor];
				unset($lastColor[$indexColor]);
				if (strlen($cColor))
				{
					$a3[$currentPos] = $cColor;
				}
				if (!isset($fontFormat[$currentPos]))
				{
					$fontFormat[$currentPos] = $a1;
				}
				else
				{
					$fontFormat[$currentPos] = $fontFormat[$currentPos] | $a1;
				}
				$d += strlen($cFontData[0]);
			}
		}
		$b = '';
		foreach ($fontFormat as $k => $v)
		{
			$b .= pack('vC', $k, $v);
			if (isset($a3[$k])) $b .= $a3[$k];
		}
		return $b;
	}
	
	/*
		 * Pakiet logowanie
		 * @param $uid
		 * @param $password
		 * @param $status
		 * @param $seed
		 * @return mixed
	*/
	protected function _packLogin80($uid, $password, $seed, $status, $description = null)
	{
		if ($description)
		{
			switch ($status)
			{
				case GG_STATUS_AVAILABLE: $status = GG_STATUS_AVAILABLE_DESCR; break;
				case GG_STATUS_BUSY: $status = GG_STATUS_BUSY_DESCR; break;
				case GG_STATUS_INVISIBLE: $status = GG_STATUS_INVISIBLE_DESCR;
			}
			return pack('Va2Ca64VVVVvVvCCVa33V'.'a'.strlen($description),$uid,'pl',1, $this->_loginHashGG32($password, $seed), $status, 0, GG_PROTOCOL_STAN80 | GG_PROTOCOL_RECV80, 0, 0, 0, 0, 0, 0x64, 0x21,'Gadu-Gadu Client build '.self::$verTxt[GG_VER_80], strlen($description), $description);
		}
		else
		{
		    return pack('Va2Ca64VVVVvVvCCVa33V',$uid,'pl',1, $this->_loginHashGG32($password, $seed), $status, 0, GG_PROTOCOL_STAN80 | GG_PROTOCOL_RECV80, 0, 0, 0, 0, 0, 0x64, 0x21,'Gadu-Gadu Client build '.self::$verTxt[GG_VER_80], 0);
		}
	}
	
	/*
		 * Pakiet logowanie
		 * @param $uid
		 * @param $password
		 * @param $status
		 * @param $seed
		 * @return mixed
	*/
	protected function _packLogin70($uid, $password, $seed, $status, $description = null)
	{
		if ($description)
		{
			switch ($status)
			{
				case GG_STATUS_AVAILABLE: $status = GG_STATUS_AVAILABLE_DESCR; break;
				case GG_STATUS_BUSY: $status = GG_STATUS_BUSY_DESCR; break;
				case GG_STATUS_INVISIBLE: $status = GG_STATUS_INVISIBLE_DESCR;
			}
			return pack('VCa64VVCVvVvCC'.'a'.strlen($description).'C',$uid,1, $this->_loginHashGG32($password, $seed), $status, $this->ver, 0, 0, 0, 0, 0, 0, 0xBE, $description,0);
		}
		else
		{
			return pack('VCa64VVCVvVvCC',$uid,1,$this->_loginHashGG32($password,$seed),$status,$this->ver, 0, 0, 0, 0, 0, 0, 0xBE);
		}
	}
	
	/*
		 * Kodowanie hasla metoda GG32
		 * @param $password
		 * @param $seed
		 * @return unknown_type
	*/
	protected function _loginHashGG32($password, $seed)
	{
		$y = $seed;
		$x = 0;
		for ($nr = 0 ; $nr < strlen($password); $nr++)
		{
			$x = ($x & 0xFFFFFF00) | ord($password[$nr]);
			$y ^= $x;
			$y += $x;
			$x <<= 8;
			$y ^= $x;
			$x <<= 8;
			$y -= $x;
			$x <<= 8;
			$y ^= $x;
			$z = $y & 0x1F;
			$y1 = ($y << $z);
			if ($z < 32)
			{
				$y2 = $y >> 1;
				$y2 &= 0x7FFFFFFF;
				$y2 = $y2 >> (31 - $z);
			}
			$y = $y1 | $y2;
		}
		return pack('V',$y);
	}
	
}
?>