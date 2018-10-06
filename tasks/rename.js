module.exports = () =>
{
	'use strict';

	const config =
	{
		templateAdmin:
		{
			src:
			[
				'templates/admin/assets/styles/icon.css'
			],
			dest: 'templates/admin/assets/styles/_icon.css'
		},
		templateDefault:
		{
			src:
			[
				'templates/default/assets/styles/icon.css'
			],
			dest: 'templates/default/assets/styles/_icon.css'
		},
		moduleDiretoryLister:
		{
			src:
			[
				'modules/DirectoryLister/assets/styles/icon.css'
			],
			dest: 'modules/DirectoryLister/assets/styles/_icon.css'
		},
		moduleSocialSharer:
		{
			src:
			[
				'modules/SocialSharer/assets/styles/icon.css'
			],
			dest: 'modules/SocialSharer/assets/styles/_icon.css'
		},
		moduleTableSorter:
		{
			src:
			[
				'modules/TableSorter/assets/styles/icon.css'
			],
			dest: 'modules/TableSorter/assets/styles/_icon.css'
		}
	};

	return config;
};