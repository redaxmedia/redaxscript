<?php
namespace Redaxscript;

use ORM;
use PDO;
use PDOException;

/**
 * children class to handle the database
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Db
 * @author Henry Ruhs
 *
 * @method _addJoinSource(string $operator, string $table, mixed $constraint, string $tableAlias)
 * @method _addOrderBy(string $column, string $value)
 * @method _addWhere(string $clause, array $valueArray)
 * @method _setupDb(string $connection)
 */

class Db extends ORM
{
	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected static $_config;

	/**
	 * constructor of the class
	 *
	 * @since 2.6.0
	 *
	 * @param Config $config instance of the config class
	 */

	public static function construct(Config $config)
	{
		self::$_config = $config;
	}

	/**
	 * init the class
	 *
	 * @since 3.1.0
	 */

	public static function init()
	{
		$dbType = self::$_config->get('dbType');
		$dbHost = self::$_config->get('dbHost');
		$dbName = self::$_config->get('dbName');
		$dbUser = self::$_config->get('dbUser');
		$dbPassword = self::$_config->get('dbPassword');
		$dbSocket = strstr($dbHost, '.sock');

		/* handle various types */

		if ($dbType === 'mssql' || $dbType === 'mysql' || $dbType === 'pgsql')
		{
			if ($dbType === 'mssql')
			{
				self::configure('connection_string', 'sqlsrv:server=' . $dbHost . ';database=' . $dbName);
			}
			if ($dbType === 'mysql')
			{
				self::configure('connection_string', 'mysql:' . ($dbSocket ? 'unix_socket' : 'host') . '=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8');
			}
			if ($dbType === 'pgsql')
			{
				self::configure('connection_string', 'pgsql:' . ($dbSocket ? 'unix_socket' : 'host') . '=' . $dbHost . ';dbname=' . $dbName . ';options=--client_encoding=utf8');
			}

			/* username and password */

			self::configure('username', $dbUser);
			if ($dbPassword)
			{
				self::configure('password', $dbPassword);
			}
		}

		/* else handle sqlite */

		if ($dbType === 'sqlite')
		{
			self::configure('sqlite:' . $dbHost);
		}

		/* general */

		self::configure(
		[
			'caching' => true,
			'caching_auto_clear' => true,
			'return_result_sets' => true
		]);
	}

	/**
	 * get the database status
	 *
	 * @since 3.1.0
	 *
	 * @return integer
	 */

	public static function getStatus()
	{
		$output = 0;

		/* has connection */

		try
		{
			$dbType = self::$_config->get('dbType') === 'mssql' ? 'sqlsrv' : self::$_config->get('dbType');
			$dbDriver = self::getDb()->getAttribute(PDO::ATTR_DRIVER_NAME);
			if ($dbType === $dbDriver)
			{
				$output = self::countTablePrefix() > 7 ? 2 : 1;
			}
		}
		catch (PDOException $exception)
		{
			$output = 0;
		}
		return $output;
	}

	/**
	 * raw instance helper
	 *
	 * @since 2.4.0
	 *
	 * @param string $table name of the table
	 * @param string $connection which connection to use
	 *
	 * @return Db
	 */

	public static function rawInstance($table = null, $connection = self::DEFAULT_CONNECTION)
	{
		self::_setupDb($connection);
		return new self($table);
	}

	/**
	 * count table with prefix
	 *
	 * @since 3.1.0
	 *
	 * @return Db
	 */

	public static function countTablePrefix()
	{
		$dbType = self::$_config->get('dbType');
		$dbName = self::$_config->get('dbName');
		$dbPrefix = self::$_config->get('dbPrefix');

		/* mssql and mysql */

		if ($dbType === 'mssql' || $dbType === 'mysql')
		{
			return self::forTable('information_schema.tables')
				->where($dbType === 'mssql' ? 'table_catalog' : 'table_schema', $dbName)
				->whereLike('table_name', $dbPrefix . '%')
				->count();
		}

		/* pgsql */

		if ($dbType === 'pgsql')
		{
			return self::forTable('pg_catalog.pg_tables')
				->whereLike('tablename', $dbPrefix . '%')
				->whereNotLike('tablename', 'pg_%')
				->whereNotLike('tablename', 'sql_%')
				->count();
		}

		/* sqlite */

		if ($dbType === 'sqlite')
		{
			return self::forTable('sqlite_master')
				->whereLike('tbl_name', $dbPrefix . '%')
				->whereNotLike('tbl_name', 'sql_%')
				->count();
		}
	}

	/**
	 * for table with prefix
	 *
	 * @since 2.2.0
	 *
	 * @param string $table name of the table
	 * @param string $connection which connection to use
	 *
	 * @return Db
	 */

	public static function forTablePrefix($table = null, $connection = self::DEFAULT_CONNECTION)
	{
		self::_setupDb($connection);
		return new self(self::$_config->get('dbPrefix') . $table, [], $connection);
	}

	/**
	 * left join with prefix
	 *
	 * @since 2.2.0
	 *
	 * @param string $table name of the table
	 * @param string $constraint constraint as needed
	 * @param string $tableAlias alias of the table
	 *
	 * @return Db
	 */

	public function leftJoinPrefix($table = null, $constraint = null, $tableAlias = null)
	{
		return $this->_addJoinSource('LEFT', self::$_config->get('dbPrefix') . $table, $constraint, $tableAlias);
	}

	/**
	 * where like with many
	 *
	 * @since 3.0.0
	 *
	 * @param array $columnArray array of column names
	 * @param array $likeArray array of the like
	 *
	 * @return Db
	 */

	public function whereLikeMany($columnArray = [], $likeArray = [])
	{
		return $this->_addWhere('(' . implode($columnArray, ' LIKE ? OR ') . ' LIKE ? )', $likeArray);
	}

	/**
	 * where language is
	 *
	 * @since 3.0.0
	 *
	 * @param string $language value of the language
	 *
	 * @return Db
	 */

	public function whereLanguageIs($language = null)
	{
		return $this->_addWhere('(language = \'' . $language . '\' OR language IS NULL)');
	}

	/**
	 * find a flat array
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return array
	 */

	public function findFlatArray($key = 'id')
	{
		$flatArray = [];
		foreach ($this->findArray() as $value)
		{
			if (array_key_exists($key, $value))
			{
				$flatArray[] = $value[$key];
			}
		}
		return $flatArray;
	}

	/**
	 * get the setting
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return mixed
	 */

	public function getSetting($key = null)
	{
		$settings = self::forTablePrefix('settings')->findMany();

		/* process settings */

		if ($key)
		{
			foreach ($settings as $setting)
			{
				if ($setting->name === $key)
				{
					return $setting->value;
				}
			}
		}
		else
		{
			return $settings;
		}
		return false;
	}

	/**
	 * set the setting
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param string $value value of the item
	 *
	 * @return boolean
	 */

	public function setSetting($key = null, $value = null)
	{
		return self::forTablePrefix('settings')->where('name', $key)->findOne()->set('value', $value)->save();
	}

	/**
	 * order according to global setting
	 *
	 * @since 2.2.0
	 *
	 * @param string $column name of the column
	 *
	 * @return Db
	 */

	public function orderGlobal($column = null)
	{
		return $this->_addOrderBy($column, $this->getSetting('order'));
	}

	/**
	 * limit according to global setting
	 *
	 * @since 2.2.0
	 *
	 * @return Db
	 */

	public function limitGlobal()
	{
		$this->_limit = $this->getSetting('limit');
		return $this;
	}
}
