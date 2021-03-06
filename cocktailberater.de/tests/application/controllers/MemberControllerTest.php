<?php

class Controllers_MemberControllerTest extends ControllerTestCase
{

	private $id;

	/**
	 * @covers Website_MemberController::getAction
	 */
	public function testGetXmlAction() {
		$this->dispatch('/website/member/1?format=xml');
		$this->assertModule("website");
		$this->assertController("member");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathCount('/rsp/member',1);
		$this->assertContains('firstname="Thomas"',$this->response->outputBody());
	}

	/**
	 * @covers Website_MemberController::postAction
	 */
	public function testPostXmlAction() {
		// delete old test members
		$member = Website_Model_Member::getMemberByEmail('max@thobach.de');
		if($member){
			$member->delete();
		}

		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test1','firstname'=>'Max','lastname'=>'Mustermann'));
		$this->dispatch('/website/member/?format=xml');

		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		$res = $xml->xpath("//member");
		$this->id = $res[0]['@attributes']['id'];
		$this->assertModule("website");
		$this->assertController("member");
		$this->assertAction("get"); // redirected
		$this->assertResponseCode(201);
		$this->assertXpathCount('/rsp/member',1);
		$this->assertContains('email="max@thobach.de"',$this->response->outputBody());
	}

	/**
	 * @covers Website_MemberController::postAction
	 */
	public function testPostFirstNameMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test1','lastname'=>'Mustermann'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('First name missing!'));
	}

	/**
	 * @covers Website_MemberController::postAction
	 */
	public function testPostLastNameMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test1','firstname'=>'Max'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Last name missing!'));
	}

	/**
	 * @covers Website_MemberController::postAction
	 */
	public function testPostEmailMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('password'=>'test1','firstName'=>'Max','lastname'=>'Mustermann'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Email missing!'));
	}

	/**
	 * @covers Website_MemberController::postAction
	 */
	public function testPostPasswordMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','firstName'=>'Max','lastname'=>'Mustermann'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Password missing!'));
	}

	/**
	 * @covers Website_MemberController::postAction
	 */
	public function testPostExistingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de', 'password'=>'test1','firstname'=>'Max','lastname'=>'Mustermann'));
		$this->dispatch('/website/member/?format=xml');
		
		/*$this->getRequest()->setActionName('post')
		->setModuleName('website')->setControllerName('member')
		->setParams(array('format'=>'xml', 'email'=>'max@thobach.de', 'password'=>'test1','firstname'=>'Max','lastname'=>'Mustermann'));
		$controller = new Website_MemberController(
		$this->request,
		$this->response,
		$this->request->getParams()
		);
		$controller->postAction();*/

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Email already exists!'));
	}

	public function testPutChangePasswordXmlAction(){
		// rais exeption
	}

	/**
	 * @covers Website_MemberController::deleteAction
	 */
	public function testDeleteWrongPasswordXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('email'=>'max@thobach.de','password'=>'test1234'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertModule("website");
		$this->assertController("member");
		$this->assertAction("delete");
		$this->assertResponseCode(412);
		$this->assertXpathCount('/rsp[@status="error"]',1);
		$this->assertXpathCount('/rsp/*',0);
	}

	/**
	 * @covers Website_MemberController::deleteAction
	 */
	public function testDeleteEmailMissingXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('password'=>'test1'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Email missing!'));
	}

	/**
	 * @covers Website_MemberController::deleteAction
	 */
	public function testDeletePasswordMissingXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('email'=>'max@thobach.de'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Password missing!'));
	}

	/**
	 * @covers Website_MemberController::deleteAction
	 */
	public function testDeleteXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('email'=>'max@thobach.de','password'=>'test1'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertModule("website");
		$this->assertController("member");
		$this->assertAction("delete");
		$this->assertResponseCode(200);
		$this->assertXpathCount('/rsp[@status="ok"]',1);
		$this->assertXpathCount('/rsp/*',0);
	}

	/**
	 * @covers Website_MemberController::deleteAction
	 */
	public function testDeleteFailedXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('email'=>'max@thobach.de','password'=>'test1'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('User does not exist!'));
	}

	/**
	 * @covers Website_MemberController::indexAction
	 */
	public function testIndexAsHtmlAction() {
		$this->dispatch('/website/member/');

		$this->assertTrue($this->getResponse()->hasExceptionOfType('Zend_Controller_Action_Exception'));
	}

	/**
	 * @covers Website_MemberController::indexAction
	 */
	public function testIndexAsXmlAction() {
		$this->getRequest()->setParam('format','xml');
		$this->dispatch('/website/member/');

		$this->assertTrue($this->getResponse()->hasExceptionOfType('Zend_Controller_Action_Exception'));
	}

	/**
	 * @covers Website_MemberController::indexAction
	 */
	public function testIndexAsJsonAction() {
		$this->getRequest()->setParam('format','json');
		$this->dispatch('/website/member/');

		$this->assertTrue($this->getResponse()->hasExceptionOfType('Zend_Controller_Action_Exception'));
	}

	/**
	 * @covers Website_MemberController::indexAction
	 */
	public function testIndexAsRssAction() {
		$this->getRequest()->setParam('format','rss');
		$this->dispatch('/website/member/');

		$this->assertTrue($this->getResponse()->hasExceptionOfType('Zend_Controller_Action_Exception'));
	}

	/**
	 * @covers Website_MemberController::getAction
	 */
	public function testGetHtmlAction() {
		$this->dispatch('/website/member/1');

		$this->assertModule("website");
		$this->assertController("member");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', "Thomas Bachmann");
	}
}
