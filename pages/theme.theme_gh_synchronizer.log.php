<?php
$query = "SELECT * from rex_theme_gh_synchronizer_log ORDER BY id DESC";
$list = rex_list::factory($query, 50, 'theme_gh_synchronizer_log', false);
$list->removeColumn('id');
$list->setColumnLabel('date', rex_i18n::msg('theme_gh_synchronizer_log_date'));
$list->setColumnLabel('level', rex_i18n::msg('theme_gh_synchronizer_log_level'));
$list->setColumnLabel('message', rex_i18n::msg('theme_gh_synchronizer_log_message'));
$list->setColumnFormat('date', 'date','d.m.Y H:i:s');
$list->setColumnFormat('level', 'custom','theme_gh_synchronizer::returnLevelBadge',['level'=>'###level###']);
$list->setNoRowsMessage(rex_i18n::msg('theme_gh_synchronizer_log_no_rows_message'));
$list->addTableAttribute('class', 'table-hover');
$list->show();
