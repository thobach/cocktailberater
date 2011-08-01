<?php

/**
 * Class responsible for everything regarding admin stuff
 */

class  Website_AdminController extends Zend_Controller_Action {


	public function init() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_AdminController->init',Zend_Log::DEBUG);

		/*
		 * use $this->_helper->getHelper('contextSwitch') instead of static call
		 * Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')
		 * otherwise controller is not registered
		 */
		/* @var $contextSwitch Zend_Controller_Action_Helper_ContextSwitch */
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->setAutoJsonSerialization(false);
		if(!$contextSwitch->hasContext('html')){
			$contextSwitch->addContext('html',array(
				'suffix'	=> '',
				'headers'	=> array('Content-Type' => 'text/html'),
				'callbacks' => array(
					'init'	=> array(__CLASS__, 'enableLayout'),
		            'post' => array(__CLASS__, 'setLayoutContext'))));
		}
		$contextSwitch->addActionContexts(array('propose-cocktail'=>true,
		'propose-cocktail-submitted'=>true,'add-product'=>true,
		'add-ingredient'=>true,'delete-cache'=>true,'find-popular'=>true,
		'add-translations'=>true));
		$contextSwitch->initContext();

	}

	public function addTranslationsAction () {

		$cache = Zend_Registry::get('cache');
		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);

		$log = Zend_Registry::get('logger');

		if($this->_hasParam ('save_recipe')) {

			$recipeId = $this->_getParam ('recipeId');
			$recipe = Website_Model_Recipe::getRecipe($recipeId);

			$log->log('Website_AdminController->addTranslationsAction (updateing recipe ' . $recipe->id . ')',Zend_Log::DEBUG);

			if($this->_getParam ('name_en')){
				$recipe->name_en = $this->_getParam ('name_en');
			}

			if($this->_getParam ('description_en')){
				$recipe->description_en = $this->_getParam ('description_en');
			}

			if($this->_getParam ('instruction_en')){
				$recipe->instruction_en = $this->_getParam ('instruction_en');
			}

			if($this->_getParam ('source_en')){
				$recipe->source_en = $this->_getParam ('source_en');
			}

			$recipe->translation_en_state = $this->_getParam ('translation_en_state');

			$recipe->save();
				
		}

		$this->view->recipes = Website_Model_Recipe::listRecipes();

		// prepopulate translations
		foreach ($this->view->recipes as $recipe){
			if($recipe->translation_en_state == null){
				$log->log('Website_AdminController->addTranslationsAction (prepopulationg recipe ' . $recipe->id . ' - state: ' . $recipe->translation_en_state . ')',Zend_Log::DEBUG);
				$recipe->name_en = $this->getTranslation($recipe->name);
				$recipe->description_en = $this->getTranslation($recipe->description);
				$recipe->instruction_en = $this->getTranslation($recipe->instruction);
				$recipe->source_en = $this->getTranslation($recipe->source);
				$recipe->translation_en_state = 'google-translate';
				$recipe->save();
			}
		}

		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);

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

	public function deleteCacheAction(){
		$cache = Zend_Registry::get('cache');
		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
	}

	private function my_fetch($url,$user_agent='Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'){
		print '<br />look for: '.$url;
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_REFERER, 'http://www.google.com/');
		$result = curl_exec ($ch);
		curl_close ($ch);
		return $result;
	}

	public function findPopularAction(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$result = $db->fetchAll ( 'SELECT recipeName FROM betaRecipeStats WHERE cocktailRezeptOhneBar IS NULL');
		foreach($result as $row){
			$data = $this->my_fetch('http://www.google.de/search?q=%22'.str_replace(array(' ','&'),array('+','%26'),$row['recipeName']).'%22+cocktail+rezept+-bar');
			$find1=utf8_decode('ungefähr <b>');
			$find2=utf8_decode('</b> für');
			//have text beginning from $find1
			$pos1=strpos($data,$find1);
			//find position of $find2
			$pos2=strpos($data,$find2);
			//take substring out, which'd be the number we want
			$search_number=substr($data,$pos1+strlen($find1), $pos2-$pos1-strlen($find2));
			$search_number=str_replace(',','',$search_number);

			if($search_number>0){
				print '<br />$search_number: '.$search_number;
			}
			if(!($search_number>0)){
				$search_number = 0;
			}
			$db->update('betaRecipeStats',
			array('cocktailRezeptOhneBar'=>$search_number),
		    				'recipeName=\''.str_replace('\'','\\\'',$row['recipeName']).'\'');
		}
		exit;
	}

	public static function enableLayout() {
		$layout = Zend_Layout::getMvcInstance();
		$layout->enableLayout();
	}

	public static function setLayoutContext() {
		$layout = Zend_Layout::getMvcInstance();
		if (null !== $layout && $layout->isEnabled()) {
			$context = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')->getCurrentContext();
			if (null !== $context) {
				$layout->setLayout($layout->getLayout() . '.' . $context);
			}
		}
	}

	private function getTranslation($stringToTranslate){

		$cache = Zend_Registry::get('cache');
		$translations = $cache->load('adminTranslations');

		if(!$translations[md5($stringToTranslate)]){

			$client = new Zend_Http_Client('http://ajax.googleapis.com/ajax/services/language/translate', array('maxredirects' => 0, 'timeout' => 10));
			$client->setParameterGet(array('v' => '1.0', 'q' => $stringToTranslate, 'langpair' => 'de|en'));
			$response = $client->request();
			$data = $response->getBody();
			$server_result = json_decode($data);
			$status = $server_result->responseStatus; // should be 200
			$details = $server_result->responseDetails;
			$result = $server_result->responseData->translatedText;

			$translations[md5($stringToTranslate)] = $result;

			$cache->save($translations,'adminTranslations',array('admin'));

		} else {

			$result = $translations[md5($stringToTranslate)];

		}

		return $result;
	}

}