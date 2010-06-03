<?php

class ErrorController extends Zend_Controller_Action {
	
	public function init(){
		$contextSwitch = $this->_helper->contextSwitch();
		$contextSwitch->setAutoJsonSerialization(false);
		if(!$contextSwitch->hasContext('rss')){
			$contextSwitch->removeContext('rss');
			$contextSwitch->addContext('rss',array(
				'suffix'	=> 'rss',
				'headers'	=> array('Content-Type' => 'application/rss+xml')));
		}
		if(!$contextSwitch->hasContext('atom')){
			$contextSwitch->addContext('atom',array(
				'suffix'	=> 'atom',
				'headers'	=> array('Content-Type' => 'application/atom+xml')));
		}
		if(!$contextSwitch->hasContext('pdf')){
			$contextSwitch->addContext('pdf',array(
				'suffix'	=> 'pdf',
				'headers'	=> array('Content-Type' => 'application/pdf')));
		}
		if(!$contextSwitch->hasContext('rdf')){
			$contextSwitch->addContext('rdf',array(
				'suffix'	=> 'rdf',
				'headers'	=> array('Content-Type' => 'application/rdf+xml')));
		}
		if(!$contextSwitch->hasContext('ajax')){
			$contextSwitch->addContext('ajax',array(
				'suffix'	=> 'ajax',
				'headers'	=> array('Content-Type' => 'text/html')));
		}
		if(!$contextSwitch->hasContext('mobile')){
			$contextSwitch->addContext('mobile',array(
				'suffix'	=> 'mobile',
				'headers'	=> array('Content-Type' => 'text/html')));
		}
		$contextSwitch->addActionContext('error', true);
		try{
			$contextSwitch->initContext();
		} catch (Zend_Controller_Action_Exception $e){
			// ignore and use default context (html)
		}	
	}

	public function errorAction() {
		$log = Zend_Registry::get('logger');
		$log->log('ErrorController->errorAction',Zend_Log::CRIT);

		$errors = $this->_getParam('error_handler');

		$log->log('Request: '.print_r($errors->request,true),Zend_Log::CRIT);
		$log->log('Exception: '.print_r($errors->exception,true),Zend_Log::CRIT);

		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Seite nicht gefunden, sorry!';
				break;
			default:
				if($errors->exception instanceof Website_Model_MemberException &&  $errors->exception->getMessage() == Website_Model_MemberException::INVALID_CREDENTIALS){
					// 401 error -- not authorized
					$this->getResponse()->setHttpResponseCode(401);
					$this->view->message = 'Zugangsdaten falsch, sorry!';
				} else {
					// application error
					$this->getResponse()->setHttpResponseCode(500);
					$this->view->message = 'Fehler unsererseits, sorry!';
				}
				break;
		}

		$this->view->exception 	= $errors->exception;
		$this->view->request   	= $errors->request;
		$this->view->format 	= $this->_getParam('format');
	}


}

