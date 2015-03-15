# summary sub-project kitchen client

[![](http://create.ly/g6thx0ci2?nonsense=pic.png)](http://creately.com/app?diagID=g6thx0ci2)

DEMO in Flex http://thobach.dyndns.org/barclient/
SVN: svn://thobach.dyndns.org/barclient

## Funktionsbaum Küchenclient ##

Einloggen
> Cocktailbar auswählen



  * Reiter -> Bestellungen
    * ansehen (cocktailname, rezept (grundrezept), kunde)
    * bearbeitung starten ???????????????????? (tobse: hatten wir das nicht gestrichen?)
    * bearbeitung abschließen
    * bearbeitung abbrechen ???????????????????? (tobse: hatten wir das nicht gestrichen?)

  * Reiter -> Abrechnung
    * anzeigen
    * buchen (es wurde gezahlt)

## User-Storys ##

  1. Als Barkeeper kann ich meine Party mit Ort, Name und Datum anlegen, damit der Gast später beim Öffnen des Bestellprogramms die richtige Party auswählen kann.
  1. Als Barkeeper kann ich für meine Party meine vorhandenen Zutaten eingeben, um alle verfügbaren Cocktails ausgegeben zu bekommen, denn diese sollen für die Cocktailkarte übernommen werden.
  1. Als Barkeeper kann ich meine Cocktails auswählen (Cocktailkarte selbst zusammenstellen), um im Bestellprogramm nur noch diese anzuzeigen.
    1. http://api.cocktailberater.de/menu/add-recipe/menu/1/recipe/23
    1. http://api.cocktailberater.de/menu/remove-recipe/menu/1/recipe/23
  1. (optional) Als Barkeeper kann ich während meiner Cocktailparty Zutaten auswählen, die mir bald ausgehen werden, um zu verhindern, dass ich nicht ausliefern kann. Cocktails mit diesen Zutaten sollen dann von der Cocktailkarte genommen werden. Offene Bestellungen sind nicht zu korrigieren.
  1. Als Barkeeper kann ich Preise für alle angebotenen Cocktails festlegen, um nicht nur die Herstellkosten anzusetzen.
  1. Als Barkeeper kann ich meine Cocktailkarte ausdrucken, um sie den Gästen vorzulegen.
  1. Als Barkeeper kann ich alle Bestellungen mit Rezept und Besteller, ggf. zusammengefasst in Zweierpacks, aufgelistet bekommen, um die Cocktails in der Küche zu mixen.
  1. Als Barkeeper kann ich Bestellungen als fertig markieren, um sie von der Liste der offenen Bestellungen zu entfernen und in Rechnung zu stellen.
  1. Als Barkeeper kann ich alle Bestellungen eines Kunden einsehen und ggf. abrechnen.
  1. Als Barkeeper kann ich Aufträge umsortieren, um sie den Anforderungen der Küche und den Kunden anzupassen
  1. Als Barkeeper kann ich Aufträge ändern, um individuellen Kundenwünschen gerecht zu werden.
  1. Als Barkeeper kann ich Aufträge in den Status "fertig" setzen, damit der Kunde weiß, dass er seinen Cocktail abholen kann
  1. Als Barkeeper kann ich Aufträge in den Status "storniert" setzen, damit der Kunde weiß, dass er keinen Cocktail mehr bekommt und der Cocktail nicht abgerechnet wird

## Funktionen des Frameworks ##

(Todo für Martin)

public static UserDto getUserDto(Document xml)

Generiert aus einem Authentifierungs XML ein UserDto (Dto = DataObject). Bei einem Fehler wird null zurückgegeben




## Küchenclient ##
  * mehrere Barkeeper sollen gleichzeitig abarbeiten können
  * Status soll in der Liste der Bestellungen auftauchen
  * Vor- und Zuname soll in der Bestelliste auftauchen (evtl. mit Initialien -> Bachmann, T.)
  * Performance-Schub beim Klick auf Liste aktualisieren
  * größere Darstellung des Sonderwunsches und der Anzahl (evtl. auch rot)
  * Zeit anzeigen, wann Bestellung aufgegeben wurde
  * Bestellungen gleicher Art in 2er Gruppen zusammenfassen (ein Barkeeper kann 2 Rezepte gleichzeitig mixen)
  * direkt auf fertig klicken können