<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Auth;
use Redaxscript\Filter;
use Redaxscript\Hash;
use Redaxscript\Validator;
use function json_encode;

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
		$passwordHash = new Hash();
		$myId = (int)$this->_registry->get('myId');

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
			$passwordHash->init($postArray['password']);
			$createArray =
			[
				'name' => $postArray['name'],
				'user' => $postArray['user'],
				'description' => $postArray['description'],
				'password' => $passwordHash->getHash(),
				'email' => $postArray['email'],
				'language' => $postArray['language'],
				'status' => $postArray['status'],
				'groups' => $postArray['groups']
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
			$updateFullArray =
			[
				'name' => $postArray['name'],
				'description' => $postArray['description'],
				'email' => $postArray['email'],
				'language' => $postArray['language'],
				'status' => $postArray['status'],
				'groups' => $postArray['groups']
			];
			$updateLiteArray =
			[
				'name' => $postArray['name'],
				'description' => $postArray['description'],
				'email' => $postArray['email'],
				'language' => $postArray['language']
			];
			if ($postArray['password'])
			{
				$passwordHash->init($postArray['password']);
				$updateFullArray['password'] = $updateLiteArray['password'] = $passwordHash->getHash();
			}
			if ($this->_update($postArray['id'], $postArray['id'] > 1 ? $updateFullArray : $updateLiteArray))
			{
				if ($postArray['id'] === $myId)
				{
					$this->_refresh($postArray);
				}
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
		$emailFilter = new Filter\Email();

		/* sanitize post */

		return
		[
			'id' => $numberFilter->sanitize($this->_request->getPost('id')),
			'name' => $this->_request->getPost('name'),
			'user' => $this->_request->getPost('user'),
			'description' => $this->_request->getPost('description'),
			'password' => $this->_request->getPost('password'),
			'password_confirm' => $this->_request->getPost('password_confirm'),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'language' => $specialFilter->sanitize($this->_request->getPost('language')),
			'status' => $numberFilter->sanitize($this->_request->getPost('status')),
			'groups' => json_encode($this->_request->getPost('groups'))
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
			else if (!$loginValidator->validate($postArray['user']))
			{
				$validateArray[] = $this->_language->get('user_incorrect');
			}
			else if ($userModel->getByUser($postArray['user']))
			{
				$validateArray[] = $this->_language->get('user_exists');
			}
			if (!$postArray['password'])
			{
				$validateArray[] = $this->_language->get('password_empty');
			}
			else if (!$loginValidator->validate($postArray['password']))
			{
				$validateArray[] = $this->_language->get('password_incorrect');
			}
			else if ($postArray['password'] !== $postArray['password_confirm'])
			{
				$validateArray[] = $this->_language->get('password_mismatch');
			}
		}
		else if ($postArray['password'])
		{
			if (!$loginValidator->validate($postArray['password']))
			{
				$validateArray[] = $this->_language->get('password_incorrect');
			}
			else if ($postArray['password'] !== $postArray['password_confirm'])
			{
				$validateArray[] = $this->_language->get('password_mismatch');
			}
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
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	public function _update(int $userId = null, array $updateArray = []) : bool
	{
		$userModel = new Admin\Model\User();
		return $userModel->updateByIdAndArray($userId, $updateArray);
	}

	/**
	 * refresh the auth
	 *
	 * @since 4.0.0
	 *
	 * @param array $refreshArray array of the update
	 */

	public function _refresh(array $refreshArray = [])
	{
		$auth = new Auth($this->_request);
		$auth->init();
		$auth->setUser('name', $refreshArray['name']);
		$auth->setUser('email', $refreshArray['email']);
		$auth->setUser('language', $refreshArray['language']);
		$auth->save();
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
		if ($this->_registry->get('usersEdit') && $postArray['id'])
		{
			return 'admin/view/users#row-' . $postArray['id'];
		}
		if ($this->_registry->get('usersEdit') && $postArray['user'])
		{
			$userModel = new Admin\Model\User();
			return 'admin/view/users#row-' . $userModel->getByUser($postArray['user'])->id;
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
		if ($this->_registry->get('usersEdit') && $postArray['id'])
		{
			return 'admin/edit/users/' . $postArray['id'];
		}
		if ($this->_registry->get('usersNew'))
		{
			return 'admin/new/users';
		}
		return 'admin';
	}
}
