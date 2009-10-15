<?php
/**
 * Infomail class for email messaging
 *
 */
class Website_Model_Infomail
{
	// attributes
	private $subject;
	private $message;

	/**
	 * magic getter for all attributes
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		if (property_exists(get_class($this), $name)) {
			return $this->$name;
		} else {
			throw new Exception('Class \''.get_class($this).'\' does not provide property: ' . $name . '.');
		}
	}

	/**
	 * Magic Setter Function, is accessed when setting an attribute
	 *
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function __set ( $name , $value ) {
		if (property_exists ( get_class($this), $name )) {
			$this->$name = $value ;
		} else {
			throw new Exception ( 'Class \''.get_class($this).'\' does not provide property: ' . $name . '.' ) ;
		}
	}

	/**
	 * Infomail constructor returns an empty Infomail object
	 *
	 * @return Infomail
	 */
	public function Infomail()
	{
	}

	/**
	 * sends the Infomail via smtp
	 *
	 */
	public function send ()
	{
		$config = Zend_Registry::get('config');
		$configMail = array('auth' => 'login',
		'username' => $config->mail->username,
		'password' => $config->mail->password);
		$transport = new Zend_Mail_Transport_Smtp($config->mail->smtp_server, $configMail);
		$email = new Zend_Mail('utf-8');
		$email->addTo($config->mail->defaultrecipient,$config->mail->defaultrecipientname);
		$email->setFrom($config->mail->defaultsender,$config->mail->defaultsendername);
		$email->setSubject('Infomail von Cocktailberater: '.$this->subject);
		$view = new Zend_View();
		$view->setBasePath($config->paths->project_path.'application/views/');
		$view->nachricht = $this->message;
		$html = $view->render('mail/infomail_html.phtml');
		$email->setBodyHtml($html);
		$email->send($transport);
	}
}
?>