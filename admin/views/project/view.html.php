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
 * View to edit a project.
 *
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogViewProject extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;
    
    protected $logs;
    
    protected $user;

	/**
	 * Display the view
     * 
     * @return  void
     * 
     * @since   3.3.1
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
        $this->logs     = $this->get('Logs');
        $this->user     = JFactory::getUser();
        
        $this->canDo	= ProjectlogHelper::getActions('com_projectlog', 'project', $this->item->id);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($this->getLayout() == 'modal')
		{
			$this->form->setFieldAttribute('language', 'readonly', 'true');
			$this->form->setFieldAttribute('catid', 'readonly', 'true');
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   3.3.1
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);		

		JToolbarHelper::title(JText::_('COM_PROJECTLOG_MANAGER_PROJECT'), 'address project');

		// Build the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($isNew && (count($user->getAuthorisedCategories('com_projectlog', 'core.create')) > 0))
			{
				JToolbarHelper::apply('project.apply');
				JToolbarHelper::save('project.save');
				JToolbarHelper::save2new('project.save2new');
			}

			JToolbarHelper::cancel('project.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($this->canDo->get('core.edit') || ($this->canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('project.apply');
					JToolbarHelper::save('project.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($this->canDo->get('core.create'))
					{
						JToolbarHelper::save2new('project.save2new');
					}
				}
			}

			// If checked out, we can still save
			if ($this->canDo->get('core.create'))
			{
				JToolbarHelper::save2copy('project.save2copy');
			}

			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_projectlog.project', $this->item->id);
			}

			JToolbarHelper::cancel('project.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
