<?php
namespace Redaxscript\Modules\FeedGenerator;

use Redaxscript\Db;
use Redaxscript\Module;
use XMLWriter;

/**
 * generate atom feeds from content
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class FeedGenerator extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Feed Generator',
		'alias' => 'FeedGenerator',
		'author' => 'Redaxmedia',
		'description' => 'Generate Atom feeds from content',
		'version' => '3.2.3'
	];

	/**
	 * renderStart
	 *
	 * @since 2.3.0
	 */

	public function renderStart()
	{
		$firstParamter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		if ($firstParamter === 'feed' && ($secondParameter === 'articles' || $secondParameter === 'comments'))
		{
			$this->_registry->set('renderBreak', true);
			header('content-type: application/atom+xml');
			echo $this->render($secondParameter);
		}
	}

	/**
	 * render
	 *
	 * @since 2.3.0
	 *
	 * @param string $table
	 *
	 * @return string
	 */

	public function render($table = 'articles')
	{
		/* query result */

		$resultArray[$table] = Db::forTablePrefix($table)
			->where('status', 1)
			->whereNull('access')
			->whereLanguageIs($this->_registry->get('language'))
			->orderGlobal('rank')
			->findMany();

		/* write xml */

		return $this->_writeXML($resultArray);
	}

	/**
	 * @param array $resultArray
	 *
	 * @return string
	 */

	protected function _writeXML($resultArray = [])
	{
		/* prepare href */

		$href = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
		if ($this->_request->getQuery('l'))
		{
			$href .= $this->_registry->get('languageRoute') . $this->_registry->get('language');
		}

		/* write xml */

		$writer = new XMLWriter();
		$writer->openMemory();
		$writer->setIndent(true);
		$writer->setIndentString('	');
		$writer->startDocument('1.0', Db::getSetting('charset'));
		$writer->startElement('feed');
		$writer->writeAttribute('xmlns', 'http://www.w3.org/2005/Atom');
		$writer->startElement('link');
		$writer->writeAttribute('type', 'application/atom+xml');
		$writer->writeAttribute('href', $href);
		$writer->writeAttribute('rel', 'self');
		$writer->endElement();
		$writer->writeElement('id', $href);
		$writer->writeElement('title', Db::getSetting('title'));
		$writer->writeElement('updated', date('c', strtotime($this->_registry->get('now'))));
		$writer->startElement('author');
		$writer->writeElement('name', Db::getSetting('author'));
		$writer->endElement();

		/* process result */

		foreach ($resultArray as $table => $result)
		{
			foreach ($result as $value)
			{
				$writer->startElement('entry');
				$writer->writeElement('id', $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . build_route($table, $value->id));
				$writer->writeElement('title', $value->title);
				$writer->writeElement('updated', date('c', strtotime($value->date)));
				$writer->writeElement('content', strip_tags($value->text));
				$writer->endElement();
			}
		}
		$writer->endElement();
		$writer->endDocument();
		return $writer->outputMemory(true);
	}
}
