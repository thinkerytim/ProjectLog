<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of projects.
 *
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogViewProjects extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
     * 
     * @return void
     * 
     * @since 3.3.1
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		ProjectlogHelper::addSubmenu('projects');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
		foreach ($this->items as &$item)
		{
			$item->order_up = true;
			$item->order_dn = true;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   3.3.1
	 */
	protected function addToolbar()
	{
		$canDo	= JHelperContent::getActions('com_projectlog', 'category', $this->state->get('filter.category_id'));
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_PROJECTLOG_MANAGER_PROJECTS'), 'book project');

		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_projectlog', 'core.create'))) > 0)
		{
			JToolbarHelper::addNew('project.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own')))
		{
			JToolbarHelper::editList('project.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('projects.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('projects.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('projects.archive');
			JToolbarHelper::checkin('projects.checkin');
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'projects.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('projects.trash');
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_projectlog') && $user->authorise('core.edit', 'com_projectlog') && $user->authorise('core.edit.state', 'com_projectlog'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($user->authorise('core.admin', 'com_projectlog'))
		{
			JToolbarHelper::preferences('com_projectlog');
		}

		JHtmlSidebar::setAction('index.php?option=com_projectlog');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
            JHtml::_('select.options', JHtml::_('category.options', 'com_projectlog', array('filter.published' => array(0, 1), 'filter.language' => ($this->state->get('filter.language')) ? array('*', $this->state->get('filter.language')) : false)), 'value', 'text', $this->state->get('filter.category_id'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);

		JHtmlSidebar::addFilter(
		JText::_('JOPTION_SELECT_TAG'),
		'filter_tag',
		JHtml::_('select.options', JHtml::_('tag.options', true, true), 'value', 'text', $this->state->get('filter.tag'))
		);

	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.3.1
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.name' => JText::_('JGLOBAL_TITLE'),
			'category_title' => JText::_('JCATEGORY'),
			'ul.name' => JText::_('COM_PROJECTLOG_FIELD_PROJECT_MANAGER_LABEL'),
			'a.featured' => JText::_('JFEATURED'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID'),
            'a.release_date' => JText::_('COM_PROJECTLOG_FIELD_RELEASE_DATE_LABEL'),
            'a.status' => JText::_('COM_PROJECTLOG_FIELD_PROJECT_STATUS_LABEL')
		);
	}
}
