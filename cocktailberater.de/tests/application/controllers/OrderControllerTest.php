<?php

class Controllers_OrderControllerTest extends ControllerTestCase
{

	public static $orderId;
	public static $hashCode;
	public static $memberId;

	public function setUp(){
		parent::setUp();
		
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->setUp',Zend_Log::DEBUG);

		// get valid session
		$member = Website_Model_Member::getMemberByEmail('max@thobach.de');
		// create new member
		if(!$member){
			$log->log('OrderControllerTest->setUp create new member',Zend_Log::DEBUG);
			$member = new Website_Model_Member();
			$member->email='max@thobach.de';
			$member->setPassword('test1');
			$member->firstname='Max';
			$member->lastname='Mustermann';
			$member->save();
		}
		
		$log->log('OrderControllerTest->setUp create guest session',Zend_Log::DEBUG);
		// create session for guest and store hashCode and member id
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');
		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		if($xml){
			$res = $xml->xpath("//member");
			$xmlAttr = $res[0]->attributes();
			self::$hashCode = print_r($xmlAttr['hashCode']."",true);
			self::$memberId = print_r($xmlAttr['id']."",true);
		} else {
			$log->log('OrderControllerTest->setUp guest session could not be created',Zend_Log::DEBUG);
			$this->fail('guest session could not be created');
		}
		
		// reset to create new request in same method
		$this->reset();
		parent::setUp();
		
		$log->log('OrderControllerTest->setUp create host session',Zend_Log::DEBUG);
		// create session for host and store hashCode and member id
		$this->request->setMethod('POST')->setPost(array('email'=>'thobach@web.de','password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');
		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		if($xml){
			$res = $xml->xpath("//member");
			$xmlAttr = $res[0]->attributes();
			$hostHashCode = print_r($xmlAttr['hashCode']."",true);
			$hostId = print_r($xmlAttr['id']."",true);
		} else {
			$log->log('OrderControllerTest->setUp host session could not be created',Zend_Log::DEBUG);
			$this->fail('host session could not be created');
		}
		
		// reset to create new request in same method
		$this->reset();
		parent::setUp();
		
		$log->log('OrderControllerTest->setUp add guest to party',Zend_Log::DEBUG);
		// add member as guest
		$this->request->setMethod('POST')->setPost(
			array('hashCode'=>$hostHashCode,
			'member'=>$hostId,
			'party'=>1,
			'guest'=>self::$memberId));
		$this->dispatch('/website/guest/?format=xml');

		// reset to create new request in same method
		$this->reset();
		parent::setUp();
		
		$log->log('OrderControllerTest->setUp exiting',Zend_Log::DEBUG);
	}

	/**
	 * @covers Website_OrderController::postAction
	 */
	public function testPostAsXmlAction() {
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testPostAsXmlAction',Zend_Log::DEBUG);
		
		$orderData = array(	'hashCode'=>self::$hashCode,
					'recipe'=>'1',
					'guest'=>self::$memberId,
					'member'=>self::$memberId,
					'party'=>'1');
		$this->request->setMethod('POST')->setPost($orderData);
		$this->dispatch('/website/order/?format=xml');
		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		if($xml){
			$res = $xml->xpath("//order");
			$xmlAttr = $res[0]->attributes();
			self::$orderId = print_r($xmlAttr['id']."",true);
		} else {
			$this->fail('xml could not be loaded');
		}
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("get"); // redirect
		$this->assertResponseCode(201); // created
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertContains('recipeName="Mojito"',$this->response->outputBody());
	}

	/**
	 * @covers Website_OrderController::indexAction
	 */
	public function testIndexOfPartyAsHtmlAction() {
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testIndexOfPartyAsHtmlAction',Zend_Log::DEBUG);

		$this->dispatch('/website/order/party/1/member/'.self::$memberId.'/hashCode/'.self::$hashCode);

		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Mojito für Max Mustermann',$this->response->outputBody());
	}

	/**
	 * @covers Website_OrderController::indexAction
	 */
	public function testIndexAsHtmlAction() {
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testIndexAsHtmlAction',Zend_Log::DEBUG);
		
		$this->dispatch('/website/order/member/'.self::$memberId.'/hashCode/'.self::$hashCode);
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Party missing!'));
	}

	/**
	 * @covers Website_OrderController::indexAction
	 */
	public function testIndexAsXmlAction() {
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testIndexAsXmlAction',Zend_Log::DEBUG);
		
		$this->dispatch('/website/order/party/1/member/'.self::$memberId.'?format=xml');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	/**
	 * @covers Website_OrderController::indexAction
	 */
	public function testIndexAsRssAction() {
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testIndexAsRssAction',Zend_Log::DEBUG);

		$this->dispatch('/website/order/party/1/hashCode/'.self::$hashCode.'?format=rss');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Member missing!'));
	}

	/**
	 * @covers Website_OrderController::indexAction
	 */
	public function testIndexAsAtomAction() {
		$this->dispatch('/website/order/party/1/hashCode/abc/member/'.self::$memberId.'?format=atom');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage(Website_Model_MemberException::INVALID_CREDENTIALS));
	}

	//public function testIndexAsJsonAction() {
	//	$this->setExpectedException('Website_Model_OrderException','Party missing!');
	//
	//	$this->dispatch('/website/order/?format=json');
	//}


	//public function testIndexAsPdfAction() {
	//	$this->setExpectedException('Website_Model_OrderException','Party missing!');
	//
	//	$this->dispatch('/website/order/?format=pdf');
	//}


	/**
	 * @covers Website_OrderController::indexAction
	 */
	public function testIndexOfPartyAsXmlAction() {
		$this->dispatch('/website/order/party/1/member/'.self::$memberId.'/hashCode/'.self::$hashCode.'?format=xml');
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertContains('recipeName="Mojito"',$this->response->outputBody());
	}

	//public function testIndexOfPartyAsJsonAction() {
	//	$this->dispatch('/website/order/party/1/?format=json');
	//
	//	$this->assertModule("website");
	//	$this->assertAction("index");
	//	$this->assertController("order");
	//	$this->assertResponseCode(200);
	//	$this->assertHeaderContains('Content-Type','application/json');
	//	$this->assertContains('"name":"Mojito"',$this->response->outputBody());
	//}

	/**
	 * @covers Website_OrderController::indexAction
	 */
	public function testIndexOfPartyAsRssAction() {
		$this->dispatch('/website/order/party/1/member/'.self::$memberId.'/hashCode/'.self::$hashCode.'?format=rss');
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/rss+xml');
		$this->assertContains('<title><![CDATA[Mojito für Max Mustermann]]></title>',$this->response->outputBody());
	}

	/**
	 * @covers Website_OrderController::indexAction
	 */
	public function testIndexOfPartyAsAtomAction() {
		$this->dispatch('/website/order/party/1/member/'.self::$memberId.'/hashCode/'.self::$hashCode.'?format=atom');
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/atom+xml');
		$this->assertContains('<title><![CDATA[Mojito für Max Mustermann]]></title>',$this->response->outputBody());
	}

	/**
	 * @covers Website_OrderController::getAction
	 */
	public function testGetAction() {
		$this->dispatch('/website/order/id/'.self::$orderId.'/member/'.self::$memberId.'/hashCode/'.self::$hashCode);
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertContains("Mojito für Max Mustermann",$this->response->outputBody());
	}

	/**
	 * @covers Website_OrderController::getAction
	 */
	public function testGetWithWrongIdAction() {
		$this->dispatch('/website/order/id/0/member/'.self::$memberId.'/hashCode/'.self::$hashCode);

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Id_Wrong'));
	}

	/**
	 * @covers Website_OrderController::postAction
	 */
	public function testPostHashCodeMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('recipe'=>'1','member'=>self::$memberId,'party'=>'1'));
		$this->dispatch('/website/order/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	/**
	 * @covers Website_OrderController::postAction
	 */
	public function testPostInvalidHashCodeAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>'abc','recipe'=>'1','member'=>self::$memberId,'party'=>'1'));
		$this->dispatch('/website/order/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage(Website_Model_MemberException::INVALID_CREDENTIALS));
	}

	/**
	 * @covers Website_OrderController::postAction
	 */
	public function testPostUserMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>self::$hashCode,'recipe'=>'1','party'=>'1'));
		$this->dispatch('/website/order/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Member missing!'));
	}

	/**
	 * @covers Website_OrderController::postAction
	 */
	public function testPostPartyMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>self::$hashCode,'recipe'=>'1','member'=>self::$memberId));
		$this->dispatch('/website/order/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Party missing!'));
	}

	/**
	 * @covers Website_OrderController::putAction
	 */
	public function testPutAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('hashCode'=>self::$hashCode,'member'=>self::$memberId,'status'=>Website_Model_Order::CANCELED));
		$this->dispatch('/website/order/'.self::$orderId.'?format=xml');

		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("get"); // redirect
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertContains('status="'.Website_Model_Order::CANCELED.'"',$this->response->outputBody());
	}

	/**
	 * @covers Website_OrderController::putAction
	 */
	public function testPutInvalidHashCodeAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('member'=>self::$memberId,'hashCode'=>'abc','status'=>'CANCELED'));
		$this->dispatch('/website/order/'.self::$orderId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage(Website_Model_MemberException::INVALID_CREDENTIALS));
	}

	/**
	 * @covers Website_OrderController::putAction
	 */
	public function testPutUserIdMissingAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('hashCode'=>self::$hashCode,'status'=>'CANCELED'));
		$this->dispatch('/website/order/'.self::$orderId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Member missing!'));
	}

	/**
	 * @covers Website_OrderController::putAction
	 */
	public function testPutHashCodeMissingAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('member'=>self::$memberId,'status'=>'CANCELED'));
		$this->dispatch('/website/order/'.self::$orderId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	/**
	 * @covers Website_OrderController::deleteAction
	 */
	public function testDeleteAsXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('hashCode'=>self::$hashCode,'member'=>self::$memberId));
		$this->dispatch('/website/order/'.self::$orderId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Delete not possible!'));
	}

	/**
	 * @covers Website_OrderController::getAction
	 */
	public function testGetGuestAction() {
		$this->request->setMethod('GET')->setPost(array('hashCode'=>self::$hashCode,'member'=>self::$memberId,'party'=>1,'guest'=>1));
		$this->dispatch('/website/guest/'.self::$memberId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Seite wurde nicht gefunden.'));
	}
	
	/**
	 * @covers Website_OrderController::putAction
	 */
	public function testPutGuestAction() {
		$this->request->setMethod('PUT')->setPost(array('hashCode'=>self::$hashCode,'member'=>self::$memberId,'party'=>1,'guest'=>1));
		$this->dispatch('/website/guest/'.self::$memberId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Seite wurde nicht gefunden.'));
	}
	
	/**
	 * @covers Website_OrderController::deleteAction
	 */
	public function testDeleteGuestAction(){
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testDeleteGuestAction',Zend_Log::DEBUG);
		
		$this->request->setMethod('POST')->setPost(array('email'=>'thobach@web.de','password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');
		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		$res = $xml->xpath("//member");
		$xmlAttr = $res[0]->attributes();
		$hostHashCode = print_r($xmlAttr['hashCode']."",true);
		$hostId = print_r($xmlAttr['id']."",true);
		
				
		$this->reset();
		parent::setUp();
		
		$log->log('OrderControllerTest->testDeleteGuestAction: remove member from party',Zend_Log::DEBUG);
		$this->request->setMethod('DELETE')->setPost(
			array('hashCode'=>$hostHashCode,
			'member'=>$hostId,
			'party'=>1,
			'guest'=>self::$memberId));
		$this->dispatch('/website/guest/?format=xml');
		
		$this->assertFalse(Website_Model_CbFactory::factory('Website_Model_Party',1)->memberIsGuest(self::$memberId));
	}
}
