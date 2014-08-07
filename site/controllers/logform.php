<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 */
class ProjectlogControllerLogform extends JControllerForm
{
	protected $view_item = 'logform';

	protected $view_list = 'project';

	protected $urlVar = 'a.id';

	/**
	 * Method to add a new record.
	 *
	 * @return  mixed  True if the record can be added, a error object if not.
	 *
	 * @since   3.3.1
	 */
	public function add()
	{
		if (!parent::add())
		{
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());
		}
	}
    
    /**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   3.3.1
	 */
	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$projectId = JArrayHelper::getValue($data, 'project_id', $this->input->getInt('filter_project_id'), 'int');
		$allow = null;

		if ($projectId)
		{
			// If the category has been passed in the URL check it.
			$allow = $user->authorise('projectlog.createlog', $this->option . '.project.' . $projectId);
		}

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		}
		else
		{
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   3.3.1
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$projectId = 0;

		if ($recordId)
		{
			$projectId = (int) $this->getModel()->getItem($recordId)->project_id;
		}

		if ($projectId)
		{
			// The project has been set. Check the project permissions.
			return JFactory::getUser()->authorise('projectlog.editlog', $this->option . '.project.' . $projectId);
		}
		else
		{
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   3.3.1
	 */
	public function cancel($key = 'a_id')
	{
		parent::cancel($key);

		// Redirect to the return page.
		$this->setRedirect($this->getReturnPage());
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key
	 * (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   3.3.1
	 */
	public function edit($key = null, $urlVar = 'a_id')
	{
		$result = parent::edit($key, $urlVar);

		if (!$result)
		{
			$this->setRedirect($this->getReturnPage());
		}

		return $result;
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   3.3.1
	 */
	public function getModel($name = 'logform', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string	The arguments to append to the redirect URL.
	 *
	 * @since   3.3.1
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'a_id')
	{
		// Need to override the parent method completely.
		$tmpl   = $this->input->get('tmpl');
		$append = '';

		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl='.$tmpl;
		}

		$append .= '&layout=edit';

		if ($recordId)
		{
			$append .= '&'.$urlVar.'='.$recordId;
		}

		$itemId	= $this->input->getInt('Itemid');
		$return	= $this->getReturnPage();
		$catId  = $this->input->getInt('catid', null, 'get');

		if ($itemId)
		{
			$append .= '&Itemid='.$itemId;
		}

		if ($catId)
		{
			$append .= '&catid='.$catId;
		}

		if ($return)
		{
			$append .= '&return='.base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string	The return URL.
	 *
	 * @since   3.3.1
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JUri::base();
		}
		else
		{
			return base64_decode($return);
		}
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model  The data model object.
	 * @param   array         $validData   The validated data.
	 *
	 * @return  void
	 *
	 * @since   3.3.1
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		return;
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   3.3.1
	 */
	public function save($key = null, $urlVar = 'a_id')
	{
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page.
		if ($result)
		{
			$this->setRedirect($this->getReturnPage());
		}

		return $result;
	}
}
