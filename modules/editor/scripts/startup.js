/**
 * @tableofcontents
 *
 * 1. editor
 */

/* @section 1. editor */

r.module.editor =
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
			editorDivider: 'div.js_editor_divider',
			editorNewline: 'div.js_editor_newline',
			editorControl: 'div.js_editor_control',
			editorSourceCode: 'div.js_editor_control_source_code',
			editorWysiwyg: 'div.js_editor_control_wysiwyg'
		},
		classString:
		{
			editor: 'js_editor editor',
			editorPreview: 'js_required js_editor_preview editor_preview',
			editorToolbar: 'js_toolbar editor_toolbar',
			editorDivider: 'js_editor_divider editor_divider',
			editorNewline: 'js_editor_newline editor_newline',
			editorControl: 'js_editor_control editor_control',
			editorSourceCode: 'source_code',
			editorWysiwyg: 'wysiwyg'
		},
		toolbar:
		{
			backend: ['toggle', 'divider', 'bold', 'italic', 'underline', 'strike', 'divider', 'superscript', 'subscript', 'divider', 'paragraph', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'newline', 'ordered_list', 'unordered_list', 'divider', 'outdent', 'indent', 'divider', 'align_left', 'align_center', 'align_right', 'align_justify', 'newline', 'undo', 'redo', 'divider', 'insert_link', 'unlink', 'insert_image', 'divider', 'cut', 'copy', 'paste', 'divider', 'insert_break', 'insert_code', 'insert_php', 'divider', 'unformat'],
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
		}
	},
	controls:
	{
		toggle:
		{
			title: l.editor_source_code,
			methode: 'toggle'
		},
		bold:
		{
			title: l.editor_bold,
			methode: 'action',
			command: 'bold'
		},
		italic:
		{
			title: l.editor_italic,
			methode: 'action',
			command: 'italic'
		},
		underline:
		{
			title: l.editor_underline,
			methode: 'action',
			command: 'underline'
		},
		strike:
		{
			title: l.editor_strike,
			methode: 'action',
			command: 'strikeThrough'
		},
		superscript:
		{
			title: l.editor_superscript,
			methode: 'action',
			command: 'superscript'
		},
		subscript:
		{
			title: l.editor_subscript,
			methode: 'action',
			command: 'subscript'
		},
		paragraph:
		{
			title: l.editor_paragraph,
			methode: 'format',
			command: 'p'
		},
		h1:
		{
			title: l.headline,
			methode: 'format',
			command: 'h1'
		},
		h2:
		{
			title: l.headline,
			methode: 'format',
			command: 'h2'
		},
		h3:
		{
			title: l.headline,
			methode: 'format',
			command: 'h3'
		},
		h4:
		{
			title: l.headline,
			methode: 'format',
			command: 'h4'
		},
		h5:
		{
			title: l.headline,
			methode: 'format',
			command: 'h5'
		},
		h6:
		{
			title: l.headline,
			methode: 'format',
			command: 'h6'
		},
		ordered_list:
		{
			title: l.editor_ordered_list,
			methode: 'action',
			command: 'insertOrderedList'
		},
		unordered_list:
		{
			title: l.editor_unordered_list,
			methode: 'action',
			command: 'insertUnorderedList'
		},
		outdent:
		{
			title: l.editor_outdent,
			methode: 'action',
			command: 'outdent'
		},
		indent:
		{
			title: l.editor_indent,
			methode: 'action',
			command: 'indent'
		},
		align_left:
		{
			title: l.editor_align_left,
			methode: 'action',
			command: 'justifyLeft'
		},
		align_center:
		{
			title: l.editor_align_center,
			methode: 'action',
			command: 'justifyCenter'
		},
		align_right:
		{
			title: l.editor_align_right,
			methode: 'action',
			command: 'justifyRight'
		},
		align_justify:
		{
			title: l.editor_align_justify,
			methode: 'action',
			command: 'justifyFull'
		},
		undo:
		{
			title: l.editor_undo,
			methode: 'action',
			command: 'undo'
		},
		redo:
		{
			title: l.editor_redo,
			methode: 'action',
			command: 'redo'
		},
		insert_image:
		{
			title: l.editor_insert_image,
			methode: 'insert',
			command: 'insertImage',
			message: l.editor_insert_image,
			value: 'http://'
		},
		insert_link:
		{
			title: l.editor_insert_link,
			methode: 'insert',
			command: 'createLink',
			message: l.editor_insert_link,
			value: 'http://'
		},
		unlink:
		{
			title: l.editor_remove_link,
			methode: 'action',
			command: 'unlink'
		},
		cut:
		{
			title: l.editor_cut,
			methode: 'action',
			command: 'cut'
		},
		copy:
		{
			title: l.editor_copy,
			methode: 'action',
			command: 'copy'
		},
		paste:
		{
			title: l.editor_paste,
			methode: 'action',
			command: 'paste'
		},
		insert_break:
		{
			title: l.editor_insert_document_break,
			methode: 'insertBreak'
		},
		insert_code:
		{
			title: l.editor_insert_code_quote,
			methode: 'insertCode'
		},
		insert_php:
		{
			title: l.editor_insert_php_function,
			methode: 'insert',
			command: 'insertPHP',
			message: l.editor_insert_php_function,
			value: ''
		},
		unformat:
		{
			title: l.editor_remove_format,
			methode: 'action',
			command: 'removeFormat'
		}
	}
};