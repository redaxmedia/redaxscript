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
				'node_modules/icomoon-free-npm/SVG/407-twitter.svg',
				'node_modules/icomoon-free-npm/SVG/459-linkedin2.svg',
				'node_modules/icomoon-free-npm/SVG/463-stumbleupon.svg',
				'node_modules/icomoon-free-npm/SVG/466-pinterest.svg'
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
			codepoints:
			{
				'add': 0x2b,
				'check-circle': 0x2714,
				'chevron-left': 0x3008,
				'chevron-right': 0x3009,
				'clear': 0xd7,
				'create': 0x270E,
				'delete': 0x2297,
				'error': 0x274C,
				'exit-to-app': 0x2192,
				'expand-less': 0x2227,
				'expand-more': 0x2228,
				'first-page': 0x27EA,
				'import-contacts': 0x25EB,
				'info': 0x0069,
				'last-page': 0x27EB,
				'lock': 0x1F511,
				'notifications': 0x1F514,
				'person': 0x26C4,
				'power-settings-new': 0x2BBE,
				'remove': 0x2d,
				'search': 0x2315,
				'settings': 0x2731,
				'visibility-off': 0x2298,
				'vpn-key': 0x2386,
				'warning': 0x0021
			},
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
