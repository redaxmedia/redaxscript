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
		moduleRankSorter:
		{
			src:
			[
				'modules/RankSorter/assets/styles/icon.css'
			],
			dest: 'modules/RankSorter/assets/styles/_icon.css'
		},
		moduleSocialSharer:
		{
			src:
			[
				'modules/SocialSharer/assets/styles/icon.css'
			],
			dest: 'modules/SocialSharer/assets/styles/_icon.css'
		},
		moduleVisualEditor:
		{
			src:
			[
				'modules/VisualEditor/assets/styles/icon.css'
			],
			dest: 'modules/VisualEditor/assets/styles/_icon.css'
		}
	};

	return config;
};
