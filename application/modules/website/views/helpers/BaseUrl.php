<?php

/**
 * Zend_View_Helper_BaseUrl returns the baseUrl
 */

class Zend_View_Helper_BaseUrl extends Zend_View_Helper_Abstract
{
	/**
	 * returns the baseUrl
	 *
	 * @return 	string 	baseUrl without trailing slash
	 */
	public function baseUrl() {
		return Zend_Controller_Front::getInstance()->getBaseUrl();
	}
}