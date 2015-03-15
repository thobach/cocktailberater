## Agenda ##
  1. aktueller Stand
    1. Architektur
    1. Datenbank
    1. Testserver
    1. API
    1. Clients
    1. Webseite
    1. Corporate Design (Logo)
    1. ...
  1. Erinnerung: Absprache mit Werner am Dienstag
  1. Ergebnis Pair-Programming (Nic)
  1. Maillingliste: entwickler@cocktailberater.de (Thomas)
  1. weiteres Vorgehen

## Protokoll ##
  * Architektur:
    * API Schlüssel in Datei ablegen, daran Datenbank knüpfen
  * Datenbank
    * aktuelles Schema ist da, mit InnoDB ist die referenzielle Integrität weitgehend sichergestellt (Lösch- und Updateweitergabe klappt aber nicht über n:m Beziehnung)
  * Test-Server
    * SVN Commit <s>ermöglichen</s> geht
  * API
    * erstmal programmieren, dann per API-Explorer testen und im Wiki die Input-Parameter & Beispielanfragen beschreiben
  * Clients
    * Küchen & Bestellclient
      * Visual Studio als IDE
    * Statusanzeige
      * Markus + Tobias (Pair Programming)
      * in PHP / ZendFramework
      * an API gekoppelt
      * gut dokumentiert als Beispiel zum nachprogrammieren / erweitern für Externe
  * API Explorer
    * Nic überlegt sich die Umsetzung
  * Website
    * offline, da Dateiumstrukturierung
  * Corporate Design (Logo)
    * Logo und Schriftzug Nr. 1, aber dunkleres Lila (wie im header jetzt)
  * Links:
    * Klassendiagramm: http://wiki.cocktailberater.de/index.php?title=Application_Server
    * ER-Diagramm: http://wiki.cocktailberater.de/index.php?title=Application_Server


  * Idee eines Softwareverteilungs-Prozesses:
  1. SVN Update um mit letzter Version zu arbeiten
  1. Test schreiben, welcher die neue Funktion testet
  1. Funktion schreiben
  1. lokal testen mit verschiedenen Datenbanken, wenn nicht erfolgreich, dann zu 3.
  1. SVN Update um Konflikte aufzuspüren, wenn Konflikt, dann lösen
  1. SVN Commit
  1. Deployment auf Test über Deploymentskript
  1. Integrationstest auf Test mit verschiedenen Datenbanken, wenn erfolglos, zu 3.
  1. Deployment auf Stage, Test mit verschiedenen Datenbanken, wenn erfolglos, dann zu 3.
  1. Deployment auf Life, Test, wenn erfolglos, letzte Version zurückspielen

-Testen mit verschiedenen Datenständen-
Schleife mit allen Datenbanken für Tests durchlaufen

  * Application Server mit PHPUnit testen
  * API mit HTTPUnit testen
  * Webseite mit ??? (Screenshotservice) testen
  * Küchenclient mit .Net Unittests testen
  * Bestellclient mit .Net Unittests testen
  * Warteanzeige mit PHPUnit testen

## Entscheidung Logo mit Schriftzug ##

![http://wiki.cocktailberater.de/images/c/c9/Logo.png](http://wiki.cocktailberater.de/images/c/c9/Logo.png)