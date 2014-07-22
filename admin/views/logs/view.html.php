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
 * View class for a list of projects.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 * @since       1.6
 */
class ProjectlogViewLogs extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		ProjectlogHelper::addSubmenu('logs');

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
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo	= JHelperContent::getActions('com_projectlog', 'project', $this->state->get('filter.project_id'));
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_PROJECTLOG_MANAGER_LOGS'), 'address log');

		if ($canDo->get('projectlog.createlog') || (count($user->getAuthorisedCategories('com_projectlog', 'core.create'))) > 0)
		{
			JToolbarHelper::addNew('log.add');
		}

		if (($canDo->get('projectlog.editlog')) || ($canDo->get('projectlog.editlog.own')))
		{
			JToolbarHelper::editList('log.edit');
		}

		if ($canDo->get('projectlog.editlog.state'))
		{
			JToolbarHelper::publish('logs.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('logs.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('logs.archive');
			JToolbarHelper::checkin('logs.checkin');
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('projectlog.deletelog'))
		{
			JToolbarHelper::deleteList('', 'logs.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('projectlog.editlog.state'))
		{
			JToolbarHelper::trash('logs.trash');
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

		/*JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PROJECT'),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('project.options', 'com_projectlog'), 'value', 'text', $this->state->get('filter.project_id'))
		);*/

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);

	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'project_name' => JText::_('COM_PROJECTLOG_PROJECT'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
