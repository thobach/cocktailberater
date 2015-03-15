**Funktion**

Liefert Informationen zu einer Bestellung.


**Entwicklungsstatus**

  * Priorität 1


**Authentifizierung**

Für diese Methode ist (erstmal!!) keine Authentifizierung erforderlich.


**Argumente**

  * idorder (erforderlich)
    * eine Ganzzahl, welche die ID der Bestellung repräsentiert
  * language (optional)
    * eine dreistellige Zeichenkette, nach [IST 639-3](http://www.sil.org/iso639-3/codes.asp)
    * Standardwert: 'deu' für deutsch
  * api-key (noch!!! optional)
    * ihr [[API-Schlüssel]]


**Beispielanfrage**

http://api.cocktailberater.de/order/get/id/1


**Beispielantwort**

```
<rsp stat="ok">
  todo
</rsp>
```


**Fehlercodes**

  * 1: Nicht alle zwingend erforderlichen Parameter wurden angegeben
    * Es müssen die Parameter ..., ... und ... angegeben werden