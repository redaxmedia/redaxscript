<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Validator;
use function json_encode;
use function strtotime;

/**
 * children class to process the admin article request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Article extends ControllerAbstract
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
				'text' => $postArray['text'],
				'language' => $postArray['language'],
				'template' => $postArray['template'],
				'sibling' => $postArray['sibling'],
				'category' => $postArray['category'],
				'headline' => $postArray['headline'],
				'byline' => $postArray['byline'],
				'comments' => $postArray['comments'],
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
				'title' => $postArray['title'],
				'alias' => $postArray['alias'],
				'author' => $myUser,
				'description' => $postArray['description'],
				'keywords' => $postArray['keywords'],
				'robots' => $postArray['robots'],
				'text' => $postArray['text'],
				'language' => $postArray['language'],
				'template' => $postArray['template'],
				'sibling' => $postArray['sibling'],
				'category' => $postArray['category'],
				'headline' => $postArray['headline'],
				'byline' => $postArray['byline'],
				'comments' => $postArray['comments'],
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
		$aliasFilter = new Filter\Alias();
		$htmlFilter = new Filter\Html();
		$nameFilter= new Filter\Name();
		$numberFilter = new Filter\Number();
		$specialFilter = new Filter\Special();
		$toggleFilter = new Filter\Toggle();

		/* sanitize post */

		return
		[
			'id' => $numberFilter->sanitize($this->_request->getPost('id')),
			'title' => $nameFilter->sanitize($this->_request->getPost('title')),
			'alias' => $aliasFilter->sanitize($this->_request->getPost('alias')),
			'description' => $this->_request->getPost('description'),
			'keywords' => $this->_request->getPost('keywords'),
			'robots' => $this->_request->getPost('robots'),
			'text' => $htmlFilter->sanitize($this->_request->getPost('text'), $this->_registry->get('filter')),
			'language' => $specialFilter->sanitize($this->_request->getPost('language')),
			'template' => $specialFilter->sanitize($this->_request->getPost('template')),
			'sibling' => $this->_request->getPost('sibling'),
			'category' => $this->_request->getPost('category'),
			'headline' => $toggleFilter->sanitize($this->_request->getPost('headline')),
			'byline' => $toggleFilter->sanitize($this->_request->getPost('byline')),
			'comments' => $toggleFilter->sanitize($this->_request->getPost('comments')),
			'status' => $toggleFilter->sanitize($this->_request->getPost('status')),
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
		$nameValidator = new Validator\Name();
		$aliasValidator = new Validator\Alias();
		$articleModel = new Admin\Model\Article();
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
		if (!$postArray['alias'])
		{
			$validateArray[] = $this->_language->get('alias_empty');
		}
		else if (!$aliasValidator->validate($postArray['alias']) || $aliasValidator->matchSystem($postArray['alias']))
		{
			$validateArray[] = $this->_language->get('alias_incorrect');
		}
		else if (!$articleModel->isUniqueByIdAndAlias($postArray['id'], $postArray['alias']))
		{
			$validateArray[] = $this->_language->get('alias_exists');
		}
		if (!$postArray['text'])
		{
			$validateArray[] = $this->_language->get('article_empty');
		}
		return $validateArray;
	}

	/**
	 * create the article
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$articleModel = new Admin\Model\Article();
		return $articleModel->createByArray($createArray);
	}

	/**
	 * update the article
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	protected function _update(int $articleId = null, array $updateArray = []) : bool
	{
		$articleModel = new Admin\Model\Article();
		return $articleModel->updateByIdAndArray($articleId, $updateArray);
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
		if ($this->_registry->get('articlesEdit') && $postArray['id'])
		{
			return 'admin/view/articles#row-' . $postArray['id'];
		}
		if ($this->_registry->get('articlesEdit') && $postArray['alias'])
		{
			$articleModel = new Admin\Model\Article();
			return 'admin/view/articles#row-' . $articleModel->getByAlias($postArray['alias'])->id;
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
		if ($this->_registry->get('articlesEdit') && $postArray['id'])
		{
			return 'admin/edit/articles/' . $postArray['id'];
		}
		if ($this->_registry->get('articlesNew'))
		{
			return 'admin/new/articles';
		}
		return 'admin';
	}
}
