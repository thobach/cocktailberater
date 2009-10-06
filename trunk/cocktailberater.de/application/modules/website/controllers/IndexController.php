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
	}
	
	public function alcoholicAction () {
		$list = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'alcoholic' ) ;
		$this->view->cocktails = $list ;
	}
	
	public function nonAlcoholicAction () {
		$list = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'non-alcoholic' ) ;
		$this->view->cocktails = $list ;
	}

	public function searchAction () {
		$recipes = array();
		if($this->_getParam ( 'search_type' ) == 'name'){
			$recipes = Website_Model_Recipe::searchByName ($this->_getParam ( 'search' ) ) ;
		} elseif($this->_getParam ( 'search_type' ) == 'ingredient'){
			$recipes = Website_Model_Recipe::searchByIngredient ( $this->_getParam ( 'search' ) ) ;
		} elseif($this->_getParam ( 'search_type' ) == 'tag'){
			$recipes = Website_Model_Recipe::searchByTag ( $this->_getParam ( 'search' ) ) ;
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
		// Input box for cocktail name with AutoCompletion
		$array = array ( 	'filters' => array ( 'StringTrim' ) ,
	                        'dojoType' => array ( 'dijit.form.ComboBox' ) ,
	                        'store' => 'testStore' ,
	                        'autoComplete' => 'false' ,
	                        'hasDownArrow' => 'false' ,
	                        'onChange' => 'cocktailexists'  );
		$form = new Zend_Form_Element_Text('cocktail_name',$array);
		// Pass Inputfeld on to View
		$this->view->form = $form;

		/*
		 * If form has been submitted, spit all data :-)
		 */
		if ($this->_hasParam ( 'einsenden' )) {
			if ($id = Cocktail::exists ( $this->_getParam ( 'cocktail_name' ) )) {
				// load existing cocktail (add new recipe later)
				$cocktail = new Cocktail ( $id ) ;
			} else {
				$cocktail = new Cocktail ( ) ;
				$cocktail->name = $this->_getParam ( 'cocktail_name' ) ;
				$cocktail->save();
			}
			$rezept = new Recipe ( ) ;
			$rezept->cocktailId = $cocktail->id;
			$rezept->name = $this->_getParam ( 'cocktail_name' ) ;
			$rezept->save(); // save already to get ID
			$rezept->glassId = $this->_getParam ( 'idglas' ) ;
			$rezept->workMin = $this->_getParam ( 'zeitaufwand_min' ) ;
			$rezept->difficulty = $this->_getParam ( 'schwierigkeits_grad' ) ;
			$rezept->name = $this->_getParam ( 'cocktail_name' ) ;
			$rezept->description = $this->_getParam ( 'cocktail_beschreibung' ) ;
			$rezept->instruction = $this->_getParam ( 'rezept_anweisung' ) ;
			$rezept->isOriginal = '1' ;
			$rezept->isAlcoholic = '1' ;
			// append recipe categories
			if (is_array ( $this->_getParam ( 'kategorien' ) )) {
				foreach ( $this->_getParam ( 'kategorien' ) as $categoryId ) {
					$rezept->addRecipeCategory($categoryId);
				}
			}
			$rezeptoren = array ( ) ;
			// anzahl der Zutaten
			$anzahlZutaten = 0 ;
			// für alle Zutatenkategorien
			for ( $kat = 1 ; $kat <= 9 ; $kat ++ ) {
				// für alle 10 möglichen Zutaten pro Kategorie und Rezept
				for ( $div = 1 ; $div <= 10 ; $div ++ ) {
					if ($this->_getParam ( 'idzutat_zut_kat_' . $kat . '_feld_' . $div ) != '' && $this->_getParam ( 'menge_zut_kat_' . $kat . '_feld_' . $div ) != '') {
						$component = new Component ( ) ;
						$component->recipeId = $rezept->id ;
						$component->ingredientId = $this->_getParam ( 'idzutat_zut_kat_' . $kat . '_feld_' . $div ) ;
						$component->amount = $this->_getParam ( 'menge_zut_kat_' . $kat . '_feld_' . $div ) ;
						$component->unit = $this->_getParam ( 'einheit_zut_kat_' . $kat . '_feld_' . $div ) ;
						$component->save();
						$rezept->addComponent($component);
					}

				}
			}
			//Zend_Debug::dump ( $rezeptoren ) ;
			//Zend_Debug::dump ( $this->_getAllParams () ) ;
			// Zutaten dem Rezept anfügen
			//$rezept->rezeptoren = $rezeptoren ;
			// dem Cocktail das Rezeptobjekt übergeben
			// array_push ( $cocktail->rezepte, $rezept ) ;
			//$neueRezepte = $cocktail->getRecipes();
			//$neueRezepte[] = $rezept;
			//$cocktail->rezepte = $neueRezepte;
			//Zend_Debug::dump ( $cocktail->rezepte ) ;
			// keine Cocktail-Kommentare in diesem Stadium
			//print 'cocktail save: ' . $cocktail->save () . '<br />' ;
			//exit () ;
			$rezept->save();
			$this->_forward('propose-cocktail-submitted');
		}
	}

	public function proposeCocktailSubmittedAction()
	{

	}


	public function suggestAction () {
		$this->suggestions = array();
		$search = str_replace('*',null,$this->_getParam ( 'search' ));
		if ($this->_getParam ( 'search_type' ) == 'name' || !$this->_hasParam ( 'search_type' )) {
			$this->suggestions = Website_Model_Recipe::searchByName ( $search, 6 ) ;
			// Zend_Debug::dump($this->suggestions);
		} elseif ($this->_getParam ( 'search_type' ) == 'ingredient') {
			$this->suggestions = Website_Model_Ingredient::listIngredients ($search, 6 ) ;
		} elseif ($this->_getParam ( 'search_type' ) == 'tag') {
			$this->suggestions = Website_Model_Tag::listTags ( $search,6,true) ;
		}
		//$this->view->search_type = $this->_getParam ( 'search_type' ) ;
		
		if ('ajax' != $this->_getParam('format', false)) {
            return $this->_helper->redirector('index');
        }
        if ($this->getRequest()->isPost()) {
            return $this->_helper->redirector('index');
        }
        
        $matches = array();
        foreach ($this->suggestions as $suggestion) {
        	$matches[] = $suggestion->name;
        }
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
				$tag = new Website_Model_Tag () ;
				$tag->name = $this->_getParam ( 'newtag' );
				$tag->recipeId = $this->_getParam ( 'recipe' );
				$tag->memberId = $this->_getParam ( 'member' );
				$tag->save();
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
				$comment = new Website_Model_Comment ( ) ;
				$comment->recipeId = $this->_getParam ( 'id' );
				$comment->memberId = $this->_getParam ( 'member' );
				$comment->comment = $this->_getParam ( 'comment' );
				$comment->ip = $_SERVER [ 'REMOTE_ADDR' ];
				$comment->save();
			}
			$this->view->recipe = new Website_Model_Recipe ( $this->_getParam ( 'id' ) ) ;
			$this->view->title = 'Rezept(e) von ' . $this->view->recipe->name ;
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

}

?>