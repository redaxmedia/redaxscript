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
 * @method _addJoinSource()
 * @method _addOrderBy()
 * @method _setupDb()
 * @method clearCache()
 * @method deleteMany()
 * @method deleteOne()
 * @method findArray()
 * @method findMany()
 * @method findOne()
 * @method forTable()
 * @method getDb()
 * @method rawExecute()
 * @method rawQuery()
 * @method orderByAsc()
 * @method orderByDesc()
 * @method selectExpr()
 * @method tableAlias()
 * @method whereGt()
 * @method whereIdIn()
 * @method whereIdIs()
 * @method whereIn()
 * @method whereLt()
 * @method whereRaw()
 */

class Db extends ORM
{
	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected static $_config;

	/**
	 * init the class
	 *
	 * @since 2.2.0
	 *
	 * @param Config $config instance of the config class
	 */

	public static function init(Config $config)
	{
		self::$_config = $config;
		$dbType = $config->get('dbType');
		$dbHost = $config->get('dbHost');
		$dbName = $config->get('dbName');
		$dbUser = $config->get('dbUser');
		$dbPassword = $config->get('dbPassword');

		/* mysql and pgsql */

		if ($dbType === 'mysql' || $dbType === 'pgsql')
		{
			if ($dbType === 'mysql')
			{
				self::configure('connection_string', 'mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8');
			}
			else
			{
				self::configure('connection_string', 'pgsql:host=' . $dbHost . ';dbname=' . $dbName . ';options=--client_encoding=utf8');
			}

			/* username and password */

			self::configure(array(
				'username' => $dbUser,
				'password' => $dbPassword
			));
		}

		/* sqlite */

		if ($dbType === 'sqlite')
		{
			self::configure('sqlite:' . $dbHost);
		}

		/* general */

		self::configure(array(
			'caching' => true,
			'caching_auto_clear' => true,
			'return_result_sets' => true
		));
	}

	/**
	 * get the database status
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public static function getStatus()
	{
		$output = 0;

		/* has connection */

		try
		{
			if (self::$_config->get('dbType') === self::getDb()->getAttribute(PDO::ATTR_DRIVER_NAME))
			{
				$output = 1;

				/* has tables */

				if (self::countTablePrefix() > 7)
				{
					$output = 2;
				}
			}
		}
		catch (PDOException $exception)
		{
			$output = -1;
		}
		return $output;
	}

	/**
	 * get item from settings
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string
	 */

	public static function getSettings($key = null)
	{
		$output = false;
		$settings = self::forTablePrefix('settings')->findMany();

		/* process settings */

		foreach ($settings as $value)
		{
			if ($value->name === $key)
			{
				$output = $value->value;
			}
		}
		return $output;
	}

	/**
	 * raw instance helper
	 *
	 * @since 2.4.0
	 *
	 * @return Db
	 */

	public static function rawInstance()
	{
		self::_setup_db();
		return new self(null);
	}

	/**
	 * count table with prefix
	 *
	 * @since 2.4.0
	 *
	 * @return Db
	 */

	public static function countTablePrefix()
	{
		if (self::$_config->get('dbType') === 'mysql')
		{
			return self::rawInstance()->rawQuery('SHOW TABLES LIKE \'' . self::$_config->get('dbPrefix') . '%\'')->findMany()->count();
		}
		if (self::$_config->get('dbType') === 'pgsql')
		{
			return self::forTable('pg_catalog.pg_tables')->whereLike('tablename', '%' . self::$_config->get('dbPrefix') . '%')->findMany()->count();
		}
		if (self::$_config->get('dbType') === 'sqlite')
		{
			return self::forTable('sqlite_master')->where('type', 'table')->whereLike('name', '%' . self::$_config->get('dbPrefix') . '%')->whereNotLike('name', '%sqlite_%')->count();
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
		return new self(self::$_config->get('dbPrefix') . $table, array(), $connection);
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
	 * @since 2.4.0
	 *
	 * @param array $columnArray array of column names
	 * @param array $likeArray array of the like
	 *
	 * @return Db
	 */

	public function whereLikeMany($columnArray = null, $likeArray = null)
	{
		return $this->whereRaw(implode($columnArray, ' LIKE ? OR ') . ' LIKE ?', $likeArray);
	}

	/**
	 * find a flatten array
	 *
	 * @since 2.4.0
	 *
	 * @param string $key key of the item
	 *
	 * @return array
	 */

	public function findArrayFlat($key = 'id')
	{
		$output = array();
		foreach ($this->findArray() as $value)
		{
			if (isset($value[$key]))
			{
				$output[] = $value[$key];
			}
		}
		return $output;
	}

	/**
	 * order according to settings
	 *
	 * @since 2.2.0
	 *
	 * @param string $column name of the column
	 *
	 * @return Db
	 */

	public function orderGlobal($column = null)
	{
		return $this->_addOrderBy($column, self::getSettings('order'));
	}

	/**
	 * limit according to settings
	 *
	 * @since 2.2.0
	 *
	 * @return Db
	 */

	public function limitGlobal()
	{
		$this->_limit = self::getSettings('limit');
		return $this;
	}
}
