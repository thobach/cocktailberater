<?php

class MemberControllerTest extends ControllerTestCase
{

	private $id;

	public function testGetXmlAction() {
		$this->dispatch('/website/member/1?format=xml');
		$this->assertModule("website");
		$this->assertController("member");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathCount('/rsp/member',1);
		$this->assertContains('firstname="Thomas"',$this->response->outputBody());
	}

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

	public function testPostFirstNameMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test1','lastname'=>'Mustermann'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('First name missing!'));
	}

	public function testPostLastNameMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test1','firstname'=>'Max'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Last name missing!'));
	}

	public function testPostEmailMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('password'=>'test1','firstName'=>'Max','lastname'=>'Mustermann'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Email missing!'));
	}

	public function testPostPasswordMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','firstName'=>'Max','lastname'=>'Mustermann'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Password missing!'));
	}

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

	public function testDeleteEmailMissingXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('password'=>'test1'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Email missing!'));
	}

	public function testDeletePasswordMissingXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('email'=>'max@thobach.de'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Password missing!'));
	}

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

	public function testDeleteFailedXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('email'=>'max@thobach.de','password'=>'test1'));
		$this->dispatch('/website/member/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('User does not exist!'));
	}

	public function testIndexAsHtmlAction() {
		$this->dispatch('/website/member/');

		$this->assertTrue($this->getResponse()->hasExceptionOfType('Zend_Controller_Action_Exception'));
	}

	public function testIndexAsXmlAction() {
		$this->getRequest()->setParam('format','xml');
		$this->dispatch('/website/member/');

		$this->assertTrue($this->getResponse()->hasExceptionOfType('Zend_Controller_Action_Exception'));
	}

	public function testIndexAsJsonAction() {
		$this->getRequest()->setParam('format','json');
		$this->dispatch('/website/member/');

		$this->assertTrue($this->getResponse()->hasExceptionOfType('Zend_Controller_Action_Exception'));
	}

	public function testIndexAsRssAction() {
		$this->getRequest()->setParam('format','rss');
		$this->dispatch('/website/member/');

		$this->assertTrue($this->getResponse()->hasExceptionOfType('Zend_Controller_Action_Exception'));
	}

	public function testGetHtmlAction() {
		$this->dispatch('/website/member/1');

		$this->assertModule("website");
		$this->assertController("member");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('//h2', "Thomas Bachmann");
	}
}
