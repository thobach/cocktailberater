<?php

/**
 * Class responsible for everything regarding recipes
 */

class Website_IndexController extends Zend_Controller_Action {
	
	public function indexAction () {
		$this->view->start = true ;
	}

	public function top10Action () {
		$list = Website_Model_Cocktail::listCocktails ( NULL, 10, 'top10' ) ;
		$this->view->cocktails = $list ;
		$this->view->title = 'Top 10 Cocktail Rezepte';
	}
	
	public function alcoholicAction () {
		$list = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'alcoholic' ) ;
		$this->view->cocktails = $list ;
		$this->view->title = 'alkoholische Cocktail Rezepte';
	}
	
	public function nonAlcoholicAction () {
		$list = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'non-alcoholic' ) ;
		$this->view->cocktails = $list ;
		$this->view->title = 'alkoholfreie Cocktail Rezepte';
	}

	public function searchAction () {
		$recipes = array();
		if($this->_getParam ( 'search_type' ) == 'name'){
			$recipes = Website_Model_Recipe::searchByName ($this->_getParam ( 'search' ) ) ;
			$this->view->title = 'Cocktail Rezepte mit dem Namen "' . $this->_getParam ( 'search' ).'"';
		} elseif($this->_getParam ( 'search_type' ) == 'ingredient'){
			$recipes = Website_Model_Recipe::searchByIngredient ( $this->_getParam ( 'search' ) ) ;
			$this->view->title = 'Cocktail Rezepte mit der Zutat "' . $this->_getParam ( 'search' ).'"';
		} elseif($this->_getParam ( 'search_type' ) == 'tag'){
			$recipes = Website_Model_Recipe::searchByTag ( $this->_getParam ( 'search' ) ) ;
			$this->view->title = 'Cocktail Rezepte mit dem Tag/Schlagwort "' . $this->_getParam ( 'search' ).'"';
		} elseif($this->_getParam ( 'search_type' ) == 'difficulty'){
			$recipes = Website_Model_Recipe::searchByDifficulty ( $this->_getParam ( 'search' ) ) ;
			$this->view->title = 'Cocktail Rezepte der Schwierigkeit "' . $this->_getParam ( 'search' ).'"';
		} elseif($this->_getParam ( 'filter' ) == 'with-image'){
			$recipes = Website_Model_Recipe::searchByName (null,null,'with-image') ;
			$this->view->title = 'Cocktail Rezepte mit Bildern';
		}
		$this->view->recipes = $recipes ;
		if (count ( $recipes ) == 1) {
			// get redirector helper
			$this->_redirector = $this->_helper->getHelper ( 'Redirector' ) ;
			// redirect to cocktail and modifiy URL
			$this->_redirector->setGoto ( 'recipe', 'index', NULL, array ( 'id' => current($recipes)->id ) ) ;
		}
	}

	public function tagAction () {
		$list = Website_Model_Recipe::getRecipesByTag ( $this->_getParam ( 'tag' )) ;
		$this->view->recipes = $list ;
		$this->view->tag = $this->_getParam ( 'tag' ) ;
		if (count ( $liste ) == 1) {
			$this->_forward ( 'recipe', null, null, array ( 'id' => $liste [ 0 ]->id ) ) ;
		}
	}

	public function recipesWithTagAction () {
		// deactivate layouts for info-fields
		$this->_helper->layout->disableLayout();

		if ($this->_hasParam ( 'tag' )) {
			$list = Website_Model_Recipe::getRecipesByTag ( $this->_getParam ( 'tag' )) ;
			$this->view->recipes = $list ;
			$this->view->tag = $this->_getParam ( 'tag' ) ;
		}
	}


	public function autocompleteAction () {
		if ('ajax' != $this->_getParam ( 'format', false )) {
			return $this->_helper->redirector ( 'cocktail-melden' ) ;
		}
		if ($this->getRequest ()->isPost ()) {
			return $this->_helper->redirector ( 'cocktail-melden' ) ;
		}
		$match = str_replace('*','',trim ( $this->getRequest ()->getQuery ( 'test', '' ) )) ;
		// für AutoComplete des Cocktailnamens
		$data = Cocktail::listCocktailNamesIndexed ($match) ;
		$this->_helper->autoCompleteDojo ( $data ) ;
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

	public function proposeCocktailSubmittedAction()
	{

	}


	public function suggestAction () {
		// only for AJAX HTTP Get calls, otherwise forward to main page
		if ('ajax' != $this->_getParam('format', false)) {
            return $this->_helper->redirector('index');
        }
        if ($this->getRequest()->isPost()) {
            return $this->_helper->redirector('index');
        }
        // get suggestions for the given search type
		$this->suggestions = array();
		$search = str_replace('*',null,$this->_getParam ( 'search' ));
		if ($this->_getParam ( 'search_type' ) == 'name' || !$this->_hasParam ( 'search_type' )) {
			$this->suggestions = Website_Model_Recipe::searchByName ( $search, 6 ) ;
			//Zend_Debug::dump($this->suggestions);
		} elseif ($this->_getParam ( 'search_type' ) == 'ingredient') {
			$this->suggestions = Website_Model_Ingredient::listIngredients ($search, 6 ) ;
		} elseif ($this->_getParam ( 'search_type' ) == 'tag') {
			$this->suggestions = Website_Model_Tag::listTags ( $search,6,true) ;
		}
        // filter only names of the matches
        $matches = array();
        foreach ($this->suggestions as $suggestion) {
        	$matches[] = $suggestion->name;
        }
        // return as json for dojo
        $this->_helper->autoCompleteDojo($matches);
	}

	// Impressum
	public function imprintAction () {
	}

	public function newsletterAction () {
	}

	public function newsletterSubscribeAction () {
		$newsletter = new Newsletter ( ) ;
		$this->view->hinweis1 = $newsletter->anmelden ( $this->_getParam ( 'email' ), $this->_getParam ( 'vorname' ), $this->_getParam ( 'nachname' ) ) ;
	}

	public function newsletterUnsubscribeAction () {
		$newsletter = new Newsletter ( ) ;
		$this->view->hinweis2 = $newsletter->abmelden ( $this->_getParam ( 'email' ) ) ;

	}

	public function newsletterSubscribeConfirmationAction () {
		$newsletter = new Newsletter ( ) ;
		$this->view->hinweis2 = $newsletter->anmeldungBestaetigen ( $this->_getParam ( 'email' ) ) ;
	}

	public function newsletterUnsubscribeConfirmationAction () {
		$newsletter = new Newsletter ( ) ;
		$this->view->hinweis2 = $newsletter->abmeldungBestaetigen ( $this->_getParam ( 'email' ) ) ;
	}

	public function recipeAction () {
		// wenn ein Cocktail angegeben wurde
		if ($this->_hasParam ( 'id' )) {
			// Taggen
			if ($this->_hasParam ( 'newtag' )) {
				$tag = new Website_Model_Tag (null, $this->_getParam ( 'newtag' ), $this->_getParam ( 'recipe' )) ;
				// TODO: memberID setzen
				//$tag->memberId = $this->_getParam ( 'member' );
			}
			// Bewerten
			if ($this->_hasParam ( 'rating' )) {
				$rating = new Website_Model_Rating ( ) ;
				$rating->memberId = null;
				$rating->recipeId = $this->_getParam('id');
				$rating->mark = $this->_getParam('rating');
				$rating->ip = $_SERVER [ 'REMOTE_ADDR' ];
				if(!$rating->save()){
					$this->view->assign ( array ( 'error' => 'Sie haben schon abgestimmt!' ) ) ;
				}
			}
			// Kommentieren
			if ($this->_hasParam ( 'comment' )) {
				$recaptcha = new Zend_Service_ReCaptcha('6LdKvggAAAAAAAhu1rSAwtIm1Ejzls4LDi0K27Td', '6LdKvggAAAAAADoKDLXugjbiSxuTc7zdrAVm8-qC');
				try{
					$result = $recaptcha->verify(
					    $this->_getParam ( 'recaptcha_challenge_field' ),
					    $this->_getParam ( 'recaptcha_response_field' )
					);
					if ($result->isValid()) {
						$comment = new Website_Model_Comment ( ) ;
						$comment->recipeId = mysql_escape_string(strip_tags($this->_getParam ( 'id' )));
						//$comment->memberId = $this->_getParam ( 'member' );
						$comment->memberId = NULL;
						$comment->comment = mysql_escape_string(strip_tags($this->_getParam ( 'comment' )));
						$comment->ip = mysql_escape_string(strip_tags($_SERVER [ 'REMOTE_ADDR' ]));
						//Zend_Debug::dump($comment);
						//exit;
						$comment->save();
						$this->view->success = 'Vielen Dank für deinen Kommentar!';
					} else {
						$this->view->error = 'Leider ist ein Fehler beim Einfügen deines Kommentares aufgetreten. Bitte überprüfe deine Eingabe!';
					}
				} catch (Zend_Exception $e){
					$this->view->error = 'Leider ist ein Fehler beim Einfügen deines Kommentares aufgetreten. Bitte überprüfe deine Eingabe!';
				}
				
			}
			$recipe = new Website_Model_Recipe ( $this->_getParam ( 'id' ) ) ;
			$this->view->recipe = $recipe;
			$cocktail = new Website_Model_Cocktail($recipe->cocktailId);
			$this->view->alternatives = $cocktail->getRecipes();
			$this->view->title = $this->view->recipe->name.' Cocktail Rezepte' ;
		}
	}

	public function recipePreviewAction () {
		// Layouts für Info-Felder deaktivieren
		$this->_helper->layout->disableLayout();
		if ($this->_hasParam ( 'id' )) {
			$cocktails = new Website_Model_Recipe ( ) ;
			$liste = $cocktails->listRecipe( $this->_getParam ( 'id' ) ) ;
			$this->view->recipe = $liste ;
			//$this->view->foto = $fotos ;
		}
	}

	public function ingredientAction () {
		if ($this->_hasParam ( 'id' )) {
			$ingredient = new Website_Model_Ingredient ( $this->_getParam ( 'id' ) ) ;
			$this->view->ingredient = $ingredient ;
			$liste = Website_Model_Cocktail::listCocktails ( $this->_getParam ( 'id' ), 100, 'zutat' ) ;
			$this->view->cocktails = $liste ;
		}
	}

	public function ingredientPreviewAction () {
		// Layouts für Info-Felder deaktivieren
		$this->_helper->layout->disableLayout();
		if ($this->_hasParam ( 'id' )) {
			$ingredient = new Website_Model_Ingredient ( $this->_getParam ( 'id' ) ) ;
			$this->view->ingredient = $ingredient ;
		}
	}

	public function addProductAction(){
		$this->view->message = $this->_getParam('message');
		$this->view->ingredients = Ingredient::listIngredients('%',10000);
		if($this->_hasParam('save_product') && !$this->_hasParam('message')){
			
			$manufacturer = new Manufacturer();
			$manufacturer->name 		= $this->_getParam('manufacturer');
			$manufacturer->website 	= '';
			$manufacturer->country 	= '';
			$manufacturer->save();		
			
			$product = new Product();
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
		$this->view->ingredientcategories = IngredientCategory::getIngredientCategories();
		if($this->_hasParam('save_ingredient') && !$this->_hasParam('message')){
			
			$ingredient = new Ingredient();
			$ingredient->name 			= $this->_getParam('name');
			$ingredient->description 	= $this->_getParam('additional_information');
			$ingredient->aggregation 	= $this->_getParam('aggregation');
			$ingredient->aliasName 		= $this->_getParam('alias');
			$ingredient->save();
			$ingredient->addIngredientCategory($this->_getParam('ingredient_category'));
			
			$this->_forward(null, null, null, array('message'=>'Zutat wurde hinzugefügt!'));
		}
		
	}
	
	public function contactedAction(){
		$defaultNamespace = new Zend_Session_Namespace('Default');
		$form    = $this->_getContactForm();
		$request = $this->getRequest();

		if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				// send email
				$formValues = $form->getValues();
				// get config
				$config = $this->getFrontController()->getParam('bootstrap')->getOptions();

				$configMail = array(
					'auth' =>  $config['mail']['auth'],
					'username' => $config['mail']['username'],
					'password' => $config['mail']['password']);
				$transport = new Zend_Mail_Transport_Smtp($config['mail']['smtp_server'], $configMail);
				Zend_Mail::setDefaultTransport($transport);

				$textView = new Zend_View();
				$textView->setScriptPath($this->view->getScriptPaths());
				$textView->contact = $formValues;

				if($formValues['getCopy']=='1'){
					$customerMail = new Zend_Mail('utf-8');
					$customerMail->setBodyText($textView->render('mail/contact-mail-customer.phtml'));
					$customerMail->setFrom($config['mail']['defaultsender'],$config['mail']['defaultsendername']);
					$customerMail->setSubject($textView->translate('Ihre Kontaktanfrage bei cocktailberater.de.de'));
					$customerMail->addTo($formValues['email']);
					$customerMail->send();
				}

				$sellerMail = new Zend_Mail('utf-8');
				$sellerMail->setBodyText($textView->render('mail/contact-mail-seller.phtml'));
				$sellerMail->setReplyTo($formValues['email'],$formValues['firstname'].' '.$formValues['lastname']);
				$sellerMail->setFrom($config['mail']['defaultsender'],$config['mail']['defaultsendername']);
				$sellerMail->setSubject('Neue Kontaktanfrage über cocktailberater.de');
				$sellerMail->addTo($config['mail']['defaultrecipient'],$config['mail']['defaultrecipientname']);
				$sellerMail->send();
			} else {
				// error -> back to contact form
				return $this->_forward('contact');
			}
		}
	}

	public function contactAction()
	{
		$defaultNamespace = new Zend_Session_Namespace('Default');
		$form    = $this->_getContactForm();
		$request = $this->getRequest();

		if ($this->getRequest()->isPost()) {
			if ($form->isValid($request->getPost())) {
				return $this->_forward('contacted');
			}
		}

		$this->view->form = $form;
		$formErrors = $this->view->getHelper('formErrors');
		$formErrors->setElementStart('<div class="notice" style="margin-top: 1em;">')
		->setElementSeparator('<br />')
		->setElementEnd('</div>');
	}

	/**
	 * @return Form_Contact
	 */

	protected function _getContactForm()
	{
		$form = new Website_Form_Contact();
		$form->setAction($this->_helper->url('contact'));
		return $form;
	}
	
	/**
	 * Returns a Sitemap XML File for search engines
	 * 
	 * @return String XML Sitemap
	 */
	public function sitemapAction()
	{
		// deactivate layout
		$this->_helper->layout->disableLayout();
		
		// load navigation nodes
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/modules/website/config/navigation.ini', 'nav');
		$pages = new Zend_Navigation($config);
		
		// add alcoholic cocktails
		$alcoholicCocktails = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'alcoholic' ) ;
		$alcoholics = array();
		foreach($alcoholicCocktails as $alcoholicCocktail){
			foreach ($alcoholicCocktail->getRecipes() as $recipe){
				$options = array();
				$options['label'] = $recipe->name;
				$options['action'] = 'recipe';
				$options['module'] = 'website';
				$options['params'] = array();
				$options['params']['cocktail'] = $recipe->name;
				$options['params']['id'] = $recipe->id;
				$alcoholics[$recipe->id] = new Zend_Navigation_Page_Mvc($options);
			}
		}
		$alcoholic = $pages->findOneBy('action','alcoholic');
		$alcoholic->addPages($alcoholics);
		
		// add non-alcoholic cocktails
		$nonAlcoholicCocktails = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'non-alcoholic' ) ;
		$nonAlcoholics = array();
		foreach($nonAlcoholicCocktails as $nonAlcoholicCocktail){
			foreach ($nonAlcoholicCocktail->getRecipes() as $recipe){
				$options = array();
				$options['label'] = $recipe->name;
				$options['action'] = 'recipe';
				$options['module'] = 'website';
				$options['params'] = array();
				$options['params']['cocktail'] = $recipe->name;
				$options['params']['id'] = $recipe->id;
				$nonAlcoholics[$recipe->id] = new Zend_Navigation_Page_Mvc($options);
			}
		}
		$nonAlcoholic = $pages->findOneBy('action','non-alcoholic');
		$nonAlcoholic->addPages($nonAlcoholics);
		
		// add top10 cocktails
		$top10Cocktails = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'top10' ) ;
		$top10s = array();
		foreach($top10Cocktails as $top10Cocktail){
			foreach ($top10Cocktail->getRecipes() as $recipe){
				$options = array();
				$options['label'] = $recipe->name;
				$options['action'] = 'recipe';
				$options['module'] = 'website';
				$options['params'] = array();
				$options['params']['cocktail'] = $recipe->name;
				$options['params']['id'] = $recipe->id;
				$top10s[$recipe->id] = new Zend_Navigation_Page_Mvc($options);
			}
		}
		$top10 = $pages->findOneBy('action','top10');
		$top10->addPages($top10s);
		
		// pass all page nodes to the view
		$this->view->pages = $pages;
		
		// set header to XML 
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}

}

?>