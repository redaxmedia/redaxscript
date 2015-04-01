<?php
namespace Redaxscript\Modules\Archive;

use Redaxscript\Db;
use Redaxscript\Element;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Validator;

/**
 * generate a archive tree
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Archive extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Archive',
		'alias' => 'Archive',
		'author' => 'Redaxmedia',
		'description' => 'Generate a archive tree',
		'version' => '2.4.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */

	public static function render()
	{
		$output = '';
		$outputItem = '';

		/* html elements */

		$titleElement = new Element('h3', array(
			'class' => self::$_config['className']['title']
		));
		$linkElement = new Element('a');
		$listElement = new Element('ul', array(
			'class' => self::$_config['className']['list'])
		);

		/* fetch articles */

		$articles = Db::forTablePrefix('articles')
			->selectExpr('*, YEAR(date) as year, MONTH(date) as month')
			->where('status', 1)
			->whereIn('language', array(
				Registry::get('language'),
				''
			))
			->orderByDesc('date')
			->findArray();

		/* process articles */

		if (empty($articles))
		{
			$error = Language::get('article_no') . Language::get('point');
		}
		else
		{
			$accessValidator = new Validator\Access();
			$accessDeny = 0;
			$lastDate = 0;
			foreach ($articles as $value)
			{
				if ($accessValidator->validate($value['access'], Registry::get('myGroups')) === Validator\ValidatorInterface::PASSED)
				{
					$currentDate = $value['month'] + $value['year'];

					/* collect output */

					if ($lastDate <> $currentDate)
					{
						if ($lastDate > 0)
						{
							$output .= $listElement->html($outputItem);
							$outputItem = '';
						}
						$output .= $titleElement->text(Language::get($value['month'] - 1, '_month') . ' ' . $value['year']);
					}

					/* collect item output */

					$outputItem .= '<li>';
					$outputItem .= $linkElement->attr(array(
						'href' => $value['category'] < 1 ? $value['alias'] : build_route('articles', $value['id']),
						'title' => $value['description'] ? $value['description'] : $value['title']
					))->text($value['title']);
					$outputItem .= '</li>';

					/* collect list output */

					if (end($articles) === $value)
					{
						$output .= $listElement->html($outputItem);
						$outputItem = '';
					}
					$lastDate = $currentDate;
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