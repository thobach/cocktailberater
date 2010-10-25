<?php
class Models_MemberTest extends ControllerTestCase {

	/**
	 * @covers Website_Model_Member::__construct
	 * @covers Website_Model_Member::getBars
	 */
	public function testGetBars() {
		// good case
		$this->object = new Website_Model_Member(54);
		$bars = $this->object->getBars();
		$this->assertEquals("Midtown Bar",$bars[0]->name);
		$this->assertEquals(1,count($bars));
		// case: no bars associated
		$this->object = new Website_Model_Member(55);
		$bars = $this->object->getBars();
		$this->assertEquals(0,count($bars));
	}

}