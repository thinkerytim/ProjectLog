<?php
/**
 * @version 1.5.1 2009-03-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C)  2009 the Thinkery
 * @license GNU/GPL
 * @link http://www.thethinkery.net
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');

jimport( 'joomla.application.component.view');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');

class ProjectlogViewProjectlog extends JView
{
	function display($tpl = null)
	{
        global $mainframe, $option;
		JHTML::_('behavior.tooltip');
        JHTML::_('behavior.calendar');
        $user = JFactory::getUser();
        $db 		= & JFactory::getDBO();
        $params     = &JComponentHelper::getParams( 'com_projectlog' );
        $this->basic_access = $params->get('basic_access');
        $this->log_access = $params->get('log_access');
        $this->doc_access = $params->get('doc_access');
        $this->edit_access = $params->get('edit_access');
	
		$document 	= & JFactory::getDocument();
		$joomla_base = $this->baseurl;
		$document->addStyleSheet($joomla_base.'/components/com_projectlog/css/default.css');

	
		$model = &$this->getModel();
		$projects = &$this->get('data');
		$pagination = &$this->get('Pagination');
		$document->setTitle( 'Current Projects' );

        $filter_order		= $mainframe->getUserStateFromRequest( $option.'.projectlog.filter_order', 'filter_order', 'p.release_date', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.projectlog.filter_order_Dir', 'filter_order_Dir', 'DESC', 'word' );
		$filter 			= $mainframe->getUserStateFromRequest( $option.'.projectlog.filter', 'filter', '', 'int' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

        $lists = array();
        $filters = array();
		$filters[] = JHTML::_('select.option', '1', JText::_( 'Project Name' ) );
		$filters[] = JHTML::_('select.option', '2', JText::_( 'Release Number' ) );
		$lists['filter'] = JHTML::_('select.genericlist', $filters, 'filter', 'size="1" class="inputbox"', 'value', 'text', $filter );

		// search filter
		$lists['search']= $search;

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		
		$this->assignRef('user', $user);
        $this->assignRef('projects', $projects);
		$this->assignRef('pagination', $pagination);
        $this->assignRef('lists', $lists);
        
        parent::display($tpl);
    }
}
