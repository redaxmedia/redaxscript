/**
 * @tableofcontents
 *
 * 1. editor
 *
 * @since 2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. editor */

r.modules.editor =
{
	startup: true,
	selector: 'form textarea.js_editor',
	options:
	{
		element:
		{
			editor: 'div.js_editor',
			editorPreview: 'div.js_editor_preview',
			editorToolbar: 'div.js_toolbar',
			editorDivider: 'a.js_editor_divider',
			editorControl: 'a.js_editor_control',
			editorSourceCode: 'a.js_editor_control_source_code',
			editorWysiwyg: 'a.js_editor_control_wysiwyg'
		},
		classString:
		{
			editor: 'js_editor editor',
			editorPreview: 'js_required js_editor_preview editor_preview',
			editorToolbar: 'js_toolbar editor_toolbar clear_fix',
			editorDivider: 'js_editor_divider editor_divider',
			editorControl: 'js_editor_control editor_control',
			editorSourceCode: 'source_code',
			editorWysiwyg: 'wysiwyg'
		},
		toolbar:
		{
			backend: ['toggle', 'divider', 'bold', 'italic', 'underline', 'strike', 'divider', 'superscript', 'subscript', 'divider', 'paragraph', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ordered_list', 'unordered_list', 'divider', 'outdent', 'indent', 'divider', 'align_left', 'align_center', 'align_right', 'align_justify', 'undo', 'redo', 'divider', 'cut', 'copy', 'paste', 'insert_link', 'unlink', 'divider', 'insert_image', 'insert_break', 'insert_code', 'insert_function', 'divider', 'unformat'],
			frontend: ['bold', 'italic', 'underline', 'strike', 'divider', 'unformat']
		},
		xhtml:
		{
			backend: true,
			frontend: true
		},
		newline:
		{
			backend: true,
			frontend: false
		},
		eol: '\n',
		vibrate: 100
	},
	controls:
	{
		toggle:
		{
			title: l.editor_source_code,
			method: 'toggle'
		},
		bold:
		{
			title: l.editor_bold,
			method: 'action',
			command: 'bold'
		},
		italic:
		{
			title: l.editor_italic,
			method: 'action',
			command: 'italic'
		},
		underline:
		{
			title: l.editor_underline,
			method: 'action',
			command: 'underline'
		},
		strike:
		{
			title: l.editor_strike,
			method: 'action',
			command: 'strikeThrough'
		},
		superscript:
		{
			title: l.editor_superscript,
			method: 'action',
			command: 'superscript'
		},
		subscript:
		{
			title: l.editor_subscript,
			method: 'action',
			command: 'subscript'
		},
		paragraph:
		{
			title: l.editor_paragraph,
			method: 'format',
			command: 'p'
		},
		h1:
		{
			title: l.headline,
			method: 'format',
			command: 'h1'
		},
		h2:
		{
			title: l.headline,
			method: 'format',
			command: 'h2'
		},
		h3:
		{
			title: l.headline,
			method: 'format',
			command: 'h3'
		},
		h4:
		{
			title: l.headline,
			method: 'format',
			command: 'h4'
		},
		h5:
		{
			title: l.headline,
			method: 'format',
			command: 'h5'
		},
		h6:
		{
			title: l.headline,
			method: 'format',
			command: 'h6'
		},
		ordered_list:
		{
			title: l.editor_ordered_list,
			method: 'action',
			command: 'insertOrderedList'
		},
		unordered_list:
		{
			title: l.editor_unordered_list,
			method: 'action',
			command: 'insertUnorderedList'
		},
		outdent:
		{
			title: l.editor_outdent,
			method: 'action',
			command: 'outdent'
		},
		indent:
		{
			title: l.editor_indent,
			method: 'action',
			command: 'indent'
		},
		align_left:
		{
			title: l.editor_align_left,
			method: 'action',
			command: 'justifyLeft'
		},
		align_center:
		{
			title: l.editor_align_center,
			method: 'action',
			command: 'justifyCenter'
		},
		align_right:
		{
			title: l.editor_align_right,
			method: 'action',
			command: 'justifyRight'
		},
		align_justify:
		{
			title: l.editor_align_justify,
			method: 'action',
			command: 'justifyFull'
		},
		undo:
		{
			title: l.editor_undo,
			method: 'action',
			command: 'undo'
		},
		redo:
		{
			title: l.editor_redo,
			method: 'action',
			command: 'redo'
		},
		cut:
		{
			title: l.editor_cut,
			method: 'action',
			command: 'cut'
		},
		copy:
		{
			title: l.editor_copy,
			method: 'action',
			command: 'copy'
		},
		paste:
		{
			title: l.editor_paste,
			method: 'action',
			command: 'paste'
		},
		insert_link:
		{
			title: l.editor_insert_link,
			method: 'insert',
			command: 'createLink',
			message: l.editor_insert_link,
			value: 'http://'
		},
		unlink:
		{
			title: l.editor_remove_link,
			method: 'action',
			command: 'unlink'
		},
		insert_image:
		{
			title: l.editor_insert_image,
			method: 'insert',
			command: 'insertImage',
			message: l.editor_insert_image,
			value: 'http://'
		},
		insert_break:
		{
			title: l.editor_insert_document_break,
			method: 'insertBreak'
		},
		insert_code:
		{
			title: l.editor_insert_code_quote,
			method: 'insertCode'
		},
		insert_function:
		{
			title: l.editor_insert_php_function,
			method: 'insert',
			command: 'insertFunction',
			message: l.editor_insert_php_function,
			value: ''
		},
		unformat:
		{
			title: l.editor_remove_format,
			method: 'action',
			command: 'removeFormat'
		}
	}
};
