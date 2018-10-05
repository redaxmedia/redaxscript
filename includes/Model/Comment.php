<?php
namespace Redaxscript\Model;

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
	 * get the comments by article and language
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param string $language
	 *
	 * @return object|null
	 */

	public function getByArticleAndLanguage(int $articleId = null, string $language = null) : ?object
	{
		return $this
			->query()
			->where('article', $articleId)
			->whereLanguageIs($language)
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
			->where('article', $articleId)
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
		$route = null;
		$commentArray = $this
			->query()
			->tableAlias('d')
			->leftJoinPrefix('articles', 'd.article = a.id', 'a')
			->leftJoinPrefix('categories', 'a.category = c.id', 'c')
			->leftJoinPrefix('categories', 'c.parent = p.id', 'p')
			->select('p.alias', 'parent_alias')
			->select('c.alias', 'category_alias')
			->select('a.alias', 'article_alias')
			->where('d.id', $commentId)
			->findArray();

		/* handle route */

		if (is_array($commentArray[0]))
		{
			$route = implode('/', array_filter($commentArray[0])) . '#comment-' . $commentId;
		}
		return $route;
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
