**Funktion**

Liefert Informationen zu einer Geschmackskategorie für Cocktails.


**Entwicklungsstatus**

  * Priorität 2


**Authentifizierung**

Für diese Methode ist keine Authentifizierung erforderlich.


**Argumente**

  * idcategory (erforderlich)
    * eine Ganzzahl, welche die ID der Kategorie repräsentiert
  * language (optional)
    * eine dreistellige Zeichenkette, nach [IST 639-3](http://www.sil.org/iso639-3/codes.asp)
    * Standardwert: 'deu' für deutsch
  * api-key (optional)
    * ihr [[API-Schlüssel]]


**Beispielanfrage**

http://api.cocktailberater.de/services/rest/cb-category-get/idcategory/1


**Beispielantwort**

```
<rsp stat="ok">
  <category idcategory="1" name="süß" />
</rsp>
```

**Fehlercodes**

  * 1: Nicht alle zwingend erforderlichen Parameter wurden angegeben
    * Es müssen die Parameter ..., ... und ... angegeben werden