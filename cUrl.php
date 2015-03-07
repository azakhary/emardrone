<?php 
/**
 * cUrl Parser Class
 *
 */
class cUrl {
	
	/**
	 * cUrl initialized object
	 *
	 * @var curl
	 */
	private $ch;
	
	/**
	 * response data
	 *
	 * @var string
	 */
	private $data;
	
	/**
	 * Initializes cUrl
	 *
	 */
	function __construct() {
		$this->ch = curl_init();
	
	}
	
	/**
	 * Loads specified Url
	 *
	 * @param string $url
	 */
	function loadUrl($url) {
		curl_setopt($this->ch, CURLOPT_URL, $url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1); 

		$this->data = curl_exec($this->ch);	
	}
	
	/**
	 * returns response data
	 *
	 * @return string
	 */
	function getData() {
		return $this->data;
	}
	
	function setPostData($data) {
		  curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
		  curl_setopt($this->ch, CURLOPT_POST, 1);		
	}
	
	/**
	 * Filetrs response data
	 *
	 * @param string $startString
	 * @param string_type $endString
	 * @return boolean
	 */
	function filterData($startString, $endString) {
		if(strpos($this->data, $startString) === false || strpos($this->data, $endString) === false) {
			return false;
		}
		//start String End Posiotion
		$ssendpos = strpos($this->data, $startString) + strlen($startString);
		//end String Start Position
		$ssstartpos = strpos($this->data, $endString, $ssendpos);		
		
		if($ssendpos > $ssstartpos) {
			return false;
		}
		
		$needle = substr($this->data, $ssendpos, $ssstartpos-$ssendpos);		
		
		$this->data = $needle;
		
		return true;
	}
	
	/**
	 * Returns array of strings between specified substrings
	 *
	 * @param string $startString
	 * @param string $endString
	 * @return array or string
	 */
	function getBetween($startString, $endString) {
		$offset = 0;
		if(strpos($this->data, $startString) === false || strpos($this->data, $endString) === false) {
			return false;
		}		
		//start String End Posiotion
		$ssendpos = strpos($this->data, $startString, $offset) + strlen($startString);
		//end String Start Position
		$ssstartpos = strpos($this->data, $endString, $ssendpos);	

		while($ssendpos <= $ssstartpos && strpos($this->data, $startString, $offset) !== false && strpos($this->data, $endString, $ssendpos) !== false) {
			$needles[] = substr($this->data, $ssendpos, $ssstartpos-$ssendpos);
			$offset = $ssstartpos + strlen($endString);
			//start String End Posiotion
			$ssendpos = strpos($this->data, $startString, $offset) + strlen($startString);
			//end String Start Position
			$ssstartpos = strpos($this->data, $endString, $ssendpos);						
		}
		if (count($needles) == 0) {
			return false;
		} else if(count($needles) == 1) {
			return $needles[0];
		} else {
			return $needles;
		}
	}
	
	static function getBetweenString($data, $startString, $endString) {
		$offset = 0;
		if(strpos($data, $startString) === false || strpos($data, $endString) === false) {
			return false;
		}		
		//start String End Posiotion
		$ssendpos = strpos($data, $startString, $offset) + strlen($startString);
		//end String Start Position
		$ssstartpos = strpos($data, $endString, $ssendpos);		
		
		while($ssendpos <= $ssstartpos && strpos($data, $startString, $offset) !== false && strpos($data, $endString, $ssendpos) !== false) {
			$needles[] = substr($data, $ssendpos, $ssstartpos-$ssendpos);
			$offset = $ssstartpos + strlen($endString);
			//start String End Posiotion
			$ssendpos = strpos($data, $startString, $offset) + strlen($startString);
			//end String Start Position
			$ssstartpos = strpos($data, $endString, $ssendpos);						
		}
		if (count($needles) == 0) {
			return false;
		} else if(count($needles) == 1) {
			return $needles[0];
		} else {
			return $needles;
		}		
	}
}

?>