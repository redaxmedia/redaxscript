<?php
namespace Redaxscript\Modules\FeedGenerator;

use Redaxscript\Dater;
use Redaxscript\Db;
use Redaxscript\Header;
use Redaxscript\Model;
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
		'version' => '4.0.0'
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
			Header::contentType('application/atom+xml');
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

	public function render(string $table = 'articles') : string
	{
		/* query result */

		$resultArray[$table] = Db::forTablePrefix($table)
			->whereLanguageIs($this->_registry->get('language'))
			->whereNull('access')
			->where('status', 1)
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

	protected function _writeXML(array $resultArray = []) : string
	{
		$writer = new XMLWriter();
		$contentModel = new Model\Content();
		$settingModel = new Model\Setting();
		$dater = new Dater();
		$dater->init($this->_registry->get('now'));

		/* prepare href */

		$href = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
		if ($this->_request->getQuery('l'))
		{
			$href .= $this->_registry->get('languageRoute') . $this->_registry->get('language');
		}

		/* write xml */

		$writer->openMemory();
		$writer->setIndent(true);
		$writer->setIndentString('	');
		$writer->startDocument('1.0', $settingModel->get('charset'));
		$writer->startElement('feed');
		$writer->writeAttribute('xmlns', 'http://www.w3.org/2005/Atom');
		$writer->startElement('link');
		$writer->writeAttribute('type', 'application/atom+xml');
		$writer->writeAttribute('href', $href);
		$writer->writeAttribute('rel', 'self');
		$writer->endElement();
		$writer->writeElement('id', $href);
		$writer->writeElement('title', $settingModel->get('title'));
		$writer->writeElement('updated', $dater->getDateTime()->format('c'));
		$writer->startElement('author');
		$writer->writeElement('name', $settingModel->get('author'));
		$writer->endElement();

		/* process result */

		foreach ($resultArray as $table => $result)
		{
			foreach ($result as $value)
			{
				$dater->init($value->date);
				$writer->startElement('entry');
				$writer->writeElement('id', $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . $contentModel->getRouteByTableAndId($table, $value->id));
				$writer->writeElement('title', $value->title);
				$writer->writeElement('updated', $dater->getDateTime()->format('c'));
				$writer->writeElement('content', strip_tags($value->text));
				$writer->endElement();
			}
		}
		$writer->endElement();
		$writer->endDocument();
		return $writer->outputMemory(true);
	}
}
