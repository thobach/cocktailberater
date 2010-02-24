<?php
/**
 * API ErrorController
 * 
 * @author thobach
 *
 */
class Api_ErrorController extends Zend_Controller_Action
{
	private $xml;
	private $rsp;
	private $config;
	private $msgFound = FALSE;
	private $errorMsg;

	public function preDispatch() {
		// do not automatically create a view object
		$this->_helper->viewRenderer->setNoRender();
		// disable layouts for this controller
		$this->_helper->layout->disableLayout();
		// set http header to xml
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
		// create a dom object
		$this->xml1 = new DOMDocument("1.0");
		// create root element 'rsp'
		$this->rsp1 = $this->xml1->createElement("rsp");
		// add the 'rsp' element to the xml document
		$this->xml1->appendChild($this->rsp1);
		// load config data
		$this->config = $this->getFrontController()->getParam('bootstrap')->getOptions();
	}

	public function errorAction()
	{
		// load XML file
		$xmlFile = APPLICATION_PATH.'/languages/errors.xml' ;
		// parse XML file
		$errors = new Zend_Config_Xml ( $xmlFile, 'errors' ) ;
		// default
		$searchID = $error->id;
		 
		// differenciate between zend_exceptions and cocktailexceptions
		if ( is_a ( $this->_getParam('error')  , 'CocktailberaterException' )) {
			$searchID = $this->_getParam('error')->getMessage();
		}

		// get error message
		foreach ($errors->error as $error) {
			if ($searchID == $error->id) {
				$this->errorMsg = $error->german;
				$this->msgFound = TRUE;
			}
		}
		// display "unknown error" if errormsg is not found
		if ($this->msgFound == FALSE) {
			$errorArray = $errors->error->toArray();
			$this->errorMsg = $errorArray[0][german];
		}
		 
		 
		// create new ApiError Object
		new Api_Model_ApiError(get_class($this->_getParam('error')),$this->_getParam('error'),$this->rsp1,$this->xml1,$this->errorMsg);
	}

	public function postDispatch(){
		// save and display tree
		print $this->xml1->saveXML();
	}
}

?>