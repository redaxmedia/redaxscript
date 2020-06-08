<?php
namespace Redaxscript;

use function file_get_contents;
use function method_exists;
use function str_replace;
use function ucfirst;

/**
 * parent class to install the database
 *
 * @since 2.4.0
 *
 * @category Installer
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Installer
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected $_config;

	/**
	 * name of the directory
	 *
	 * @var string
	 */

	protected $_directory;

	/**
	 * placeholder for the prefix
	 *
	 * @var string
	 */

	protected $_prefixPlaceholder = '/* %PREFIX% */';

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 * @param Language $language instance of the language class
	 * @param Config $config instance of the config class
	 */

	public function __construct(Registry $registry, Request $request, Language $language, Config $config)
	{
		$this->_registry = $registry;
		$this->_request = $request;
		$this->_language = $language;
		$this->_config = $config;
	}

	/**
	 * init the class
	 *
	 * @since 2.6.0
	 *
	 * @param string $directory name of the directory
	 */

	public function init(string $directory = 'database') : void
	{
		$this->_directory = $directory;
	}

	/**
	 * create from sql
	 *
	 * @since 2.4.0
	 */

	public function rawCreate() : void
	{
		$this->_rawExecute('create', $this->_config->get('dbType'));
	}

	/**
	 * drop from sql
	 *
	 * @since 2.4.0
	 */

	public function rawDrop() : void
	{
		$this->_rawExecute('drop', $this->_config->get('dbType'));
	}

	/**
	 * insert the data
	 *
	 * @since 3.1.0
	 *
	 * @param array $optionArray options of the installation
	 */

	public function insertData(array $optionArray = []) : void
	{
		$this->insertCategories($optionArray);
		$this->insertArticles($optionArray);
		$this->insertExtras($optionArray);
		$this->insertComments($optionArray);
		$this->insertGroups();
		$this->insertUsers($optionArray);
		$this->insertModules();
		$this->insertSettings($optionArray);
	}

	/**
	 * insert the categories
	 *
	 * @since 3.1.0
	 *
	 * @param array $optionArray options of the installation
	 */

	public function insertCategories(array $optionArray = []) : void
	{
		$now = $this->_registry->get('now');
		Db::forTablePrefix('categories')
			->create()
			->set(
			[
				'title' => 'Home',
				'alias' => 'home',
				'author' => $optionArray['adminUser'],
				'rank' => 1,
				'date' => $now
			])
			->save();
	}

	/**
	 * insert the articles
	 *
	 * @since 3.1.0
	 *
	 * @param array $optionArray options of the installation
	 */

	public function insertArticles(array $optionArray = []) : void
	{
		$now = $this->_registry->get('now');
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Welcome',
				'alias' => 'welcome',
				'author' => $optionArray['adminUser'],
				'text' => file_get_contents('database' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'articles' . DIRECTORY_SEPARATOR . 'welcome.phtml'),
				'category' => 1,
				'comments' => 1,
				'rank' => 1,
				'date' => $now
			])
			->save();
	}

	/**
	 * insert the extras
	 *
	 * @since 3.1.0
	 *
	 * @param array $optionArray options of the installation
	 */

	public function insertExtras(array $optionArray = []) : void
	{
		$now = $this->_registry->get('now');
		$extrasArray =
		[
			'categories' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 1
			],
			'articles' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 1
			],
			'comments' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 1
			],
			'languages' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 0
			],
			'templates' =>
			[
				'category' => null,
				'headline' => 1,
				'status' => 0
			],
			'teaser' =>
			[
				'category' => 1,
				'headline' => 0,
				'status' => 0
			]
		];
		$extrasRank = 0;

		/* process extras */

		foreach ($extrasArray as $key => $value)
		{
			Db::forTablePrefix('extras')
				->create()
				->set(
				[
					'title' => ucfirst($key),
					'alias' => $key,
					'author' => $optionArray['adminUser'],
					'text' => file_get_contents('database' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'extras' . DIRECTORY_SEPARATOR . $key . '.phtml'),
					'category' => $value['category'],
					'headline' => $value['headline'],
					'status' => $value['status'],
					'rank' => ++$extrasRank,
					'date' => $now
				])
				->save();
		}
	}

	/**
	 * insert the comments
	 *
	 * @since 3.1.0
	 *
	 * @param array $optionArray options of the installation
	 */

	public function insertComments(array $optionArray = []) : void
	{
		$now = $this->_registry->get('now');
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => $optionArray['adminUser'],
				'email' => $optionArray['adminEmail'],
				'text' => file_get_contents('database' . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'comments' . DIRECTORY_SEPARATOR . 'hello.phtml'),
				'article' => 1,
				'rank' => 1,
				'date' => $now
			])
			->save();
	}

	/**
	 * insert the groups
	 *
	 * @since 3.1.0
	 */

	public function insertGroups() : void
	{
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Administrators',
				'alias' => 'administrators',
				'description' => 'Unlimited access',
				'categories' => '[1, 2, 3]',
				'articles' => '[1, 2, 3]',
				'extras' => '[1, 2, 3]',
				'comments' => '[1, 2, 3]',
				'groups' => '[1, 2, 3]',
				'users' => '[1, 2, 3]',
				'modules' => '[1, 2, 3]',
				'settings' => 1,
				'filter' => 0
			])
			->save();
		Db::forTablePrefix('groups')
			->create()
			->set(
			[
				'name' => 'Members',
				'alias' => 'members',
				'description' => 'Default members group'
			])
			->save();
	}

	/**
	 * insert the users
	 *
	 * @since 3.1.0
	 *
	 * @param array $optionArray options of the installation
	 */

	public function insertUsers(array $optionArray = []) : void
	{
		$passwordHash = new Hash();
		$passwordHash->init($optionArray['adminPassword']);
		Db::forTablePrefix('users')
			->create()
			->set(
			[
				'name' => $optionArray['adminName'],
				'user' => $optionArray['adminUser'],
				'password' => $passwordHash->getHash(),
				'email' => $optionArray['adminEmail'],
				'description' => 'God admin',
				'groups' => '[1]'
			])
			->save();
	}

	/**
	 * insert the modules
	 *
	 * @since 3.1.0
	 */

	public function insertModules() : void
	{
		$moduleArray =
		[
			'AliasGenerator',
			'CallHome',
			'ConfirmAction',
			'FormValidator',
			'RankSorter',
			'TextareaResizer',
			'UnmaskPassword',
			'VisualEditor'
		];

		/* process modules */

		foreach ($moduleArray as $alias)
		{
			$moduleClass = 'Redaxscript\Modules\\' . $alias . '\\' . $alias;

			/* install */

			if (method_exists($moduleClass, 'install'))
			{
				$module = new $moduleClass($this->_registry, $this->_request, $this->_language, $this->_config);
				$module->install();
			}
		}
	}

	/**
	 * insert the settings
	 *
	 * @since 3.1.0
	 *
	 * @param array $optionArray options of the installation
	 */

	public function insertSettings(array $optionArray = []) : void
	{
		$settingArray =
		[
			'language' => null,
			'template' => null,
			'title' => $this->_language->get('_package')['name'],
			'author' => $optionArray['adminName'],
			'copyright' => null,
			'description' => $this->_language->get('_package')['description'],
			'keywords' => null,
			'robots' => 1,
			'email' => $optionArray['adminEmail'],
			'subject' => $this->_language->get('_package')['name'],
			'notification' => 0,
			'charset' => 'utf-8',
			'divider' => ' - ',
			'zone' => 'Europe/Berlin',
			'time' => 'H:i',
			'date' => 'd.m.Y',
			'homepage' => 0,
			'limit' => 10,
			'order' => 'asc',
			'pagination' => 1,
			'registration' => 0,
			'verification' => 0,
			'recovery' => 1,
			'moderation' => 0,
			'captcha' => 0,
			'version' => $this->_language->get('_package')['version']
		];

		/* process settings */

		foreach ($settingArray as $name => $value)
		{
			Db::forTablePrefix('settings')
				->create()
				->set(
				[
					'name' => $name,
					'value' => $value
				])
				->save();
		}
	}

	/**
	 * execute from sql
	 *
	 * @since 2.4.0
	 *
	 * @param string $action action to process
	 * @param string $type type of the database
	 */

	protected function _rawExecute(string $action = null, string $type = 'mysql') : void
	{
		$actionFilesystem = new Filesystem\File();
		$actionFilesystem->init($this->_directory . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $action);
		$actionFilesystemArray = $actionFilesystem->getSortArray();

		/* process filesystem */

		foreach ($actionFilesystemArray as $file)
		{
			$query = $actionFilesystem->readFile($file);
			if ($query)
			{
				if ($this->_config->get('dbPrefix'))
				{
					$query = str_replace($this->_prefixPlaceholder, $this->_config->get('dbPrefix'), $query);
				}
				Db::rawExecute($query);
			}
		}
	}
}
