<?php

/**
 * admin settings form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_settings_form()
{
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');
	$output .= '<h2 class="admin-title-content">' . l('settings') . '</h2>';
	$output .= form_element('form', 'form_admin', 'admin-js-validate-form admin-js-accordion admin-form-admin', '', '', '', 'action="' . REWRITE_ROUTE . 'admin/update/settings" method="post"');

	/* collect general set */

	$output .= '<fieldset class="admin-js-set-accordion admin-js-set-active admin-set-accordion admin-set-accordion-admin admin-set-active">';
	$output .= '<legend class="admin-js-title-accordion admin-js-title-active admin-title-accordion admin-title-accordion-admin admin-title-active">' . l('general') . '</legend>';
	$output .= '<ul class="admin-js-box-accordion admin-js-box-active admin-box-accordion admin-box-accordion-admin admin-box-active">';

	/* languages directory object */

	$languages_directory = new Redaxscript\Directory();
	$languages_directory->init('languages');
	$languages_directory_array = $languages_directory->getArray();

	/* build languages select */

	if (count($languages_directory_array) > 1)
	{
		$language_array[l('detect')] = 'detect';
	}
	foreach ($languages_directory_array as $value)
	{
		$value = substr($value, 0, 2);
		$language_array[l($value, '_index')] = $value;
	}
	$output .= '<li>' . select_element('language', 'admin-field-select-admin', 'language', $language_array, s('language'), l('language')) . '</li>';

	/* templates directory object */

	$templates_directory = new Redaxscript\Directory();
	$templates_directory->init('templates', array(
		'admin',
		'install'
	));
	$templates_directory_array = $templates_directory->getArray();

	/* build templates select */

	$output .= '<li>' . select_element('template', 'admin-field-select-admin', 'template', $templates_directory_array, s('template'), l('template')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect metadata set */

	$output .= '<fieldset class="admin-js-set-accordion admin-set-accordion admin-set-accordion-admin">';
	$output .= '<legend class="admin-js-title-accordion admin-title-accordion admin-title-accordion-admin">' . l('metadata') . '</legend>';
	$output .= '<ul class="admin-js-box-accordion admin-box-accordion admin-box-accordion-admin">';
	$output .= '<li>' . form_element('text', 'title', 'admin-field-text-admin', 'title', s('title'), l('title'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('text', 'author', 'admin-field-text-admin', 'author', s('author'), l('author'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('text', 'copyright', 'admin-field-text-admin', 'copyright', s('copyright'), l('copyright'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'admin-js-auto-resize admin-field-textarea-admin admin-field-small', 'description', s('description'), l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'keywords', 'admin-js-auto-resize admin-field-textarea-admin admin-field-small', 'keywords', s('keywords'), l('keywords'), 'rows="1" cols="15"') . '</li>';
	$output .= '<li>' . select_element('robots', 'admin-field-select-admin', 'robots', array(
		l('index') => 'all',
		l('index_no') => 'none'
	), s('robots'), l('robots')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect contact set */

	$output .= '<fieldset class="admin-js-set-accordion admin-set-accordion admin-set-accordion-admin">';
	$output .= '<legend class="admin-js-title-accordion admin-title-accordion admin-title-accordion-admin">' . l('contact') . '</legend>';
	$output .= '<ul class="admin-js-box-accordion admin-box-accordion admin-box-accordion-admin">';
	$output .= '<li>' . form_element('email', 'email', 'admin-field-text-admin admin-field-note', 'email', s('email'), l('email'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'subject', 'admin-field-text-admin', 'subject', s('subject'), l('subject'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . select_element('notification', 'admin-field-select-admin', 'notification', array(
		l('enable') => 1,
		l('disable') => 0
	), s('notification'), l('notification')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect formatting set */

	$output .= '<fieldset class="admin-js-set-accordion admin-set-accordion admin-set-accordion-admin">';
	$output .= '<legend class="admin-js-title-accordion admin-title-accordion admin-title-accordion-admin">' . l('formatting') . '</legend>';
	$output .= '<ul class="admin-js-box-accordion admin-box-accordion admin-box-accordion-admin">';
	$output .= '<li>' . form_element('text', 'charset', 'admin-field-text-admin admin-field-note', 'charset', s('charset'), l('charset'), 'maxlength="10" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'divider', 'admin-field-text-admin', 'divider', s('divider'), l('divider'), 'maxlength="10"') . '</li>';
	$output .= '<li>' . select_element('time', 'admin-field-select-admin', 'time', array(
		'H:i',
		'h:i'
	), s('time'), l('time')) . '</li>';
	$output .= '<li>' . select_element('date', 'admin-field-select-admin', 'date', array(
		'd.m.Y',
		'm.d.Y',
		'Y.m.d'
	), s('date'), l('date')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect contents set */

	$output .= '<fieldset class="admin-js-set-accordion admin-set-accordion admin-set-accordion-admin">';
	$output .= '<legend class="admin-js-title-accordion admin-title-accordion admin-title-accordion-admin">' . l('contents') . '</legend>';
	$output .= '<ul class="admin-js-box-accordion admin-box-accordion admin-box-accordion-admin">';
	$homepage_array[l('none')] = 0;
	$homepage_result = Redaxscript\Db::forTablePrefix('articles')->orderByAsc('title')->findArray();
	if ($homepage_result)
	{
		foreach ($homepage_result as $r)
		{
			$homepage_array[$r['title']] = $r['id'];
		}
	}
	$output .= '<li>' . select_element('homepage', 'admin-field-select-admin', 'homepage', $homepage_array, s('homepage'), l('homepage')) . '</li>';
	$output .= '<li>' . form_element('text', 'limit', 'admin-field-text-admin admin-field-note', 'limit', s('limit'), l('limit'), 'min="1" max="1000" required="required"') . '</li>';
	$output .= '<li>' . select_element('order', 'admin-field-select-admin', 'order', array(
		l('ascending') => 'asc',
		l('descending') => 'desc'
	), s('order'), l('order')) . '</li>';
	$output .= '<li>' . select_element('pagination', 'admin-field-select-admin', 'pagination', array(
		l('enable') => 1,
		l('disable') => 0
	), s('pagination'), l('pagination')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect comments set */

	$output .= '<fieldset class="admin-js-set-accordion admin-set-accordion admin-set-accordion-admin">';
	$output .= '<legend class="admin-js-title-accordion admin-title-accordion admin-title-accordion-admin">' . l('comments') . '</legend>';
	$output .= '<ul class="admin-js-box-accordion admin-box-accordion admin-box-accordion-admin">';
	$output .= '<li>' . select_element('moderation', 'admin-field-select-admin', 'moderation', array(
		l('enable') => 1,
		l('disable') => 0
	), s('moderation'), l('moderation')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect users set */

	$output .= '<fieldset class="admin-js-set-accordion admin-set-accordion admin-set-accordion-admin">';
	$output .= '<legend class="admin-js-title-accordion admin-title-accordion admin-title-accordion-admin">' . l('users') . '</legend>';
	$output .= '<ul class="admin-js-box-accordion admin-box-accordion admin-box-accordion-admin">';
	$output .= '<li>' . select_element('registration', 'admin-field-select-admin', 'registration', array(
		l('enable') => 1,
		l('disable') => 0
	), s('registration'), l('registration')) . '</li>';
	$output .= '<li>' . select_element('verification', 'admin-field-select-admin', 'verification', array(
		l('enable') => 1,
		l('disable') => 0
	), s('verification'), l('verification')) . '</li>';
	$output .= '<li>' . select_element('reminder', 'admin-field-select-admin', 'reminder', array(
		l('enable') => 1,
		l('disable') => 0
	), s('reminder'), l('reminder')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect security set */

	$output .= '<fieldset class="admin-js-set-accordion admin-js-set-accordion-last admin-set-accordion admin-set-accordion-admin admin-set-accordion-last">';
	$output .= '<legend class="admin-js-title-accordion admin-title-accordion admin-title-accordion-admin">' . l('security') . '</legend>';
	$output .= '<ul class="admin-js-box-accordion admin-box-accordion admin-box-accordion-admin">';
	$output .= '<li>' . select_element('captcha', 'admin-field-select-admin', 'captcha', array(
		l('random') => 1,
		l('addition') => 2,
		l('subtraction') => 3,
		l('disable') => 0
	), s('captcha'), l('captcha')) . '</li>';
	$output .= '<li>' . select_element('blocker', 'admin-field-select-admin', 'blocker', array(
		l('enable') => 1,
		l('disable') => 0
	), s('blocker'), l('blocker')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= anchor_element('internal', '', 'admin-js-cancel admin-button-admin admin-button-large admin-button-cancel-admin', l('cancel'), 'admin');
	$output .= form_element('button', '', 'admin-js-submit admin-button-admin admin-button-large admin-button-submit-admin', 'update', l('save'));
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}
