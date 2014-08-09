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
	$output .= '<h2 class="title_content">' . l('settings') . '</h2>';
	$output .= form_element('form', 'form_admin', 'js_validate_form js_accordion form_admin', '', '', '', 'action="' . REWRITE_ROUTE . 'admin/update/settings" method="post"');

	/* collect general set */

	$output .= '<fieldset class="js_set_accordion js_set_active set_accordion set_accordion_admin set_active">';
	$output .= '<legend class="js_title_accordion js_title_active title_accordion title_accordion_admin title_active">' . l('general') . '</legend>';
	$output .= '<ul class="js_box_accordion js_box_active box_accordion box_accordion_admin box_active">';

	/* languages directory object */

	$languages_directory = new Redaxscript\Directory('languages');
	$languages_directory_array = $languages_directory->get();

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
	$output .= '<li>' . select_element('language', 'field_select_admin', 'language', $language_array, s('language'), l('language')) . '</li>';

	/* templates directory object */

	$templates_directory = new Redaxscript\Directory('templates', array(
		'admin',
		'install'
	));
	$templates_directory_array = $templates_directory->get();

	/* build templates select */

	$output .= '<li>' . select_element('template', 'field_select_admin', 'template', $templates_directory_array, s('template'), l('template')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect metadata set */

	$output .= '<fieldset class="js_set_accordion set_accordion set_accordion_admin">';
	$output .= '<legend class="js_title_accordion title_accordion title_accordion_admin">' . l('metadata') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_admin">';
	$output .= '<li>' . form_element('text', 'title', 'field_text_admin', 'title', s('title'), l('title'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('text', 'author', 'field_text_admin', 'author', s('author'), l('author'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('text', 'copyright', 'field_text_admin', 'copyright', s('copyright'), l('copyright'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'js_auto_resize field_textarea_admin field_small_admin', 'description', s('description'), l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'keywords', 'js_auto_resize field_textarea_admin field_small_admin', 'keywords', s('keywords'), l('keywords'), 'rows="1" cols="15"') . '</li>';
	$output .= '<li>' . select_element('robots', 'field_select_admin', 'robots', array(
		l('index') => 'all',
		l('index_no') => 'none'
	), s('robots'), l('robots')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect contact set */

	$output .= '<fieldset class="js_set_accordion set_accordion set_accordion_admin">';
	$output .= '<legend class="js_title_accordion title_accordion title_accordion_admin">' . l('contact') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_admin">';
	$output .= '<li>' . form_element('email', 'email', 'field_text_admin field_note', 'email', s('email'), l('email'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'subject', 'field_text_admin', 'subject', s('subject'), l('subject'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . select_element('notification', 'field_select_admin', 'notification', array(
		l('enable') => 1,
		l('disable') => 0
	), s('notification'), l('notification')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect formatting set */

	$output .= '<fieldset class="js_set_accordion set_accordion set_accordion_admin">';
	$output .= '<legend class="js_title_accordion title_accordion title_accordion_admin">' . l('formatting') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_admin">';
	$output .= '<li>' . form_element('text', 'charset', 'field_text_admin field_note', 'charset', s('charset'), l('charset'), 'maxlength="10" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'divider', 'field_text_admin', 'divider', s('divider'), l('divider'), 'maxlength="10"') . '</li>';
	$output .= '<li>' . select_element('time', 'field_select_admin', 'time', array(
		'H:i',
		'h:i'
	), s('time'), l('time')) . '</li>';
	$output .= '<li>' . select_element('date', 'field_select_admin', 'date', array(
		'd.m.Y',
		'm.d.Y',
		'Y.d.m'
	), s('date'), l('date')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect contents set */

	$output .= '<fieldset class="js_set_accordion set_accordion set_accordion_admin">';
	$output .= '<legend class="js_title_accordion title_accordion title_accordion_admin">' . l('contents') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_admin">';
	$homepage_array[l('none')] = 0;
	$homepage_query = 'SELECT id, title FROM ' . PREFIX . 'articles ORDER BY rank ASC';
	$homepage_result = mysql_query($homepage_query);
	if ($homepage_result)
	{
		while ($r = mysql_fetch_assoc($homepage_result))
		{
			$homepage_array[$r['title']] = $r['id'];
		}
	}
	$output .= '<li>' . select_element('homepage', 'field_select_admin', 'homepage', $homepage_array, s('homepage'), l('homepage')) . '</li>';
	$output .= '<li>' . form_element('text', 'limit', 'field_text_admin field_note', 'limit', s('limit'), l('limit'), 'min="1" max="1000" required="required"') . '</li>';
	$output .= '<li>' . select_element('order', 'field_select_admin', 'order', array(
		l('ascending') => 'asc',
		l('descending') => 'desc'
	), s('order'), l('order')) . '</li>';
	$output .= '<li>' . select_element('pagination', 'field_select_admin', 'pagination', array(
		l('enable') => 1,
		l('disable') => 0
	), s('pagination'), l('pagination')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect comments set */

	$output .= '<fieldset class="js_set_accordion set_accordion set_accordion_admin">';
	$output .= '<legend class="js_title_accordion title_accordion title_accordion_admin">' . l('comments') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_admin">';
	$output .= '<li>' . select_element('moderation', 'field_select_admin', 'moderation', array(
		l('enable') => 1,
		l('disable') => 0
	), s('moderation'), l('moderation')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect users set */

	$output .= '<fieldset class="js_set_accordion set_accordion set_accordion_admin">';
	$output .= '<legend class="js_title_accordion title_accordion title_accordion_admin">' . l('users') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_admin">';
	$output .= '<li>' . select_element('registration', 'field_select_admin', 'registration', array(
		l('enable') => 1,
		l('disable') => 0
	), s('registration'), l('registration')) . '</li>';
	$output .= '<li>' . select_element('verification', 'field_select_admin', 'verification', array(
		l('enable') => 1,
		l('disable') => 0
	), s('verification'), l('verification')) . '</li>';
	$output .= '<li>' . select_element('reminder', 'field_select_admin', 'reminder', array(
		l('enable') => 1,
		l('disable') => 0
	), s('reminder'), l('reminder')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect security set */

	$output .= '<fieldset class="js_set_accordion js_set_accordion_last set_accordion set_accordion_admin set_accordion_last">';
	$output .= '<legend class="js_title_accordion title_accordion title_accordion_admin">' . l('security') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_admin">';
	$output .= '<li>' . select_element('captcha', 'field_select_admin', 'captcha', array(
		l('random') => 1,
		l('addition') => 2,
		l('subtraction') => 3,
		l('disable') => 0
	), s('captcha'), l('captcha')) . '</li>';
	$output .= '<li>' . select_element('blocker', 'field_select_admin', 'blocker', array(
		l('enable') => 1,
		l('disable') => 0
	), s('blocker'), l('blocker')) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= anchor_element('internal', '', 'js_cancel button_admin button_large_admin button_cancel_admin', l('cancel'), 'admin');
	$output .= form_element('button', '', 'js_submit button_admin button_large_admin button_submit_admin', 'update', l('save'));
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}