<?php

class Controllers_SitemapOutputTest extends ControllerTestCase {


	public function testCaseProvider()	{
		$data = file_get_contents("http://cocktailberater.local:10088/sitemap/index/format/array");
		$data = unserialize($data);
		return $data;
	}

	/**
	 * @dataProvider testCaseProvider
	 */
	public function testContent($url) {
		$log = Zend_Registry::get('logger');
		$log->log('Controllers_SitemapOutputTest->testContent',Zend_Log::DEBUG);
		
		$log->log('Controllers_SitemapOutputTest->testContent http://cocktailberater.local:10088'.$url,Zend_Log::DEBUG);
		$info = get_headers('http://cocktailberater.local:10088'.$url);
		$status_code = $info[0];
		$this->assertEquals($status_code,'HTTP/1.1 200 OK');
		$log->log('Controllers_SitemapOutputTest->testContent done',Zend_Log::DEBUG);
	}

}