<?php

/**
 * Class responsible for everything regarding admin stuff
 */

class AdminController extends Zend_Controller_Action {
	
	function indexAction () {
		phpinfo();
		exit;
	}
	

	public function proposeCocktailAction () {
		/*
		 * If form has been submitted, spit all data :-)
		 */
		if ($this->_hasParam ( 'einsenden' )) {
			if ($id = Website_Model_Cocktail::exists ( $this->_getParam ( 'cocktail_name' ) )) {
				// load existing cocktail (add new recipe later)
				$cocktail = new Website_Model_Cocktail ( $id ) ;
			} else {
				$cocktail = new Website_Model_Cocktail ( ) ;
				$cocktail->name = $this->_getParam ( 'cocktail_name' ) ;
				$cocktail->save();
			}
			$rezept = new Website_Model_Recipe ( ) ;
			$rezept->cocktailId = $cocktail->id;
			$rezept->name = $this->_getParam ( 'cocktail_name' ) ;
			$rezept->save(); // save already to get ID
			$rezept->glassId = $this->_getParam ( 'idglas' ) ;
			$rezept->workMin = $this->_getParam ( 'zeitaufwand_min' ) ;
			$rezept->difficulty = $this->_getParam ( 'schwierigkeits_grad' ) ;
			$rezept->name = $this->_getParam ( 'cocktail_name' ) ;
			$rezept->description = $this->_getParam ( 'cocktail_beschreibung' ) ;
			$rezept->instruction = $this->_getParam ( 'rezept_anweisung' ) ;
			$rezept->source = $this->_getParam ( 'quelle' ) ;
			$rezept->isOriginal = '1' ;
			$rezept->isAlcoholic = '1' ;
			// append recipe categories
			if (is_array ( $this->_getParam ( 'kategorien' ) )) {
				foreach ( $this->_getParam ( 'kategorien' ) as $categoryId ) {
					$rezept->addRecipeCategory($categoryId);
				}
			}
			// append tags
			// since it is a new recipe, no need to check if tags exist
			if ($this->_hasParam ( 'tags' )) {
				// remove spaces
				$tags = preg_replace('/\s\s*/', '', $this->_getParam ( 'tags' ));
				// get array
				$tags = explode(',',$tags);
				// add all tags
				foreach($tags as $tag){
					$tag = new Website_Model_Tag (null,$tag, $rezept->id);
					// TODO: retrieve member ID when inserting tags
					// $tag->memberId = $this->_getParam ( 'member' );
				}
			}
			// append video
			if ($this->_hasParam ( 'videourl' )) {
				// TODO: check video url format, is it youtube link etc.?
				$video = new Website_Model_Video ();
				$video->recipeId = $rezept->id;
				$video->name = $this->_getParam ( 'video_name' );
				$video->description = $this->_getParam ( 'video_beschreibung' );
				$video->url = $this->_getParam ( 'videourl' );
				// TODO: add insert and update date
				$video->save();
			}
			$rezeptoren = array ( ) ;
			// anzahl der Zutaten
			$anzahlZutaten = 0 ;
			// für alle Zutatenkategorien
			for ( $kat = 1 ; $kat <= 9 ; $kat ++ ) {
				// für alle 10 möglichen Zutaten pro Kategorie und Rezept
				for ( $div = 1 ; $div <= 10 ; $div ++ ) {
					if ($this->_getParam ( 'idzutat_zut_kat_' . $kat . '_feld_' . $div ) != '' && $this->_getParam ( 'menge_zut_kat_' . $kat . '_feld_' . $div ) != '') {
						$component = new Website_Model_Component ( ) ;
						$component->recipeId = $rezept->id ;
						$component->ingredientId = $this->_getParam ( 'idzutat_zut_kat_' . $kat . '_feld_' . $div ) ;
						$component->amount = $this->_getParam ( 'menge_zut_kat_' . $kat . '_feld_' . $div ) ;
						$component->unit = $this->_getParam ( 'einheit_zut_kat_' . $kat . '_feld_' . $div ) ;
						$component->save();
						$rezept->addComponent($component);
					}

				}
			}
			$rezept->save();
			$this->_forward('propose-cocktail-submitted');
		}
	}

	public function proposeCocktailSubmittedAction() {

	}
	

	public function addProductAction(){
		$this->view->message = $this->_getParam('message');
		$this->view->ingredients = Website_Model_Ingredient::listIngredients('%',10000);
		if($this->_hasParam('save_product') && !$this->_hasParam('message')){

			$manufacturer = new Website_Model_Manufacturer();
			$manufacturer->name 		= $this->_getParam('manufacturer');
			$manufacturer->website 	= '';
			$manufacturer->country 	= '';
			$manufacturer->save();

			$product = new Website_Model_Product();
			$product->ingredientId 			= $this->_getParam('product_group');
			$product->name 					= $this->_getParam('product_name');
			$product->manufacturerId 		= $manufacturer->id;
			$product->size					= $this->_getParam('amount');
			$product->unit 					= $this->_getParam('unit');
			$product->alcoholLevel 			= $this->_getParam('alcohol_level');
			$product->caloriesKcal 			= $this->_getParam('calories_kcal');
			$product->densityGramsPerCm3 	= $this->_getParam('density_gcm3');
			$product->fruitConcentration 	= $this->_getParam('fruit_concentration');
			$product->color 				= '';
			$product->save();

			$this->_forward(null, null, null, array('message'=>'Produkt wurde hinzugefügt!'));
		}

		if($this->_hasParam('save_chain') && !$this->_hasParam('message')){
			$this->_forward(null, null, null, array('message'=>'Kette wurde hinzugefügt!'));
		}

		if($this->_hasParam('save_price') && !$this->_hasParam('message')){
			$this->_forward(null, null, null, array('message'=>'Kaufinformationen wurden hinzugefügt!'));
		}
	}

	public function addIngredientAction(){
		$this->view->message = $this->_getParam('message');
		$this->view->ingredientcategories = Website_Model_IngredientCategory::getIngredientCategories();
		if($this->_hasParam('save_ingredient') && !$this->_hasParam('message')){

			$ingredient = new Website_Model_Ingredient();
			$ingredient->name 			= $this->_getParam('name');
			$ingredient->description 	= $this->_getParam('additional_information');
			$ingredient->aggregation 	= $this->_getParam('aggregation');
			$ingredient->aliasName 		= $this->_getParam('alias');
			$ingredient->save();
			$ingredient->addIngredientCategory($this->_getParam('ingredient_category'));

			$this->_forward(null, null, null, array('message'=>'Zutat wurde hinzugefügt!'));
		}

	}
}