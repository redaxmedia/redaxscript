<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;

/**
 * children class to process the admin module request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Module extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 4.0.0
	 *
	 * @param string $action action to process
	 *
	 * @return string
	 */

	public function process(string $action = null) : string
	{
		$postArray = $this->_normalizePost($this->_sanitizePost());
		$validateArray = $this->_validatePost($postArray);

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => $this->_getErrorRoute($postArray),
				'message' => $validateArray
			]);
		}

		/* handle update */

		if ($action === 'update')
		{
			$updateArray =
			[
				'name' => $postArray['name'],
				'description' => $postArray['description'],
				'status' => $postArray['status'],
				'access' => $postArray['access']
			];
			if ($this->_update($postArray['id'], $updateArray))
			{
				return $this->_success(
				[
					'route' => $this->_getSuccessRoute($postArray),
					'timeout' => 2
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => $this->_getErrorRoute($postArray)
		]);
	}

	/**
	 * sanitize the post
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizePost() : array
	{
		$numberFilter = new Filter\Number();

		/* sanitize post */

		return
		[
			'id' => $numberFilter->sanitize($this->_request->getPost('id')),
			'name' => $this->_request->getPost('name'),
			'description' => $this->_request->getPost('description'),
			'status' => $numberFilter->sanitize($this->_request->getPost('status')),
			'access' => json_encode($this->_request->getPost('access'))
		];
	}

	/**
	 * validate the post
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validatePost(array $postArray = []) : array
	{
		$validateArray = [];

		/* validate post */

		if (!$postArray['name'])
		{
			$validateArray[] = $this->_language->get('name_empty');
		}
		return $validateArray;
	}

	/**
	 * update the module
	 *
	 * @since 4.0.0
	 *
	 * @param int $moduleId identifier of the module
	 * @param array $updateArray array of the update
	 *
	 * @return bool
	 */

	public function _update(int $moduleId = null, array $updateArray = []) : bool
	{
		$moduleModel = new Admin\Model\Module();
		return $moduleModel->updateByIdAndArray($moduleId, $updateArray);
	}

	/**
	 * get success route
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return string
	 */

	protected function _getSuccessRoute(array $postArray = []) : string
	{
		if ($this->_registry->get('modulesEdit') && $postArray['id'])
		{
			return 'admin/view/modules#row-' . $postArray['id'];
		}
		return 'admin';
	}

	/**
	 * get error route
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return string
	 */

	protected function _getErrorRoute(array $postArray = []) : string
	{
		if ($this->_registry->get('modulesEdit'))
		{
			if ($postArray['id'])
			{
				return 'admin/edit/modules/' . $postArray['id'];
			}
			return 'admin/view/modules';
		}
		return 'admin';
	}
}
