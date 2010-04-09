<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class projectlogViewcat extends JView
{
	function display($tpl = null)
	{
		global $option, $mainframe;
		JHTML::_('behavior.tooltip');
        JHTML::_('behavior.modal', 'a.modal');
	
		$db 		   = & JFactory::getDBO();
        $user 		   = & JFactory::getUser();
        $this->baseurl = JURI::base();
        $document 	   = &JFactory::getDocument();
		$settings      = &JComponentHelper::getParams( 'com_projectlog' );
        $pathway	   = &$mainframe->getPathway();
        $document->addStyleSheet($this->baseurl.'components/com_projectlog/assets/css/projectlog.css');

        $model          = &$this->getModel();
        $catinfo	    = &$this->get('data');
		$projects		= &$this->get('projects');
		$pagination     = &$this->get('Pagination');

		$lists = array();
        
        $filter_order		= $mainframe->getUserStateFromRequest( $option.'.cat.filter_order', 'filter_order', 'p.release_date', 'cmd' );
		$filter_order_dir	= $mainframe->getUserStateFromRequest( $option.'.cat.filter_order_dir', 'filter_order_Dir', 'DESC', 'word' );
        $filter 			= $mainframe->getUserStateFromRequest( $option.'.cat.filter', 'filter', '', 'int' );
        $search 			= $mainframe->getUserStateFromRequest( $option.'.cat.search', 'search', '', 'string' );
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

        $filters = array();
		$filters[] = JHTML::_('select.option', '1', JText::_( 'PROJECT NAME' ) );
		$filters[] = JHTML::_('select.option', '2', JText::_( 'RELEASE NUM' ) );
		$lists['filter'] = JHTML::_('select.genericlist', $filters, 'filter', 'size="1" class="inputbox"', 'value', 'text', $filter );
        $lists['search']= $search;
        $lists['order']= $filter_order;
        $lists['order_Dir']= $filter_order_dir;

        $this->assignRef('catinfo', $catinfo);
        $this->assignRef('projects', $projects);
		$this->assignRef('lists', $lists);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('settings', $settings);
        $this->assignRef('user', $user);
        parent::display($tpl);
	}
}

?>
