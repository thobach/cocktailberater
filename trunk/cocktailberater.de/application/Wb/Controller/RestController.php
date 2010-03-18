<?php
/**
 * Context sensitive Controller
 *
 * @author thobach
 *
 */
abstract class Wb_Controller_RestController extends Zend_Rest_Controller {
	
	public function init() {
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
		if(!$contextSwitch->hasContext('ajax')){
			$contextSwitch->addContext('ajax',array(
				'suffix'	=> 'ajax',
				'headers'	=> array('Content-Type' => 'text/html')));
		}
		$contextSwitch->addActionContext('index', true)->initContext();
		$contextSwitch->addActionContext('get', true)->initContext();
		$contextSwitch->addActionContext('post', true)->initContext();
		$contextSwitch->addActionContext('put', true)->initContext();
		$contextSwitch->addActionContext('delete', true)->initContext();
	}

	public function postDispatch(){
		$this->view->format = $this->_getParam('format');
	}

	public function indexAction() {

	}

	public function getAction() {
		$this->_forward('index');
	}

	public function postAction() {
		$this->_forward('index');
	}

	public function putAction() {
		$this->_forward('index');
	}

	public function deleteAction() {
		$this->_forward('index');
	}
}