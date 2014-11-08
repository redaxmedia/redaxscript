<?php
namespace Redaxscript\Modules\Archive;

use Redaxscript\Db;
use Redaxscript\Element;
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
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Archive',
		'alias' => 'Archive',
		'author' => 'Redaxmedia',
		'description' => 'Generate a archive tree',
		'version' => '2.2.0',
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
		$linkElement = new Element('a');
		$listElement = new Element('ul', array(
			'class' => self::$_config['className']['list'])
		);
		$headlineElement = new Element('h3', array(
			'class' => self::$_config['className']['headline']
		));

		/* fetch result */

		$result = Db::forTablePrefix('articles')
			->selectExpr('*, YEAR(date) as year, MONTH(date) as month')
			->where('status', 1)
			->whereIn('language', array(
				Registry::get('language'),
				''
			))
			->orderByDesc('date')
			->findArray();

		/* process result */

		if (empty($result))
		{
			$error = l('article_no') . l('point');
		}
		else
		{
			$accessValidator = new Validator\Access();
			$accessDeny = 0;
			$lastDate = 0;
			$outputItem = '';
			foreach ($result as $value)
			{
				if ($accessValidator->validate($value['access'], Registry::get('myGroups')) === Validator\Validator::PASSED)
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
						$output .= $headlineElement->text(l($value['month'] - 1, '_month') . ' ' . $value['year']);
					}

					/* collect item output */

					$outputItem .= '<li>';
					$outputItem .= $linkElement->attr(array(
						'href' => $value['category'] === 0 ? $value['alias'] : build_route('articles', $value['id']),
						'title' => empty($value['description']) ? $value['title'] : $value['description']
					))->text($value['title']);
					$outputItem .= '</li>';

					/* collect list output */

					if (end($result) === $value)
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
		}

		/* handle access */

		if (count($result) === $accessDeny)
		{
			$error = l('access_no') . l('point');
		}

		/* handle error */

		if ($error)
		{
			$output = $listElement->html('<li>' . $error . '</li>');
		}
		return $output;
	}
}