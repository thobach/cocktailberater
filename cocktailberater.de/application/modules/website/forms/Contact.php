<?php

/**
 * This is the contact us form.
 */
class Website_Form_Contact extends Zend_Form
{
	/**
	 * @return void
	 */
	public function init()
	{
		// add translation for default layout
		$translate =  new Zend_Translate('tmx',
		APPLICATION_PATH.DIRECTORY_SEPARATOR.'languages'.DIRECTORY_SEPARATOR.'contact.tmx','de');
				// register translation object for view helpers
		Zend_Registry::set('Zend_Translate',$translate);
		
		/*$this->addElementPrefixPath('Default_Form_Decorator',
                            'Form/Decorator/',
                            'decorator');
		*/
		$this->setMethod('post');
		$this->addElement('radio', 'salutation', array(
            'label'      	=> 'Anrede'.':',
			'multiOptions'	=>	
		array (
		'Frau'=>'Frau',
		'Herr'=>'Herr',
		'Firma'=>'Firma'
		),
            'required'   	=> false));

		/*$this->addElement('text', 'company', array(
            'label'      => 'Firma'.':',
            'required'   => false,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 20))
		)
		));*/

		$this->addElement('text', 'firstname', array(
            'label'      => 'Vorname'.'*:',
			'class'		 => 'text', 
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 20))
		)
		));

		$this->addElement('text', 'lastname', array(
            'label'      => 'Nachname'.'*:',
			'class'		 => 'text', 
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 20))
		)
		));

		$this->addElement('text', 'street', array(
            'label'      => 'Straße'.':',
			'class'		 => 'text', 
            'required'   => false,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 20))
		)
		));

		$this->addElement('text', 'number', array(
            'label'      => 'Hausnummer'.':',
			'class'		 => 'text', 
            'required'   => false,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 20))
		)
		));

		$this->addElement('text', 'zip', array(
            'label'      => 'Postleitzahl'.':',
			'class'		 => 'text', 
            'required'   => false,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 20))
		)
		));

		$this->addElement('text', 'city', array(
            'label'      => 'Ort'.':',
			'class'		 => 'text', 
            'required'   => false,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 20))
		)
		));

		$this->addElement('text', 'phone', array(
            'label'      => 'Telefonnummer'.':',
			'class'		 => 'text', 
            'required'   => false,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 30))
		)
		));

		$this->addElement('text', 'mobile', array(
            'label'      => 'Mobiltelefon'.':',
			'class'		 => 'text', 
            'required'   => false,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 30))
		)
		));

		/*$this->addElement('text', 'fax', array(
            'label'      => 'Faxnummer'.':',
            'required'   => false,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 30))
		)
		));*/

		$this->addElement('text', 'email', array(
            'label'      => 'E-Mail Adresse'.'*:',
			'class'		 => 'text', 
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'EmailAddress')
		)
		));

		$this->addElement('text', 'subject', array(
            'label'      => 'Betreff'.'*:',
			'class'		 => 'text', 
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0, 40))
		)
		));

		$this->addElement('textarea', 'message', array(
            'label'      => 'Ihre Nachricht'.'*:',
			'class'		 => 'text', 
			'cols'		 => 50,
			'rows'		 => 10,
            'required'   => true,
            'validators' => array(
		array('validator' => 'StringLength', 'options' => array(0))
		)
		));

		$captcha = new Zend_Captcha_Image(array(
			    'name' => 'captcha',
				'font' => realpath(APPLICATION_PATH.'/../public/fonts').'/teen____.ttf',
				'imgDir' => realpath(APPLICATION_PATH.'/../public/img/captchas/'),
				'imgUrl' => '/img/captchas/',
			    'expiration' => 300,
				'wordLen' => 5,
				'width' => 120
			));
		
		$this->addElement('captcha','captcha',array(
            'label'      => 'Bitte geben Sie die 5 unten dargestellten Buchstaben ein'.'*:',
            'required'   => true,
			'class'		 => 'text',
            'captcha'    => $captcha
		));

		$getCopyField = new Zend_Form_Element_Checkbox('getCopy');
		$getCopyField->setCheckedValue('1');
		$getCopyField->setUncheckedValue('0');

		$getCopyField->setLabel('Ich möchte eine Kopie der Kontaktanfrage per E-Mail erhalten') ;
		$checkboxDecorator = new Website_Form_Decorator_Checkbox();
		$getCopyField->setDecorators(array($checkboxDecorator));
		$getCopyField->setRequired(false);
		$this->addElement($getCopyField);

		$this->addElement('submit', 'submit', array(
            'label'    => 'Kontaktieren Sie Uns',
		));

	}
}
