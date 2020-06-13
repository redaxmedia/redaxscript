rs.modules.VisualEditor =
{
	frontend:
	{
		init: !rs.registry.adminParameter,
		optionArray:
		{
			selector: 'textarea.rs-js-editor',
			className:
			{
				boxContent: 'rs-box-content',
				boxVisualEditor: 'rs-box-visual-editor',
				listVisualEditor: 'rs-list-visual-editor',
				linkVisualEditor: 'rs-link-visual-editor',
				fieldUpload: 'rs-js-upload'
			},
			element:
			{
				fieldUpload	: 'input.rs-js-upload'
			},
			mimeTypeArray: [],
			controlArray:
			[
				{
					name: 'bold',
					title: rs.language._visual_editor.bold,
					command: 'bold',
					value: null
				},
				{
					name: 'italic',
					title: rs.language._visual_editor.italic,
					command: 'italic',
					value: null
				},
				{
					name: 'underline',
					title: rs.language._visual_editor.underline,
					command: 'underline',
					value: null
				},
				{
					name: 'strike-through',
					title: rs.language._visual_editor.strike_through,
					command: 'strikeThrough',
					value: null
				},
				{
					name: 'remove-format',
					title: rs.language._visual_editor.remove_format,
					command: 'removeFormat',
					value: null
				},
				{
					name: 'undo',
					title: rs.language._visual_editor.undo,
					command: 'undo',
					value: null
				},
				{
					name: 'redo',
					title: rs.language._visual_editor.redo,
					command: 'redo',
					value: null
				}
			]
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token && rs.registry.adminParameter,
		optionArray:
		{
			selector: 'textarea.rs-admin-js-editor',
			className:
			{
				boxContent: 'rs-box-content',
				boxVisualEditor: 'rs-admin-box-visual-editor',
				listVisualEditor: 'rs-admin-list-visual-editor',
				linkVisualEditor: 'rs-admin-link-visual-editor',
				fieldUpload: 'rs-admin-js-upload'
			},
			element:
			{
				fieldUpload: 'input.rs-admin-js-upload'
			},
			mimeTypeArray:
			[
				'image/gif',
				'image/jpeg',
				'image/png',
				'image/svg+xml'
			],
			controlArray:
			[
				{
					name: 'bold',
					title: rs.language._visual_editor.bold,
					command: 'bold',
					value: null
				},
				{
					name: 'italic',
					title: rs.language._visual_editor.italic,
					command: 'italic',
					value: null
				},
				{
					name: 'underline',
					title: rs.language._visual_editor.underline,
					command: 'underline',
					value: null
				},
				{
					name: 'strike-through',
					title: rs.language._visual_editor.strike_through,
					command: 'strikeThrough',
					value: null
				},
				{
					name: 'paragraph',
					title: rs.language._visual_editor.paragraph,
					command: 'formatBlock',
					value: 'p'
				},
				{
					name: 'headline-1',
					title: rs.language._visual_editor.headline,
					command: 'formatBlock',
					value: 'h1'
				},
				{
					name: 'headline-2',
					title: rs.language._visual_editor.headline,
					command: 'formatBlock',
					value: 'h2'
				},
				{
					name: 'headline-3',
					title: rs.language._visual_editor.headline,
					command: 'formatBlock',
					value: 'h3'
				},
				{
					name: 'ordered-list',
					title: rs.language._visual_editor.ordered_list,
					command: 'insertOrderedList',
					value: null
				},
				{
					name: 'unordered-list',
					title: rs.language._visual_editor.unordered_list,
					command: 'insertUnorderedList',
					value: null
				},
				{
					name: 'remove-format',
					title: rs.language._visual_editor.remove_format,
					command: 'removeFormat',
					value: null
				},
				{
					name: 'insert-link',
					title: rs.language._visual_editor.insert_link,
					command: 'createLink',
					value: null
				},
				{
					name: 'remove-link',
					title: rs.language._visual_editor.remove_link,
					command: 'unlink',
					value: null
				},
				{
					name: 'insert-image',
					title: rs.language._visual_editor.insert_image,
					command: 'insertImage',
					value: null
				},
				{
					name: 'upload-image',
					title: rs.language._visual_editor.upload_image,
					command: null,
					value: null
				},
				{
					name: 'undo',
					title: rs.language._visual_editor.undo,
					command: 'undo',
					value: null
				},
				{
					name: 'redo',
					title: rs.language._visual_editor.redo,
					command: 'redo',
					value: null
				}
			]
		}
	}
};
