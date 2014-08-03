<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/models/category.php';

/**
 * HTML Project View class for the Projectlog component
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogViewProject extends JViewLegacy
{
	protected $state;
	protected $form;
	protected $item;
	protected $return_page;
    protected $logs; 
    protected $docs; 

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
		$app            = JFactory::getApplication();
		$user           = JFactory::getUser();
		$state          = $this->get('State');
		$item           = $this->get('Item');
		$this->form     = $this->get('Form');
        $this->logs     = $this->get('Logs');
        $this->docs     = $this->get('Docs');
        
        $this->canDo	= ProjectlogHelper::getActions('com_projectlog', 'project', $item->id);

		// Get the parameters
		$params = JComponentHelper::getParams('com_projectlog');

		if ($item)
		{
			// If we found an item, merge the item parameters
			$params->merge($item->params);

			// Get Category Model data
			$categoryModel = JModelLegacy::getInstance('Category', 'ProjectlogModel', array('ignore_request' => true));
			$categoryModel->setState('category.id', $item->catid);
			$categoryModel->setState('list.ordering', 'a.name');
			$categoryModel->setState('list.direction', 'asc');
			$categoryModel->setState('filter.published', 1);

			$projects = $categoryModel->getItems();
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

		// check if access is not public
		$groups	= $user->getAuthorisedViewLevels();

		$return = '';

		if ((!in_array($item->access, $groups)) || (!in_array($item->category_access, $groups)))
		{
			JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}

		$options['category_id']	= $item->catid;
		$options['order by']	= 'a.default_con DESC, a.ordering ASC';

		// Handle email cloaking
		if ($item->email_to && $params->get('show_email'))
		{
			$item->email_to = JHtml::_('email.cloak', $item->email_to);
		}

		// Add links to projects
		if ($params->get('show_project_list') && count($projects) > 1)
		{
			foreach ($projects as &$project)
			{
				$project->link = JRoute::_(ProjectlogHelperRoute::getProjectRoute($project->slug, $project->catid));
			}
			$item->link = JRoute::_(ProjectlogHelperRoute::getProjectRoute($item->slug, $item->catid));
		}

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->project  = &$item;
		$this->params   = &$params;
		$this->return   = &$return;
		$this->state    = &$state;
		$this->item     = &$item;
		$this->user     = &$user;
		$this->projects = &$projects;

		$item->tags = new JHelperTags;
		$item->tags->getItemTags('com_projectlog.project', $this->item->id);

		// Override the layout only if this is not the active menu item
		// If it is the active menu item, then the view and item id will match
		$active	= $app->getMenu()->getActive();
		if ((!$active) || ((strpos($active->link, 'view=project') === false) || (strpos($active->link, '&id=' . (string) $this->item->id) === false)))
		{
			if ($layout = $params->get('project_layout'))
			{
				$this->setLayout($layout);
			}
		}
		elseif (isset($active->query['layout']))
		{
			// We need to set the layout in case this is an alternative menu item (with an alternative layout)
			$this->setLayout($active->query['layout']);
		}

		$model = $this->getModel();
		$model->hit();
		$this->_prepareDocument();

		return parent::display($tpl);
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
		$pathway	= $app->getPathway();
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
			$this->params->def('page_heading', JText::_('COM_PROJECTLOG_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		$id = (int) @$menu->query['id'];

		// if the menu item does not concern this project
		if ($menu && ($menu->query['option'] != 'com_projectlog' || $menu->query['view'] != 'project' || $id != $this->item->id))
		{

			// If this is not a single project menu item, set the page title to the project title
			if ($this->item->name)
			{
				$title = $this->item->name;
			}
			$path = array(array('title' => $this->project->name, 'link' => ''));
			$category = JCategories::getInstance('Projectlog')->get($this->project->catid);

			while ($category && ($menu->query['option'] != 'com_projectlog' || $menu->query['view'] == 'project' || $id != $category->id) && $category->id > 1)
			{
				$path[] = array('title' => $category->title, 'link' => ProjectlogHelperRoute::getCategoryRoute($this->project->catid));
				$category = $category->getParent();
			}

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		if (empty($title))
		{
			$title = $this->item->name;
		}
		$this->document->setTitle($title);

		if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif (!$this->item->metadesc && $this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif (!$this->item->metakey && $this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		$mdata = $this->item->metadata->toArray();

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$this->document->setMetadata($k, $v);
			}
		}
	}
}
