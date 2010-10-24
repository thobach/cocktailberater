<?php

class Controllers_SitemapPerformanceTest extends ControllerTestCase {


	public function testCaseProvider()	{
		$data = file_get_contents("http://cocktailberater.local:10088/sitemap/index/format/array");
		$data = unserialize($data);
		return $data;
	}

	/**
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