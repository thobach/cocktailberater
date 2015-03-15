## cb-member-login ##
  * DEMO: http://thobach.dyndns.org/cocktailberater.de/html/api/member/login/email/thobach@web.de/password-md5/098f6bcd4621d373cade4e832627b4f6
  * Anfrage: http://www.cocktailberater.de/api/member/login/email/thobach@web.de/password-md5/09bf93aae4166cd12775c2592a1c613c
  * Hinweis: Das Passwort wird via HTTP übertragen, daher muss es vorher MD5 verschlüsselt werden. Es bekommt den Präfix: '0$gW4gCB:', also wird z.B.: '0$gW4gCB:godisheaven89' zu '4a1e5253b87c24389ef2f4819cf45784'.
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```
## cb-member-register ##
#### User-Story #18 ####
  * User-Story: Als Gast kann ich mich registrieren, um später Cocktails zu bestellen.
  * Anfrage: http://www.cocktailberater.de/api/member-register/forname/Thomas/surname/Bachmann/email/thobach@web.de/sex/male/passwort/09bf93aae4166cd12775c2592a1c613c
  * Hinweis: Das Passwort wird via HTTP übertragen, daher muss es vorher MD5 verschlüsselt werden. Es bekommt den Präfix: '0$gW4gCB:', also wird z.B.: '0$gW4gCB:godisheaven89' zu '4a1e5253b87c24389ef2f4819cf45784'.
  * Hinweis: Da das Passwort somit nie im Klartext in der Datenbank ankommt, kann es auch nicht ausgelesen werden, sondern nur zurückgesetz werden.
  * Beispielantwort bei Erfolg:
```
  <?xml version="1.0" encoding="utf-8" ?>
  <rsp stat="ok" />
```
## cb-member-checkout ##