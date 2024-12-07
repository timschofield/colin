<?php

class URLDetails {

	public $SessionID;
	public $URL;
	public $PostArray;
	public $FormDetails;
	public $xml;
	public $Links;

	function __construct($SessionID, $URL, $PostArray) {
		$this->SessionID = $SessionID;
		$this->URL = $URL;
		$this->PostArray = $PostArray;
	}

	private function GetTextDetails() {
		$Texts=array();
		$Result=$this->xml->getElementsByTagName('input');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			if ($Result->item($i)->getAttribute('type')=='text') {
				for ($j=0; $j<$Result->item($i)->attributes->length; $j++) {
					$name = $Result->item($i)->attributes->item($j)->name;
					$Texts['text'][$k][$name]=(string)$Result->item($i)->attributes->getNamedItem($name)->nodeValue;
				}
				if (!isset($Texts['text'][$k]['maxlength'])) {
					error_log('**Warning** '.$Texts['text'][$k]['name'].' in '.$this->URL.' has no maxlength attribute set.'."\n\n", 3, '/home/tim/weberp'.date('Ymd').'.log');
					$Texts['text'][$k]['maxlength'] = 10;
				}
				if (!isset($Texts['text'][$k]['minlength'])) {
					$Texts['text'][$k]['minlength'] = 1;
				}
				$k++;
			}
		}
		return $Texts;
	}

	private function GetSubmitDetails() {
		$Submits=array();
		$Result=$this->xml->getElementsByTagName('input');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			if ($Result->item($i)->getAttribute('type')=='submit') {
				for ($j=0; $j<$Result->item($i)->attributes->length; $j++) {
					$name = $Result->item($i)->attributes->item($j)->name;
					$Submits['submit'][$k][$name]=(string)$Result->item($i)->attributes->getNamedItem($name)->nodeValue;
				}
				$k++;
			}
		}
		return $Submits;
	}

	private function GetButtonDetails() {
		$Submits=array();
		$Result=$this->xml->getElementsByTagName('button');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			if ($Result->item($i)->getAttribute('type')=='submit') {
				for ($j=0; $j<$Result->item($i)->attributes->length; $j++) {
					$name = $Result->item($i)->attributes->item($j)->name;
					$Submits['button'][$k][$name]=(string)$Result->item($i)->attributes->getNamedItem($name)->nodeValue;
				}
				$k++;
			}
		}
		return $Submits;
	}

	private function GetRadioDetails() {
		$Radios=array();
		$Result=$this->xml->getElementsByTagName('input');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			if ($Result->item($i)->getAttribute('type')=='radio') {
				for ($j=0; $j<$Result->item($i)->attributes->length; $j++) {
					$name = $Result->item($i)->attributes->item($j)->name;
					$Radios['radio'][$k][$name]=(string)$Result->item($i)->attributes->getNamedItem($name)->nodeValue;
				}
				$k++;
			}
		}
		return $Radios;
	}

	private function GetCheckBoxDetails() {
		$CheckBoxs=array();
		$Result=$this->xml->getElementsByTagName('input');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			if ($Result->item($i)->getAttribute('type')=='checkbox') {
				for ($j=0; $j<$Result->item($i)->attributes->length; $j++) {
					$name = $Result->item($i)->attributes->item($j)->name;
					$CheckBoxs['checkbox'][$k][$name]=(string)$Result->item($i)->attributes->getNamedItem($name)->nodeValue;
				}
				$k++;
			}
		}
		return $CheckBoxs;
	}

	private function GetHiddenDetails() {
		$Hiddens=array();
		$Result=$this->xml->getElementsByTagName('input');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			if ($Result->item($i)->getAttribute('type')=='hidden') {
				for ($j=0; $j<$Result->item($i)->attributes->length; $j++) {
					$name = $Result->item($i)->attributes->item($j)->name;
					$Hiddens['hidden'][$k][$name]=(string)$Result->item($i)->attributes->getNamedItem($name)->nodeValue;
				}
				$k++;
			}
		}
		return $Hiddens;
	}

	private function GetPasswordDetails() {
		$Passwords=array();
		$Result=$this->xml->getElementsByTagName('input');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			if ($Result->item($i)->getAttribute('type')=='password') {
				for ($j=0; $j<$Result->item($i)->attributes->length; $j++) {
					$name = $Result->item($i)->attributes->item($j)->name;
					$Passwords['password'][$k][$name]=(string)$Result->item($i)->attributes->getNamedItem($name)->nodeValue;
				}
				$k++;
			}
		}
		return $Passwords;
	}

	private function GetSelectDetails() {
		$Selects=array();
		$Result=$this->xml->getElementsByTagName('select');
		for ($i=0; $i<$Result->length; $i++) {
			$SelectName=$Result->item($i)->getAttribute('name');
			$Result1=$Result->item($i)->getElementsByTagName('option');
			for ($j=0; $j<$Result1->length; $j++) {
				for ($k=0; $k<$Result1->item($j)->attributes->length; $k++) {
					$name = $Result1->item($j)->attributes->item($k)->name;
					$Selects['select'][$SelectName]['options'][$j][$name]=(string)$Result1->item($j)->attributes->getNamedItem($name)->nodeValue;
				}
			}
		}
		return $Selects;
	}

	public function GetHREFDetails() {
		$Links=array();
		$Result=$this->xml->getElementsByTagName('a');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			for ($j=0; $j<$Result->item($i)->attributes->length; $j++) {
				$name = $Result->item($i)->attributes->item($j)->name;
				$Links[$k][$name]=(string)$Result->item($i)->attributes->getNamedItem($name)->nodeValue;
				$Links[$k]['value']=$Result->item($i)->nodeValue;
			}
			$k++;
		}
		return $Links;
	}

	public function GetLabelDetails() {
		$Labels=array();
		$Result=$this->xml->getElementsByTagName('label');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			for ($j=0; $j<$Result->item($i)->attributes->length; $j++) {
				$name = $Result->item($i)->attributes->item($j)->name;
				$Labels[$k][$name]=(string)$Result->item($i)->attributes->getNamedItem($name)->nodeValue;
				$Labels[$k]['value']=$Result->item($i)->nodeValue;
			}
			$k++;
		}
		return $Labels;
	}

	public function GetFormAction() {
		$Action='';
		$Result=$this->xml->getElementsByTagName('form');
		$k=0;
		for ($i=0; $i<$Result->length; $i++) {
			$Action=(string)$Result->item($i)->attributes->getNamedItem('action')->nodeValue;
		}
		return $Action;
	}

	public function GetFormDetails() {
		$this->FormDetails['Labels']=$this->GetLabelDetails();
		$this->FormDetails['Texts']=$this->GetTextDetails();
		$this->FormDetails['Passwords']=$this->GetPasswordDetails();
		$this->FormDetails['Selects']=$this->GetSelectDetails();
		$this->FormDetails['Submits']=$this->GetSubmitDetails();
		$this->FormDetails['Buttons']=$this->GetButtonDetails();
		$this->FormDetails['Hiddens']=$this->GetHiddenDetails();
		$this->FormDetails['Action']=$this->GetFormAction();
		return $this->FormDetails;
	}

	private function ValidateHTML($html) {
		$Validator = new XhtmlValidator();
		$Result=$Validator->validate($html);
		if($Validator->validate($html) === false){
			error_log('**Error**'.'There are errors in the XHTML of page '.$this->URL."\n", 3, '/home/tim/weberp'.date('Ymd').'.log');
			$Validator->logErrors();
		}
		return $Result;
	}

	private function ValidateLinks($ServerPath) {
		for ($i=0; $i<sizeOf($this->Links); $i++) {
			if (!strstr($this->Links[$i]['href'], 'Logout.php')) {
				if (strstr($this->Links[$i]['href'], 'http:')) {
					exec('curl -ILs -b '.$this->SessionID.' '.$this->Links[$i]['href'], $Output);
				} else {
					exec('curl -ILs -b '.$this->SessionID.' '.$ServerPath.$this->Links[$i]['href'], $Output);
				}
				if (strstr($Output[0], '404')) {
					error_log('**Warning**'.$i.' '.$ServerPath.$this->Links[$i]['href'].' '.$Output[0]."\n", 3, '/home/tim/weberp'.date('Ymd').'.log');
				}
			}
		}
	}

	public function CheckForXDebugMessages($HTML, $TestNumber) {
		if (strstr($HTML, 'xdebug-error')) {
			LogMessage($TestNumber, 1, 'X-Debug Error message', $HTML);
		}
	}

	public function FetchPage($RootPath, $ServerPath, $TestNumber) {
		$PostString='';
		foreach($this->PostArray as $Key=>$Value) {
			$PostString .= $Key.'='.urlencode($Value).'&';
		}
		rtrim($PostString,'&');
echo 'curl -s -b '.$this->SessionID.' -d "' . $PostString . '" "' . $this->URL . '"' , $FormArray."\n";
		if (file_exists($this->SessionID)) {
			exec('curl -s -b '.$this->SessionID.' -d "' . $PostString . '" "' . $this->URL . '"' , $FormArray);
		} else {
			exec('curl -s -c '.$this->SessionID.' -d "' . $PostString . '" "' . $this->URL . '"' , $FormArray);
		}

		$Result[0] = '<html>';
		for ($i=2; $i < sizeOf($FormArray); $i++) {
			$Result[0] .= $FormArray[$i];
		}

		$this->CheckForXDebugMessages($Result[0], $TestNumber);
		$this->xml = new DOMDocument();
		$this->xml->loadHTML($Result[0]);
//		$answer = $this->ValidateHTML($Result[0]);

		$this->Links=$this->GetHREFDetails();
		$this->ValidateLinks($ServerPath);

		$Result[1] = $this->GetHREFDetails();
		$Result[2] = $this->GetFormDetails();
		$Result[3] = $this->Links;

		return $Result;
	}

}

?>