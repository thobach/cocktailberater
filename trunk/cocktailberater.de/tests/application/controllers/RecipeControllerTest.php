<?php

class RecipeControllerTest extends ControllerTestCase
{

	public function testIndexAction() {
		$this->dispatch('/website/recipe/');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertXpathCountMin('//*[@id="ergebnisse"]/div',10);
	}

	public function testIndexAsAjaxNameAction() {
		$this->getRequest()->setActionName('index')
		->setModuleName('website')->setControllerName('recipe')
		->setParams(array('format'=>'ajax','search_type'=>'name','search'=>'S'));
		$controller = new Website_RecipeController(
		$this->request,
		$this->response,
		$this->request->getParams()
		);
		$controller->indexAction();

		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Sex on the Beach',$this->response->outputBody());
	}

	public function testIndexAsAjaxIngredientAction() {
		$this->getRequest()->setActionName('index')
		->setModuleName('website')->setControllerName('recipe')
		->setParams(array('format'=>'ajax','search_type'=>'ingredient','search'=>'S'));
		$controller = new Website_RecipeController(
		$this->request,
		$this->response,
		$this->request->getParams()
		);
		$controller->indexAction();

		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Sahne',$this->response->outputBody());
	}
	public function testIndexAsAjaxTagAction() {
		$this->getRequest()->setActionName('index')
		->setModuleName('website')->setControllerName('recipe')
		->setParams(array('format'=>'ajax','search_type'=>'tag','search'=>'K'));
		$controller = new Website_RecipeController(
		$this->request,
		$this->response,
		$this->request->getParams()
		);
		$controller->indexAction();

		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Klassiker',$this->response->outputBody());
	}

	public function testIndexAsXmlAction() {
		$log = Zend_Registry::get('logger');
		$log->log('testIndexAsXmlAction',Zend_Log::DEBUG);
		
		$this->getRequest()->setParam('format','xml');
		$this->dispatch('/website/recipe/');

		$this->assertModule("website");
		$this->assertAction("index");
		$this->assertController("recipe");
		$this->assertResponseCode(200);
		$this->assertXpathCountMin('/rsp/recipes/recipe',10);
		$this->assertContains('name="Sex on the Beach"',$this->response->outputBody());
	}

	public function testIndexAsJsonAction() {
		$log = Zend_Registry::get('logger');
		$log->log('testIndexAsJsonAction',Zend_Log::DEBUG);
		/*
		 * uni test fails at post dispatch when all are executed:
		 	2010-04-06T00:54:27+02:00 DEBUG (7): testIndexAsJsonAction
			2010-04-06T00:54:27+02:00 DEBUG (7): Wb_Controller_RestController->init
			2010-04-06T00:54:27+02:00 DEBUG (7): Website_RecipeController->indexAction
			2010-04-06T00:54:27+02:00 DEBUG (7): Website_RecipeController->indexAction -> list all
			2010-04-06T00:54:27+02:00 DEBUG (7): Wb_Controller_RestController->postDispatch
		 */
		$this->getResponse()->clearAllHeaders()->clearBody();
		$this->getRequest()->clearParams();
		$this->getRequest()->setParam('format','json');
		$log->log('setParam',Zend_Log::DEBUG);
		$this->dispatch('/website/recipe/?format=json');
		$log->log('dispatch',Zend_Log::DEBUG);
		
		$this->assertModule("website");
		$log->log('assert1',Zend_Log::DEBUG);
		$this->assertAction("index");
		$this->assertController("recipe");
		$this->assertResponseCode(200);
		$log->log('assert2',Zend_Log::DEBUG);
		$this->assertContains('"name":"Sex on the Beach"',$this->response->outputBody());
		$log->log('assert3',Zend_Log::DEBUG);
	}

	public function testIndexAsRssAction() {
		$log = Zend_Registry::get('logger');
		$log->log('testIndexAsRssAction',Zend_Log::DEBUG);
		
		$this->getRequest()->setParam('format','rss');
		$this->dispatch('/website/recipe/');
		$this->assertModule("website");
		$this->assertAction("index");
		$this->assertController("recipe");
		$this->assertResponseCode(200);
		$this->assertContains('<title><![CDATA[Sex on the Beach]]></title>',$this->response->outputBody());
	}


	public function testIndexAlcoholicAction() {
		$this->dispatch('/website/recipe/index/search_type/alcoholic');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Sex on the Beach',$this->response->outputBody());

	}

	public function testIndexNonAlcoholicAction() {
		$this->dispatch('/website/recipe/index/search_type/non-alcoholic');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Red Risk',$this->response->outputBody());
	}

	public function testIndexTop10Action() {
		$this->dispatch('/website/recipe/index/search_type/top10');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('#10',$this->response->outputBody());
	}

	public function testIndexDifficultyAction() {
		$this->dispatch('/website/recipe/index/search_type/difficulty/search/beginner');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Tequila Sunrise',$this->response->outputBody());
	}

	public function testIndexIngredientAction() {
		$this->dispatch('/website/recipe/index/search_type/ingredient/search/Vodka');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Sex on the Beach',$this->response->outputBody());
	}

	public function testIndexImageAction() {
		$this->dispatch('/website/recipe/index/search_type/image');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Tequila Sunrise',$this->response->outputBody());
	}

	public function testIndexTagAction() {
		$this->dispatch('/website/recipe/index/search_type/tag/search/Klassiker');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Sex on the Beach',$this->response->outputBody());
	}

	public function testIndexNameAction() {
		$this->dispatch('/website/recipe/index/search_type/name/search/sex');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Sex on the Beach',$this->response->outputBody());
	}


	public function testGetWithIdAction() {
		$this->dispatch('/website/recipe/1');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('/html/body/div[2]/div[2]/div/div[2]/div[2]/div[2]', "Mojito");
	}
	
	public function testGetWithNameAction() {
		$this->dispatch('/website/recipe/Mai_Tai');
		$this->assertModule("website");
		$this->assertController("recipe");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('/html/body/div[2]/div[2]/div/div[2]/div[2]/div[2]', "Mai Tai");
	}

	public function testGetWithWrongIdAction() {
		$this->dispatch('/website/recipe/0');
		
				$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Incorrect_Id'));
	}
}
