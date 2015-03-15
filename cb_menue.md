## cb-menue-get ##
#### User-Story #6 ####
  * User-Story: Als Barkeeper kann ich meine Cocktailkarte ausdrucken, um sie den Gästen vorzulegen.

  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-get-menue/idcocktailparty/21
  * Beispielantwort 1 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <cocktailparty idcocktailparty="21" location="Stuttgart" name="Tabas Hawaiifete" date="2008-05-28">
      <owners count="1">
        <member idmember="42" name="Thomas Bachmann" email="thobach@web.de" hash="89f770d0f6401b724b88a7437ded38bb" />
      </owners>
      <orders count="0" />
      <menue count="1">
        <cocktail ... price="2.90">
        </cocktail>
      </menue>
    </cocktailparty>
  </rsp>
```
  * Beispielantwort 2 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <menue count="1">
      <cocktail ... price="2.90">
      </cocktail>
    </menue>
  </rsp>
```

## cb-menue-cocktail-add ##
#### User-Story #3 ####
  * User-Story: Als Barkeeper kann ich meine Cocktails auswählen (Cocktailkarte selbst zusammenstellen), um im Bestellprogramm nur noch diese anzuzeigen.

  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-add-cocktail/member_hash/89f770d0f6401b724b88a7437ded38bb/idcocktailparty/12/idcocktail/21
  * Beispielantwort 1 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <cocktailparty idcocktailparty="21" location="Stuttgart" name="Tabas Hawaiifete" date="2008-05-28">
      <owners count="1">
        <member idmember="42" name="Thomas Bachmann" email="thobach@web.de" hash="89f770d0f6401b724b88a7437ded38bb" />
      </owners>
      <orders count="0" />
      <menue count="1">
        <cocktail idcocktail="2" ...>
          ...
        </cocktail>
      </menue>
    </cocktailparty>
  </rsp>
```
  * Beispielantwort 2 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```

## cb-menue-cocktail-remove ##
#### User-Story #4 ####
  * User-Story: Als Barkeeper kann ich während meiner Cocktailparty Zutaten auswählen, die mir bald ausgehen werden, um zu verhindern, dass ich nicht ausliefern kann. Cocktails mit diesen Zutaten sollen dann von der Bestelliste genommen werden. Offene Bestellungen sind nicht zu korrigieren.

  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-remove-cocktail/member_hash/89f770d0f6401b724b88a7437ded38bb/idcocktailparty/12/idcocktail/21
  * Beispielantwort 1 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <cocktailparty idcocktailparty="21" location="Stuttgart" name="Tabas Hawaiifete" date="2008-05-28">
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
  <rsp stat="ok" />
```

## cb-menue-cocktail-price-set ##
#### User-Story #5 ####
  * User-Story: Als Barkeeper kann ich Preise für alle angebotenen Cocktails festlegen, um nicht nur die Herstellkosten anzusetzen.

  * Anfrage: http://www.cocktailberater.de/api/cocktail-party-set-cocktail-price/member_hash/89f770d0f6401b724b88a7437ded38bb/idcocktailparty/12/idcocktail/21/price/2.90
  * Beispielantwort 1 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok">
    <cocktailparty idcocktailparty="21" location="Stuttgart" name="Tabas Hawaiifete" date="2008-05-28">
      <owners count="1">
        <member idmember="42" name="Thomas Bachmann" email="thobach@web.de" hash="89f770d0f6401b724b88a7437ded38bb" />
      </owners>
      <orders count="0" />
      <menue count="1">
        <cocktail ... price="2.90">
        </cocktail>
      </menue>
    </cocktailparty>
  </rsp>
```

  * Beispielantwort 2 bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```