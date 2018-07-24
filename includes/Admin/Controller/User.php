<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Validator;

/**
 * children class to process the admin user request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class User extends ControllerAbstract
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
				'route' => $postArray['id'] ? 'admin/edit/users/' . $postArray['id'] : 'admin/new/users',
				'message' => $validateArray
			]);
		}

		/* handle create */

		if ($action === 'create')
		{
			$createArray =
			[
				'name' => $postArray['name'],
				'user' => $postArray['user'],
				'description' => $postArray['description'],
				'password' => $postArray['password'],
				'email' => $postArray['email'],
				'language' => $postArray['language'],
				'status' => $postArray['status'],
				'groups' => $postArray['groups']
			];
			if ($this->_create($createArray))
			{
				return $this->_success(
				[
					'route' => 'admin/view/users#' . $postArray['user'],
					'timeout' => 2,
					'message' => $this->_language->get('operation_completed')
				]);
			}
		}

		/* handle update */

		if ($action === 'update')
		{
			$updateArray =
			[
				'name' => $postArray['name'],
				'user' => $postArray['user'],
				'description' => $postArray['description'],
				'password' => $postArray['password'],
				'email' => $postArray['email'],
				'language' => $postArray['language'],
				'status' => $postArray['status'],
				'groups' => $postArray['groups']
			];
			if ($this->_update($postArray['id'], $updateArray))
			{
				return $this->_success(
				[
					'route' => 'admin/view/users#' . $postArray['user'],
					'timeout' => 2,
					'message' => $this->_language->get('operation_completed')
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => $postArray['id'] ? 'admin/edit/users/' . $postArray['id'] : 'admin/new/users',
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
		$emailFilter = new Filter\Email();

		/* sanitize post */

		return
		[
			'id' => $specialFilter->sanitize($this->_request->getPost('id')),
			'name' => $this->_request->getPost('name'),
			'user' => $this->_request->getPost('user'),
			'description' => $this->_request->getPost('description'),
			'password' => $this->_request->getPost('password'),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'language' => $specialFilter->sanitize($this->_request->getPost('language')),
			'status' => $specialFilter->sanitize($this->_request->getPost('status')),
			'groups' => $this->_request->getPost('groups')
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
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();
		$userModel = new Admin\Model\User();
		$validateArray = [];

		/* validate post */

		if (!$postArray['name'])
		{
			$validateArray[] = $this->_language->get('name_empty');
		}
		if (!$postArray['id'])
        {
			if (!$postArray['user'])
			{
				$validateArray[] = $this->_language->get('user_empty');
			}
			else if ($loginValidator->validate($postArray['user']))
			{
				$validateArray[] = $this->_language->get('user_incorrect');
			}
			else if ($userModel->getByUser($postArray['user'])->id !== $userModel->getById($postArray['id'])->id)
			{
				$validateArray[] = $this->_language->get('user_exists');
			}
		}
		if (!$postArray['password'])
		{
			$validateArray[] = $this->_language->get('password_empty');
		}
		else if (!$loginValidator->validate($postArray['password']))
		{
			$validateArray[] = $this->_language->get('password_incorrect');
		}
		if (!$emailValidator->validate($postArray['email']))
		{
			$validateArray[] = $this->_language->get('email_incorrect');
		}
		return $validateArray;
	}

	/**
	 * create the user
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$userModel = new Admin\Model\User();
		return $userModel->createByArray($createArray);
	}

	/**
	 * update the user
	 *
	 * @since 4.0.0
	 *
	 * @param int $userId identifier of the user
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(int $userId = null, array $updateArray = []) : bool
	{
		$userModel = new Admin\Model\User();
		return $userModel->updateByIdAndArray($userId, $updateArray);
	}
}
