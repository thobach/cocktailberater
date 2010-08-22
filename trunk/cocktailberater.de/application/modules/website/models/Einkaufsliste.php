<?php
/**
 * Einkaufsliste class represents a list of wished recipes and generates needed ingredients
 *
 */
class Website_Model_Einkaufsliste {

	// attributes
	private $name;

	// associations
	private $_recipes;
	private $_ingredients;

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
	 * 
	 * Adds a recipe to the wish list
	 * @param Website_Model_Recipe $recipe
	 */
	public function addRecipe (Website_Model_Recipe $recipe){
		$this->_recipes[$recipe->id] = $recipe;
	}
	
	/**
	 * Returns all wanted recipes
	 */
	public function getRecipes(){
		return $this->_recipes;
	}

	/**
	 * returns all ingredients which are needed to mix the given recipes
	 */
	public function getIngredients(){
		if(count($this->_recipes)>0){
			$recipes = implode(",",array_keys($this->_recipes));
			$this->_ingredients = array();
			$db = Zend_Db_Table::getDefaultAdapter();
			$ingredients = $db->fetchAll("
			SELECT
				DISTINCT component.ingredient 
			FROM
				component 
			WHERE
				component.recipe IN (".$recipes.")");
			foreach($ingredients as $ingredient){
				$this->_ingredients[$ingredient['ingredient']] =
				Website_Model_CbFactory::factory('Website_Model_Ingredient',
				$ingredient['ingredient']);
			}
			return $this->_ingredients;
		} else {
			return array();
		}
	}
	
	/**
	 * Returns true if the recipe is already in the list, false if not
	 * @param Website_Model_Recipe $recipe
	 */
	public function hasRecipe(Website_Model_Recipe $recipe){
		if($this->_recipes[$recipe->id]){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * clears the einkaufsliste
	 */
	public function clear(){
		$this->_recipes = array();
		$this->_ingredients = array();
	}

}
