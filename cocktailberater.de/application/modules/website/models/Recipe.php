<?php
/**
 * Recipe Class which holds all information for a recipe (instructions, glass, ingredients..)
 *
 */
class Website_Model_Recipe {

	// constants
	const BEGINNER 	= 'beginner';
	const ADVANCED 	= 'advanced';
	const PROFI 	= 'profi';

	// attributes
	private $id;
	private $name;
	private $memberId;
	private $cocktailId;
	private $glassId;
	private $description;
	private $instruction;
	private $source;
	private $workMin;
	private $difficulty;
	private $isOriginal;
	private $isAlcoholic; // calculated
	private $alcoholLevel; // calculated
	private $caloriesKcal; // calculated
	private $volumeCl; // calculated
	private $ratingsSum;
	private $ratingsCount;
	private $insertDate;
	private $updateDate;

	// associations
	private $_cocktail;
	private $_member;
	private $_glass;

	// supporting variables
	private $_components;
	private static $_recipes;

	/**
	 * magic getter for all attributes
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		if (property_exists(get_class($this), $name)) {
			// load associations only when needed (performance)
			if($name == '_cocktail' && !is_a($this->_cocktail,'Cocktail')){
				$this->_cocktail = Website_Model_CbFactory::factory('Cocktail', $this->cocktailId);
			} elseif($name=='_member' && !is_a($this->_member,'Member')){
				$this->_member = Website_Model_CbFactory::factory('Website_Model_Member', $this->memberId);
			} elseif($name=='_glass' && !is_a($this->_glass,'Glass')){
				$this->_glass = Website_Model_CbFactory::factory('Website_Model_Glass', $this->glassId);
			}
			return $this->$name;
		} else {
			throw new Exception('Class \''.get_class($this).'\' does not provide property: ' . $name . '.');
		}
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
	 * resolve Association and return an object of Member
	 *
	 * @return Member
	 * @tested
	 */
	public function getMember()
	{
		if(!$this->_member){
			$this->_member = Website_Model_CbFactory::factory('Website_Model_Member',$this->memberId);
		}
		return $this->_member;
	}

	/**
	 * resolve Association and return an object of Cocktail
	 *
	 * @return Cocktail
	 */
	public function getCocktail()
	{
		// TODO: write unit test
		if(!$this->_cocktail){
			$this->_cocktail = Website_Model_CbFactory::factory('Cocktail',$this->cocktailId);
		}
		return $this->_cocktail;
	}

	/**
	 * resolve Association and return an object of Glass
	 *
	 * @return Glass
	 */
	public function getGlass()
	{
		// TODO: write unit test
		if(!$this->_glass){
			$this->_glass = Website_Model_CbFactory::factory('Website_Model_Glass',$this->glassId);
		}
		return $this->_glass;
	}

	public static function getRecipe($id)
	{
		// TODO: write unit test
		if(!isset(self::$_recipes[$id])){
			self::$_recipes[$id] = Website_Model_CbFactory::factory('Website_Model_Recipe', $id);
		}
		return self::$_recipes[$id];
	}

	public function getRating(){
		// TODO: write unit test
		return round(@($this->ratingsSum/$this->ratingsCount),2);
	}

	/**
	 * checks whether the recipe exists in DB
	 *
	 * @param String $id
	 * @return booloean | int False or ID for cocktail
	 */
	public static function exists($id) {
		// TODO: write unit test
		$recipeTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipe') ;
		$recipe = $recipeTable->fetchRow('id='.$id);
		if ($recipe) {
			return $recipe->id;
		} else {
			return false;
		}
	}


	/**
	 *
	 * @tested
	 */
	public function __construct ( $id = NULL ) {

		// get proper table for object which holds all attributes
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipe') ;

		// Get recipe
		if (! empty ( $id )) {

			// fetch attributes from the database
			$recipe = $table->fetchRow ( 'id=' . $id ) ;
			if(!$recipe){
				throw new Website_Model_RecipeException('Recipe_Id_Invalid');
			}
			// assign attributes to object
			$this->id = $recipe->id;
			$this->name = $recipe->name ;
			$this->glassId = $recipe->glass;
			$this->memberId = $recipe->member;
			$this->cocktailId = $recipe->cocktail;
			$this->description = $recipe->description ;
			$this->instruction = $recipe->instruction ;
			$this->source = $recipe->source ;
			$this->workMin = $recipe->workMin ;
			$this->difficulty = $recipe->difficulty ;
			$this->isOriginal = $recipe->isOriginal ;
			//$this->isAlcoholic = $this->isAlcoholic();
			$this->isAlcoholic = $recipe->isAlcoholic;
			$this->alcoholLevel = $this->getAlcoholLevel();
			$this->caloriesKcal = $this->getCaloriesKcal();
			$this->volumeCl = $this->getVolumeCl();
			$this->ratingsCount = $recipe->ratingsCount;
			$this->ratingsSum = $recipe->ratingsSum;
			//$this->insertDate =  new Zend_Date ($recipe->insertDate);
			$this->insertDate =  $recipe->insertDate;
			//$this->updateDate =  new Zend_Date ($recipe->updateDate);
			$this->updateDate =  $recipe->updateDate;


		}

	}

	/**
	 * Returns all recipes where the recipe title contains the given string.
	 *
	 * @param $name search string
	 * @param $limit maximal number of recipes (nullable)
	 * @param $filter array with filters (nullable)
	 * @todo write unit test
	 * @todo implement filter array
	 * @return Website_Model_Recipe[] array with matching recipes
	 */
	public static function searchByName ($name,$limit=null,$filter=null){
		// if search string was empty, immediately return
		if($name=='' && $filter==null){
			return array();
		}
		// modfiy sql search if limit was given
		if($limit){
			$limitSql = ' LIMIT '.((int) $limit);
		} else {
			$limitSql = '';
		}
		$db = Zend_Db_Table::getDefaultAdapter();
		
		// evaluate filter
		$validFilters = array('with-image');
		if(is_array($filter)){
			foreach ($filter as $singleFilter){
				if(in_array($singleFilter,$validFilters)){
					// TODO
				}
			}
		} else if (in_array($filter,$validFilters)) {
			if($filter=='with-image'){
				
				$fromSQL .= ', photo2recipe';
				$whereSQL .= ' AND recipe.id=photo2recipe.recipe ';
			}
		}
		// look for perfect match
		$result = $db->fetchAll ( 'SELECT id,name FROM recipe'.$fromSQL.' WHERE name LIKE \''.mysql_escape_string($name).'%\''.$whereSQL.' GROUP BY name ORDER BY name '.$limitSql);
		// if perfect match has not reached the maximum limit, continue with fuzzy search
		if($limit && count($result)<$limit){
			$names = '';
			if(count($result)!=0){
				foreach($result as $recipe){
					$names[] = '\''.$recipe['name'].'\'';
				}
				$names = ' AND name NOT IN ('.implode(',',$names).') ';
			}
			// also include recipes where the given search string is contained somewhere in the title
			$result2 = $db->fetchAll ( 'SELECT id FROM recipe'.$fromSQL.' WHERE name LIKE \'%'.mysql_escape_string($name).'%\' '.$names.$whereSQL.' GROUP BY name LIMIT '.($limit-count($result)));
			$result = array_merge($result,$result2);
		}
		$recipeArray = array();
		foreach ($result as $recipe) {
			$recipeArray[] = Website_Model_CbFactory::factory('Website_Model_Recipe',$recipe['id']);
		}
		return $recipeArray;
	}

	public static function searchByIngredient ($name){
		// TODO: write unit test
		$ingredients = Website_Model_Ingredient::listIngredients ( $name ) ;
		$recipeArray = array();
		foreach($ingredients as $ingredient){
			$componentsArray = Website_Model_Component::componentsByIngredientId($ingredient->id);
			if(is_array($componentsArray)){
				foreach ($componentsArray as $component){
					$recipeArray[$component->recipeId] = Website_Model_CbFactory::factory('Website_Model_Recipe',$component->recipeId);
				}
			}
		}
		return $recipeArray;
	}

	public static function searchByTag ($name){
		// TODO: write  unit test
		$tags = Website_Model_Tag::listTags ( $name ) ;
		foreach($tags as $tag){
			$recipeArray[$tag->recipeId] = Website_Model_CbFactory::factory('Website_Model_Recipe',$tag->recipeId);
		}
		return $recipeArray;
	}

	/**
	 * Searches for recipes with certain difficulty, ordered by rating
	 * 
	 * @todo: write unit test
	 * @param String $difficulty
	 * @return Website_Model_Recipe[] array with unique recipes (id as key)
	 */
	public static function searchByDifficulty ($difficulty){
		$recipeArray = array();
		// check if valid difficulty
		$difficulties = array ('profi','advanced','beginner');
		if(in_array($difficulty,$difficulties)){
			$recipeTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe');
			// search for recipes with certain difficulty, order by rating
			$recipes = $recipeTable->fetchAll ( 'difficulty=\'' . $difficulty .'\'', '(ratingsSum / ratingsCount) DESC');
			foreach($recipes as $recipe){
				$recipeArray[$recipe['id']] = Website_Model_CbFactory::factory('Website_Model_Recipe',$recipe->id);
			}
		}
		return $recipeArray;
	}

	public function getCaloriesKcal (){
		if(!$this->caloriesKcal){
			// TODO: write unit test
			$components = $this->getComponents();

			// log to debug
			$logger = Zend_Registry::get('logger');

			$totalCaloriesKcal = 0;
			if (is_array ( $components )) {
				foreach ( $components as $component ) {
					$totalCaloriesKcal += $component->getCaloriesKcal();
				}
			}
			$this->caloriesKcal = round($totalCaloriesKcal,0);
		}
		return $this->caloriesKcal;
	}

	public function getVolumeCl()
	{
		// TODO: write unit test
		$components = $this->getComponents();
		$volumeCl=0;
		if (is_array ( $components )) {
			foreach ($components as $component) {
				if ($component->unit == 'cl') {
					$vol = $component->amount ;
					$volumeCl += $vol ;
				}
			}
		}
		return $volumeCl;
	}

	public function getAlcoholLevel()
	{
		// TODO: write unit test
		$components = $this->getComponents();
		$alcoholLevel=0;
		$volume=0;
		if (is_array ( $components )) {
			foreach($components as $component){
				$db = Zend_Db_Table::getDefaultAdapter();
				$alcoholLevelSql = $db->fetchRow ( 'SELECT AVG(alcoholLevel) AS averageAlcoholLevel
				FROM `product` 
				WHERE ingredient='.$component->ingredientId);
				if($alcoholLevelSql['averageAlcoholLevel']!="NULL"){
					$alcoholLevel += $alcoholLevelSql['averageAlcoholLevel'] / 100 * $component->amount ;
					//var_dump($alcoholLevel);
				}
				$volume += $component->amount;
			}
			$alcoholLevel = round($alcoholLevel/$volume*100,1);
		}
		return $alcoholLevel;
	}

	public function isAlcoholic()
	{
		// TODO: write unit test
		$alcoholLevel = $this->getAlcoholLevel();
		if($alcoholLevel > 0){
			return true;
		}
		return false;
	}

	public function getPhotos()
	{
		return Website_Model_Photo::photosByRecipeId ($this->id);
	}

	public function getVideos()
	{
		return Website_Model_Video::videosByRecipeId ($this->id);
	}

	/**
	 *
	 * @tested
	 */
	public function getComponents()
	{
		if(!$this->id){
			throw new Website_Model_RecipeException('Id_Missing');
		}
		if(!$this->_components){
			$this->_components = Website_Model_Component::componentsByRecipeId ($this->id);
		}
		return $this->_components;
	}

	public function addComponent(Website_Model_Component $component)
	{
		// TODO: write unit test
		$this->_components[] = $component;
	}

	public function addRecipeCategory ($recipeCategoryId){
		// TODO: write unit test
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe2recipecategory');
		$data['recipe'] = $this->id;
		$data['recipeCategory'] = $recipeCategoryId;
		$table->insert($data);
	}

	public function getComments()
	{
		// TODO: write unit test
		return Website_Model_Comment::commentsByRecipeId ($this->id);
	}

	private function getTags()
	{
		// TODO: write unit test
		return Website_Model_Tag::tagsByRecipeId ($this->id);
	}

	public static function getRecipesByTag($tag){
		// TODO: write unit test
		$db = Zend_Db_Table::getDefaultAdapter();
		$tags = $db->fetchAll ( 'SELECT recipe FROM tag WHERE name=\''.$tag.'\' GROUP BY recipe');
		foreach ( $tags as $tag ) {
			$recipeArray [] = Website_Model_CbFactory::factory('Website_Model_Recipe', $tag['recipe'] ) ;
		}
		return $recipeArray ;
	}

	private function getCategories()
	{
		// TODO: write unit test
		return RecipeCategory::categoryByRecipeId ($this->id);

	}

	public function recipesByCocktailId ( $idcocktail ) {
		// TODO: write unit test
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe');
		try {
			$recipes = $table->fetchAll ( 'cocktail=' . $idcocktail , '(ratingsSum / ratingsCount) DESC') ; }
			catch (Exception $e) {
				throw new Website_Model_RecipeException('Id_Wrong');
			}
			foreach ( $recipes as $recipe ) {
				$recipeArray [] = Website_Model_CbFactory::factory('Website_Model_Recipe', $recipe->id ) ;
			}
			return $recipeArray ;
	}

	// For testing purposes
	public function listRecipe ( $idrecipe ) {
		// TODO: write unit test
		try {
			return new Website_Model_Recipe ( $idrecipe ) ;
		} catch ( Zend_Db_Adapter_Exception $e ) {
			// perhaps a failed login credential, or perhaps the RDBMS is not running
			print $e ;
		} catch ( Zend_Exception $e ) {
			// perhaps factory() failed to load the specified Adapter class
			print $e ;
		}
	}

	public function listRecipes () {
		// TODO: write unit test
		// load cache from registry
		$cache = Zend_Registry::get('cache');
		// see if a cache already exists:
		if(!$recipeArray = $cache->load('recipeList')) {

			$recipeTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe');
			$recipes = $recipeTable->fetchAll();

			foreach ($recipes as $recipe) {
				$recipeArray[] = Website_Model_CbFactory::factory('Website_Model_Recipe',$recipe->id);
			}
			$cache->save($recipeArray,'recipeList');
		}
		return $recipeArray;

	}
	public function photoRecipe ( $idrecipe ) {
		// TODO: write unit test
		try {
			$db = Zend_Db_Table::getDefaultAdapter();
			$result = $db->fetchAll ( 'SELECT	*
			FROM photo2recipe
			NATURAL JOIN photo AS recipe_photo
			WHERE idrecipe = ?
			', $idrecipe ) ;
			return ($result) ;
		} catch ( Zend_Db_Adapter_Exception $e ) {
			// perhaps a failed login credential, or perhaps the RDBMS is not running
			print $e ;
		} catch ( Zend_Exception $e ) {
			// perhaps factory() failed to load the specified Adapter class
			print $e ;
		}
	}
	public function videoCocktail ( $idrecipe ) {
		// TODO: write unit test
		try {
			$db = Zend_Db_Table::getDefaultAdapter();
			$result = $db->fetchAll ( 'SELECT	*
			FROM video 
			NATURAL JOIN video2recipe
			WHERE idrecipe = ?
			LIMIT 1', $idrecipe ) ;
			return ($result) ;
		} catch ( Zend_Db_Adapter_Exception $e ) {
			// perhaps a failed login credential, or perhaps the RDBMS is not running
			print $e ;
		} catch ( Zend_Exception $e ) {
			// perhaps factory() failed to load the specified Adapter class
			print $e ;
		}
	}
	public function toXml ( $xml , $ast ) {
		// TODO: write unit test
		$recipe = $xml->createElement ( 'recipe' ) ;
		$recipe->setAttribute ( 'id', $this->id ) ;
		$recipe->setAttribute ( 'member', $this->memberId ) ;
		$recipe->setAttribute ( 'glass', $this->glassId ) ;
		$recipe->setAttribute ( 'cocktail', $this->cocktailId ) ;
		$this->getGlass()->toXml ( $xml, $recipe ) ;
		$recipe->setAttribute ( 'name', $this->name ) ;
		$recipe->setAttribute ( 'price', 2 ) ; // TODO: preis generieren oder so
		$recipe->setAttribute ( 'rating', $this->getRating()) ;
		$recipe->setAttribute ( 'instruction', $this->instruction ) ;
		$recipe->setAttribute ( 'description', $this->description ) ;

		$components = $xml->createElement ( 'components' ) ;
		if (is_array ( $_components = $this->getComponents())) {

			foreach ( $_components as $_component) {
				$_component->toXml ( $xml, $components ) ;
			}

		}
		$recipe->appendChild ( $components );

		$recipe->setAttribute ('source', $this->source);
		$recipe->setAttribute ('workMin', $this->workMin);
		$recipe->setAttribute ('difficulty', $this->difficulty);
		$recipe->setAttribute ('isOriginal', $this->isOriginal);
		$recipe->setAttribute ('isAlcoholic', $this->isAlcoholic);
		$recipe->setAttribute ('alcoholLevel', $this->alcoholLevel);
		$recipe->setAttribute ('caloriesKcal', $this->caloriesKcal);
		$recipe->setAttribute ('volumeCl', $this->volumeCl) ;
		$categories = $xml->createElement ( 'categories' ) ;

		if (is_array ( $_categories = $this->getCategories() )) {

			foreach ( $_categories as $_category ) {
				$_category->toXml ( $xml, $categories ) ;
			}
		}

		$recipe->appendChild ( $categories ) ;

		$photos = $xml->createElement ( 'photos' ) ;
		if (is_array ( $_photos = $this->getPhotos() )) {
			foreach ( $_photos as $_photo ) {
				$_photo->toXml ( $xml, $photos ) ;
			}
		}
		$recipe->appendChild ( $photos ) ;


		$videos = $xml->createElement ( 'videos' ) ;
		if (is_array ( $_videos = $this->getVideos())) {
			foreach ( $_videos as $_video ) {
				$_video->toxml ( $xml, $videos ) ;
			}
		}
		$recipe->appendChild ( $videos ) ;

		$tags = $xml->createElement ( 'tags' ) ;
		if (is_array ($_tags = $this->getTags())) {
			foreach ( $_tags as $_tag ) {
				$_tag->toxml ( $xml, $tags ) ;
			}
		}
		$tags->setAttribute ( 'anzahl', count ( $_tags ) ) ;
		$recipe->appendChild ( $tags ) ;

		$ast->appendchild ( $recipe ) ;
	}

	/**
	 * saves the object persistent into the databse
	 *
	 * @return mixed at update true, at insert int (recipeId)
	 * @tested
	 */
	public function save () {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe');
		if ($this->id) {
			// update recipe
			//var_dump($this->dataBaseRepresentation ());
			$table->update ( $this->dataBaseRepresentation (), 'id = ' . $this->id ) ;
			// update components
			/*$components = $this->getComponents();
			 if(is_array($components)){
				foreach ($components as $component) {
				$component->recipe = $this->id;
				$component->save();
				}
				}*/
			$return = true ;
		} else {
			// insert new recipe
			$this->id = $table->insert ( $this->dataBaseRepresentation () ) ;
			// insert components for recipe
			/*if(is_array($this->_components)){
				foreach ($this->_components as $component) {
				$component->recipeId = $this->id;
				$component->save();
				}
				} else {
				print 'no components!';
				}*/
			$return = $this->id ;
		}
		// abhängige Objekte speichern
		//$this->photos->save();
		//$this->videos->save();

		//$this->tags->save();
		// Erfolg zurückgeben
		return $return ;
	}

	/**
	 *
	 * @tested
	 */
	public function delete (){
		$recipeTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipe');
		/*$components = $this->getComponents();
		 if(is_array($components)){
			foreach ($components as $component){
			$component->delete();
			}
			}*/
		$recipeTable->delete('id='.$this->id);
		CbFactory::destroy('Recipe',$this->id);
		unset($this);
	}


	/**
	 * gibt ein Array zurück um die Daten in eine table zu speichern
	 *
	 * @return array
	 */
	public function dataBaseRepresentation () {
		$array [ 'cocktail' ] = $this->cocktailId;
		$array [ 'member' ] = $this->memberId;
		$array [ 'glass' ] = $this->glassId ;
		$array [ 'name' ] = $this->name ;
		$array [ 'instruction' ] = $this->instruction ;
		$array [ 'source' ] = $this->source ;
		$array [ 'workMin' ] = $this->workMin ;
		$array [ 'difficulty' ] = $this->difficulty ;
		$array [ 'description' ] = $this->description ;
		$array [ 'isOriginal' ] = $this->isOriginal ;
		$array [ 'isAlcoholic' ] = $this->isAlcoholic ;
		$array [ 'ratingsSum' ] = $this->ratingsSum ;
		$array [ 'ratingsCount' ] = $this->ratingsCount ;
		return $array ;
	}

}
?>