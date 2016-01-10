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
	$output = Redaxscript\Hook::trigger('adminSettingFormStart');
	$output .= '<h2 class="rs-admin-title-content">' . l('settings') . '</h2>';
	$output .= form_element('form', 'form_admin', 'rs-js-validate-form rs-js-accordion rs-admin-form', '', '', '', 'action="' . REWRITE_ROUTE . 'admin/update/settings" method="post"');

	/* collect general set */

	$output .= '<fieldset class="rs-js-set-accordion rs-js-set-active rs-set-accordion rs-admin-set-accordion rs-set-active">';
	$output .= '<legend class="rs-js-title-accordion rs-js-title-active rs-title-accordion rs-admin-title-accordion rs-title-active">' . l('general') . '</legend>';
	$output .= '<ul class="rs-js-box-accordion rs-js-box-active rs-box-accordion rs-admin-box-accordion rs-box-active">';

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
	$output .= '<li>' . select_element('language', 'rs-admin-field-select', 'language', $language_array, s('language'), l('language')) . '</li>';

	/* templates directory object */

	$templates_directory = new Redaxscript\Directory();
	$templates_directory->init('templates', array(
		'admin',
		'install'
	));
	$templates_directory_array = $templates_directory->getArray();

	/* build templates select */

	$output .= '<li>' . select_element('template', 'rs-admin-field-select', 'template', $templates_directory_array, s('template'), l('template')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect metadata set */

	$output .= '<fieldset class="rs-js-set-accordion rs-set-accordion rs-admin-set-accordion">';
	$output .= '<legend class="rs-js-title-accordion rs-title-accordion rs-admin-title-accordion">' . l('metadata') . '</legend>';
	$output .= '<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion">';
	$output .= '<li>' . form_element('text', 'title', 'rs-admin-field-text', 'title', s('title'), l('title'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('text', 'author', 'rs-admin-field-text', 'author', s('author'), l('author'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('text', 'copyright', 'rs-admin-field-text', 'copyright', s('copyright'), l('copyright'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'rs-js-auto-resize rs-admin-field-textarea rs-field-small', 'description', s('description'), l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'keywords', 'rs-js-auto-resize rs-admin-field-textarea rs-field-small', 'keywords', s('keywords'), l('keywords'), 'rows="1" cols="15"') . '</li>';
	$output .= '<li>' . select_element('robots', 'rs-admin-field-select', 'robots', array(
			l('index') => 'all',
			l('index_no') => 'none'
	), s('robots'), l('robots')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect contact set */

	$output .= '<fieldset class="rs-js-set-accordion rs-set-accordion rs-admin-set-accordion">';
	$output .= '<legend class="rs-js-title-accordion rs-title-accordion rs-admin-title-accordion">' . l('contact') . '</legend>';
	$output .= '<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion">';
	$output .= '<li>' . form_element('email', 'email', 'rs-admin-field-text rs-admin-field-note', 'email', s('email'), l('email'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'subject', 'rs-admin-field-text', 'subject', s('subject'), l('subject'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . select_element('notification', 'rs-admin-field-select', 'notification', array(
			l('enable') => 1,
			l('disable') => 0
	), s('notification'), l('notification')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect formatting set */

	$output .= '<fieldset class="rs-js-set-accordion rs-set-accordion rs-admin-set-accordion">';
	$output .= '<legend class="rs-js-title-accordion rs-title-accordion rs-admin-title-accordion">' . l('formatting') . '</legend>';
	$output .= '<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion">';
	$output .= '<li>' . form_element('text', 'charset', 'rs-admin-field-text rs-admin-field-note', 'charset', s('charset'), l('charset'), 'maxlength="10" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'divider', 'rs-admin-field-text', 'divider', s('divider'), l('divider'), 'maxlength="10"') . '</li>';
	$output .= '<li>' . select_element('time', 'rs-admin-field-select', 'time', array(
			'H:i',
			'h:i'
	), s('time'), l('time')) . '</li>';
	$output .= '<li>' . select_element('date', 'rs-admin-field-select', 'date', array(
			'd.m.Y',
			'm.d.Y',
			'Y.m.d'
	), s('date'), l('date')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect contents set */

	$output .= '<fieldset class="rs-js-set-accordion rs-set-accordion rs-admin-set-accordion">';
	$output .= '<legend class="rs-js-title-accordion rs-title-accordion rs-admin-title-accordion">' . l('contents') . '</legend>';
	$output .= '<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion">';
	$homepage_array[l('none')] = 0;
	$homepage_result = Redaxscript\Db::forTablePrefix('articles')->orderByAsc('title')->findArray();
	if ($homepage_result)
	{
		foreach ($homepage_result as $r)
		{
			$homepage_array[$r['title'] . ' (' . $r['id'] . ')'] = $r['id'];
		}
	}
	$output .= '<li>' . select_element('homepage', 'rs-admin-field-select', 'homepage', $homepage_array, s('homepage'), l('homepage')) . '</li>';
	$output .= '<li>' . form_element('text', 'limit', 'rs-admin-field-text rs-admin-field-note', 'limit', s('limit'), l('limit'), 'min="1" max="1000" required="required"') . '</li>';
	$output .= '<li>' . select_element('order', 'rs-admin-field-select', 'order', array(
			l('ascending') => 'asc',
			l('descending') => 'desc'
	), s('order'), l('order')) . '</li>';
	$output .= '<li>' . select_element('pagination', 'rs-admin-field-select', 'pagination', array(
			l('enable') => 1,
			l('disable') => 0
	), s('pagination'), l('pagination')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect comments set */

	$output .= '<fieldset class="rs-js-set-accordion rs-set-accordion rs-admin-set-accordion">';
	$output .= '<legend class="rs-js-title-accordion rs-title-accordion rs-admin-title-accordion">' . l('comments') . '</legend>';
	$output .= '<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion">';
	$output .= '<li>' . select_element('moderation', 'rs-admin-field-select', 'moderation', array(
			l('enable') => 1,
			l('disable') => 0
	), s('moderation'), l('moderation')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect users set */

	$output .= '<fieldset class="rs-js-set-accordion rs-set-accordion rs-admin-set-accordion">';
	$output .= '<legend class="rs-js-title-accordion rs-title-accordion rs-admin-title-accordion">' . l('users') . '</legend>';
	$output .= '<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion">';
	$output .= '<li>' . select_element('registration', 'rs-admin-field-select', 'registration', array(
			l('enable') => 1,
			l('disable') => 0
	), s('registration'), l('registration')) . '</li>';
	$output .= '<li>' . select_element('verification', 'rs-admin-field-select', 'verification', array(
			l('enable') => 1,
			l('disable') => 0
	), s('verification'), l('verification')) . '</li>';
	$output .= '<li>' . select_element('recovery', 'rs-admin-field-select', 'recovery', array(
			l('enable') => 1,
			l('disable') => 0
	), s('recovery'), l('recovery')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect security set */

	$output .= '<fieldset class="rs-js-set-accordion rs-js-set-accordion-last rs-set-accordion rs-admin-set-accordion rs-admin-set-accordion-last">';
	$output .= '<legend class="rs-js-title-accordion rs-title-accordion rs-admin-title-accordion">' . l('security') . '</legend>';
	$output .= '<ul class="rs-js-box-accordion rs-box-accordion rs-admin-box-accordion">';
	$output .= '<li>' . select_element('captcha', 'rs-admin-field-select', 'captcha', array(
			l('random') => 1,
			l('addition') => 2,
			l('subtraction') => 3,
			l('disable') => 0
	), s('captcha'), l('captcha')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= anchor_element('internal', '', 'rs-js-cancel rs-admin-button-default rs-button-large rs-admin-button-cancel', l('cancel'), 'admin');
	$output .= form_element('button', '', 'rs-js-submit rs-admin-button-default rs-button-large rs-admin-button-submit', 'update', l('save'));
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger('adminSettingFormEnd');
	echo $output;
}
