<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;
use function count;

/**
 * children class to create the admin article table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ArticleTable extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('adminArticleTableStart');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$articlesNew = $this->_registry->get('articlesNew');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($this->_language->get('articles'));
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => 'rs-admin-button-default rs-admin-button-create',
				'href' => $parameterRoute . 'admin/new/articles'
			])
			->text($this->_language->get('article_new'));

		/* collect output */

		$output .= $titleElement;
		if ($articlesNew)
		{
			$output .= $linkElement;
		}
		$output .= $this->_renderTable();
		$output .= Module\Hook::trigger('adminArticleTableEnd');
		return $output;
	}

	/**
	 * render the table
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	protected function _renderTable() : ?string
	{
		$output = null;
		$outputHead = null;
		$outputBody = null;
		$outputFoot = null;
		$tableArray =
		[
			'title' => $this->_language->get('title'),
			'alias' => $this->_language->get('alias'),
			'language' => $this->_language->get('language'),
			'category' => $this->_language->get('category'),
			'rank' => $this->_language->get('rank')
		];
		$adminControl = new Helper\Control($this->_registry, $this->_language);
		$adminControl->init();
		$categoryModel = new Admin\Model\Category();
		$articleModel = new Admin\Model\Article();
		$articles = $articleModel->getAllByOrder('rank');
		$articlesTotal = $articles->count();
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html element */

		$element = new Html\Element();
		$wrapperElement = $element
			->copy()
			->init('div',
			[
				'class' => 'rs-admin-wrapper-table'
			]);
		$tableElement = $element
			->copy()
			->init('table',
			[
				'class' => 'rs-admin-js-sort rs-admin-table-default rs-admin-table-article'
			]);
		$linkElement = $element->copy()->init('a');
		$theadElement = $element->copy()->init('thead');
		$tbodyElement = $element->copy()->init('tbody');
		$tfootElement = $element->copy()->init('tfoot');
		$trElement = $element->copy()->init('tr');
		$thElement = $element->copy()->init('th');
		$tdElement = $element->copy()->init('td');

		/* process table */

		foreach ($tableArray as $key => $value)
		{
			$outputHead .= $thElement->copy()->text($value);
			$outputFoot .= $tdElement->copy()->text($value);
		}

		/* process categories */

		if ($articlesTotal)
		{
			foreach ($articles as $key => $value)
			{
				$outputBody .= $trElement
					->copy()
					->attr('id', 'row-' . $value->id)
					->addClass(!$value->status ? 'rs-admin-is-disabled' : null)
					->html(
						$tdElement->copy()->html(
							$linkElement
								->attr('href', $parameterRoute . $articleModel->getRouteById($value->id))
								->text($value->title) .
							$adminControl->render('articles', $value->id, $value->alias, $value->status)
						) .
						$tdElement->copy()->text($value->alias) .
						$tdElement->copy()->text($value->language ? $this->_language->get('_language')[$value->language] : $this->_language->get('all')) .
						$tdElement->copy()->text($value->category ? $categoryModel->getById($value->category)->title : $this->_language->get('uncategorized')) .
						$tdElement
							->copy()
							->addClass('rs-admin-js-move rs-admin-col-move')
							->addClass($articlesTotal > 1 ? 'rs-admin-is-active' : null)
							->text($value->rank)
				);
			}
		}
		else
		{
			$outputBody .= $trElement
				->copy()
				->html(
					$tdElement
						->copy()
						->attr('colspan', count($tableArray))
						->text($this->_language->get('article_no'))
				);
		}

		/* collect output */

		$outputHead = $theadElement->html(
			$trElement->html($outputHead)
		);
		$outputBody = $tbodyElement->html($outputBody);
		$outputFoot = $tfootElement->html(
			$trElement->html($outputFoot)
		);
		$output .= $wrapperElement->copy()->html(
			$tableElement->html($outputHead . $outputBody . $outputFoot)
		);
		return $output;
	}
}
