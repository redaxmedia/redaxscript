/**
 * @tableofcontents
 *
 * 1. editor
 *
 * @since 2.0.2
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. editor */

r.modules.editor =
{
	startup: true,
	selector: 'form textarea.js_editor_textarea',
	options:
	{
		classString:
		{
			editor: 'js_editor editor',
			editorPreview: 'js_editor_preview editor_preview',
			editorToolbar: 'js_toolbar editor_toolbar clear_fix',
			editorControl: 'js_editor_control editor_control',
			editorSourceCode: 'source_code',
			editorWysiwyg: 'wysiwyg'
		},
		element:
		{
			editorControl: 'a.js_editor_control'
		},
		toolbar:
		{
			backend:
			[
				'toggle',
				'bold',
				'italic',
				'underline',
				'strike',
				'superscript',
				'subscript',
				'paragraph',
				'h1',
				'h2',
				'h3',
				'h4',
				'h5',
				'h6',
				'ordered_list',
				'unordered_list',
				'outdent',
				'indent',
				'align_left',
				'align_center',
				'align_right',
				'align_justify',
				'undo',
				'redo',
				'cut',
				'copy',
				'paste',
				'insert_link',
				'unlink',
				'insert_image',
				'insert_break',
				'insert_code',
				'insert_function',
				'unformat'
			],
			frontend:
			[
				'bold',
				'italic',
				'underline',
				'strike',
				'unformat'
			]
		},
		xhtml:
		{
			backend: true,
			frontend: true
		},
		breakOnEnter:
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
			title: l.editor.source_code,
			method: 'toggle'
		},
		bold:
		{
			title: l.editor.bold,
			method: 'action',
			command: 'bold'
		},
		italic:
		{
			title: l.editor.italic,
			method: 'action',
			command: 'italic'
		},
		underline:
		{
			title: l.editor.underline,
			method: 'action',
			command: 'underline'
		},
		strike:
		{
			title: l.editor.strike,
			method: 'action',
			command: 'strikeThrough'
		},
		superscript:
		{
			title: l.editor.superscript,
			method: 'action',
			command: 'superscript'
		},
		subscript:
		{
			title: l.editor.subscript,
			method: 'action',
			command: 'subscript'
		},
		paragraph:
		{
			title: l.editor.paragraph,
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
			title: l.editor.ordered_list,
			method: 'action',
			command: 'insertOrderedList'
		},
		unordered_list:
		{
			title: l.editor.unordered_list,
			method: 'action',
			command: 'insertUnorderedList'
		},
		outdent:
		{
			title: l.editor.outdent,
			method: 'action',
			command: 'outdent'
		},
		indent:
		{
			title: l.editor.indent,
			method: 'action',
			command: 'indent'
		},
		align_left:
		{
			title: l.editor.align_left,
			method: 'action',
			command: 'justifyLeft'
		},
		align_center:
		{
			title: l.editor.align_center,
			method: 'action',
			command: 'justifyCenter'
		},
		align_right:
		{
			title: l.editor.align_right,
			method: 'action',
			command: 'justifyRight'
		},
		align_justify:
		{
			title: l.editor.align_justify,
			method: 'action',
			command: 'justifyFull'
		},
		undo:
		{
			title: l.editor.undo,
			method: 'action',
			command: 'undo'
		},
		redo:
		{
			title: l.editor.redo,
			method: 'action',
			command: 'redo'
		},
		cut:
		{
			title: l.editor.cut,
			method: 'action',
			command: 'cut'
		},
		copy:
		{
			title: l.editor.copy,
			method: 'action',
			command: 'copy'
		},
		paste:
		{
			title: l.editor.paste,
			method: 'action',
			command: 'paste'
		},
		insert_link:
		{
			title: l.editor.insert_link,
			method: 'insert',
			command: 'createLink',
			message: l.editor.insert_link,
			value: 'http://'
		},
		unlink:
		{
			title: l.editor.remove_link,
			method: 'action',
			command: 'unlink'
		},
		insert_image:
		{
			title: l.editor.insert_image,
			method: 'insert',
			command: 'insertImage',
			message: l.editor.insert_image,
			value: 'http://'
		},
		insert_break:
		{
			title: l.editor.insert_document_break,
			method: 'insertBreak'
		},
		insert_code:
		{
			title: l.editor.insert_code_quote,
			method: 'insertCode'
		},
		insert_function:
		{
			title: l.editor.insert_php_function,
			method: 'insert',
			command: 'insertFunction',
			message: l.editor.insert_php_function,
			value: ''
		},
		unformat:
		{
			title: l.editor.remove_format,
			method: 'action',
			command: 'removeFormat'
		}
	}
};