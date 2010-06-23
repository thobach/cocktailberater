<?php

class SitemapOutputTest extends ControllerTestCase {


	public function testCaseProvider()	{
		$data = file_get_contents("http://cocktailberater.local:10088/sitemap/index/format/array");
		$data = unserialize($data);
		return $data;
	}

	/**
	 * @dataProvider testCaseProvider
	 */
	public function testContent($url) {
		$info = get_headers($url); // 'http://cocktailberater.local:10088'.
		$status_code = $info[0];
		$this->assertEquals($status_code,'HTTP/1.1 200 OK');
	}

}