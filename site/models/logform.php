<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Base this model on the backend version.
require_once JPATH_ADMINISTRATOR.'/components/com_projectlog/models/log.php';

/**
 * Projectlog Component Logform Model
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 */
class ProjectlogModelLogform extends ProjectlogModelLog
{
	/**
	 * Model typeAlias string. Used for version history.
	 *
	 * @var        string
	 */
	public $typeAlias = 'com_projectlog.log';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();

		// Load state from the request.
		$pk = $app->input->getInt('a_id');
		$this->setState('log.id', $pk);

		$this->setState('log.project_id', $app->input->getInt('project_id'));

		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params	= $app->getParams();
		$this->setState('params', $params);

		$this->setState('layout', $app->input->getString('layout'));
	}

	/**
	 * Method to get item data.
	 *
	 * @param   integer  $itemId  The id of the item.
	 *
	 * @return  mixed  Content item data object on success, false on failure.
	 */
	public function getItem($itemId = null)
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('log.id');

		// Get a row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		$return = $table->load($itemId);

		// Check for a table object error.
		if ($return === false && $table->getError())
		{
			$this->setError($table->getError());

			return false;
		}

		$properties = $table->getProperties(1);
		$value = JArrayHelper::toObject($properties, 'JObject');

		// Convert params field to Registry.
		$value->params = new JRegistry;

		// Compute selected asset permissions.
		$user	= JFactory::getUser();
		$userId	= $user->get('id');
		$asset	= 'com_projectlog.project.' . $value->project_id;

		// Check general edit permission first.
		if ($user->authorise('projectlog.createlog', $asset))
		{
			$value->params->set('access-edit', true);
		}

		// Now check if edit.own is available.
		elseif (!empty($userId) && $user->authorise('projectlog.editlog.own', $asset))
		{
			// Check for a valid user and that they are the owner.
			if ($userId == $value->created_by)
			{
				$value->params->set('access-edit', true);
			}
		}

		// Check edit state permission.
		if ($itemId)
		{
			// Existing item
			$value->params->set('access-change', $user->authorise('projectlog.editlog.state', $asset));
		}
		else
		{
			// New item.
			$project_id = (int) $this->getState('log.project_id');

			if ($project_id)
			{
				$value->params->set('access-change', $user->authorise('projectlog.editlog.state', 'com_projectlog.project.' . $project_id));
				$value->project_id = $project_id;
			}
			else
			{
				$value->params->set('access-change', $user->authorise('projectlog.editlog.state', 'com_projectlog'));
			}
		}

		return $value;
	}

	/**
	 * Get the return URL.
	 *
	 * @return  string	The return URL.
	 *
	 * @since   1.6
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.2
	 */
	public function save($data)
	{
		// Associations are not edited in frontend ATM so we have to inherit them
		if (JLanguageAssociations::isEnabled() && !empty($data['id']))
		{
			if ($associations = JLanguageAssociations::getAssociations('com_projectlog', '#__projectlog_logs', 'com_projectlog.log', $data['id']))
			{
				foreach ($associations as $tag => $associated)
				{
					$associations[$tag] = (int) $associated->id;
				}

				$data['associations'] = $associations;
			}
		}

		return parent::save($data);
	}
}
