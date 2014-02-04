<?php
class RPCError {
	public $code;
	public $message;
}

class mJsonRPCResponse {
	public $result = null;
	public $error = null;
	public $id;
	public $jsonrpc = 2.0;

	public function setResult ($sResult) {
		$this->result = $sResult;
	}
	
	public function setError ($oError) {
		$err = new RPCError();
		if (isset($oError->code)) {
			$err->code = $oError->code;
		}
		if (isset($oError->message)) {
			$err->message = $oError->message;
		}
		$this->error = $err;
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