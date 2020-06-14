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
				boxContent: 'rs-box-comment',
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
					name: 'toggle',
					titleArray:
					[
						rs.language._visual_editor.wysiwyg,
						rs.language._visual_editor.source_code
					],
					command: null,
					value: null
				},
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
					name: 'handle-link',
					titleArray:
					[
						rs.language._visual_editor.insert_link,
						rs.language._visual_editor.remove_link
					],
					commandArray:
					[
						'createLink',
						'unlink'
					],
					value: null
				},
				{
					name: 'handle-image',
					titleArray:
					[
						rs.language._visual_editor.insert_image,
						rs.language._visual_editor.remove_image
					],
					commandArray:
					[
						'insertImage',
						'delete'
					],
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
