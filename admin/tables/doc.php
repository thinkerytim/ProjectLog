<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 */
class ProjectlogTableDoc extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  Database connector object
	 *
	 * @return  ProjectlogTableDoc
	 * @since   3.3.1
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__projectlog_docs', 'id', $db);

		JTableObserverTags::createObserver($this, array('typeAlias' => 'com_projectlog.doc'));
		JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_projectlog.doc'));
	}

	/**
	 * Stores a document
	 *
	 * @param   boolean  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   3.3.1
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
			// New documnet. A document created and created_by field can be set by the user,
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
		$table = JTable::getInstance('Doc', 'ProjectlogTable');
		if ($table->load(array('title' => $this->title, 'project_id' => $this->project_id, 'language' => $this->language)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->title = JString::increment($this->title);
		}

		return parent::store($updateNulls);
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see JTable::check
	 * @since 3.3.1
	 */
	public function check()
	{
		/** check for valid name */
		if (trim($this->title) == '')
		{
			$this->setError(JText::_('COM_PROJECTLOG_WARNING_PROVIDE_VALID_NAME'));

			return false;
		}

		/** check for valid project */
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
    
    /**
	 * Method to compute the default name of the asset.
	 * The default name is in the form table_name.id
	 * where id is the value of the primary key of the table.
	 *
	 * @return  string
	 *
	 * @since   3.3.1
	 */   
    protected function _getAssetName() 
    {
        $k = $this->_tbl_key;
        return 'com_projectlog.doc.'.(int) $this->$k;
    }
    
    /**
     * We provide our global ACL as parent
     * @see JTable::_getAssetParentId()
     * 
	 * Method to get the parent asset under which to register this one.
	 * By default, all assets are registered to the ROOT node with ID,
	 * which will default to 1 if none exists.
	 * The extended class can define a table and id to lookup.  If the
	 * asset does not exist it will be created.
	 *
	 * @param   JTable   $table  A JTable object for the asset parent.
	 * @param   integer  $id     Id to look up
	 *
	 * @return  integer
	 *
	 * @since   3.3.1
	 */
    protected function _getAssetParentId(JTable $table = null, $id = null)
    {
        $asset = JTable::getInstance('Asset');
        $asset->loadByName('com_projectlog');
        return $asset->id;
    }
}
