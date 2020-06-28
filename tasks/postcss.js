module.exports = () =>
{
	'use strict';

	const config =
	{
		templateAdmin:
		{
			src:
			[
				'templates/admin/assets/styles/_admin.css'
			],
			dest: 'templates/admin/dist/styles/admin.min.css'
		},
		templateConsole:
		{
			src:
			[
				'templates/console/assets/styles/_console.css'
			],
			dest: 'templates/console/dist/styles/console.min.css'
		},
		templateDefault:
		{
			src:
			[
				'templates/default/assets/styles/_default.css'
			],
			dest: 'templates/default/dist/styles/default.min.css'
		},
		templateInstall:
		{
			src:
			[
				'templates/install/assets/styles/_install.css'
			],
			dest: 'templates/install/dist/styles/install.min.css'
		},
		templateSkeleton:
		{
			src:
			[
				'templates/skeleton/assets/styles/_skeleton.css'
			],
			dest: 'templates/skeleton/dist/styles/skeleton.min.css'
		},
		moduleCodeEditor:
		{
			src:
			[
				'modules/CodeEditor/assets/styles/_code-editor.css'
			],
			dest: 'modules/CodeEditor/dist/styles/code-editor.min.css'
		},
		moduleDialog:
		{
			src:
			[
				'modules/Dialog/assets/styles/_dialog.css'
			],
			dest: 'modules/Dialog/dist/styles/dialog.min.css'
		},
		moduleDirectoryLister:
		{
			src:
			[
				'modules/DirectoryLister/assets/styles/_directory-lister.css'
			],
			dest: 'modules/DirectoryLister/dist/styles/directory-lister.min.css'
		},
		moduleFeedReader:
		{
			src:
			[
				'modules/FeedReader/assets/styles/_feed-reader.css'
			],
			dest: 'modules/FeedReader/dist/styles/feed-reader.min.css'
		},
		moduleGallery:
		{
			src:
			[
				'modules/Gallery/assets/styles/_gallery.css'
			],
			dest: 'modules/Gallery/dist/styles/gallery.min.css'
		},
		modulePreview:
		{
			src:
			[
				'modules/Preview/assets/styles/_preview.css'
			],
			dest: 'modules/Preview/dist/styles/preview.min.css'
		},
		moduleMaps:
		{
			src:
			[
				'modules/Maps/assets/styles/_maps.css'
			],
			dest: 'modules/Maps/dist/styles/maps.min.css'
		},
		moduleRankSorter:
		{
			src:
			[
				'modules/RankSorter/assets/styles/_rank-sorter.css'
			],
			dest: 'modules/RankSorter/dist/styles/rank-sorter.min.css'
		},
		moduleSocialSharer:
		{
			src:
			[
				'modules/SocialSharer/assets/styles/_social-sharer.css'
			],
			dest: 'modules/SocialSharer/dist/styles/social-sharer.min.css'
		},
		moduleVisualEditor:
		{
			src:
			[
				'modules/VisualEditor/assets/styles/_visual-editor.css'
			],
			dest: 'modules/VisualEditor/dist/styles/visual-editor.min.css'
		},
		stylelint:
		{
			src:
			[
				'assets/styles/*.css',
				'templates/*/assets/styles/*.css',
				'modules/*/assets/styles/*.css'
			],
			options:
			{
				processors:
				[
					require('stylelint'),
					require('postcss-reporter')(
					{
						throwError: true
					})
				]
			}
		},
		stylefmt:
		{
			src:
			[
				'assets/styles/*.css',
				'!assets/styles/_query.css',
				'templates/*/assets/styles/*.css',
				'modules/*/assets/styles/*.css'
			],
			options:
			{
				processors:
				[
					require('stylefmt')
				]
			}
		},
		colorguard:
		{
			src:
			[
				'templates/*/dist/styles/*.css',
				'modules/*/dist/styles/*.css'
			],
			options:
			{
				processors:
				[
					require('colorguard')(
					{
						threshold: 2,
						allowEquivalentNotation: true
					}),
					require('postcss-reporter')(
					{
						throwError: true
					})
				]
			}
		},
		options:
		{
			processors:
			[
				require('postcss-import'),
				require('postcss-custom-properties')(
				{
					preserve: false
				}),
				require('postcss-custom-media'),
				require('postcss-custom-selectors'),
				require('postcss-nesting'),
				require('postcss-extend'),
				require('postcss-color-gray')(
				{
					preserve: false
				}),
				require('postcss-color-function'),
				require('postcss-rtl'),
				require('postcss-inline-svg')(
				{
					paths:
					[
						'node_modules'
					]
				}),
				require('autoprefixer'),
				require('cssnano')
			]
		}
	};

	return config;
};
