<?php
class Cb_Controller_Plugin_Layout extends Zend_Controller_Plugin_Abstract {
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)	{
		if($request->getModuleName()!='default'){
			$layout = Zend_Layout::getMvcInstance();
			$layout->setLayoutPath(APPLICATION_PATH . '/modules/' .
			$request->getModuleName() . '/layouts');
		}
	}
}
