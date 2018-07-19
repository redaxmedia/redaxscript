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
				'route' => $postArray['id'] ? 'admin/edit/modules/' . $postArray['id'] : 'admin/view/modules',
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
			if ($this->_update($postArray['module'], $updateArray))
			{
				return $this->_success(
				[
					'route' => 'admin/view/modules#' . $postArray['alias'],
					'timeout' => 2,
					'message' => $this->_language->get('operation_completed')
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => $postArray['id'] ? 'admin/edit/modules/' . $postArray['id'] : 'admin/view/modules',
			'message' => $this->_language->get('something_wrong')
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
		$specialFilter = new Filter\Special();

		/* sanitize post */

		return
		[
			'id' => $specialFilter->sanitize($this->_request->getPost('id')),
			'name' => $this->_request->getPost('name'),
			'description' => $this->_request->getPost('description'),
			'status' => $specialFilter->sanitize($this->_request->getPost('status')),
			'access' => $specialFilter->sanitize($this->_request->getPost('access'))
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
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(int $moduleId = null, array $updateArray = []) : bool
	{
		$moduleModel = new Admin\Model\Module();
		return $moduleModel->updateByIdAndArray($moduleId, $updateArray);
	}
}
