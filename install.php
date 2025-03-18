<?php
$addon = rex_addon::get('theme_gh_synchronizer');
$dataPath = $addon->getDataPath();
//Erstmal frisch machen
rex_dir::delete($dataPath);
// Data-Directory anlegen
mkdir($dataPath, 0755, true);
// Eigene Log-Tabelle
rex_sql_table::get(rex::getTable('theme_gh_synchronizer_log'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('date', 'datetime'))
    ->ensureColumn(new rex_sql_column('level', 'varchar(191)', false, 'notice'))
    ->ensureColumn(new rex_sql_column('message', 'text'))
    ->ensure();