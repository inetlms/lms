<?
class SerwerSMS {


    public static $API_URL = 'https://api1.serwersms.pl/zdalnie/';
    
    public static function wyslij_sms($Parametry) {
        return SerwerSMS::Zapytanie("wyslij_sms", $Parametry);
    }

    public static function sprawdz_sms($Parametry) {
        return SerwerSMS::Zapytanie("sprawdz_sms", $Parametry);
    }

    public static function ilosc_sms($Parametry) {
        return SerwerSMS::Zapytanie("ilosc_sms", $Parametry);
    }

    public static function sprawdz_odpowiedzi($Parametry) {
        return SerwerSMS::Zapytanie("sprawdz_odpowiedzi", $Parametry);
    }
    
    public static function pliki($Parametry) {
        return SerwerSMS::Zapytanie("pliki",$Parametry);
    }
    
    public static function premium_api($Parametry) {
        return SerwerSMS::Zapytanie("premium_api",$Parametry);
    }
    
    public static function usun_zaplanowane($Parametry) {
        return SerwerSMS::Zapytanie("usun_zaplanowane",$Parametry);
    }
    
    public static function pobierz_mms($Parametry){
        return SerwerSMS::Zapytanie("pobierz_mms",$Parametry);
    }
    
    public static function nazwa_nadawcy($Parametry){
        return SerwerSMS::Zapytanie("nazwa_nadawcy",$Parametry);
    }
    
    public static function hlr($Parametry){
        return SerwerSMS::Zapytanie("hlr",$Parametry);
    }
    
    public static function kontakty($Parametry){
        return SerwerSMS::Zapytanie("kontakty",$Parametry);
    }
    
    public static function mms_z_dysku($plik){
        if(is_uploaded_file($plik['tmp_name'])){
            
            $f = file_get_contents($plik['tmp_name']);
            
            return SerwerSMS::pliki(array(plik_mms => $f));

        } else {
            return false;
        }
    }

    private static function Zapytanie($akcja, $params) {

        $requestUrl = SerwerSMS::$API_URL;
		$params["akcja"] = $akcja;
	$konto['login'] = get_conf('sms.username','');
	$konto['haslo'] = get_conf('sms.password','');
        $postParams = array_merge($konto, $params);

        $curl = curl_init($requestUrl);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postParams));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($curl,CURLOPT_TIMEOUT,60); 
        $answer = curl_exec($curl);
		if (curl_errno($curl)) {
			die('<pre style="color:red">'.curl_error($curl).':'.curl_errno($curl).'</pre>');
			exit();
		}
        curl_close($curl);

        return $answer;
    }
}

function xml_attribute($object, $attribute)
{
	if(isset($object[$attribute]))
	return (string) $object[$attribute];
}


function PrzetworzXML($akcja,$xml_file) {
	$dom = new domDocument;
	$dom->loadXML($xml_file);
	$xml = simplexml_import_dom($dom);
	
	if (isset($xml->Blad)) {
		$numer = $_POST['numer'];
		$przyczyna = $xml->Blad;
		echo 'Błąd ogólny: '.$przyczyna;
	}

	if($akcja=="wyslij_sms") {
		if(isset($xml->Odbiorcy->Skolejkowane)){	
			foreach($xml->Odbiorcy->Skolejkowane->SMS as $sms) {
				echo '
				Zapis wysłanych do bazy - smsid: '.xml_attribute($sms, 'id').'; numer: '.xml_attribute($sms, 'numer').'; godzina_skolejkowania: '.xml_attribute($sms, 'godzina_skolejkowania');
			}
		} 
		if (isset($xml->Odbiorcy->Niewyslane)) {
			foreach($xml->Odbiorcy->Niewyslane->SMS as $sms) {
				echo '
				Zapis niewysłanych do bazy - smsid: '.xml_attribute($sms, 'id').'; numer: '.xml_attribute($sms, 'numer').'; przyczyna: '.xml_attribute($sms, 'przyczyna');
			}
		}
	}
	
	if($akcja=="sprawdz_sms") {
		if(isset($xml->SMS)){
			foreach($xml->SMS as $sms) {
				echo '
				Sprawdzanie statusów - smsid: '.xml_attribute($sms, 'id').'; numer: '.xml_attribute($sms, 'numer').'; stan: '.xml_attribute($sms, 'stan').'; przyczyna: '.xml_attribute($sms, 'przyczyna');
			}
		} 
	}

	if($akcja=="ilosc_sms") {
		if(isset($xml->SMS)){
			foreach($xml->SMS as $sms) {
				echo '
				Sprawdzanie limitów - typ: '.xml_attribute($sms, 'typ').'; limit: '.$sms;
			}
		} 
	}
	
	if($akcja=="sprawdz_odpowiedzi") {
		if(isset($xml->SMS)){	
			foreach($xml->SMS as $sms) {
				echo '
				Wiadomość przychodząca - id: '.xml_attribute($sms, 'id').'; numer: '.xml_attribute($sms, 'numer').'; data: '.xml_attribute($sms, 'data').'; tresc: '.xml_attribute($sms, 'tresc').'; na numer: '.xml_attribute($sms, 'na_numer');
			}
		}
                if(isset($xml->MMS)){
                    foreach($xml->MMS as $mms){
                        echo'
                        Wiadomość MMS - id: '.xml_attribute($mms, 'id').'; numer: '.xml_attribute($mms, 'numer').'; data: '.xml_attribute($mms, 'data').'; temat: '.xml_attribute($mms, 'temat');
                        if(isset($xml->MMS->Zalacznik)){
                            foreach($xml->MMS->Zalacznik as $zalacznik){
                                echo '
                                Załącznik - id: '.xml_attribute($zalacznik, 'id').'; nazwa: '.xml_attribute($zalacznik,'nazwa').'; contenttype: '.xml_attribute($zalacznik,'contenttype').'; zawartość: '.$zalacznik;
                            }
                        }
                    }
                }
	}
        
        if($akcja=="pliki") {
            if(isset($xml->Plik)){
                foreach($xml->Plik as $plik){
                    echo '
                    Plik - id: '.xml_attribute($plik, 'id').'; nazwa: '.$plik->Nazwa.'; rozmiar: '.$plik->Rozmiar.'; typ: '.$plik->Typ.'; data: '.$plik->Data;

                }
            }
        }
        
        if($akcja=="premium_api"){
            if(isset($xml->SMS) and $xml->SMS == "OK"){
                echo '
                    Odpowiedź wysłana - id: '.xml_attribute($xml->SMS,'id');
                
            }elseif(isset($xml->SMS)){
                foreach($xml->SMS as $sms){
                    echo '
                    Wiadomość: '.xml_attribute($sms, 'id').'; na numer: '.xml_attribute($sms, 'na_numer').'; z numeru: '.xml_attribute($sms, 'z_numeru').'; data: '.xml_attribute($sms, 'data').'; limit: '.xml_attribute($sms, 'limit').'; tekst: '.$sms;
                }
            }
        }
        
        if($akcja=="usun_zaplanowane"){
            if(isset($xml->ZAPLANOWANE)){
                foreach($xml->ZAPLANOWANE as $zaplanowane){
                    if($zaplanowane == "OK"){
                        echo '
                            Usunięto sms - smsid:'.xml_attribute($zaplanowane,'smsid');;
                    } 
                    if($zaplanowane == "ERR"){
                        echo '
                            Nie znaleziono wiadomości - smsid:'.xml_attribute($zaplanowane,'smsid');
                    }
                }
            }
        }
        
        if($akcja=="pobierz_mms"){
            if(isset($xml->MMS)){
                foreach($xml->MMS as $mms){
                    echo'
                    Wiadomość MMS - id: '.xml_attribute($mms, 'id').'; numer: '.xml_attribute($mms, 'numer').'; data: '.xml_attribute($mms, 'data');
                    if(isset($mms->Zalacznik)){
                        foreach($mms->Zalacznik as $zalacznik){
                            echo '
                            Załącznik - id: '.xml_attribute($zalacznik, 'id').'; nazwa: '.xml_attribute($zalacznik,'nazwa').'; contenttype: '.xml_attribute($zalacznik,'contenttype').'; zawartość: '.$zalacznik;
                        }
                    }
                }
            }
        }
        
        if($akcja=="nazwa_nadawcy"){
            if(isset($xml->NADAWCA)){
                foreach($xml->NADAWCA as $nadawca){
                    echo '
                    Nadawca - nazwa: '.xml_attribute($nadawca,'nazwa').'; status: '.$nadawca;
                }
            }
        }
        
        if($akcja=="hlr"){
            if(isset($xml->NUMER)){
                echo'
                Numer: '.xml_attribute($xml->NUMER,'numer').'; status: '.$xml->NUMER->status.'; imsi: '.$xml->NUMER->imsi.'; sieć macierzysta: '.$xml->NUMER->siec_macierzysta.'; przenoszony: '.$xml->NUMER->przenoszony.'; sieć obecna: '.$xml->NUMER->siec_obecna;
            }
        }
        
        if($akcja=="kontakty"){
            if(isset($xml->GRUPA->KONTAKT)){
                if(isset($xml->GRUPA->NAZWA)){
                        echo '
                        Nazwa grupy: '.$xml->GRUPA->NAZWA.'; ID grupy: '.xml_attribute($xml->GRUPA,'id').'; liczba kontaktów: '.xml_attribute($xml->GRUPA,'ilosc');
                    }
                foreach($xml->GRUPA->KONTAKT as $kontakt){
                    if(isset($kontakt)){
                        echo'
                        ID kontaktu: '.xml_attribute($kontakt,'id').'; Telefon: '.$kontakt->TELEFON.'; E-mail: '.$kontakt->EMAIL.'; Firma: '.$kontakt->FIRMA.'; Imie: '.$kontakt->IMIE.'; Nazwisko: '.$kontakt->NAZWISKO;
                    }
                }
            } elseif (isset($xml->GRUPA->NAZWA)) {
                foreach($xml as $grupy){
                    if(isset($grupy)){
                        echo '
                        Nazwa grupy: '.$grupy->NAZWA.'; ID grupy: '.xml_attribute($grupy,'id').'; liczba kontaktów: '.xml_attribute($grupy,'ilosc');
                    }
                }
            } elseif (isset($xml->GRUPA)){
                echo '
                ID grupy: '.xml_attribute($xml->GRUPA,'id').'; Stan: '.$xml->GRUPA;
                
            } elseif (isset($xml->KONTAKT)){
                echo '
                ID kontaktu: '.xml_attribute($xml->KONTAKT,'id').'; Stan: '.$xml->KONTAKT;
            }
        }

}

?>
