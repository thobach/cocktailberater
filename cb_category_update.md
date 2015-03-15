## cb-category-update ##
  * RIO 2

**Authentifizierung**

Für diese Methode ist eine Authentifizierung mit Schreibrechten erforderlich.


**Argumente**

  * category (erforderlich)
    * eine Zeichenkette, die den neuen Namen der Kategorie enthält
  * idcategory (erforderlich)
    * eine Ganzzahl, welche die ID der Kategorie repräsentiert
  * language (optional)
    * eine dreistellige Zeichenkette, nach [IST 639-3](http://www.sil.org/iso639-3/codes.asp)
    * Standardwert: 'deu' für deutsch
  * api-key (erforderlich)
    * ihr [API-Schlüssel]


**Beispielanfrage**

http://api.cocktailberater.de/cb-category-update/category/scharf/idcategory/11/api-key/ihr-api-schlüssel


**Beispielantwort**

```
<rsp stat="ok" />
```

**Fehlercodes**

  * 1: Nicht alle zwingend erforderlichen Parameter wurden angegeben
    * Es müssen die Parameter ..., ... und ... angegeben werden