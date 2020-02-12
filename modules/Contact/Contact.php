<?php
namespace Redaxscript\Modules\Contact;

use Redaxscript\Module;

/**
 * simple contact form
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Contact extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Contact',
		'alias' => 'Contact',
		'author' => 'Redaxmedia',
		'description' => 'Simple contact form',
		'version' => '4.2.0'
	];

	/**
	 * routeHeader
	 *
	 * @since 4.0.0
	 */

	public function routeHeader() : void
	{
		if ($this->_request->getPost('Redaxscript\Modules\Contact\Form'))
		{
			$this->_request->set('routerBreak', true);
		}
	}

	/**
	 * routeContent
	 *
	 * @since 4.0.0
	 */

	public function routeContent() : void
	{
		if ($this->_request->getPost('Redaxscript\Modules\Contact\Form'))
		{
			$controller = new Controller($this->_registry, $this->_request, $this->_language, $this->_config);
			echo $controller->process();
		}
	}

	/**
	 * render
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$form = new Form($this->_registry, $this->_language);
		return $form->render();
	}
}
