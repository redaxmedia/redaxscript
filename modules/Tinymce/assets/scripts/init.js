rs.modules.Tinymce =
{
	init: rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit' && rs.registry.tableParameter === 'articles' || rs.registry.tableParameter === 'extras' || rs.registry.tableParameter === 'comments',
	dependency: typeof window.tinymce === 'object',
	optionArray:
	{
		selector: 'form textarea.rs-admin-js-editor',
		tinymce:
		{
			plugins:
			[
				'autolink',
				'code',
				'fullscreen',
				'image',
				'imagetools',
				'link',
				'media',
				'table',
				'visualblocks'
			],
			custom_elements:
			[
				'rs-code',
				'rs-language',
				'rs-module',
				'rs-more',
				'rs-registry',
				'rs-template'
			],
			short_ended_elements: 'rs-more',
			content_css: rs.baseUrl + 'templates/' + rs.registry.template + '/dist/styles/' + rs.registry.template + '.min.css',
			skin_url: rs.baseUrl + 'modules/Tinymce/dist/styles',
			images_upload_url: rs.registry.parameterRoute + 'module/tinymce/upload/' + rs.registry.token,
			forced_root_block: false,
			branding: false
		}
	}
};
