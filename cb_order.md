## cb-order-add ##
#### User-Story #16 ####
  * User-Story: Als Gast kann ich einen Cocktail aus einer Cocktailbar bestellen (optional mit Kommentar), nachdem ich mir einen aus dem Katalog ausgesucht habe.
  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-order-add/member_hash/89f770d0f6401b724b88a7437ded38bb/idcocktailparty/12/idcocktail/21/comment/SAHNE!!
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```
## cb-order-update ##
#### User-Story #11 ####
  * User-Story: Als Barkeeper kann ich Aufträge ändern, um individuellen Kundenwünschen gerecht zu werden.
  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-order-edit/idorder/84/idcocktail/12 oder /idrezept/83 oder /price/3.00 oder /comment/will%20ihn%extra%stark
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```
## cb-order-get ##
## cb-order-get-all ##
#### User-Story #7 ####
  * User-Story: Als Barkeeper kann ich alle Bestellung mit Rezept und Besteller, ggf. zusammengefasst in Zweierpacks, aufgelistet bekommen, um die Cocktails in der Küche zu mixen.
  * Hinweis: Die Zusammenfassung in Zweierpacks muss clientseitig passieren.
  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-get-orders/idcocktailparty/21
  * Beispielantwort 1 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <cocktailparty idcocktailparty="21" location="Stuttgart" name="Tabas Hawaiifete" date="2008-05-28">
      <owners count="1">
        <member idmember="42" name="Thomas Bachmann" email="thobach@web.de" hash="89f770d0f6401b724b88a7437ded38bb" />
      </owners>
      <orders count="2">
        <order idorder="83" date="2007-05-12" time="21:57" comment="bitte mit viel mehr Rum ;)" position="1">
          <recipe ... >
            ...
          </recipe>
          <member idmember="42" name="Thomas Bachmann" email="thobach@web.de" hash="89f770d0f6401b724b88a7437ded38bb" />
        </order>
        <order idorder="84" date="2007-05-12" time="21:59" comment="Danke!" position="3">
          <recipe ... >
            ...
          </recipe>
          <member idmember="48" name="Tobias Ernstberger" email="tobse@slashte.de" hash="3ac29aa4b209fed14c1f8d0362d38b69" />
        </order>
      </orders>
    </cocktailparty>
  </rsp>
```
  * Beispielantwort 2 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <orders count="2">
      <order idorder="83" date="2007-05-12" time="21:57" comment="bitte mit viel mehr Rum ;)" position="1">
        <recipe ... >
          ...
        </recipe>
        <member idmember="42" name="Thomas Bachmann" email="thobach@web.de" hash="89f770d0f6401b724b88a7437ded38bb" />
      </order>
      <order idorder="84" date="2007-05-12" time="21:59" comment="Danke!" position="3">
        <recipe ... >
          ...
        </recipe>
        <member idmember="48" name="Tobias Ernstberger" email="tobse@slashte.de" hash="3ac29aa4b209fed14c1f8d0362d38b69" />
      </order>
    </orders>
  </rsp>
```
#### User-Story #9 ####
  * User-Story: Als Barkeeper kann ich alle Bestellungen eines Kunden einsehen und ggf. abrechnen.
  * Anfrage 1: http://www.cocktailberater.de/api/cocktail-party-orders-get/idmember/48
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <orders idcocktailparty="21" count="1" total="2.90">
      <order idorder="84" date="2007-05-12" time="21:59" comment="Danke!">
        <recipe ... price="2.90">
          ...
        </recipe>
        <member idmember="48" name="Tobias Ernstberger" email="tobse@slashte.de" hash="3ac29aa4b209fed14c1f8d0362d38b69" />
      </order>
    </orders>
  </rsp>
```
  * Beispielantwort im Fehlerfall:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="fail">
    <err code="1010" msg="..." />
  </rsp>
```
  * Anfrage 2: http://www.cocktailberater.de/api/cocktail-party-guest-checkout/idmember/48/member_hash/89f770d0f6401b724b88a7437ded38bb/idcocktailparty/12
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```
## cb-order-move ##
#### User-Story #10 ####
  * User-Story: Als Barkeeper kann ich Aufträge umsortieren, um sie den Anforderungen der Küche und den Kunden anzupassen
  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-order-move/idorder/84/up/1 oder /down/2
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```
## cb-order-complete ##
#### User-Story #12 ####
  * User-Story: Als Barkeeper kann ich Aufträge in den Status "fertig" setzen, damit der Kunde weiß, dass er seinen Cocktail abholen kann
  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-order-complete/idorder/84
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```
#### User-Story #8 ####
  * User-Story: Als Barkeeper kann ich Bestellungen als fertig markieren, um sie von der Liste der offenen Bestellungen zu entfernen und in Rechnung zu stellen.
  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-complete-orders/member_hash/89f770d0f6401b724b88a7437ded38bb/idcocktailparty/12/idorder/83
  * Beispielantwort 1 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <cocktailparty idcocktailparty="21" location="Stuttgart" name="Tabas Hawaiifete" date="2008-05-28">
      <owners count="1">
        <member idmember="42" name="Thomas Bachmann" email="thobach@web.de" hash="89f770d0f6401b724b88a7437ded38bb" />
      </owners>
      <orders count="1">
        <order idorder="83" date="2007-05-12" time="21:57" comment="bitte mit viel mehr Rum ;)" position="1">
          <recipe ... >
            ...
          </recipe>
          <member idmember="42" name="Thomas Bachmann" email="thobach@web.de" hash="89f770d0f6401b724b88a7437ded38bb" />
        </order>
      </orders>
    </cocktailparty>
  </rsp>
```
  * Beispielantwort 2 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
  </rsp>
```
## cb-order-cancel ##
#### User-Story #13 ####
  * User-Story: Als Barkeeper kann ich Aufträge in den Status "storniert" setzen, damit der Kunde weiß, dass er keinen Cocktail mehr bekommt und der Cocktail nicht abgerechnet wird
  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-order-cancel/idorder/84
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```
#### User-Story #19 ####
  * User-Story: Als Gast kann ich noch nicht bearbeitete Bestellungen stornieren, um einen Fehler korrigieren zu können. Zur Sicherheit, dass nicht schon mit der Bestellung angefangen wurde, muss dazu die Bestellung des Gasts in der Warteliste mindestens auf Position 6 sein.
  * Anfrage: http://www.cocktailberater.de/api/order-cancel/idorder/82/member_hash/89f770d0f6401b724b88a7437ded38bb
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```