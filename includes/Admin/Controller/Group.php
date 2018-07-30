<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Validator;

/**
 * children class to process the admin group request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Group extends ControllerAbstract
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

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => $postArray['id'] ? 'admin/edit/groups/' . $postArray['id'] : 'admin/new/groups',
				'message' => $validateArray
			]);
		}

		/* handle create */

		if ($action === 'create')
		{
			$createArray =
			[
				'name' => $postArray['name'],
				'alias' => $postArray['alias'],
				'description' => $postArray['description'],
				'categories' => $postArray['categories'],
				'articles' => $postArray['articles'],
				'extras' => $postArray['extras'],
				'comments' => $postArray['comments'],
				'groups' => $postArray['groups'],
				'users' => $postArray['users'],
				'modules' => $postArray['modules'],
				'settings' => $postArray['settings'],
				'filter' => $postArray['filter'],
				'status' => $postArray['status']
			];
			if ($this->_create($createArray))
			{
				return $this->_success(
				[
					'route' => 'admin/view/groups#' . $postArray['alias'],
					'timeout' => 2,
					'message' => $this->_language->get('operation_completed')
				]);
			}
		}

		/* handle update */

		if ($action === 'update')
		{
			$updateFullArray =
			[
				'name' => $postArray['name'],
				'description' => $postArray['description'],
				'categories' => $postArray['categories'],
				'articles' => $postArray['articles'],
				'extras' => $postArray['extras'],
				'comments' => $postArray['comments'],
				'groups' => $postArray['groups'],
				'users' => $postArray['users'],
				'modules' => $postArray['modules'],
				'settings' => $postArray['settings'],
				'filter' => $postArray['filter'],
				'status' => $postArray['status']
			];
			$updateLiteArray =
			[
				'name' => $postArray['name'],
				'description' => $postArray['description']
			];
			if ($this->_update($postArray['id'], $postArray['id'] > 1 ? $updateFullArray : $updateLiteArray))
			{
				return $this->_success(
				[
					'route' => 'admin/view/groups#' . $postArray['alias'],
					'timeout' => 2,
					'message' => $this->_language->get('operation_completed')
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => $postArray['id'] ? 'admin/edit/groups/' . $postArray['id'] : 'admin/new/groups',
			'message' => $this->_language->get('something_wrong')
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
		$specialFilter = new Filter\Special();
		$aliasFilter = new Filter\Alias();

		/* sanitize post */

		return
		[
			'id' => $specialFilter->sanitize($this->_request->getPost('id')),
			'name' => $this->_request->getPost('name'),
			'alias' => $aliasFilter->sanitize($this->_request->getPost('alias')),
			'description' => $this->_request->getPost('description'),
			'categories' => $this->_request->getPost('categories'),
			'articles' => $this->_request->getPost('articles'),
			'extras' => $this->_request->getPost('extras'),
			'comments' => $this->_request->getPost('comments'),
			'groups' => $this->_request->getPost('groups'),
			'users' => $this->_request->getPost('users'),
			'modules' => $this->_request->getPost('modules'),
			'settings' => $this->_request->getPost('settings'),
			'filter' => $specialFilter->sanitize($this->_request->getPost('filter')),
			'status' => $specialFilter->sanitize($this->_request->getPost('status'))
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
		$groupModel = new Admin\Model\Group();
		$validateArray = [];

		/* validate post */

		if (!$postArray['name'])
		{
			$validateArray[] = $this->_language->get('name_empty');
		}
		if (!$postArray['id'])
		{
			if (!$postArray['alias'])
			{
				$validateArray[] = $this->_language->get('alias_empty');
			}
			else if ($aliasValidator->validate($postArray['alias'], 'general'))
			{
				$validateArray[] = $this->_language->get('alias_incorrect');
			}
			else if ($groupModel->getByAlias($postArray['alias'])->id !== $groupModel->getById($postArray['id'])->id)
			{
				$validateArray[] = $this->_language->get('alias_exists');
			}
		}
		return $validateArray;
	}

	/**
	 * create the group
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$groupModel = new Admin\Model\Group();
		return $groupModel->createByArray($createArray);
	}

	/**
	 * update the group
	 *
	 * @since 4.0.0
	 *
	 * @param int $groupId identifier of the group
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(int $groupId = null, array $updateArray = []) : bool
	{
		$groupModel = new Admin\Model\Group();
		return $groupModel->updateByIdAndArray($groupId, $updateArray);
	}
}
