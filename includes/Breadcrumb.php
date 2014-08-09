<?php
namespace Redaxscript;
use Redaxscript\Validator;
use Redaxscript_Validator_Interface;

/**
 * parent class to provide a location based breadcrumb navigation
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Breadcrumb
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class Breadcrumb
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * array to store all the nodes of the breadcrumb
	 *
	 * @var array
	 */

	protected $_breadcrumbArray = array();

	/**
	 * options of the breadcrumb
	 *
	 * @var array
	 */

	protected $_options = array(
		'className' => array(
			'list' => 'list-breadcrumb',
			'divider' => 'item-divider'
		)
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.1.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 * @param array $options options of the breadcrumb
	 */

	public function __construct(Registry $registry, Language $language, $options = null)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		if (is_array($options))
		{
			$this->_options = array_unique(array_merge($this->_options, $options));
		}
		$this->init();
	}

	/**
	 * init the class
	 *
	 * @since 2.1.0
	 */

	public function init()
	{
		$this->_build();
	}

	/**
	 * get the breadcrumb array for further processing
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function get()
	{
		return $this->_breadcrumbArray;
	}

	/**
	 * render the breadcrumb trail as an unordered list
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = Hook::trigger('breadcrumb_start');

		/* breadcrumb keys */

		$breadcrumbKeys = array_keys($this->_breadcrumbArray);
		$last = end($breadcrumbKeys);

		/* collect item output */

		foreach ($this->_breadcrumbArray as $key => $value)
		{
			$title = array_key_exists('title', $value) ? $value['title'] : null;
			$route = array_key_exists('route', $value) ? $value['route'] : null;
			if ($title)
			{
				$output .= '<li>';

				/* build link if route */

				if ($route)
				{
					$output .= '<a href="' . $this->_registry->get('rewriteRoute') . $route . '" title="' . $title . '">' . $title . '</a>';
				}

				/* else plain text */

				else
				{
					$output .= $title;
				}
				$output .= '</li>';

				/* add divider */

				if ($last !== $key)
				{
					$output .= '<li class="' . $this->_options['className']['divider'] . '">' . Db::getSettings('divider') . '</li>';
				}
			}
		}

		/* collect list output */

		if ($output)
		{
			$output = '<ul class="' . $this->_options['className']['list'] . '">' . $output . '</ul>';
		}
		$output .= Hook::trigger('breadcrumb_end');
		return $output;
	}

	/**
	 * build the breadcrumb array
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key key of the item
	 */

	private function _build($key = 0)
	{
		$aliasValidator = new Validator\Alias();

		/* if title constant */

		if ($this->_registry->get('title'))
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_registry->get('title');
		}

		/* else if home */

		else if (!$this->_registry->get('fullRoute'))
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('home');
		}

		/* else if administration */

		else if ($this->_registry->get('firstParameter') === 'admin')
		{
			$this->_buildAdmin($key);
		}

		/* else if default alias */

		else if ($aliasValidator->validate($this->_registry->get('firstParameter'), Validator\Alias::ALIAS_MODE_DEFAULT) === Redaxscript_Validator_Interface::VALIDATION_OK)
		{
			/* join default title */

			if ($this->_registry->get('firstParameter') && $this->_language->get($this->_registry->get('firstParameter')))
			{
				$this->_breadcrumbArray[$key]['title'] = $this->_language->get($this->_registry->get('firstParameter'));
			}
		}

		/* handle error */

		else if (!$this->_registry->get('lastId'))
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('error');
		}

		/* query title from content */

		else if ($this->_registry->get('firstTable'))
		{
			$this->_buildContent($key);
		}
	}

	/**
	 * build the breadcrumb array for current administration
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key key of the item
	 */

	private function _buildAdmin($key = 0)
	{
		$this->_breadcrumbArray[$key]['title'] = $this->_language->get('administration');

		/* if admin parameter  */

		if ($this->_registry->get('adminParameter'))
		{
			$this->_breadcrumbArray[$key]['route'] = 'admin';
		}

		/* join admin title */

		if ($this->_registry->get('adminParameter') && $this->_language->get($this->_registry->get('adminParameter')))
		{
			$key++;
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get($this->_registry->get('adminParameter'));

			/* set route if not end */

			if ($this->_registry->get('adminParameter') !== $this->_registry->get('lastParameter'))
			{
				$this->_breadcrumbArray[$key]['route'] = $this->_registry->get('fullRoute');
			}

			/* join table title */

			if ($this->_registry->get('tableParameter') && $this->_language->get($this->_registry->get('tableParameter')))
			{
				$key++;
				$this->_breadcrumbArray[$key]['title'] = $this->_language->get($this->_registry->get('tableParameter'));
			}
		}
	}

	/**
	 * build the breadcrumb array for current content
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key
	 */

	private function _buildContent($key = 0)
	{
		/* join first title */

		$this->_breadcrumbArray[$key]['title'] = Db::forPrefixTable($this->_registry->get('firstTable'))->where('alias', $this->_registry->get('firstParameter'))->findOne()->title;

		/* set route if not end */

		if ($this->_registry->get('firstParameter') !== $this->_registry->get('lastParameter'))
		{
			$this->_breadcrumbArray[$key]['route'] = $this->_registry->get('firstParameter');
		}

		/* join second title */

		if ($this->_registry->get('secondTable'))
		{
			$key++;
			$this->_breadcrumbArray[$key]['title'] = Db::forPrefixTable($this->_registry->get('secondTable'))->where('alias', $this->_registry->get('secondParameter'))->findOne()->title;

			/* set route if not end */

			if ($this->_registry->get('secondParameter') !== $this->_registry->get('lastParameter'))
			{
				$this->_breadcrumbArray[$key]['route'] = $this->_registry->get('firstParameter') . '/' . $this->_registry->get('secondParameter');
			}

			/* join third title */

			if ($this->_registry->get('thirdTable'))
			{
				$key++;
				$this->_breadcrumbArray[$key]['title'] = Db::forPrefixTable($this->_registry->get('thirdTable'))->where('alias', $this->_registry->get('thirdParameter'))->findOne()->title;
			}
		}
	}
}