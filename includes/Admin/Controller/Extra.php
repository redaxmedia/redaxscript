<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Validator;

/**
 * children class to process the admin extra request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Extra extends ControllerAbstract
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
				'route' => $postArray['id'] ? 'admin/edit/extras/' . $postArray['id'] : 'admin/new/extras',
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
				'text' => $postArray['text'],
				'language' => $postArray['language'],
				'sibling' => $postArray['sibling'],
				'category' => $postArray['category'],
				'article' => $postArray['article'],
				'headline' => $postArray['headline'],
				'status' => $postArray['date'] > $now ? 2 : $postArray['status'],
				'rank' => $postArray['rank'],
				'access' => $postArray['access'],
				'date' => $postArray['date'] ? $postArray['date'] : $now
			];
			if ($this->_create($createArray))
			{
				return $this->_success(
				[
					'route' => 'admin/view/extras#' . $postArray['alias'],
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
				'title' => $postArray['title'],
				'alias' => $postArray['alias'],
				'author' => $myUser,
				'text' => $postArray['text'],
				'language' => $postArray['language'],
				'sibling' => $postArray['sibling'],
				'category' => $postArray['category'],
				'article' => $postArray['article'],
				'headline' => $postArray['headline'],
				'status' => $postArray['date'] > $now ? 2 : $postArray['status'],
				'rank' => $postArray['rank'],
				'access' => $postArray['access'],
				'date' => $postArray['date'] ? $postArray['date'] : $now
			];
			if ($this->_update($postArray['id'], $updateArray))
			{
				return $this->_success(
				[
					'route' => 'admin/view/extras#' . $postArray['alias'],
					'timeout' => 2,
					'message' => $this->_language->get('operation_completed')
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => $postArray['id'] ? 'admin/edit/extras/' . $postArray['id'] : 'admin/new/extras',
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
		$htmlFilter = new Filter\Html();

		/* sanitize post */

		return
		[
			'id' => $specialFilter->sanitize($this->_request->getPost('id')),
			'title' => $this->_request->getPost('title'),
			'alias' => $aliasFilter->sanitize($this->_request->getPost('alias')),
			'text' => $htmlFilter->sanitize($this->_request->getPost('text'), $this->_registry->get('filter')),
			'language' => $specialFilter->sanitize($this->_request->getPost('language')),
			'sibling' => $specialFilter->sanitize($this->_request->getPost('sibling')),
			'category' => $specialFilter->sanitize($this->_request->getPost('category')),
			'article' => $specialFilter->sanitize($this->_request->getPost('article')),
			'headline' => $specialFilter->sanitize($this->_request->getPost('headline')),
			'status' => $specialFilter->sanitize($this->_request->getPost('status')),
			'rank' => $specialFilter->sanitize($this->_request->getPost('rank')),
			'access' => $specialFilter->sanitize($this->_request->getPost('access')),
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
		$extraModel = new Admin\Model\Extra();
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
		else if ($extraModel->getByAlias($postArray['alias'])->id !== $extraModel->getById($postArray['id'])->id)
		{
			$validateArray[] = $this->_language->get('alias_exists');
		}
		if (!$postArray['text'])
		{
			$validateArray[] = $this->_language->get('text_empty');
		}
		return $validateArray;
	}

	/**
	 * create the extra
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$extraModel = new Admin\Model\Extra();
		return $extraModel->createByArray($createArray);
	}

	/**
	 * update the extra
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(int $extraId = null, array $updateArray = []) : bool
	{
		$extraModel = new Admin\Model\Extra();
		return $extraModel->updateByIdAndArray($extraId, $updateArray);
	}
}
