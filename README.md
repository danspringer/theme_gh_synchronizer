# Theme-GitHub-Synchronizer für REDAXO

Dieses AddOn ermöglicht es, REDAXO mit einem GitHub-Repository zu verbinden, um dann automatisch via GitHub-Webhook den <code>theme</code>-Ordner im Root zu aktualisieren.
Somit aktualisiert sich sich REDAXO bzw. das Theme-Addon bei Commits oder gemergten Pull-Requests im entsprechenden Respository von GitHub automatisch.

## How-To
Bevor man starten kann, muss man einige Vorbereitungen treffen:
1. Developer-AddOn muss installiert sein
2. Theme-AddOn muss installiert sein
3. Repository bei GitHub anlegen (empfohlen wird ein **_privates Repository_**) - hier bitte die empfohlene ``.gitignore``-Datei von REDAXO beachten, damit keine sensiblen Daten im GitHub-Repo landen.
4. Webhook bei GitHub anlegen
5. GitHub-Access-Token anlegen
6. AddOn konfigurieren
7. Funktionalität testen

### Repository bei GitHub anlegen
Je nachdem, wie das Projekt-Setup aussieht und welche Dateien und Ordner in GitHub verwaltet werden, kann es hier individuelle Abweichungen geben. Diese Anleitung geht von folgender Ordnerstruktur für das Repository aus:
``` 
.
└── Root/
    ├── assets
    ├── redaxo
    └── theme
   ```
![Bildschirmfoto 2023-02-17 um 19 18 17](https://user-images.githubusercontent.com/16903055/219748324-2b1e78e0-727a-46c8-8f32-7decd81c7c91.png)

#### Repository auf private stellen und ``.gitignore`` beachten, damit keine sensiblen Daten wie z.B. Zugangsdaten zur Datenbank im Repo landen oder die eigene REDAXO-Logik oder die des Kunden öffentlich auf GitHub landet.


### Webhook bei GitHub anlegen
Innerhalb des Repository muss ein Webhook angelegt werden. Dies kann man hier tun: https://github.com/dein_username/repo_name/settings/hooks/

Der Webhook soll wie folgt konfiguriert sein:

**Payload-URL:**
```https://deinedomain.de/?rex-api-call=github_webhook```

**Content type**
```application/json```

**Secret**
```eigenes Secret anlegen (Github-Hinweise beachten)```

**SSL verfication**
```enabled```

**Which events would you like to trigger this webhook?**
```Let me select individual events.```
- [x] Pull requests  
- [x] Pushes  

  ![Bildschirmfoto 2023-02-17 um 19 30 27](https://user-images.githubusercontent.com/16903055/219752552-fb5f2f4f-6a4b-41bd-886a-1c0ab0b31d51.png)


### GitHub-Access-Token anlegen
Damit wir auf das private Repository zugreifen können und auch unser API-Anfragen-Limit erhöht ist, benötigen wir einen Access-Token für unseren Account bei GitHub.

Hierzu rufen wir mit unserem eingeloggten GitHub-Account die Developer-Settings auf uns legen einen `Personal access token (classic)` an.

Diesem Token geben wir in `Note` eine bezeichnende Beschreibung, damit wir wissen, um was es sich handelt.

Die `Expiration` stellen wir auf `no expiration` und bei `scope` wählen wir alles unter `repo` aus.
![Bildschirmfoto 2023-02-17 um 19 36 48](https://user-images.githubusercontent.com/16903055/219755423-aaca84b4-961d-47cc-8cc8-66ccf69137de.png)

Nachdem wir den Token generiert haben, holen wir ihn uns in die Zwischenablage und speichern ihn direkt in den [Einstellungen](index.php?page=theme/theme_gh_synchronizer/settings) des AddOns unter `Access token`.


### AddOn konfigurieren
Nachdem wir im Schritt zuvor bereits den Access token im AddOn hinterlegt haben, tragen wir in den AddOn-Einstellungen nun noch die URL des Repositorys ein, sowie das `Webhook Secret` und die `Webhook ID`.

Das Secret ist das selbst angelegte Secret des Webhooks.
Die ID des Webhooks kann man sich von GitHub anzeigen lassen, indem man unter [https://github.com/dein_username/repo_name/settings/hooks](https://github.com/dein_username/repo_name/settings/hooks) den Webhook anklickt und die ID oben aus der URL holt oder dort unter `Recent Deliveries` eine Test-Webhook auslöst und im Header von GitHub unter `X-GitHub-Hook-ID` nachsieht.


### Funktionalität testen
Wenn man alles konfiguriert hat, sollte man entweder
- Einen Test-Webhook auslösen
- Etwas in Repo pushen oder einen PR mergen
damit im [Log](index.php?page=theme/theme_gh_synchronizer/log) dann ein Eintrag zu finden ist.

## Credits
Daniel Springer, Medienfeuer

## Support
https://github.com/danspringer/theme_gh_synchronizer
