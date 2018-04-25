<?php
namespace Redaxscript\Head;

use Redaxscript\Asset;
use Redaxscript\Html;
use Redaxscript\Registry;
use Redaxscript\Language;

/**
 * children class to create the script tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 */

class Script extends HeadAbstract
{
	/**
	 * options of the script
	 *
	 * @var string
	 */

	protected static $_optionArray =
	[
		'directory' => 'cache/scripts',
		'extension' => 'js',
		'attribute' => 'src',
		'lifetime' => 86400
	];

	/**
	 * inline script
	 *
	 * @var string
	 */

	protected static $_inline = null;

	/**
	 * append script file
	 *
	 * @since 3.0.0
	 *
	 * @param string $source
	 *
	 * @return self
	 */

	public function appendFile(string $source = null) : self
	{
		$this->append('src', $source);
		return $this;
	}

	/**
	 * prepend script file
	 *
	 * @since 3.0.0
	 *
	 * @param string $source
	 *
	 * @return self
	 */

	public function prependFile(string $source = null) : self
	{
		$this->prepend('src', $source);
		return $this;
	}

	/**
	 * remove script file
	 *
	 * @since 3.0.0
	 *
	 * @param string $source
	 *
	 * @return self
	 */

	public function removeFile(string $source = null) : self
	{
		$this->remove('src', $source);
		return $this;
	}

	/**
	 * append inline script
	 *
	 * @since 3.0.0
	 *
	 * @param string $inline
	 *
	 * @return self
	 */

	public function appendInline(string $inline = null) : self
	{
		self::$_inline .= $inline;
		return $this;
	}

	/**
	 * prepend inline script
	 *
	 * @since 3.0.0
	 *
	 * @param string $inline
	 *
	 * @return self
	 */

	public function prependInline(string $inline = null) : self
	{
		self::$_inline = $inline . self::$_inline;
		return $this;
	}

	/**
	 * transport javascript variables
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param string|array $value
	 *
	 * @return self
	 */

	public function transportVar(string $key = null, $value = null) : self
	{
		$transport = new Asset\Transport(Registry::getInstance(), Language::getInstance());
		$inline = $transport->render($key, $value);
		$this->appendInline($inline);
		return $this;
	}

	/**
	 * concat the script
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return self
	 */

	public function concat(array $optionArray = []) : self
	{
		$optionArray = array_merge(self::$_optionArray, $optionArray);
		$loader = new Asset\Loader(Registry::getInstance());
		$loader
			->init($this->_getCollectionArray())
			->concat($optionArray);

		/* update collection */

		$this->_setCollectionArray($loader->getCollectionArray());
		return $this;
	}

	/**
	 * render the script
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public function render() : ?string
	{
		$output = null;

		/* html element */

		$scriptElement = new Html\Element();
		$scriptElement->init('script');

		/* handle collection */

		$collectionArray = $this->_getCollectionArray();

		/* process collection */

		foreach ($collectionArray as $key => $attribute)
		{
			$output .= $scriptElement
				->copy()
				->attr($attribute);
		}

		/* collect inline */

		if (self::$_inline)
		{
			$output .= $scriptElement
				->copy()
				->text(self::$_inline);
		}
		$this->clear();
		return $output;
	}

	/**
	 * clear the script
	 *
	 * @since 3.0.0
	 *
	 * @return self
	 */

	public function clear() : self
	{
		parent::clear();
		self::$_inline = null;
		return $this;
	}
}