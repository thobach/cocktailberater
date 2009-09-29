<?php
class Website_Model_RecipeCategory extends Category {


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

	public function RecipeCategory($id = NULL)
	{
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipecategory');
		if(!empty($id)){
			$recipeCategory = $table->fetchRow('id='.$id);
			$this->id = $recipeCategory->id;
			$this->name = $recipeCategory->name;
			$this->insertDate = $recipeCategory->insertDate;
			$this->updateDate = $recipeCategory->updateDate;
		}
	}

	public static function categoryByRecipeId($id){
		//	print microtime(). ' ';
		// load cache from registry
		$cache = Zend_Registry::get('cache');

		// see if categoryByRecipeId - list is already in cache
		if(!$recipesArray = $cache->load('categoryByRecipeId'.$id)) {
			// print "no cache: ".$id." (id) ";
			$recipe2categoryTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe2recipecategory');
			$recipes = $recipe2categoryTable->fetchAll('recipe='.$id);
			if(count($recipes) > 0 ){
				foreach ($recipes as $recipe) {
					$recipesArray[] = CbFactory::factory('RecipeCategory', $recipe->recipeCategory);
				}
			}
			$cache->save($recipesArray,'categoryByRecipeId'.$id);
		}
		//	print microtime(). ' ';
		return $recipesArray;

	}

	/**
	 * gibt alle RecipeCategory-Objekte sortiert als Array zur�ck
	 *
	 * @return array RecipeCategory
	 */
	public static function getRecipeCategories () {
		$recipeTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'glass');
		$categories = $recipeTable->fetchAll ( NULL, 'name' ) ;
		foreach ( $categories as $category ) {
			$categoryArray [] = new RecipeCategory ( $category->id ) ;
		}
		return $categoryArray ;
	}
	
	public function save (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipecategory');
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$this->id = $table->insert($data);
		}
		else {
			$table->update($this->databaseRepresentation(),'id='.$this->id);
		}
	}
	
	public function delete (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipecategory');
		$table->delete('id='.$this->id);
		CbFactory::destroy('RecipeCategory',$this->id);
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
	 */
	public function toXml ( $xml , $ast ) {
		$ingredientCategory = $xml->createElement ( 'recipeCategory' ) ;
		$ingredientCategory->setAttribute ( 'id', $this->id ) ;
		$ingredientCategory->setAttribute ( 'name', $this->name ) ;
		$ingredientCategory->setAttribute ( 'insertDate', $this->insertDate ) ;
		$ingredientCategory->setAttribute ( 'updateDate', $this->updateDate ) ;
		$ast->appendchild ( $ingredientCategory ) ;
	}
}
?>