<?php
class PartyTest extends PHPUnit_Framework_TestCase {

	private static $partyId;
	
	public function getPartyId()
	{
		return PartyTest::$partyId;
	}
	
	public function testPartyAdd($params=null)
	{
		$party = new Party();
		$party->barId = 1;
		$party->menuId = 1;
		$party->name = 'TestParty';
		$party->date = '2010-08-28 21:00:00';
		
		if($params['existingMemberId']==null){
			$memberTest = new MemberTest();
			$memberTest->testMemberAdd();
			$party->hostId = $memberTest->getMemberId();
		} else {
			$party->hostId = $params['existingMemberId'];
		}

		$party->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);		
		$party->save();
		PartyTest::$partyId = $party->id;
	}
	
	// TODO: listPartys
	// TODO: exists
	
	public function testPartyLoad()
	{
		$party = CbFactory::factory('Party',PartyTest::$partyId);
		$this->assertEquals(PartyTest::$partyId, $party->id);
		$this->assertEquals('TestParty', $party->name);
	}
	
	public function testPartyDelete($deleteHostToo=true)
	{
		$party = CbFactory::factory('Party',PartyTest::$partyId);
		if($deleteHostToo){
			$party->getHost()->delete();
		}
		$party->delete();
		$this->setExpectedException('PartyException','Id_Wrong');
		$party = CbFactory::factory('Party',PartyTest::$partyId);
	}
	

}