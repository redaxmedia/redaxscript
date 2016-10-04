<?php
namespace Redaxscript\Modules\Sitemap;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Validator;

/**
 * generate a sitemap
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Sitemap extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Sitemap',
		'alias' => 'Sitemap',
		'author' => 'Redaxmedia',
		'description' => 'Generate a sitemap tree',
		'version' => '3.0.0'
	];

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */

	public static function render()
	{
		$output = null;

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h3',
		[
			'class' => self::$_configArray['className']['title']
		]);
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => self::$_configArray['className']['list']
		]);

		/* query articles */

		$articles = Db::forTablePrefix('articles')
			->where('status', 1)
			->whereLanguageIs(Registry::get('language'))
			->orderByDesc('category')
			->findMany();

		/* process articles */

		if (!$articles)
		{
			$error = Language::get('article_no') . Language::get('point');
		}
		else
		{
			$accessValidator = new Validator\Access();
			$accessDeny = 0;
			$lastCategory = 0;
			foreach ($articles as $value)
			{
				if ($accessValidator->validate($value->access, Registry::get('myGroups')) === Validator\ValidatorInterface::PASSED)
				{
					$currentCategory = $value->category;

					/* collect output */

					if ($lastCategory <> $currentCategory)
					{
						$output .= $titleElement->text(
							$currentCategory < 1 ? Language::get('uncategorized') : Db::forTablePrefix('categories')->whereIdIs($currentCategory)->findOne()->title
						);
					}
					$lastCategory = $currentCategory;

					/* collect item output */

					$outputItem = '<li>';
					$outputItem .= $linkElement->attr(
					[
						'href' => Registry::get('parameterRoute') . build_route('articles', $value->id),
						'title' => $value->description ? $value->description : $value->title
					])
					->text($value->title);
					$outputItem .= '</li>';

					/* collect list output */

					$output .= $listElement->html($outputItem);
				}
				else
				{
					$accessDeny++;
				}
			}

			/* handle access */

			if (count($articles) === $accessDeny)
			{
				$error = Language::get('access_no') . Language::get('point');
			}
		}

		/* handle error */

		if ($error)
		{
			$output = $listElement->html('<li>' . $error . '</li>');
		}
		return $output;
	}
}
