<?php
class mJsonRPCRequest {
	public $method;
	public $params;
	public $id;
	public $jsonrpc = 2.0;

// 	public function setMethod ($sMethod) {
// 		$this->method = $sMethod;
// 	}
	
// 	public function setParams ($aParams) {
// 		$this->params = $aParams;
// 	}
	
// 	public function setId ($sId) {
// 		$this->id = $sId;
// 	}

// 	public function getMethod () {
// 		return $this->method;
// 	}
	
// 	public function getParams () {
// 		return $this->params;
// 	}
	
// 	public function getId () {
// 		return $this->id;
// 	}
	
	static public function encode (mJsonRPCRequest $oRequest) {
		return json_encode ($oRequest);
	}
}