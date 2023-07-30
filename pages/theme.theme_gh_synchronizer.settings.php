<?php
#
$addon = rex_addon::get('theme_gh_synchronizer');

$form = rex_config_form::factory($addon->getName());

$field = $form->addFieldset(rex_i18n::msg('theme_gh_synchronizer_access_token'));
$field = $form->addTextField('access_token', $addon->getConfig('access_token'),  ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('theme_gh_synchronizer_access_token_label'));
$field->setNotice(rex_i18n::msg('theme_gh_synchronizer_access_token_notice'));

$field = $form->addFieldset(rex_i18n::msg('theme_gh_synchronizer_repository'));
$field = $form->addTextField('repository_url', $addon->getConfig('repository_url'),  ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('theme_gh_synchronizer_repository_url_label'));
$field->setNotice(rex_i18n::msg('theme_gh_synchronizer_repository_url_notice'));

$field = $form->addFieldset(rex_i18n::msg('theme_gh_synchronizer_webhook'));
$field = $form->addTextField('webhook_secret', $addon->getConfig('webhook_secret'),  ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('theme_gh_synchronizer_secret_label'));
$field->setNotice(rex_i18n::msg('theme_gh_synchronizer_secret_notice'));

$field = $form->addTextField('webhook_id', $addon->getConfig('webhook_id'),  ["class" => "form-control"]);
$field->setLabel(rex_i18n::msg('theme_gh_synchronizer_webhook_id_label'));
$field->setNotice(rex_i18n::msg('theme_gh_synchronizer_webhook_id_notice'));

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('theme_gh_synchronizer_config'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');

?>

