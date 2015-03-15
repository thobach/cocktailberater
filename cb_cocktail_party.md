## cb-cocktail-party-add ##
#### User-Story #1 ####
  * User-Story: Als Barkeeper kann ich meine Party mit Ort, Name und Datum anlegen, damit der Gast später beim Öffnen des Bestellprogramms die richtige Party auswählen kann.

  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-add/member_hash/89f770d0f6401b724b88a7437ded38bb/location/Stuttgart/name/Tabas%20Hawaiifete/date/2008-05-28
  * @Tobse: Ist es ok, dass hier die Cocktailparty zurückgegeben wird? Ich denke es ist sinnvoll irgendwie die generierte idcocktailparty zurückzugeben für die weitere Verwendung.
  * Beispielantwort 1 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <cocktailparty idcocktailparty="12" location="Stuttgart" name="Tabas Hawaiifete" date="2008-05-28">
      <owners count="1">
        <member idmember="42" name="Thomas Bachmann" email="thobach@web.de" hash="89f770d0f6401b724b88a7437ded38bb" />
      </owners>
      <orders count="0" />
      <menue count="0" />
    </cocktailparty>
  </rsp>
```
  * Beispielantwort 2 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <cocktailparty idcocktailparty="12" />
  </rsp>
```
## cb-cocktail-party-update ##
## cb-cocktail-party-get-all ##
#### User-Story #14 ####
  * User-Story: Als Gast kann ich die Stadt und Cocktailbar in der ich bin aussuchen, um dort später Cocktails zu bestellen.
  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-list-all
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <cocktailpartys count="2">
      <cocktailparty idcocktailparty="12" location="Stuttgart" name="Tabas Hawaiifete" date="2008-05-28" />
      <cocktailparty idcocktailparty="13" location="Dresden" name="Tabas Silvesterfete" date="2008-12-31" />
    </cocktailparty>
  </rsp>
```