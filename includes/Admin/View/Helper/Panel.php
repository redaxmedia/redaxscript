<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Validator;
use function array_key_exists;
use function array_replace_recursive;

/**
 * helper class to create the admin panel
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Panel extends Admin\View\ViewAbstract
{
	/**
	 * options of the panel
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' =>
			[
				'panel' => 'rs-admin-fn-dropdown rs-admin-list-panel',
				'content' => 'rs-admin-fn-content-panel rs-admin-list-panel-children',
				'access' => 'rs-admin-fn-content-panel rs-admin-list-panel-children',
				'system' => 'rs-admin-fn-content-panel rs-admin-list-panel-children',
				'notification' => 'rs-admin-fn-content-panel rs-admin-list-panel-children rs-admin-list-notification'
			],
			'item' =>
			[
				'content' => 'rs-admin-item-panel',
				'access' => 'rs-admin-item-panel',
				'system' => 'rs-admin-item-panel',
				'profile' => 'rs-admin-item-panel',
				'notification' => 'rs-admin-item-panel',
				'logout' => 'rs-admin-item-panel rs-admin-item-panel-logout'
			],
			'link' =>
			[
				'view' => 'rs-admin-link-panel rs-admin-link-panel-view',
				'new' => 'rs-admin-link-panel rs-admin-link-panel-new',
				'system' => 'rs-admin-link-panel rs-admin-link-panel-system',
				'profile' => 'rs-admin-link-panel rs-admin-link-panel-profile',
				'logout' => 'rs-admin-link-panel rs-admin-link-panel-logout'
			],
			'label' =>
			[
				'content' => 'rs-admin-fn-toggle-panel rs-admin-label-panel rs-admin-label-panel-content',
				'access' => 'rs-admin-fn-toggle-panel rs-admin-label-panel rs-admin-label-panel-access',
				'system' => 'rs-admin-fn-toggle-panel rs-admin-label-panel rs-admin-label-panel-system',
				'notification' => 'rs-admin-fn-toggle-panel rs-admin-label-panel rs-admin-label-panel-notification'
			],
			'note' =>
			[
				'success' => 'rs-admin-is-success',
				'warning' => 'rs-admin-is-warning',
				'error' => 'rs-admin-is-error',
				'info' => 'rs-admin-is-info'
			],
			'text' => 'rs-admin-text-panel-group',
			'input' => 'rs-admin-fn-status-panel',
			'sup' => 'rs-admin-sup-panel-notification'
		]
	];

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the panel
	 *
	 * @return self
	 */

	public function init(array $optionArray = []) : self
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
		return $this;
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('adminPanelStart');
		$outputItem = null;

		/* html element */

		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => $this->_optionArray['className']['list']['panel']
		]);

		/* collect item output */

		if ($this->_hasPermission('contents'))
		{
			$outputItem .= $this->_renderContent();
		}
		if ($this->_hasPermission('access'))
		{
			$outputItem .= $this->_renderAccess();
		}
		if ($this->_hasPermission('system'))
		{
			$outputItem .= $this->_renderSystem();
		}
		if ($this->_hasPermission('profile'))
		{
			$outputItem .= $this->_renderProfile();
		}
		if ($this->_hasPermission('notification'))
		{
			$outputItem .= $this->_renderNotification();
		}
		$outputItem .= $this->_renderLogout();

		/* collect output */

		$output .= $listElement->append($outputItem);
		$output .= Module\Hook::trigger('adminPanelEnd');
		return $output;
	}

	/**
	 * has the permission
	 *
	 * @since 4.0.0
	 *
	 * @param string $type
	 *
	 * @return bool
	 */

	protected function _hasPermission(string $type = null) : bool
	{
		$permissionArray = [];
		$accessValidator = new Validator\Access();
		if ($this->_registry->get('categoriesEdit'))
		{
			$permissionArray['categories'] = $permissionArray['contents'] = true;
		}
		if ($this->_registry->get('articlesEdit'))
		{
			$permissionArray['articles'] = $permissionArray['contents'] = true;
		}
		if ($this->_registry->get('extrasEdit'))
		{
			$permissionArray['extras'] = $permissionArray['contents'] = true;
		}
		if ($this->_registry->get('commentsEdit'))
		{
			$permissionArray['comments'] = $permissionArray['contents'] = true;
		}
		if ($this->_registry->get('usersEdit'))
		{
			$permissionArray['users'] = $permissionArray['access'] = true;
		}
		if ($this->_registry->get('groupsEdit'))
		{
			$permissionArray['groups'] = $permissionArray['access'] = true;
		}
		if ($this->_registry->get('modulesEdit'))
		{
			$permissionArray['modules'] = $permissionArray['system'] = true;
		}
		if ($this->_registry->get('settingsEdit'))
		{
			$permissionArray['settings'] = $permissionArray['system'] = true;
		}
		if ($this->_registry->get('myId'))
		{
			$permissionArray['profile'] = true;
		}
		if ($accessValidator->validate(1, $this->_registry->get('myGroups')))
		{
			$permissionArray['notification'] = true;
		}
		return array_key_exists($type, $permissionArray);
	}

	/**
	 * render the content
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	protected function _renderContent() : ?string
	{
		$output = null;
		$parameterRoute = $this->_registry->get('parameterRoute');
		$contentArray =
		[
			'categories',
			'articles',
			'extras',
			'comments'
		];

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_optionArray['className']['list']['content']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');
		$textElement = $element
			->copy()
			->init('span',
			[
				'class' => $this->_optionArray['className']['text']
			]);
		$labelElement = $element
			->copy()
			->init('label',
			[
				'class' => $this->_optionArray['className']['label']['content'],
				'for' => self::class . '\Content'
			])
			->text($this->_language->get('contents'));
		$inputElement = $element
			->copy()
			->init('input',
			[
				'id' => self::class . '\Content',
				'class' => $this->_optionArray['className']['input'],
				'type' => 'radio',
				'name' => self::class . '\Panel'
			]);

		/* process contents */

		foreach ($contentArray as $type)
		{
			$tableNew = $this->_registry->get($type . 'New');
			if ($this->_hasPermission($type))
			{
				$listElement->append(
					$itemElement
						->copy()
						->html(
							$textElement
								->copy()
								->append(
									$linkElement
										->copy()
										->addClass($this->_optionArray['className']['link']['view'])
										->attr('href', $parameterRoute . 'admin/view/' . $type)
										->text($this->_language->get($type))
								)
								->append($tableNew ? $linkElement
									->copy()
									->addClass($this->_optionArray['className']['link']['new'])
									->attr('href', $parameterRoute . 'admin/new/' . $type)
									->text($this->_language->get('new')) : null
								)
						)
				);
			}
		}

		/* collect output */

		$output .= $itemElement
			->copy()
			->addClass($this->_optionArray['className']['item']['content'])
			->html($inputElement . $labelElement . $listElement);
		return $output;
	}

	/**
	 * render the access
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	protected function _renderAccess() : ?string
	{
		$output = null;
		$parameterRoute = $this->_registry->get('parameterRoute');
		$accessArray =
		[
			'users',
			'groups'
		];

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_optionArray['className']['list']['access']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');
		$textElement = $element
			->copy()
			->init('span',
			[
				'class' => $this->_optionArray['className']['text']
			]);
		$labelElement = $element
			->copy()
			->init('label',
			[
				'class' => $this->_optionArray['className']['label']['access'],
				'for' => self::class . '\Access'
			])
			->text($this->_language->get('access'));
		$inputElement = $element
			->copy()
			->init('input',
			[
				'id' => self::class . '\Access',
				'class' => $this->_optionArray['className']['input'],
				'type' => 'radio',
				'name' => self::class . '\Panel'
			]);

		/* process access */

		foreach ($accessArray as $type)
		{
			$tableNew = $this->_registry->get($type . 'New');
			if ($this->_hasPermission($type))
			{
				$listElement->append(
					$itemElement
						->copy()
						->html(
							$textElement
								->copy()
								->append(
									$linkElement
										->copy()
										->addClass($this->_optionArray['className']['link']['view'])
										->attr('href', $parameterRoute . 'admin/view/' . $type)
										->text($this->_language->get($type))
								)
								->append($tableNew ? $linkElement
									->copy()
									->addClass($this->_optionArray['className']['link']['new'])
									->attr('href', $parameterRoute . 'admin/new/' . $type)
									->text($this->_language->get('new')) : null
								)
						)
				);
			}
		}

		/* collect output */

		$output .= $itemElement
			->copy()
			->addClass($this->_optionArray['className']['item']['access'])
			->html($inputElement . $labelElement . $listElement);
		return $output;
	}

	/**
	 * render the system
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	protected function _renderSystem() : ?string
	{
		$output = null;
		$parameterRoute = $this->_registry->get('parameterRoute');
		$systemArray =
		[
			'modules',
			'settings'
		];

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_optionArray['className']['list']['system']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => $this->_optionArray['className']['link']['system'],
			]);
		$labelElement = $element
			->copy()
			->init('label',
			[
				'class' => $this->_optionArray['className']['label']['system'],
				'for' => self::class . '\System'
			])
			->text($this->_language->get('system'));
		$inputElement = $element
			->copy()
			->init('input',
			[
				'id' => self::class . '\System',
				'class' => $this->_optionArray['className']['input'],
				'type' => 'radio',
				'name' => self::class . '\Panel'
			]);

		/* process system */

		foreach ($systemArray as $type)
		{
			if ($this->_hasPermission($type))
			{
				$listElement->append(
					$itemElement
						->copy()
						->html(
							$linkElement
								->copy()
								->attr('href', $type === 'settings' ? $parameterRoute . 'admin/edit/settings' : $parameterRoute . 'admin/view/' . $type)
								->text($this->_language->get($type))
						)
				);
			}
		}

		/* collect output */

		$output .= $itemElement
			->copy()
			->addClass($this->_optionArray['className']['item']['system'])
			->html($inputElement . $labelElement . $listElement);
		return $output;
	}

	/**
	 * render the profile
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	protected function _renderProfile() : string
	{
		$parameterRoute = $this->_registry->get('parameterRoute');
		$myId = $this->_registry->get('myId');

		/* html element */

		$element = new Html\Element();
		$itemElement = $element
			->copy()
			->init('li',
			[
				'class' => $this->_optionArray['className']['item']['profile']
			]);
		$linkElement = $element
			->copy()
			->init('a',
			[
				'href' => $parameterRoute . 'admin/edit/users/' . $myId,
				'class' => $this->_optionArray['className']['link']['profile']
			])
			->text($this->_language->get('profile'));

		/* collect item output */

		$output = $itemElement->html($linkElement);
		return $output;
	}

	/**
	 * render the notification
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	protected function _renderNotification() : ?string
	{
		$output = null;
		$adminNotification = new Notification($this->_language);
		$adminNotification->init(
		[
			'className' =>
			[
				'list' => $this->_optionArray['className']['list']['notification']
			]
		]);
		$metaArray = $adminNotification->getMetaArray();

		/* html element */

		$element = new Html\Element();
		$itemElement = $element
			->copy()
			->init('li',
			[
				'class' => $this->_optionArray['className']['item']['notification']
			]);
		$labelElement = $element
			->copy()
			->init('label',
			[
				'class' => $this->_optionArray['className']['label']['notification'],
				'for' => self::class . '\Notification'
			])
			->text($this->_language->get('notification'));
		$inputElement = $element
			->copy()
			->init('input',
			[
				'id' => self::class . '\Notification',
				'class' => $this->_optionArray['className']['input'],
				'type' => 'radio',
				'name' => self::class . '\Panel',
				'checked' => 'checked'
			]);
		$supElement = $element
			->copy()
			->init('sup',
			[
				'class' => $this->_optionArray['className']['sup']
			])
			->text($metaArray['total']);

		/* process meta */

		foreach ($metaArray as $key => $value)
		{
			$supElement->addClass($this->_optionArray['className']['note'][$key]);
		}
		$labelElement->append($supElement);

		/* collect item output */

		if ($metaArray['total'])
		{
			$output = $itemElement->html($inputElement . $labelElement . $adminNotification->render());
		}
		return $output;
	}

	/**
	 * render the logout
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	protected function _renderLogout() : string
	{
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html element */

		$element = new Html\Element();
		$itemElement = $element
			->copy()
			->init('li',
			[
				'class' => $this->_optionArray['className']['item']['logout']
			]);
		$linkElement = $element
			->copy()
			->init('a',
			[
				'href' => $parameterRoute . 'logout',
				'class' => $this->_optionArray['className']['link']['logout']
			])
			->text($this->_language->get('logout'));

		/* collect item output */

		$output = $itemElement->html($linkElement);
		return $output;
	}
}
