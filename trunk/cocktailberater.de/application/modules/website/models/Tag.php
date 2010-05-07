<?php
class Website_Model_Tag implements Zend_Tag_Taggable {

	// attributes
	private $id;
	private $name;
	// although every tag is unique, there are many tags with the same name
	// the count only refers to the same recipe, not to the overall accurance
	private $count;
	private $recipeId;
	private $memberId;
	private $insertDate;
	private $updateDate;

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
	
	public function getParam($name) {
		return $this->__get($name);
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
	
	public function setParam($name,$value) {
		return $this->__set($name,$value);
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
	
	public function __construct ( $idtag = NULL , $name = NULL, $recipeId = NULL ) {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','tag');
		try {
			// get existing tag
			if ($idtag != '' && empty ( $name )) {
				$tag = $table->fetchRow ( 'id=' . $idtag ) ;
				$this->id = $tag->id;
				$this->name= $tag->name;
				//$this->insertDate =  new Zend_Date ($tag->insertDate, Website_Model_DateFormat::MYSQLTIMESTAMP);
				//$this->updateDate =  new Zend_Date ($tag->updateDate, Website_Model_DateFormat::MYSQLTIMESTAMP);
				$this->insertDate =  $tag->insertDate;
				$this->updateDate =  $tag->updateDate;
				$this->recipeId = $tag->recipe;
				$this->memberId = $tag->member;
				$this->count = $this->getCountForRecipe();
			} 
			// create new tag, no need to check for duplicates, since they are wanted (no counting)
			elseif ($name != '' && $recipeId != '' && empty ( $idtag )) {
				$this->name = $name ;
				$this->recipeId = $recipeId;
				// TODO: save memberId
				$this->save();
			}
		} catch ( Zend_Db_Adapter_Exception $e ) {
			// perhaps a failed login credential, or perhaps the RDBMS is not running
			print $e ;
		} catch ( Zend_Exception $e ) {
			// perhaps factory() failed to load the specified Adapter class
			print $e ;
		}
	}
	
	public function getWeight(){
		return $this->getCountForRecipe();
	}
	
	public function getTitle(){
		return $this->name;
	}
	
	public function getCountForRecipe(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$result = $db->fetchRow ( 'SELECT count(id) as count FROM tag WHERE recipe='.$this->recipeId.' AND name=\''.$this->name.'\' GROUP BY recipe AND name');
		return $result->count;
	}

	

	/**
	 * returns an array of tag objects by search string
	 *
	 * @param string $search
	 * @param int $limit, default = 100
	 * @return array Tag
	 */
	static function listTags ( $search, $limit=null,$unique=false) {
		if($limit){
			$limitSql = ' LIMIT '.$limit;
		}
		if($unique){
			$uniqueSql = 'GROUP BY name ';
		}
		$db = Zend_Db_Table::getDefaultAdapter();
		$tags = $db->fetchAll('SELECT id FROM tag WHERE name LIKE ? '.$uniqueSql.$limitSql, str_replace ( '\'', '\\\'', '%'.$search.'%' ));
		$tagArray = array();
		foreach ($tags as $tag) {
			$tagArray[$tag['id']] = Website_Model_CbFactory::factory('Website_Model_Tag', $tag['id']);
		}
		return ($tagArray) ;
	}
	
	/**
	 * returns a Zend_Tag_Cloud with all tags of a recipe
	 *
	 * @param int $recipeId
	 * @return Zend_Tag_Cloud tag cloud
	 */
	static function getRecipeTagCloud ( $recipeId) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$tags = $db->fetchAll ( 'SELECT name, count(name) as weight FROM tag WHERE recipe='.$recipeId.' GROUP BY name');
		
		$list = new Zend_Tag_ItemList();
		$view = new Zend_View();
		foreach ($tags as $tag) {
			$list[] = new Zend_Tag_Item(array('title' => $tag['name'], 'weight' => $tag['weight'], 
				'params'=>array('id'=>'test','url' => $view->url(array('module'=>'website','controller'=>'recipe',
						'action'=>'index','search_type'=>'tag','search'=>$tag['name']),'default',true))));
		}
		// Spread absolute values on the items
		$list->spreadWeightValues(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));
		$cloud = new Zend_Tag_Cloud();
		$cloud->setItemList($list);
		return ($cloud) ;
	}

	public static function tagsByRecipeId($id,$unique=false){
		if($unique){
			$db = Zend_Db_Table::getDefaultAdapter();
			$tags = $db->fetchAll ( 'SELECT id FROM tag WHERE recipe='.$id.' GROUP BY name');
		} else {
			$tagTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','tag');
			$tags = $tagTable->fetchAll('recipe='.$id);
		}
		$tagArray = array();
		foreach ($tags as $tag) {
			$tagArray[] = Website_Model_CbFactory::factory('Website_Model_Tag', $tag['id']);
		}
		return $tagArray;
	}
	
	public function toxml ( $xml , $ast ) {
		$tag = $xml->createElement ('tag') ;
		$tag->setAttribute('id', $this->id) ;
		$tag->setAttribute('name', $this->name) ;
		$tag->setAttribute('insertDate',$this->insertDate);
		$tag->setAttribute('updateDate',$this->updateDate);
		$tag->setAttribute('recipe',$this->recipeId);
		$tag->setAttribute('member', $this->memberId) ;
		
		$ast->appendchild ( $tag ) ;
	}
	
	/**
	 * saves the object persistent into the databse
	 *
	 * @return mixed at update true, at insert int (tagId)
	 */
	public function save () {
		// remove all saved pages
		$cache = Zend_Registry::get('cache');
		$cache->clean(
			Zend_Cache::CLEANING_MODE_NOT_MATCHING_TAG,
			array('model')
		);
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','tag');
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
		$tagTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'tag');
		$tagTable->delete('id='.$this->id);
		Website_Model_CbFactory::destroy('Website_Model_Tag',$this->id);
		unset($this);
	}
	

	/**
	 * gibt ein Array zurück um die Daten in eine table zu speichern
	 *
	 * @return array
	 */
	public function dataBaseRepresentation () {
		$array [ 'name' ] = $this->name;
		$array [ 'recipe' ] = $this->recipeId;
		$array [ 'member' ] = $this->memberId ;
		return $array ;
	}
}
?>