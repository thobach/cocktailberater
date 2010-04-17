<?php
class Website_Model_Rating {
	// attributes
	private $id;
	private $memberId;
	private $recipeId;
	private $mark;
	private $ip;
	private $insertDate;
	
	// associations
	private $_recipe;
	private $_member;

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
	
	/**
	 * Rating constructor returns a Rating object by an id, or an empty one
	 *
	 * @param integer optional $idRating
	 * @return Rating
	 */
	public function __construct ( $idRating = NULL ) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Rating->__construct',Zend_Log::DEBUG);
		
		if (! empty ( $idRating )) {
			$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','rating');
			$rating = $table->fetchRow ( 'id=' . $idRating ) ;
			// if rating does not exist
			if(!$rating){
				$log->log('Website_Model_Rating->__construct: Website_Model_RatingException (Id_Wrong)',Zend_Log::DEBUG);
				throw new Website_Model_RatingException('Id_Wrong');
			}
			$this->id 			= $rating->id ;
			$this->memberId 	= $rating->member;
			$this->recipeId		= $rating->recipe;
			$this->mark 		= $rating->mark;
			$this->ip 			= $rating->ip;
			$this->insertDate 	= $rating->insertDate;
		}
		$log->log('Website_Model_Rating->__construct: exit',Zend_Log::DEBUG);
	}
	
	/**
	 * saves the object persistent into the databse
	 *
	 * @return mixed at insert int (recipeId), if failed (fraud protection) false
	 */
	public function save () {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','rating');
		if(!$this->rated24hBefore()) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$this->id = $table->insert ( $data ) ;
			$this->updateRecipeStatistics();
			return $this->id ;
		} else {
			return false;
		}
	}

	private function rated24hBefore (){
		$table= Website_Model_CbFactory::factory('Website_Model_MysqlTable','rating');
		$date = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
		// TODO: Date-Funktion, die 24h vorher ausgibt
		$date = '2008-09-08 08:34:23';
		$result = $table->fetchAll('recipe='.$this->recipeId.' AND ip=\''.$this->ip.'\' AND insertDate>=\''.$date.'\'');
		if (count($result)>=1){
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * saves the rating to the recipe table (for statistics and performance)
	 * 
	*/
	private function updateRecipeStatistics(){
		$this->getRecipe()->ratingsSum += $this->mark;
		$this->getRecipe()->ratingsCount += 1;
		$this->getRecipe()->save();
	}
	
	/**
	 * gibt ein Array zurück um die Daten in eine table zu speichern
	 *
	 * @return array
	 */
	public function dataBaseRepresentation () {
		$array [ 'member' ] = $this->memberId;
		$array [ 'recipe' ] = $this->recipeId;
		$array [ 'mark' ] = $this->mark ;
		$array [ 'ip' ] = $this->ip ;
		return $array ;
	}
	
	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 * @tested
	 */
	public function toXml ( $xml , $ast ) {
		$rating = $xml->createElement ( 'reating' ) ;
		$rating->setAttribute ( 'id', $this->id ) ;
		$rating->setAttribute ( 'recipe', $this->recipeId ) ;
		$rating->setAttribute ( 'member', $this->memberId) ;
		$rating->setAttribute ( 'mark', $this->mark) ;
		$rating->setAttribute ( 'ip', $this->ip) ;
		$rating->setAttribute ( 'insertDate', $this->insertDate) ;
		$ast->appendchild ( $rating ) ;
	}
}
?>