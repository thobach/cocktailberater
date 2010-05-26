<?php
/**
 * Comment class
 *
 */
class Website_Model_Comment {

	// attributes
	private $id;
	private $memberId;
	/**
	 * name for non members
	 * @var string
	 */
	private $name;
	/**
	 * email for non members
	 * @var string
	 */
	private $email;
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
	 * @return Website_Model_Recipe
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
			$this->name = $comment->name;
			$this->email = $comment->email;
			$this->recipeId = $comment->recipe;
			$this->comment = $comment->comment;
			$this->ip = $comment->ip;
			$this->insertDate = $comment->insertDate;
			$this->updateDate = $comment->updateDate;
		}
	}

	public static function commentsByRecipeId ($recipeId){
		$commentTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','comment');
		$comments = $commentTable->fetchAll('recipe = '.$recipeId,'updateDate DESC');
		foreach ($comments as $comment) {
			$commentArray[] = Website_Model_CbFactory::factory('Website_Model_Comment',$comment['id']);
		}
		return $commentArray;
	}
	
	/**
	 * returns an array of comment objects
	 *
	 * @return array[int]Website_Model_Tag
	 */
	static function listComments () {
		$commentTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','comment');
		$comments = $commentTable->fetchAll(NULL,'updateDate DESC');
		$tagArray = array();
		foreach ($comments as $comment) {
			$commentArray[] = Website_Model_CbFactory::factory('Website_Model_Comment',$comment['id']);
		}
		return ($commentArray) ;
	}

	/**
	 * saves the object persistent into the databse
	 *
	 * @return mixed at update true, at insert int (recipeId)
	 */
	public function save () {
		// remove all saved pages
		$cache = Zend_Registry::get('cache');
		$cache->clean(
		Zend_Cache::CLEANING_MODE_NOT_MATCHING_TAG,
		array('model')
		);
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','comment');
		if ($this->id) {
			// update comment
			$table->update ( $this->dataBaseRepresentation (), 'id = ' . $this->id ) ;
			$return = true ;
		} else {
			// insert new comment
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
		$array [ 'name' ] = $this->name;
		$array [ 'email' ] = $this->email;
		$array [ 'recipe' ] = $this->recipeId;
		$array [ 'comment' ] = $this->comment ;
		$array [ 'ip' ] = $this->ip ;
		return $array ;
	}

	/**
	 * Returns an array with all elements of a feed entry
	 *
	 * @return array
	 */
	public function toFeedEntry(){
		$view = new Zend_View();

		$date = new Zend_Date($this->insertDate,Zend_Date::ISO_8601);

		$entry = array(
				'title'       	=> 	$this->getRecipe()->name.' wurde kommentiert',
				'guid'			=>	$view->url(array('module'=>'website',
									'controller'=>'recipe','action'=>'get',
									'id'=>$this->getRecipe()->getUniqueName()),'rest',true).'#comment-'.$this->id,
				'link'        	=> 	$view->url(array('module'=>'website',
									'controller'=>'recipe','action'=>'get',
									'id'=>$this->getRecipe()->getUniqueName()),'rest',true),
				'lastUpdate'	=> 	$date->getTimestamp(),
				'content'		=> 	'Am '.$date->get(Zend_Date::DATE_FULL,$de).
									' um '.$date->get(Zend_Date::TIME_MEDIUM,$de).
									' Uhr wurde '.$this->getRecipe()->name.' mit folgendem Kommentar versehen: '.
									''.$this->comment,
				'description'	=>	'Am '.$date->get(Zend_Date::DATE_FULL,$de).
									' um '.$date->get(Zend_Date::TIME_MEDIUM,$de).
									' Uhr wurde '.$this->getRecipe()->name.' mit folgendem Kommentar versehen: '.
									''.$this->comment,
		);
		return $entry;
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