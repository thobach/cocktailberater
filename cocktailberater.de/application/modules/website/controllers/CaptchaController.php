<?php
/**
 * Context sensitive Controller for captcha matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_CaptchaController extends Wb_Controller_RestController {
	
	public function postAction(){
		$captcha = new Zend_Captcha_Image(array(
			    'name' => 'captcha',
				'font' => realpath(APPLICATION_PATH.'/../public/fonts').'/teen____.ttf',
				'imgDir' => realpath(APPLICATION_PATH.'/../public/img/captchas/'),
				'imgUrl' => $this->view->baseUrl().'/img/captchas/',
			    'expiration' => 300,
				'wordLen' => 5,
				'width' => 120
			));
		$this->view->captchaId = $captcha->generate();
		$this->view->imageTag = $captcha->render($this->view);
	}

}