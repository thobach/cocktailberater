<?php
/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

class Website_ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
		var_dump($errors);
    }
}

?>