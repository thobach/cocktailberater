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
				$recaptcha = new Zend_Service_ReCaptcha('6LdKvggAAAAAAAhu1rSAwtIm1Ejzls4LDi0K27Td', '6LdKvggAAAAAADoKDLXugjbiSxuTc7zdrAVm8-qC');
				try{
					$result = $recaptcha->verify(
					$this->_getParam ( 'recaptcha_challenge_field' ),
					$this->_getParam ( 'recaptcha_response_field' )
					);
					if ($result->isValid()) {
						$comment = new Website_Model_Comment ( ) ;
						$comment->recipeId = mysql_escape_string(strip_tags($this->_getParam ( 'recipe' )));
						//$comment->memberId = $this->_getParam ( 'member' );
						$comment->memberId = NULL;
						$comment->comment = mysql_escape_string(strip_tags($this->_getParam ( 'comment' )));
						$comment->ip = mysql_escape_string(strip_tags($_SERVER [ 'REMOTE_ADDR' ]));
						//Zend_Debug::dump($comment);
						//exit;
						$comment->save();
						$this->view->comment_success = 'Vielen Dank für deinen Kommentar!';
					} else {
						$this->view->comment_error = 'Leider ist ein Fehler beim Einfügen deines Kommentares aufgetreten. Bitte überprüfe deine Eingabe!';
					}
				} catch (Zend_Exception $e){
					$this->view->comment_error = 'Leider ist ein Fehler beim Einfügen deines Kommentares aufgetreten. Bitte überprüfe deine Eingabe!';
				}
				$this->_forward('get','recipe','website',array('id'=>$this->_getParam('recipe')));
			}
		}
	}

}