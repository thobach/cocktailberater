## Daten-Hierarchie ##
Allgemeint kann die Datenstruktur oder -hierarchie wie folgt beschrieben werden:
  * Es gibt Cocktails. -> Sex on the Beach
  * Ein Cocktail kann mehrere Rezepte haben. -> Sex on the Beach Basic, Sex on the Beach alkoholfrei, Tabas Sex on the Beach Spezial
    * Es gibt Rezepte. -> Sex on the Beach Basic
    * Ein Rezept besteht aus mindestens einer Rezeptur. -> 4cl Wodka, 3cl Pfirsichlikör, 6cl Ananassaft, 6cl Cranberrysaft
      * Es gibt Rezepturen. -> 4cl Wodka
      * Eine Rezeptur beschreibt wie viel von jeder Zutat benötigt wird. -> ???
        * Es gibt Zutaten. -> Wodka
        * Eine Zutat ist einer Zutatenkategorie zugeordnet. -> Alkohol (>30%)
        * Jede Zutat hat mehrere Produkte. -> Puschkin Vodka, Gorbatschov Vodka, Jelzin Vodka, ...
          * Es gibt Produkte. -> Puschkin Vodka
          * Ein Produkt kann bei mehreren Händlern erworben werden. -> Penny Mark Stuttgart-Ost, Lidl Stuttgart-West
            * Es gibt Händler. -> Lidl Stuttgart-West
            * Ein Händler kann einer Handelskette zugeordnet werden. -> Lidl

## Datenpflege ##
  * Rezeptanweisungen alle umschreiben
  * Alternativrezepte anhand der Kommentare in cocktailscout.de erstellen
  * Beschreibungen der Cocktails ausführlicher, evtl. aus Wikipedia kopieren (mit Angabe der Quelle)
  * Link zum Rezept auf Wiki (Kochbuch / Cocktails) einpflegen
  * mehr Bilder einpflegen / selbst fotografieren