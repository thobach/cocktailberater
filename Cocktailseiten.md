# Top 10 Seiten Ermittler #
  * basierend auf Alexa-DE-Rang mittels AWIS (Alexa Web Information Service) http://aws.amazon.com/awis/

# Implementierung #

## Einbindung in cocktailberater.de ##
  * Problem: private key darf nicht eingecheckt werden
  * Lösung: Build System übernimmt die Aufgabe

## Eingabe ##
  * public Key
  * private Key
  * Liste von Webseiten

## Ausgabe ##
  * Tabelle mit Webseite + Screenshot, Alexa-Rang, Alexa-DE-Rang, Details Link

## Details ##
  * alle Details die man vom AWIS bekommt

## Beispielantworten ##
cocktailberater.de

```
<?xml version="1.0"?>
<aws:UrlInfoResponse xmlns:aws="http://alexa.amazonaws.com/doc/2005-10-05/">
	<aws:Response xmlns:aws="http://awis.amazonaws.com/doc/2005-07-11">
		<aws:OperationRequest>
			<aws:RequestId>edea8dde-0e15-4dc1-2af5-3be38d1825fc</aws:RequestId>
		</aws:OperationRequest>
		<aws:UrlInfoResult>
			<aws:Alexa>
			  <aws:ContactInfo>
				<aws:DataUrl type="canonical">cocktailberater.de</aws:DataUrl>
				<aws:PhoneNumbers>
				  <aws:PhoneNumber/>
				</aws:PhoneNumbers>
				<aws:OwnerName>Thomas Bachmann</aws:OwnerName>
				<aws:Email/>
				<aws:PhysicalAddress>
				  <aws:Streets>
					<aws:Street>Wehlener Str. 27</aws:Street>
				  </aws:Streets>
				  <aws:City>Dresden</aws:City>
				  <aws:PostalCode>01328</aws:PostalCode>
				</aws:PhysicalAddress>
				<aws:CompanyStockTicker/>
			  </aws:ContactInfo>
			  <aws:ContentData>
				<aws:DataUrl type="canonical">cocktailberater.de</aws:DataUrl>
				<aws:SiteData>
				  <aws:Title>Cocktail Rezepte vom cocktailberater</aws:Title>
				  <aws:Description>Datenbank mit Rezepten für alkoholfreie und alkoholische Cocktails</aws:Description>
				</aws:SiteData>
				<aws:Speed>
				  <aws:MedianLoadTime/>
				  <aws:Percentile/>
				</aws:Speed>
				<aws:AdultContent/>
				<aws:Language/>
				<aws:LinksInCount>7</aws:LinksInCount>
				<aws:Keywords/>
				<aws:OwnedDomains/>
			  </aws:ContentData>
			  <aws:Related>
				<aws:DataUrl type="canonical">cocktailberater.de</aws:DataUrl>
				<aws:RelatedLinks/>
				<aws:Categories>
				  <aws:CategoryData>
					<aws:Title>Getränke/Cocktails</aws:Title>
					<aws:AbsolutePath>Top/World/Deutsch/Freizeit/Essen_und_Trinken/Getränke/Cocktails</aws:AbsolutePath>
				  </aws:CategoryData>
				</aws:Categories>
			  </aws:Related>
			  <aws:TrafficData>
				<aws:DataUrl type="canonical">cocktailberater.de</aws:DataUrl>
				<aws:Rank>1741801</aws:Rank>
				<aws:RankByCountry/>
				<aws:RankByCity/>
				<aws:UsageStatistics>
				  <aws:UsageStatistic>
					<aws:TimeRange>
					  <aws:Months>3</aws:Months>
					</aws:TimeRange>
					<aws:Rank>
					  <aws:Value>1741801</aws:Value>
					  <aws:Delta>-1139047</aws:Delta>
					</aws:Rank>
					<aws:Reach>
					  <aws:Rank>
						<aws:Value>2139805</aws:Value>
						<aws:Delta>-1702280</aws:Delta>
					  </aws:Rank>
					  <aws:PerMillion>
						<aws:Value>0.55</aws:Value>
						<aws:Delta>+120%</aws:Delta>
					  </aws:PerMillion>
					</aws:Reach>
					<aws:PageViews>
					  <aws:PerMillion>
						<aws:Value>0.03</aws:Value>
						<aws:Delta>+60%</aws:Delta>
					  </aws:PerMillion>
					  <aws:Rank>
						<aws:Value>1229420</aws:Value>
						<aws:Delta>-392080</aws:Delta>
					  </aws:Rank>
					  <aws:PerUser>
						<aws:Value>6</aws:Value>
						<aws:Delta>-30%</aws:Delta>
					  </aws:PerUser>
					</aws:PageViews>
				  </aws:UsageStatistic>
				  <aws:UsageStatistic>
					<aws:TimeRange>
					  <aws:Months>1</aws:Months>
					</aws:TimeRange>
					<aws:Rank>
					  <aws:Value>5638962</aws:Value>
					  <aws:Delta>+4661841</aws:Delta>
					</aws:Rank>
					<aws:Reach>
					  <aws:Rank>
						<aws:Value>5935673</aws:Value>
						<aws:Delta>+4658572</aws:Delta>
					  </aws:Rank>
					  <aws:PerMillion>
						<aws:Value>0.16</aws:Value>
						<aws:Delta>-80%</aws:Delta>
					  </aws:PerMillion>
					</aws:Reach>
					<aws:PageViews>
					  <aws:PerMillion>
						<aws:Value>NaN</aws:Value>
						<aws:Delta>-96%</aws:Delta>
					  </aws:PerMillion>
					  <aws:Rank>
						<aws:Value>5375592</aws:Value>
						<aws:Delta>4773085</aws:Delta>
					  </aws:Rank>
					  <aws:PerUser>
						<aws:Value>2</aws:Value>
						<aws:Delta>-70%</aws:Delta>
					  </aws:PerUser>
					</aws:PageViews>
				  </aws:UsageStatistic>
				</aws:UsageStatistics>
				<aws:ContributingSubdomains/>
			  </aws:TrafficData>
			</aws:Alexa>
		</aws:UrlInfoResult>
		<aws:ResponseStatus xmlns:aws="http://alexa.amazonaws.com/doc/2005-10-05/">
			<aws:StatusCode>Success</aws:StatusCode>
		</aws:ResponseStatus>
	</aws:Response>
</aws:UrlInfoResponse>
```

cocktailscout.de
```
<?xml version="1.0"?>
<aws:UrlInfoResponse xmlns:aws="http://alexa.amazonaws.com/doc/2005-10-05/"><aws:Response xmlns:aws="http://awis.amazonaws.com/doc/2005-07-11"><aws:OperationRequest><aws:RequestId>16a1b270-d38a-b6ee-81d9-4f90011e929e</aws:RequestId></aws:OperationRequest><aws:UrlInfoResult><aws:Alexa>
  
  <aws:ContactInfo>
    <aws:DataUrl type="canonical">cocktailscout.de</aws:DataUrl>
    <aws:PhoneNumbers>
      <aws:PhoneNumber/>
    </aws:PhoneNumbers>
    <aws:OwnerName>Pawel Cofta</aws:OwnerName>
    <aws:Email/>
    <aws:PhysicalAddress>
      <aws:Streets>
        <aws:Street>Blankenbergstr. 2</aws:Street>
      </aws:Streets>
      <aws:City>D-12161 Berlin</aws:City>
      <aws:Country>GERMANY</aws:Country>
    </aws:PhysicalAddress>
    <aws:CompanyStockTicker/>
  </aws:ContactInfo>
  <aws:ContentData>
    <aws:DataUrl type="canonical">cocktailscout.de</aws:DataUrl>
    <aws:SiteData>
      <aws:Title>Cocktailscout.de</aws:Title>
      <aws:Description>Zutatensuchmaschine und viele Rezepte etc.</aws:Description>
    </aws:SiteData>
    <aws:Speed>
      <aws:MedianLoadTime/>
      <aws:Percentile/>
    </aws:Speed>
    <aws:AdultContent>no</aws:AdultContent>
    <aws:Language>
      <aws:Locale>de</aws:Locale>
      <aws:Encoding>iso-8859-1</aws:Encoding>
    </aws:Language>
    <aws:LinksInCount>87</aws:LinksInCount>
    <aws:Keywords>
      <aws:Keyword>Deutsch</aws:Keyword>
      <aws:Keyword>Getränke</aws:Keyword>
      <aws:Keyword>Cocktails</aws:Keyword>
    </aws:Keywords>
    <aws:OwnedDomains/>
  </aws:ContentData>
  <aws:Related>
    <aws:DataUrl type="canonical">cocktailscout.de</aws:DataUrl>
    <aws:RelatedLinks>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">www.cocktail-lounge.net/</aws:DataUrl>
        <aws:NavigableUrl>http://www.cocktail-lounge.net/</aws:NavigableUrl>
        <aws:Title>Cocktail-Lounge</aws:Title>
      </aws:RelatedLink>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">www.1000getraenke.de/</aws:DataUrl>
        <aws:NavigableUrl>http://www.1000getraenke.de/</aws:NavigableUrl>
        <aws:Title>1000 Getränke</aws:Title>
      </aws:RelatedLink>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">www.cocktailtipp.de/</aws:DataUrl>
        <aws:NavigableUrl>http://www.cocktailtipp.de/</aws:NavigableUrl>
        <aws:Title>Cocktailtipp</aws:Title>
      </aws:RelatedLink>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">cocktail.buschtrommel.net/</aws:DataUrl>
        <aws:NavigableUrl>http://cocktail.buschtrommel.net/</aws:NavigableUrl>
        <aws:Title>PDA Cocktail Handbuch</aws:Title>
      </aws:RelatedLink>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">onlinebar.de/</aws:DataUrl>
        <aws:NavigableUrl>http://onlinebar.de/</aws:NavigableUrl>
        <aws:Title>OnlineBar.de</aws:Title>
      </aws:RelatedLink>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">de.bartrend.com/</aws:DataUrl>
        <aws:NavigableUrl>http://de.bartrend.com/</aws:NavigableUrl>
        <aws:Title>BARtrend</aws:Title>
      </aws:RelatedLink>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">cocktails.twotoasts.de/</aws:DataUrl>
        <aws:NavigableUrl>http://cocktails.twotoasts.de/</aws:NavigableUrl>
        <aws:Title>twotoasts.de</aws:Title>
      </aws:RelatedLink>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">bar.scotty.de/</aws:DataUrl>
        <aws:NavigableUrl>http://bar.scotty.de/</aws:NavigableUrl>
        <aws:Title>Scottys Internet Bar</aws:Title>
      </aws:RelatedLink>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">www.der-cocktail-mixer.de/</aws:DataUrl>
        <aws:NavigableUrl>http://www.der-cocktail-mixer.de/</aws:NavigableUrl>
        <aws:Title>Der-Cocktail-Mixer</aws:Title>
      </aws:RelatedLink>
      <aws:RelatedLink>
        <aws:DataUrl type="canonical">www.shakeman.de/</aws:DataUrl>
        <aws:NavigableUrl>http://www.shakeman.de/</aws:NavigableUrl>
        <aws:Title>Cocktailtreff</aws:Title>
      </aws:RelatedLink>
    </aws:RelatedLinks>
    <aws:Categories>
      <aws:CategoryData>
        <aws:Title>Getränke/Cocktails</aws:Title>
        <aws:AbsolutePath>Top/World/Deutsch/Freizeit/Essen_und_Trinken/Getränke/Cocktails</aws:AbsolutePath>
      </aws:CategoryData>
    </aws:Categories>
  </aws:Related>
  <aws:TrafficData>
    <aws:DataUrl type="canonical">cocktailscout.de</aws:DataUrl>
    <aws:Rank>534362</aws:Rank>
    <aws:RankByCountry>
      <aws:Country Code="DE">
        <aws:Rank>134673</aws:Rank>
        <aws:Contribution>
          <aws:PageViews>30.4%</aws:PageViews>
          <aws:Users>74.0%</aws:Users>
        </aws:Contribution>
      </aws:Country>
      <aws:Country Code="O">
        <aws:Rank/>
        <aws:Contribution>
          <aws:PageViews>69.5%</aws:PageViews>
          <aws:Users>25.7%</aws:Users>
        </aws:Contribution>
      </aws:Country>
    </aws:RankByCountry>
    <aws:RankByCity>
      <aws:City Code="C136" Name="Berlin, DE">
        <aws:Rank>26433</aws:Rank>
        <aws:Contribution>
          <aws:PageViews>32.6%</aws:PageViews>
          <aws:Users>20.7%</aws:Users>
          <aws:PerUser>
            <aws:AveragePageViews>5</aws:AveragePageViews>
          </aws:PerUser>
        </aws:Contribution>
      </aws:City>
      <aws:City Code="O" Name="OTHER">
        <aws:Rank/>
        <aws:Contribution>
          <aws:PageViews>67.6%</aws:PageViews>
          <aws:Users>79.6%</aws:Users>
          <aws:PerUser>
            <aws:AveragePageViews>2.5</aws:AveragePageViews>
          </aws:PerUser>
        </aws:Contribution>
      </aws:City>
    </aws:RankByCity>
    <aws:UsageStatistics>
      <aws:UsageStatistic>
        <aws:TimeRange>
          <aws:Months>3</aws:Months>
        </aws:TimeRange>
        <aws:Rank>
          <aws:Value>534362</aws:Value>
          <aws:Delta>+20133</aws:Delta>
        </aws:Rank>
        <aws:Reach>
          <aws:Rank>
            <aws:Value>550629</aws:Value>
            <aws:Delta>+18026</aws:Delta>
          </aws:Rank>
          <aws:PerMillion>
            <aws:Value>2.7</aws:Value>
            <aws:Delta>+1%</aws:Delta>
          </aws:PerMillion>
        </aws:Reach>
        <aws:PageViews>
          <aws:PerMillion>
            <aws:Value>0.09</aws:Value>
            <aws:Delta>-0.3%</aws:Delta>
          </aws:PerMillion>
          <aws:Rank>
            <aws:Value>597601</aws:Value>
            <aws:Delta>26807</aws:Delta>
          </aws:Rank>
          <aws:PerUser>
            <aws:Value>3.2</aws:Value>
            <aws:Delta>-1%</aws:Delta>
          </aws:PerUser>
        </aws:PageViews>
      </aws:UsageStatistic>
      <aws:UsageStatistic>
        <aws:TimeRange>
          <aws:Months>1</aws:Months>
        </aws:TimeRange>
        <aws:Rank>
          <aws:Value>975255</aws:Value>
          <aws:Delta>+433412</aws:Delta>
        </aws:Rank>
        <aws:Reach>
          <aws:Rank>
            <aws:Value>923940</aws:Value>
            <aws:Delta>+348540</aws:Delta>
          </aws:Rank>
          <aws:PerMillion>
            <aws:Value>1.6</aws:Value>
            <aws:Delta>-40%</aws:Delta>
          </aws:PerMillion>
        </aws:Reach>
        <aws:PageViews>
          <aws:PerMillion>
            <aws:Value>0.03</aws:Value>
            <aws:Delta>-64%</aws:Delta>
          </aws:PerMillion>
          <aws:Rank>
            <aws:Value>1252777</aws:Value>
            <aws:Delta>677556</aws:Delta>
          </aws:Rank>
          <aws:PerUser>
            <aws:Value>2.1</aws:Value>
            <aws:Delta>-40%</aws:Delta>
          </aws:PerUser>
        </aws:PageViews>
      </aws:UsageStatistic>
      <aws:UsageStatistic>
        <aws:TimeRange>
          <aws:Days>7</aws:Days>
        </aws:TimeRange>
        <aws:Rank>
          <aws:Value>826397</aws:Value>
          <aws:Delta>-321127</aws:Delta>
        </aws:Rank>
        <aws:Reach>
          <aws:Rank>
            <aws:Value>1010935</aws:Value>
            <aws:Delta>+76027</aws:Delta>
          </aws:Rank>
          <aws:PerMillion>
            <aws:Value>1.6</aws:Value>
            <aws:Delta>-7%</aws:Delta>
          </aws:PerMillion>
        </aws:Reach>
        <aws:PageViews>
          <aws:PerMillion>
            <aws:Value>0.08</aws:Value>
            <aws:Delta>+300%</aws:Delta>
          </aws:PerMillion>
          <aws:Rank>
            <aws:Value>674101</aws:Value>
            <aws:Delta>-1091557</aws:Delta>
          </aws:Rank>
          <aws:PerUser>
            <aws:Value>5</aws:Value>
            <aws:Delta>+300%</aws:Delta>
          </aws:PerUser>
        </aws:PageViews>
      </aws:UsageStatistic>
    </aws:UsageStatistics>
    <aws:ContributingSubdomains>
      <aws:ContributingSubdomain>
        <aws:DataUrl>cocktailscout.de</aws:DataUrl>
        <aws:TimeRange>
          <aws:Months>1</aws:Months>
        </aws:TimeRange>
        <aws:Reach>
          <aws:Percentage>100.0%</aws:Percentage>
        </aws:Reach>
        <aws:PageViews>
          <aws:Percentage>100.0%</aws:Percentage>
          <aws:PerUser>1.5</aws:PerUser>
        </aws:PageViews>
      </aws:ContributingSubdomain>
    </aws:ContributingSubdomains>
  </aws:TrafficData>
</aws:Alexa></aws:UrlInfoResult><aws:ResponseStatus xmlns:aws="http://alexa.amazonaws.com/doc/2005-10-05/"><aws:StatusCode>Success</aws:StatusCode></aws:ResponseStatus></aws:Response></aws:UrlInfoResponse>
```