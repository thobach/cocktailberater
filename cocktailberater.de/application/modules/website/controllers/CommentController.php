<?php
/**
 * Context sensitive Controller for comment matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_CommentController extends Wb_Controller_RestController {
	
	public function indexAction() {
		$this->view->comments = Website_Model_Comment::listComments();
	}

	public function getAction(){
		
	}
	
	public function postAction(){
		// check if recipe id given
		if($this->_getParam('recipe')=='' OR $this->_getParam('recipe')==0){
			// if the id parameter is missing, throw exception
			throw new RecipeException('Id_Missing');
		} else {
			// form validation
			$this->view->comment_error = array();
			// check valid comment text
			if (str_word_count($this->_getParam ( 'comment' ))<=0) {
				$this->view->comment_error['comment'] = 'Das Kommentarfeld war leer.';
			}
			// check valid captcha
			$captcha = new Zend_Captcha_Image();
			if (!$captcha->isValid($this->_getParam('captcha'))) {
				$this->view->comment_error['captcha'] = 'Der Spamschutz war inkorrekt.';
			}
			// check valid email if any given
			$emailValidator = new Zend_Validate_EmailAddress();
			if ($this->_getParam ( 'email' ) && 
				!$emailValidator->isValid($this->_getParam ( 'email' ))) {
				$this->view->comment_error['email'] = $emailValidator->getMessages();
			}
			// check valid name if any given (alpha & max. 45 char)
			$nameAlphaValidator = new Zend_Validate_Alpha();
			if ($this->_getParam ( 'name' ) && 
				!$nameAlphaValidator->isValid($this->_getParam ( 'name' ))) {
				$this->view->comment_error['name'] = $nameAlphaValidator->getMessages();
			}
			$nameLengthValidator = new Zend_Validate_StringLength(array('max'=>'45'));
			if ($this->_getParam ( 'name' ) && 
				!$nameLengthValidator->isValid($this->_getParam ( 'name' ))) {
				$this->view->comment_error['name'] = array_merge(
					$nameLengthValidator->getMessages(),
					$this->view->comment_error['name']);
			}
			// insert into database if no errors
			if(count($this->view->comment_error)==0){
				$comment = new Website_Model_Comment ( ) ;
				$comment->recipeId = mysql_escape_string(strip_tags($this->_getParam ( 'recipe' )));
				//$comment->memberId = $this->_getParam ( 'member' );
				$comment->memberId = NULL;
				$comment->name = $this->_getParam ( 'name' );
				$comment->email = $this->_getParam ( 'email' );
				$comment->comment = mysql_escape_string(strip_tags($this->_getParam ( 'comment' )));
				$comment->ip = mysql_escape_string(strip_tags($_SERVER [ 'REMOTE_ADDR' ]));
				$comment->save();
				$this->view->comment_success = 'Vielen Dank für deinen Kommentar!';
			}
			$this->_forward('get','recipe','website',array('id'=>$this->_getParam('recipe')));
		}
	}

}