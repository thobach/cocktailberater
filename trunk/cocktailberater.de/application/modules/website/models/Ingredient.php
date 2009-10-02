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
	private $insertDate;
	private $updateDate;
	
	// supporting variables
	private static $_ingredients;
	
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
	 * @return array Ingredient
	 * @tested
	 */
	static function listIngredients ( $search , $limit = 100) {
		$db = Zend_Db_Table::getDefaultAdapter();
		// wird für suggest benutzt
		$result = $db->fetchAll ( 'SELECT id
		FROM ingredient
		WHERE (name LIKE ?)
		ORDER BY name 
		LIMIT ' . $limit, array ( str_replace ( '\'', '\\\'', $search ) . '%' ) ) ; // evtl. das noch davor: '%'.
		$ingredientArray = array();
		foreach ($result as $ingredient) {
			$ingredientArray[] = Website_Model_CbFactory::factory('Website_Model_Ingredient',$ingredient['id']);
		}
		return ($ingredientArray) ;
	}
	
	/**
	 * returns an Array of IngredientCategory objects
	 *
	 * @return array IngredientCategory
	 * @tested
	 */
	public function getIngredientCategories () {
		$ingredientCategoryArray = Website_Model_IngredientCategory::categoriesByIngredientId( $this->id );
		return $ingredientCategoryArray;
	}
	
	/**
	 * returns an Array of Product objects
	 *
	 * @return array Product
	 * @tested
	 */
	public function getProducts () {
		$productArray = Website_Model_Product::productsByIngredientId( $this->id );
		return $productArray;
	}
	
	/**
	 * returns the average density in g/cm3 of the product
	 *
	 * @return int|null density in g/cm3
	 * @tested
	 */	
	public function getAverageDensityGramsPerCm3(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$res = $db->fetchRow ( 'SELECT 
			AVG (densityGramsPerCm3) AS averageDensityGramsPerCm3 
			FROM `product` 
			WHERE ingredient='.$this->id);
		if(!$res['averageDensityGramsPerCm3']){
			return round(1,3);
		} else {
		return round($res['averageDensityGramsPerCm3'],3);
		}
	}
	
	/**
	 * returns the average kcal of the product per given unit
	 *
	 * @param string Unit like Component::PIECE or Component::CENTILITRE
	 * @return int|null Kcall
	 * @tested
	 */
	public function getAverageCaloriesKcal($unit = Website_Model_Component::LITRE){
		$db = Zend_Db_Table::getDefaultAdapter();
		if($unit==Website_Model_Component::PIECE){
			$res = $db->fetchRow ( 'SELECT 
				CASE unit 
					WHEN \'g\' THEN AVG(caloriesKcal/size)*AVG(size)
					WHEN \'kg\' THEN AVG(caloriesKcal/size)*AVG(size)
					ELSE NULL
				END	
				averageCaloriesKcal
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
		if($res['averageCaloriesKcal']==null){
			return null;
		} else{
			return round($res['averageCaloriesKcal']);
		}
	}
	
	/**
	 * returns the average alcohol level of the product
	 *
	 * @return int|null alcohol level
	 * @tested
	 */	
	public function getAverageAlcoholLevel(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$res = $db->fetchRow ( 'SELECT 
			AVG(alcoholLevel) AS averageAlcoholLevel
			FROM `product` 
			WHERE ingredient='.$this->id.' AND alcoholLevel IS NOT null');
		if($res['averageAlcoholLevel']==null){
			return null;
		} else{
			return round($res['averageAlcoholLevel']);
		}
	}
	
	/**
	 * returns the average weight in gram of the product
	 *
	 * @return int|null weight in gram
	 * @tested
	 */	
	public function getAverageWeightGram(){
		// TODO: not only accept GRAM as unit here, also do some conversion
		$db = Zend_Db_Table::getDefaultAdapter();
		$res = $db->fetchRow ( 'SELECT 
			AVG(size) AS averageSize
			FROM `product` 
			WHERE unit=\''.Website_Model_Component::GRAM.'\' AND ingredient='.$this->id);
		if($res['averageSize']){
			return round($res['averageSize']);
		} else {
			return null;
		}
	}
	
	/**
	 * returns the average volume in Litre of the product
	 *
	 * @return int|null volume in litre
	 * @tested
	 */		
	public function getAverageVolumeLitre(){
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
			return round($res['sizeInLitre'],2);
		} else {
			return null;
		}
	}
		
	/**
	* returns the most used unit of an Ingredient, 
	* if not unambgiguous only one unit is returned
	* return string (Unit) or null if no product data available
	*@tested
	*/
	public function getMostUsedUnit(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$res = $db->fetchRow ( 'SELECT 
			unit 
			FROM `product` 
			WHERE ingredient='.$this->id.' GROUP BY unit ORDER BY count(unit) DESC LIMIT 1');
		if($res){
			return $res['unit'];
		} else {
			null;
		}
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