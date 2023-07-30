<?php

class theme_gh_synchronizer
{
    public static function download($repository_url, $gh_access_token, $zip_path) :bool
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $repository_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $gh_access_token",
            'User-Agent: ' . rex_addon::get('theme_gh_synchronizer')->getName()
        ));

        // Download the zip file
        $file_content = curl_exec($curl);

        // Store the zip file on the server
        $file_handler = fopen($zip_path, 'w');
        if ($file_handler === false) {
            self::log('error', 'Failed to open file for writing');
            return false;
        } else {
            if (fwrite($file_handler, $file_content) === false) {
                rex_logger::factory()->log('error', 'Failed to write file');
                return false;
            } else {
                self::log('success', 'Successfully downloaded zip file');
                self::log('success', 'File-Path: '.$zip_path);
            }
            fclose($file_handler);
        }

        // Close the cURL session
        curl_close($curl);
        return true;
    }

    public static function unzip($zip_path): ?string
    {
        $zip = new ZipArchive;
        // open the zip file for reading
        if ($zip->open($zip_path) === true) {
            // extract the contents of the zip file to the same directory
            $zip->extractTo(dirname($zip_path));
            // close the zip file
            $zip->close();
            // get the name of the unzipped folder
            $unzipped_folder_name = null;
            $dirs = glob(dirname($zip_path) . '/*', GLOB_ONLYDIR);
            foreach ($dirs as $dir) {
                if (basename($dir) !== '__MACOSX') {
                    $unzipped_folder_name = basename($dir);
                    break;
                }
            }
            return $unzipped_folder_name;
        } else {
            return null;
        }
    }

    public static function moveFolder($folder_path, $dst_folder_path) {

        if (!is_dir($folder_path)) {
            self::log('error', 'Invalid source folder: ' . $folder_path);
            return false;
        }

        if (!is_writable(dirname($dst_folder_path))) {
            self::log('error', 'Destination folder is not writable: ' . $dst_folder_path);
            return false;
        }

        if (!rex_file::move($folder_path, $dst_folder_path)) {
            self::log('error', 'Failed to move the folder from ' . $folder_path . ' to ' . $dst_folder_path);
            return false;
        }

        return true;
    }

    // delete all files and sub-folders from a folder
    public static function deleteDirAndSubDirs($dir)
    {
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file))
                self::deleteDirAndSubDirs($file);
            else
                rex_file::delete($file);
        }
       if(rex_dir::delete($dir)) {
           return true;
       }
       return false;
    }

    public static function log($level = "notice", $message = "") {
        $sql = rex_sql::factory();
        $sql->setTable('rex_theme_gh_synchronizer_log');
        $sql->setValues(['date' => date("Y-m-d H:i:s"), 'level' => $level, 'message' => $message]);
        if($sql->insert()) {
            return true;
        }
        return false;
    }

    public static function returnLevelBadge($level) {
        $class = match (strtolower($level['value'])) {
            'success' => 'success',
            'debug' => 'default',
            'info', 'notice', 'deprecated' => 'info',
            'warning' => 'warning',
            default => 'danger',
        };
        return '<span class="label label-'.$class.'">'.ucfirst($class).'</span>';
    }

} // EoC