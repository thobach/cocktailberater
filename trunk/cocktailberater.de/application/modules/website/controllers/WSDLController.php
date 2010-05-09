<?php

class Website_WSDLController extends Zend_Controller_Action {

	public function preDispatch(){
		$this->getHelper('layout')->disableLayout();
		$this->getFrontController()->setParam('noViewRenderer', true);
		$this->getResponse()->setHeader('Content-Type','application/xml');
		ini_set("soap.wsdl_cache_enabled", "0");
	}

	public function indexAction(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_WSDLController->indexAction',Zend_Log::DEBUG);

		if($this->_hasParam('wsdl')){
		$wsdl = new Zend_Soap_Wsdl(
			'cocktailberater',
			'http://'.$_SERVER['HTTP_HOST'].'/website/wsdl?wsdl');

		$inputOrder = $wsdl->addMessage(
			'orderInput',
		array(
				'customer'=>'xsd:int',
				'recipe'=>'xsd:int',
				'party'=>'xsd:int'));
			
		$inputGreeting = $wsdl->addMessage(
			'greetingInput',
		array('namea'=>'xsd:string'));
			
		$outputOrder = $wsdl->addMessage(
			'orderOutput',
		array('order'=>'xsd:int'));
			
		$outputGreeting = $wsdl->addMessage(
			'greetingOutput',
		array('greeting'=>'xsd:string'));

		$portType = $wsdl->addPortType('cocktailberaterPortType');

		$wsdl->addPortOperation($portType,'order','orderInput','orderOutput');
		$wsdl->addPortOperation($portType,'greeting','greetingInput','greetingOutput');

		$binding = $wsdl->addBinding('cocktailberaterSoapBinding','cocktailberaterPortType');

		$orderOperation = $wsdl->addBindingOperation($binding,'order',
		array('use'=>'literal'),array('use'=>'literal'));
			
		$greetingOperation = $wsdl->addBindingOperation($binding,'greeting',
		array('use'=>'literal'),array('use'=>'literal'));
			
		$wsdl->addSoapBinding($binding);

		$wsdl->addSoapOperation($orderOperation,
			'http://'.$_SERVER['HTTP_HOST'].'/website/wsdl/#order');

		$wsdl->addSoapOperation($greetingOperation,
			'http://'.$_SERVER['HTTP_HOST'].'/website/wsdl/#greeting');

		$service = $wsdl->addService('cbService',
			'cocktailberaterPortType','cocktailberaterSoapBinding',
			'http://'.$_SERVER['HTTP_HOST'].'/website/wsdl/');

		$wsdl->addDocumentation($service,
			'place orders of cocktail recipes at a party');

		echo $wsdl->toXML();
		
		}
		else {
			$server = new 	Zend_Soap_Server('http://'.$_SERVER['HTTP_HOST'].'/website/wsdl/?wsdl');
			$server->addFunction('greeting');
			$server->addFunction('order');
			$server->handle();
		}
	}
	
	public function clientAction(){
		$this->getFrontController()->setParam('noViewRenderer', true);
		$this->getResponse()->setHeader('Content-Type','text/html');

		$client = new Zend_Soap_Client('http://'.$_SERVER['HTTP_HOST'].'/website/wsdl/wsdl?wsdl',array('cache_wsdl'=>false));

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

	function wsdlAction(){
		$this->getResponse()->setHeader('Content-Type','application/xml');
		if($this->_hasParam('wsdl')){
			$autodiscover = new Zend_Soap_AutoDiscover();
			$typeMap = array("Website_Model_RecipeInterface[]" => "Zend_Soap_Wsdl_Strategy_ArrayOfTypeComplex","Website_Model_RecipeInterface" => "Zend_Soap_Wsdl_Strategy_DefaultComplexType");
			$autodiscover->setComplexTypeStrategy(new Zend_Soap_Wsdl_Strategy_Composite($typeMap,"Zend_Soap_Wsdl_Strategy_AnyType"));
			$autodiscover->setClass('Website_Model_RecipeInterface');
			$autodiscover->handle();
		} else {
			$server = new 	Zend_Soap_Server('http://'.$_SERVER['HTTP_HOST'].'/website/wsdl/wsdl?wsdl');
			$server->setClass('Website_Model_Recipe');
			$server->handle();
		}
	}
}


