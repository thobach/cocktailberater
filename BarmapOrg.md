![http://cocktailberater.googlecode.com/files/barmap_org.png](http://cocktailberater.googlecode.com/files/barmap_org.png)

# Introduction #

barmap.org shows different kinds of bars/pubs/nightclubs on a openstreetmap.org map. The user can get detailed information on the bar or add information.

User Story #1: I am in the city with a couple of friends and we don't know where to go. We want suggestions on close bars, close bars with happy hour right now, close bars where we have not been yet.

User Story #2: I am planning a birthday party in a bar and look for close bars with an happy hour at a specific day and or time.

User Story #3: I am at a bar and I want a friend to find me. I want to send him a SMS or E-Mail with all relevant information to find me.

User Story #4: I am at a bar and want to remind me and others that this bar is good/bad and how expensive drinks are etc.

# Details #

  * clients
    * iPhone App
    * Android App
    * mobile website
    * desktop website
    * all versions allow to view and edit the data
  * community
    * store favorite bars
    * show edits of user
    * the information will be stored in OSM and if necessary in local key-value store
    * integration with twitter, facebook places, foursquare
  * Displayed information: (sth. like this http://maps.google.de/places/de/stuttgart/hirschstraße/36/-kaufhaus-schocken?hl=de&gl=de)
    * category (bar, pub, nightclub)
    * name of the bar
    * description
    * opening hours
    * happy hours
    * phone number
    * website
    * address
    * public transport
    * photos (browse through)
    * food? (y/n)
    * smoking? (y/n)
    * size (# tables, # m2)
    * prices (mojito, mai tai, cuba libre, rum cola)
    * ratings (qype.com, ...)

  * architecture & design
    * sizing:
      * Germany: 1.493 bars (`cat germany.osm | grep -i 'k="amenity" v="bar"' | wc -l`), 12.359 pubs (`cat germany.osm | grep -i 'k="amenity" v="pub"' | wc -l`)
    * internationalization
    * world-wide usage (scalable)
    * quick response time
    * reduced design, concentrate on content
    * easily adopt to other topics like schoolmap, bankmap (ATM), public toilette, supermärkte etc.
    * platform possibilities
      * PHP/ZF on own server (doesn't scale, cheap in the beginning)
      * GAE (scales, cheap in the beginning)
      * Amazon EC2 (scales, expensive in the beginning)
    * source possibilities:
      * OSM, all relevant nodes can be received like this http://www.informationfreeway.org/api/0.6/*%5Bamenity=pub%5D%5Bbbox=8.4806,53.0159,8.9911,53.2299%5D (http://wiki.openstreetmap.org/wiki/Bremen/POI/Restaurant_%26_Bar)


  * todo
    * market evaluation: Google Maps, Bing maps, Foursqure, cojito.do, facebook places, bartime.de
      * http://toolserver.org/~stephankn/smoking/ (only smoking yes/no/..)
      * http://toolserver.org/~stephankn/cuisine/ (not responding)
      * http://www.partygps.com/ (only US, no real details)
      * http://outalot.com/ (only 2 US areas, no real details)
      * http://www.pubipedia.co.uk/ (only UK, no real details)
      * http://www.BestBloodyMary.com/ (only US, only Bloody Mary ratings)
      * http://www.urbandrinks.com/ (only US, many details)
      * http://www.thursdayclub.com/city/ (only few in Germany, some details)
    * add service to http://wiki.openstreetmap.org/wiki/List_of_OSM_based_Services and sub pages

# Code #
```
<node id='280861523' lat='53.2178567' lon='8.5079260' user='FK270673' timestamp='2008-07-24T23:09:06Z' uid='42429' version='1' changeset='588434'>
    <tag k='amenity' v='pub'/>
    <tag k='created_by' v='Potlatch 0.10'/>
    <tag k='name' v='Rekumer Siel'/>
</node>

<node id='419718790' lat='53.2272702' lon='8.785486' user='crom' timestamp='2010-10-12T15:38:18Z' uid='50274' version='11' changeset='6022030'>
    <tag k='amenity' v='pub'/>
    <tag k='name' v='Loger Eck'/>
    <tag k='opening_hours' v='Mi-So 17:00-24:00'/>
    <tag k='real_ale' v='Newcastle Brown Ale'/>
    <tag k='smoking' v='isolated'/>
  </node>

  <node id='279034511' lat='53.1752984' lon='8.6184332' user='hwi' timestamp='2010-04-05T10:47:47Z' uid='15302' version='5' changeset='4331606'>
    <tag k='amenity' v='pub'/>
    <tag k='name' v='Pinökel'/>
    <tag k='website' v='http://www.pinoekel.de/'/>
  </node>

  <node id='339854640' lat='53.2229359' lon='8.9248969' user='Ebbe73' timestamp='2009-10-06T20:33:37Z' uid='55521' version='5' changeset='2764853'>
    <tag k='addr:city' v='Worpswede'/>
    <tag k='addr:country' v='DE'/>
    <tag k='addr:housenumber' v='21'/>
    <tag k='addr:postcode' v='27726'/>
    <tag k='addr:street' v='Findorffstraße'/>
    <tag k='amenity' v='pub'/>
    <tag k='name' v='Zur Kogge'/>
  </node>


  <node id='280943964' lat='53.0553859' lon='8.6359727' user='gastromat' timestamp='2010-07-03T18:39:44Z' uid='309463' version='5' changeset='5126916'>
    <tag k='amenity' v='pub'/>
    <tag k='cuisine' v='mexican'/>
    <tag k='food' v='yes'/>
    <tag k='name' v='El Mariachi'/>
    <tag k='old_name' v='Alte Kämmerei'/>
    <tag k='operator' v='El Mariachi'/>
    <tag k='smoking' v='isolated'/>
    <tag k='sportsbar' v='yes'/>
  </node>

  <way id='49363073' user='Dusche' uid='155761' timestamp='2010-04-06T19:21:32Z' version='3' changeset='4347829'>
    <nd ref='626935321'/>
    <nd ref='626935322'/>
    <nd ref='626935324'/>
    <nd ref='626935325'/>
    <nd ref='626935326'/>
    <nd ref='626935328'/>
    <nd ref='626935321'/>
    <tag k='amenity' v='pub'/>
    <tag k='building' v='yes'/>
    <tag k='name' v='Kerem Kulturkneipe'/>
    <tag k='sportsbar' v='yes'/>
    <tag k='website' v='http://www.kerem-kultur-kneipe.de'/>
  </way>

  <way id='52027291' visible='true' timestamp='2010-04-24T12:10:47Z' version='2' changeset='4511348' user='Dusche' uid='155761'>
    <nd ref='519952242'/>
    <nd ref='663249338'/>
    <nd ref='663249342'/>
    <nd ref='663249343'/>
    <nd ref='663249340'/>
    <nd ref='519952242'/>
    <tag k='amenity' v='bar'/>
    <tag k='building' v='yes'/>
    <tag k='name' v='Cramers'/>
  </way>

  <node id='931939126' lat='53.0882909' lon='8.8083763' version='1' changeset='5921454' user='f00bar' uid='350549' visible='true' timestamp='2010-09-30T16:36:09Z'>
    <tag k='amenity' v='bar'/>
    <tag k='name' v='Oniro'/>
  </node>
```