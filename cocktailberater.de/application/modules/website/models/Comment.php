<?php
/**
 * Comment class
 *
 */
class Website_Model_Comment {
	
	// attributes
	private $id;
	private $memberId;
	private $recipeId;
	private $comment;
	private $ip;
	private $insertDate;
	private $updateDate;
	
	// associations
	private $_member;
	private $_recipe;

	/**
	 * magic getter for all attributes
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		if (property_exists(get_class($this), $name)) {
			return $this->$name;
		} else {
			throw new Exception('Class \''.get_class($this).'\' does not provide property: ' . $name . '.');
		}
	}
	
	/**
	 * resolve Association and return an object of Member
	 *
	 * @return Member
	 */
	public function getMember()
	{
		if(!$this->_member){
			$this->_member = Website_Model_CbFactory::factory('Website_Model_Member',$this->memberId);
		}
		return $this->_member;
	}
	
	/**
	 * resolve Association and return an object of Recipe
	 *
	 * @return Recipe
	 */
	public function getRecipe()
	{
		if(!$this->_recipe){
			$this->_recipe = Website_Model_CbFactory::factory('Website_Model_Recipe',$this->recipeId);
		}
		return $this->_recipe;
	}

	/**
	 * Magic Setter Function, is accessed when setting an attribute
	 *
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function __set ( $name , $value ) {
		if (property_exists ( get_class($this), $name )) {
			$this->$name = $value ;
		} else {
			throw new Exception ( 'Class \''.get_class($this).'\' does not provide property: ' . $name . '.' ) ;
		}
	}
	
	public function __construct ($commentId=NULL){
		if($commentId!=NULL){
			$commentTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','comment');
			$comment = $commentTable->fetchRow('id = '.$commentId);
			// if comment does not exist
			if(!$comment){
				throw new CommentException('Id_Wrong');
			}
			$this->id = $comment->id;
			$this->memberId = $comment->member;
			$this->recipeId = $comment->recipe;
			$this->comment = $comment->comment;
			$this->ip = $comment->ip;
			$this->insertDate = $comment->insertDate;
			$this->updateDate = $comment->updateDate;
		}
	}
	
	public static function commentsByRecipeId ($recipeId){
		$commentTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','comment');
		$comments = $commentTable->fetchAll('recipe = '.$recipeId);
		foreach ($comments as $comment) {
			$commentArray[] = Website_Model_CbFactory::factory('Website_Model_Comment',$comment['id']);
		}
	    return $commentArray;
	}

	/**
	 * saves the object persistent into the databse
	 *
	 * @return mixed at update true, at insert int (recipeId)
	 */
	public function save () {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','comment');
		if ($this->id) {
			// update tag
			$table->update ( $this->dataBaseRepresentation (), 'id = ' . $this->id ) ;
			$return = true ;
		} else {
			// insert new tag
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$this->id = $table->insert ( $data ) ;
			$return = $this->id ;
		}
		return $return ;
	}
	
	public function delete (){
		$tagTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'comment');
		$tagTable->delete('id='.$this->id);
		CbFactory::destroy('Comment',$this->id);
		unset($this);
	}
	

	/**
	 * gibt ein Array zurück um die Daten in eine table zu speichern
	 *
	 * @return array
	 */
	public function dataBaseRepresentation () {
		$array [ 'member' ] = $this->memberId;
		$array [ 'recipe' ] = $this->recipeId;
		$array [ 'comment' ] = $this->comment ;
		$array [ 'ip' ] = $this->ip ;
		return $array ;
	}
	
	
	public function toxml ( $xml , $ast ) {
		$tag = $xml->createElement ('comment') ;
		$tag->setAttribute('id', $this->id) ;
		$tag->setAttribute('comment', $this->comment) ;
		$tag->setAttribute('recipe',$this->recipeId);
		$tag->setAttribute('member', $this->memberId) ;
		$tag->setAttribute('insertDate',$this->insertDate);
		$tag->setAttribute('updateDate',$this->updateDate);
		
		$ast->appendchild ( $tag ) ;
	}
	
}
?>