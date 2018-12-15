rs.modules.Tinymce.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.Tinymce.optionArray,
		...optionArray
	};
	const textareaList = document.querySelectorAll(OPTION.selector);

	if (textareaList)
	{
		textareaList.forEach(textarea =>
		{
			window.tinymce.init(
			{
				...OPTION.tinymce,
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
						OPTION.tinymce.custom_elements.forEach(element =>
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
	rs.modules.Tinymce.process(rs.modules.Tinymce.optionArray);
}
