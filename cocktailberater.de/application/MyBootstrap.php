<?php
class MyBootstrap extends Zend_Application_Bootstrap_Bootstrap {

	/*
	// bad idea -> home page w/o /website and counter doesn't work
	protected function _initPageCache(){
		$config = $this->getOptions();
		if($config['pagecache']['enabled']=='true') {
			if(APPLICATION_ENV == 'development'){
				$debug = true;
			} else {
				$debug = false;
			}
			$frontendOptionsPage = array(
			   'lifetime' => 7200,
			   'debug_header' => $debug, // für das Debuggen
			   'regexps' => array(
			// cache den gesamten IndexController
			       '^/website' => array(
			       		'cache' => true,
						'cache_with_cookie_variables' => true,
					 	'cache_with_session_variables' => true
			)
			)
			);
			$backendOptionsPage = array(
			    'cache_dir' => realpath(APPLICATION_PATH.'/../tmp/')
			);
			$cachePage = Zend_Cache::factory('Page','File',$frontendOptionsPage,$backendOptionsPage);
			$cachePage->start();
		}
	}
	*/

	/**
	 * Initialize Logger depending on application settings / environment
	 *
	 * @return void
	 */
	protected function _initLogger(){
		date_default_timezone_set('Europe/Berlin');
		
		// get application settings for logging
		$logOption = $this->getOption('logger');
		// logging enabled
		if($logOption['enabled']=='true') {
			try{
				if($logOption['type']=='firebug'){
					$writer = new Zend_Log_Writer_Firebug();
				} else if ($logOption['type']=='logfile'){
					$writer = new Zend_Log_Writer_Stream(realpath($logOption['logfile']));
				}
				$log = new Zend_Log($writer);
				$log->log('MyBootstrap->_initLogger: logger started',Zend_Log::DEBUG);
				//$log->addFilter(new Zend_Log_Filter_Priority(Zend_Log::CRIT));
			} 
			// log file not writeable
			catch (Zend_Exception $e){
				$writer = new Zend_Log_Writer_Null();
				$log = new Zend_Log($writer);
				echo $e->getMessage(). ' -> note: chmod 666 would solve it ;)';
			}
		}
		// logging disabled
		else {
			$writer = new Zend_Log_Writer_Null();
			$log = new Zend_Log($writer);
		}
		// register logger in registry
		Zend_Registry::set('logger',$log);
	}

	/**
	 * Initialize Cache
	 *
	 * @return void
	 */
	protected function _initCache(){
		$cacheOptions = $this->getOption('cache');
		$log = Zend_Registry::get('logger');
		if($cacheOptions['enabled']=='true') {
			$log->log('MyBootstrap->_initCache: cache enabled',Zend_Log::DEBUG);
			$cacheEnabled = true;
		} else {
			$log->log('MyBootstrap->_initCache: cache disabled',Zend_Log::DEBUG);
			$cacheEnabled = false;
		}

		// normal cache
		$frontendOptions = array(
			'lifetime' => 259200, // cache lifetime of 3 days
			'automatic_serialization' => true,
			'caching' => $cacheEnabled
		);

		$backendOptions = array(
		    'cache_dir' => realpath(APPLICATION_PATH.'/../tmp/') // Directory where to put the cache files
		);
		// getting a Zend_Cache_Core object
		$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
		// add Cache object to registry
		Zend_Registry::set ('cache',$cache);
		// Register the Cache object for Zend_Locale
		Zend_Locale::setCache($cache);
		// register cache for meta data of database tables
		//Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
	}

	/**
	 * Initialize Plugins like layout switch
	 *
	 * @return void
	 */
	protected function _initPlugins() {
		$bootstrap = $this->getApplication();
		if ($bootstrap instanceof Zend_Application) {
			$bootstrap = $this;
		}
		$bootstrap->bootstrap('FrontController');
		$front = $bootstrap->getResource('FrontController');
		require_once('Wb/Controller/Plugin/Layout.php');
		$plugin = new Wb_Controller_Plugin_Layout();
		$front->registerPlugin($plugin);
	}

	/**
	 * Initialze Routes, contains a list of all REST Controllers which should
	 * be affected by the rest route
	 *
	 * @return void
	 */
	public function _initRouter() {
		$front     = Zend_Controller_Front::getInstance();
		// when adding a new rest controller, add it here to the list
		$restControllers = array(
			'bar','cocktail','comment','component','glass',
			'guest','ingredient','ingredient-category',
		    'manufacturer','member','menue','order','party',
		    'photo','photoCategory','product','rating',
		    'recipe','recipeCategory','session','tag','video');
		$restRoute = new Zend_Rest_Route($front, array(),
		array('website' => $restControllers));
		
		
		$recipesRoute = new Zend_Controller_Router_Route(
		    'rezept',
		    array('controller' => 'recipe', 'action' => 'index', 'module' => 'website')
		);
		
		$recipesAlcoholicRoute = new Zend_Controller_Router_Route(
		    'alkoholische-cocktailrezepte',
		    array(	'controller' => 'recipe', 'action' => 'index', 
		    		'module' => 'website', 'search_type' => 'alcoholic')
		);
		
		$recipesNonAlcoholicRoute = new Zend_Controller_Router_Route(
		    'alkoholfreie-cocktailrezepte',
		    array(	'controller' => 'recipe', 'action' => 'index', 
		    		'module' => 'website', 'search_type' => 'non-alcoholic')
		);
		
		$recipesTop10Route = new Zend_Controller_Router_Route(
		    'top10-cocktailrezepte',
		    array(	'controller' => 'recipe', 'action' => 'index', 
		    		'module' => 'website', 'search_type' => 'top10')
		);
		
		$recipesEasyRoute = new Zend_Controller_Router_Route(
		    'einfache-cocktailrezepte',
		    array(	'controller' => 'recipe', 'action' => 'index', 
		    		'module' => 'website', 'search_type' => 'difficulty',
		    		'search' => 'beginner')
		);
		
		$recipesVodkaRoute = new Zend_Controller_Router_Route(
		    'vodka-cocktail-rezepte',
		    array(	'controller' => 'recipe', 'action' => 'index', 
		    		'module' => 'website', 'search_type' => 'ingredient',
		    		'search' => 'Vodka')
		);
		
		$recipesRumRoute = new Zend_Controller_Router_Route(
		    'rum-cocktail-rezepte',
		    array(	'controller' => 'recipe', 'action' => 'index', 
		    		'module' => 'website', 'search_type' => 'ingredient',
		    		'search' => 'Rum')
		);
		
		$recipesImagesRoute = new Zend_Controller_Router_Route(
		    'cocktailrezepte-mit-bildern',
		    array(	'controller' => 'recipe', 'action' => 'index', 
		    		'module' => 'website', 'search_type' => 'image')
		);
		
		$recipesClassicsRoute = new Zend_Controller_Router_Route(
		    'cocktail-klassiker',
		    array(	'controller' => 'recipe', 'action' => 'index', 
		    		'module' => 'website', 'search_type' => 'tag',
		    		'search' => 'Klassiker')
		);
		
		$recipesOriginalRoute = new Zend_Controller_Router_Route(
		    'original-cocktail-rezepte',
		    array(	'controller' => 'portal', 'action' => 'authors', 
		    		'module' => 'website')
		);
		
		$recipeRoute = new Zend_Controller_Router_Route(
		    'rezept/:id',
		    array('controller' => 'recipe', 'action' => 'get', 'module' => 'website')
		);
		
		$ingredientsRoute = new Zend_Controller_Router_Route(
		    'zutat',
		    array('controller' => 'ingredient', 'action' => 'index', 'module' => 'website')
		);
			
		$ingredientRoute = new Zend_Controller_Router_Route(
		    'zutat/:id',
		    array('controller' => 'ingredient', 'action' => 'get', 'module' => 'website')
		);
		
		$productsRoute = new Zend_Controller_Router_Route(
		    'produkt',
		    array('controller' => 'product', 'action' => 'index', 'module' => 'website')
		);
		
		$productRoute = new Zend_Controller_Router_Route(
		    'produkt/:id',
		    array('controller' => 'product', 'action' => 'get', 'module' => 'website')
		);
		
		$manufacturersRoute = new Zend_Controller_Router_Route(
		    'hersteller',
		    array('controller' => 'manufacturer', 'action' => 'index', 'module' => 'website')
		);
		
		$manufacturerRoute = new Zend_Controller_Router_Route(
		    'hersteller/:id',
		    array('controller' => 'manufacturer', 'action' => 'get', 'module' => 'website')
		);
		
		$front->getRouter()->addRoute('recipes', $recipesRoute);
		$front->getRouter()->addRoute('recipes-alcoholic', $recipesAlcoholicRoute);
		$front->getRouter()->addRoute('recipes-non-alcoholic', $recipesNonAlcoholicRoute);
		$front->getRouter()->addRoute('recipes-top10', $recipesTop10Route);
		$front->getRouter()->addRoute('recipes-easy', $recipesEasyRoute);
		$front->getRouter()->addRoute('recipes-vodka', $recipesVodkaRoute);
		$front->getRouter()->addRoute('recipes-rum', $recipesRumRoute);
		$front->getRouter()->addRoute('recipes-images', $recipesImagesRoute);
		$front->getRouter()->addRoute('recipes-classics', $recipesClassicsRoute);
		$front->getRouter()->addRoute('recipes-original', $recipesOriginalRoute);
		$front->getRouter()->addRoute('recipe', $recipeRoute);
		$front->getRouter()->addRoute('ingredients', $ingredientsRoute);
		$front->getRouter()->addRoute('ingredient', $ingredientRoute);
		$front->getRouter()->addRoute('products', $productsRoute);
		$front->getRouter()->addRoute('product', $productRoute);
		$front->getRouter()->addRoute('manufacturers', $manufacturersRoute);
		$front->getRouter()->addRoute('manufacturer', $manufacturerRoute);
		$front->getRouter()->addRoute('rest', $restRoute);
		
		// Returns the router resource to bootstrap resource registry
		return $router;
	}

	/**
	 * Sets the baseUrl
	 * bad idea to do it this way
	 * @return void
	 */
	/*public function _initUrl(){
		Zend_Controller_Front::getInstance()->setBaseUrl('http://'.$_SERVER['HTTP_HOST']);
	}*/

	
}
