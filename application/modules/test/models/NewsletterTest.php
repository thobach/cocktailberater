<?php
class NewsletterTest extends PHPUnit_Framework_TestCase {

	private static $newsletterId;
	
	public function getNewsletterId()
	{
		return NewsletterTest::$newsletterId;
	}
	
	public function testNewsletterAdd()
	{
		$newsletter = new Newsletter();
		$newsletter->name = 'TestNewsletter';
		$newsletter->description = 'Die beste Newsletter der Stadt';
		$newsletter->save();
		NewsletterTest::$newsletterId = $newsletter->id;
	}
	
	
	public function testNewsletterLoad()
	{
		$newsletter = Website_Model_CbFactory::factory('Website_Model_Newsletter',NewsletterTest::$newsletterId);
		$this->assertEquals(NewsletterTest::$newsletterId, $newsletter->id);
		$this->assertEquals('TestNewsletter', $newsletter->name);
		$this->assertEquals('Die beste Newsletter der Stadt', $newsletter->description);
	}
	
	public function testNewsletterDelete($newsletterId=NULL)
	{
		if(!$newsletterId){
			$newsletterId = $this->getNewsletterId();
		}
		$newsletter = Website_Model_CbFactory::factory('Website_Model_Newsletter',$newsletterId);
		$newsletter->delete();
		$this->setExpectedException('NewsletterException','Id_Wrong');
		$newsletter = Website_Model_CbFactory::factory('Website_Model_Newsletter',$newsletterId);
	}
	

}