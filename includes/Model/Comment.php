<?php
namespace Redaxscript\Model;

use function array_column;
use function array_filter;
use function array_pop;
use function array_search;
use function implode;

/**
 * parent class to provide the comment model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Comment extends ContentAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'comments';

	/**
	 * count the comments by article and language
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param string $language
	 *
	 * @return int|null
	 */

	public function countByArticleAndLanguage(int $articleId = null, string $language = null) : ?int
	{
		return $this
			->query()
			->where(
			[
				'article' => $articleId,
				'status' => 1
			])
			->whereLanguageIs($language)
			->count() ? : null;
	}

	/**
	 * get the articles by category and language and order
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param string $language
	 * @param string $orderColumn
	 *
	 * @return object|null
	 */

	public function getByArticleAndLanguageAndOrder(int $articleId = null, string $language = null, string $orderColumn = null) : ?object
	{
		return $this
			->query()
			->where(
			[
				'article' => $articleId,
				'status' => 1
			])
			->whereLanguageIs($language)
			->orderBySetting($orderColumn)
			->findMany() ? : null;
	}

	/**
	 * get the articles by category and language and order and step
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param string $language
	 * @param string $orderColumn
	 * @param int $limitStep
	 *
	 * @return object|null
	 */

	public function getByArticleAndLanguageAndOrderAndStep(int $articleId = null, string $language = null, string $orderColumn = null, int $limitStep = null) : ?object
	{
		return $this
			->query()
			->where(
			[
				'article' => $articleId,
				'status' => 1
			])
			->whereLanguageIs($language)
			->orderBySetting($orderColumn)
			->limitBySetting($limitStep)
			->findMany() ? : null;
	}

	/**
	 * get the comment route by id
	 *
	 * @since 3.3.0
	 *
	 * @param int $commentId identifier of the comment
	 *
	 * @return string|null
	 */

	public function getRouteById(int $commentId = null) : ?string
	{
		if ($commentId)
		{
			$routeArray = $this
				->query()
				->tableAlias('comment')
				->leftJoinPrefix('articles', 'comment.article = article.id', 'article')
				->leftJoinPrefix('categories', 'article.category = category.id', 'category')
				->leftJoinPrefix('categories', 'category.parent = parent.id', 'parent')
				->select('parent.alias', 'parent_alias')
				->select('category.alias', 'categoryAlias')
				->select('article.alias', 'articleAlias')
				->select('comment.id', 'commentId')
				->findArray();

			/* handle route */

			$key = array_search($commentId, array_column($routeArray, 'commentId'));
			array_pop($routeArray[$key]);
			return implode('/', array_filter($routeArray[$key])) . '#comment-' . $commentId;
		}
		return null;
	}

	/**
	 * create the comment by array
	 *
	 * @since 3.3.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return $this
			->query()
			->create()
			->set($createArray)
			->save();
	}
}
