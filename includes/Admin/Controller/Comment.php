<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;

/**
 * children class to process the admin comment request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Comment extends ControllerAbstract
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
		$myEmail = $this->_registry->get('myEmail');
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
				'author' => $myUser,
				'email' => $myEmail,
				'url' => $postArray['url'],
				'text' => $postArray['text'],
				'language' => $postArray['language'],
				'article' => $postArray['article'],
				'status' => $postArray['date'] > $now ? 2 : $postArray['status'],
				'rank' => $postArray['rank'],
				'access' => $postArray['access'],
				'date' => $postArray['date'] ? : $now
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
				'url' => $postArray['url'],
				'text' => $postArray['text'],
				'language' => $postArray['language'],
				'article' => $postArray['article'],
				'status' => $postArray['date'] > $now ? 2 : $postArray['status'],
				'rank' => $postArray['rank'],
				'access' => $postArray['access'],
				'date' => $postArray['date'] ? : $now
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
		$urlFilter = new Filter\Url();
		$htmlFilter = new Filter\Html();

		/* sanitize post */

		return
		[
			'id' => $numberFilter->sanitize($this->_request->getPost('id')),
			'url' => $urlFilter->sanitize($this->_request->getPost('url')),
			'text' => $htmlFilter->sanitize($this->_request->getPost('text'), $this->_registry->get('filter')),
			'language' => $specialFilter->sanitize($this->_request->getPost('language')),
			'article' => $this->_request->getPost('article'),
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
		$validateArray = [];

		/* validate post */

		if (!$postArray['text'])
		{
			$validateArray[] = $this->_language->get('comment_empty');
		}
		if (!$postArray['article'])
		{
			$validateArray[] = $this->_language->get('article_empty');
		}
		return $validateArray;
	}

	/**
	 * create the comment
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$commentModel = new Admin\Model\Comment();
		return $commentModel->createByArray($createArray);
	}

	/**
	 * update the comment
	 *
	 * @since 4.0.0
	 *
	 * @param int $commentId identifier of the comment
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	public function _update(int $commentId = null, array $updateArray = []) : bool
	{
		$commentModel = new Admin\Model\Comment();
		return $commentModel->updateByIdAndArray($commentId, $updateArray);
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
		if ($this->_registry->get('commentsEdit'))
		{
			if ($postArray['id'])
			{
				return 'admin/view/comments#row-' . $postArray['id'];
			}
			return 'admin/view/comments';
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
		if ($this->_registry->get('commentsEdit') && $postArray['id'])
		{
			return 'admin/edit/comments/' . $postArray['id'];
		}
		if ($this->_registry->get('commentsNew'))
		{
			return 'admin/new/comments';
		}
		return 'admin';
	}
}
