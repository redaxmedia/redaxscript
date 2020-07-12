<?php
namespace Redaxscript\Modules\Reporter;

use Redaxscript\Filesystem;
use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;

/**
 * report metrics on the dashboard
 *
 * @since 4.1.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Reporter extends Module\Metadata
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Reporter',
		'alias' => 'Reporter',
		'author' => 'Redaxmedia',
		'description' => 'Report metrics on the dashboard',
		'version' => '4.3.1'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'dashboard' =>
			[
				'title' => 'rs-admin-title-dashboard',
				'text' => 'rs-admin-text-dashboard'
			]
		]
	];

	/**
	 * adminDashboard
	 *
	 * @since 4.1.0
	 *
	 * @return array
	 */

	public function adminDashboard() : array
	{
		$this->setDashboard($this->_renderDashboard('categories'));
		$this->setDashboard($this->_renderDashboard('articles'));
		$this->setDashboard($this->_renderDashboard('extras'));
		$this->setDashboard($this->_renderDashboard('comments'));
		$this->setDashboard($this->_renderDashboard('users'));
		$this->setDashboard($this->_renderDashboard('groups'));
		$this->setDashboard($this->_renderDashboard('modules'));
		$this->setDashboard($this->_renderDashboard('languages'));
		$this->setDashboard($this->_renderDashboard('templates'));
		return $this->getDashboardArray();
	}

	/**
	 * renderDashboard
	 *
	 * @since 4.1.0
	 *
	 * @param string type
	 * @return string
	 */

	protected function _renderDashboard(string $type = null) : string
	{
		$categoryModel = new Model\Category();
		$articleModel = new Model\Article();
		$extraModel = new Model\Extra();
		$commentModel = new Model\Comment();
		$userModel = new Model\User();
		$groupModel = new Model\Group();
		$moduleModel = new Model\Module();
		$filesystem = new Filesystem\Filesystem();
		$divider = '/';

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h3',
			[
				'class' => $this->_optionArray['className']['dashboard']['title']
			]);
		$textElement = $element
			->copy()
			->init('span',
			[
				'class' => $this->_optionArray['className']['dashboard']['text']
			]);

		/* handle type */

		if ($type === 'categories')
		{
			$titleElement->text($categoryModel->query()->where('status', 1)->count() . $divider . $categoryModel->query()->count());
			$textElement->text($this->_language->get('_reporter')['categories_publish']);
		}
		if ($type === 'articles')
		{
			$titleElement->text($articleModel->query()->where('status', 1)->count() . $divider . $articleModel->query()->count());
			$textElement->text($this->_language->get('_reporter')['articles_publish']);
		}
		if ($type === 'extras')
		{
			$titleElement->text($extraModel->query()->where('status', 1)->count() . $divider . $extraModel->query()->count());
			$textElement->text($this->_language->get('_reporter')['extras_publish']);
		}
		if ($type === 'comments')
		{
			$titleElement->text($commentModel->query()->where('status', 1)->count() . $divider . $commentModel->query()->count());
			$textElement->text($this->_language->get('_reporter')['comments_publish']);
		}
		if ($type === 'users')
		{
			$titleElement->text($userModel->query()->where('status', 1)->count() . $divider . $userModel->query()->count());
			$textElement->text($this->_language->get('_reporter')['users_enable']);
		}
		if ($type === 'groups')
		{
			$titleElement->text($groupModel->query()->where('status', 1)->count() . $divider . $groupModel->query()->count());
			$textElement->text($this->_language->get('_reporter')['groups_enable']);
		}
		if ($type === 'modules')
		{
			$titleElement->text($moduleModel->query()->count() . $divider . $filesystem->init('modules')->countIterator());
			$textElement->text($this->_language->get('_reporter')['modules_install']);
		}
		if ($type === 'languages')
		{
			$titleElement->text($filesystem->init('languages')->countIterator());
			$textElement->text($this->_language->get('_reporter')['languages_install']);
		}
		if ($type === 'templates')
		{
			$titleElement->text($filesystem->init('templates', false,
			[
				'admin',
				'console',
				'install'
			])->countIterator());
			$textElement->text($this->_language->get('_reporter')['templates_install']);
		}
		return $titleElement . $textElement;
	}
}
