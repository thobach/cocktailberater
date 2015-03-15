## cb-category-add ##
  * PRIO 2

**Authentifizierung**

Für diese Methode ist eine Authentifizierung mit Schreibrechten erforderlich.


**Argumente**

  * category (erforderlich)
    * eine Zeichenkette, die den Namen der Kategorie enthält
  * language (optional)
    * eine dreistellige Zeichenkette, nach [IST 639-3](http://www.sil.org/iso639-3/codes.asp)
    * Standardwert: 'deu' für deutsch
  * api-key (erforderlich)
    * ihr [API-Schlüssel](API_Key.md)


**Beispielanfrage**

http://api.cocktailberater.de/cb-category-add/category/scharf/api-key/ihr-api-schlüssel


**Beispielantwort**

```
  <rsp stat="ok" />
```


**Fehlercodes**

  * 1: Nicht alle zwingend erforderlichen Parameter wurden angegeben
    * Es müssen die Parameter ..., ... und ... angegeben werden
  * 2: Die Kategorie existiert schon
    * Der Name der Kategorie wurde schon in der Datenbank gefunden. Bitte schauen sie unter ... nach, welche Kategorien es schon gibt.