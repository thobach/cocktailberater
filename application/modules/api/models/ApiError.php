<?php
class ApiError {
	private $code;
	private $message;
	private $details;
	
	public function ApiError ($code, Exception $e, DOMElement $rsp, DOMDocument $xml, $msg = NULL){
		$this->code = $code;
		$this->message = $e->getMessage();
		$this->details = $e;
		// echo "Errormessage: ".$msg;
		
		// set status for 'rsp' element to 'fail'
		$rsp->setAttribute('status','fail');
		// create error element
		$error = $xml->createElement('error');
		// add the error element to the 'rsp' element
		$rsp->appendChild($error);
		// set the error code for the error element
		$error->setAttribute('code',$code);
		// set the error message for error element
		if ($msg == NULL) {
		$error->setAttribute('msg',$e->getMessage()); }
		else {
			$error->setAttribute('msg',$msg);
		}
		// set the error details for the error element
		$error->setAttribute('details',$e);
	}
	
}
?>