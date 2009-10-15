<?php


/**
 * Class responsible for testing the access to the REST API
 */

class Api_TestController extends Zend_Controller_Action {

	private $xml; // xml dom document
	private $rsp; // root element for xml document
	private $config; // config data from xml file
	private $error; // boolean - if error, don't do postDispatch

	public function preDispatch() {
		// do not automatically create a view object
		$this->_helper->viewRenderer->setNoRender();
		// disable layouts for this controller
		$this->_helper->layout->disableLayout();
		// set http header to xml
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
		// create a dom object
		$this->xml = new DOMDocument("1.0");
		// create root element 'rsp'
		$this->rsp = $this->xml->createElement("rsp");
		// add the 'rsp' element to the xml document
		$this->xml->appendChild($this->rsp);
	}

	public function __call($method, $args)
	{
		try {
			// if __call is called, the action was not found, therefore throw Exception
			throw new Zend_Controller_Action_Exception('Action not found');
		} catch (Exception $e) {
			// if another error occurs, stop here (don't use postDispatch()) ...
			$this->error = true;
			// ...and go to error controller
			$this->_forward('error','error','api',array ('error' => $e));
		}
	}

	public function indexAction()
	{
		// if index action is called, forward to proper page in the wiki
		$this->_redirector = $this->_helper->getHelper('Redirector');
		$this->_redirector->gotoUrl('http://wiki.cocktailberater.de/index.php?title=Cb-party-get');
	}

	public function testAction() {
		// set status for root element 'rsp' to 'ok'
		$this->rsp->setAttribute('status','ok');
	}
	
	public function postDispatch(){
		if(!$this->error){
			// save and display tree
			print $this->xml->saveXML();
		}
	}

}