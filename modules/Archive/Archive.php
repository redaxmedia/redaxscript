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
		$listElement = new Element('ul');

		/* fetch result */

		$result = Db::forTablePrefix('articles')
			->where(array(
				'language' => Registry::get('language') || null,
				'status' => 1
			))
			->orderDesc('date')
			->findArray();

		/* process result */

		if (is_empty($result))
		{
			$error = l('article_no') . l('point');
		}
		else
		{
			$accessValidator = new Validator\Access();
			foreach ($result as $value)
			{
				if ($accessValidator->validate($value['access'], Registry::get('myGroups')) === Validator\Validator::PASSED)
				{
					$output .= '<li>' . $linkElement->text($value['text']) . '</li>';
				}
			}
		}

		/* handle error */

		if ($error)
		{
			$output = '<li>' . $error . '</li>';
		}

		/* collect list output */

		if ($output)
		{
			$output = $listElement->attr('class', self::$_config['className']['list'])->html($output);
		}
		return $output;
	}
}