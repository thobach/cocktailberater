<?php
/**
 * Context sensitive Controller
 *
 * @author thobach
 *
 */
abstract class Wb_Controller_RestController extends Zend_Rest_Controller {

	public function init() {
		$log = Zend_Registry::get('logger');
		$log->log('Wb_Controller_RestController->init',Zend_Log::DEBUG);
		
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
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
		$contextSwitch->addActionContext('index', true);
		$contextSwitch->addActionContext('get', true);
		$contextSwitch->addActionContext('post', true);
		$contextSwitch->addActionContext('put', true);
		$contextSwitch->addActionContext('delete', true);
		$contextSwitch->initContext();
	}

	public function postDispatch(){
		$log = Zend_Registry::get('logger');
		$log->log('Wb_Controller_RestController->postDispatch',Zend_Log::DEBUG);
		
		$this->view->format = $this->_getParam('format');
		$log->log('postDispatched',Zend_Log::DEBUG);
	}

	public function indexAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Wb_Controller_RestController->indexAction',Zend_Log::DEBUG);
	}

	public function getAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Wb_Controller_RestController->getAction',Zend_Log::DEBUG);
		
		$this->_forward('index');
	}

	public function postAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Wb_Controller_RestController->postAction',Zend_Log::DEBUG);
		
		$this->_forward('index');
	}

	public function putAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Wb_Controller_RestController->putAction',Zend_Log::DEBUG);
		
		$this->_forward('index');
	}

	public function deleteAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Wb_Controller_RestController->deleteAction',Zend_Log::DEBUG);
		
		$this->_forward('index');
	}
}