<?php
namespace Redaxscript\Modules\Archive;

use Redaxscript\Db;
use Redaxscript\Html;
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
		'version' => '3.0.0'
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
		$output = null;
		$outputItem = null;

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h3', array(
			'class' => self::$_configArray['className']['title']
		));
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => self::$_configArray['className']['list']
		));

		/* fetch articles */

		$articles = Db::forTablePrefix('articles')
			->where('status', 1)
			->whereRaw('(language = ? OR language is ?)', array(
				Registry::get('language'),
				null
			))
			->orderByDesc('date')
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
			$lastDate = 0;
			foreach ($articles as $value)
			{
				if ($accessValidator->validate($value->access, Registry::get('myGroups')) === Validator\ValidatorInterface::PASSED)
				{
					$month = date('n', strtotime($value->date));
					$year = date('Y', strtotime($value->date));
					$currentDate = $month + $year;

					/* collect output */

					if ($lastDate <> $currentDate)
					{
						$output .= $titleElement->text(Language::get($month - 1, '_month') . ' ' . $year);
					}
					$lastDate = $currentDate;

					/* collect item output */

					$outputItem = '<li>';
					$outputItem .= $linkElement
						->attr(array(
							'href' => Registry::get('parameterRoute') . build_route('articles', $value->id),
							'title' => $value->description ? $value->description : $value->title
						))
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
