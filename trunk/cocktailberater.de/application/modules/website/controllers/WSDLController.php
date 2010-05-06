<?php

class Website_WSDLController extends Zend_Controller_Action {

	public function preDispatch(){
		$this->getHelper('layout')->disableLayout();
		$this->getFrontController()->setParam('noViewRenderer', true);
		$this->getResponse()->setHeader('Content-Type','application/xml');
	}

	public function indexAction(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_WSDLController->indexAction',Zend_Log::DEBUG);

		if($this->_hasParam('wsdl')){
		$wsdl = new Zend_Soap_Wsdl(
			'cocktailberater',
			'http://cocktailberater.local:10088/website/wsdl?wsdl');

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
			'http://cocktailberater.local:10088/website/wsdl/#order');

		$wsdl->addSoapOperation($greetingOperation,
			'http://cocktailberater.local:10088/website/wsdl/#greeting');

		$service = $wsdl->addService('cbService',
			'cocktailberaterPortType','cocktailberaterSoapBinding',
			'http://cocktailberater.local:10088/website/wsdl/');

		$wsdl->addDocumentation($service,
			'place orders of cocktail recipes at a party');

		echo $wsdl->toXML();
		
		}
		else {
			$server = new 	Zend_Soap_Server('http://cocktailberater.local:10088/website/wsdl/?wsdl');
			$server->addFunction('greeting');
			$server->addFunction('order');
			$server->handle();
		}
	}
	
	public function clientAction(){
		$this->getFrontController()->setParam('noViewRenderer', true);
		$this->getResponse()->setHeader('Content-Type','text/html');
		/*$client = new Zend_Soap_Client('http://cocktailberater.local:10088/website/wsdl/wsdl?wsdl');
		
		var_dump($client->getFunctions());
		//exit;
		
		echo 'get: ';
		var_dump($client->getrecipe(1));
		echo 'Request: ';
		var_dump($client->getLastRequest());
		echo 'Response: ';
		var_dump($client->getLastResponse());
		
		echo 'index: ';
		var_dump($client->index());
		echo 'Request: ';
		var_dump($client->getLastRequest());
		echo 'Response: ';
		var_dump($client->getLastResponse());
		*/
		$client = new Zend_Soap_Client('http://cocktailberater.local:10088/website/wsdl/wsdl?wsdl');
		
		var_dump($client->getFunctions());
		exit;
		
		
		echo 'greet: ';
		var_dump($client->greeting('thomas'));
		echo 'Request: ';
		var_dump($client->getLastRequest());
		echo 'Response: ';
		var_dump($client->getLastResponse());
		exit;

		echo 'order: ';
		var_dump($client->order(1,1,2));
		echo 'Request: ';
		var_dump($client->getLastRequest());
		echo 'Response: ';
		var_dump($client->getLastResponse());

	}

	function wsdlAction(){
		$this->getResponse()->setHeader('Content-Type','application/xml');
		if($this->_hasParam('wsdl')){
			$autodiscover = new Zend_Soap_AutoDiscover();
			//$autodiscover->setClass('Recipe');
			$autodiscover->addFunction('order');
			$autodiscover->addFunction('greeting');
			$autodiscover->handle();
		} else {
			$server = new 	Zend_Soap_Server('http://cocktailberater.local:10088/website/wsdl/wsdl?wsdl');
			//$server->setClass('Recipe');
			$server->addFunction('order');
			$server->addFunction('greeting');
			$server->handle();
		}
	}
}

class Recipe {
	/**
	 * Get recipe information by id
	 * 
	 * @param int $id of the recipe
	 * @return string
	 */
	function getrecipe($id){
		return "$id";
	}
	/**
	 * Get all recipes
	 * 
	 * @return string index
	 */
	function index(){
		 return 'alle rezepte';
	}
}

function order($customer,$recipe,$party){
	return 'blub';
}

function greeting($name){
	return 'hello '.$name;
}
