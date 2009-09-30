<?php
/**
 * IngredientCategory class
 *
 */
class Website_Model_IngredientCategory extends Website_Model_Category {

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

	/**
	 * IngredientCategory constructor
	 *
	 * @param integer $idIngredientCategory
	 * @return IngredientCategory
	 * @tested
	 */
	public function __construct ( $ingredientCategoryId = NULL ) {
		if (! empty ( $ingredientCategoryId )) {
			$ingredientCategoryTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','ingredientcategory');
			$ingredientCategory = $ingredientCategoryTable->fetchRow ( 'id=' . $ingredientCategoryId ) ;
			if(!$ingredientCategory){
				throw new IngredientCategoryException('Id_Wrong');
			}
			$this->id = $ingredientCategory->id;
			$this->name = $ingredientCategory->name;
			$this->insertDate = $ingredientCategory->insertDate;
			$this->updateDate = $ingredientCategory->updateDate;
		}
	}

	/**
	 * returns an array containing all ingredient categories of an ingeredient
	 *
	 * @param integer $idIngredient
	 * @return array ingredientCategories
	 * @tested
	 */
	public static function categoriesByIngredientId( $ingredientId ) {
		$ingredientHasIngredientCategoryTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','ingredient2ingredientcategory');
		$categories = $ingredientHasIngredientCategoryTable->fetchAll ( 'ingredient=' . $ingredientId ) ;
		foreach ( $categories as $category ) {
			$ingredientCategories [] = Website_Model_CbFactory::factory('Website_Model_IngredientCategory', $category['ingredientCategory']) ;
		}
		return $ingredientCategories ;
	}

	/**
	 * returns a sorted array of all ingredients of one or more categories
	 *
	 * @param integer|array $idzutaten_kategorie
	 * @return array Zutat
	 */
	public static function getIngredientsByCategory ( $ingredientCategoryId ) {
		$ingredientHasIngredientCategoryTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','ingredient2ingredientcategory');
		if (is_array ( $ingredientCategoryId )) {
			$select = new Zend_Db_Table_Select($ingredientHasIngredientCategoryTable);
			foreach ( $ingredientCategoryId as $value ) {
				$where = $select->orWhere('ingredientCategory = '.$value);
			}
		} else {
			$where = 'ingredientCategory=' . $ingredientCategoryId;
		}
		$ingredients = $ingredientHasIngredientCategoryTable->fetchAll ( $where ) ;
		$ingredientsArray = array ( ) ;
		foreach ( $ingredients as $ingredient ) {
			$ingredientsArray [] = Website_Model_CbFactory::factory('Website_Model_Ingredient', $ingredient->ingredient ) ;
		}
		// sort array by name
		usort ( $ingredientsArray, array ( "Website_Model_Ingredient" , "cmp_obj" ) ) ;
		return $ingredientsArray ;
	}

	/**
	 * returns an array of all Ingredient Categorys as IngredientCategory objects
	 *
	 * @return array ZutatenKategorie
	 * @tested
	 */
	public static function getIngredientCategories() {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','ingredientcategory' ) ;
		$categories = $table->fetchAll();
		$categoriesArray = array ();
		foreach ( $categories as $category ) {
			$categoriesArray [] = CbFactory::factory('IngredientCategory',$category->id) ;
		}
		return $categoriesArray ;
	}
	
	/**
	 * @tested
	 */
	public function save (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'ingredientcategory');
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$this->id = $table->insert($data);
		}
		else {
			$table->update($this->databaseRepresentation(),array ('id'=>$this->id));
		}
	}
	
	/**
	 * @tested
	 */
	public function delete (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'ingredientcategory');
		$table->delete('id='.$this->id);
		CbFactory::destroy('IngredientCategory',$this->id);
		unset($this);
	}
	
	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	protected function dataBaseRepresentation() {
		$array['name'] = $this->name;
		return $array;
	}
	
	
	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 * @tested
	 */
	public function toXml ( $xml , $ast ) {
		$ingredientCategory = $xml->createElement ( 'ingredientCategory' ) ;
		$ingredientCategory->setAttribute ( 'id', $this->id ) ;
		$ingredientCategory->setAttribute ( 'name', $this->name ) ;
		$ast->appendchild ( $ingredientCategory ) ;
	}
}
?>