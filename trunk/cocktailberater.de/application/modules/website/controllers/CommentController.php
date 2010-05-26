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
		// wenn ein Cocktail angegeben wurde
		if($this->_getParam('recipe')=='' OR $this->_getParam('recipe')==0){
			// if the id parameter is missing, throw exception
			throw new RecipeException('Id_Missing');
		} else {
			// Kommentieren
			if ($this->_hasParam ( 'comment' )) {
				$captcha = new Zend_Captcha_Image();
				if($captcha->isValid($this->_getParam('captcha'))){
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
				} else {
					$this->view->comment_error = 'Leider ist ein Fehler beim Einfügen deines Kommentares aufgetreten. Bitte überprüfe deine Eingabe!';
				}
				$this->_forward('get','recipe','website',array('id'=>$this->_getParam('recipe')));
			}
		}
	}

}