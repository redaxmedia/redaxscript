rs.modules.Tinymce.execute = config =>
{
	const CONFIG =
	{
		...rs.modules.Tinymce.config,
		...config
	};
	const textareaList = document.querySelectorAll(CONFIG.selector);

	if (textareaList)
	{
		textareaList.forEach(textarea =>
		{
			window.tinymce.init(
			{
				...CONFIG.tinymce,
				...
				{
					target: textarea,
					setup: editor =>
					{
						editor.on('change', () =>
						{
							window.tinymce.activeEditor.uploadImages();
							window.tinymce.triggerSave();
						});
						CONFIG.tinymce.custom_elements.forEach(element =>
						{
							editor.addMenuItem(element,
							{
								text: element,
								context: 'insert',
								onselect: () =>
								{
									editor.dom.add(editor.getBody(), element);
								}
							});
						});
					}
				}
			});
		});
	}
};

/* run as needed */

if (rs.modules.Tinymce.init && rs.modules.Tinymce.dependency)
{
	rs.modules.Tinymce.execute(rs.modules.Tinymce.config);
}
