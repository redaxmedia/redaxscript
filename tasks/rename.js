module.exports = function ()
{
	'use strict';

	var config =
	{
		templateAdmin:
		{
			src:
			[
				'templates/admin/assets/styles/icon.tpl'
			],
			dest: 'templates/admin/assets/styles/_icon.css'
		},
		templateDefault:
		{
			src:
			[
				'templates/default/assets/styles/icon.tpl'
			],
			dest: 'templates/default/assets/styles/_icon.css'
		},
		moduleDiretoryLister:
		{
			src:
			[
				'modules/DirectoryLister/assets/styles/icon.tpl'
			],
			dest: 'modules/DirectoryLister/assets/styles/_icon.css'
		},
		moduleSocialSharer:
		{
			src:
			[
				'modules/SocialSharer/assets/styles/icon.tpl'
			],
			dest: 'modules/SocialSharer/assets/styles/_icon.css'
		}
	};

	return config;
};