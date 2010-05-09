<?php
/**
 * Recipe Class which holds all information for a recipe (instructions, glass, ingredients..)
 *
 */
abstract class Website_Model_RecipeInterface {

	// attributes
	/**
	 * 
	 * @var int
	 */
	public $id;
	/**
	 * 
	 * @var string
	 */
	public $name;
	/**
	 * 
	 * @var int
	 */
	public $memberId;
	/**
	 * 
	 * @var int
	 */
	public $cocktailId;
	/**
	 * 
	 * @var int
	 */
	public $glassId;
	/**
	 * 
	 * @var string
	 */
	public $description;
	/**
	 * 
	 * @var string
	 */
	public $instruction;
	/**
	 * 
	 * @var string
	 */
	public $source;
	/**
	 * 
	 * @var int
	 */
	public $workMin;
	/**
	 * 
	 * @var string
	 */
	public $difficulty;
	/**
	 * 
	 * @var int
	 */
	public $isOriginal;
	/**
	 * 
	 * @var int
	 */
	public $isAlcoholic; // calculated
	/**
	 * 
	 * @var int
	 */
	public $alcoholLevel; // calculated
	/**
	 * 
	 * @var int
	 */
	public $caloriesKcal; // calculated
	/**
	 * 
	 * @var int
	 */
	public $averagePrice; // calculated
	/**
	 * 
	 * @var int
	 */
	public $volumeCl; // calculated
	/**
	 * 
	 * @var int
	 */
	public $ratingsSum;
	/**
	 * 
	 * @var int
	 */
	public $ratingsCount;
	/**
	 * 
	 * @var string
	 */
	public $insertDate;
	/**
	 * 
	 * @var string
	 */
	public $updateDate;


	/**
	 * 
	 * @param int $id
	 * @return Website_Model_RecipeInterface
	 */
	public static function getRecipe($id)
	{
	}
	

	/**
	 * Returns all recipes where the recipe title contains the given string.
	 *
	 * @param string $name search string
	 * @param int $limit maximal number of recipes (nullable)
	 * @param string $filter array with filters (nullable)
	 * @return Website_Model_RecipeInterface[] array with matching recipes
	 */
	public static function searchByName ($name,$limit=null,$filter=null){
	}

	/**
	 * 
	 * @param string $name
	 * @return Website_Model_RecipeInterface[] array with matching recipes
	 */
	public static function searchByIngredient ($name){
	}

	/**
	 * 
	 * @param string $name
	 * @return Website_Model_RecipeInterface[] array with unique recipes (id as key)
	 */
	public static function searchByTag ($name){
	}

	/**
	 * Searches for recipes with certain difficulty, ordered by rating
	 *
	 * @param string $difficulty
	 * @return Website_Model_RecipeInterface[] array with unique recipes (id as key)
	 */
	public static function searchByDifficulty ($difficulty){
	}

	/**
	 * 
	 * @param string $tag
	 * @param int $max
	 * @return Website_Model_RecipeInterface[]
	 */
	public static function getRecipesByTag($tag,$max = NULL){
	}


}
?>