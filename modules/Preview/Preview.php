<?php
namespace Redaxscript\Modules\Preview;

use Redaxscript\Filesystem;
use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Module;

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
		'version' => '3.3.0'
	];

	/**
	 * routeHeader
	 *
	 * @since 3.3.0
	 */

	public function routeHeader()
	{
		if ($this->_registry->get('firstParameter') === 'preview')
		{
			$this->_registry->set('useTitle', $this->_language->get('preview', '_preview'));
			$this->_registry->set('useDescription', $this->_language->get('description', '_preview'));
			$this->_registry->set('routerBreak', true);

			/* link */

			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile('modules/Preview/dist/styles/preview.min.css');
		}
	}

	/**
	 * routeContent
	 *
	 * @since 3.3.0
	 */

	public function routeContent()
	{
		if ($this->_registry->get('firstParameter') === 'preview')
		{
			echo $this->render();
		}
	}

	/**
	 * render
	 *
	 * @since 3.2.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = null;
		$partialsFilesystem = new Filesystem\File();
		$partialsFilesystem->init('modules/Preview/partials');
		$secondParameter = $this->_registry->get('secondParameter');
		$extension = '.phtml';

		/* collect single */

		if ($secondParameter)
		{
			$output = $this->_renderPartial($secondParameter, $partialsFilesystem->renderFile($secondParameter . $extension));
		}

		/* else collect all */

		else
		{
			$partialsFilesystemArray = $partialsFilesystem->getSortArray();

			/* process filesystem */

			foreach ($partialsFilesystemArray as $value)
			{
				$alias = str_replace($extension, '', $value);
				$output .= $this->_renderPartial($alias, $partialsFilesystem->renderFile($value));
			}
		}
		return $output;
	}

	/**
	 * renderPartial
	 *
	 * @since 3.2.0
	 *
	 * @param string $alias
	 * @param string $html
	 *
	 * @return string
	 */

	protected function _renderPartial(string $alias = null, string $html = null) : string
	{
		$secondParameter = $this->_registry->get('secondParameter');
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement
			->init('a',
			[
				'href' => $secondParameter === $alias ? $parameterRoute . 'preview#' . $alias : $parameterRoute . 'preview/' . $alias
			])
			->text($secondParameter === $alias ? $this->_language->get('back') : $alias);
		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-preview',
				'id' => $alias
			])
			->html($linkElement);
		$boxElement = new Html\Element();
		$boxElement
			->init('div',
			[
				'class' => 'rs-is-preview rs-fn-clearfix'
			])
			->html($html);

		/* collect output */

		$output = $titleElement . $boxElement;
		return $output;
	}
}
