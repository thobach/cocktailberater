<?php
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
	public $application;

	public function setUp(){
		$this->application = new Zend_Application(APPLICATION_ENV,APPLICATION_PATH . '/modules/default/config/application.ini');
		$this->bootstrap = array($this, 'appBootstrap');
		parent::setUp();
	}

	public function appBootstrap(){

		$this->application->bootstrap();
	}

	public function dispatch($url = null)
	{
		$log = Zend_Registry::get('logger');
		$log->log('ControllerTestCase->dispatch',Zend_Log::DEBUG);
		// redirector should not exit
		$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
		$redirector->setExit(false);
		// json helper should not exit
		$json = Zend_Controller_Action_HelperBroker::getStaticHelper('json');
		$json->suppressExit = true;
		$dojo = Zend_Controller_Action_HelperBroker::getStaticHelper('autoCompleteDojo');
		$dojo->suppressExit = true;
		$request    = $this->getRequest();
		if (null !== $url) {
			$request->setRequestUri($url);
		}
		$request->setPathInfo(null);
		
		// why this?
		$controller = $this->getFrontController();
		
		$this->frontController->setParam('noErrorHandler', true);
        $this->frontController->throwExceptions(false);
        $this->frontController->returnResponse(true);
		
		$this->frontController->setRequest($request);
		$this->frontController->setResponse($this->getResponse());
		
		if ($this->bootstrap instanceof Zend_Application) {
			$this->bootstrap->run();
		} else {
			$log->log('ControllerTestCase->dispatch pre dispatch',Zend_Log::DEBUG);
			$this->frontController->dispatch();
			$log->log('ControllerTestCase->dispatch post dispatch',Zend_Log::DEBUG);
		}
	}

}