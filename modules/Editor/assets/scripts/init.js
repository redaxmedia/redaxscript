/**
 * @tableofcontents
 *
 * 1. editor
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. editor */

rs.modules.editor =
{
	init: rs.registry.lastTable === 'articles' || rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit' && rs.registry.tableParameter === 'articles' || rs.registry.tableParameter === 'extras' || rs.registry.tableParameter === 'comments',
	selector: 'form textarea.rs-admin-js-editor-textarea, form textarea.rs-js-editor-textarea',
	options:
	{
		className:
		{
			editor: 'rs-js-editor rs-editor',
			editorPreview: 'rs-js-editor-preview rs-editor-preview',
			editorToolbar: 'rs-js-toolbar rs-editor-toolbar rs-fn-clearfix',
			editorControl: 'rs-js-editor-control rs-editor-control',
			editorSourceCode: 'rs-source-code',
			editorWysiwyg: 'rs-wysiwyg'
		},
		element:
		{
			editorControl: 'a.rs-js-editor-control'
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
				'orderedList',
				'unorderedList',
				'outdent',
				'indent',
				'alignLeft',
				'alignCenter',
				'alignRight',
				'alignJustify',
				'undo',
				'redo',
				'cut',
				'copy',
				'paste',
				'insertLink',
				'unlink',
				'insertImage',
				'insertReadmore',
				'insertCodequote',
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
		breakOnEnter:
		{
			backend: true,
			frontend: true
		},
		vibrate: 100
	},
	controls:
	{
		toggle:
		{
			className: 'rs-toggle',
			title: rs.language._editor.source_code,
			method: 'toggle'
		},
		bold:
		{
			className: 'rs-bold',
			title: rs.language._editor.bold,
			method: 'action',
			command: 'bold'
		},
		italic:
		{
			className: 'rs-italic',
			title: rs.language._editor.italic,
			method: 'action',
			command: 'italic'
		},
		underline:
		{
			className: 'rs-underline',
			title: rs.language._editor.underline,
			method: 'action',
			command: 'underline'
		},
		strike:
		{
			className: 'rs-strike',
			title: rs.language._editor.strike,
			method: 'action',
			command: 'strikeThrough'
		},
		superscript:
		{
			className: 'rs-superscript',
			title: rs.language._editor.superscript,
			method: 'action',
			command: 'superscript'
		},
		subscript:
		{
			className: 'rs-subscript',
			title: rs.language._editor.subscript,
			method: 'action',
			command: 'subscript'
		},
		paragraph:
		{
			className: 'rs-paragraph',
			title: rs.language._editor.paragraph,
			method: 'format',
			command: 'p'
		},
		h1:
		{
			className: 'rs-h1',
			title: rs.language.headline,
			method: 'format',
			command: 'h1'
		},
		h2:
		{
			className: 'rs-h2',
			title: rs.language.headline,
			method: 'format',
			command: 'h2'
		},
		h3:
		{
			className: 'rs-h3',
			title: rs.language.headline,
			method: 'format',
			command: 'h3'
		},
		h4:
		{
			className: 'rs-h4',
			title: rs.language.headline,
			method: 'format',
			command: 'h4'
		},
		h5:
		{
			className: 'rs-h5',
			title: rs.language.headline,
			method: 'format',
			command: 'h5'
		},
		h6:
		{
			className: 'rs-h6',
			title: rs.language.headline,
			method: 'format',
			command: 'h6'
		},
		orderedList:
		{
			className: 'rs-ordered-list',
			title: rs.language._editor.ordered_list,
			method: 'action',
			command: 'insertOrderedList'
		},
		unorderedList:
		{
			className: 'rs-unordered-list',
			title: rs.language._editor.unordered_list,
			method: 'action',
			command: 'insertUnorderedList'
		},
		outdent:
		{
			className: 'rs-outdent',
			title: rs.language._editor.outdent,
			method: 'action',
			command: 'outdent'
		},
		indent:
		{
			className: 'rs-indent',
			title: rs.language._editor.indent,
			method: 'action',
			command: 'indent'
		},
		alignLeft:
		{
			className: 'rs-align-left',
			title: rs.language._editor.align_left,
			method: 'action',
			command: 'justifyLeft'
		},
		alignCenter:
		{
			className: 'rs-align-center',
			title: rs.language._editor.align_center,
			method: 'action',
			command: 'justifyCenter'
		},
		alignRight:
		{
			className: 'rs-align-right',
			title: rs.language._editor.align_right,
			method: 'action',
			command: 'justifyRight'
		},
		alignJustify:
		{
			className: 'rs-align-justify',
			title: rs.language._editor.align_justify,
			method: 'action',
			command: 'justifyFull'
		},
		undo:
		{
			className: 'rs-undo',
			title: rs.language._editor.undo,
			method: 'action',
			command: 'undo'
		},
		redo:
		{
			className: 'rs-redo',
			title: rs.language._editor.redo,
			method: 'action',
			command: 'redo'
		},
		cut:
		{
			className: 'rs-cut',
			title: rs.language._editor.cut,
			method: 'action',
			command: 'cut'
		},
		copy:
		{
			className: 'rs-copy',
			title: rs.language._editor.copy,
			method: 'action',
			command: 'copy'
		},
		paste:
		{
			className: 'rs-paste',
			title: rs.language._editor.paste,
			method: 'action',
			command: 'paste'
		},
		insertLink:
		{
			className: 'rs-insert-link',
			title: rs.language._editor.insert_link,
			method: 'insert',
			command: 'createLink',
			message: rs.language._editor.insert_link,
			value: ''
		},
		unlink:
		{
			className: 'rs-unlink',
			title: rs.language._editor.remove_link,
			method: 'action',
			command: 'unlink'
		},
		insertImage:
		{
			className: 'rs-insert-image',
			title: rs.language._editor.insert_image,
			method: 'insert',
			command: 'insertImage',
			message: rs.language._editor.insert_image,
			value: ''
		},
		insertCodequote:
		{
			className: 'rs-insert-codequote',
			title: rs.language._editor.insert_codequote,
			method: 'insertCodequote'
		},
		insertReadmore:
		{
			className: 'rs-insert-readmore',
			title: rs.language._editor.insert_readmore,
			method: 'insertReadmore'
		},
		unformat:
		{
			className: 'rs-unformat',
			title: rs.language._editor.remove_format,
			method: 'action',
			command: 'removeFormat'
		}
	}
};
