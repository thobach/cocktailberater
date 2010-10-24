<?php

class Controllers_SitemapPerformanceTest extends PHPUnit_Extensions_PerformanceTestCase {


	public function testCaseProvider()	{
		$data = file_get_contents("http://cocktailberater.local:10088/sitemap/index/format/array");
		$data = unserialize($data);
		return $data;
	}

	/**
	 * @dataProvider testCaseProvider
	 */
	public function testPerformance($url) {
		$this->setMaxRunningTime(2);
		file_get_contents ('http://cocktailberater.local:10088'.$url);
	}
}