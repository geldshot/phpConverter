<?php

class converter{
	/*converter class
	Author: Timothy Jones
	Date: 10/16/2012
	Purpose: built to handle file movement and control where the encode/decode process
		happens. Meant to be extended by various encoder classes. Set to work from
		a directory inside of webroot, moving the file requires changing CFP.
	*/
	protected $CFP = '../';//core file path
	protected $Source = ''; //source file path
	protected $Destination = ''; //destination file path
	protected $Working = ''; //working directory file path
	protected $SourceFile = ''; //source filename
	protected $DestFile = ''; //end filename
	protected $Type = ''; //type of converter
	protected $Error = 'encoder: call setFiles';

	public function _construct($arr){
		$this->Source = $this->CFP . '/' . $arr['ClientID'] . '/' .dirname($arr['Source']);
		$this->Destination = $this->CFP . '/' . $arr['ClientID'] . '/' .dirname($arr['Destination']);
		if(isset($arr['EventID']))
			$this->Working = $this->CFP . '/' . $arr['ClientID'] . '/' 
				. $arr['EventID'] . '-' . $this->Type;
		else
			$this->Working = $this->CFP . '/' . $arr['ClientID'] . '/' 
				. $arr['ClientID'] . '-' . $this->Type;
		$this->SourceFile = basename($arr['Source']);
		$this->DestFile = basename($arr['Destination']);
		$this->Error = null;
		}

	public function convert(){
		echo "CFP: {$this->CFP}\n<br/>";
		echo "Source: {$this->Source}\n<br/>";
		echo "Working: {$this->Working}\n<br/>";
		echo "SourceFile: {$this->SourceFile}\n<br/>";
		echo "DestFile: {$this->DestFile}\n<br/>";
		echo "Type: {$this->Type}\n<br/>";
		$this->moveIn();

		$this->_convert();

		$this->moveOut();
	}



	protected function setFiles($arr){//takes array{ Source:'subfolder/file.ext', Destination:'subfolder/file.ext', ClientID:'clientid'}
		$this->Source = $this->CFP . '/' . $arr['ClientID'] . '/' .dirname($arr['Source']);
		$this->Destination = $this->CFP . '/' . $arr['ClientID'] . '/' .dirname($arr['Destination']);
		if(isset($arr['EventID']))
			$this->Working = $this->CFP . '/' . $arr['ClientID'] . '/' 
				. $arr['EventID'] . '-' . $this->Type;
		else
			$this->Working = $this->CFP . '/' . $arr['ClientID'] . '/' 
				. $arr['ClientID'] . '-' . $this->Type;
		$this->SourceFile = basename($arr['Source']);
		$this->DestFile = basename($arr['Destination']);
		$this->Error = null;
	}

	protected function moveIn(){
		$this->_makeWorking();
		$this->_copyWorking();
	}

	protected function _copyWorking(){
		$out = shell_exec("cp {$this->Source}/{$this->SourceFile} {$this->Working}/"
			."{$this->SourceFile}");
	}

	protected function _makeWorking(){
		$out = shell_exec("mkdir -p {$this->Working}");
	}

	protected function moveOut(){
		$this->moveWorking();
		$this->removeWorking();
	}

	protected function moveWorking(){
		$out = shell_exec("mv {$this->Working}/{$this->DestFile} "
			."{$this->Destination}/{$this->DestFile}");
	}

	protected function removeWorking(){
		$out = shell_exec("rm -rf {$this->Working}");
	}

	protected function _convert(){
		$this->Error = "converter: implement _convert()";
	}

	protected function getError(){
		return json_encode($this->Error);
	}
}

?>