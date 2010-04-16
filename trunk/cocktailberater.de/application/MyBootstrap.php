<?php
class MyBootstrap extends Zend_Application_Bootstrap_Bootstrap {

	/**
	 * Initialize Cache
	 *
	 * @return void
	 */
	protected function _initCache(){
		if($config->cache->enabled=='true') {
			$cacheEnabled = true;
		} else {
			$cacheEnabled = false;
		}

		$frontendOptions = array(
			'lifetime' => 7200, // cache lifetime of 2 hours
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
	}

	/**
	 * Initialize Logger
	 *
	 * @return void
	 */
	protected function _initLogger(){
		date_default_timezone_set('Europe/Berlin');
		
		$logOption = $this->getOption('logger');
		//Zend_Debug::dump($logOption['enabled']);
		if($logOption['enabled']=='true') {
			try{
				if($logOption['type']=='firebug'){
					$writer = new Zend_Log_Writer_Firebug();
				} else if ($logOption['type']=='logfile'){
					$writer = new Zend_Log_Writer_Stream(realpath($logOption['logfile']));
				}
				$log = new Zend_Log($writer);
				$log->log('logger started',Zend_Log::DEBUG);
				//$log->addFilter(new Zend_Log_Filter_Priority(Zend_Log::CRIT));
			} catch (Zend_Exception $e){
				$writer = new Zend_Log_Writer_Null();
				$log = new Zend_Log($writer);
				echo $e->getMessage(). ' -> note: chmod 666 would solve it ;)';
			}
		} else {
			$writer = new Zend_Log_Writer_Null();
			$log = new Zend_Log($writer);
		}
		Zend_Registry::set('logger',$log);
	}

	/**
	 * Initialize Plugins
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
	 * Initialze Routes, dcntains a list of all REST Controllers which should
	 * be affected by the rest route
	 *
	 * @return void
	 */
	public function _initRouter() {
		$front     = Zend_Controller_Front::getInstance();
		// when adding a new rest controller, add it here to the list
		$restControllers = array(
			'bar','cocktail','comment','component','glass',
			'guest',
		    'ingredient','ingredientCategory',
		    'manufacturer','member','menue','order','party',
		    'photo','photoCategory','product','rating',
		    'recipe','recipeCategory','session','tag','video');
		$restRoute = new Zend_Rest_Route($front, array(),
		array('website' => $restControllers));
		$front->getRouter()->addRoute('rest', $restRoute);
		// Returns the router resource to bootstrap resource registry
		return $router;
	}
}
