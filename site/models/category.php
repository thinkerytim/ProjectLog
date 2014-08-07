<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * This models supports retrieving lists of projects in a category.
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogModelCategory extends JModelList
{
	protected $_item = null;

	protected $_articles = null;

	protected $_siblings = null;

	protected $_children = null;

	protected $_parent = null;

	protected $_category = null;

	protected $_categories = null;

	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
     * @return  ProjectlogModelCategory
	 * @see     JController
	 * @since       3.3.1
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'manager', 'a.manager', 'manager_name',
				'project_type', 'a.project_type',
				'client', 'a.client',
				'release_id', 'a.release_id',
				'ordering', 'a.ordering',
				'sortname',
				'sortname1', 'a.sortname1',
				'sortname2', 'a.sortname2',
				'sortname3', 'a.sortname3',
                'cat_id', 'c.id',
                'job_id', 'a.job_id',
                'task_id', 'a.task_id',
                'workorder_id', 'a.workorder_id',
                'project_type', 'a.project_type'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get a list of items.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
     * @since       3.3.1
	 */
	public function getItems()
	{
		$user = JFactory::getUser();

        // Invoke the parent getItems method to get the main list
		$items = parent::getItems();

		// Convert the params field into an object, saving original in _params
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item = & $items[$i];
			if (!isset($this->_params))
			{
				$params = new JRegistry;
				$params->loadString($item->params);
				$item->params = $params;
			}
			$this->tags = new JHelperTags;
			$this->tags->getItemTags('com_projectlog.project', $item->id);
            
            // Technically guest could edit a project, but lets not check that to improve performance a little.
            if (!$user->get('guest'))
            {
                $userId = $user->get('id');
                $asset = 'com_projectlog.project.' . $item->id;

                // Check general edit permission first.
                if ($user->authorise('core.edit', $asset))
                {
                    $item->params->set('access-edit', true);
                }

                // Now check if edit.own is available.
                elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
                {
                    // Check for a valid user and that they are the owner.
                    if ($userId == $item->created_by)
                    {
                        $item->params->set('access-edit', true);
                    }
                }
            }

		}

		return $items;
	}

	/**
	 * Method to build a SQL query to load the list data.
	 *
	 * @return  string    An SQL query
	 * @since       3.3.1
	 */
	protected function getListQuery()
	{
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields from the categories.
		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('a.alias', '!=', '0');
		$case_when .= ' THEN ';
		$a_id = $query->castAsChar('a.id');
		$case_when .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $a_id . ' END as slug';

		$case_when1 = ' CASE WHEN ';
		$case_when1 .= $query->charLength('c.alias', '!=', '0');
		$case_when1 .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when1 .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when1 .= ' ELSE ';
		$case_when1 .= $c_id . ' END as catslug';
		$query->select($this->getState('list.select', 'a.*') . ',' . $case_when . ',' . $case_when1)
			->from($db->quoteName('#__projectlog_projects') . ' AS a')
			->join('LEFT', '#__categories AS c ON c.id = a.catid')
			->where('a.access IN (' . $groups . ')');

		// Filter by category.
		if ($categoryId = $this->getState('category.id'))
		{
			$query->where('a.catid = ' . (int) $categoryId)
				->where('c.access IN (' . $groups . ')');
		}

		// Join over the users for the author and modified_by names.
		$query->select("CASE WHEN a.created_by_alias > ' ' THEN a.created_by_alias ELSE ua.name END AS author")
			->select("ua.email AS author_email")
            ->select("plman.name AS manager_name")

			->join('LEFT', '#__users AS ua ON ua.id = a.created_by')
            ->join('LEFT', '#__users AS plman ON plman.id = a.manager')
			->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

		// Filter by state
		$state = $this->getState('filter.published');

		if (is_numeric($state))
		{
			$query->where('a.published = ' . (int) $state);
		}
		// Filter by start and end dates.
		$nullDate = $db->quote($db->getNullDate());
		$nowDate = $db->quote(JFactory::getDate()->toSql());

		if ($this->getState('filter.publish_date'))
		{
			$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')')
				->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
		}

		// Filter by search in title
		$search = $this->getState('list.filter');
		if (!empty($search))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(a.name LIKE ' . $search . ')');
		}

		// Filter by language
		if ($this->getState('filter.language'))
		{
			$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		// Set sortname ordering if selected
		if ($this->getState('list.ordering') == 'sortname')
		{
			$query->order($db->escape('a.sortname1') . ' ' . $db->escape($this->getState('list.direction', 'ASC')))
				->order($db->escape('a.sortname2') . ' ' . $db->escape($this->getState('list.direction', 'ASC')))
				->order($db->escape('a.sortname3') . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
		}
		else
		{
			$query->order($db->escape($this->getState('list.ordering', 'a.ordering')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
		}

        //echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since       3.3.1
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_projectlog');

		// List state information
		$format = $app->input->getWord('format');
		if ($format == 'feed')
		{
			$limit = $app->getCfg('feed_limit');
		}
		else
		{
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
		}
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $limitstart);

		// Optional filter text
		$this->setState('list.filter', $app->input->getString('filter-search'));

		// Get list ordering default from the parameters
		$menuParams = new JRegistry;
		if ($menu = $app->getMenu()->getActive())
		{
			$menuParams->loadString($menu->params);
		}
		$mergedParams = clone $params;
		$mergedParams->merge($menuParams);

		$orderCol = $app->input->get('filter_order', $mergedParams->get('initial_sort', 'ordering'));
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'ordering';
		}
		$this->setState('list.ordering', $orderCol);

		$listOrder = $app->input->get('filter_order_Dir', 'ASC');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);

		$id = $app->input->get('id', 0, 'int');
		$this->setState('category.id', $id);

		$user = JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_projectlog')) && (!$user->authorise('core.edit', 'com_projectlog')))
		{
			// limit to published for people who can't edit or edit.state.
			$this->setState('filter.published', 1);

			// Filter by start and end dates.
			$this->setState('filter.publish_date', true);
		}
		$this->setState('filter.language', JLanguageMultilang::isEnabled());

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get category data for the current category
	 *
	 * @param   integer  An optional ID
	 *
	 * @return  object
	 * @since       3.3.1
	 */
	public function getCategory()
	{
		if (!is_object($this->_item))
		{
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry;

			if ($active)
			{
				$params->loadString($active->params);
			}

			$options = array();
			$options['countItems'] = $params->get('show_cat_items', 1) || $params->get('show_empty_categories', 0);
			$categories = JCategories::getInstance('Projectlog', $options);
			$this->_item = $categories->get($this->getState('category.id', 'root'));
			
            if (is_object($this->_item))
			{
				$user	= JFactory::getUser();
				$asset	= 'com_projectlog.category.'.$this->_item->id;

				// Check general create permission.
				if ($user->authorise('core.create', $asset))
				{
					$this->_item->getParams()->set('access-create', true);
				}
                
                $this->_children = $this->_item->getChildren();
				$this->_parent = false;
                
				if ($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}
                
				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			}
			else
			{
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}

	/**
	 * Get the parent category.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
     * @since       3.3.1
	 */
	public function getParent()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_parent;
	}

	/**
	 * Get the sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
     * @since       3.3.1
	 */
	function &getLeftSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_leftsibling;
	}

    /**
	 * Get the sibling (adjacent) categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
     * @since       3.3.1
	 */
	function &getRightSibling()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_rightsibling;
	}

	/**
	 * Get the child categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
     * @since       3.3.1
	 */
	function &getChildren()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}
		return $this->_children;
	}

	/**
	 * Increment the hit counter for the category.
	 *
	 * @param   integer  $pk  Optional primary key of the category to increment.	 *
	 * @return  boolean  True if successful; false otherwise and internal error set.
	 *
	 * @since       3.3.1
	 */
	public function hit($pk = 0)
	{
		$input = JFactory::getApplication()->input;
		$hitcount = $input->getInt('hitcount', 1);

		if ($hitcount)
		{
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('category.id');

			$table = JTable::getInstance('Category', 'JTable');
			$table->load($pk);
			$table->hit($pk);
		}

		return true;
	}
}
