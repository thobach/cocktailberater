<?php
class Wb_Controller_Plugin_Layout extends Zend_Controller_Plugin_Abstract {

	// display mobile page if 'mobile' or 'webos' is found in user agent
	// see http://en.wikipedia.org/wiki/List_of_user_agents_for_mobile_phones
	private $_agents = array(
        'mobile'		=> array('ipad'),
        'webos'			=> false,
		'symbian'		=> false,
		'iphone'		=> false,
		'windows ce'	=> false,
		'palm'			=> false,
		'midp'			=> false,
	);

	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)	{
		// set layout path for each module
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayoutPath(APPLICATION_PATH . '/modules/' .
		$request->getModuleName() . '/layouts');
		
		
		/**
		 * skip mobile detection
		 */
		// skip mobile browser detection if normal layout is wanted explicitly
		if($request->getParam('format') == 'html'){
			return;
		}
		
		/**
		 * mobile detection
		 */
		// get user agent
		$uAgent = $request->HTTP_USER_AGENT;
		// check if useragent contains 'mobile' or 'webos'
		foreach ($this->_agents as $agent => $negation) {
			if (stripos($uAgent, $agent) !== false) {
				// but not on mobile -> ipad
				if ($negation) {
					foreach ($negation as $neg) {
						// check if anything is excluded from 'mobile' or 'webos'
						// like a normal page for ipad, the use default
						if (stripos($uAgent, $neg) !== false) {
							$request->setParam('format','html');
							return;
						}
					}
				}
				// set mobile layout
				$request->setParam('format','mobile');
				return;
			}
		}
		
		// set default context		
		if($request->getParam('format') == null){
			$request->setParam('format','html');
			return;
		}
		
	}
}
