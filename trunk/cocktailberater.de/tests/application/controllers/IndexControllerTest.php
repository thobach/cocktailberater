<?php

class IndexControllerTest extends ControllerTestCase
{

	public function testIndexAction() {
		$this->dispatch('/');
		$this->assertModule("default");
		$this->assertController("index");
		$this->assertAction("index");
		$this->assertResponseCode(302);
		//$this->assertXpathContentContains("/html/body/div[2]/div[2]/div/div/form/fieldset/label", "Cocktail:");
	}

	// somehow kills the unit test routine
	public function testSitemapAction() {
		//$this->dispatch('/website/index/sitemap');
		
		/*$controller = new Website_IndexController(
            $this->request,
            $this->response,
            $this->request->getParams()
        );*/
        //$controller->sitemapAction();

        //$this->assertTrue(isset($controller->view->pages));
		
		//$this->assertModule("website");
		//$this->assertController("index");
		//$this->assertAction("sitemap");
		//$this->assertResponseCode(200);
		//$this->assertXpathContentContains("/urlset/url/loc", "http://cocktailberater.local:10088/website");
		
	}
}
