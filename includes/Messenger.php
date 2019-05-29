<?php
namespace Redaxscript;

use function array_key_exists;
use function array_replace_recursive;
use function count;
use function is_array;
use function is_numeric;
use function strlen;

/**
 * parent class to create a flash message
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Messenger
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Messenger
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * array of the action
	 *
	 * @var array
	 */

	protected $_actionArray =
	[
		'text' => null,
		'route' => null,
		'url' => null
	];

	/**
	 * options of the messenger
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'box' => ' rs-box-note',
			'title' => 'rs-title-note',
			'list' => 'rs-list-note',
			'link' => 'rs-button-note',
			'redirect' => 'rs-meta-redirect',
			'note' =>
			[
				'success' => 'rs-is-success',
				'warning' => 'rs-is-warning',
				'error' => 'rs-is-error',
				'info' => 'rs-is-info'
			]
		]
	];

	/**
	 * constructor of the class
	 *
	 * @since 2.4.0
	 *
	 * @param Registry $registry instance of the registry class
	 */

	public function __construct(Registry $registry)
	{
		$this->_registry = $registry;
	}

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray options of the messenger
	 *
	 * @return self
	 */

	public function init(array $optionArray = []) : self
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
		return $this;
	}

	/**
	 * set the absolute redirect url
	 *
	 * @since 3.0.0
	 *
	 * @param string $text text of the action
	 * @param string $url absolute url of the action
	 *
	 * @return self
	 */

	public function setUrl(string $text = null, string $url = null) : self
	{
		if (strlen($text) && strlen($url))
		{
			$this->_actionArray['text'] = $text;
			$this->_actionArray['route'] = null;
			$this->_actionArray['url'] = $url;
		}
		return $this;
	}

	/**
	 * set the relative redirect url
	 *
	 * @since 3.0.0
	 *
	 * @param string $text text of the action
	 * @param string $route relative route of the action
	 *
	 * @return self
	 */

	public function setRoute(string $text = null, string $route = null) : self
	{
		if (strlen($text) && strlen($route))
		{
			$this->_actionArray['text'] = $text;
			$this->_actionArray['route'] = $route;
			$this->_actionArray['url'] = null;
		}
		return $this;
	}

	/**
	 * do the redirect
	 *
	 * @since 3.0.0
	 *
	 * @param int $timeout timeout of the redirect
	 *
	 * @return self
	 */

	public function doRedirect(int $timeout = 2) : self
	{
		$this->_actionArray['redirect'] = $timeout;
		return $this;
	}

	/**
	 * success message
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $message message of the success
	 * @param string $title title of the success
	 *
	 * @return string
	 */

	public function success($message = null, string $title = null) : string
	{
		return $this->render('success', $message, $title);
	}

	/**
	 * info message
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $message message of the info
	 * @param string $title title of the info
	 *
	 * @return string
	 */

	public function info($message = null, string $title = null) : string
	{
		return $this->render('info', $message, $title);
	}

	/**
	 * warning message
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $message message of the warning
	 * @param string $title message title of the warning
	 *
	 * @return string
	 */

	public function warning($message = null, string $title = null) : string
	{
		return $this->render('warning', $message, $title);
	}

	/**
	 * error message
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $message message of the error
	 * @param string $title title of the error
	 *
	 * @return string
	 */

	public function error($message = null, string $title = null) : string
	{
		return $this->render('error', $message, $title);
	}

	/**
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the flash
	 * @param string|array $message message of the flash
	 * @param string $title title of the flash
	 *
	 * @return string
	 */

	public function render(string $type = null, $message = null, string $title = null) : string
	{
		$output = Module\Hook::trigger('messengerStart');

		/* html element */

		$element = new Html\Element();
		$titleElement = $title ? $element
			->copy()
			->init('h2',
			[
				'class' => $this->_optionArray['className']['title'] . ' ' . $this->_optionArray['className']['note'][$type]
			])
			->text($title) : null;
		$boxElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_optionArray['className']['box'] . ' ' . $this->_optionArray['className']['note'][$type]
			]);

		/* create a list */

		if (is_array($message) && count($message) > 1)
		{
			$listElement = $element
				->copy()
				->init('ul',
				[
					'class' => $this->_optionArray['className']['list']
				]);
			$itemElement = $element->copy()->init('li');

			/* collect item output */

			foreach ($message as $value)
			{
				$listElement
					->append(
						$itemElement->html($value)
					);
			}
			$boxElement->html($listElement);
		}

		/* else plain text */

		else
		{
			$boxElement->html(is_array($message) && array_key_exists(0, $message) ? $message[0] : $message);
		}

		/* collect output */

		$output .= $titleElement . $boxElement . $this->_renderAction($type);
		$output .= Module\Hook::trigger('messengerEnd');
		return $output;
	}

	/**
	 * render action
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the flash
	 *
	 * @return string|null
	 */

	protected function _renderAction(string $type = null) : ?string
	{
		$output = null;
		$parameterRoute = $this->_registry->get('parameterRoute');
		$root = $this->_registry->get('root');
		if ($this->_actionArray['text'] && ($this->_actionArray['route'] || $this->_actionArray['url']))
		{
			$element = new Html\Element();
			$output .= $element
				->copy()
				->init('a',
				[
					'href' => $this->_actionArray['route'] ? $parameterRoute . $this->_actionArray['route'] : $this->_actionArray['url'],
					'class' => $this->_optionArray['className']['link'] . ' ' . $this->_optionArray['className']['note'][$type]
				])
				->text($this->_actionArray['text']);

			/* meta redirect */

			if (is_numeric($this->_actionArray['redirect']))
			{
				$output .= $element
					->copy()
					->init('meta',
					[
						'class' => $this->_actionArray['redirect'] === 0 ? $this->_optionArray['className']['redirect'] : null,
						'content' => $this->_actionArray['redirect'] . ';url=' . ($this->_actionArray['route'] ? $root . '/' . $parameterRoute . $this->_actionArray['route'] : $this->_actionArray['url']),
						'http-equiv' => 'refresh'
					]);
			}
		}
		return $output;
	}
}