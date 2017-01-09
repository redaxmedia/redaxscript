<?php
namespace Redaxscript\Modules\Preview;

use Redaxscript\Directory;
use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Template;

/**
 * preview template elements
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Preview extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Preview',
		'alias' => 'Preview',
		'author' => 'Redaxmedia',
		'description' => 'Preview template elements',
		'version' => '3.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public function renderStart()
	{
		if ($this->_registry->get('firstParameter') === 'preview')
		{
			$this->_registry->set('metaTitle', $this->_language->get('preview', '_preview'));
			$this->_registry->set('metaDescription', $this->_language->get('description', '_preview'));
			$this->_registry->set('routerBreak', true);

			/* link */

			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile('modules/Preview/dist/styles/preview.min.css');
		}
	}

	/**
	 * routerStart
	 *
	 * @since 3.0.0
	 */

	public function routerStart()
	{
		if ($this->_registry->get('firstParameter') === 'preview')
		{
			$partialsPath = 'modules/Preview/partials';
			$partialExtension = '.phtml';
			$partialsDirectory = new Directory();
			$partialsDirectory->init($partialsPath);
			$partialsDirectoryArray = $partialsDirectory->getArray();
			$secondParameter = $this->_registry->get('secondParameter');

			/* collect partial output */

			$output = '<div class="rs-is-preview rs-fn-clearfix">';

			/* include single */

			if ($secondParameter)
			{
				$output .= $this->render($secondParameter, $partialsPath . '/' . $secondParameter . $partialExtension);
			}

			/* else include all */

			else
			{
				foreach ($partialsDirectoryArray as $value)
				{
					$alias = str_replace($partialExtension, '', $value);
					$output .= $this->render($alias, $partialsPath . '/' . $value);
				}
			}
			$output .= '</div>';
			echo $output;
		}
	}

	/**
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param string $alias
	 * @param string $path
	 *
	 * @return string
	 */

	public function render($alias = null, $path = null)
	{
		$titleElement = new Html\Element();
		$titleElement->init('h2',
		[
			'class' => 'rs-title-preview',
			'id' => $alias
		]);
		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'href' => $this->_registry->get('secondParameter') === $alias ? $this->_registry->get('parameterRoute') . 'preview#' . $alias : $this->_registry->get('parameterRoute') . 'preview/' . $alias
		])
		->text($this->_registry->get('secondParameter') === $alias ? $this->_language->get('back') : $alias);

		/* collect output */

		$output = $titleElement->html($linkElement);
		$output .= Template\Tag::partial($path);
		return $output;
	}
}
