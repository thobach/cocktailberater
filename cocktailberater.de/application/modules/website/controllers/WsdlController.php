<?php

class Website_WsdlController extends Zend_Controller_Action {

	public function preDispatch(){
		$this->_helper->layout->disableLayout();
		$this->getFrontController()->setParam('noViewRenderer', true);
		$this->getResponse()->setHeader('Content-Type','application/xml');
		ini_set("soap.wsdl_cache_enabled", "0");
	}

	public function clientAction(){
		$this->getResponse()->setHeader('Content-Type','text/html');

		$client = new Zend_Soap_Client('http://'.$_SERVER['HTTP_HOST'].'/website/wsdl/?wsdl',array('cache_wsdl'=>false));

		var_dump($client->getFunctions());
		echo 'getRecipe: ';
		var_dump($client->getRecipe(1));
		echo 'Request: ';
		var_dump($client->getLastRequest());
		echo 'Response: ';
		var_dump($client->getLastResponse());
		
		echo 'searchByName: ';
		var_dump($client->searchByName());
		echo 'Request: ';
		var_dump($client->getLastRequest());
		echo 'Response: ';
		var_dump($client->getLastResponse());
		
		echo 'searchByName: ';
		var_dump($client->searchByName('Mai'));
		echo 'Request: ';
		var_dump($client->getLastRequest());
		echo 'Response: ';
		var_dump($client->getLastResponse());

	}

	function indexAction(){
		$this->getResponse()->setHeader('Content-Type','application/xml');
		if($this->_hasParam('wsdl')){
			$autodiscover = new Zend_Soap_AutoDiscover();
			$typeMap = array("Website_Model_RecipeInterface[]" => "Zend_Soap_Wsdl_Strategy_ArrayOfTypeComplex","Website_Model_RecipeInterface" => "Zend_Soap_Wsdl_Strategy_DefaultComplexType");
			$autodiscover->setBindingStyle(array('style'=>'document')); // default style was rpc, transport is soap over http
			$autodiscover->setComplexTypeStrategy(new Zend_Soap_Wsdl_Strategy_Composite($typeMap,"Zend_Soap_Wsdl_Strategy_AnyType"));
			$autodiscover->setClass('Website_Model_RecipeInterface');
			$autodiscover->handle();
		} else {
			$server = new 	Zend_Soap_Server('http://'.$_SERVER['HTTP_HOST'].'/website/wsdl/?wsdl');
			$server->setClass('Website_Model_Recipe');
			$server->handle();
		}
	}
}


