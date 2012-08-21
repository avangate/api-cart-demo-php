<?php
class mJsonRPCResponse {
	public $result = null;
	public $error = null;
	public $id;
	
	public function setResult ($sResult) {
		$this->result = $sResult;
	}
	
	public function setError ($aError) {
		$this->error = $aError;
	}
	
	public function setId ($sId) {
		$this->id = $sId;
	}

	public function getResult () {
		return $this->result;
	}
	
	public function getError () {
		return $this->error;
	}
	
	public function getId () {
		return $this->id;
	}
	
	static public function decode ($sResponse) {
		$oResponse = new self();
		
		$oStdClass = json_decode ($sResponse);
		try {
			if (isset($oStdClass->result)) {
				$oResponse->setResult($oStdClass->result); // need to deserialize it
			}
			
			if (isset($oStdClass->error)) { 
				$oResponse->setError($oStdClass->error);
			}
			if (isset($oStdClass->id)) { 
				$oResponse->setId($oStdClass->id);
			}
		} catch (Exception $e) {
			_e ($e);
		}
		
		return $oResponse;
	}
}