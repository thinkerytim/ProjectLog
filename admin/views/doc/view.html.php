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
 * View to edit a project.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 * @since       1.6
 */
class ProjectlogViewDoc extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;
    
    protected $user;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
        $this->user     = JFactory::getUser();
        
        $this->canDo	= ProjectlogHelper::getActions('com_projectlog', 'project', $this->item->project_id);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($this->getLayout() == 'modal')
		{
			$this->form->setFieldAttribute('language', 'readonly', 'true');
			$this->form->setFieldAttribute('project_id', 'readonly', 'true');
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);		

		JToolbarHelper::title(JText::_('COM_PROJECTLOG_MANAGER_DOC'), 'list doc');

		// Build the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($isNew && $this->canDo->get('projectlog.createdoc'))
			{
				JToolbarHelper::apply('doc.apply');
				JToolbarHelper::save('doc.save');
				JToolbarHelper::save2new('doc.save2new');
			}

			JToolbarHelper::cancel('doc.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($this->canDo->get('projectlog.editdoc') || ($this->canDo->get('projectlog.editdoc.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('doc.apply');
					JToolbarHelper::save('doc.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($this->canDo->get('projectlog.createdoc'))
					{
						JToolbarHelper::save2new('doc.save2new');
					}
				}
			}

			JToolbarHelper::cancel('doc.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
