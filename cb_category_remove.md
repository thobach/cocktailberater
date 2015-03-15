## cb-category-remove ##
**PRIO 2**

**Authentifizierung**

Für diese Methode ist eine Authentifizierung mit Löschrechten erforderlich.


**Argumente**

  * idcategory (erforderlich)
    * eine Ganzzahl, welche die ID der Kategorie repräsentiert
  * api-key (erforderlich)
    * ihr [API-Schlüssel](API_Key.md)


**Beispielanfrage**

http://api.cocktailberater.de/cb-category-remove/idcategory/1


**Beispielantwort**

```
  <rsp stat="ok" />
```


**Fehlercodes**

  * 1: Nicht alle zwingend erforderlichen Parameter wurden angegeben
    * Es müssen die Parameter ..., ... und ... angegeben werden