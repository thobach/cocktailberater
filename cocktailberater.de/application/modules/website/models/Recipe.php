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
	private $isAlcoholic; // calculated, but from DB
	private $alcoholLevel; // calculated
	private $averagePrice; // calculated
	private $caloriesKcal; // calculated
	private $volumeCl; // calculated
	private $ratingsSum; // calculated
	private $ratingsCount; // calculated
	private $insertDate;
	private $updateDate;

	// associations
	private $_cocktail;
	private $_member;
	private $_glass;
	private $_views;


	// supporting variables
	private $_components;
	private $_alternatives;
	private static $_recipes;
	private static $_avgPrices;
	private static $_avgCalories;

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
				$this->_cocktail = Website_Model_CbFactory::factory('Website_Model_Cocktail', $this->cocktailId);
			} elseif($name=='_member' && !is_a($this->_member,'Member')){
				$this->_member = Website_Model_CbFactory::factory('Website_Model_Member', $this->memberId);
			} elseif($name=='_glass' && !is_a($this->_glass,'Glass')){
				$this->_glass = Website_Model_CbFactory::factory('Website_Model_Glass', $this->glassId);
			}
			// calculated attributes are lazy loaded
			elseif($name=='alcoholLevel' && $this->alcoholLevel === NULL){
				$this->alcoholLevel = $this->getAlcoholLevel();
			} elseif($name=='caloriesKcal' && $this->caloriesKcal === NULL){
				$this->caloriesKcal = $this->getCaloriesKcal();
			} elseif($name=='averagePrice' && $this->averagePrice === NULL){
				$this->averagePrice = $this->getAveragePrice();
			} elseif($name=='volumeCl' && $this->volumeCl === NULL){
				$this->volumeCl = $this->getVolumeCl();
			} elseif($name=='ratingsSum' && $this->ratingsSum === NULL){
				$this->ratingsSum = 0;
				foreach($this->getRatings() as /* @var $rating Website_Model_Rating */$rating){
					$this->ratingsSum += $rating->mark;
				}
			} elseif($name=='ratingsCount' && $this->ratingsCount === NULL){
				$this->ratingsCount = count($this->getRatings());
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
	 * @return Website_Model_Member
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
	 * @return Website_Model_Cocktail
	 */
	public function getCocktail()
	{
		if(!$this->_cocktail){
			$this->_cocktail = Website_Model_CbFactory::factory('Website_Model_Cocktail',$this->cocktailId);
		}
		return $this->_cocktail;
	}

	/**
	 * resolve Association and return an object of Glass
	 *
	 * @return Website_Model_Glass
	 */
	public function getGlass()
	{
		if(!$this->_glass){
			$this->_glass = Website_Model_CbFactory::factory('Website_Model_Glass',$this->glassId);
		}
		return $this->_glass;
	}

	public static function getRecipe($id)
	{
		if(!isset(self::$_recipes[$id])){
			self::$_recipes[$id] = Website_Model_CbFactory::factory('Website_Model_Recipe', $id);
		}
		return self::$_recipes[$id];
	}


	public function getRating(){
		return round(@($this->__get('ratingsSum')/$this->__get('ratingsCount')),2);
	}

	/**
	 * @return array[int]Website_Model_Rating
	 */
	public function getRatings(){
		$ratingTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'rating');
		$ratings = $ratingTable->fetchAll('recipe='.$this->id);
		$ratingArray = array();
		foreach($ratings as $rating){
			$ratingArray[] = Website_Model_CbFactory::factory('Website_Model_Rating', $rating->id);
		}
		return $ratingArray;
	}

	/**
	 * checks whether the recipe exists in DB
	 * the check is by name and id
	 *
	 * @param String $id eather the integer id or unique name
	 * @return booloean | int False or integer ID for recipe
	 */
	public static function exists($id) {
		$recipeTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipe') ;
		if($id>0){
			$recipe = $recipeTable->fetchRow('id='.$id);
		} else {
			$recipe = $recipeTable->fetchRow($recipeTable->select()
			->where('name = ?',rawurldecode(str_replace(array('_'),array(' '),$id))));
		}
		if ($recipe) {
			return $recipe->id;
		} else {
			return false;
		}
	}

	public function getUniqueName(){
		return rawurlencode(str_replace(array(' '),array('_'),$this->name));
	}

	/**
	 *
	 * @param int|NULL $id
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
			$this->isAlcoholic = $recipe->isAlcoholic;
			//$this->alcoholLevel = $this->getAlcoholLevel();
			//$this->caloriesKcal = $this->getCaloriesKcal();
			//$this->volumeCl = $this->getVolumeCl();
			//$this->ratingsCount = $recipe->ratingsCount;
			//$this->ratingsSum = $recipe->ratingsSum;
			$this->insertDate =  $recipe->insertDate;
			$this->updateDate =  $recipe->updateDate;


		}

	}

	/**
	 * Returns all recipes where the recipe title contains the given string.
	 *
	 * @param $name search string
	 * @param $limit maximal number of recipes (nullable)
	 * @param $filter array with filters (nullable)
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

		$orderBySql = 'name';

		$db = Zend_Db_Table::getDefaultAdapter();

		// evaluate filter
		$validFilters = array('with-image','alcoholic','non-alcoholic','top10');
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
			} elseif($filter=='alcoholic'){
				$whereSQL .= ' AND recipe.isAlcoholic=1 ';
			} elseif($filter=='non-alcoholic'){
				$whereSQL .= ' AND recipe.isAlcoholic=0 ';
			} elseif($filter=='top10'){
				$orderBySql = '(ratingsSum/ratingsCount) desc';
				$limitSql = ' LIMIT 10';
			}
		}
		// look for perfect match
		$result = $db->fetchAll ( 'SELECT id,name FROM recipe'.$fromSQL.' WHERE name LIKE \''.mysql_escape_string($name).'%\''.$whereSQL.' GROUP BY name ORDER BY  '.$orderBySql.' '.$limitSql);
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

	public function getCaloriesKcal() {
		// check if data is already calculated
		if(Website_Model_Recipe::$_avgCalories[$this->id] === NULL){
			// load cache module from registry
			$cache = Zend_Registry::get('cache');
			// internal cache not yet created
			if(!is_array(Website_Model_Recipe::$_avgCalories)){
				// load calories information from cache
				Website_Model_Recipe::$_avgCalories = $cache->load('averageRecipeCalories');
			}
			// continue if cache does not contain the price information for this recipe
			if(Website_Model_Recipe::$_avgCalories[$this->id] === NULL) {
				$components = $this->getComponents();
				$totalCaloriesKcal = 0;
				if (is_array ( $components )) {
					foreach ( $components as $component ) {
						/* @var $component Website_Model_Component */
						$totalCaloriesKcal += $component->getCaloriesKcal();
					}
				}
				Website_Model_Recipe::$_avgCalories[$this->id] = round($totalCaloriesKcal,0);
				// persist new data in cache
				$cache->save(Website_Model_Recipe::$_avgCalories,'averageRecipeCalories',array('model'));
			}
		}
		return Website_Model_Recipe::$_avgCalories[$this->id];
	}

	public function getVolumeCl() {
		$components = $this->getComponents();
		$volumeCl=0;
		if (is_array ( $components )) {
			foreach ($components as /*@var $component Website_Model_Component*/ $component) {
				if($component->getAmountInLiter()!==null){
					$vol = $component->getAmountInLiter()*100;
					$volumeCl += $vol;
				}
			}
		}
		return round($volumeCl);
	}

	public function getAlcoholLevel() {
		if(!$this->alcoholLevel){
			$components = $this->getComponents();
			$alcoholLevel=0;
			$volume=0;
			if (is_array ( $components )) {
				foreach($components as $component){
					if($component->getIngredient()->aggregation == "liquid"){
						$db = Zend_Db_Table::getDefaultAdapter();
						$alcoholLevelSql = $db->fetchRow (
							'SELECT AVG(alcoholLevel) AS averageAlcoholLevel '.
							'FROM `product` '.
							'WHERE ingredient='.$component->ingredientId);
						if($alcoholLevelSql['averageAlcoholLevel']!="NULL"){
							$alcoholLevel += $alcoholLevelSql['averageAlcoholLevel'] / 100 * $component->amount ;
						}
						$volume += $component->amount;
					}
				}
				$this->alcoholLevel = $alcoholLevel = round($alcoholLevel/$volume*100,1);
			}
		}
		return $this->alcoholLevel;
	}

	/**
	 * returns alternative recipes, from the same cocktail
	 *
	 * @return Website_Model_Recipe[]
	 */
	public function getAlternatives(){
		if(!$this->_alternatives){
			$this->_alternatives = array();
			if(is_array($this->getCocktail()->getRecipes())){
				foreach($this->getCocktail()->getRecipes() as $recipe){
					if($recipe->id != $this->id){
						$this->_alternatives[] = $recipe;
					}
				}
			}
		}
		return $this->_alternatives;
	}

	public function isAlcoholic() {
		$alcoholLevel = $this->getAlcoholLevel();
		if($alcoholLevel > 0){
			return true;
		}
		return false;
	}

	public function getPhotos($max = NULL) {
		return Website_Model_Photo::photosByRecipeId ($this->id, $max);
	}

	public function getVideos() {
		return Website_Model_Video::videosByRecipeId ($this->id);
	}

	/**
	 * Returns the average price (e.g. 1.32) of the recipe calculated by each components average price
	 *
	 * @return double price
	 */
	public function getAveragePrice() {
		// check if data is already calculated
		if(Website_Model_Recipe::$_avgPrices[$this->id] === NULL){
			// load cache module from registry
			$cache = Zend_Registry::get('cache');
			// internal cache not yet created
			if(!is_array(Website_Model_Recipe::$_avgPrices)){
				// load price information from cache
				Website_Model_Recipe::$_avgPrices = $cache->load('averageRecipePrices');
			}
			// continue if cache does not contain the price information for this recipe
			if(Website_Model_Recipe::$_avgPrices[$this->id] === NULL) {
				$components = $this->getComponents();
				$avgPrice = 0.0;
				if (is_array ( $components )) {
					foreach ( $components as $component ) {
						/* @var $component Website_Model_Component */
						$avgPrice += $component->getAveragePrice();
					}
				}
				Website_Model_Recipe::$_avgPrices[$this->id] = round($avgPrice,2);
				// persist new data in cache
				$cache->save(Website_Model_Recipe::$_avgPrices,'averageRecipePrices',array('model'));
			}
		}
		return Website_Model_Recipe::$_avgPrices[$this->id];
	}

	/**
	 * Returns all compoments with ingredient, amount & unit of a recipe
	 *
	 * @return Website_Model_Component[]
	 */
	public function getComponents(){
		if(!$this->id){
			throw new Website_Model_RecipeException('Id_Missing');
		}
		if(!$this->_components){
			$this->_components = Website_Model_Component::componentsByRecipeId ($this->id);
		}
		return $this->_components;
	}

	public function addComponent(Website_Model_Component $component) {
		$this->_components[] = $component;
	}

	/**
	 * increases the view counter
	 */
	public function addView($format) {
		$statistic = Website_Model_CbFactory::factory('Website_Model_Statistic',
			Website_Model_Statistic::RESOURCE_RECIPE,$this->id,$format) ;
		$statistic->addView();
	}

	/**
	 * returns the statistic objects of this reciep
	 * @return Website_Model_Statistic
	 */
	public function getStatistics() {
		return Website_Model_Statistic::statisticsByRecipeId($this->id);
	}

	public function addRecipeCategory ($recipeCategoryId){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe2recipecategory');
		$data['recipe'] = $this->id;
		$data['recipeCategory'] = $recipeCategoryId;
		$table->insert($data);
	}

	public function getComments() {
		return Website_Model_Comment::commentsByRecipeId ($this->id);
	}

	public function getTags() {
		return Website_Model_Tag::tagsByRecipeId ($this->id);
	}

	public static function getRecipesByTag($tag,$max = NULL){
		$db = Zend_Db_Table::getDefaultAdapter();
		if($max>0){
			$max = ' LIMIT '.$max;
		}
		$tags = $db->fetchAll ( 'SELECT recipe FROM tag WHERE name=\''.$tag.'\' GROUP BY recipe'.$max);
		foreach ( $tags as $tag ) {
			$recipeArray [] = Website_Model_CbFactory::factory('Website_Model_Recipe', $tag['recipe'] ) ;
		}
		return $recipeArray ;
	}

	private function getCategories()
	{
		return Website_Model_RecipeCategory::categoryByRecipeId ($this->id);

	}

	public function recipesByCocktailId ( $idcocktail ) {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe');
		try {
			$recipes = $table->fetchAll ( 'cocktail=' . $idcocktail) ; }
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
		// load cache from registry
		$cache = Zend_Registry::get('cache');
		// see if a cache already exists:
		if(!$recipeArray = $cache->load('recipeList')) {

			$recipeTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe');
			$recipes = $recipeTable->fetchAll();

			foreach ($recipes as $recipe) {
				$recipeArray[] = Website_Model_CbFactory::factory('Website_Model_Recipe',$recipe->id);
			}
			$cache->save($recipeArray,'recipeList',array('model'));
		}
		return $recipeArray;

	}
	public function photoRecipe ( $idrecipe ) {
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

	/**
	 * Returns an array with all elements of a feed entry
	 *
	 * @return array
	 */
	public function toFeedEntry(){
		$view = new Zend_View();

		$date = new Zend_Date($this->updateDate,Zend_Date::ISO_8601);

		$categories = array();
		if(is_array($this->getTags())){
			foreach($this->getTags() as $tag){
				$categories[] = array(
				'term'	=>	$tag->name,
				'scheme'=>	$view->url(array('module'=>'website',
				'controller'=>'recipe','action'=>'index','index'=>'index',
				'search_type'=>'tag','search'=>$tag->name,'format'=>'')),'rest',true);
			}
		}

		$entry = array(
		'title'       	=> 	$this->name,
		'link'        	=> 	$view->url(array('module'=>'website',
							'controller'=>'recipe','action'=>'get',
							'id'=>$this->getUniqueName()),'rest',true),
		'description'	=>	$this->name.
							' Cocktailrezept mit Zusatzinformationen wie '.
							'Videos, Fotos, Kosten, Kalorien, '.
							'Alkoholgehalt, etc.',
		'guid'			=>	$view->url(array('module'=>'website',
							'controller'=>'recipe','action'=>'get',
							'id'=>$this->getUniqueName()),'rest',true),
		'content'		=> 	"<p><strong>Beschreibung:</strong></p>".
							"<p>".$this->description."</p>".
							"<p><strong>Zutaten:</strong></p>".
							"<ul>".$components."</ul>".
							"<p><strong>Anweisung:</strong></p>".
							"<p>".$this->instruction."</p>".
							"<p><strong>Zusatzinformationen:</strong></p>".
							"<ul>".
							"<li>Glas: ".$this->getGlass()->name."</li>".
							"<li>Volumen: ".$this->getVolumeCl()." cl</li>".
							"<li>Schwierigkeitsgrad: ".$this->difficulty."</li>".
							"<li>Zeitaufwand: ".$this->workMin." min</li>".
							"<li>Kalorien: ".$this->getCaloriesKcal()." kcal</li>".
							"<li>Alkoholgehalt: ".number_format($this->getAlcoholLevel(),0,',','.')." %</li>".
							"<li>Kosten: ".number_format($this->getAveragePrice(),2,',','.')." €</li>".
							"</ul>",
		'category'		=>	$categories,
		'lastUpdate'	=> 	$date->getTimestamp(),
		);
		return $entry;
	}

	/**
	 * Appends its XML representation to the DOMNode of the DOMDocument
	 *
	 * @param DOMDocument $xml
	 * @param DOMNode $ast
	 * @return void
	 */
	public function toXml (DOMDocument $xml ,DOMNode  $ast ) {
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
		$array [ 'views' ] = $this->views ;
		$array [ 'ratingsSum' ] = $this->ratingsSum ;
		$array [ 'ratingsCount' ] = $this->ratingsCount ;
		return $array ;
	}

}
?>