<?php
namespace Redaxscript\Modules\Preview;

use Redaxscript\Filesystem;
use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Module;
use function str_replace;

/**
 * overview of the elements
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Preview extends Module\Metadata
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
		'description' => 'Overview of the elements',
		'version' => '4.3.0'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'dashboard' =>
			[
				'title' => 'rs-admin-title-dashboard',
				'text' => 'rs-admin-text-dashboard'
			],
			'preview' =>
			[
				'title' => 'rs-title-preview',
				'box' => 'rs-is-preview',
			]
		]
	];

	/**
	 * renderStart
	 *
	 * @since 4.0.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'preview')
		{
			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile(
				[
					'modules/Dialog/dist/styles/dialog.min.css',
					'modules/Preview/dist/styles/preview.min.css'
				]);
		}
	}

	/**
	 * adminDashboard
	 *
	 * @since 4.1.0
	 *
	 * @return array
	 */

	public function adminDashboard() : array
	{
		$this->setDashboard($this->_renderDashboard());
		return $this->getDashboardArray();
	}

	/**
	 * routeHeader
	 *
	 * @since 3.3.0
	 */

	public function routeHeader() : void
	{
		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'preview')
		{
			$this->_registry->set('useTitle', $this->_language->get('_preview')['preview']);
			$this->_registry->set('useDescription', $this->_language->get('_preview')['description']);
			$this->_registry->set('routerBreak', true);
		}
	}

	/**
	 * routeContent
	 *
	 * @since 3.3.0
	 */

	public function routeContent() : void
	{
		if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'preview')
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
		$templatesFilesystem = new Filesystem\File();
		$templatesFilesystem->init('modules/Preview/templates');
		$thirdParameter = $this->_registry->get('thirdParameter');
		$extension = '.phtml';

		/* collect output */

		if ($thirdParameter)
		{
			$output = $this->_renderPartial($thirdParameter, $templatesFilesystem->renderFile($thirdParameter . $extension));
		}
		else
		{
			foreach ($templatesFilesystem->getSortArray() as $value)
			{
				$alias = str_replace($extension, '', $value);
				$output .= $this->_renderPartial($alias, $templatesFilesystem->renderFile($value));
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
		$thirdParameter = $this->_registry->get('thirdParameter');
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html element */

		$element = new Html\Element();
		$linkElement = $element
			->copy()
			->init('a',
			[
				'href' => $thirdParameter === $alias ? $parameterRoute . 'module/preview#' . $alias : $parameterRoute . 'module/preview/' . $alias
			])
			->text($thirdParameter === $alias ? $this->_language->get('back') : $alias);
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => $this->_optionArray['className']['preview']['title'],
				'id' => $alias
			])
			->html($linkElement);
		$boxElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_optionArray['className']['preview']['box'],
			])
			->html($html);

		/* collect output */

		$output = $titleElement . $boxElement;
		return $output;
	}

	/**
	 * renderDashboard
	 *
	 * @since 4.1.0
	 *
	 * @return string
	 */

	protected function _renderDashboard() : string
	{
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html element */

		$element = new Html\Element();
		$linkElement = $element
			->copy()
			->init('a',
			[
				'href' => $parameterRoute . 'module/preview/'
			])
			->text($this->_language->get('_preview')['preview']);
		$titleElement = $element
			->copy()
			->init('h3',
			[
				'class' => $this->_optionArray['className']['dashboard']['title']
			])
			->html($linkElement);
		$textElement = $element
			->copy()
			->init('a',
			[
				'class' => $this->_optionArray['className']['dashboard']['text']
			])
			->text($this->_language->get('_preview')['description']);

		return $titleElement . $textElement;
	}
}
