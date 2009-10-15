<?php

/**
 * Class responsible for everything regarding admin stuff
 */

class AdminController extends Zend_Controller_Action {
	
	function indexAction () {
		phpinfo();
		exit;
	}
	
}