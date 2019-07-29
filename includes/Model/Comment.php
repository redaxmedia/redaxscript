<?php
namespace Redaxscript\Model;

use PDOException;
use function array_filter;
use function implode;
use function is_array;

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

	public function getByArticleAndLanguageAndOrderAndStep(int $articleId = null, string $language = null, string $orderColumn = 'rank', int $limitStep = null) : ?object
	{
		$query = $this
			->query()
			->where(
			[
				'article' => $articleId,
				'status' => 1
			])
			->whereLanguageIs($language)
			->orderBySetting($orderColumn);
		if ($limitStep)
		{
			$query->limitBySetting($limitStep);
		}
		return $query->findMany() ? : null;
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
				->select('parent.alias', 'parentAlias')
				->select('category.alias', 'categoryAlias')
				->select('article.alias', 'articleAlias')
				->where('comment.id', $commentId)
				->findArray();

			/* handle route */

			if (is_array($routeArray[0]))
			{
				return implode('/', array_filter($routeArray[0])) . '#comment-' . $commentId;
			}
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
		try
		{
			return $this
				->query()
				->create()
				->set($createArray)
				->save();
		}
		catch (PDOException $exception)
		{
			return false;
		}
	}
}
