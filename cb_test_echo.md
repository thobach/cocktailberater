**Funktion**

Bietet eine Möglichkeit die [API](API.md) via REST zu testen


**Entwicklungsstatus**

**Priorität 1**


**Authentifizierung**

Für diese Methode ist keine Authentifizierung erforderlich.


**Argumente**

  * language (optional)
    * ine dreistellige Zeichenkette, nach [IST 639-3](http://www.sil.org/iso639-3/codes.asp)
    * tandardwert: 'deu' für deutsch
  * api-key (optional)
    * hr [API-Schlüssel](API_Key.md)


**Beispielanfrage**

http://api.cocktailberater.de/services/rest/cb-test-echo


**Beispielantwort**

```
<rsp stat="ok">
  <echo language="deu" api-key="">Der Zugriff auf die API funktioniert.</echo>
</rsp>
```


**Fehlercodes**

  * ...: Ungültiger API-Schlüssel
    * Der angegebene API-Schlüssel war nicht korrekt. Entweder ist er nicht registriert oder wurde gesperrt.
  * ...: Ungültige Sprache
    * Der angegebene Sprache war nicht korrekt. Sie muss nach [IST 639-3](http://www.sil.org/iso639-3/codes.asp) spezifiziert sein.