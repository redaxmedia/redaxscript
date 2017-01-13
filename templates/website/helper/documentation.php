<?php
namespace Redaxscript\Template;

$navigationArray = [];
$categories = Tag::categoryRaw()->where('author', 'documentation-sync')->orderByAsc('rank')->findMany();
$articles = Tag::articleRaw()->where('author', 'documentation-sync')->orderByAsc('rank')->findMany();

/* process categories */

foreach ($categories as $category)
{
	if (!$category->parent)
	{
		$parentAlias = $category->alias;
		$navigationArray[$parentAlias] =
		[
			'title' => $category->title,
			'alias' => $category->alias,
			'route' => $category->alias
		];
	}
	else
	{
		$categoryAlias = $category->alias;
		$navigationArray[$parentAlias]['children'][$categoryAlias] =
		[
			'title' => $category->title,
			'alias' => $category->alias,
			'route' => $parentAlias . '/' . $category->alias
		];

		/* process articles */

		foreach ($articles as $article)
		{
			if ($article->category === $category->id)
			{
				$navigationArray[$parentAlias]['children'][$categoryAlias]['children'][$article->alias] =
				[
					'title' => $article->title,
					'alias' => $article->alias,
					'route' => $parentAlias . '/' . $category->alias . '/' . $article->alias
				];
			}
		}
	}
}
