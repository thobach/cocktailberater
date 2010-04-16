<?php 
error_reporting( E_ALL & E_STRICT); //  & ~E_NOTICE

define('BASE_PATH', realpath(dirname(__FILE__) . '/../../'));
define('APPLICATION_PATH', BASE_PATH . '/application');


// Include path
set_include_path(
    '.'
    . PATH_SEPARATOR . BASE_PATH . '/library'
    . PATH_SEPARATOR . get_include_path()
);

// Define application environment
define('APPLICATION_ENV', 'testing');

defined('SVN_REVISION')
	|| define('SVN_REVISION', exec('svnversion'));

defined('APPLICATION_VERSION')
	|| define('APPLICATION_VERSION', '0.8.1');
	
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
            
require_once 'controllers/ControllerTestCase.php';