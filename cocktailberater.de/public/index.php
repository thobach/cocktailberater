<?php
// forward requests without www to www.cocktailberater.de and set status 301
if($_ENV['HTTP_HOST']=='cocktailberater.de'){
	header("Location: http://www.cocktailberater.de".$_ENV['REQUEST_URI'],TRUE,301);
}

// Define path to application directory
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('SVN_REVISION')
	|| define('SVN_REVISION', exec('svnversion'));
	
// Define application environment
if($_ENV['HTTP_HOST']=='www-test.cocktailberater.de'){
	putenv('APPLICATION_ENV=testing');
} else if($_ENV['HTTP_HOST']=='www-stage.cocktailberater.de'){
	putenv('APPLICATION_ENV=staging');
}

defined('APPLICATION_ENV')
	|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined('APPLICATION_VERSION')
	|| define('APPLICATION_VERSION', '1.0.0');
	
// Ensure library/ is on include_path
set_include_path(
	implode(PATH_SEPARATOR, array(
		realpath(APPLICATION_PATH . '/../library'),
		get_include_path())
	)
);

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
	APPLICATION_ENV,
	APPLICATION_PATH . '/modules/default/config/application.ini');
$application->bootstrap()->run();
