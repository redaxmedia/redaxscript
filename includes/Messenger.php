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
	 * action array
	 *
	 * @var array
	 */

	protected $_action = array();

	/**
	 * options array
	 *
	 * @var array
	 */

	protected $_options = array(
		'className' => array(
			'list' => 'rs-list-messenger',
			'divider' => 'rs-item-divider'
		)
	);

	/**
	 * setAction
	 *
	 * @since 3.0.0
	 *
	 * @param string $text text of the action
	 * @param string $route route of the action
	 */

	public function setAction($text = null, $route = null)
	{
		if ($text)
		{
			$this->_action = [
				'text' => $text,
				'route' => $route,
			];
		}
	}

	/**
	 * success message
	 *
	 * @since 3.0.0
	 *
	 * @param array $message text of the success message
	 * @param string $title message title
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
	 * @param array $message message text
	 * @param string $title message title
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
	 * @param array $message message text
	 * @param string $title message title
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
	 * @param array $message message text
	 * @param string $title message title
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
	 * @param string $type
	 * @param array $messageData
	 * @param string $title
	 *
	 * @return string
	 */

	public function render($type = null, $messageData = null, $title = null)
	{
		$output = Hook::trigger('messengerStart');
		if ($title)
		{
			/* html element */

			$titleElement = new Html\Element();
			$titleElement->init('h2', array(
				'class' => 'rs-title-note rs-note-' . $type
			));
			$titleElement->text($title);
			$output .= $titleElement->render();
		}

		/* html element */

		$boxElement = new Html\Element();
		$boxElement->init('div', array(
			'class' => 'rs-box-note rs-note-' . $type
		));

		/* build a list */

		if (is_array($messageData))
		{
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
			$boxElement->html($listElement->html($outputItem));
		}

		/* else plain text */

		else
		{
			$boxElement->text($messageData);
		}

		/* collect output */

		$output .= $boxElement;
		if ($this->_action)
		{
			$linkElement = new Html\Element();
			$output .= $linkElement
				->init('a')
				->attr('href', $this->_action['route'])
				->text($this->_action['text']);
		}
		$output .= Hook::trigger('messengerEnd');
		return $output;
	}

	/**
	 * redirect user
	 *
	 * @since 3.0.0
	 *
	 * @param string $route
	 * @param int $time
	 *
	 * @return string $redirect
	 */

	public function redirect($route = null, $time = 4)
	{
		if (!$route)
		{
			$route = $this->_action['route'];
		}
		$output = '<meta http-equiv="refresh" content="' . $time . ';url=' . $route . '">';
		return $output;
	}
}