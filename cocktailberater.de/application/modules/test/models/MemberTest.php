<?php
class MemberTest extends PHPUnit_Framework_TestCase {

	private static $memberId;
	private static $random;
	private static $partyTest;
	
	public function getMemberId()
	{
		return MemberTest::$memberId;
	}
	
	public function getRandom()
	{
		return MemberTest::$random;
	}
	
	public function testMemberAdd()
	{
		$member = new Member();
		$member->firstname = 'Test';
		$member->lastname = 'Tester';
		$member->birthday = '1986-08-28';
		MemberTest::$random = rand();
		$member->email = MemberTest::$random.'test@test.de';
		$member->passwordHash = md5('test');
		$member->save();
		MemberTest::$memberId = $member->id;
	}
	
	// TODO: getPhoto
	
	public function testMemberLoad()
	{
		$member = Website_Model_CbFactory::factory('Website_Model_Member',MemberTest::$memberId);
		$this->assertEquals(MemberTest::$memberId, $member->id);
		$this->assertEquals('Test', $member->firstname);
		$this->assertEquals('Tester', $member->lastname);
		$this->assertTrue(Member::exists($member->id));
		$this->assertTrue(!Member::exists($member->id+1));
	}
	
	public function testGetMemberByEmail()
	{
		$member = Member::getMemberByEmail(MemberTest::$random.'test@test.de');
		$this->assertEquals(MemberTest::$memberId, $member->id);
		$this->assertEquals('Test', $member->firstname);
		$this->assertEquals('Tester', $member->lastname);
		$member = Member::getMemberByEmail(MemberTest::$random.'test@test.de1');
		$this->assertTrue(!$member);
	}
	
	public function testMemberAuth()
	{
		$member = Website_Model_CbFactory::factory('Website_Model_Member',MemberTest::$memberId);
		$this->assertTrue($member->authenticate(md5('test'))); // TODO: md5 prefix einf�gen
		$this->setExpectedException('MemberException','Password_Invalid');
		$this->assertTrue(!$member->authenticate(md5('test1'))); // TODO: md5 prefix einf�gen
		/*$auth = Zend_Auth::getInstance();
		$authAdapter = new CbAuthAdapter(MemberTest::$random.'test@test.de',md5('test'));
		$result = $auth->authenticate($authAdapter);
		$this->assertTrue($result->isValid());
		// Zend_Debug::dump($memberId->getIdentity());
		$this->assertEquals(MemberTest::$memberId, $auth->getIdentity()->id);*/
	}
	
	public function testMemberAuthByHashCode()
	{
		$member = Website_Model_CbFactory::factory('Website_Model_Member',MemberTest::$memberId);
		$this->assertTrue($member->authenticateByHashCode($member->hashCode));
		$this->setExpectedException('MemberException','Member_HashCode_Invalid');
		$this->assertTrue($member->authenticateByHashCode($member->hashCode+'1'));
		// TODO: test Member_HashCode_Expired
	}
	
	public function testMemberGetInvoice(){
		$paramsPartyTest['existingMemberId'] = MemberTest::$memberId;
		$partyTest = new PartyTest();
		$partyTest->testPartyAdd($paramsPartyTest);
		
		$paramsOrderTest['existingMemberId'] = MemberTest::$memberId;
		$paramsOrderTest['existingPartyId'] = $partyTest->getPartyId();
		$orderTest = new OrderTest();
		$orderTest->testOrderAdd($paramsOrderTest);
		$member = Website_Model_CbFactory::factory('Website_Model_Member',MemberTest::$memberId);
		$invoice = $member->getInvoice($partyTest->getPartyId());
		try{
			$orderTest->testOrderDelete(false, false);
		} catch (OrderException $e){
			try {
				$partyTest->testPartyDelete(false);
			} catch (PartyException $e){
				return;
			}
		}
	}
	
	public function testListMember(){
		$members = Member::listMembers();
		$this->assertTrue(count($members)>=1);
	}
	
	public function testToXml(){
		$member = Website_Model_CbFactory::factory('Website_Model_Member',MemberTest::$memberId);
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$member->toXml($xml,$rsp);
		$xmlString = '<?xml version="1.0"?>
<rsp><member id="'.$member->id.'" firstname="Test" lastname="Tester" birthday="1986-08-28" email="'.$member->email.'" hashCode="'.$member->hashCode.'" hashExpiryDate="'.$member->hashExpiryDate.'" apiKey="" insertDate="'.$member->insertDate.'" updateDate="'.$member->updateDate.'"/></rsp>
';
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}
	
	public function testMemberDelete($memberId=NULL)
	{
		if(!$memberId){
			$memberId = $this->getMemberId();
		}
		$member = Website_Model_CbFactory::factory('Website_Model_Member',$memberId);
		$member->delete();
		$this->setExpectedException('MemberException','Id_Wrong');
		$member = Website_Model_CbFactory::factory('Website_Model_Member',$memberId);
	}
	

}