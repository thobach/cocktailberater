<?php
// TODO: übersetzen

class Website_Model_Newsletter
{
	// attributes
	private $id;
	private $name;
	private $description;
	private $insertDate;
	private $updateDate;

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
	 * Newsletter constructor returns a Newsletter object by an id, or an empty one
	 *
	 * @throws NewsletterException(Id_Wrong)
	 * @param integer optional $id
	 * @return Newsletter
	 * @tested
	 */
	public function Newsletter ($id=NULL){
		if(!empty($id)){
			$newsletterTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','newsletter');
			$newsletter = $newsletterTable->fetchRow('id='.$id);
			if(!$newsletter){
				throw new NewsletterException('Id_Wrong');
			}
			// attributes
			$this->id 			= $newsletter->id;
			$this->name 		= $newsletter->name;
			$this->description 	= $newsletter->description;
			$this->insertDate 	= $newsletter->insertDate;
			$this->updateDate 	= $newsletter->updateDate;
		}
	}

    
	public function anmelden ($email,$vorname=NULL,$nachname=NULL) {
		// TODO: write unit test
		try {
			$config = Zend_Registry::get('config');
			// wenn E-Mail-Adresse angegeben wurde
			if($email!=''){
				// Verbindung zu DB Tabelle herstellen
				$neuerEmpfaenger = new NewsletterEmpfaenger();
				$data = array(
				'email'     => $email,
				'vorname'   => $vorname,
				'nachname'  => $nachname
				);
				// Datensatz einfügen
				// wenn der Nutzer schon existiert wird eine Exception geworfen
				$neuerEmpfaenger->insert($data);
				// E-Mail versenden mit Link zur Bestätigung der Anmeldung
				$config = Zend_Registry::get('config');
				$configMail = array(
				'auth' => 'login',
				'username' => $config->mail->username,
				'password' => $config->mail->password);
				$transport = new Zend_Mail_Transport_Smtp($config->mail->smtp_server, $configMail);
				$mailView = new Zend_View();
				$mailView->uri = $config->paths->uri;
				$mailView->setBasePath($config->paths->project_path.'/application/views');
				$mailView->assign('email', $email);
				// E-Mail an Gast
				$bodyText = $mailView->render('mail/newsletter_anmeldung_txt.phtml');
				$bodyHtml = $mailView->render('mail/newsletter_anmeldung_html.phtml');
				$mail = new Zend_Mail('utf-8');
				$mail->setBodyText($bodyText);
				$mail->setBodyHtml($bodyHtml);
				$mail->setFrom($config->mail->defaultsender,$config->mail->defaultsendername);
				$mail->addTo($email);
				$mail->setSubject('Newsletteranmeldung - cocktailberater.de');
				$mail->send($transport);
				return '<div id="hinweis">
				<img src="'.$config->paths->uri.'img/ok.png" alt="Erfolg" title="Erfolg" align="top" />
				Glückwunsch: Ihre Anmeldung war erfolgreich, bitte schauen Sie in Ihr Postfach 
				und bestätigen Sie die Anmeldung!
				</div>';
			}
			else {
				return '<div id="achtung">
				<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
				Achtung: Es wurde keine E-Mail-Adresse angegeben!
				</div>';
			}
		} catch (Zend_Db_Adapter_Exception $e) {
			// Zugangsdaten falsch oder Datenbank nicht online
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Unsere Datenbank scheint momentan nicht online zu sein!
			</div>';
		} catch(Zend_Db_Statement_Exception $e) {
			// Abfrage an die Datenbank war fehlerhaft, evtl. gibt es den Nutzer schon
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Es gab ein Problem beim Anmelden, warscheinlich erhalten Sie schon
			unseren Newlsetter. Wenn nicht wenden Sie sich bitte an unseren Support!
			</div>';
		}
		catch (Zend_Mail_Protocol_Exception $e) {
			// E-Mail konnte nicht verschickt werden, also ist es wohl keine echte
			// E-Mail-Adresse (Eintrag aus DB löschen und Fehlermeldung)
			$this->abmeldungBestaetigen ($email);
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Ihre E-Mail-Adresse ist fehlerhaft, bitte korrigieren Sie sie. (z.B.: max.mustermann@email.de)!
			</div>';
		}
		catch (Zend_Exception $e) {
			// ein anderer Zend Framework Fehler
			// perhaps factory() failed to load the specified Adapter class
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Ein unbekannter Fehler ist aufgetreten, bitte kontaktieren Sie unseren Support!
			</div>';
		}
	}
	public function anmeldungBestaetigen ($email) {
		// TODO: write unit test
		try {
			$config = Zend_Registry::get('config');
			// Überprüfung ob E-Mail-Adresse angegeben wurde
			if($email!=''){
				// Verbindung zur DB Tabelle herstellen
				$vorhandenerEmpfaenger = new NewsletterEmpfaenger();
				// Bestätigung auf 'J' in der Tabelle setzen, wenn erfolgreich return
				if($vorhandenerEmpfaenger->update(
				array ('bestaetigt'=>'J'),
				'email = \''.$email.'\'')==1){
					return '<div id="hinweis">
					<img src="'.$config->paths->uri.'img/ok.png" alt="Erfolg" title="Erfolg" align="top" />
					Hinweis: Ihre Vervollständigung der Anmeldung war erfolgreich!
					Sie erhalten nun regelmäßig unseren Newsletter.
					</div>';
				}
				// Wenn es keine Veränderung an der Tabelle gab
				else {
					// User hat den Link schon mal geklickt
					if($vorhandenerEmpfaenger->fetchRow('email = \''.$email.'\'')){
						return '<div id="achtung">
						<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
						Achtung: Sie haben Ihre Anmeldung schon abgeschlossen!
						</div>';
					}
					// Abonnent existiert gar nicht
					else {
						return '<div id="achtung">
						<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
						Achtung: Sie sind nicht im System angemeldet! Wenn Sie sich beim Newsletter
						anmelden wollen, klicken Sie bitte auf der Startseite auf Newsletter.
						</div>';
					}
				}
			}
			else {
				return '<div id="achtung">
				<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
				Achtung: Es wurde keine E-Mail-Adresse angegeben!
				</div>';
			}
		} catch (Zend_Db_Adapter_Exception $e) {
			// Zugangsdaten falsch oder Datenbank nicht online
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Unsere Datenbank scheint momentan nicht online zu sein!
			</div>';
		} catch(Zend_Db_Statement_Exception $e) {
			// Abfrage an die Datenbank war fehlerhaft
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Es gab ein Problem beim Anmelden, bitte wenden Sie sich an unseren Support!
			</div>';
		}
		catch (Zend_Exception $e) {
			// ein anderer Zend Framework Fehler
			// perhaps factory() failed to load the specified Adapter class
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Ein unbekannter Fehler ist aufgetreten, bitte kontaktieren Sie unseren Support!
			</div>';
		}
	}
	public function abmelden ($email) {
		// TODO: write unit test
		try {
			$config = Zend_Registry::get('config');
			// wenn eine E-Mail-Adresse angegeben wurde
			if($email!=''){
				$vorhandenerEmpfaenger = new NewsletterEmpfaenger();
				// Überprüfung oder der Abonnent in der DB vorhanden ist
				if(count($vorhandenerEmpfaenger->fetchRow('email = \''.$email.'\''))==1){
					// E-Mail mit Abmeldelink verschicken
					$config = Zend_Registry::get('config');
					$configMail = array(
					'auth' => 'login',
					'username' => $config->mail->username,
					'password' => $config->mail->password);
					$transport = new Zend_Mail_Transport_Smtp($config->mail->smtp_server, $configMail);
					$mailView = new Zend_View();
					$mailView->uri = $config->paths->uri;
					$mailView->setBasePath($config->paths->project_path.'/application/views');
					$mailView->assign('email', $email);
					// E-Mail an Gast
					$bodyText = $mailView->render('mail/newsletter_abmeldung_txt.phtml');
					$bodyHtml = $mailView->render('mail/newsletter_abmeldung_html.phtml');
					$mail = new Zend_Mail('utf-8');
					$mail->setBodyText($bodyText);
					$mail->setBodyHtml($bodyHtml);
					$mail->setFrom($config->mail->defaultsender,$config->mail->defaultsendername);
					$mail->addTo($email);
					$mail->setSubject('Newsletterabmeldung - cocktailberater.de');
					$mail->send($transport);
					return '<div id="hinweis">
					<img src="'.$config->paths->uri.'img/ok.png" alt="Erfolg" title="Erfolg" align="top" />
					Hinweis: Ihre Newsletterabmeldung ist fast komplett, Sie haben soeben eine 
					E-Mail mit dem Bestätigungslink zur Abmeldung bekommen! Bitte schauen Sie in Ihr
					Postfach und bestätigen die Abmeldung, indem Sie auf den angegebenen Link klicken.
					</div>';
				}
				// keinen Abonnent unter der E-Mail-Adresse gefunden
				else {
					return '<div id="achtung">
					<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
					Achtung: Sie sind nicht im System angemeldet!
					</div>';
				}
			}
			// wenn die E-Mail-Adresse nicht vorhanden war
			else {
				return '<div id="achtung"><img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" /> Achtung: Ihre E-Mail-Adresse war leer!</div>';
			}
		} catch (Zend_Db_Adapter_Exception $e) {
			// Zugangsdaten falsch oder Datenbank nicht online
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Unsere Datenbank scheint momentan nicht online zu sein!
			</div>';
		} catch(Zend_Db_Statement_Exception $e) {
			// Abfrage an die Datenbank war fehlerhaft
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Es gab ein Problem beim Abmelden, bitte wenden Sie sich an unseren Support!
			</div>';
		}
		catch (Zend_Exception $e) {
			// ein anderer Zend Framework Fehler
			// perhaps factory() failed to load the specified Adapter class
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Ein unbekannter Fehler ist aufgetreten, bitte kontaktieren Sie unseren Support!
			</div>';
		}
	}
	
	public function abmeldungBestaetigen ($email) {
		// TODO: write unit test
		try {
			$config = Zend_Registry::get('config');
			// E-Mail-Adresse wurde ausgefüllt
			if($email!=''){
				// Verbindung zur DB-Tabelle herstellen
				$vorhandenerEmpfaenger = new NewsletterEmpfaenger();
				// Versuch, den Abonnenten zu löschen
				if($vorhandenerEmpfaenger->delete('email = \''.$email.'\'')==1){
					return '<div id="hinweis">
					<img src="'.$config->paths->uri.'img/ok.png" alt="Erfolg" title="Erfolg" align="top" />
					Hinweis: Sie wurden erfolgreich vom cocktailberater.de Newsletter abgemeldet!
					</div>';
				} // if
				// Wenn das Löschen fehlschlug, existierte der Abonnent nicht mehr
				else {
					return '<div id="achtung">
					<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
					Achtung: Sie sind nicht im System angemeldet!
					</div>';
				} // else
			} // if
			// wenn keine E-Mail-Adresse angegeben wurde
			else {
				return '<div id="achtung">
				<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
				Achtung: Ihre E-Mail-Adresse war leer!
				</div>';
			} // else
		} catch (Zend_Db_Adapter_Exception $e) {
			// Zugangsdaten falsch oder Datenbank nicht online
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Unsere Datenbank scheint momentan nicht online zu sein!
			</div>';
		} catch(Zend_Db_Statement_Exception $e) {
			// Abfrage an die Datenbank war fehlerhaft
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Es gab ein Problem beim Abmelden, bitte wenden Sie sich an unseren Support!
			</div>';
		}
		catch (Zend_Exception $e) {
			// ein anderer Zend Framework Fehler
			// perhaps factory() failed to load the specified Adapter class
			return '<div id="achtung">
			<img src="'.$config->paths->uri.'img/achtung.png" alt="Achtung" title="Achtung" align="top" />
			Achtung: Ein unbekannter Fehler ist aufgetreten, bitte kontaktieren Sie unseren Support!
			</div>';
		}
	}
	
	/**
	 * returns an array of all Newsletter objects
	 *
	 * @return array Bar
	 */
	public static function listNewsletter()
	{
		// TODO: write unit test
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','newsletter');
		foreach ($table->fetchAll() as $newsletter) {
			$newsletterArray[] = Website_Model_CbFactory::factory('Website_Model_Newsletter',$newsletter->id);
		}
		return $newsletterArray;
	}

	/**
	 * @tested
	 */
	public function save (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'newsletter');
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$this->id = $table->insert($data);
		}
		else {
			$table->update($this->databaseRepresentation(),'id='.$this->id);
		}
	}
	
	/**
	 * @tested
	 */
	public function delete (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'newsletter');
		$table->delete('id='.$this->id);
		CbFactory::destroy('Newsletter',$this->id);
		unset($this);
	}
		
	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$array['name'] = $this->name;
		$array['description'] = $this->description;
		return $array;
	}
	
	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 */
	public function toXml($xml, $branch) {
		// TODO: write unit test
		$newsletter = $xml->createElement('newsletter');
		$newsletter->setAttribute('id', $this->id);
		$newsletter->setAttribute('name', $this->name);
		$newsletter->setAttribute('description', $this->description);
		$newsletter->setAttribute('insertDate', $this->insertDate);
		$newsletter->setAttribute('updateDate', $this->updateDate);
		$branch->appendChild($newsletter);
	}


}
?>