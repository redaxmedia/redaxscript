/**
 * @tableofcontents
 *
 * 1. tinymce
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. tinymce */

rs.modules.tinymce =
{
	init: rs.registry.lastTable === 'articles' || rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit' && rs.registry.tableParameter === 'articles' || rs.registry.tableParameter === 'extras' || rs.registry.tableParameter === 'comments',
	dependency: typeof tinymce === 'object',
	options:
	{
		selector: 'form textarea.rs-admin-js-editor-textarea',
		plugins: 'autolink code fullscreen image imagetools link media table visualblocks',
		body_class: 'rs-body',
		content_css: 'templates/' + rs.registry.template + '/dist/styles/' + rs.registry.template + '.min.css',
		skin_url: 'modules/Tinymce/dist/styles',
		images_upload_url: rs.registry.parameterRoute + 'tinymce/upload/' + rs.registry.token,
		custom_elements: 'codequote, language, module, readmore, registry, template',
		forced_root_block: false
	}
};