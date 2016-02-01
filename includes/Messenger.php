<?php
namespace Redaxscript;

/**
 * parent class to generate a flash message
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Messenger
 * @author Balázs Szilágyi
 */
class Messenger
{
	/**
	 * redirect link
	 *
	 * @var array
	 */

	protected $_action = array();

	/**
	 * options array
	 *
	 * @var array
	 */

	protected $_options = array();

	/**
	 * init
	 *
	 * @since 3.0.0
	 *
	 */

	protected $_registry = array();

	/**
	 * init
	 *
	 * @since 3.0.0
	 *
	 */

	public function init()
	{
		$this->_registry = Registry::getInstance();
	}

	/**
	 * setAction
	 *
	 * @since 3.0.0
	 *
	 * @param string $name action name
	 * @param string $route action route
	 */

	public function setAction($name = null, $route = null)
	{
		if (!empty($name))
		{
			$this->_action = [
				'action' => $name,
				'route' => $route,
			];
		}
	}

	/**
	 * success message
	 *
	 * @since 3.0.0
	 *
	 * @param array $message message text
	 * @param string $title message title
	 *
	 * @return string
	 */

	public function success($message = null, $title = null)
	{
		return $this->render($message, 'success', $title);
	}

	/**
	 * warning message
	 *
	 * @since 3.0.0
	 *
	 * @param array $message message text
	 * @param string $title message title
	 *
	 * @return string
	 */

	public function warning($message = null, $title = null)
	{
		return $this->render($message, 'warning', $title);
	}

	/**
	 * error message
	 *
	 * @since 3.0.0
	 *
	 * @param array $message message text
	 * @param string $title message title
	 *
	 * @return string
	 */

	public function error($message = null, $title = null)
	{
		return $this->render($message, 'error', $title);
	}

	/**
	 * info message
	 *
	 * @since 3.0.0
	 *
	 * @param array $message message text
	 * @param string $title message title
	 *
	 * @return string
	 */

	public function info($message = null, $title = null)
	{
		return $this->render($message, 'info', $title);
	}

	/**
	 * redirect user
	 *
	 * @since 3.0.0
	 *
	 * @param string $url
	 * @param int $time
	 *
	 * @return string $redirect
	 */

	public function redirect($url = null, $time = 4)
	{
		if (!$url)
		{
			$url = $this->_action['route'];
		}

		$redirect = '<meta http-equiv="refresh" content="' . $time . ';url=' . $url . '">';
		return $redirect;
	}

	/**
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param array $messageData
	 * @param string $type
	 * @param string $title
	 *
	 * @return string
	 */

	public function render($messageData = null, $type = null, $title = null)
	{
		$output = Hook::trigger('messengerStart');

		if ($title)
		{
			/* html element */

			$headingElement = new Html\Element();
			$headingElement->init('h2', array(
				'class' => 'rs-title-note rs-note-' . $type
			));
			$headingElement->text($title);
			$output .= $headingElement->render();
		}

		/* html element */

		$divElement = new Html\Element();
		$divElement->init('div', array(
			'class' => 'rs-box-note rs-note-' . $type
		));

		/* put messageData in a list */

		if (is_array($messageData))
		{
			/* html elements */

			$itemElement = new Html\Element();
			$itemElement->init('li');
			$listElement = new Html\Element();
			$listElement->init('ul', array(
				'class' => $this->_options['className']['list']
			));
			$outputItem = null;

			/* collect item output */

			foreach ($messageData as $value)
			{
				$outputItem .= '<li>' . $value . '</li>';
			}
			$divElement->html($listElement->html($outputItem));

		}

		/* if just one message, no need for list */

		else
		{
			$divElement->text($messageData);
		}

		$output .= $divElement->render();

		/* place action link */

		if (!empty($this->_action))
		{
			$anchorElement = new Html\Element();
			$anchorElement->init('a');
			$anchorElement->attr('href', $this->_action['route'])->text($this->_action['action']);

			$output .= $anchorElement->render();
		}
		$output .= Hook::trigger('messengerEnd');

		return $output;
	}
}