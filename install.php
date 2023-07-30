<?php
//Erstmal frisch machen
rex_dir::delete(rex_addon::get('theme_gh_synchronizer')->getDataPath());
// copy data directory
rex_dir::copy(rex_addon::get('theme_gh_synchronizer')->getPath('data'), rex_addon::get('theme_gh_synchronizer')->getDataPath());
// Eigene Log-Tabelle
rex_sql_table::get(rex::getTable('theme_gh_synchronizer_log'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('date', 'datetime'))
    ->ensureColumn(new rex_sql_column('level', 'varchar(191)', false, 'notice'))
    ->ensureColumn(new rex_sql_column('message', 'text'))
    ->ensure();