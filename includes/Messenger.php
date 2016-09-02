<?php
namespace Redaxscript;

/**
 * parent class to create a message
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
	 * @var object
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
			'box' => ' rs-box-note rs-fn-clearfix',
			'title' => 'rs-title-note',
			'list' => 'rs-list-note',
			'link' => 'rs-button-note',
			'redirect' => 'rs-meta-redirect',
			'notes' =>
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
	 * @return Messenger
	 */

	public function init($optionArray = [])
	{
		if (is_array($optionArray))
		{
			$this->_optionArray = array_merge($this->_optionArray, $optionArray);
		}
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
	 * @return Messenger
	 */

	public function setUrl($text = null, $url = null)
	{
		if (strlen($text) && strlen($url))
		{
			$this->_actionArray['text'] = $text;
			$this->_actionArray['route'] = false;
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
	 * @return Messenger
	 */

	public function setRoute($text = null, $route = null)
	{
		if (strlen($text) && strlen($route))
		{
			$this->_actionArray['text'] = $text;
			$this->_actionArray['route'] = $route;
			$this->_actionArray['url'] = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_actionArray['route'];
		}
		return $this;
	}

	/**
	 * do the redirect
	 *
	 * @since 3.0.0
	 *
	 * @param integer $timeout timeout of the redirect
	 *
	 * @return Messenger
	 */

	public function doRedirect($timeout = 2)
	{
		$this->_actionArray['redirect'] = $timeout;
		return $this;
	}

	/**
	 * success message
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $message message of the success
	 * @param string $title title of the success
	 *
	 * @return string
	 */

	public function success($message = null, $title = null)
	{
		return $this->render('success', $message, $title);
	}

	/**
	 * warning message
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $message message of the warning
	 * @param string $title message title of the warning
	 *
	 * @return string
	 */

	public function warning($message = null, $title = null)
	{
		return $this->render('warning', $message, $title);
	}

	/**
	 * error message
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $message message of the error
	 * @param string $title title of the error
	 *
	 * @return string
	 */

	public function error($message = null, $title = null)
	{
		return $this->render('error', $message, $title);
	}

	/**
	 * info message
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $message message of the info
	 * @param string $title title of the info
	 *
	 * @return string
	 */

	public function info($message = null, $title = null)
	{
		return $this->render('info', $message, $title);
	}

	/**
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the flash
	 * @param mixed $message message of the flash
	 * @param string $title title of the flash
	 *
	 * @return string
	 */

	public function render($type = null, $message = null, $title = null)
	{
		$output = Hook::trigger('messengerStart');
		$outputItem = null;

		/* html elements */

		if ($title)
		{
			$titleElement = new Html\Element();
			$titleElement
				->init('h2',
				[
					'class' => $this->_optionArray['className']['title'] . ' ' . $this->_optionArray['className']['notes'][$type]
				])
				->text($title);
		}
		$boxElement = new Html\Element();
		$boxElement->init('div',
		[
			'class' => $this->_optionArray['className']['box'] . ' ' . $this->_optionArray['className']['notes'][$type]
		]);

		/* create a list */

		if (is_array($message) && count($message) > 1)
		{
			$listElement = new Html\Element();
			$listElement->init('ul',
			[
				'class' => $this->_optionArray['className']['list']
			]);

			/* collect item output */

			foreach ($message as $value)
			{
				$outputItem .= '<li>' . $value . '</li>';
			}
			$boxElement->html($listElement->html($outputItem));
		}

		/* else plain text */

		else
		{
			$boxElement->html(array_key_exists(0, $message) ? $message[0] : $message);
		}

		/* collect output */

		$output .= $titleElement . $boxElement . $this->_renderAction($type);
		$output .= Hook::trigger('messengerEnd');
		return $output;
	}

	/**
	 * render action
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the flash
	 *
	 * @return string
	 */

	protected function _renderAction($type = null)
	{
		$output = null;
		if ($this->_actionArray['text'] && ($this->_actionArray['route'] || $this->_actionArray['url']))
		{
			$linkElement = new Html\Element();
			$output .= $linkElement
				->init('a',
				[
					'href' => $this->_actionArray['route'] ? $this->_registry->get('parameterRoute') . $this->_actionArray['route'] : $this->_actionArray['url'],
					'class' => $this->_optionArray['className']['link'] . ' ' . $this->_optionArray['className']['notes'][$type]
				])
				->text($this->_actionArray['text']);

			/* meta redirect */

			if (is_numeric($this->_actionArray['redirect']))
			{
				$metaElement = new Html\Element();
				$output .= $metaElement->init('meta',
				[
					'class' => $this->_actionArray['redirect'] === 0 ? $this->_optionArray['className']['redirect'] : null,
					'content' => $this->_actionArray['redirect'] . ';url=' . $this->_actionArray['url'] ,
					'http-equiv' => 'refresh'
				]);
			}
		}
		return $output;
	}
}