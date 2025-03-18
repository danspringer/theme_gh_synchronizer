<?php
$addon = rex_addon::get('theme_gh_synchronizer');
$dataPath = $addon->getDataPath();
// Data-Ordner löschen
rex_dir::delete($dataPath);
// Log-Tabelle löschen
rex_sql_table::get(rex::getTable('theme_gh_synchronizer_log'))
    ->drop();