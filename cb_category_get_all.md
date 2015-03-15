**Funktion**

Gibt alle Cocktail Kategorien zurück. Eine Cocktail Kategorie entspricht einer Geschmacksrichtung.


**Entwicklungsstatus**

**Priorität 1**


**Authentifizierung**

Für diese Methode ist keine Authentifizierung erforderlich.


**Argumente**

  * category (optional)
    * eine Zeichenkette beliebiger Länge zum Suchen nach dem Kategoriennamen
  * language (optional)
    * eine dreistellige Zeichenkette, nach [IST 639-3](http://www.sil.org/iso639-3/codes.asp)
    * Standardwert: 'deu' für deutsch
  * api-key (optional)
    * ihr [[API-Schlüssel]]


**Beispielanfrage**

http://api.cocktailberater.de/services/rest/cb-category-get-all/category/s


**Beispielantwort**

```
<rsp stat="ok">
  <categories count="1">
    <category idcategory="1" name="süß" />
  </categories>
</rsp>
```


**Fehlercodes**

  * ...: Nicht alle zwingend erforderlichen Parameter wurden angegeben
    * Es müssen die Parameter ..., ... und ... angegeben werden