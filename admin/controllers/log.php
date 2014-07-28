<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Controller for a single project
 *
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogControllerLog extends JControllerForm
{
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
			// The category has been set. Check the category permissions.
			return JFactory::getUser()->authorise('projectlog.editlog', $this->option . '.project.' . $projectId);
		}
		else
		{
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   3.3.1
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Log', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_projectlog&view=logs' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return  void
	 * @since   3.3.1
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
	}   
}
