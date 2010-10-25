<?php

class Controllers_SitemapTest extends ControllerTestCase
{

	/**
	 * Returns 'all' accessible pages of the website, uses sitemap as reference
	 * for 'all'
	 *
	 * Does not contain workflow of e.g. 'Partyhelferlein'.
	 *
	 */
	public function testCaseProvider()
	{
		$data = file_get_contents("http://cocktailberater.local:10088/sitemap/index/format/array");
		$data = unserialize($data);
		return $data;
	}

	/**
	 * Checks whether all pages are loaded within 2s and HTTP Status Code
	 * is 200
	 * 
	 * @todo covers for phpUnit is missing
	 *
	 * @dataProvider testCaseProvider 
	 */
	public function testPerformanceAndContent($url)
	{
		// Performance test was previously done with file_get_contents()
		// which only takes slightly longer relative to time needed for
		// get_headers, so it is good enough for now, in future it could be
		// replaced by curl or sth. like that

		// Performance and HTTP Status test at once
		$start = microtime(true);
		$info = get_headers('http://cocktailberater.local:10088'.$url);
		$end = microtime(true);
		if($end-$start > 2.0){
			$this->fail('http://cocktailberater.local:10088'.$url.' needed over 2s to load');
		}
		// HTTP Status code test
		$status_code = $info[0];
		$this->assertEquals($status_code,'HTTP/1.1 200 OK');
	}
}