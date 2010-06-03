<?php
class Wb_Controller_Plugin_Layout extends Zend_Controller_Plugin_Abstract {

	// display mobile page if 'mobile' or 'webos' is found in user agent
	private $_agents = array(
        'mobile'	=> array('ipad'),
        'webos'		=> false
	);

	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)	{
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayoutPath(APPLICATION_PATH . '/modules/' .
		$request->getModuleName() . '/layouts');
		// get user agent
		$uAgent = $request->HTTP_USER_AGENT;
		// check if useragent contains 'mobile' or 'webos' 
		foreach ($this->_agents as $agent => $negation) {
			if (stripos($uAgent, $agent) !== false) {
				if ($negation) {
					foreach ($negation as $neg) {
						// check if anything is excluded from 'mobile' or 'webos'
						// like a special page for iphone/ipad
						if (stripos($uAgent, $neg) !== false) {
							return;
						}
					}
				}
				// set mobile layout
				Zend_Layout::getMvcInstance()->setLayout('mobilelayout');
				return;
			}
		}
	}
}
