<?php
namespace Redaxscript\Template;

use Redaxscript\Admin;
use Redaxscript\Config;
use Redaxscript\Console;
use Redaxscript\Filesystem;
use Redaxscript\Head;
use Redaxscript\Language;
use Redaxscript\Model;
use Redaxscript\Navigation;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Router;
use Redaxscript\View;
use function ceil;
use function htmlentities;
use function strlen;

/**
 * parent class to provide template tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Tag
{
	/**
	 * base
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function base() : string
	{
		$base = new Head\Base(Registry::getInstance());
		return $base->render();
	}

	/**
	 * title
	 *
	 * @since 3.0.0
	 *
	 * @param string $text
	 *
	 * @return string|null
	 */

	public static function title(string $text = null) : ?string
	{
		$title = new Head\Title();
		return $title->render($text);
	}

	/**
	 * link
	 *
	 * @since 3.0.0
	 *
	 * @return Head\Link
	 */

	public static function link() : Head\Link
	{
		return Head\Link::getInstance();
	}

	/**
	 * meta
	 *
	 * @since 3.0.0
	 *
	 * @return Head\Meta
	 */

	public static function meta() : Head\Meta
	{
		return Head\Meta::getInstance();
	}

	/**
	 * script
	 *
	 * @since 3.0.0
	 *
	 * @return Head\Script
	 */

	public static function script() : Head\Script
	{
		return Head\Script::getInstance();
	}

	/**
	 * style
	 *
	 * @since 3.0.0
	 *
	 * @return Head\Style
	 */

	public static function style() : Head\Style
	{
		return Head\Style::getInstance();
	}

	/**
	 * breadcrumb
	 *
	 * @since 2.3.0
	 *
	 * @param array $optionArray options of the breadcrumb
	 *
	 * @return string
	 */

	public static function breadcrumb(array $optionArray = []) : string
	{
		$breadcrumb = new View\Helper\Breadcrumb(Registry::getInstance(), Language::getInstance());
		return $breadcrumb->init($optionArray)->render();
	}

	/**
	 * partial
	 *
	 * @since 3.2.0
	 *
	 * @param string|array $partial
	 *
	 * @return string|null
	 */

	public static function partial($partial = null) : ?string
	{
		$output = null;

		/* template filesystem */

		$templateFilesystem = new Filesystem\File();
		$templateFilesystem->init('templates');

		/* process partial */

		foreach ((array)$partial as $file)
		{
			$output .= $templateFilesystem->renderFile($file);
		}
		return $output;
	}

	/**
	 * content
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	public static function content() : ?string
	{
		$adminContent = self::_renderAdminContent();
		return $adminContent ? : self::_renderContent();
	}

	/**
	 * render the admin content
	 *
	 * @since 3.3.0
	 *
	 * @return string|null
	 */

	protected static function _renderAdminContent() : ?string
	{
		$registry = Registry::getInstance();
		if ($registry->get('token') === $registry->get('loggedIn'))
		{
			$adminRouter = new Admin\Router\Router(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
			$adminRouter->init();
			return $adminRouter->routeContent();
		}
		return null;
	}

	/**
	 * render the content
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	protected static function _renderContent() : ?string
	{
		$router = new Router\Router(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		$router->init();
		return $router->routeContent();
	}

	/**
	 * article
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 * @param int $articleId identifier of the article
	 * @param array $optionArray options of the content
	 *
	 * @return string
	 */

	public static function article(int $categoryId = null, int $articleId = null, array $optionArray = []) : string
	{
		$article = new View\Article(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		return $article->init($optionArray)->render($categoryId, $articleId);
	}

	/**
	 * comment
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param array $optionArray options of the comment
	 *
	 * @return string|null
	 */

	public static function comment(int $articleId = null, array $optionArray = []) : ?string
	{
		$articleModel = new Model\Article();
		$article = $articleModel->getById($articleId);
		if ($article->comments)
		{
			$comment = new View\Comment(Registry::getInstance(), Language::getInstance());
			return $comment->init($optionArray)->render($articleId);
		}
		return null;
	}

	/**
	 * extra
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 * @param array $optionArray options of the extra
	 *
	 * @return string
	 */

	public static function extra(int $extraId = null, array $optionArray = []) : string
	{
		$extra = new View\Extra(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		return $extra->init($optionArray)->render($extraId);
	}

	/**
	 * pagination
	 *
	 * @since 4.0.0
	 *
	 * @param string $type type of the pagination
	 * @param int $parentId identifier of the parent
	 * @param array $optionArray options of the pagination
	 *
	 * @return string|null
	 */

	public static function pagination(string $type = null, int $parentId, array $optionArray = []) : ?string
	{
		$settingModel = new Model\Setting();
		if ($settingModel->get('pagination'))
		{
			$categoryModel = new Model\Category();
			$articleModel = new Model\Article();
			$commentModel = new Model\Comment();
			$registry = Registry::getInstance();
			$language = $registry->get('language');
			$route = null;
			$total = null;
			$current = $registry->get('lastSubParameter') ? : 1;
			if ($type === 'articles')
			{
				$route = $categoryModel->getRouteById($parentId);
				$total = ceil($articleModel->countByCategoryAndLanguage($parentId, $language) / $settingModel->get('limit'));
			}
			if ($type === 'comments')
			{
				$route = $articleModel->getRouteById($parentId);
				$total = ceil($commentModel->countByArticleAndLanguage($parentId, $language) / $settingModel->get('limit'));
			}
			if ($total > 1)
			{
				$pagination = new View\Helper\Pagination(Registry::getInstance(), Language::getInstance());
				return $pagination->init($optionArray)->render($route, $current, $total);
			}
		}
		return null;
	}

	/**
	 * navigation
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the navigation
	 * @param array $optionArray options of the navigation
	 *
	 * @return string|null
	 */

	public static function navigation(string $type = null, array $optionArray = []) : ?string
	{
		if ($type === 'articles')
		{
			$navigation = new Navigation\Article(Registry::getInstance(), Language::getInstance());
			return $navigation->init($optionArray)->render();
		}
		if ($type === 'categories')
		{
			$navigation = new Navigation\Category(Registry::getInstance(), Language::getInstance());
			return $navigation->init($optionArray)->render();
		}
		if ($type === 'comments')
		{
			$navigation = new Navigation\Comment(Registry::getInstance(), Language::getInstance());
			return $navigation->init($optionArray)->render();
		}
		if ($type === 'languages')
		{
			$navigation = new Navigation\Language(Registry::getInstance(), Language::getInstance());
			return $navigation->init($optionArray)->render();
		}
		if ($type === 'templates')
		{
			$navigation = new Navigation\Template(Registry::getInstance(), Language::getInstance());
			return $navigation->init($optionArray)->render();
		}
		return null;
	}

	/**
	 * console
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public static function console() : ?string
	{
		$console = new Console\Console(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		$output = $console->init('template');
		if (strlen($output))
		{
			return htmlentities($output, ENT_QUOTES);
		}
		return null;
	}

	/**
	 * console form
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public static function consoleForm() : string
	{
		$consoleForm = new View\ConsoleForm(Registry::getInstance(), Language::getInstance());
		return $consoleForm->render();
	}

	/**
	 * comment form
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return string
	 */

	public static function commentForm(int $articleId = null) : ?string
	{
		$articleModel = new Model\Article();
		$article = $articleModel->getById($articleId);
		if ($article->comments)
		{
			$commentForm = new View\CommentForm(Registry::getInstance(), Language::getInstance());
			return $commentForm->render($articleId);
		}
		return null;
	}

	/**
	 * search form
	 *
	 * @since 3.0.0
	 *
	 * @param string $table name of the table
	 *
	 * @return string
	 */

	public static function searchForm(string $table = null) : string
	{
		$searchForm = new View\SearchForm(Registry::getInstance(), Language::getInstance());
		return $searchForm->render($table);
	}
}
