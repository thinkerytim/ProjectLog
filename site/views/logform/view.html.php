<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * HTML Log Edit View class for the Projectlog component
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogViewLogform extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $return_page;

	protected $state;
    

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
     * @since   3.3.1
	 */
	public function display($tpl = null)
	{
		$user = JFactory::getUser();

		// Get model data.
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');
		$this->return_page	= $this->get('ReturnPage');
        
        // Make sure to include admin language file for form fields
        JFactory::getLanguage()->load('com_projectlog', JPATH_ADMINISTRATOR);
        JFactory::getLanguage()->load('', JPATH_ADMINISTRATOR);

		if (empty($this->item->id))
		{
			$authorised = $user->authorise('core.create', 'com_projectlog') || $user->authorise('projectlog.createlog', 'com_projectlog.project.' . $this->state->get('log.project_id'));
		}
		else
		{
			$authorised = $this->item->params->get('access-edit');
		}

		if ($authorised !== true)
		{
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// Create a shortcut to the parameters.
		$params	= &$this->state->params;

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->params = $params;

		// Override global params with article specific params
		$this->params->merge($this->item->params);
		$this->user   = $user;

		$this->_prepareDocument();
		parent::display($tpl);
	}

	/**
	 * Prepares the document
     * 
     * @return  void
     * @since   3.3.1
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$title 		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_PROJECTLOG_FORM_EDIT_LOG'));
		}

		$title = $this->params->def('page_title', JText::_('COM_PROJECTLOG_FORM_EDIT_LOG'));

		if ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		$pathway = $app->getPathWay();
		$pathway->addItem($title, '');

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}
