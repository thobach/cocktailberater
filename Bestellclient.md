[![](http://create.ly/g6thx0ci1?nonsense=pic.png)](http://creately.com/app?diagID=g6thx0ci1)

## Funktionsbaum BestellClient ##

  * User
    * User identifizieren
    * User anlegen
    * User abmelden

  * Cocktails
    * anzeigen
    * suchen
    * bestellen
    * eingeben

  * Bestellungen
    * Status anzeigen
    * löschen (Wenn nicht in Bearbeitung)
    * ändern (Wenn nicht in Bearbeitung)

## User-Storys ##
  1. Als Gast kann ich die Stadt und Cocktailbar in der ich bin aussuchen, um dort später Cocktails zu bestellen.
    1. http://api.cocktailberater.de/party/get-all
    1. http://api.cocktailberater.de/party/add/name/tabas-bar/date/2008-10-11%2019:00:00/host/1/bar/1
  1. Als Gast kann ich Cocktails einer Cocktailbar nach Name, Zutat, Schlagwort, Geschmackskategorie, Alkoholfrei/Alkoholhaltig suchen, um sie später zu bestellen.
    1. http://api.cocktailberater.de/menu/get/id/1
  1. Als Gast kann ich <u>einen</u> Cocktail aus einer Cocktailbar bestellen (optional mit Kommentar), nachdem ich mir einen aus dem Katalog ausgesucht habe.
    1. http://localhost/cocktailberater.de/html/api/member/login/email/thobach@web.de/password-md5/5a105e8b9d40e1329780d62ea2265d8a
    1. http://localhost/cocktailberater.de/html/api/order/add/party/1/member/1/recipe/26/comment/ads/hashcode/95282d895d0c959235b777cd672cef32
  1. Als Gast kann ich nach einer Bestellung meine aktuelle Rechnung ansehen, meinen Alkoholgehalt angezeigt bekommen und erfahren auf welcher Bestellposition meine Bestellung ist.
    1. http://api.cocktailberater.de/member/get-invoice/party/1/member/1
  1. Als Gast kann ich mich registrieren, um später Cocktails zu bestellen.
    1. http://api.cocktailberater.de/member/add/passwordHash/mysecretinmd5withprefix/firstname/taba/lastname/luga/birthday/2008-09-12/email/test1@test.de
  1. (optional) Als Gast kann ich noch nicht bearbeitete Bestellungen stornieren, um einen Fehler korrigieren zu können. Zur Sicherheit, dass nicht schon mit der Bestellung angefangen wurde, muss dazu die Bestellung des Gasts in der Warteliste mindestens auf Position 6 sein.

## Global ##

## ToDo ##
Optional:
  * ewertung der Cocktails über den Bestellclient
  * Link zur Homepage


  * nach Bestellung soll Benutzer gefragt werden ob er sich ausloggen möchte oder nicht (Hinweis, dass andere sonst über ihn bestellen können)

  * Login - sec-string am anfang des pw fehlt noch
    * http://localhost/cocktailberater.de/html/api/member/login/email/test@test.de/password-md5/asd
    * sec-string lautet: gE3EcUy2
/ads/hashcode/4dce536c05da8614c87289bd54a9efe0


Registrierung
  * Als Gast kann ich mich registrieren, um später Cocktails zu bestellen.
    * http://localhost/cocktailberater.de/html/api/member/add/passwordHash/asd/firstname/taba/lastname/luga/birthday/2008-09-12/email/test@test.de
    * Ist das jahrt monat tag oder jahr tag monat
  * Email Validieren

  * ToDO für Thomas: der rsp ist nicht valid, es fehlt der status! Bitte einfügen!

## Tests ##

  * Test: Wenn während der Laufzeit des Programmes eine neue Bar erstellt wird, kann man dann durch "aktualisieren" die Bar auch auswählen? - Mal zusammen mit Thomas
  * Test: Aktualisieren der Cocktails - Mal mit Thomas zusammen