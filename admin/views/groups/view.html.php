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

class projectlogViewgroups extends JView {

	function display($tpl = null)
	{
		global $mainframe, $option;

		//initialise variables
		$user 		= & JFactory::getUser();
		$db 		= & JFactory::getDBO();
		$document	= & JFactory::getDocument();
		$settings   = & JComponentHelper::getParams( 'com_projectlog' );
		//get vars
		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.groups.filter_order', 'filter_order', 'name', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.groups.filter_order_Dir', 'filter_order_Dir', 'ASC', 'word' );
		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.groups.filter_state', 'filter_state', '*', 'word' );
		$filter 			= $mainframe->getUserStateFromRequest( $option.'.groups.filter', 'filter', '', 'int' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.groups.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );
		$template			= $mainframe->getTemplate();

		JHTML::_('behavior.tooltip');

		//create the toolbar
		JToolBarHelper::title( '<span style="color: #fea100;">'.JText::_( 'PROJECT LOG' ) . '</span> <span style="font-size: 14px;">[' . JText::_( 'GROUPS' ) . ']</span>', 'projectlog' );
		JToolBarHelper::addNew();
		JToolBarHelper::spacer();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::deleteList(JText::_('CONFIRM GROUP DELETE'));
		JToolBarHelper::spacer();

		// Get data from the model
		$rows      	= & $this->get( 'Data');
		$pageNav 	= & $this->get( 'Pagination' );

		$filters = array();
		$filters[] = JHTML::_('select.option', '1', JText::_( 'TITLE' ) );
		$lists['filter'] = JHTML::_('select.genericlist', $filters, 'filter', 'size="1" class="inputbox"', 'value', 'text', $filter );

		// search filter
		$lists['search']= $search;
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('user'			, $user);
		$this->assignRef('template'		, $template);

        $iconstyle = '<style type="text/css">.icon-48-projectlog{ background-image: url(components/com_projectlog/assets/images/icon-48-projectlog.png);}</style>';
        $mainframe->addCustomHeadTag($iconstyle);
		
		parent::display($tpl);
	}
}
?>