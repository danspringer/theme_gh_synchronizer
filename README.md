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


### Webhook bei GitHub anlegen
Innerhalb des Repository muss ein Webhook angelegt werden. Dies kann man hier tun: https://github.com/dein_username/repo_name/settings/hooks/

### GitHub-Access-Token anlegen

### AddOn konfigurieren

### Funktionalität testen

## Credits
Daniel Springer, Medienfeuer

## Support
https://github.com/danspringer/theme_gh_synchronizer
