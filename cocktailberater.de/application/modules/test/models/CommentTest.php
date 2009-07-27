<?php
class CommentTest extends PHPUnit_Framework_TestCase {

	private static $commentId;
	private static $comment2Id;
	private static $memberRandom;
	private static $member;
	private static $recipe;

	public function getCommentId()
	{
		return CommentTest::$commentId;
	}
	
	public function getComment2Id()
	{
		return CommentTest::$comment2Id;
	}

	/**
	 * testCommentAdd creates a comment with owner
	 **/
	public function testCommentAdd()
	{
		$comment = new Comment();
		
		$memberTest = new MemberTest();
		$memberTest->testMemberAdd();
		CommentTest::$memberRandom = MemberTest::getRandom();
		CommentTest::$member = $memberTest;
		
		$recipeTest = new RecipeTest();
		$recipeTest->testRecipeAdd();
		CommentTest::$recipe = $recipeTest;

		$comment->memberId = $memberTest->getMemberId();
		$comment->recipeId = $recipeTest->getRecipeId();
		$comment->comment = 'Die beste Comment der Stadt';
		$comment->ip = 'DE';
		$comment->save();
		CommentTest::$commentId = $comment->id;
	}
	
	
	public function testCommentAdd2()
	{
		$comment = new Comment();
		
		$memberTest = CommentTest::$member;

		$recipeTest = new RecipeTest(); // TODO: diese und alle weiteren recipeTest zuweisungen überprüfen

		$comment->memberId = $memberTest->getMemberId();
		$comment->recipeId = $recipeTest->getRecipeId();
		$comment->comment = 'Die beste Comment der Stadt';
		$comment->ip = 'DE';
		$comment->save();
		CommentTest::$comment2Id = $comment->id;
	}

	/**
	 * testCommentLoad tests the behaviour of CbFactory::factory,
	 * Comment properties, getMember method and getRecipe method
	 **/
	public function testCommentLoad()
	{
		$comment = Website_Model_CbFactory::destroy('Website_Model_Comment',CommentTest::$commentId);
		$this->assertEquals(CommentTest::$commentId, $comment->id);
		$this->assertEquals('Die beste Comment der Stadt', $comment->comment);
		$this->assertEquals('Test', $comment->getMember()->firstname);
		$this->assertEquals('TestCocktail', $comment->getRecipe()->name);
	}

	public function testToXml(){
		$commentId = $this->getCommentId();
		$comment = Website_Model_CbFactory::destroy('Website_Model_Comment',$commentId);
		$xml = new DOMDocument("1.0");
		$rsp = $xml->createElement("rsp");
		$xml->appendChild($rsp);
		$comment->toXml($xml,$rsp);
		$member = Website_Model_CbFactory::factory('Website_Model_Member',$comment->memberId);
		$recipeTest = new RecipeTest();
		$xmlString = '<?xml version="1.0"?>
<rsp><comment id="'.$comment->id.'" comment="Die beste Comment der Stadt" recipe="'.$recipeTest->getRecipeId().'" member="'.CommentTest::$member->getMemberId().'" insertDate="'.$comment->insertDate.'" updateDate="'.$comment->updateDate.'"/></rsp>
';
		$this->assertXmlStringEqualsXmlString($xmlString,$xml->saveXML());
	}

	public function testCommentsByRecipeId(){
		$recipeTest = new RecipeTest();
		$comments = Comment::commentsByRecipeId($recipeTest->getRecipeId());
		$this->assertTrue(count($comments)>=2);
	}

	public function testCommentDelete($commentId=NULL)
	{
		if(!$commentId){
			$commentId = $this->getCommentId();
		}
		$comment = Website_Model_CbFactory::destroy('Website_Model_Comment',$commentId);
		$comment->delete();
		$recipeTest = new RecipeTest();
		$recipeTest->testRecipeDelete($recipeTest->getRecipeId());
		$this->setExpectedException('CommentException','Id_Wrong');
		$comment = Website_Model_CbFactory::destroy('Website_Model_Comment',$commentId);
	}
	
	public function testComment2Delete($comment2Id=NULL)
	{
		if(!$comment2Id){
			$comment2Id = $this->getComment2Id();
		}
		$comment = Website_Model_CbFactory::destroy('Website_Model_Comment',$comment2Id);
		$comment->delete();
		$this->setExpectedException('CommentException','Id_Wrong');
		$comment = Website_Model_CbFactory::destroy('Website_Model_Comment',$comment2Id);
	}

}