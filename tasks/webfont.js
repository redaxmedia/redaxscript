module.exports = grunt =>
{
	'use strict';

	const config =
	{
		templateAdmin:
		{
			src:
			[
				'node_modules/material-design-icons/action/svg/production/ic_check_circle_24px.svg',
				'node_modules/material-design-icons/action/svg/production/ic_delete_24px.svg',
				'node_modules/material-design-icons/action/svg/production/ic_info_24px.svg',
				'node_modules/material-design-icons/action/svg/production/ic_lock_24px.svg',
				'node_modules/material-design-icons/action/svg/production/ic_power_settings_new_24px.svg',
				'node_modules/material-design-icons/action/svg/production/ic_reorder_24px.svg',
				'node_modules/material-design-icons/action/svg/production/ic_settings_24px.svg',
				'node_modules/material-design-icons/action/svg/production/ic_visibility_off_24px.svg',
				'node_modules/material-design-icons/alert/svg/production/ic_error_24px.svg',
				'node_modules/material-design-icons/alert/svg/production/ic_warning_24px.svg',
				'node_modules/material-design-icons/communication/svg/production/ic_import_contacts_24px.svg',
				'node_modules/material-design-icons/communication/svg/production/ic_vpn_key_24px.svg',
				'node_modules/material-design-icons/content/svg/production/ic_add_24px.svg',
				'node_modules/material-design-icons/content/svg/production/ic_clear_24px.svg',
				'node_modules/material-design-icons/content/svg/production/ic_create_24px.svg',
				'node_modules/material-design-icons/content/svg/production/ic_remove_24px.svg',
				'node_modules/material-design-icons/navigation/svg/production/ic_chevron_right_24px.svg',
				'node_modules/material-design-icons/navigation/svg/production/ic_expand_less_24px.svg',
				'node_modules/material-design-icons/navigation/svg/production/ic_expand_more_24px.svg',
				'node_modules/material-design-icons/navigation/svg/production/ic_subdirectory_arrow_right_24px.svg',
				'node_modules/material-design-icons/social/svg/production/ic_notifications_24px.svg',
				'node_modules/material-design-icons/social/svg/production/ic_person_24px.svg'
			],
			dest: 'templates/admin/dist/fonts',
			options:
			{
				destCss: 'templates/admin/assets/styles',
				template: 'templates/admin/assets/styles/_template.css'
			}
		},
		templateDefault:
		{
			src:
			[
				'node_modules/material-design-icons/action/svg/production/ic_check_circle_24px.svg',
				'node_modules/material-design-icons/action/svg/production/ic_info_24px.svg',
				'node_modules/material-design-icons/action/svg/production/ic_search_24px.svg',
				'node_modules/material-design-icons/alert/svg/production/ic_error_24px.svg',
				'node_modules/material-design-icons/alert/svg/production/ic_warning_24px.svg',
				'node_modules/material-design-icons/navigation/svg/production/ic_chevron_left_24px.svg',
				'node_modules/material-design-icons/navigation/svg/production/ic_chevron_right_24px.svg',
				'node_modules/material-design-icons/navigation/svg/production/ic_first_page_24px.svg',
				'node_modules/material-design-icons/navigation/svg/production/ic_last_page_24px.svg',
				'node_modules/material-design-icons/content/svg/production/ic_add_24px.svg',
				'node_modules/material-design-icons/content/svg/production/ic_remove_24px.svg',
				'node_modules/material-design-icons/navigation/svg/production/ic_chevron_right_24px.svg',
				'node_modules/material-design-icons/social/svg/production/ic_person_24px.svg'
			],
			dest: 'templates/default/dist/fonts',
			options:
			{
				destCss: 'templates/default/assets/styles',
				template: 'templates/default/assets/styles/_template.css'
			}
		},
		moduleDirectoryLister:
		{
			src:
			[
				'node_modules/material-design-icons/editor/svg/production/ic_insert_drive_file_24px.svg',
				'node_modules/material-design-icons/file/svg/production/ic_folder_24px.svg',
				'node_modules/material-design-icons/file/svg/production/ic_folder_open_24px.svg'
			],
			dest: 'modules/DirectoryLister/dist/fonts',
			options:
			{
				destCss: 'modules/DirectoryLister/assets/styles',
				template: 'modules/DirectoryLister/assets/styles/_template.css'
			}
		},
		moduleSocialSharer:
		{
			src:
			[
				'node_modules/icomoon-free-npm/SVG/396-google-plus.svg',
				'node_modules/icomoon-free-npm/SVG/401-facebook.svg',
				'node_modules/icomoon-free-npm/SVG/406-telegram.svg',
				'node_modules/icomoon-free-npm/SVG/407-twitter.svg',
				'node_modules/icomoon-free-npm/SVG/404-whatsapp.svg'
			],
			dest: 'modules/SocialSharer/dist/fonts',
			options:
			{
				destCss: 'modules/SocialSharer/assets/styles',
				template: 'modules/SocialSharer/assets/styles/_template.css'
			}
		},
		options:
		{
			font: 'icon',
			types:
			[
				'woff',
				'woff2'
			],
			rename: name =>
			{
				return require('path')
					.basename(name)
					.replace('ic_', '')
					.replace('_24px', '')
					.split('_')
					.join('-');
			},
			autoHint: false,
			htmlDemo: false
		}
	};
	if (grunt.option('W') || grunt.option('webfont-compat'))
	{
		config.options.engine = 'node';
		config.options.normalize = true;
	}

	return config;
};
