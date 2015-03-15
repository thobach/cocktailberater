Der ApplicationServer ist das Herz des Cocktailberaters und beinhaltet die Business Logic. Er bedient die [Webseite](Webseite.md) und den [Bestell](Bestellclient.md)- und [Küchenclient] über die [API](API.md).

Er wird objektorientiert in [PHP 5](http://www.php.net) geschrieben und nutzt das [Zend Framework](http://framework.zend.com/).

## Funktionsbaum ApplicationServer ##
  * Kunden
    * Kunden verwalten
    * Foto hochladen

  * Bestellung
    * Bestellungen verwalten

  * Zutaten
    * Zutaten verwalten

  * Produkte
    * Produkte verwalten

  * Cocktail
    * Cocktails verwalten
    * Rezepte verwalten
    * Tags verwalten
    * Bewertungen verwalten

![http://wiki.cocktailberater.de/images/a/a7/Business_Logic.png](http://wiki.cocktailberater.de/images/a/a7/Business_Logic.png)

![http://wiki.cocktailberater.de/images/8/8b/Business_Logic_Details.png](http://wiki.cocktailberater.de/images/8/8b/Business_Logic_Details.png)

![http://wiki.cocktailberater.de/images/5/5a/ER-Diagramm.png](http://wiki.cocktailberater.de/images/5/5a/ER-Diagramm.png)