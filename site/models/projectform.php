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
require_once JPATH_ADMINISTRATOR.'/components/com_projectlog/models/project.php';

/**
 * This models extends admin project model for front end project management
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogModelProjectform extends ProjectlogModelProject
{
	/**
	 * Model typeAlias string. Used for version history.
	 *
	 * @var        string
	 */
	public $typeAlias = 'com_projectlog.project';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   3.3.1
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();

		// Load state from the request.
		$pk = $app->input->getInt('a_id');
		$this->setState('project.id', $pk);	

		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params	= $app->getParams();
		$this->setState('params', $params);
        
        // Automatically set the catid if creating a new project directly from category view
        // Fall back to menu item parameters catid if it exists
        $this->setState('project.catid', ($app->input->getInt('catid')) ? $app->input->getInt('catid') : $params->get('catid'));

		$this->setState('layout', $app->input->getString('layout'));
	}

	/**
	 * Method to get project data.
	 *
	 * @param   integer  $itemId  The id of the project.
	 *
	 * @return  mixed  Content item data object on success, false on failure.
	 */
	public function getItem($itemId = null)
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('project.id');

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
		$asset	= 'com_projectlog.project.' . $value->id;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset))
		{
			$value->params->set('access-edit', true);
		}

		// Now check if edit.own is available.
		elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
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
			$value->params->set('access-change', $user->authorise('core.edit.state', $asset));
		}
		else
		{
			// New item.
			$catId = (int) $this->getState('project.catid');

			if ($catId)
			{
				$value->params->set('access-change', $user->authorise('core.edit.state', 'com_projectlog.category.' . $catId));
				$value->catid = $catId;
			}
			else
			{
				$value->params->set('access-change', $user->authorise('core.edit.state', 'com_projectlog'));
			}
		}

		$value->projecttext = $value->misc;

		if (!empty($value->fulltext))
		{
			$value->projecttext .= '<hr id="system-readmore" />' . $value->fulltext;
		}

		// Convert the metadata field to an array.
		$registry = new JRegistry;
		$registry->loadString($value->metadata);
		$value->metadata = $registry->toArray();

		if ($itemId)
		{
			$value->tags = new JHelperTags;
			$value->tags->getTagIds($value->id, 'com_projectlog.project');
			$value->metadata['tags'] = $value->tags;
		}

		return $value;
	}
    
    /**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   3.3.1
	 */
	protected function loadFormData()
	{
        $data = parent::loadFormData();

        // Prime some default values.
        if ($this->getState('project.id') == 0)
        {
            $app = JFactory::getApplication();
            $data->set('manager', JFactory::getUser()->get('id'), 'int');				
        }

		return $data;
	}

	/**
	 * Get the return URL.
	 *
	 * @return  string	The return URL.
	 *
	 * @since   3.3.1
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
	 * @since   3.3.1
	 */
	public function save($data)
	{
		// Associations are not edited in frontend ATM so we have to inherit them
		if (JLanguageAssociations::isEnabled() && !empty($data['id']))
		{
			if ($associations = JLanguageAssociations::getAssociations('com_projectlog', '#__projectlog_projects', 'com_projectlog.project', $data['id']))
			{
				foreach ($associations as $tag => $associated)
				{
					$associations[$tag] = (int) $associated->id;
				}

				$data['associations'] = $associations;
			}
		}

		if (parent::save($data))
        {
            $plparams = JComponentHelper::getParams('com_projectlog');
            if($plparams->get('project_notify') == 1 && $this->getState($this->getName().'.new')){
                $project_id = $this->getState($this->getName().'.id');
                projectlogHtml::notifyAdmin($project_id, 'project');
            }
            return true;
        }
        
        return false;
	}
}
