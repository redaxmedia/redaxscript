<?php
error_reporting(0);

/* include core files */

include_once('includes/generate.php');
include_once('includes/loader.php');
include_once('includes/migrate.php');
include_once('includes/password.php');
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

	/* define meta */

	define('TITLE', l('installation'));
	define('ROBOTS', 'none');

	/* module init */

	Redaxscript\Hook::trigger('init');

	/* call loader else render template */

	if (FIRST_PARAMETER == 'loader' && (SECOND_PARAMETER == 'styles' || SECOND_PARAMETER == 'scripts'))
	{
		echo loader(SECOND_PARAMETER, 'outline');
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

	$installer = new Redaxscript\Installer();
	$installer->init(Redaxscript\Config::getInstance());
	$installer->rawDrop();
	$installer->rawCreate();
	$installer->insertData(array(
		'adminName' => $name,
		'adminUser' => $user,
		'adminPassword' => $password,
		'adminEmail' => $email
	));

	/* send login information */

	$urlLink = anchor_element('external', '', '', ROOT, ROOT);
	$toArray = $fromArray = array(
		$name => $email
	);
	$subject = l('installation');
	$bodyArray = array(
		'<strong>' . l('user') . l('colon') . '</strong> ' . $user,
		'<br />',
		'<strong>' . l('password') . l('colon') . '</strong> ' . $password,
		'<br />',
		'<strong>' . l('url') . l('colon') . '</strong> ' . $urlLink
	);

	/* mailer object */

	$mailer = new Redaxscript\Mailer();
	$mailer->init($toArray, $fromArray, $subject, $bodyArray);
	$mailer->send();
}

/**
 * install form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 */

function install_form()
{
	global $d_type, $d_host, $d_name, $d_user, $d_password, $d_prefix, $name, $user, $password, $email;

	/* build type array */

	$typeArray = array();
	foreach (PDO::getAvailableDrivers() as $driver)
	{
		if (is_dir('database/' . $driver))
		{
			$typeArray[$driver] = $driver;
		}
	};

	/* collect output */

	$output = '<h2 class="title_content">' . l('installation') . '</h2>';
	$output .= form_element('form', 'form_install', 'js_validate_form js_accordion form_default', '', '', '', 'action="' . FILE . '" method="post" autocomplete="off"');

	/* collect database set */

	$output .= '<fieldset class="js_set_accordion js_set_active set_accordion set_accordion_default set_active">';
	$output .= '<legend class="js_title_accordion js_title_active title_accordion title_accordion_default title_active">' . l('database_setup') . '</legend>';
	$output .= '<ul class="js_box_accordion js_box_active box_accordion box_accordion_default box_active">';
	if ($typeArray)
	{
		$output .= '<li>' . select_element('type', 'field_select', 'd_type', $typeArray, $d_type, l('type')) . '</li>';
	}
	$output .= '<li>' . form_element('text', 'd_host', 'field_text field_note', 'd_host', $d_host, '* ' . l('host'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
	$output .= '<li>' . form_element('text', 'd_name', 'field_text field_note', 'd_name', $d_name, '* ' . l('name'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'd_user', 'field_text field_note', 'd_user', $d_user, '* ' . l('user'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('password', 'd_password', 'js_unmask_password field_text', 'd_password', $d_password, l('password'), 'maxlength="50"') . '</li>';
	$output .= '<li>' . form_element('text', 'd_prefix', 'field_text', 'd_prefix', $d_prefix, l('prefix'), 'maxlength="50"') . '</li>';
	$output .= '</ul></fieldset>';

	/* collect account set */

	$output .= '<fieldset class="js_set_accordion js_set_accordion_last set_accordion set_accordion_default set_accordion_last">';
	$output .= '<legend class="js_title_accordion title_accordion title_accordion_default">' . l('account_create') . '</legend>';
	$output .= '<ul class="js_box_accordion box_accordion box_accordion_default">';
	$output .= '<li>' . form_element('text', 'name', 'field_text field_note', 'name', $name, '* ' . l('name'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('text', 'user', 'field_text field_note', 'user', $user, '* ' . l('user'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('password', 'password', 'js_unmask_password field_text field_note', 'password', $password, '* ' . l('password'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('email', 'email', 'field_text field_note', 'email', $email, '* ' . l('email'), 'maxlength="50" required="required"') . '</li>';
	$output .= '</ul></fieldset>';

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'd_salt', hash_generator(40));
	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'js_submit button_default button_large', 'install_post', l('install'));
	$output .= '</form>';
	echo $output;
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

	$output = '';
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
		$output .= 'Database password: ' . $d_password . PHP_EOL;
	}

	/* handle db options */

	foreach ($argv as $key => $value)
	{
		if (in_array($value, $dbArray))
		{
			$suffix = str_replace('--db-', 'd_', $value);
			$$suffix = $argv[$key + 1];
			$output .=  str_replace('--db-', 'Database ', $value) . ': ' . ($value === '--db-password' ? str_repeat('*', strlen($argv[$key + 1])) : $argv[$key + 1])  . PHP_EOL;
		}
	}

	/* handle admin options */

	foreach ($argv as $key => $value)
	{
		if (in_array($value, $adminArray))
		{
			$suffix = str_replace('--admin-', '', $value);
			$$suffix = $argv[$key + 1];
			$output .=  str_replace('--admin-', 'Admin ', $value) . ': ' . ($value === '--admin-password' ? str_repeat('*', strlen($argv[$key + 1])) : $argv[$key + 1]) . PHP_EOL;
		}
	}

	/* write config */

	if (isset($argv[1]))
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

	$d_type = stripslashes($_POST['d_type']);
	$d_host = stripslashes($_POST['d_host']);
	$d_name = stripslashes($_POST['d_name']);
	$d_user = stripslashes($_POST['d_user']);
	$d_password = stripslashes($_POST['d_password']);
	$d_prefix = stripslashes($_POST['d_prefix']);
	$d_salt = stripslashes($_POST['d_salt']);
	$name = stripslashes($_POST['name']);
	$user = stripslashes($_POST['user']);
	$password = stripslashes($_POST['password']);
	$email = stripslashes($_POST['email']);

	/* validate post */

	if ($d_type == '')
	{
		$d_type = 'mysql';
	}
	if ($d_host == '')
	{
		if ($d_type == 'sqlite')
		{
			$d_host = 'database.sqlite';
		}
		else
		{
			$d_host = 'localhost';
		}
	}
	if ($user == '')
	{
		$user = 'admin';
	}
	if ($password == '')
	{
		$password = substr(sha1(uniqid()), 0, 10);
	}

	/* write config */

	if ($_POST['install_post'])
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

	if (is_writable('config.php') == '')
	{
		$error = l('file_permission_grant') . l('colon') . ' config.php';
	}
	else if (!$registry->get('dbStatus'))
	{
		$error = l('database_failed');
		if ($registry->get('dbException'))
		{
			$error .= l('colon') . ' ' . $registry->get('dbException');
		}
	}

	/* validate post */

	else if ($_POST['install_post'])
	{
		$loginValidator = new Redaxscript\Validator\Login();
		$emailValidator = new Redaxscript\Validator\Email();
		if ($d_type != 'sqlite' && $name == '')
		{
			$error = l('name_empty');
		}
		else if ($d_type != 'sqlite' && $user == '')
		{
			$error = l('user_empty');
		}
		else if ($d_type != 'sqlite' && $password == '')
		{
			$error = l('password_empty');
		}
		else if ($email == '')
		{
			$error = l('email_empty');
		}
		else if ($loginValidator->validate($user) == Redaxscript\Validator\ValidatorInterface::FAILED)
		{
			$error = l('user_incorrect');
		}
		else if ($loginValidator->validate($password) == Redaxscript\Validator\ValidatorInterface::FAILED)
		{
			$error = l('password_incorrect');
		}
		else if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
		{
			$error = l('email_incorrect');
		}
	}

	/* handle error */

	if ($error)
	{
		$output = '<div class="box_note note_error">' . $error . l('point') . '</div>';
	}

	/* handle success */

	else if (check_install() == 1)
	{
		$output = '<div class="box_note note_success">' . l('installation_completed') . l('point') . '</div>';
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
	if ($_POST['install_post'] && $registry->get('dbStatus') && $name && $loginValidator->validate($user) == Redaxscript\Validator\ValidatorInterface::PASSED && $loginValidator->validate($password) == Redaxscript\Validator\ValidatorInterface::PASSED && $emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::PASSED)
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
	$output = '<base href="' . ROOT . '/" />' . PHP_EOL;
	$output .= '<title>' . TITLE . '</title>' . PHP_EOL;
	$output .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . PHP_EOL;
	if (check_install() == 1)
	{
		$output .= '<meta http-equiv="refresh" content="2; url=' . ROOT . '" />' . PHP_EOL;
	}
	$output .= '<meta name="generator" content="' . l('name', '_package') . ' ' . l('version', '_package') . '" />' . PHP_EOL;
	$output .= '<meta name="robots" content="' . ROBOTS . '" />' . PHP_EOL;
	echo $output;
}

/**
 * center
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Install
 * @author Henry Ruhs
 */

function center()
{
	/* check token */

	if ($_POST && $_POST['token'] != TOKEN)
	{
		$output = '<div class="box_note note_error">' . l('token_incorrect') . l('point') . '</div>';
		echo $output;
		return;
	}

	/* routing */

	install_notification();
	if (check_install() == 1)
	{
		install();
	}
	else
	{
		install_form();
	}
}
