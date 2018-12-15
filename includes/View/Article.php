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
use Redaxscript\Template;
use Redaxscript\Validator;
use function array_replace_recursive;

/**
 * children class to create the article
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Article extends ViewAbstract
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
	 * options of the article
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'tag' =>
		[
			'title' => 'h2',
			'box' => 'div'
		],
		'className' =>
		[
			'title' => 'rs-title-content',
			'box' => 'rs-box-content'
		],
		'orderColumn' => 'rank',
		'partial' =>
		[
			'error' => 'error.phtml'
		]
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
	 * stringify the article
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
	 * @param array $optionArray options of the article
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
	 * @param int $categoryId identifier of the category
	 * @param int $articleId identifier of the article
	 *
	 * @return string
	 */

	public function render(int $categoryId = null, int $articleId = null) : string
	{
		if ($this->_registry->get('articleReplace'))
		{
			return Module\Hook::trigger('articleReplace');
		}
		$output = Module\Hook::trigger('articleStart');
		$outputFragment = null;
		$accessValidator = new Validator\Access();
		$settingModel = new Model\Setting();
		$articleModel = new Model\Article();
		$articles = null;
		$contentParser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$byline = new Helper\Byline($this->_registry, $this->_language);
		$byline->init();
		$adminDock = new Admin\View\Helper\Dock($this->_registry, $this->_language);
		$adminDock->init();
		$language = $this->_registry->get('language');
		$loggedIn = $this->_registry->get('loggedIn');
		$token = $this->_registry->get('token');
		$firstParameter = $this->_registry->get('firstParameter');
		$lastSubParameter = $this->_registry->get('lastSubParameter');
		$lastTable = $this->_registry->get('lastTable');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$myGroups = $this->_registry->get('myGroups');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init($this->_optionArray['tag']['title'],
			[
				'class' => $this->_optionArray['className']['title']
			]);
		$linkElement = $element->copy()->init('a');
		$boxElement = $element
			->copy()
			->init($this->_optionArray['tag']['box'],
			[
				'class' => $this->_optionArray['className']['box']
			]);

		/* query articles */

		if ($categoryId)
		{
			if ($settingModel->get('pagination'))
			{
				$articles = $articleModel->getByCategoryAndLanguageAndOrderAndStep($categoryId, $language, $this->_optionArray['orderColumn'], $lastSubParameter - 1);
			}
			else
			{
				$articles = $articleModel->getByCategoryAndLanguageAndOrder($categoryId, $language, $this->_optionArray['orderColumn']);
			}
		}
		else if ($articleId)
		{
			$articles = $articleModel->getByIdAndLanguageAndOrder($articleId, $language, $this->_optionArray['orderColumn']);
		}

		/* process articles */

		foreach ($articles as $value)
		{
			if ($accessValidator->validate($value->access, $myGroups))
			{
				$outputFragment .= Module\Hook::trigger('articleFragmentStart', (array)$value);
				if ($value->headline)
				{
					$outputFragment .= $titleElement
						->attr('id', 'article-' . $value->alias)
						->html($lastTable === 'categories' ? $linkElement
							->attr('href', $parameterRoute . $articleModel->getRouteById($value->id))
							->text($value->title) : $value->title
						);
				}
				$contentParser->process($value->text);
				$outputFragment .= $boxElement->html($contentParser->getOutput()) . $byline->render($value->date, $value->author) . Module\Hook::trigger('articleFragmentEnd', (array)$value);

				/* admin dock */

				if ($loggedIn === $token && $firstParameter !== 'logout')
				{
					$outputFragment .= $adminDock->render('articles', $value->id);
				}
			}
		}

		/* collect output */

		$output .= $outputFragment ? : Template\Tag::partial($this->_registry->get('template') . '/' . $this->_optionArray['partial']['error']);
		$output .= Module\Hook::trigger('articleEnd');
		return $output;
	}
}
