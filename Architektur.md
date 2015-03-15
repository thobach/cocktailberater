## Systemverbund ##
Das System umfasst mehere, lose verbundene Systeme:

  * [Datenhaltung](Datanbasis.md)] in einer MySQL [Datenbank](Datenbank.md)
  * [Application Server](ApplicationServer.md)] mit der [Logic](Business.md)
  * [Küchenclient Client](KuechenclientClient.md) für den Cocktail Mixer in der Küche
  * [Bestellclient](Bestellclient.md) (evtl. in verschiedenen Versionen)
  * Anbindung zur [Webseite](Webseite.md) cocktailberater.de

Als Erweiterung wäre denkbar:

  * Bluetooth Gateway für Handys mit Clients

## Eingesetzte Versionen ##

Versionen auf dem Produktionsserver:
  * PHP 5.2.4 Standard
  * MySQL 5.0.51
  * Apache mit mod\_rewrite an (sollte in der phpinfo() erscheinen)

## SVN-Zugangsdaten ##
  * URL: svn://thobach.dyndns.org/repositoryname
  * Benutzername / Passwort: Systempasswort

Angelegte Repositories:
-server
-doku
-bestellclient

## Architektur TODOs ##
  * LDAP
  * SSH zu Domainfactory
  * Zend Framework checkout auf /Zend

## Architektur Skizze ##
![http://wiki.cocktailberater.de/images/a/a9/Netzwerk-villakunterbunt.png](http://wiki.cocktailberater.de/images/a/a9/Netzwerk-villakunterbunt.png)

Adobe Illustrator Datei: http://rapidshare.com/files/109337202/netzwerk_villakunterbunt.ai.html

## Dateistruktur ##
  * Dateistruktur auf dem Domainfactory-Server:
![http://wiki.cocktailberater.de/images/b/b1/Dateistruktur.png](http://wiki.cocktailberater.de/images/b/b1/Dateistruktur.png)

## Subdomainstruktur ##
  * www.cocktailberater.de und cocktailberater.de -> Webseite (life)
  * www-stage.cocktailberater.de -> Website (stage)
  * www-test.cocktailberater.de -> Website (test)
  * api.cocktailberater.de -> API (life)
  * api-stage.cocktailberater.de -> API (stage)
  * api-test.cocktailberater.de -> API (test)
  * wiki.cocktailberater.de -> Wiki (one and only)

## Datenbankstruktur ##

Es gibt drei verschiedene Datenbanken zum Testen (für lokalen Test, Test-Server und Stage-Umgebung).
Die Produktionsumgebung hat nur eine Datenbank zur Verfügung.

Das Anziehen der drei verschiedenen Datenbanken wird über den API-Key geregelt. Standardmäßig wird die "Life"-Datenbank angezogen.

Über eine Weboberfläche im Admin wird man über seinen API-Key eine andere Standard-Datenbank anziehen können.

Wird kein API-Key übergeben, so wird auch die "Life"-Datenbank angezogen.

## .htaccess auf den verschiedenen Umgebungen ##
  * Test-Server: http://thobach.dyndns.org
  * Anleitung für mod\_rewrite: http://enarion.net/web/apache/htaccess/mod_rewrite-on-suse/
  * .htaccess Datei:
> Options +FollowSymlinks
> RewriteEngine on
> RewriteRule !\.(php|js|ico|gif|jpg|png|css|pdf)$ index.php