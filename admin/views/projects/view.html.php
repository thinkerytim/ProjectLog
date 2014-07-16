<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class ProjectlogViewProjects extends JViewLegacy 
{
    protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
        // Initialise variables.
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

        // Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raise(E_ERROR, 500, implode("\n", $errors));
			return false;
		}
        
        // Load the submenu and admin menu drop down        
		ProjectlogHelper::addSubmenu(JFactory::getApplication()->input->getCmd('view', 'projects'));        

		$this->addToolbar();
        $this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

    protected function addToolbar()
	{
		$canDo	= ProjectlogHelper::getActions($this->state->get('filter.category_id'));
        
        JToolBarHelper::title(JText::_('COM_PROJECTLOG_PROJECTS'), 'projectlog.png');
        
        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('project.add','JTOOLBAR_NEW');
        }              
        
        if ($canDo->get('core.edit')) {
            JToolBarHelper::editList('project.edit','JTOOLBAR_EDIT');
            JToolBarHelper::divider(); 
            JToolBarHelper::publishList('projects.publish', 'JTOOLBAR_PUBLISH');
            JToolBarHelper::unpublishList('projects.unpublish', 'JTOOLBAR_UNPUBLISH');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::divider();
            JToolBarHelper::deleteList('', 'projects.delete','JTOOLBAR_EMPTY_TRASH');			
		}
		else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
            JToolBarHelper::trash('projects.trash','JTOOLBAR_TRASH');			
		}
        
        // Add search filters
		JHtmlSidebar::setAction('index.php?option=com_projectlog&view=projects');
        
        JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived'=>false, 'trash'=>false, 'all'=>false)), 'value', 'text', $this->state->get('filter.state'), true)
		);
	}
    
    protected function getSortFields()
	{
		return array(
			'title' => JText::_('COM_PROJECTLOG_TITLE'),
			'id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
?>