<?php

/**
 *
 * @author thobach
 *
 */
class MobileSitemapController extends Zend_Controller_Action{

	/**
	 * Returns a Sitemap XML File for search engines
	 *
	 * @return String XML Sitemap
	 */
	public function indexAction() {
		require_once 'SitemapController.php';
		$sitemapController = new SitemapController($this->getRequest(),$this->getResponse());
		$sitemapController->indexAction();	
	}

}

