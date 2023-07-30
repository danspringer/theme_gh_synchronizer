<?php
class rex_api_github_webhook extends rex_api_function
{
    protected $published = true; // Aufruf aus dem Frontend erlaubt

    function execute()
    {

        // Im Header checken ob Signature Header vorhanden ist
        $signature = isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : '';
        if (empty($signature)) {
            theme_gh_synchronizer::log('error', 'Missing signature header');
            exit();
        }

        // Addon-Settings holen
        $addon          = rex_addon::get('theme_gh_synchronizer');
        $secret         = $addon->getConfig('webhook_secret');
        $webhook_id     = $addon->getConfig('webhook_id');
        $repository_url = $addon->getConfig('repository_url');
        $folder_name    = 'theme';


        // Extract username, repository name aus der URL mit regular expressions
        preg_match('#^https?://github.com/([^/]+)/([^/]+)#', $repository_url, $matches);
        if (count($matches) < 3) {
            preg_match('#^/([^/]+)/([^/]+)#', $repository_url, $matches);
            if (count($matches) < 3) {
                theme_gh_synchronizer::log('error', 'Invalid repository URL');
                exit();
            }
        }
        $username = $matches[1];
        $repository = $matches[2];

        // Build the API URL for downloading the repository ZIP file
        $repository_url = "https://api.github.com/repos/$username/$repository/zipball";

        // Ist die Webhook-ID im Addon gesetzt und wird die ID im Header von GitHub mitgesendet?
        if ($webhook_id == "") {
            theme_gh_synchronizer::log('error', 'Webhook-ID in AddOn-Settings missing');
            exit();
        }
        $x_github_hook_id = isset($_SERVER['HTTP_X_GITHUB_HOOK_ID']) ? $_SERVER['HTTP_X_GITHUB_HOOK_ID'] : '';
        if (empty($x_github_hook_id)) {
            theme_gh_synchronizer::log('error', 'X-GitHub-Hook-ID in header missing');
            exit();
        }

        // Stimmen die beiden Webhook-IDs überein?
        if ($_SERVER['HTTP_X_GITHUB_HOOK_ID'] != $webhook_id) {
            theme_gh_synchronizer::log('error', 'Webhook-ID not matching');
            exit();
        }

        // Daten holen
        $body = rex_file::get('php://input');

        // Github-Secret checken
        list($algo, $hash) = explode('=', $signature, 2);
        $expectedHash = hash_hmac($algo, $body, $secret);
        if (!hash_equals($hash, $expectedHash)) {
            theme_gh_synchronizer::log('error', 'Invalid signature');
            exit();
        }

        // Wenn der Token valide ist, weitermachen
        $data = rex_var::toArray($body);
        // Alles okay, los geht´s
        theme_gh_synchronizer::log('success', $body);

        $gh_access_token = $addon->getConfig('access_token');
        $zip_path = $addon->getDataPath() . "master.zip";
        // Wenn Download der Datei von GH erfolgreich war, unzip
        if( theme_gh_synchronizer::download($repository_url, $gh_access_token, $zip_path) ){
            $unzipped_folder_name = theme_gh_synchronizer::unzip($zip_path);
            // Wenn der Name des entpackten Ordners zurückkommt, theme-Ordner im Root suchen und verschieben, anschließend urspr. Ordner löschen.
            if( $unzipped_folder_name) {
                theme_gh_synchronizer::log('info', 'Unzipped: ' . $unzipped_folder_name);
                // Nur den Theme-Ordner aus dem entpackten Zip-Ordner ziehen und in den Data-Ordner des AddOns verschieben
                theme_gh_synchronizer::moveFolder($addon->getDataPath().$unzipped_folder_name.'/theme', $addon->getDataPath() . 'theme');
                // Unzipped-Ordner löschen, es bleibt der neue "nackte" Ordner theme übrig im Data-Ordner
                theme_gh_synchronizer::deleteDirAndSubDirs($addon->getDataPath().$unzipped_folder_name);
                // Den Live-Theme ordner erst komplett löschen, weil man Ordner mit Inhalt nicht einfach ersetzen kann
                theme_gh_synchronizer::deleteDirAndSubDirs(theme_path::base());
                // Jetzt theme-Ordner aus data, welcher vo nGH stammt verschieben und als live-theme Ordner im Root setzen
                theme_gh_synchronizer::moveFolder($addon->getDataPath().'theme', theme_path::base());
            };
            // Zip löschen
            rex_file::delete($zip_path);

            // Developer anstossen umd die Änderungen anzustossen
            rex_developer_manager::start();
        }

        exit();
    }
}// EoF function execute


