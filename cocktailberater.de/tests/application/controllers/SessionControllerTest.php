<?php

class Controllers_SessionControllerTest extends ControllerTestCase
{

	public static $hashCode;


	public function testIndexAsHtmlAction() {
		$this->dispatch('/website/session/');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Seite wurde nicht gefunden.'));
	}

	/**
	 * Create new Session (xml)
	 * @covers Website_SessionController::postAction
	 */
	public function testPostXmlAction() {
		$log = Zend_Registry::get('logger');
		$log->log('testPostXmlAction',Zend_Log::DEBUG);
		// check that member exists
		$member = Website_Model_Member::getMemberByEmail('max@thobach.de');
		if(!$member){
			$member = new Website_Model_Member();
			$member->email='max@thobach.de';
			$member->firstname = 'Max';
			$member->lastname = 'Mustermann';
			$member->setPassword('test1');
			$member->save();
		}

		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');

		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		$res = $xml->xpath("member");
		$xmlAttr = $res[0]->attributes();
		self::$hashCode = print_r($xmlAttr['hashCode']."",true);
		$this->assertModule("website");
		$this->assertController("member");
		$this->assertAction("get"); // redirected
		$this->assertResponseCode(201); // created
		$this->assertXpathCount('/rsp/member',1);
		$this->assertContains('email="max@thobach.de"',$this->response->outputBody());
	}

	/**
	 * @covers Website_SessionController::postAction
	 */
	public function testPostEmailMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Email missing!'));
	}

	/**
	 * @covers Website_SessionController::postAction
	 */
	public function testPostPasswordMissingXmlAction() {
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de'));
		$this->dispatch('/website/session/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Password missing!'));
	}

	/**
	 * @covers Website_SessionController::deleteAction
	 */
	public function testDeleteInvalidCredentialsXmlAction(){
		$this->request->setMethod('DELETE')->setPost(array('email'=>'max@thobach.de','hashCode'=>'invalid'));
		$this->dispatch('/website/session/?format=xml');
		$this->assertModule("website");
		$this->assertController("session");
		$this->assertAction("delete");
		$this->assertResponseCode(401); // unauthorized
		$this->assertXpathCount('/rsp[@status="error"]',1);
	}

	/**
	 * @covers Website_SessionController::deleteAction
	 */
	public function testDeleteEmailMissingXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Email missing!'));
	}

	/**
	 * @covers Website_SessionController::deleteAction
	 */
	public function testDeleteHashCodeMissingXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('email'=>'max@thobach.de'));
		$this->dispatch('/website/session/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	/**
	 * @covers Website_SessionController::deleteAction
	 */
	public function testDeleteXmlAction(){
		$this->request->setMethod('DELETE')->setPost(array('email'=>'max@thobach.de','hashCode'=>self::$hashCode));
		$this->dispatch('/website/session/?format=xml');
		$this->assertModule("website");
		$this->assertController("session");
		$this->assertAction("delete");
		$this->assertResponseCode(200);
		$this->assertXpathCount('/rsp[@status="ok"]',1);
		$this->assertXpathCount('/rsp/*',0);
	}

	/**
	 * @covers Website_SessionController::postAction
	 */
	public function testPostInvalidCredentialsXmlAction() {
		// check that member exists
		$member = Website_Model_Member::getMemberByEmail('max@thobach.de');
		if(!$member){
			$member = new Website_Model_Member();
			$member->email='max@thobach.de';
			$member->firstname = 'Max';
			$member->lastname = 'Mustermann';
			$member->setPassword('test1');
			$member->save();
		}

		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test'));
		$this->dispatch('/website/session/?format=xml');

		$this->assertModule("website");
		$this->assertController("session");
		$this->assertAction("post");
		$this->assertResponseCode(401); // unauthorized
		$this->assertXpathCount('/rsp[@status="error"]',1);
	}

}
