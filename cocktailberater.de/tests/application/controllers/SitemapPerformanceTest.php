<?php

class Controllers_SitemapPerformanceTest extends ControllerTestCase {


	/**
	 * Returns 'all' accessible pages of the website, uses sitemap as reference 
	 * for 'all'
	 * 
	 * Does not contain workflow of 'Partyhelferlein'.
	 * 
	 */
	public function testCaseProvider()	{
		$data = file_get_contents("http://cocktailberater.local:10088/sitemap/index/format/array");
		$data = unserialize($data);
		return $data;
	}

	/**
	 * Checks whether all pages are 
	 * 
	 * @dataProvider testCaseProvider
	 */
	public function testPerformance($url) {
		$start = microtime();
		file_get_contents ('http://cocktailberater.local:10088'.$url);
		$end = microtime();
		if($end-$start>=2000){
			$this->fail('http://cocktailberater.local:10088'.$url.' needed over 2s to load');
		}
	}
}