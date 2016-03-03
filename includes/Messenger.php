<?php
namespace Redaxscript;

/**
 * parent class to generate a flash message
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

	protected $_options = array(
		'className' => array(
			'box' => 'rs-box-messenger rs-box-note',
			'title' => 'rs-title-messenger rs-title-note',
			'list' => 'rs-list-messenger',
			'link' => 'rs-button-messenger',
			'redirect' => 'rs-redirect-overlay',
			'notes' => array(
				'success' => 'rs-note-success',
				'warning' => 'rs-note-warning',
				'error' => 'rs-note-error',
				'info' => 'rs-note-info'
			)
		)
	);

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 *
	 * @param array $options options of the messenger
	 *
	 * @return Messenger
	 */

	public function init($options = array())
	{
		if (is_array($options))
		{
			$this->_options = array_merge($this->_options, $options);
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
	 * @return string
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
	 * @return Messenger
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
	 * @return Messenger
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
	 * @return Messenger
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
	 * @return Messenger
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

		/* html element */

		if ($title)
		{
			$titleElement = new Html\Element();
			$titleElement
				->init('h2', array(
					'class' => $this->_options['className']['title'] . ' ' . $this->_options['className']['notes'][$type]
				))
				->text($title);
		}
		$boxElement = new Html\Element();
		$boxElement->init('div', array(
			'class' => $this->_options['className']['box'] . ' ' . $this->_options['className']['notes'][$type]
		));
		if ($this->_actionArray['text'] && $this->_actionArray['route'])
		{
			$linkElement = new Html\Element();
			$linkElement
				->init('a', array(
					'href' => Registry::get('rewriteRoute') . $this->_actionArray['route'],
					'class' => $this->_options['className']['link']
				))
				->text($this->_actionArray['text']);

			/* redirect is numeric */

			if (is_numeric($this->_actionArray['redirect']))
			{
				$metaElement = new Html\Element();
				$metaElement->init('meta', array(
					'class' => $this->_actionArray['redirect'] === 0 ? $this->_options['className']['redirect'] : null,
					'content' => $this->_actionArray['redirect'] . ';url=' . Registry::get('rewriteRoute') . $this->_actionArray['route'],
					'http-equiv' => 'refresh'
				));
			}
		}

		/* build a list */

		if (is_array($message))
		{
			$listElement = new Html\Element();
			$listElement->init('ul', array(
				'class' => $this->_options['className']['list']
			));

			/* collect item output */

			foreach ($message as $value)
			{
				$outputItem .= '<li>' . $value . '</li>';
			}
			$boxElement->html($listElement->html($outputItem));
		}

		/* else plain text */

		else if ($message)
		{
			$boxElement->text($message);
		}

		/* collect output */

		$output .= $titleElement . $boxElement . $linkElement . $metaElement;
		$output .= Hook::trigger('messengerEnd');
		return $output;
	}
}