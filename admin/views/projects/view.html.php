<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class projectlogViewprojects extends JView {

	function display($tpl = null)
	{
		global $mainframe, $option;

		//initialise variables
		$user 		= & JFactory::getUser();
		$db 		= & JFactory::getDBO();
		$document	= & JFactory::getDocument();
		$settings   = & JComponentHelper::getParams( 'com_projectlog' );
		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.projects.filter_order', 'filter_order', 'release_date', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.projects.filter_order_Dir', 'filter_order_Dir', 'DESC', 'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.projects.filter_state', 'filter_state', '*', 'word' );
		$filter 			= $mainframe->getUserStateFromRequest( $option.'.projects.filter', 'filter', '', 'int' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.projects.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );
        $category 			= $mainframe->getUserStateFromRequest( $option.'.projects.category', 'category', '', 'string' );
        $group  			= $mainframe->getUserStateFromRequest( $option.'.projects.group_access', 'group_access', '', 'string' );
		$template			= $mainframe->getTemplate();

		JHTML::_('behavior.tooltip');

		//create the toolbar
		JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'PROJECTS' ) . ']</span>', 'projectlog' );
        JToolbarHelper::preferences('com_projectlog', '570');
        JToolBarHelper::spacer();
        JToolBarHelper::custom( 'edit_css', 'css.png', 'css_f2.png', 'EDIT CSS', false, false );
        JToolBarHelper::divider();
        JToolBarHelper::publishList();
		JToolBarHelper::spacer();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
        JToolBarHelper::custom( 'approve', 'new.png', 'new.png', JText::_( 'APPROVE' ), true );
        JToolBarHelper::spacer();
        JToolBarHelper::custom( 'disapprove', 'cancel.png', 'cancel.png', JText::_( 'DISAPPROVE' ), true );
        JToolBarHelper::divider();

		JToolBarHelper::addNew();
		JToolBarHelper::spacer();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::deleteList(JText::_('CONFIRM PROJECT DELETE'));
		JToolBarHelper::spacer();

		// Get data from the model
		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );

		//publish unpublished filter
		$lists['state']	= JHTML::_('grid.state', $filter_state );
        $lists['categories'] = projectlogHTML::catSelect('category', 'size="1" class="inputbox"', $category);
        $lists['groups'] = projectlogHTML::groupSelect('group_access', 'size="1" class="inputbox"', $group);

		$filters = array();
		$filters[] = JHTML::_('select.option', '1', JText::_( 'TITLE' ) );
        $filters[] = JHTML::_('select.option', '2', JText::_( 'RELEASE #' ) );
        $filters[] = JHTML::_('select.option', '3', JText::_( 'JOB #' ) );
        $filters[] = JHTML::_('select.option', '4', JText::_( 'TASK #' ) );
        $filters[] = JHTML::_('select.option', '5', JText::_( 'WORKORDER #' ) );
        $filters[] = JHTML::_('select.option', '6', JText::_( 'DESCRIPTION' ) );
		$lists['filter'] = JHTML::_('select.genericlist', $filters, 'filter', 'size="1" class="inputbox"', 'value', 'text', $filter );

		// search filter
		$lists['search']= $search;
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('ordering'		, $ordering);
		$this->assignRef('user'			, $user);
		$this->assignRef('template'		, $template);

        $iconstyle = '<style type="text/css">
                        .green, a.green{color: #37bf0d !important;}
                        .orange, a.orange{color: #f79c0e !important;}
                        .red, a.red{color: #ff0000 !important;}
                        .icon-48-projectlog{ background-image: url(components/com_projectlog/assets/images/icon-48-projectlog.png);}
                      </style>';
        $mainframe->addCustomHeadTag($iconstyle);
		
		parent::display($tpl);
	}
}
?>