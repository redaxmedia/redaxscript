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
					},
					file_picker_callback: callback =>
					{
						const input = document.createElement('input');

						input.setAttribute('type', 'file');
						input.setAttribute('accept', 'image/*');

						/* listen on change */

						input.onchange = () =>
						{
							const file = this.files[0];
							const reader = new FileReader();

							/* listen on load */

							reader.onload = () =>
							{
								const blobCache = window.tinymce.activeEditor.editorUpload.blobCache;
								const blobInfo = blobCache.create(Date.now(), file);

								blobCache.add(blobInfo);
								callback(blobInfo.blobUri(),
								{
									title: file.name
								});
							};
							reader.readAsDataURL(file);
						};
						input.click();
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
