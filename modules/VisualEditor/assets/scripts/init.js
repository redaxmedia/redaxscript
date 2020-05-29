rs.modules.VisualEditor =
{
	frontend:
	{
		init: true,
		optionArray:
		{
			selector: 'textarea.rs-js-editor',
			className:
			{
				boxContent: 'rs-box-content',
				boxVisualEditor: 'rs-admin-box-visual-editor',
				listVisualEditor: 'rs-admin-list-visual-editor',
				linkVisualEditor: 'rs-admin-link-visual-editor'
			},
			controlArray:
			[
				{
					name: 'bold',
					title: rs.language._editor.bold,
					command: 'bold',
					value: null
				},
				{
					name: 'italic',
					title: rs.language._editor.italic,
					command: 'italic',
					value: null
				},
				{
					name: 'underline',
					title: rs.language._editor.underline,
					command: 'underline',
					value: null
				},
				{
					name: 'strike-through',
					title: rs.language._editor.strike_through,
					command: 'strikeThrough',
					value: null
				},
				{
					name: 'undo',
					title: rs.language._editor.undo,
					command: 'undo',
					value: null
				},
				{
					name: 'redo',
					title: rs.language._editor.redo,
					command: 'redo',
					value: null
				}
			]
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token,
		optionArray:
		{
			selector: 'textarea.rs-admin-js-editor',
			className:
			{
				boxContent: 'rs-box-content',
				boxVisualEditor: 'rs-admin-box-visual-editor',
				listVisualEditor: 'rs-admin-list-visual-editor',
				linkVisualEditor: 'rs-admin-link-visual-editor'
			},
			controlArray:
			[
				{
					name: 'bold',
					title: rs.language._editor.bold,
					command: 'bold',
					value: null
				},
				{
					name: 'italic',
					title: rs.language._editor.italic,
					command: 'italic',
					value: null
				},
				{
					name: 'underline',
					title: rs.language._editor.underline,
					command: 'underline',
					value: null
				},
				{
					name: 'strike-through',
					title: rs.language._editor.strike_through,
					command: 'strikeThrough',
					value: null
				},
				{
					name: 'paragraph',
					title: rs.language._editor.paragraph,
					command: 'formatBlock',
					value: 'p'
				},
				{
					name: 'headline-1',
					title: rs.language._editor.headline,
					command: 'formatBlock',
					value: 'h1'
				},
				{
					name: 'headline-2',
					title: rs.language._editor.headline,
					command: 'formatBlock',
					value: 'h2'
				},
				{
					name: 'headline-3',
					title: rs.language._editor.headline,
					command: 'formatBlock',
					value: 'h3'
				},
				{
					name: 'ordered-list',
					title: rs.language._editor.ordered_list,
					command: 'insertOrderedList',
					value: null
				},
				{
					name: 'unordered-list',
					title: rs.language._editor.unordered_list,
					command: 'insertUnorderedList',
					value: null
				},
				{
					name: 'remove-format',
					title: rs.language._editor.remove_format,
					command: 'removeFormat',
					value: null
				},
				{
					name: 'undo',
					title: rs.language._editor.undo,
					command: 'undo',
					value: null
				},
				{
					name: 'redo',
					title: rs.language._editor.redo,
					command: 'redo',
					value: null
				}
			]
		}
	}
};
