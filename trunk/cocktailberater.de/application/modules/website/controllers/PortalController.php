<?php

/**
 * Class responsible for everything regarding the portal
 */

class Website_PortalController extends Zend_Controller_Action {

	public function init() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_IndexController->init',Zend_Log::DEBUG);

		/* @var $contextSwitch Zend_Controller_Action_Helper_ContextSwitch */
		$contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
		$contextSwitch->setAutoJsonSerialization(false);
		$contextSwitch->addContext('mobile',array(
				'suffix'	=> 'mobile',
				'headers'	=> array('Content-Type' => 'text/html'),
				'callbacks' => array(
					'init'	=> array(__CLASS__, 'enableLayout'),
		            'post' => array(__CLASS__, 'setLayoutContext'))));
		$contextSwitch->addContext('html',array(
				'suffix'	=> '',
				'headers'	=> array('Content-Type' => 'text/html'),
				'callbacks' => array(
					'init'	=> array(__CLASS__, 'enableLayout'),
		            'post' => array(__CLASS__, 'setLayoutContext'))));
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
		$contextSwitch->addActionContexts(array('about'=>true,'imprint'=>true,
		'contact'=>true,'contacted'=>true));
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

	public function cocktailDerWocheAction () {

	}

	public function top10DrinksAction () {

	}

	public function forumAction () {

	}

	public function loginAction () {

	}

	public function meineHausbarAction () {
		//Zend_Debug::dump($this->_getAllParams());
		//fetch all products from the current bar
		$this->view->bar = $bar = new Website_Model_Bar(1);
		$this->view->currentProducts = $bar->getProducts();
		//Zend_Debug::dump($this->view->currentProducts);
		//if form was posted, insert into database
		if($this->getRequest()->isPost()){
			$bar->removeProducts();
			$this->view->message='Die abgewählten Produkte wurden aus Ihrer Hausbar entfernt.';
			$products = $this->_getParam('product2bar');
			if(is_array($products)) {
				foreach($products as $product) {
					$bar->addProduct($product);
				}
				//print 'neue produkte hinzugefügt<br />';
				$this->view->message='Die ausgewählten Produkte wurden in Ihrer Hausbar angelegt.';
			} else {
				//print 'keine produkte wieder hinzugefügt<br />';
			}
			//print 'post-methode<br />';
		} else {
			//print 'get-methode<br />';
		}
	}

	public function meineHausbarPrintAction () {
		//fetch all products from the current bar
		$this->view->bar = $bar = new Bar(1);
		$this->view->currentProducts = $bar->getProducts();
	}

	public function meineFavoritenAction () {

	}

	public function meineCocktailsAction () {

	}

	public function meinCocktailbuchAction () {

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