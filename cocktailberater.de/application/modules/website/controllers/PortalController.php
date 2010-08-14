<?php

/**
 * Class responsible for everything regarding the portal
 */

class Website_PortalController extends Zend_Controller_Action {

	public function init() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_PortalController->init',Zend_Log::DEBUG);

		/*
		 * use $this->_helper->getHelper('contextSwitch') instead of static call
		 * Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')
		 * otherwise controller is not registered
		 */
		/* @var $contextSwitch Zend_Controller_Action_Helper_ContextSwitch */
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->setAutoJsonSerialization(false);
		if(!$contextSwitch->hasContext('mobile')){
			$contextSwitch->addContext('mobile',array(
				'suffix'	=> 'mobile',
				'headers'	=> array('Content-Type' => 'text/html'),
				'callbacks' => array(
					'init'	=> array(__CLASS__, 'enableLayout'),
		            'post' => array(__CLASS__, 'setLayoutContext'))));
		}
		if(!$contextSwitch->hasContext('html')){
			$contextSwitch->addContext('html',array(
				'suffix'	=> '',
				'headers'	=> array('Content-Type' => 'text/html'),
				'callbacks' => array(
					'init'	=> array(__CLASS__, 'enableLayout'),
		            'post' => array(__CLASS__, 'setLayoutContext'))));
		}
		if(!$contextSwitch->hasContext('pdf')){
			$contextSwitch->addContext('pdf',array(
				'suffix'	=> 'pdf',
				'headers'	=> array('Content-Type' => 'application/pdf')));
		}
		$contextSwitch->addActionContexts(array('about'=>true,'authors'=>true,
		'andere-seiten'=>true,'barkunde'=>true,'buecher'=>true,
		'cocktail-der-woche'=>true,'contact'=>true,'contacted'=>true,
		'community'=>true,'forum'=>true,'glas'=>true,'grundausstattung'=>true,
		'hausbar'=>true,'imprint'=>true,'index'=>true,'login'=>true,
		'mein-cocktailbuch'=>true,'mein-cocktailbuch-erstellen'=>true,
		'meine-cocktails'=>true,'meine-favoriten'=>true,'meine-hausbar'=>true,
		'meine-hausbar-mixen'=>true,'mixtechniken'=>true,'nutrition'=>true,
		'top10-drinks'=>true,'utensilien'=>true,'zutaten'=>true));
		$contextSwitch->addActionContext('meine-hausbar',array('mobile','html'));
		$contextSwitch->initContext();

	}

	public function indexAction () {

	}

	public function aboutAction () {

	}

	public function barkundeAction () {

	}

	public function hausbarAction () {

	}

	public function communityAction () {

	}
	
	public function authorsAction () {
		 $this->view->format = $this->_getParam('format');
		 $recipes = array();
		 $recipes['schumann'] = Website_Model_Recipe::searchByName('%nach Schumann');
		 $recipes['brandl'] = Website_Model_Recipe::searchByName('%nach Brandl');
		 $recipes['trader_vic'] = Website_Model_Recipe::searchByName('%nach Trader Vic');
		 $recipes['beach'] = Website_Model_Recipe::searchByName('%nach Beach');
		 $this->view->recipes = $recipes;
		 
		 // get sources
		 // $this->view->sources
		 // 
		 $this->view->sources = Website_Model_Recipe::listSources();
	}

	public function cocktailDerWocheAction () {

	}

	public function top10DrinksAction () {

	}

	public function forumAction () {

	}

	public function loginAction () {

	}

	public function meineHausbarAction () {
		$defaultNamespace = new Zend_Session_Namespace('Default');
		// load default bar
		if($defaultNamespace->bar == null){
			$defaultNamespace->bar = new Website_Model_Bar();
			// assume that every bar has ice cubes and crushed ice
			$iceCubes = new Website_Model_Ingredient(113);
			$crushedIce = new Website_Model_Ingredient(114);
			$defaultNamespace->bar->addIngredient($iceCubes);
			$defaultNamespace->bar->addIngredient($crushedIce);
		}
		$this->view->bar = $defaultNamespace->bar;

		/* @var $contextSwitch Zend_Controller_Action_Helper_ContextSwitch */
		$contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
	}

	public function meineHausbarMixenAction () {
		$defaultNamespace = new Zend_Session_Namespace('Default');
		if($defaultNamespace->bar == null){
			return $this->_forward('meine-hausbar');
		}
		// load default bar
		$this->view->bar = $defaultNamespace->bar;
		// if form was posted, insert into "database" or "session"
		if($this->getRequest()->isPost()){
			$this->view->bar->removeIngredients();
			$this->view->message='Die abgewählten Zutaten wurden aus Ihrer Hausbar entfernt.';
			if(!$this->_hasParam('reset_ingredient2bar')){
				$ingredients = $this->_getParam('ingredient2bar');
				if(is_array($ingredients)) {
					foreach($ingredients as $ingredient) {
						$this->view->bar->addIngredient(Website_Model_CbFactory::factory('Website_Model_Ingredient',$ingredient));
					}
					$this->view->message='Die ausgewählten Zutaten wurden in Ihrer Hausbar angelegt.';
				}
			} else {
				return $this->_redirect($this->view->url(array('module'=>'website','controller'=>'portal','action'=>'meine-hausbar')).'?format='.$this->_getParam('format'));
			}
		}
	}

	public function meineHausbarPrintAction () {
		//fetch all products from the current bar
		$this->view->bar = $bar = new Website_Model_Bar(1);
		$this->view->currentProducts = $bar->getProducts();
	}

	public function meineFavoritenAction () {

	}

	public function meineCocktailsAction () {

	}

	public function meinCocktailbuchAction () {
		$defaultNamespace = new Zend_Session_Namespace('Default');
		// load default menu
		if($defaultNamespace->menu == null){
			$defaultNamespace->menu = new Website_Model_Menu();
		}
		$this->view->recipes = Website_Model_Recipe::searchByName('%');
		$this->view->menu = $defaultNamespace->menu;
	}

	public function meinCocktailbuchErstellenAction () {
		$defaultNamespace = new Zend_Session_Namespace('Default');
		if($defaultNamespace->menu == null){
			return $this->_forward('mein-cocktalbuch');
		}
		// load default menu
		$this->view->menu = $defaultNamespace->menu;
		if($this->getRequest()->isPost()){
			// create pdf menu
			if($this->_hasParam('submit_recipe2menu_pdf')){
				$this->view->recipes = $this->view->menu->listRecipes();
				$this->view->name = $this->_getParam('name');
			}
			// return to selection page
			else if($this->_hasParam('submit_recipe2menu_reset')){
				$this->_redirect($this->view->url(array('module'=>'website','controller'=>'portal','action'=>'mein-cocktailbuch')).'?format='.$this->_getParam('myformat'));
			}
			// show list of added recipes
			else {
				$this->view->menu->removeRecipes();
				$recipes = $this->_getParam('recipe2menu');
				if(is_array($recipes)) {
					foreach($recipes as $recipe) {
						$this->view->menu->addRecipe($recipe);
					}
				}
			}
		}
	}

	public function glasAction () {

	}

	public function utensilienAction () {

	}

	public function nutritionAction () {
		$this->view->cocktails = Website_Model_Cocktail::listCocktails('');
		//Zend_Debug::dump($cocktails);
	}

	public function mixtechnikenAction () {

	}

	public function grundausstattungAction () {

	}

	public function zutatenAction () {

	}

	public function buecherAction () {

	}

	public function andereSeitenAction () {

	}

	// Impressum
	public function imprintAction () {
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
		$form->setAction($this->_helper->url('contact')."?format=".$this->_getParam('format'));
		return $form;
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

}

?>