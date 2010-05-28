<?php
/**
 * Ingredient class
 *
 */
class Website_Model_Ingredient {

	// attributes
	private $id;
	private $name;
	private $description;
	private $aggregation;
	private $aliasName;
	private $pieces_in_whole;
	private $insertDate;
	private $updateDate;

	// calculated attributes
	private $averageAlcoholLevel;
	private $averageDensityGramsPerCm3;
	private $averageCaloriesKcal;
	private $averagePricePerKilogram;
	private $averagePricePerPiece;
	private $averagePricePerWhole;
	private $averageVolumeLitre;
	private $averageWeightGram;
	private $ingredientCategoryArray;
	private $mostUsedUnit;
	private $productArray;

	// supporting variables
	private static $_ingredients;
	private static $_numberOfRecipes;
	private static $_averageIngredientPricePerLitre;
	private static $_averageIngredientPricePerKilogram;
	private $recipes;

	// constants
	const SOLID = 'solid';
	const LIQUID = 'liquid';
	const GAS = 'gas';
	const GEL = 'gel';

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

	/* depreciated
	 public static function getIngredient($id)
	 {
		if(!Ingredient::$_ingredients[$id]){
		Ingredient::$_ingredients[$id] = CbFactory::factory('Ingredient', $id);
		}
		return Ingredient::$_ingredients[$id];
		}*/

	/**
	 * Ingredient constructor returns a Ingredient object by an id, or an empty one
	 *
	 * @param unknown_type $ingredientId
	 * @return Ingredient
	 * @tested
	 */
	public function __construct ($ingredientId = NULL)
	{
		if(!empty($ingredientId)){
			$ingredientTable 	= Website_Model_CbFactory::factory('Website_Model_MysqlTable','ingredient');
			$ingredient 		= $ingredientTable->fetchRow('id='.$ingredientId);
			// if ingredient does not exist
			if(!$ingredient){
				throw new Website_Model_IngredientException('Id_Wrong');
			}

			// attributes
			$this->id					= $ingredient['id'];
			$this->name					= $ingredient['name'];
			$this->description			= $ingredient['description'];
			$this->aggregation			= $ingredient['aggregation'];
			$this->aliasName			= $ingredient['aliasName'];
			$this->pieces_in_whole		= $ingredient['pieces_in_whole'];
			$this->insertDate			= $ingredient['insertDate'];
			$this->updateDate			= $ingredient['updateDate'];
		}
	}

	/**
	 * static function to compare objects by ingredient name
	 *
	 * @param Website_Model_Ingredient $a
	 * @param Website_Model_Ingredient $b
	 * @return int
	 */
	public static function cmp_obj(Website_Model_Ingredient $a, Website_Model_Ingredient $b)
	{
		$al = strtolower($a->name);
		$bl = strtolower($b->name);
		if ($al == $bl) {
			return 0;
		}
		return ($al > $bl) ? +1 : -1;
	}

	/**
	 * returns an Array of ingredient objects by a search string
	 *
	 * @param string $search
	 * @param int $limit count of results, default 100
	 * @return array[int]Website_Model_Ingredient
	 */
	static function listIngredients ( $search , $limit = 200) {
		$db = Zend_Db_Table::getDefaultAdapter();
		// wird für suggest benutzt
		$result = $db->fetchAll ( 'SELECT id, name
		FROM ingredient
		WHERE (name LIKE ? OR aliasName LIKE ?)
		GROUP BY name
		ORDER BY name 
		LIMIT ' . $limit, array (str_replace ( '\'', '\\\'', $search ) . '%', str_replace ( '\'', '\\\'', $search ) . '%' ) ) ; // evtl. das noch davor: '%'.
		// use ORDER BY name to avoid entries with same name
		$ingredientArray = array();
		foreach ($result as $ingredient) {
			$ingredientArray[] = Website_Model_CbFactory::factory('Website_Model_Ingredient',$ingredient['id']);
		}
		return $ingredientArray;
	}

	/**
	 * returns an array of IngredientCategory objects
	 *
	 * @return array[int]Website_Model_IngredientCategory
	 */
	public function getIngredientCategories() {
		if($this->ingredientCategoryArray === NULL){
			$this->ingredientCategoryArray = Website_Model_IngredientCategory::categoriesByIngredientId( $this->id );
		}
		return $this->ingredientCategoryArray;
	}

	/**
	 * returns an array of Product objects
	 *
	 * @return Website_Model_Product[]
	 */
	public function getProducts() {
		if($this->productArray === NULL){
			$this->productArray = Website_Model_Product::productsByIngredientId( $this->id );
		}
		return $this->productArray;
	}

	/**
	 * returns an array of Recipe objects
	 *
	 * @return array Website_Model_Recipe
	 */
	public function getRecipes(){
		if(!$this->recipes){
			// load cache from registry
			$cache = Zend_Registry::get('cache');
			// see if product - list is already in cache
			if(!$recipes = $cache->load('recipesByIngredientId'.$this->id)) {
				$recipes = array();
				$componentsArray = Website_Model_Component::componentsByIngredientId($this->id);
				if(is_array($componentsArray)){
					foreach ($componentsArray as $component){
						$recipes[$component->recipeId] = Website_Model_CbFactory::factory('Website_Model_Recipe',$component->recipeId);
					}
				}
				$cache->save($recipes,'recipesByIngredientId'.$this->id,array('model'));
			}
			$this->recipes = $recipes;
		}
		return $this->recipes;
	}

	/**
	 * returns the number of Recipes that use this ingredient
	 *
	 * @return	int
	 */
	public function getNumberOfRecipes(){
		if(!$this->recipes){
			// check if data is already calculated
			if(Website_Model_Ingredient::$_numberOfRecipes[$this->id] === NULL){
				// load cache module from registry
				$cache = Zend_Registry::get('cache');
				// internal cache not yet created
				if(!is_array(Website_Model_Ingredient::$_numberOfRecipes)){
					// load calories information from cache
					Website_Model_Ingredient::$_numberOfRecipes = $cache->load('ingredientNumberOfRecipes');
				}
				// continue if cache does not contain the price information for this recipe
				if(Website_Model_Ingredient::$_numberOfRecipes[$this->id] === NULL) {
					$componentsArray = Website_Model_Component::componentsByIngredientId($this->id);
					Website_Model_Ingredient::$_numberOfRecipes[$this->id] = count($componentsArray);
					// persist new data in cache
					$cache->save(Website_Model_Ingredient::$_numberOfRecipes,'ingredientNumberOfRecipes',array('model'));
				}
			}
		} else {
			Website_Model_Ingredient::$_numberOfRecipes[$this->id] = count($this->recipes);
			// load cache module from registry
			$cache = Zend_Registry::get('cache');
			// persist new data in cache
			$cache->save(Website_Model_Ingredient::$_numberOfRecipes,'ingredientNumberOfRecipes',array('model'));
		}
		return Website_Model_Ingredient::$_numberOfRecipes[$this->id];
	}

	/**
	 * returns the average density in g/cm3 of the product
	 *
	 * @return int|null density in g/cm3
	 * @tested
	 */
	public function getAverageDensityGramsPerCm3(){
		if($this->averageDensityGramsPerCm3 === NULL){
			$db = Zend_Db_Table::getDefaultAdapter();
			$res = $db->fetchRow ( 'SELECT
				AVG (densityGramsPerCm3) AS averageDensityGramsPerCm3 
				FROM `product` 
				WHERE ingredient='.$this->id);
			if(!$res['averageDensityGramsPerCm3']){
				$this->averageDensityGramsPerCm3 = round(1,3);
			} else {
				$this->averageDensityGramsPerCm3 = round($res['averageDensityGramsPerCm3'],3);
			}
		}
		return $this->averageDensityGramsPerCm3;
	}

	public function getUniqueName() {
		return rawurlencode(str_replace(array(' '),array('_'),$this->name));
	}

	/**
	 * returns the average kcal of the product per given unit
	 *
	 * @param string Unit like Component::PIECE or Component::CENTILITRE
	 * @return int|null Kcall
	 */
	public function getAverageCaloriesKcal($unit = Website_Model_Component::LITRE) {
		if($this->averageCaloriesKcal[$unit] === NULL){
			$db = Zend_Db_Table::getDefaultAdapter();
			if($unit==Website_Model_Component::PIECE){
				$res = $db->fetchRow ( 'SELECT
					CASE unit 
						WHEN \'g\' THEN AVG(caloriesKcal/size)*AVG(size)/'.$this->pieces_in_whole.'
						WHEN \'kg\' THEN AVG(caloriesKcal/size)*AVG(size)/'.$this->pieces_in_whole.'
						ELSE NULL
					END	
					averageCaloriesKcal
					FROM `product` 
					WHERE ingredient='.$this->id.' AND caloriesKcal IS NOT null');
			} else if($unit==Website_Model_Component::WHOLE){
				$res = $db->fetchRow ( 'SELECT
					AVG(caloriesKcal/size)*AVG(size) AS averageCaloriesKcal
					FROM `product` 
					WHERE ingredient='.$this->id.' AND caloriesKcal IS NOT null');
			} else if($unit==Website_Model_Component::TEASPOON){
				$res = $db->fetchRow ( 'SELECT
					CASE unit 
						WHEN \'g\' THEN AVG(caloriesKcal/size/densityGramsPerCm3)*5
						WHEN \'kg\' THEN AVG(caloriesKcal/size/densityGramsPerCm3/1000)*5
						WHEN \'ml\' THEN AVG(caloriesKcal/size)*5
						WHEN \'cl\' THEN AVG(caloriesKcal/size/10)*5
						WHEN \'l\' THEN AVG(caloriesKcal/size/1000)*5
						ELSE NULL
					END	
					averageCaloriesKcal
					FROM `product` 
					WHERE ingredient='.$this->id.' AND caloriesKcal IS NOT null');
			} else {
				$res = $db->fetchRow ( 'SELECT
					CASE unit 
						WHEN \'l\' THEN AVG(caloriesKcal/size) 
						WHEN \'cl\' THEN AVG(caloriesKcal/size*100) 
						WHEN \'ml\' THEN AVG(caloriesKcal/size*1000)
						WHEN \'g\' THEN AVG((1000*caloriesKcal)/(size/CASE densityGramsPerCm3 WHEN NOT NULL THEN densityGramsPerCm3 ELSE 1 END))
						WHEN \'kg\' THEN AVG((1000*caloriesKcal)/(size*1000*CASE densityGramsPerCm3 WHEN NOT NULL THEN densityGramsPerCm3 ELSE 1 END))
						WHEN \'fl. oz\' THEN AVG(caloriesKcal/size*29.5735296*1000)
						ELSE NULL
					END	
					averageCaloriesKcal
					FROM `product` 
					WHERE ingredient='.$this->id.' AND caloriesKcal IS NOT null');
			}
			if($res['averageCaloriesKcal'] === NULL){
				$this->averageCaloriesKcal[$unit] = NULL;
			} else{
				$this->averageCaloriesKcal[$unit] = round($res['averageCaloriesKcal']);
			}
		}
		return $this->averageCaloriesKcal[$unit];
	}

	/**
	 * returns the average alcohol level of the product
	 *
	 * @return int|null alcohol level
	 */
	public function getAverageAlcoholLevel(){
		if($this->averageAlcoholLevel===NULL){
			$db = Zend_Db_Table::getDefaultAdapter();
			$res = $db->fetchRow ( 'SELECT
				AVG(alcoholLevel) AS averageAlcoholLevel
				FROM `product` 
				WHERE ingredient='.$this->id.' AND alcoholLevel IS NOT null');
			if($res['averageAlcoholLevel']==null){
				$this->averageAlcoholLevel = null;
			} else{
				$this->averageAlcoholLevel = round($res['averageAlcoholLevel']);
			}
		}
		return $this->averageAlcoholLevel;

	}

	/**
	 * returns the average price per liter of all products
	 *
	 * @return float average price
	 */
	public function getAveragePricePerLitre(){
		// check if data is already calculated
		if(Website_Model_Ingredient::$_averageIngredientPricePerLitre[$this->id] === NULL){
			// load cache module from registry
			$cache = Zend_Registry::get('cache');
			// internal cache not yet created
			if(!is_array(Website_Model_Ingredient::$_averageIngredientPricePerLitre)){
				// load calories information from cache
				Website_Model_Ingredient::$_averageIngredientPricePerLitre = $cache->load('averageIngredientPricePerLitre');
			}
			// continue if cache does not contain the price information for this recipe
			if(Website_Model_Ingredient::$_averageIngredientPricePerLitre[$this->id] === NULL) {
				$products = $this->getProducts();
				$avgCount = 0;
				if(is_array($products)){
					foreach($products as $product){
						// cached
						/* @var $product Website_Model_Product */
						$price = $product->getAveragePrice();
						if($product->unit == 'l' && $price>0){
							$avgSum += $price / $product->size;
							$avgCount++;
						} else if ($product->unit == 'g' && $price > 0 && $product->densityGramsPerCm3 > 0){
							$avgSum += $price / $product->size / $product->densityGramsPerCm3 * 1000;
							$avgCount++;
						} else if ($product->unit == 'kg' && $price > 0 && $product->densityGramsPerCm3 > 0){
							$avgSum += $price / $product->size / $product->densityGramsPerCm3;
							$avgCount++;
						}
					}
				}
				if($avgCount>0){
					Website_Model_Ingredient::$_averageIngredientPricePerLitre[$this->id] = round($avgSum/$avgCount,2);
				} else {
					Website_Model_Ingredient::$_averageIngredientPricePerLitre[$this->id] = -1;
				}
				// persist new data in cache
				$cache->save(Website_Model_Ingredient::$_averageIngredientPricePerLitre,'averageIngredientPricePerLitre',array('model'));
			}
		}
		if(Website_Model_Ingredient::$_averageIngredientPricePerLitre[$this->id]== -1){
			$price = NULL;
		} else {
			$price = Website_Model_Ingredient::$_averageIngredientPricePerLitre[$this->id];
		}
		return $price;
	}

	/**
	 * returns the average price per kilogram of all products
	 *
	 * @return float average price
	 */
	public function getAveragePricePerKilogram(){
		// check if data is already calculated
		if(Website_Model_Ingredient::$_averageIngredientPricePerKilogram[$this->id] === NULL){
			// load cache module from registry
			$cache = Zend_Registry::get('cache');
			// internal cache not yet created
			if(!is_array(Website_Model_Ingredient::$_averageIngredientPricePerKilogram)){
				// load calories information from cache
				Website_Model_Ingredient::$_averageIngredientPricePerKilogram = $cache->load('averageIngredientPricePerKilogram');
			}
			// continue if cache does not contain the price information for this recipe
			if(Website_Model_Ingredient::$_averageIngredientPricePerKilogram[$this->id] === NULL) {
			$products = $this->getProducts();
			$avgCount = 0;
			foreach($products as $product){
				$price = $product->getAveragePrice();
				if($product->unit == 'g' && $price>0){
					$avgSum += $price * 1000 / $product->size;
					$avgCount++;
				} else if($product->unit == 'kg' && $price>0){
					$avgSum += $price / $product->size;
					$avgCount++;
				}
			}
			if($avgCount>0){
					Website_Model_Ingredient::$_averageIngredientPricePerKilogram[$this->id] = round($avgSum/$avgCount,2);
				} else {
					Website_Model_Ingredient::$_averageIngredientPricePerKilogram[$this->id] = -1;
				}
				// persist new data in cache
				$cache->save(Website_Model_Ingredient::$_averageIngredientPricePerKilogram,'averageIngredientPricePerKilogram',array('model'));
			}
		}
		if(Website_Model_Ingredient::$_averageIngredientPricePerKilogram[$this->id]== -1){
			$price = NULL;
		} else {
			$price = Website_Model_Ingredient::$_averageIngredientPricePerKilogram[$this->id];
		}
		return $price;
	}

	/**
	 * returns the average price per piece of all products
	 *
	 * @return float average price
	 */
	public function getAveragePricePerPiece(){
		if($this->averagePricePerPiece === NULL){
			$products = $this->getProducts();
			$avgCount = 0;
			foreach($products as $product){
				$price = $product->getAveragePrice();
				if($price>0){
					$avgSum += $price / $product->getIngredient()->pieces_in_whole;
					$avgCount++;
					//print 'preis: '.$price.'<br />';
				}
			}
			if($avgCount>0){
				$this->averagePricePerPiece = round($avgSum/$avgCount,2);
			} else {
				$this->averagePricePerPiece = NULL;
			}
		}
		return $this->averagePricePerPiece;
	}

	/**
	 * returns the average price per whole of all products
	 *
	 * @return float average price
	 */
	public function getAveragePricePerWhole(){
		if($this->averagePricePerWhole === NULL){
			$products = $this->getProducts();
			$avgCount = 0;
			foreach($products as $product){
				$price = $product->getAveragePrice();
				if($product->unit == 'l' && $price>0){
					$avgSum += $price / $product->size;
					$avgCount++;
					//print 'preis: '.$price.'<br />';
				}
			}
			if($avgCount>0){
				$this->averagePricePerWhole = round($avgSum/$avgCount,2);
			} else {
				$this->averagePricePerWhole = NULL;
			}
		}
		return $this->averagePricePerWhole;
	}

	/**
	 * returns the average weight in gram of the product
	 *
	 * @return int|null weight in gram
	 */
	public function getAverageWeightGram(){
		if($this->averageWeightGram === NULL){
			// TODO: not only accept GRAM as unit here, also do some conversion
			$db = Zend_Db_Table::getDefaultAdapter();
			$res = $db->fetchRow ( 'SELECT
			AVG(size) AS averageSize
			FROM `product` 
			WHERE unit=\''.Website_Model_Component::GRAM.'\' AND ingredient='.$this->id);
			if($res['averageSize']){
				$this->averageWeightGram = round($res['averageSize']);
			} else {
				$this->averageWeightGram = NULL;
			}
		}
		return $this->averageWeightGram;
	}

	/**
	 * returns the average volume in Litre of the product
	 *
	 * @return int|null volume in litre
	 * @tested
	 */
	public function getAverageVolumeLitre(){
		if($this->averageVolumeLitre === NULL){
			$db = Zend_Db_Table::getDefaultAdapter();
			$res = $db->fetchRow ( 'SELECT
			AVG(
			if(unit=\'ml\',size/1000,
			if(unit=\'cl\',size/100,
			if(unit=\'l\',size,
			if(unit=\'fl. oz\',size/1000*29.5,\'noVolume\'))))) as sizeInLitre 
			FROM `product` 
			WHERE if(unit=\'ml\',size/1000,
			if(unit=\'cl\',size/100,
			if(unit=\'l\',size,
			if(unit=\'fl. oz\',size/1000*29.5,\'noVolume\')))) !=\'noVolume\'
				AND ingredient='.$this->id.' AND size IS NOT null');
			if($res['sizeInLitre']){
				$this->averageVolumeLitre = round($res['sizeInLitre'],2);
			} else {
				$this->averageVolumeLitre = NULL;
			}
		}
		return $this->averageVolumeLitre;
	}

	/**
	 * returns the most used unit of an Ingredient,
	 * if not unambgiguous only one unit is returned
	 * return string (Unit) or null if no product data available
	 *@tested
	 */
	public function getMostUsedUnit(){
		if($this->mostUsedUnit === NULL){
			$db = Zend_Db_Table::getDefaultAdapter();
			$res = $db->fetchRow ( 'SELECT
			unit 
			FROM `product` 
			WHERE ingredient='.$this->id.' GROUP BY unit ORDER BY count(unit) DESC LIMIT 1');
			if($res){
				$this->mostUsedUnit = $res['unit'];
			} else {
				$this->mostUsedUnit = NULL;
			}
		}
		return $this->mostUsedUnit;
	}

	/**
	 * attaches an IngredientCategory to an Ingredient
	 *@tested
	 */
	public function addIngredientCategory($ingredientCategoryId){
		$db = Zend_Db_Table::getDefaultAdapter();
		$res = $db->query ( 'INSERT INTO ingredient2ingredientcategory
			(ingredientCategory, ingredient)
			VALUES ('.$ingredientCategoryId.','.$this->id.')');
	}

	/**
	 *detaches an IngredientCategory from an Ingredient
	 *@tested
	 */
	public function removeIngredientCategory($ingredientCategoryId){
		$ingredientTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'ingredient2ingredientcategory');
		$ingredientTable->delete('ingredientCategory='.$ingredientCategoryId.' AND ingredient='.$this->id);
	}

	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 * @tested
	 */
	public function toXml ($xml,$ast){
		$zutat = $xml->createElement('ingredient');
		$zutat->setAttribute('id',$this->id);
		$zutat->setAttribute('name',$this->name);
		$zutat->setAttribute('description',$this->description);
		$zutat->setAttribute('aggregation',$this->aggregation);
		$zutat->setAttribute('aliasName',$this->aliasName);
		$zutat->setAttribute('insertDate',$this->insertDate);
		$zutat->setAttribute('updateDate',$this->updateDate);
		$ast->appendchild($zutat);
	}

	/**
	 * saves an object persistent into a database
	 *
	 * @return (boolean | int) if update => true, if insert => recipe and ingredient id
	 * @tested
	 */
	public function save () {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','ingredient');
		if ($this->id) {
			// Update
			$table->update ( $this->dataBaseRepresentation (), 'id = ' . $this->id) ;
			$return = true ;
		} else {
			// Insert new ingredient
			$data = $this->dataBaseRepresentation ();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$return = $this->id = $table->insert ( $data ) ;
		}
		// Erfolg zurückgeben
		return $return ;
	}

	/**
	 *
	 * @tested
	 */
	public function delete (){
		$ingredientTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'ingredient');
		$ingredientTable->delete('id='.$this->id);
		Website_Model_CbFactory::destroy('Ingredient',$this->id);
		unset($this);
	}


	public static function exists($id) {
		$ingredientTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'ingredient') ;
		if($id>0){
			$ingredient = $ingredientTable->fetchRow('id='.$id);
		} else {
			$ingredient = $ingredientTable->fetchRow($ingredientTable->select()
			->where('name = ?',rawurldecode(str_replace(array('_'),array(' '),$id))));
		}
		if ($ingredient) {
			return $ingredient->id;
		} else {
			return false;
		}
	}

	/**
	 * returns an array containing the data to be saved in the database
	 *
	 * @return array
	 */
	public function dataBaseRepresentation () {
		$array [ 'name' ] = $this->name;
		$array [ 'description' ] = $this->description;
		$array [ 'aggregation' ] = $this->aggregation;
		$array [ 'aliasName' ] = $this->aliasName ;
		return $array ;
	}
}
?>