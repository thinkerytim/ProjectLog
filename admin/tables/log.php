<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 */
class ProjectlogTableLog extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  Database connector object
	 *
	 * @since   1.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__projectlog_logs', 'id', $db);

		JTableObserverTags::createObserver($this, array('typeAlias' => 'com_projectlog.log'));
		JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_projectlog.log'));
	}

	/**
	 * Stores a project
	 *
	 * @param   boolean  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();

		if ($this->id)
		{
			// Existing item
			$this->modified		= $date->toSql();
			$this->modified_by	= $user->get('id');
		}
		else
		{
			// New project. A project created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}

		// Set publish_up to null date if not set
		if (!$this->publish_up)
		{
			$this->publish_up = $this->_db->getNullDate();
		}

		// Set publish_down to null date if not set
		if (!$this->publish_down)
		{
			$this->publish_down = $this->_db->getNullDate();
		}

		// Verify that the alias is unique
		$table = JTable::getInstance('Log', 'ProjectlogTable');
		if ($table->load(array('title' => $this->title, 'project_id' => $this->project_id)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('COM_PROJECTLOG_ERROR_UNIQUE_TITLE'));

			return false;
		}

		return parent::store($updateNulls);
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see JTable::check
	 * @since 1.5
	 */
	public function check()
	{
		/** check for valid name */
		if (trim($this->title) == '')
		{
			$this->setError(JText::_('COM_PROJECTLOG_WARNING_PROVIDE_VALID_NAME'));

			return false;
		}

		/** check for valid category */
		if (trim($this->project_id) == '')
		{
			$this->setError(JText::_('COM_PROJECTLOG_WARNING_PROJECT'));

			return false;
		}

		// Check the publish down date is not earlier than publish up.
		if ((int) $this->publish_down > 0 && $this->publish_down < $this->publish_up)
		{
			$this->setError(JText::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

			return false;
		}

		return true;
	}
    
    protected function _getAssetName() 
    {
        $k = $this->_tbl_key;
        return 'com_projectlog.log.'.(int) $this->$k;
    }

    protected function _getAssetParentId(JTable $table = null, $id = null)
    {
        $asset = JTable::getInstance('Asset');
        $asset->loadByName('com_projectlog');
        return $asset->id;
    }
}
