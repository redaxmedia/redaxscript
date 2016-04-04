<?php
error_reporting(E_ERROR || E_PARSE);

/* include core files */

include_once('includes/loader.php');
include_once('includes/startup.php');
include_once('includes/Singleton.php');
include_once('includes/Config.php');

if (is_array($argv))
{
	/* install cli */

	install_cli($argv);

	/* bootstrap */

	include_once('includes/bootstrap.php');

	/* install */

	install();
}
else
{
	/* install post */

	install_post();

	/* bootstrap */

	include_once('includes/bootstrap.php');

	/* module init */

	Redaxscript\Hook::trigger('init');

	/* call loader else render template */

	if (Redaxscript\Registry::get('firstParameter') == 'loader' && (Redaxscript\Registry::get('secondParameter') == 'styles' || Redaxscript\Registry::get('secondParameter') == 'scripts'))
	{
		echo loader(Redaxscript\Registry::get('secondParameter'), 'outline');
	}
	else
	{
		include_once('templates/install/install.phtml');
	}
}

/**
 * install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 */

function install()
{
	global $name, $user, $password, $email;

	/* installer */

	$installer = new Redaxscript\Installer(Redaxscript\Config::getInstance());
	$installer->init();
	$installer->rawDrop();
	$installer->rawCreate();
	$installer->insertData(array(
		'adminName' => $name,
		'adminUser' => $user,
		'adminPassword' => $password,
		'adminEmail' => $email
	));

	/* send login information */

	$urlLink = '<a href="' . Redaxscript\Registry::get('root') . '"/>' . Redaxscript\Registry::get('root') . '</a>';
	$toArray = $fromArray = array(
		$name => $email
	);
	$subject = Redaxscript\Language::get('installation');
	$bodyArray = array(
		'<strong>' . Redaxscript\Language::get('user') . Redaxscript\Language::get('colon') . '</strong> ' . $user,
		'<br />',
		'<strong>' . Redaxscript\Language::get('password') . Redaxscript\Language::get('colon') . '</strong> ' . $password,
		'<br />',
		'<strong>' . Redaxscript\Language::get('url') . Redaxscript\Language::get('colon') . '</strong> ' . $urlLink
	);

	/* mailer object */

	$mailer = new Redaxscript\Mailer();
	$mailer->init($toArray, $fromArray, $subject, $bodyArray);
	$mailer->send();
}

/**
 * install cli
 *
 * @since 2.4.0
 * @deprecated 2.4.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 *
 * @param array $argv
 */

function install_cli($argv = array())
{
	global $d_type, $d_host, $d_name, $d_user, $d_password, $d_prefix, $d_salt, $name, $user, $password, $email;

	$output = Redaxscript\Language::get('name', '_package') . ' ' . Redaxscript\Language::get('version', '_package') . PHP_EOL . PHP_EOL;
	$typeArray = array();
	foreach (PDO::getAvailableDrivers() as $driver)
	{
		if (is_dir('database/' . $driver))
		{
			$typeArray[$driver] = $driver;
		}
	};
	$dbUrlOption = '--db-url';
	$dbArray = array(
		'--db-host',
		'--db-name',
		'--db-user',
		'--db-password',
		'--db-prefix',
		'--db-salt'
	);
	$adminArray = array(
		'--admin-name',
		'--admin-user',
		'--admin-password',
		'--admin-email'
	);

	/* type */

	if (in_array($argv[1], $typeArray))
	{
		$d_type = $argv[1];
		$output .= 'Type: ' . $argv[1] . PHP_EOL;
	}

	/* parse url options */

	if ($argv[2] === $dbUrlOption)
	{
		$dbUrl = parse_url(getenv($argv[3]));
		$d_host = $dbUrl['host'];
		$d_name = trim($dbUrl['path'], '/');
		$d_user = $dbUrl['user'];
		$d_password = $dbUrl['pass'];
		$output .= 'Database host: ' . $d_host . PHP_EOL;
		$output .= 'Database name: ' . $d_name . PHP_EOL;
		$output .= 'Database user: ' . $d_user . PHP_EOL;
		$output .= 'Database password: ' . str_repeat('*', strlen($d_password)) . PHP_EOL;
	}

	/* handle db options */

	foreach ($argv as $key => $value)
	{
		if (in_array($value, $dbArray))
		{
			$suffix = str_replace('--db-', 'd_', $value);
			$$suffix = $argv[$key + 1];
			$output .= str_replace('--db-', 'Database ', $value) . ': ' . ($value === '--db-password' ? str_repeat('*', strlen($argv[$key + 1])) : $argv[$key + 1]) . PHP_EOL;
		}
	}

	/* handle admin options */

	foreach ($argv as $key => $value)
	{
		if (in_array($value, $adminArray))
		{
			$suffix = str_replace('--admin-', '', $value);
			$$suffix = $argv[$key + 1];
			$output .= str_replace('--admin-', 'Admin ', $value) . ': ' . ($value === '--admin-password' ? str_repeat('*', strlen($argv[$key + 1])) : $argv[$key + 1]) . PHP_EOL;
		}
	}

	/* write config */

	if ($argv[1])
	{
		write_config();
	}

	/* print output */

	echo $output;
}

/**
 * install post
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 */

function install_post()
{
	global $d_type, $d_host, $d_name, $d_user, $d_password, $d_prefix, $d_salt, $name, $user, $password, $email;

	/* clean post */

	$d_type = $_POST['db_type'];
	$d_host = $_POST['db_host'];
	$d_name = $_POST['db_name'];
	$d_user = $_POST['db_user'];
	$d_password = $_POST['db_password'];
	$d_prefix = $_POST['db_prefix'];
	$d_salt = $_POST['db_salt'];
	$name = $_POST['admin_name'];
	$user = $_POST['admin_user'];
	$password = $_POST['admin_password'];
	$email = $_POST['admin_email'];

	/* validate post */

	if (!$d_type)
	{
		$d_type = 'mysql';
	}
	if (!$d_host)
	{
		$d_host = 'localhost';
	}
	if (!$user)
	{
		$user = 'admin';
	}
	if (!$password)
	{
		$password = uniqid();
	}

	/* write config */

	if ($_POST['Redaxscript\View\InstallForm'])
	{
		write_config();
	}
}

/**
 * write config
 *
 * @since 2.4.0
 * @deprecated 2.4.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 */

function write_config()
{
	global $d_type, $d_host, $d_name, $d_user, $d_password, $d_prefix, $d_salt;

	$config = Redaxscript\Config::getInstance();
	$config->set('dbType', $d_type);
	$config->set('dbHost', $d_host);
	$config->set('dbName', $d_name);
	$config->set('dbUser', $d_user);
	$config->set('dbPassword', $d_password);
	$config->set('dbPrefix', $d_prefix);
	$config->set('dbSalt', $d_salt);
	$config->write();
}

/**
 * install status
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 */

function install_notification()
{
	global $d_type, $d_host, $d_name, $d_user, $d_password, $name, $user, $password, $email;

	$registry = Redaxscript\Registry::getInstance();

	if (!is_writable('config.php'))
	{
		$error = Redaxscript\Language::get('file_permission_grant') . Redaxscript\Language::get('colon') . ' config.php';
	}
	else if (!$registry->get('dbStatus'))
	{
		$error = Redaxscript\Language::get('database_failed');
		if ($registry->get('dbException'))
		{
			$error .= Redaxscript\Language::get('colon') . ' ' . $registry->get('dbException');
		}
	}

	/* validate post */

	else if ($_POST['Redaxscript\View\InstallForm'])
	{
		$loginValidator = new Redaxscript\Validator\Login();
		$emailValidator = new Redaxscript\Validator\Email();
		if ($d_type != 'sqlite' && !$name)
		{
			$error = Redaxscript\Language::get('name_empty');
		}
		else if ($d_type != 'sqlite' && !$user)
		{
			$error = Redaxscript\Language::get('user_empty');
		}
		else if ($d_type != 'sqlite' && !$password)
		{
			$error = Redaxscript\Language::get('password_empty');
		}
		else if (!$email)
		{
			$error = Redaxscript\Language::get('email_empty');
		}
		else if ($loginValidator->validate($user) == Redaxscript\Validator\ValidatorInterface::FAILED)
		{
			$error = Redaxscript\Language::get('user_incorrect');
		}
		else if ($loginValidator->validate($password) == Redaxscript\Validator\ValidatorInterface::FAILED)
		{
			$error = Redaxscript\Language::get('password_incorrect');
		}
		else if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
		{
			$error = Redaxscript\Language::get('email_incorrect');
		}
	}

	/* handle error */

	if ($error)
	{
		$output = '<div class="rs-box-note rs-note-error">' . $error . Redaxscript\Language::get('point') . '</div>';
	}

	/* handle success */

	else if (check_install() == 1)
	{
		$output = '<div class="rs-box-note rs-note-success">' . Redaxscript\Language::get('installation_completed') . Redaxscript\Language::get('point') . '</div>';
	}
	echo $output;
}

/**
 * check install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 *
 * @return integer
 */

function check_install()
{
	global $name, $user, $password, $email;

	$registry = Redaxscript\Registry::getInstance();
	$loginValidator = new Redaxscript\Validator\Login();
	$emailValidator = new Redaxscript\Validator\Email();
	if ($_POST['Redaxscript\View\InstallForm'] && $registry->get('dbStatus') && $name && $loginValidator->validate($user) == Redaxscript\Validator\ValidatorInterface::PASSED && $loginValidator->validate($password) == Redaxscript\Validator\ValidatorInterface::PASSED && $emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::PASSED)
	{
		$output = 1;
	}
	else
	{
		$output = 0;
	}
	return $output;
}

/**
 * head
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 */

function head()
{
	$output = '<base href="' . Redaxscript\Registry::get('root') . '/" />' . PHP_EOL;
	$output .= '<title>' . Redaxscript\Language::get('installation') . '</title>' . PHP_EOL;
	$output .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . PHP_EOL;
	if (check_install() == 1)
	{
		$output .= '<meta http-equiv="refresh" content="2; url=' . Redaxscript\Registry::get('root') . '" />' . PHP_EOL;
	}
	$output .= '<meta name="generator" content="' . Redaxscript\Language::get('name', '_package') . ' ' . Redaxscript\Language::get('version', '_package') . '" />' . PHP_EOL;
	$output .= '<meta name="robots" content="none" />' . PHP_EOL;
	echo $output;
}

/**
 * router
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 */

function router()
{
	global $d_type, $d_host, $d_name, $d_user, $d_password, $d_prefix, $name, $user, $password, $email;

	/* check token */

	if ($_POST && $_POST['token'] != Redaxscript\Registry::get('token'))
	{
		$output = '<div class="rs-box-note rs-note-error">' . Redaxscript\Language::get('token_incorrect') . Redaxscript\Language::get('point') . '</div>';
		echo $output;
		return;
	}

	/* router */

	install_notification();
	if (check_install() == 1)
	{
		install();
	}
	else
	{
		$installForm = new Redaxscript\View\InstallForm();
		echo $installForm->render(array(
			'dbType' => $d_type,
			'dbHost' => $d_host,
			'dbName' => $d_name,
			'dbUser' => $d_user,
			'dbPassword' => $d_password,
			'dbPrefix' => $d_prefix,
			'adminName' => $name,
			'adminUser' => $user,
			'adminPassword' => $password,
			'adminEmail' => $email
		));
	}
}
