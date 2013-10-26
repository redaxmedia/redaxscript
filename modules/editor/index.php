<?php

/**
 * editor loader start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function editor_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/editor/styles/editor.css';
	$loader_modules_scripts[] = 'modules/editor/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/editor/scripts/editor.js';
}

/**
 * editor loader scripts transport start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function editor_loader_scripts_transport_start()
{
	$output = languages_transport(array(
		'headline',
		'editor_bold',
		'editor_italic',
		'editor_underline',
		'editor_strike',
		'editor_subscript',
		'editor_superscript',
		'editor_paragraph',
		'editor_ordered_list',
		'editor_unordered_list',
		'editor_outdent',
		'editor_indent',
		'editor_align_left',
		'editor_align_center',
		'editor_align_right',
		'editor_align_justify',
		'editor_undo',
		'editor_redo',
		'editor_insert_image',
		'editor_insert_link',
		'editor_remove_link',
		'editor_remove_format',
		'editor_cut',
		'editor_copy',
		'editor_paste',
		'editor_source_code',
		'editor_wysiwyg',
		'editor_insert_document_break',
		'editor_insert_code_quote',
		'editor_insert_php_function',
		'editor_select_text_first',
		'editor_browser_support_no'
	));
	echo $output;
}
?>