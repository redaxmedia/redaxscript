<?php
namespace Redaxscript\View;

use Redaxscript\Admin;
use Redaxscript\Config;
use Redaxscript\Content;
use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;

/**
 * children class to create the extra
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Extra extends ViewAbstract
{
	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected $_config;

	/**
	 * options of the extra
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'tag' =>
		[
			'title' => 'h3',
			'box' => 'div'
		],
		'className' =>
		[
			'title' => 'rs-title-extra',
			'box' => 'rs-box-extra'
		],
		'orderColumn' => 'rank'
	];

	/**
	 * constructor of the class
	 *
	 * @since 4.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 * @param Language $language instance of the language class
	 * @param Config $config instance of the config class
	 */

	public function __construct(Registry $registry, Request $request, Language $language, Config $config)
	{
		parent::__construct($registry, $language);
		$this->_request = $request;
		$this->_config = $config;
	}

	/**
	 * stringify the extra
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function __toString() : string
	{
		return $this->render();
	}

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the extra
	 */

	public function init(array $optionArray = [])
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 *
	 * @return string
	 */

	public function render(int $extraId = null) : string
	{
		if ($this->_registry->get('extraReplace'))
		{
			return Module\Hook::trigger('extraReplace');
		}
		$output = Module\Hook::trigger('extraStart');
		$accessValidator = new Validator\Access();
		$extraModel = new Model\Extra();
		$extras = null;
		$contentParser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$adminDock = new Admin\View\Helper\Dock($this->_registry, $this->_language);
		$adminDock->init();
		$language = $this->_registry->get('language');
		$loggedIn = $this->_registry->get('loggedIn');
		$token = $this->_registry->get('token');
		$firstParameter = $this->_registry->get('firstParameter');
		$myGroups = $this->_registry->get('myGroups');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init($this->_optionArray['tag']['title'],
			[
				'class' => $this->_optionArray['className']['title']
			]);
		$boxElement = $element
			->copy()
			->init($this->_optionArray['tag']['box'],
			[
				'class' => $this->_optionArray['className']['box']
			]);

		/* query extras */

		if ($extraId)
		{
			$extras = $extraModel->getByIdAndLanguageAndOrder($extraId, $language, $this->_optionArray['orderColumn']);
		}
		else
		{
			$extras = $extraModel->getByLanguageAndOrder($language, $this->_optionArray['orderColumn']);
		}

		/* process extras */

		foreach ($extras as $value)
		{
			if ($accessValidator->validate($value->access, $myGroups))
			{
				$output .= Module\Hook::trigger('extraFragmentStart', (array)$value);
				if ($value->headline)
				{
					$output .= $titleElement
						->attr('id', 'extra-' . $value->alias)
						->text($value->title);
				}
				$contentParser->process($value->text);
				$output .= $boxElement->html($contentParser->getOutput()) . Module\Hook::trigger('extraFragmentEnd', (array)$value);

				/* admin dock */

				if ($loggedIn === $token && $firstParameter !== 'logout')
				{
					$output .= $adminDock->render('extras', $value->id);
				}
			}
		}
		$output .= Module\Hook::trigger('extraEnd');
		return $output;
	}
}
