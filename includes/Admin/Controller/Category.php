<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Validator;

/**
 * children class to process the admin category request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Category extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 4.0.0
	 *
	 * @param string $action action to process
	 *
	 * @return string
	 */

	public function process(string $action = null) : string
	{
		$postArray = $this->_normalizePost($this->_sanitizePost());
		$validateArray = $this->_validatePost($postArray);
		$myUser = $this->_registry->get('myUser');
		$now = $this->_registry->get('now');

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => $this->_getErrorRoute($postArray),
				'message' => $validateArray
			]);
		}

		/* handle create */

		if ($action === 'create')
		{
			$createArray =
			[
				'title' => $postArray['title'],
				'alias' => $postArray['alias'],
				'author' => $myUser,
				'description' => $postArray['description'],
				'keywords' => $postArray['keywords'],
				'robots' => $postArray['robots'],
				'language' => $postArray['language'],
				'template' => $postArray['template'],
				'sibling' => $postArray['sibling'],
				'parent' => $postArray['parent'],
				'status' => $postArray['date'] > $now ? 2 : $postArray['status'],
				'rank' => $postArray['rank'],
				'access' => $postArray['access'],
				'date' => $postArray['date'] ? $postArray['date'] : $now
			];
			if ($this->_create($createArray))
			{
				return $this->_success(
				[
					'route' => $this->_getSuccessRoute($postArray),
					'timeout' => 2
				]);
			}
		}

		/* handle update */

		if ($action === 'update')
		{
			$updateArray =
			[
				'title' => $postArray['title'],
				'alias' => $postArray['alias'],
				'author' => $myUser,
				'description' => $postArray['description'],
				'keywords' => $postArray['keywords'],
				'robots' => $postArray['robots'],
				'language' => $postArray['language'],
				'template' => $postArray['template'],
				'sibling' => $postArray['sibling'],
				'parent' => $postArray['parent'],
				'status' => $postArray['date'] > $now ? 2 : $postArray['status'],
				'rank' => $postArray['rank'],
				'access' => $postArray['access'],
				'date' => $postArray['date'] ? $postArray['date'] : $now
			];
			if ($this->_update($postArray['id'], $updateArray))
			{
				return $this->_success(
				[
					'route' => $this->_getSuccessRoute($postArray),
					'timeout' => 2
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => $this->_getErrorRoute($postArray)
		]);
	}

	/**
	 * sanitize the post
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizePost() : array
	{
		$numberFilter = new Filter\Number();
		$specialFilter = new Filter\Special();
		$aliasFilter = new Filter\Alias();

		/* sanitize post */

		return
		[
			'id' => $numberFilter->sanitize($this->_request->getPost('id')),
			'title' => $this->_request->getPost('title'),
			'alias' => $aliasFilter->sanitize($this->_request->getPost('alias')),
			'description' => $this->_request->getPost('description'),
			'keywords' => $this->_request->getPost('keywords'),
			'robots' => $numberFilter->sanitize($this->_request->getPost('robots')),
			'language' => $specialFilter->sanitize($this->_request->getPost('language')),
			'template' => $specialFilter->sanitize($this->_request->getPost('template')),
			'sibling' => $numberFilter->sanitize($this->_request->getPost('sibling')),
			'parent' => $numberFilter->sanitize($this->_request->getPost('parent')),
			'status' => $numberFilter->sanitize($this->_request->getPost('status')),
			'rank' => $numberFilter->sanitize($this->_request->getPost('rank')),
			'access' => json_encode($this->_request->getPost('access')),
			'date' => strtotime($this->_request->getPost('date'))
		];
	}

	/**
	 * validate the post
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validatePost(array $postArray = []) : array
	{
		$aliasValidator = new Validator\Alias();
		$categoryModel = new Admin\Model\Category();
		$validateArray = [];

		/* validate post */

		if (!$postArray['title'])
		{
			$validateArray[] = $this->_language->get('title_empty');
		}
		if (!$postArray['alias'])
		{
			$validateArray[] = $this->_language->get('alias_empty');
		}
		else if ($aliasValidator->validate($postArray['alias'], 'general') || $aliasValidator->validate($postArray['alias'], 'system'))
		{
			$validateArray[] = $this->_language->get('alias_incorrect');
		}
		else if (!$categoryModel->isUniqueByIdAndAlias($postArray['id'], $postArray['alias']))
		{
			$validateArray[] = $this->_language->get('alias_exists');
		}
		return $validateArray;
	}

	/**
	 * create the category
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$categoryModel = new Admin\Model\Category();
		return $categoryModel->createByArray($createArray);
	}

	/**
	 * update the category
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	public function _update(int $categoryId = null, array $updateArray = []) : bool
	{
		$categoryModel = new Admin\Model\Category();
		return $categoryModel->updateByIdAndArray($categoryId, $updateArray);
	}

	/**
	 * get success route
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return string
	 */

	protected function _getSuccessRoute(array $postArray = []) : string
	{
		if ($this->_registry->get('categoriesEdit') && $postArray['id'])
		{
			return 'admin/view/categories#' . $postArray['id'];
		}
		if ($this->_registry->get('categoriesEdit') && $postArray['alias'])
		{
			$categoryModel = new Admin\Model\Category();
			return 'admin/view/categories#' . $categoryModel->getByAlias($postArray['alias'])->id;
		}
		return 'admin';
	}

	/**
	 * get error route
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return string
	 */

	protected function _getErrorRoute(array $postArray = []) : string
	{
		if ($this->_registry->get('categoriesEdit') && $postArray['id'])
		{
			return 'admin/edit/categories/' . $postArray['id'];
		}
		if ($this->_registry->get('categoriesNew'))
		{
			return 'admin/new/categories';
		}
		return 'admin';
	}
}
