Die [API](API.md) nutzt den [Application Server](Application_Server.md) und baut auf dem REST Konzept auf. Es existiert allerdings nur eine HTTP Implementierung.

## Allgemeines zur API ##
Der Cocktailberater bietet am Application Server eine Schnittstelle, die von verschiedenen Clients genutzt wird.

Die Kommunikation zum Application Server wird immer vom Client angestoßen. Jede Anfrage muss zu einer Antwort führen, die entweder Nutzdaten, oder eine Erfogs-/Misserfolgsmeldung beinhaltet. Der Server kann keine Events aussenden. Die Clients müssen gegebenfalls im Polling-Modus arbeiten.

Der Application Server wird über http auf Port 80 angesprochen. Es steht die HTTP Methoden GET, POST, PUT und DELETE zur Verfügung. Das Antwortformat ist flexibel. Der Standard ist HTML, es ist in der Regel aber auch XML und JSON möglich. Teilweise ist auch RSS, HTML oder PDF implementiert.

  * API-Sprache: Englisch
    * alle API-Namen sind Englisch, die Inhalte aktuell nur auf Deutsch verfügbar
  * Fehlermeldungen sind standardmäßig auf Englisch und benutzen die HTTP Status-Codes

  * allgemein Anlehnung an die [Flickr API](http://www.flickr.com/services/api/)

  * sich wiederholende Phrasen
    * GET: Auflistung aller Cocktails / Kategorien / Usern / Tags
      * z.B.: http://www.cocktailberater.de/website/cocktail/?format=xml
    * GET: eine bestimmte Bestellung, Cocktail / etc. anhand der 'ID' zurückgeben
      * z.B.: http://www.cocktailberater.de/website/recipe/1?format=xml

**Aufbau der GET Anfragen:**

  * Wurzel: http://www.cocktailberater.de/website/

anschließend können zusätzliche Parameter angehängt werden:

## API-Explorer ##
Der API-Explorer dient dem komfortablen Testen der API.

  * todo...

## API-Methoden ##
### Namensräume ###
/cocktail?format=[xml|json]
/recipe?format=[xml|json]
/recipe/1?format=[xml|json]

/
### Fehlermeldungen ###
Fehlermeldungen haben immer folgendes Format:
```
<?xml version="1.0" encoding="utf-8" ?>
 <rsp stat="fail">
   <err code="1001" msg="The database is currently not available" />
</rsp>
```

Übersetzungen der Fehlercodes können wie folgt abgerufen werden:
  * Anfrage: http://www.cocktailberater.de/api/error-code-translate/code/1001/language/de
  * Beispielantwort bei Erfolg:
```
<?xml version="1.0" encoding="utf-8" ?>
 <rsp stat="ok">
   <err code="1001" msg="Die Datenbank ist zur Zeit nicht verfügbar." />
 </rsp>
```

Übersicht aller [Fehlercodes](Fehlercodes.md)