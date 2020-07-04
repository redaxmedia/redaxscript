<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Validator;

/**
 * children class to process the admin setting request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Setting extends ControllerAbstract
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
				'route' => $this->_getErrorRoute(),
				'message' => $validateArray
			]);
		}

		/* handle update */

		if ($action === 'update')
		{
			$updateArray =
			[
				'language' => $postArray['language'],
				'template' => $postArray['template'],
				'title' => $postArray['title'],
				'author' => $postArray['author'],
				'copyright' => $postArray['copyright'],
				'description' => $postArray['description'],
				'keywords' => $postArray['keywords'],
				'robots' => $postArray['robots'],
				'email' => $postArray['email'],
				'subject' => $postArray['subject'],
				'notification' => $postArray['notification'],
				'charset' => $postArray['charset'],
				'divider' => $postArray['divider'],
				'zone' => $postArray['zone'],
				'time' => $postArray['time'],
				'date' => $postArray['date'],
				'homepage' => $postArray['homepage'],
				'limit' => $postArray['limit'],
				'order' => $postArray['order'],
				'pagination' => $postArray['pagination'],
				'moderation' => $postArray['moderation'],
				'registration' => $postArray['registration'],
				'verification' => $postArray['verification'],
				'recovery' => $postArray['recovery'],
				'captcha' => $postArray['captcha']
			];
			if ($this->_update($updateArray))
			{
				return $this->_success(
				[
					'route' => 'admin',
					'timeout' => 2
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => $this->_getErrorRoute()
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
		$emailFilter = new Filter\Email();
		$numberFilter = new Filter\Number();
		$specialFilter = new Filter\Special();
		$textFilter = new Filter\Text();
		$toggleFilter = new Filter\Toggle();

		/* sanitize post */

		return
		[
			'language' => $specialFilter->sanitize($this->_request->getPost('language')),
			'template' => $specialFilter->sanitize($this->_request->getPost('template')),
			'title' => $textFilter->sanitize($this->_request->getPost('title')),
			'author' => $textFilter->sanitize($this->_request->getPost('author')),
			'copyright' => $textFilter->sanitize($this->_request->getPost('copyright')),
			'description' => $textFilter->sanitize($this->_request->getPost('description')),
			'keywords' => $textFilter->sanitize($this->_request->getPost('keywords')),
			'robots' => $numberFilter->sanitize($this->_request->getPost('robots')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'subject' => $textFilter->sanitize($this->_request->getPost('subject')),
			'notification' => $toggleFilter->sanitize($this->_request->getPost('notification')),
			'charset' => $textFilter->sanitize($this->_request->getPost('charset')),
			'divider' => $textFilter->sanitize($this->_request->getPost('divider')),
			'zone' => $textFilter->sanitize($this->_request->getPost('zone')),
			'time' => $textFilter->sanitize($this->_request->getPost('time')),
			'date' => $textFilter->sanitize($this->_request->getPost('date')),
			'homepage' => $numberFilter->sanitize($this->_request->getPost('homepage')),
			'limit' => $numberFilter->sanitize($this->_request->getPost('limit')),
			'order' => $specialFilter->sanitize($this->_request->getPost('order')),
			'pagination' => $toggleFilter->sanitize($this->_request->getPost('pagination')),
			'moderation' => $toggleFilter->sanitize($this->_request->getPost('moderation')),
			'registration' => $toggleFilter->sanitize($this->_request->getPost('registration')),
			'verification' => $toggleFilter->sanitize($this->_request->getPost('verification')),
			'recovery' => $toggleFilter->sanitize($this->_request->getPost('recovery')),
			'captcha' => $numberFilter->sanitize($this->_request->getPost('captcha'))
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
		$nameValidator = new Validator\Name();
		$userValidator = new Validator\User();
		$validateArray = [];

		/* validate post */

		if (!$postArray['title'])
		{
			$validateArray[] = $this->_language->get('title_empty');
		}
		else if (!$nameValidator->validate($postArray['title']))
		{
			$validateArray[] = $this->_language->get('title_incorrect');
		}
		if (!$postArray['author'])
		{
			$validateArray[] = $this->_language->get('author_empty');
		}
		else if (!$userValidator->validate($postArray['author']))
		{
			$validateArray[] = $this->_language->get('author_incorrect');
		}
		if (!$postArray['charset'] || !$postArray['limit'])
		{
			$validateArray[] = $this->_language->get('input_empty');
		}
		return $validateArray;
	}

	/**
	 * update the setting
	 *
	 * @since 4.0.0
	 *
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	protected function _update(array $updateArray = []) : bool
	{
		$settingModel = new Admin\Model\Setting();
		return $settingModel->updateByArray($updateArray);
	}

	/**
	 * get error route
	 *
	 * @since 4.1.0
	 *
	 * @return string
	 */

	protected function _getErrorRoute() : string
	{
		if ($this->_registry->get('settingsEdit'))
		{
			return 'admin/edit/settings';
		}
		return 'admin';
	}
}
