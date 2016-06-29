<?php
namespace Redaxscript;

/**
 * parent class to generate a message
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

	protected $_actionArray = array();

	/**
	 * options of the messenger
	 *
	 * @var array
	 */

	protected $_optionArray = array(
		'className' => array(
			'box' => 'rs-box-messenger rs-box-note',
			'title' => 'rs-title-messenger rs-title-note',
			'list' => 'rs-list-messenger',
			'link' => 'rs-button-default rs-button-messenger',
			'redirect' => 'rs-meta-redirect',
			'notes' => array(
				'success' => 'rs-note-success',
				'warning' => 'rs-note-warning',
				'error' => 'rs-note-error',
				'info' => 'rs-note-info'
			)
		)
	);

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

	public function init($optionArray = array())
	{
		if (is_array($optionArray))
		{
			$this->_optionArray = array_merge($this->_optionArray, $optionArray);
		}
		return $this;
	}


	/**
	 * set the action
	 *
	 * @since 3.0.0
	 *
	 * @param string $text text of the action
	 * @param string $route route of the action
	 *
	 * @return Messenger
	 */

	public function setAction($text = null, $route = null)
	{
		if (strlen($text) && strlen($route))
		{
			$this->_actionArray = array(
				'text' => $text,
				'route' => $route
			);
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
				->init('h2', array(
					'class' => $this->_optionArray['className']['title'] . ' ' . $this->_optionArray['className']['notes'][$type]
				))
				->text($title);
		}
		$boxElement = new Html\Element();
		$boxElement->init('div', array(
			'class' => $this->_optionArray['className']['box'] . ' ' . $this->_optionArray['className']['notes'][$type]
		));

		/* create a list */

		if (is_array($message))
		{
			$listElement = new Html\Element();
			$listElement->init('ul', array(
				'class' => $this->_optionArray['className']['list']
			));

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
			$boxElement->html($message);
		}

		/* collect output */

		$output .= $titleElement . $boxElement . $this->_renderAction();
		$output .= Hook::trigger('messengerEnd');
		return $output;
	}

	/**
	 * render action
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _renderAction()
	{
		$output = null;
		if ($this->_actionArray['text'] && $this->_actionArray['route'])
		{
			$linkElement = new Html\Element();
			$output .= $linkElement
				->init('a', array(
					'href' => $this->_registry->get('parameterRoute') . $this->_actionArray['route'],
					'class' => $this->_optionArray['className']['link']
				))
				->text($this->_actionArray['text']);

			/* meta redirect */

			if (is_numeric($this->_actionArray['redirect']))
			{
				$metaElement = new Html\Element();
				$output .= $metaElement->init('meta', array(
					'class' => $this->_actionArray['redirect'] === 0 ? $this->_optionArray['className']['redirect'] : null,
					'content' => $this->_actionArray['redirect'] . ';url=' . $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_actionArray['route'],
					'http-equiv' => 'refresh'
				));
			}
		}
		return $output;
	}
}